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

        <title>@isset($page_title) {{ $page_title }} @endisset</title>
        @isset($page_description)
        <meta name="description" content="{{ $page_description }}">
        @endisset

        @if( Route::currentRouteName() == 'home')
        <meta property="og:title" content="LaraDocs" />
        <meta property="og:description" content="A documentation search tool for the Laravel ecosystem, associated frameworks & packages all in one place!" />
        <meta property="og:url" content="https://www.laradocs.dev" />
        <meta property="og:image" content="https://www.laradocs.dev/img/ogimage.png" />
        @endif 

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

            <div class="w-[70%] sm:w-[30%] md:w-auto mx-auto sm:fixed sm:right-2 sm:top-2 md:flex flex-col justify-center">
                <div class="p-2 mt-2 text-center">
        
                    @guest
                    <div class="bg-white p-2 rounded-lg flex gap-2 md:mb-4 text-sm justify-center">
                        <a href="/login" class="py-1 px-2 border border-red-600 rounded transition hover:bg-red-600 hover:text-white duration-100">Login</a>
                        <a href="/register" class="py-1 px-2 border border-gray-400 bg-gray-100 rounded transition hover:bg-gray-600 hover:text-white duration-100">Register</a>
                    </div>
                    @endguest
                    
                    @auth
                    <div class="bg-white p-2 rounded-lg gap-2 md:mb-4 text-sm justify-center w-auto flex flex-col">
                        <a href="/profile" class="py-1 px-2 border border-red-600 rounded transition hover:bg-red-600 hover:text-white duration-100">Profile</a>
                        @if(Auth::user()->email == 'a.hutchinson86@gmail.com')
                        <a href="/manage">Manage</a>
                        @endif
                    </div>
                    @endauth
        
                    @if( Route::currentRouteName() == 'home')
                    <div class="flex flex-col text-left text-xs mt-2 md:mt-4 bg-gray-50 border p-3 rounded-lg" 
                        x-data="{showList: false}"
                        x-init="
                            $nextTick(() => {
                                if(window.innerWidth > 768 ){
                                    showList = true;
                                }
                            });
                        "
                    >
                        <span class="underline" x-on:click="showList = !showList">Recent Updates</span>
                        <div class="flex flex-col mt-2 mb-4" x-cloak x-show="showList">
                            <span>&#9745; User accounts</span>
                            <span>&#9745; Saved filters</span>
                            <span>&#9745; Link history*</span>
                            <span>&#9745; Better search results</span>
                            <span>&#9745; Filter Groups*</span>
                            <span>&#9745; Common searches</span>
                        </div>
                        <span class="underline" x-on:click="showList = !showList">Todo List</span>
                        <div class="flex flex-col mt-2" x-cloak x-show="showList">
                            <span>Quick links*</span>
                            <span>Older doc versions</span>
                            <span class="mt-2"><small>*for registered users</small></span>
                        </div>
                    </div>
                    @endif
                </div>
        
                {{-- <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" class="bg-gray-200 w-36 h-36 mt-2 flex justify-center items-center text-center text-gray-400 text-sm mx-auto">
                    Sponsor <br> Opportunity
                </a> --}}
            </div>

            
            <div class="flex flex-col justify-between items-center md:py-4 lg:w-5/6 mx-auto mb-1">
            
                <div class="pt-6 px-6 text-gray-900">
                    <a href="/">
                    <h1 class="text-2xl lg:text-4xl text-center">
                        <span class="text-red-500">LARA</span><img class="w-8 h-8 inline" src="/img/book.svg" />DOCS
                    </h1>
                    </a>
    
                    @if( Route::currentRouteName() == 'home'  )
                    <p class="max-w-2xl my-6 text-xl text-center mx-auto">
                        A <strong>documentation search tool</strong> for the Laravel ecosystem, associated frameworks &amp; packages <i><u>all in one place</u></i>!
                    </p>
                    @endif
                </div>

                <!-- Page Content -->
                <main class="w-full relative">
                    {{ $slot }}
                </main>

            </div>

            <x-logos class="mt-8" />

    
            <div class="text-gray-400 text-xs hover:text-gray-900 transition duration-200 text-center">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" >Contact // Feedback // Suggestions // Sponsorship Enquiries</a>
            </div>
        </div>
    </body>
</html>
