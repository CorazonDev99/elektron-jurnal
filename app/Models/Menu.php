<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tb_menu';
    public $timestamps = false;

    protected $primaryKey = 'menu_id';

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'menu_id')->orderBy('ordering');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'menu_id');
    }
}
