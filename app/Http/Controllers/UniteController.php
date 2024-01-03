<?php

namespace App\Http\Controllers;

use App\Models\UnitMeasure;
use Illuminate\Http\Request;

class UniteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:unit.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = UnitMeasure::all();

        return view('unites.index', compact('units'));
    }
}
