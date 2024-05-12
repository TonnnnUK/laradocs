@props([
    'link',
    'search'
])

@php
    $bg = '';
    $text = '';
    $highlight = '';

    if( $link['framework']['name'] == 'Laravel'){
        $border = "border-red-300 hover:bg-red-50";
        $text = "text-red-600";
        $highlight = "text-red-700";
    }

    if( $link['framework']['name'] == 'Livewire'){
        $border = "border-pink-400 hover:bg-pink-50";
        $text = "text-pink-600";
        $highlight = "text-pink-700";
    }

    if( $link['framework']['name'] == 'Filament'){
        $border = "border-yellow-400 hover:bg-yellow-50";
        $text = "text-yellow-600";
        $highlight = "text-yellow-700";
    }

    if( $link['framework']['name'] == 'AlpineJS'){
        $border = "border-teal-300 hover:bg-teal-50";
        $text = "text-teal-600";
        $highlight = "text-teal-800";
    }

    if( $link['framework']['name'] == 'TailwindCSS'){
        $border = "border-blue-300 hover:bg-blue-50";
        $text = "text-blue-600";
        $highlight = "text-blue-800";
    }

    if( $link['framework']['name'] == 'Carbon'){
        $border = "border-green-300 hover:bg-green-50";
        $text = "text-green-600";
        $highlight = "text-green-800";
    }

    if( $link['framework']['name'] == 'Inertia'){
        $border = "border-purple-300 hover:bg-purple-50";
        $text = "text-purple-600";
        $highlight = "text-purple-800";
    }

    $searchWords = explode(' ', $search);

@endphp

<div class="w-full">

    <a class="rounded-lg mb-2 border flex flex-col p-4 transition duration-150 bg-white {{$border}}"
        href="{{$link['url']}}" target="_blank"
    >
        
        <div class="flex gap-1 items-center">
            <img class="w-3 h-3" src="/img/icons/{{$link['framework']['logo_icon']}}" alt="{{$link['framework']['name']}} logo">
            <span class="font-bold {{$text}}">{{$link['framework']['name']}}</span>
        </div>
        
        <div class="flex items-center">
            <div class="flex flex-wrap items-center">
                {{-- Iterate over each title element and highlight matching words --}}
                @foreach(['topic_title', 'page_title', 'section_title', 'link_title'] as $title)
                    <span class="@if($title == 'topic_title') w-full text-lg mb-1 @else w-auto @endif">
                        @php
                            // Replace each word in the title with highlighted version
                            $highlightedTitle = $link[$title];
                            foreach ($searchWords as $word) {
                                $highlightedTitle = str_ireplace($word, '<strong class="'.$highlight.'">'.ucfirst($word).'</strong>', $highlightedTitle);
                            }
                        @endphp
                        {!! $highlightedTitle !!}
                        @if ($title != 'topic_title' &&  ($title != 'link_title' && $link[$title] != $link['link_title']))
                            <span class="mx-2">&raquo;</span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
        <div class="mt-1">
            <span class="text-sm">url: <span class="{{$text}}">{{$link['url']}}</span></span>
        </div>
    </a>

</div>