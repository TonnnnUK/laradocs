<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
        @if( config('app.env') == 'production')
        @if( auth()->guest() ||  ( auth()->user() && auth()->user()->email != 'a.hutchinson86@gmail.com') )
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-P5G83ZJN9J"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-P5G83ZJN9J');
        </script>
        @endif
        @endif


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bakbak-one:400" rel="stylesheet" />

        <style>
            h1 {
                font-display: optional;
                font-family: 'Bakbak One', Arial, display;
            }

            .icon-bg {
                background-image: url('https://laradocs.dev/img/faded-icons-bg.png');
                background-repeat: repeat-x;
            }
        </style>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100">
        <div class="min-h-screen relative icon-bg">
            
            <!-- Page Content -->
            <main class="relative">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
