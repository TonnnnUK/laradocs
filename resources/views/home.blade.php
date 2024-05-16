<x-app-layout>
    
    <div class="w-[70%] sm:w-[30%] md:w-auto mx-auto sm:fixed sm:right-2 sm:top-2 md:flex flex-col justify-center">
        <div class="p-2 mt-2 text-center">
            <small>Finding this useful?</small>
            <a target="_blank" href="https://buymeacoffee.com/tonhutchinson" 
                class="py-2 px-4 bg-yellow-300 text-gray-800 flex items-center justify-center gap-2 text-sm mx-auto rounded-lg hover:bg-yellow-400 transition duration-200"
            >
                <img class="w-4 h-4" src="/img/coffee.svg" /> <span>Buy me a coffee</span>
            </a>

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
                <span class="underline" x-on:click="showList = !showList">Upcoming improvements</span>
                <div class="flex flex-col mt-2" x-cloak x-show="showList">
                    <span>Saved filters</span>
                    <span>Better search results</span>
                    <span>Most popular topics</span>
                    <span>User accounts</span>
                </div>
            </div>
        
        </div>

        {{-- <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" class="bg-gray-200 w-36 h-36 mt-2 flex justify-center items-center text-center text-gray-400 text-sm mx-auto">
            Sponsor <br> Opportunity
        </a> --}}
    </div>

    <div class="flex flex-col justify-between items-center md:py-4 lg:w-5/6 min-h-screen mx-auto mb-1">
        
            <div class="pt-6 px-6 text-gray-900">
                <h1 class="text-2xl lg:text-4xl text-center">
                    <span class="text-red-500">LARA</span><img class="w-8 h-8 inline" src="/img/book.svg" />DOCS
                </h1>

                <p class="max-w-2xl my-6 text-xl text-center mx-auto">
                    A <strong>documentation search tool</strong> for the Laravel ecosystem, associated frameworks &amp; packages <i><u>all in one place</u></i>!
                </p>
            </div>
        
            <livewire:lara-search />

            {{-- <div class="md:hidden w-full flex justify-end">               
                <a target="_blank" href="https://buymeacoffee.com/tonhutchinson" class="py-2 px-4 bg-yellow-300 text-gray-800 flex items-center gap-2 text-sm mt-2 mr-2 rounded-lg hover:bg-yellow-400 transition duration-200">
                    <img class="w-4 h-4" src="/img/coffee.svg" /> <span>Buy me coffee</span>
                </a>
            </div> --}}

            <!-- Logos -->
            <div class="mt-4 opacity-60 w-full flex flex-wrap">
                <div class="w-full flex flex-wrap justify-center mb-4 gap-y-2 md:gap-y-6">
                    <a href="https://laravel.com/docs/11.x/" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center gap-2">
                        <img width="20" height="20" class="inline w-8" src="/img/icons/laravel.svg" />  
                        <img width="120" height="30" class="inline md:w-28" src="/img/logos/laravel-text.svg" />  
                    </a>
                    <a href="https://livewire.laravel.com/docs/quickstart" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-40" src="/img/logos/livewire.svg" />  
                    </a>
                    <a href="https://tailwindcss.com/docs/installation" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <x-tailwind-icon class="w-[80%] md:w-40" />
                    </a>
                    <a href="https://alpinejs.dev/start-here" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-36" src="/img/logos/alpine.svg" />  
                    </a>
                    <a href="https://inertiajs.com/" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <x-inertia-icon class="md:w-36 bg-purple-500 px-1 pt-1 pb-2" />
                    </a>
                    <a href="https://jetstream.laravel.com/introduction.html" class="w-1/2 md:w-1/4 mb-4 flex justify-center items-center">
                        <x-jetstream-icon class="w-[80%] md:w-40" />
                    </a>
                    <a href="https://vuejs.org/guide/introduction.html" class="w-1/2 md:w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-32" src="/img/logos/vue.png" />  
                    </a>
                    <a href="https://react.dev/learn" class="w-1/2 md:w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-32" src="/img/logos/react.png" />  
                    </a>
                    <a href="https://nova.laravel.com/docs/installation.html" class="w-1/2 md:w-1/4 mb-4 flex justify-center items-center">
                        <x-nova-icon class="w-[80%] md:w-44" />
                    </a>
                    <a href="https://filamentphp.com/docs/3.x/panels/installation" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <x-filament-icon class="w-[70%] md:w-32" />
                    </a>
                    <a href="https://spatie.be/docs/laravel-permission/v4/introduction" class="w-1/2 md:w-1/5 mb-4 flex justify-center text-xs items-center gap-1 font-bold">
                        <x-spatie-icon class="w-[70%] md:w-24" /> Permission
                    </a>
                    <a href="https://pestphp.com/docs/installation" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-32" src="/img/logos/pest.svg" />  
                    </a>
                    <a href="https://carbon.nesbot.com/docs/" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-30" src="/img/logos/carbon.png" />  
                    </a>
                    <a href="https://nativephp.com/docs/1/getting-started/introduction" class="w-1/2 md:w-1/5 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline md:w-36" src="/img/logos/nativephp.svg" />  
                    </a>
                </div>
            </div>

            <div class="text-gray-400 text-xs hover:text-gray-900 transition duration-200 text-center">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" >Contact // Feedback // Suggestions // Sponsorship Enquiries</a>
            </div>
        
    </div>
</x-app-layout>
