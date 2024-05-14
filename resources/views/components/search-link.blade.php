@props([
    'link',
    'search'
])

@php

    $searchWords = explode(' ', $search);
    $highlight = '';

@endphp

<div class="w-full">

    <a class="rounded-lg mb-2 border flex flex-col p-4 transition duration-150 bg-white {{$link['framework']['name']}}"
        href="{{$link['url']}}" target="_blank"
    >
        
        <div class="flex gap-1 items-center">
            <img class="w-3 h-3" src="/img/icons/{{$link['framework']['logo_icon']}}" alt="{{$link['framework']['name']}} logo">
            <span class="font-bold">{{$link['framework']['name']}}</span>
        </div>
        
        <div class="flex items-center">
            <div class="flex flex-wrap items-center">
                {{-- Iterate over each title element and highlight matching words --}}
                @php $types = ['topic_title', 'page_title', 'section_title', 'link_title']; @endphp
                @foreach( $types as $title)

                    <span class="@if($title == 'topic_title') w-full text-lg mb-1 @else w-auto @endif">
                        
                        @php
                            // Replace each word in the title with highlighted version
                            $highlightedTitle = $link[$title];
                            foreach ($searchWords as $word) {
                                $highlightedTitle = str_ireplace($word, '<strong class="highlight">'.ucfirst($word).'</strong>', $highlightedTitle);
                            }
                        @endphp
                        {!! $highlightedTitle !!}

                        @if( $title != 'topic_title' && $title != 'link_title')
                        <span class="mx-2">&raquo;</span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
        <div class="mt-1">
            <span class="text-sm">url: <span class="link">{{$link['url']}}</span></span>
        </div>
    </a>

</div>