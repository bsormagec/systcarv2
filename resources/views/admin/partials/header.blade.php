<header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">

      <h1 class="logo mr-auto"><a href="/">{{env('APP_NAME')}}</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo mr-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active"><a href="/">Inicio</a></li>
          <li><a href="#about">Sobre Nosotros</a></li>
          <li><a href="#services">Modulos</a></li>
          <li><a href="#portfolio">Proyectos</a></li>
          <li><a href="#team">Equipo</a></li>
          <li><a href="#pricing">Precios</a></li>
          <li class="drop-down"><a href="">Drop Down</a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="drop-down"><a href="#">Deep Drop Down</a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          <li><a href="#contact">Contacto</a></li>
          @auth
            <li class="drop-down"><a href="">{{\Auth::user()->name}}</a>
              <ul>
                @if (\Auth::user()->tenant()->exists())
                  <li>
                    @php
                        $tenant = \Auth::user()->tenant->domains[0]->domain;
                       
                    @endphp
                    <a href="//{{$tenant}}" target="_blank">
                       Administracion
                    </a>
                  </li>
                @endif
                <li><a href="{{route('profile.show')}}">Perfil</a></li>
                <li>
                  <a href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST">
                      @csrf
                  </form>
                </li>
              </ul>
            </li>
          @endauth
        </ul>
      </nav><!-- .nav-menu -->
      @if (Route::has('login'))
        @guest
        <a href="{{route('login')}}" class="get-started-btn scrollto">Login</a>
        @endguest
      @endif
      
    </div>
</header>