<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:group.index')->only('index'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Groups::all();

        return view('groups.index', compact('groups'));
    }
}
