<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colors;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:color.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Colors::all();

        return view('colors.index', compact('colors'));
    }
}
