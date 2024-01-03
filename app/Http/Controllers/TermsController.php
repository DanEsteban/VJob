<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerms;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:terms.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = PaymentTerms::all();

        return view('terms.index', compact('terms'));
    }
}
