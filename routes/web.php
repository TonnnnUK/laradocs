<?php

use App\Models\Link;
use Livewire\Volt\Volt;
use App\Models\Outbound;
use Illuminate\Support\Facades\Auth;
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


Route::view('/', 'home')->name('home');

Route::view('/manage', 'manage')
    ->middleware(['auth'])
    ->name('manage');

Route::get('/outbound', function(){
    $link = Link::with('framework')->where('id', request()->id)->first();
    $outbound = Outbound::where('url', $link->url)->first();

    if($outbound){
        $outbound->count = $outbound->count+1;
        $outbound->save();
    } else {
        $outbound = Outbound::create([
            'link_id' => $link->id,
            'count' => 1,
            'url' => $link->url
        ]);
    }

    if( Auth::user()){
        $user =  Auth::user();
        $user->history()->save($link);
    }
    
    return view('outbound')->with([
        'outbound' => $outbound->url,
        'link' => $link
    ]);

})->name('outbound');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    
require __DIR__.'/auth.php';
