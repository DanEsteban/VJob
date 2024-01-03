<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sizes;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:size.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Sizes::all();

        return view('sizes.index', compact('sizes'));
    }
}
