<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Cashier\Exceptions\IncompletePayment;
use App\Models\Tenant;
class PlanController extends Controller
{
    /**
     *
     * LISTADO DE PLANES PARA CONTRATAR
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $plans = Plan::all();
        $currentPlan = auth()->user()->subscription('main');
        $priceCurrentPlan = null;
        if ($currentPlan) {
            if ($currentPlan->active()) {
                $plan = Plan::whereSlug($currentPlan->stripe_plan)->first();
                $priceCurrentPlan = $plan->amount;
                if (! $plans->contains($plan)) {
                    $plans->push($plan);
                }
            }
        }
        return view("admin.planes.index", compact("plans", "priceCurrentPlan"));
    }

    /**
     *
     * FORMULARIO ALTA PLANES
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view("admin.planes.form");
    }

    /**
     *
     * CREAR NUEVOS PLANES EN STRIPE Y BD
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function store() {
        $this->validate(request(), [
            'plan_name' => 'required|unique:plans,nickname|string|max:200',
            'plan_price' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $plan = \Stripe\Plan::create([
                'currency' => env("CASHIER_CURRENCY"),
                'interval' => env("CASHIER_INTERVAL"),
                "product" => [
                    "name" => request('plan_name')
                ],
                'nickname' => request('plan_name'),
                'id' => Str::slug(request('plan_name')),
                'amount' => request('plan_price') * 100,
            ]);
            if ($plan) {
                Plan::create([
                    'product' => $plan->product,
                    'nickname' => request('plan_name'),
                    'amount' => request('plan_price'),
                    'slug' => $plan->id,
                    's3' => request('s3') ? true : false
                ]);
            }
            DB::commit();
            session()->flash('message', ['success', __('Plan dado de alta correctamente')]);
            return redirect(route('plans.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            $plan = \Stripe\Plan::retrieve(Str::slug(request('plan_name')));
            if ($plan) {
                $plan->delete();
            }
            //mostrar solo en desarrollo no en produccion
            session()->flash('message', ['danger', $exception->getMessage()]);
            return back()->withInput();
        }
    }

    /**
     *
     * CONTRATAR NUEVAS SUSCRIPCIONES
     *
     * @param $hash
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function purchase () {
        if ( ! auth()->user()->hasPaymentMethod()) {
            return back()->with('message', ['danger', __('No sabemos c??mo has llegado hasta aqu??, ??a??ade una tarjeta para contratar un plan!')]);
        }
       
        $planId = (int) request("plan");
        $domain = sprintf('%s.%s', request('domain'), env('APP_DOMAIN'));
        $this->validate(request(), [
            'plan' => 'required',
            'domain' => ['required', 'string', 'min:2', 'max:20', Rule::unique('domains')->where(function ($query) use ($domain) {
                return $query->where('domain', $domain);
            })],
        ],[
            'domain.required' => "El dominio es requerido",
            'domain.unique' => "Ese nombre de dominio ya est?? en uso"
        ]);

        //obtenemos el plan que se est?? intentando contratar
        $plan = Plan::find($planId);

        try {
            //nos aseguramos que el plan a contratar es el correcto
            if ($planId === $plan->id) {
                $user = User::find(auth()->id());
            
                $currentPlan = auth()->user()->subscription('main');
                $tenant = Tenant::create([
                    'plan' => $plan->nickname,
                    'domain'=> $domain,
                    'user_id' => auth()->user()->id
                ]);
                
                $tenant->domains()->create([
                    'domain' => $domain
                ]);

                // si no ha finalizado subimos el plan
                if ($currentPlan && ! $currentPlan->ended()) {
                    $currentPlanForCompare = Plan::whereSlug($currentPlan->stripe_plan)->first();
                    //comparamos los precios para saber que el pr??ximo plan tiene un precio superior
                    if ($currentPlanForCompare) {
                        if ($currentPlanForCompare->amount < $plan->amount) {
                            //subimos el plan y generamos la factura al momento!
                            auth()->user()->subscription('main')->swapAndInvoice($plan->slug);
                            return redirect(route("plans.index"))->with('message', ['info', __('Has cambiado al plan ' . $plan->nickname . ' correctamente, recuerda revisar tu correo electr??nico por si es necesario confirmar el pago')]);
                        }
                    }
                } else {
                    // si nunca ha contratado una suscripci??n
                    auth()->user()->newSubscription('main', $plan->slug)->create();
                    return redirect(route("plans.index"))->with('message', ['info', __('Te has suscrito al plan ' . $plan->nickname . ' correctamente, recuerda revisar tu correo electr??nico por si es necesario confirmar el pago')]);
                }
                 
                
            } else {
                return back()->with('message', ['info', __('El plan seleccionado parece no estar disponible')]);
            }
        } catch (IncompletePayment $exception) {
            session()->flash('message', ['success', __('Te has suscrito al plan ' . $plan->nickname . ' correctamente, ya puedes disfrutar de todas las ventajas')]);
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('plans.index')]
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }

        return abort(401);
    }

    /**
     *
     * REAUNUDAR SUSCRIPCIONES CANCELADAS PREVIAMENTE
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resumeSubscription () {
        $subscription = request()->user()->subscription(request('plan'));
        if ($subscription->cancelled()) {
            request()->user()->subscription(request('plan'))->resume();
            return back()->with('message', ['success', __("Has reanudado tu suscripci??n correctamente")]);
        }
        return back()->with('message', ['danger', __("La suscripci??n no se puede reanudar, consulta con el administrador")]);
    }

    /**
     *
     * CANCELAR SUSCRIPCIONES PARA QUE NO SE RENUEVEN AUTOM??TICAMENTE
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSubscription () {
        auth()->user()->subscription(request('plan'))->cancel();
        return back()->with('message', ['success', __("La suscripci??n se ha cancelado correctamente")]);
    }
}
