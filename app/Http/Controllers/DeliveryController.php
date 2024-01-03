<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMethod;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:delivery.index')->only('index');   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = DeliveryMethod::all();

        return view('deliveries.index', compact('deliveries'));
    }
}
