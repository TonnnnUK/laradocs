<?php

use App\Models\Link;
use Livewire\Volt\Volt;
use App\Models\Outbound;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::view('/', 'home');

Route::view('/json', 'json')
    ->middleware(['auth'])
    ->name('json');

Route::get('/outbound', function(){
    $decode = json_decode(request()->url);
    $link = Link::where('url', $decode)->first();
    $outbound = Outbound::where('url', $decode)->first();

    if($outbound){
        $outbound->count = $outbound->count+1;
        $outbound->save();
    } else {
        $outbound = Outbound::create([
            'link_id' => $link->id,
            'count' => 1,
            'url' => $decode
        ]);
    }
    
    return redirect($outbound->url);
})->name('outbound');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
require __DIR__.'/auth.php';
