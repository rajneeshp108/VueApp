<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name', 'parent_id', 'm_id', 'c_id'
    ];

    public function master_menu(){
        return $this->hasMany('App\Model\Menu', 'm_id');
    }
    public function child_menu(){
        return $this->hasMany('App\Model\Menu', 'c_id');
    }
}
