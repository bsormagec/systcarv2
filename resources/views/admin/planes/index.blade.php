@extends('admin.layout.frontend')

@section('content')
<main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
  
          <ol>
            <li><a href="/">Inicio</a></li>
            <li>Planes</li>
          </ol>
          <h2>PLANES</h2>
  
        </div>
    </section><!-- End Breadcrumbs -->
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing">
        <div class="container" data-aos="fade-up">
            @component('components.alert')@endcomponent
            @if(!auth()->user()->isAdmin())
                @if(! auth()->user()->hasPaymentMethod())
                    <div class="alert alert-danger text-center">
                        <span class="fas fa-exclamation-circle"></span> 
                        {{ __("Todavía no has vinculado ninguna tarjeta a tu cuenta") }} 
                        <a href="{{route('billing.credit_card_form')}}">
                            {{ __("Házlo ahora") }}
                        </a>
                    </div>
                @endif
            @endif
            @if (count($plans))
                <div class="section-title">
                    <h2>Precios</h2>
                    <p>Puede Cambiar de plan en cualquier momento y tendra acceso a los demas modulos.</p>
                    <p>Su dominio se registrara de la siguiente manera farmacorp.systcarv.com</p>
                </div> 
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    @foreach($plans as $plan)
                    <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="200">
                        <div class="box">
                            <h3>{{ __($plan->nickname) }}</h3>
                            <h4><sup></sup>{{ __(":amount$", ["amount" => $plan->amount]) }}<span>{{ __("mensual") }}</span></h4>
                            <form action="{{ route("plans.purchase") }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plan->id }}">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="domain" class="form-control" name="domain" value="{{ old('domain') }}" placeholder="Nombre de dominio ej. farmacorp">
                                    </div>
                                </div>
                                <ul>
                                    <li><i class="bx bx-check"></i> Caja</li>
                                    <li><i class="bx bx-check"></i> Inventario</li>
                                    <li><i class="bx bx-check"></i> Ventas Contado</li>
                                    <li><i class="bx bx-check"></i> Compras</li>
                                    @if ($plan->slug != 'bronce')
                                    <li><i class="bx bx-check"></i> Ventas a Credito</li>
                                    @else
                                    <li class="na"><i class="bx bx-x"></i> <span>Ventas a Credito</span></li>
                                    @endif
                                    @if ($plan->slug === 'oro')
                                    <li><i class="bx bx-check"></i> <span>Facturacion Electronica</span></li>
                                    @else
                                    <li class="na"><i class="bx bx-x"></i> <span>Facturacion Electronica</span></li>
                                    @endif
                                </ul>
                                @if(!auth()->user()->isAdmin())
                                    @if( ! auth()->user()->hasIncompletePayment('main'))
                                        @if(auth()->user()->subscribed('main'))
                                            @if(auth()->user()->subscription('main')->stripe_plan === $plan->slug)
                                                <button type="button" disabled class="btn btn-block btn-primary text-uppercase">{{ __("Tu plan actual") }}</button>
                                            @else
                                                @if($priceCurrentPlan < $plan->amount)
                                                    <button disabled class="btn btn-block btn-dark text-uppercase">{{ __("No es posible cambiar de plan") }}</button>
                                                @else
                                                    <button type="button" disabled class="btn btn-block btn-dark text-uppercase">{{ __("No es posible bajar") }}</button>
                                                @endif
                                            @endif
                                        @else
                                            <button type="submit" class="buy-btn">{{ __("Suscribirme") }}</button>
                                        @endif
                                    @else
                                        @if(auth()->user()->subscription('main')->stripe_plan === $plan->slug)
                                            <a class="btn btn-block btn-info text-uppercase" href="">
                                                {{ __("Confirma tu pago aquí") }}
                                            </a>
                                        @else
                                            <button type="button" disabled class="btn btn-block btn-primary text-uppercase">{{ __("Esperando...") }}</button>
                                        @endif
                                    @endif
                                @endif
                            </form>    
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                @if(auth()->user()->isAdmin())
                    <div class="alert alert-danger text-center">
                        <span class="fas fa-exclamation-circle"></span> {{ __("No hay ningún plan disponible todavía") }} <a href="{{route('plans.create')}}">{{ __("Crea uno ahora ahora") }}</a>
                    </div>
                @endif
            @endif
            @if(!auth()->user()->isAdmin())
                {{-- tabla suscripción actual! --}}
                @include('admin.planes.current_subscription')
            @endif
        </div>
    </section><!-- End Pricing Section -->
</main>
@endsection