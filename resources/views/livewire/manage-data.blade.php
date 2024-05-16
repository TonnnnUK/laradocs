<div x-data="{tab: 'stats'}">

    <div class="flex gap-2 justify-center">
        <div class="tab cursor-pointer bg-gray-100 px-3 py-1 border border-b-0 hover:bg-gray-200 transition" :class="tab == 'stats' ? 'border-gray-300 bg-gray-200' : ''" x-on:click="tab = 'stats'">Stats</div>
        <div class="tab cursor-pointer bg-gray-100 px-3 py-1 border border-b-0 hover:bg-gray-200 transition" :class="tab == 'json' ? 'border-gray-300 bg-gray-200' : ''" x-on:click="tab = 'json'">Json</div>
    </div>

    <div x-cloak x-show="tab == 'stats'" class="border border-gray-300 p-6 flex flex-col" >
        <span class="font-bold text-lg mb-4">Popular Outbound</span>

        <table class="text-xs w-3/4">
            <tr class="font-bold border bg-gray-100">
                <td class="p-1">Url</td>
                <td class="p-1">Framework</td>
                <td class="p-1">Count</td>
            </tr>
            @foreach($outbounds as $link)
            <tr class="border">
                <td class="p-1">{{ $link->url }} </td>
                <td class="p-1">{{ $link->link->framework->name }} </td>
                <td class="p-1">{{ $link->count }} </td>
            </tr>
            @endforeach
        </table>


        <span class="font-bold text-lg mt-8 mb-4">Popular Searches</span>

        <table class="text-xs w-1/2">
            <tr class="font-bold border bg-gray-100">
                <td class="p-1">Search</td>
                <td class="p-1">Count</td>
            </tr>
            @foreach($searches as $search)
            <tr class="border">
                <td class="p-1">{{ $search->search }} </td>
                <td class="p-1">{{ $link->count }} </td>
            </tr>
            @endforeach
        </table>


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