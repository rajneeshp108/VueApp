<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function menuData()
    {
        $menu = Menu::select('id','name')
            ->where('is_parent', 'yes')
            ->with(['master_menu' => function($query){
                $query->select('id','name', 'm_id')
                    ->whereNull('c_id')
                    ->where('is_parent', 'no')
                    ->with(['child_menu' => function($query2){
                        $query2->select('id','name', 'm_id','c_id')->pluck('name');
                    }]);
            }])
            ->get();

            dd(/* response()->json( */$menu->toArray() /*) */);
                $output = [];
            foreach($menu as $key){
                $string = '';        
                foreach($key->master_menu as $m){
                    $d = Menu::select('name')->where('c_id', $m->id)->get()->pluck('name');
                    $string = implode(explode(',',$d));
                    // dump($string);
                    $output[$key->name][$m->name] = $d->toArray();
                }
            }
    
    
            dump(/* response()->json( */$output/* ) */);
        
            dd('--------');
    }
}
