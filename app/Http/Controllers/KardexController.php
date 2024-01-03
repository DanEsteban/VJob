<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ItemTypes;    
use App\Models\Inventories;  
use App\Models\Customers;
use App\Models\Vendors;  

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Products::where('is_active', 1)->get()->toArray();
        $types = ItemTypes::find(2);
        return view('kardex.index', compact('items', 'types')); 
        
    }
    
}
