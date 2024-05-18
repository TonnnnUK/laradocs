<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';
    
    protected $guarded = [];
    protected $with = ['framework'];

    public function framework(){
        return $this->belongsTo(Framework::class);
    }

    public function user_clicks(){
        return $this->belongsToMany(User::class, 'history', 'link_id', 'user_id');
    }
}
