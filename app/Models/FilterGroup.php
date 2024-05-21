<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function frameworks(){
        return $this->belongsToMany(Framework::class, 'filter_group_framework', 'filter_group_id', 'framework_id');
    }
}
