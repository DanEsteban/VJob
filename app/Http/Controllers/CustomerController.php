<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipToCustomer;
use App\Models\PaymentTerms;
use App\Models\DeliveryMethod;
use App\Models\Customers;
use App\Models\ContactCustomer;
use App\Models\NotesCustomer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:customer.index')->only('index'); 
        $this->middleware('can:customer.create')->only('create', 'store');
        $this->middleware('can:customer.edit')->only('edit', 'update');
        $this->middleware('can:customer.show')->only('show');
        $this->middleware('can:customer.destroy')->only('destroy');  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customers::all();
        $shipto_array = ShipToCustomer::where('id_customer', null)->get();
        foreach ($shipto_array as $shipto) {
            $shipto->delete();
        }

        foreach ($customers as $customer) {
            $term = PaymentTerms::where('id', $customer->id_terms)->value('name');
            $delivery = DeliveryMethod::where('id', $customer->id_delivery)->value('name');

            $customer->id_terms = $term;
            $customer->id_delivery = $delivery;
        }

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terms = PaymentTerms::all();
        $deliveries = DeliveryMethod::all();

        return view('customers.create', compact('terms', 'deliveries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datosPersona = [
            'nombre' => $request->cs_firstname,
            'apellido' => $request->cs_lastname,
            'numero' =>  $request->cs_phone,
            'correo' => $request->cs_ccemail,
        ];
        $jsonData = json_encode($datosPersona);
        QrCode::format('svg')->size(250)->generate($jsonData, public_path('qr-code.svg'));

        $customer = new Customers;
        $customer->company_name = $request->cs_company;
        $customer->first_name = $request->cs_firstname;
        $customer->midle_name = $request->cs_midlename;
        $customer->last_name = $request->cs_lastname;
        $customer->phone = $request->cs_phone;
        $customer->work_phone = $request->cs_workphone;
        $customer->email = $request->cs_mail;
        $customer->cc_email = $request->cs_ccemail;
        $customer->id_terms = $request->select_payment;
        $customer->id_delivery = $request->select_delivery;
        $customer->billto_street = $request->street_billto;
        $customer->billto_company = $request->company_billto;
        $customer->billto_city = $request->city_billto;
        $customer->billto_postal = $request->postal_billto;
        $customer->billto_state = $request->state_billto;
        if(isset($request->cs_inactive)){
            $customer->is_active = 0;
        }
        else{
            $customer->is_active = 1;
        }
        $customer->save();

        $shipto_array = ShipToCustomer::where('id_customer', null)->get();
        if($shipto_array){
            foreach ($shipto_array as $shipto) {
                $shipto->id_customer = $customer->id;
                $shipto->save();
            }
        }

        
        return redirect()->route('customers.index')->with('info', 'A new record has been created')->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customers::find($id);
        $notes = NotesCustomer::where('id_customer', $id)->first();
        $contacts = ContactCustomer::where('id_customer', $id)->get();

        return view('customers.show', compact('customer', 'notes', 'contacts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $terms = PaymentTerms::all();
        $deliveries = DeliveryMethod::all();
        $shipto = ShipToCustomer::where('id_customer', $id)->get();
        $customer = Customers::find($id);

        return view('customers.edit', compact('customer', 'terms', 'deliveries', 'shipto'));
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
        $customer = Customers::find($id);
        $customer->company_name = $request->cs_company;
        $customer->first_name = $request->cs_firstname;
        $customer->midle_name = $request->cs_midlename;
        $customer->last_name = $request->cs_lastname;
        $customer->phone = $request->cs_phone;
        $customer->work_phone = $request->cs_workphone;
        $customer->email = $request->cs_mail;
        $customer->cc_email = $request->cs_ccemail;
        $customer->id_terms = $request->select_payment;
        $customer->id_delivery = $request->select_delivery;
        $customer->billto_street = $request->street_billto;
        $customer->billto_company = $request->company_billto;
        $customer->billto_city = $request->city_billto;
        $customer->billto_postal = $request->postal_billto;
        $customer->billto_state = $request->state_billto;
        if(isset($request->cs_inactive)){
            $customer->is_active = $request->cs_inactive;
        }
        $customer->save();

        $shipto_array = ShipToCustomer::where('id_customer', null)->get();
        if($shipto_array){
            foreach ($shipto_array as $shipto) {
                $shipto->id_customer = $customer->id;
                $shipto->save();
            }
        }    

        return redirect()->route('customers.index')->with('info', 'A record has been edited')->send();
    }
}
