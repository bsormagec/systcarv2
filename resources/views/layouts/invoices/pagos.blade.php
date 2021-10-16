<html lang="{{ app()->getLocale() }}">
    @include('partials.print.recibos.headpago')

    <body>
        @stack('body_start')

            @yield('content')

        @stack('body_end')
    </body>
</html>