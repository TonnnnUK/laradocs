<x-app-layout>
    <div class="py-10 min-h-screen mx-auto mb-1">
        <h1 class="text-2xl lg:text-4xl text-center mb-4">
            <span class="text-red-500">LARA</span><img class="w-8 h-8 inline" src="/img/book.svg" />DOCS
        </h1>
        <div class="max-w-7xl mx-auto px-4 sm:p-6 lg:p-8 space-y-6 bg-white rounded">
            
            <div class="flex justify-between items-center">
                <h2 class="text-2xl">JSON Management</h2>
                <a class="text-sm text-blue-500" href="/">Home</a>
            </div>
            <livewire:manage-data />
        </div>
    </div>
</x-app-layout>
