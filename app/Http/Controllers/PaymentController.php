<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Invoices;
use App\Models\PaymentTerms;
use App\Models\PaymentCustomers;
use App\Models\PaymentsDetails;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:payment.index')->only('index'); 
        $this->middleware('can:payment.create')->only('create', 'store');
        $this->middleware('can:payment.edit')->only('edit', 'update');
        //$this->middleware('can:payment.show')->only('show');
        $this->middleware('can:payment.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = PaymentTerms::all();
        $customers = Customers::where('is_active', 1)->get();

        return view('payments.index', compact('customers', 'terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = Customers::where('company_name', $request->customer)->first();
        $id_term = PaymentTerms::where('name', $request->term)->value('id');
        $payment =  PaymentCustomers::create([
                'id_customer' => $customer->id,
                'date' => $request->date,
                'id_term' => $id_term,
                'reference' => $request->reference,
                'card_number' => $request->ccn,
                'exp_date' => $request->cce,
                'memo' => $request->memo
        ]);

        $count = count($request->invoice);
        for ($i=0; $i < $count; $i++) { 
            if ($request->payment[$i] != null) {
                $details = PaymentsDetails::create([
                    'id_payment' => $payment->id,
                    'invoice' => $request->invoice[$i],
                    'amount' => $request->payment[$i]
                ]); 

                
                $invoice = Invoices::where('number', $request->invoice[$i])->first();
                $payed = PaymentsDetails::where('invoice', $request->invoice[$i])->sum('amount');
                if($invoice->total == $payed){
                    $invoice->status = 'Payed';
                    $invoice->save();
                }

                $customer = Customers::where('company_name', $request->customer)->first();
                $customer->balance -=  $request->payment[$i];
                $customer->save();
            }  
        }


        return redirect()->route('payments.index')->with('info', 'A new payment has been created')->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terms = PaymentTerms::all();
        $customers = Customers::where('is_active', 1)->get();
        $invoice = Invoices::find($id);
        $current_customer = Customers::where('id', $invoice->id_customer)->value('company_name');

        return view('payments.index', compact('customers', 'terms', 'invoice', 'current_customer'));
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
        $payment = PaymentCustomers::find($id);
        $payment->id_term = PaymentTerms::where('id', $payment->id_term)->value('name');
        $payment_detail = PaymentsDetails::where('id_payment', $id)->get();

        $customers = Customers::where('is_active', 1)->get();
        $current_customer = Customers::where('id', $payment->id_customer)->value('company_name');

        return view('payments.edit', compact('customers', 'terms', 'current_customer', 'payment', 'payment_detail'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
