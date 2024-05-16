<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    use HasFactory;

    protected $table = 'outbound';
    
    protected $guarded = [];

    public $timestamps = false;
    
    public function link(){
        return $this->belongsTo(Link::class);
    }
}
