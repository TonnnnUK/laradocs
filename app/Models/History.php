<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'link_user';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function link(){
        return $this->belongsTo(Link::class);
    }
}
