<x-app-layout>
    <x-slot name="page_title">Redirecting to {{$link->framework['name']}} - {{$link->url}}</x-slot>
    <div class="py-10 min-h-screen mx-auto mb-1">
        <div class="max-w-7xl mx-auto px-4 sm:p-6 lg:p-8 space-y-6 bg-white rounded">
            <livewire:outbound-link :link="$outbound" />
        </div>
    </div>
</x-app-layout>
