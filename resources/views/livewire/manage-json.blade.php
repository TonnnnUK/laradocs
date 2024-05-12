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