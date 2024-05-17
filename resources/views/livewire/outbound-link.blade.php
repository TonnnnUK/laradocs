<div x-data 
    x-init="
    setTimeout(()=>{
        $wire.go();
    }, 300)
">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl">Redirecting you to docs....</h2>
    </div>

    <span class="animate-ping inline-flex h-3 w-3 rounded-full bg-sky-400 opacity-75"></span>
</div>
