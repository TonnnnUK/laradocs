<div x-data="{tab: 'outbound'}">

    <div class="flex gap-2 justify-center">
        <div class="tab cursor-pointer bg-gray-100 px-3 py-1 border border-b-0" :class="tab == 'outbound' ? 'border-gray-400' : ''" x-on:click="tab = 'outbound'">Outbound</div>
        <div class="tab cursor-pointer bg-gray-100 px-3 py-1 border border-b-0" :class="tab == 'json' ? 'border-gray-400' : ''" x-on:click="tab = 'json'">Json</div>
    </div>

    <div x-cloak x-show="tab == 'outbound'" class="border border-gray-400 p-6 flex flex-col" >
        <span class="font-bold text-lg mb-4">Popular Outbound</span>

        <div class="flex flex-col text-xs">
            <div class="flex font-bold">
                <span class="grow">Url</span>
                <span class="w-1/5">Framework</span>
                <span class="w-1/5">Count</span>
            </div>
            @foreach($outbounds as $link)
            <div class="flex">
                <span class="grow">{{ $link->url }} </span>
                <span class="w-1/5">{{ $link->link->framework->name }} </span>
                <span class="w-1/5">{{ $link->count }} </span>
            </div>
            @endforeach
        </div>
    </div>

    <div x-cloak x-show="tab == 'json'" class="border border-gray-400 flex justify-between p-6">
        <div>
            <h2>Import JSON</h2>
            <div class="flex gap-2">
                <select class="bg-white py-1 px-2 pr-8" wire:model="selected_framework">
                    <option value="0">Select framework</option>
                    @foreach( $frameworks as $framework )
                    <option value="{{$framework->id}}">{{ $framework->name }}</option>
                    @endforeach
                </select>
                
                <select class="bg-white py-1 px-2 pr-8" wire:model="selected_json">
                    <option value="0">Select JSON</option>
                    @foreach($files as $file )
                    <option value="{{$file['path']}}">{{ $file['name'] }}</option>
                    @endforeach
                </select>
        
                <button wire:click="import" class="bg-gray-700 text-white py-1 px-3 rounded-lg">Import</button>
        
                <div wire:loading class="w-full flex justify-center my-2">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-sky-400 opacity-75"></span>
                </div>
        
            </div>
            
            @if(count($added) > 0 )
            <div class="flex flex-col">
                @foreach($added as $item)
                <span>{{$item}}</span>
                @endforeach
            </div>
            @endif
        </div>
    
        <div class="w-1/2">
            <div class="flex justify-end items-center flex-wrap gap-y-1">
                @foreach($frameworks as $fr)
                    <div class="w-[50%] flex justify-end items-center text-sm gap-2">
                        {{ $fr->name }} 
                        <button class="bg-gray-100 px-2 py-1 text-xs hover:bg-gray-200" 
                            wire:click="deleteLinks({{$fr->id}})"
                        >
                            Delete links
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    
    </div>
</div>
