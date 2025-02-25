<div class="w-full flex flex-col justify-center items-center p-2 min-h-48" x-data="searchText()">

    <div class="flex flex-col"
        x-data="{
            allFilters: $wire.filters,
            filters: $wire.entangle('filters'),

            syncFilters( all = false){
                setTimeout( () => {

                    console.log('syncing', this.filters);
                    
                    if(all == 'all'){
                        localStorage.setItem('active-filters', JSON.stringify(this.allFilters));
                    } else {
                        localStorage.setItem('active-filters', JSON.stringify(this.filters));
                    }
                },350)
            }
        }"
        x-init="
            if( localStorage.getItem('active-filters') ){
                let filters = localStorage.getItem('active-filters');
                $wire.set('filters', JSON.parse(filters));
            } else {
                localStorage.setItem('active-filters', JSON.stringify(filters));
            }
        "
    >

        {{-- FILTERS --}}
        <div class="flex items-stretch flex-wrap bg-gray-50 border border-gray-300 mb-2 text-sm rounded w-auto mx-auto">
            <span class="p-2 flex items-center md:border-r font-bold text-xs">Filter</span>
            <input type="hidden" wire:model.live="filters" id="filtersInput">
    
            <div class="flex items-stretch flex-wrap gap-1" 
            >
                @foreach($frameworks as $framework)
                    <label wire:key="{{$framework->id}}" wire:click="filterSearch()" x-on:click="syncFilters()" 
                        class="flex gap-1 md:gap-0 md:flex-col justify-between items-center border-gray-300 md:border-r px-3 pb-1 cursor-pointer">
                        <div class="py-2 w-6 flex justify-center items-center grow">
                            @if( in_array( $framework->id, $filters ))
                            <img width="20" height="20" src="/img/icons/{{$framework->logo_icon}}" alt="{{$framework->name}} icon" title="{{$framework->name}}" class="min-w-[95%] w-[95%]">
                            @else
                            <img width="20" height="20" src="/img/icons/{{$framework->logo_icon}}" alt="{{$framework->name}} icon" title="{{$framework->name}}" class="min-w-[95%] w-[95%] grayscale">
                            @endif
                        </div>
                        <input class="w-3 h-3" type="checkbox" wire:model.live="filters" value="{{ intval($framework->id) }}" wire:key="{{$framework->id}}">
                    </label>
                @endforeach
                    <label class="flex md:flex-col gap-1 md:gap-0 justify-between items-center border-gray-300 md:border-r px-3 pb-1 cursor-pointer" 
                        x-on:click="syncFilters('all')">
                        <div class="py-2 w-6 flex flex-col justify-center items-center grow text-center">
                            <small>All</small>
                        </div>
                        <input class="w-3 h-3" type="checkbox" wire:model.live="allFilters" wire:click="toggleAll()" value="{{ $framework->id }}" wire:key="{{$framework->id}}">
                    </label>
            </div>
    
        </div>
    
        @auth
        <div class="text-xs mt-2 mb-4 flex justify-between items-center gap-2"
            x-data="{showField: false}"
        >
            <div class="text-left flex items-center gap-2">
                <span class="cursor-pointer text-blue-800 hover:text-blue-950" x-on:click="showField = !showField">Save current filters</span>
                <div class="flex" x-cloak x-show="showField">
                    <input class="w-40 text-xs rounded-l" type="text" wire:model="newFilterGroup" placeholder="Filter group name" />
                    <button class="bg-blue-700 text-white py-1 px-3 rounded-r hover:bg-blue-800" x-on:click="$wire.addFilterGroup(); showField = false">Save</button>
                </div>
            </div>

            @if(count($filter_groups) > 0)
            <div>
                Filter groups: @foreach ( $filter_groups as $item )
                    <span class="text-red-600 hover:underline cursor-pointer" wire:click="applyFilters({{$item->id}})" 
                        x-on:click="syncFilters()"    
                    >
                        {{ $item->name }}
                    </span> @if(!$loop->last)|@endif
                @endforeach
            </div>
            @endif
        </div>
        @endauth
    </div>


    {{-- Common Searches --}}
    <div class="w-full md:w-4/6 mt-4 md:mt-8">
        <div class="flex flex-wrap gap-2 text-xs">
            <span>Popular Searches:</span>
            @foreach($common_searches as $search)
                <span class="text-blue-600 cursor-pointer hover:underline"
                    wire:click="set('search', '{{ucwords($search->search)}}')"
                >
                {{ ucwords($search->search) }} 
                </span>
                @if(!$loop->last)|@endif
            @endforeach
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="w-full lg:w-4/6 mt-2">
        <div class="flex relative">
        
            @if(!$hasSearched)
            <span 
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center text-xl block w-11/12"
                x-ref="typewriter"
                x-on:click="hideTypewriter">
            </span>
            @endif
            <input 
                x-ref="textSearch"
                x-on:click="hideTypewriter"
                class="border border-gray-300 rounded-lg w-full text-center text-xl p-4 bg-white" 
                wire:model.live.debounce.800="search"
            >
        </div>

        @auth
            @if (count($results) == 0)
            <div class="flex flex-col my-4 text-xs" x-data="{showHistory: false}">
                
                @if( Auth::user() && is_countable($link_history) && count($link_history) > 0)
                <span class="cursor-pointer underline" x-on:click="showHistory = !showHistory">
                    <span x-text="showHistory ? 'Hide' : 'Show'"></span> history
                </span>
                <div class="flex flex-col" x-cloak x-show="showHistory">
                    @foreach ( $link_history as $li )
                        <a  class="" href="{{route('outbound', ['id' => $li['id']])}}" target="_blank">
                            {{ $li->framework->name }} - {{ $li->topic_title }} - {{ $li->section_title }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        @endauth

        {{-- RESULTS --}}
        <div class="mt-4 flex flex-wrap">
            <div wire:loading class="w-full flex justify-center my-2">
                <span class="animate-ping inline-flex h-3 w-3 rounded-full bg-sky-400 opacity-75"></span>
            </div>

            @if(count($results) > 0 &&  strlen($search) >= 4  )
                <span class="w-full block font-bold my-2 px-3">{{count($results)}} search results...</span>
            @endif

            @if( strlen($search) >= 4 ) 
            @forelse($results as $result)

                @if ( $loop->index == 8 || $loop->index == 20)
                <div class="w-48 text-center mx-auto mb-4">
                    <x-buy-coffee />
                </div>
                @endif

                <x-search-link :link="$result" :search="$search" wire:key="{{$result['id']}}" />
            @empty
            @if($hasSearched && $search !== "")
                <div class="w-full bg-white p-4 text-center text-sm rounded-lg">
                    No results found..
                </div>
            @endif
            @endforelse
            @endif
        </div>
    </div>
</div>

<script>


    let searchText = () => {
        return {
            searchLabels: [
                'Migrations',
                'Livewire actions',
                'X-ref',
                'Permissions',
                'Filament panel',
            ],


            async init() {
                await this.sleep(800);
                await this.typeWriter('Search all the docs...');
                await this.sleep(1200);

                for (const label of this.searchLabels) {
                    await this.typeWriter(label);
                    await this.sleep(1200);
                }        

                await this.typeWriter('Search all the docs...');
                
                
            },

            async typeWriter(text) {
                if(this.$refs.typewriter){
                    this.$refs.typewriter.innerHTML = '';
                    var i = 0; // Initialize i to 1
                    var txt = text;
                    var speed = 50; /* The speed/duration of the effect in milliseconds */
                    while (i <= txt.length) { 
                        this.$refs.typewriter.innerHTML += txt.charAt(i - 1); // Adjust index to i - 1
                        i++;
                        await this.sleep(speed);
                    }
                }
            
            },


            hideTypewriter(){
                if(this.$refs.typewriter){
                    this.$refs.typewriter.remove();
                    this.focusSearch();
                    this.addPlaceholder();
                }
            },

            focusSearch(){
                this.$refs.textSearch.focus();
            },

            addPlaceholder(){
                this.$refs.textSearch.placeholder = 'Search the docs...'
            },

            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
        }
    };

</script>
