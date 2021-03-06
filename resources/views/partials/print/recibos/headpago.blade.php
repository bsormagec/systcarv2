<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>@yield('title') - {{setting('company.name')}}</title>

    <base href="{{ config('app.url') . '/' }}">

    <!-- Favicon -->
    <link rel="icon" href="img/favicon.ico" type="image/png">

    <!-- Css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css
    " type="text/css">
    <link rel="stylesheet" href="css/recibo.css" type="text/css">

    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>

    @stack('css')

    @stack('stylesheet')

    @stack('js')

    @stack('scripts')

    @stack('head_end')
</head>
