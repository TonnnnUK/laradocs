<div class="w-full flex flex-col justify-center items-center p-2 min-h-48" x-data="searchText()">

    <div class="flex items-stretch bg-gray-50 border border-gray-300 mb-4 text-sm rounded w-auto mx-auto">
        <span class="px-2 flex items-center border-r font-bold text-xs">Filter</span>
        <div class="flex items-stretch">
            @foreach($frameworks as $framework)
                <label wire:key="{{$framework->id}}" wire:click="filterSearch()" class="flex flex-col justify-between items-center border-gray-300 border-r px-3 pb-1 cursor-pointer">
                    <div class="py-2 w-6 flex justify-center items-center grow">
                        @if( in_array( $framework->id, $filters ))
                        <img width="20" height="20" src="/img/icons/{{$framework->logo_icon}}" alt="{{$framework->name}} icon" title="{{$framework->name}}" class="w-[95%]">
                        @else
                        <img width="20" height="20" src="/img/icons/{{$framework->logo_icon}}" alt="{{$framework->name}} icon" title="{{$framework->name}}" class="w-[95%] grayscale">
                        @endif
                    </div>
                    <input class="w-3 h-3" type="checkbox" wire:model.live="filters" value="{{ intval($framework->id) }}" wire:key="{{$framework->id}}">
                </label>
            @endforeach
                <label class="flex flex-col justify-between items-center border-gray-300 border-r px-3 pb-1 cursor-pointer">
                    <div class="py-2 w-6 flex flex-col justify-center items-center grow text-center">
                        <small>All</small>
                    </div>
                    <input class="w-3 h-3" type="checkbox" wire:model.live="allFilters" wire:click="toggleAll()" value="{{ $framework->id }}" wire:key="{{$framework->id}}">
                </label>
        </div>
    </div>

    <div class="w-full lg:w-4/6">
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

        <!-- results -->
        <div class="mt-4 flex flex-wrap">
            <div wire:loading wire:target="updatedSearch" class="w-full flex justify-center my-2">
                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-sky-400 opacity-75"></span>
            </div>

            @if(count($results) > 0 &&  strlen($search) >= 4  )
                <span class="w-full block font-bold my-2 px-3">{{count($results)}} search results...</span>
            @endif

            @if( strlen($search) >= 4 ) 
            @forelse($results as $result)
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
