{{-- HTML --}}
<!doctype html>
<html lang="{{ app()->getLocale() }}">

    {{-- Head --}}
    <head>

        {{-- Meta --}}
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">
        <meta name="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- SEO --}}
        @include('app.seo')

        {{-- Favicon --}}
        <link rel="icon"
              type="image/png"
              href="{{ asset('img/logo.png') }}">

        {{-- Font Awesome --}}
        <link rel="stylesheet"
              crossorigin="anonymous"
              referrerpolicy="no-referrer"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
              integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" />

        {{-- Vite --}}
        @vite(['resources/js/app.js'])

    </head>

    {{-- Body --}}
    <body class="leading-none {{ env('APP_DUSK') }}">

        {{-- Routes --}}
        @routes()

        {{-- Inertia --}}
        @inertia()

    </body>

</html>