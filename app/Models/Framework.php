<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    use HasFactory;

    protected $table = 'frameworks';

    public function links(){
        return $this->hasMany(Link::class);
    }

    public function filter_groups(){
        return $this->belongsToMany(FilterGroup::class, 'filter_group_framework', "framework_id", 'filter_group_id');
    }
}
