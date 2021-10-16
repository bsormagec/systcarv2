<?php

namespace Augusto\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\tenant\Setting;
use Illuminate\Support\Facades\Route;
use App\Models\tenant\Type;
use Augusto\Purchase\Http\Requests\UpdateGeneralSettingsRequest;

class SettingController extends Controller {
  
    public function general(){
        $form=[
            'company.name' => setting('company.name'),
            'company.address'=> setting('company.address'),
            'company.nit' => setting('company.nit')
        ];
        return response()->json([
            'form'=> $form
        ]);
    }

    public function updategeneral(UpdateGeneralSettingsRequest $request){
        Setting::where('key','company.name')
                ->update(['value' => $request->get('company.name')]);
        Setting::where('key','company.address')
                ->update(['value' => $request->get('company.address')]);
        Setting::where('key','company.nit')
                ->update(['value' => $request->get('company.nit')]);
        return response()->json([
            'saved'=> true
        ]);
    }

    public function invoices(){
        $form=[
            'invoices.invoice_the_sale' => setting('invoices.invoice_the_sale')? true:false
        ];
        return response()->json([
            'form'=> $form,
            'plan' => tenant('plan')
        ]);
    }

    public function updateinvoices(Request $request){
        Setting::where('key','invoices.invoice_the_sale')
                ->update(['value' => $request->get('invoices.invoice_the_sale')]);
        return response()->json([
            'saved'=> true
        ]);
    }

    public function default(){
        $form=[
            'typepayments' => Type::select('id','name','description')
                                    ->where('tipo','payment')
                                    ->get(),
            'default.type_payment_tocreditsales' => setting('default.type_payment_tocreditsales'),
            'default.type_payment_to_chash' => setting('default.type_payment_to_chash')
        ];
        return response()->json([
            'form'=> $form
        ]);
    }

    public function updatedefault(Request $request){
        Setting::where('key','default.type_payment_tocreditsales')
                ->update(['value' => $request->get('default.type_payment_tocreditsales')]);
        Setting::where('key','default.type_payment_to_chash')
                ->update(['value' => $request->get('default.type_payment_to_chash')]);
        return response()->json([
            'saved'=> true
        ]);
    }

    public function getinvoicelectronic(){
        return tenant('plan');
    }
}