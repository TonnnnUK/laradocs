<x-app-layout>
    
    <div class="hidden fixed right-2 top-2 md:flex flex-col justify-center">
        <div class="p-2 mt-2 text-center">
            <small>Finding this useful?</small>
            <a target="_blank" href="https://buymeacoffee.com/tonhutchinson" 
                class="py-2 px-4 bg-yellow-300 text-gray-800 flex items-center gap-2 text-sm mx-auto rounded-lg hover:bg-yellow-400 transition duration-200"
            >
                <img class="w-4 h-4" src="/img/coffee.svg" /> <span>Buy me a coffee</span>
            </a>
        </div>

        {{-- <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" class="bg-gray-200 w-36 h-36 mt-2 flex justify-center items-center text-center text-gray-400 text-sm mx-auto">
            Sponsor <br> Opportunity
        </a> --}}
    </div>

    <div class="flex flex-col justify-between items-center py-4 lg:w-5/6 min-h-screen mx-auto mb-1">
        
            <div class="pt-6 px-6 text-gray-900">
                <h1 class="text-2xl lg:text-4xl text-center">
                    <span class="text-red-500">LARA</span><img class="w-8 h-8 inline" src="/img/book.svg" />DOCS
                </h1>

                <p class="max-w-2xl my-6 text-xl text-center mx-auto">
                    A <strong>documentation search tool</strong> for the Laravel ecosystem, associated frameworks &amp; packages <i><u>all in one place</u></i>!
                </p>
            </div>
        
            <livewire:lara-search />

            <div class="md:hidden w-full flex justify-end">               
                <a target="_blank" href="https://buymeacoffee.com/tonhutchinson" class="py-2 px-4 bg-yellow-300 text-gray-800 flex items-center gap-2 text-sm mt-2 mr-2 rounded-lg hover:bg-yellow-400 transition duration-200">
                    <img class="w-4 h-4" src="/img/coffee.svg" /> <span>Buy me coffee</span>
                </a>
            </div>

            <!-- Logos -->
            <div class="mt-4 opacity-60 w-full flex flex-wrap">
                <div class="w-full flex justify-center mb-4">
                    <div class="w-1/4 mb-4 flex justify-center items-center gap-2">
                        <img width="20" height="20" class="inline w-8" src="/img/icons/laravel.svg" />  <img width="120" height="30" class="inline w-28" src="/img/logos/laravel-text.svg" />  
                    </div>
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline w-40" src="/img/logos/livewire.svg" />  
                    </div>
                    <div class="w-1/3 mb-4 flex justify-center items-center">
                        <x-inertia-icon class="w-36 bg-purple-500 px-1 pt-1 pb-2" />
                    </div>
                    <div class="w-1/3 mb-4 flex justify-center items-center">
                        <x-jetstream-icon class="w-40" />
                    </div>
                </div>
                <div class="w-full flex justify-center mb-4">
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline w-36" src="/img/logos/alpine.svg" />  
                    </div>
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <x-tailwind-icon class="w-40" />
                    </div>
                    <div class="w-1/3 mb-4 flex justify-center items-center">
                        <x-nova-icon class="w-44" />
                    </div>
                </div>
                <div class="w-full flex justify-center mb-4">
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <x-filament-icon class="w-32" />
                    </div>
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <x-spatie-icon class="w-24" />
                    </div>
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline w-32" src="/img/logos/pest.svg" />  
                    </div>
                    <div class="w-1/4 mb-4 flex justify-center items-center">
                        <img width="120" height="30" class="inline w-32" src="/img/logos/carbon.png" />  
                    </div>
                </div>
            </div>

            <div class="text-gray-400 text-sm hover:text-gray-900 transition duration-200 text-center">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSda7x8mGB96ycmDVLw2SIfKya_bVstgS0FOhHbu0dGXkQ56JA/viewform?usp=pp_url" target="_blank" >Sponsorship Enquiries</a>
            </div>
        
    </div>
</x-app-layout>
