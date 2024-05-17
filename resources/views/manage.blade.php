<x-app-layout>
    
    <x-slot name="page_title">Manage LaraDocs</x-slot>
    <div class="py-10 min-h-screen mx-auto mb-1">
        <div class="max-w-7xl mx-auto px-4 sm:p-6 lg:p-8 space-y-6 bg-white rounded">
            
            <div class="flex justify-between items-center">
                <h2 class="text-2xl">JSON Management</h2>
                <a class="text-sm text-blue-500" href="/">Home</a>
            </div>
            <livewire:manage-data />
        </div>
    </div>
</x-app-layout>
