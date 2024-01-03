<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendors;
use App\Models\VendorsUsers;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:vendor.index')->only('index'); 
        $this->middleware('can:vendor.create')->only('create', 'store');
        $this->middleware('can:vendor.edit')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendors::all();

        return view('providers.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendor = Vendors::create([
            'name' => $request->v_company,
            'contact' => $request->v_contact,
            'phone' => $request->v_phone,
            'email' => $request->v_mail,
            'billto_street' => $request->street_billto,
            'billto_company' => $request->company_billto,
            'billto_city' => $request->city_billto,
            'billto_postal' => $request->postal_billto,
            'billto_state' => $request->state_billto,
        ]);

        $user = VendorsUsers::create([
            'id_vendor' => $vendor->id,
            'user' => $vendor->name,
            'password' => uniqid()
        ]);

        return redirect()->route('vendors.index')->with('info', 'A new vendor has been added')->send();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendors::find($id);

        return view('providers.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendors::find($id);
        $vendor->name = $request->v_company;
        $vendor->contact = $request->v_contact;
        $vendor->phone = $request->v_phone;
        $vendor->email = $request->v_mail;
        $vendor->billto_street = $request->street_billto;
        $vendor->billto_company = $request->company_billto;
        $vendor->billto_city = $request->city_billto;
        $vendor->billto_postal = $request->postal_billto;
        $vendor->billto_state = $request->state_billto;
        $vendor->save();

        return redirect()->route('vendors.index')->with('info', 'The vendor has been edited')->send();
    }
}
