<?php

namespace App\Http\Controllers;

use App\Models\Taxes;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:taxes.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxes = Taxes::all();
     
        return view('taxes.index', compact('taxes'));
    }
}
