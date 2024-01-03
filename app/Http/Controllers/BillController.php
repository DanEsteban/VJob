<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendors;
use App\Models\PaymentTerms;
use App\Models\Products;
use App\Models\Inventories;
use App\Models\Bills;
use App\Models\BillsItems;
use App\Models\Sizes;
use App\Models\Colors;
use App\Models\DocumentNumbers;

class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:bill.index')->only('index'); 
        $this->middleware('can:bill.create')->only('create', 'store');
        $this->middleware('can:bill.edit')->only('edit', 'update');
        $this->middleware('can:bill.show')->only('show'); 
        $this->middleware('can:bill.destroy')->only('destroy');  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bills::all();

        return view('bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendors::all();
        $terms = PaymentTerms::all();
        $items = Products::where('is_active', 1)->get();

        return view('bills.create', compact('vendors', 'terms', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ///Declaración de variables
        $total = "";
        $total = str_replace("$", " ", $request->bill_total);
        $length = 9;
        $sales_number = null;
        $secuencial = $request->number;

        ///Revisa si existe creado un registro con los mismos datos
        $id_vendor = Vendors::where('name', $request->select_vendor)->value('id');
        $is_bills = Bills::where('number', $request->number)->where('id_vendor', $id_vendor)->where('total', $total)->exists();
        if($is_bills == 1){
                ///Si el dato existe, regresa al formulario con un mensaje
                return back()->withInput($request->input())->with('info', 'The bill already exists')->send();
        }

        ///Buscar incidencias de número de Bills y si ya existe asigna nuevo número.
        do {
            $if_exists = Bills::where('number', $secuencial)->exists();
            if($if_exists == 1){    
                $number = intval($secuencial);   
                $number += 1;
                $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                $sales_number = $secuencial;
            }
            else{
               $sales_number =  $request->number;
            }
       } while ($if_exists == 1);

        ///Consultar el registro del Proveedor, usando el nombre.
        $vendor = Vendors::where('name', $request->select_vendor)->first();

        ///Crea la cabecera del Bills
        $bill = Bills::create([
            'number' => $sales_number,
            'id_vendor' => $vendor->id,
            'date' => $request->vendor_date,
            'phone' => $request->vendor_phone,
            'email' => $request->vendor_email,
            'id_term' => $request->select_term,
            'billto' => $request->vendor_billto,
            'total' => $total
        ]);

        ///Cuenta el array principal para saber cuantos registros vienen
        $count = count($request->items);
        ///Crea los detalles de items Bills
        for ($i=0; $i < $count; $i++) { 
            $id_item = Products::where('item_name', $request->items[$i])->value('id');
            BillsItems::create([
                'id_bill' => $bill->id,
                'id_item' => $id_item,
                'id_size' => $request->select_size[$i],
                'id_color' => $request->select_color[$i],
                'qty' => $request->qty[$i],
                'unit' => $request->unit[$i],
                'price' => $request->price[$i],
            ]);
            
            ///Consulta el registro del producto para sumar stock total
            $response=Products::where('id',$id_item)->first();

            ///Realiza el cálculo del costo promedio
            $cost_prom=(($response->cost_avg*$response->qty)+($request->qty[$i]*$request->price[$i]))/($response->qty+$request->qty[$i]);
            ///Guarada el costo promedio en el registro de producto
            $response->qty  += $request->qty[$i];
            $response->cost = $request->price[$i];
            $response->cost_avg=$cost_prom;
            $response->save();

            ///Crea el registro del producto en inventario
            Inventories::create([
                'type' => 'BL',
                'id_transaction' => $bill->id,
                'id_item' => $id_item,
                'id_size' => $request->select_size[$i],
                'id_color' => $request->select_color[$i],
                'price' => $request->price[$i],
                'cost' => $cost_prom,
                'qty' => $request->qty[$i]
            ]);

        }

         //Asignar el siguiente número de documento
        $document_number = DocumentNumbers::where('type', 'Invoices')->first();
        $number = intval($sales_number) + 1;
        $document_number->number = $number;
        $document_number->save();

         ///Redirigir a la vista Index, se adjunta mensaje en la ruta.
        return redirect()->route('bills.index')->with('info', 'A new record has been created')->send();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendors = Vendors::all();
        $terms = PaymentTerms::all();
        $items = Products::where('is_active', 1)->get();
        $bill = Bills::find($id);
        $bill->id_vendor = Vendors::where('id', $bill->id_vendor)->value('name');
        $bill_items = BillsItems::where('id_bill', $bill->id)->get();
        $sizes = Sizes::all();
        $colors = Colors::all();

        return view('bills.edit', compact('vendors', 'terms', 'items', 'bill', 'bill_items', 'sizes', 'colors'));
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
        $total = "";
        $total = str_replace("$", " ", $request->bill_total);

        $vendor = Vendors::where('name', $request->select_vendor)->first();
        $bill = Bills::find($id);
        $bill->number = $request->number;
        $bill->id_vendor = $vendor->id;
        $bill->date = $request->vendor_date;
        $bill->phone= $request->vendor_phone;
        $bill->email = $request->vendor_email;
        $bill->id_term = $request->select_term;
        $bill->billto = $request->vendor_billto;
        $bill->total = $total;
        $bill->save();

        $bill_items = BillsItems::where('id_bill', $bill->id)->get();
        foreach ($bill_items as $item) {
            $inventory = Inventories::where('id_product', $item->id_item)
                                    ->where('id_size', $item->id_size)
                                    ->where('id_color', $item->id_color)
                                    ->where('num_transaction_one', $item->id_bill)
                                    ->first();
            if($inventory){
                $inventory->delete();
            }
           
            $item->delete();
        }

        $count = count($request->items);
        for ($i=0; $i < $count; $i++) { 
            $id_item = Products::where('item_name', $request->items[$i])->value('id');
            BillsItems::create([
                'id_bill' => $bill->id,
                'id_item' => $id_item,
                'id_size' => $request->select_size[$i],
                'id_color' => $request->select_color[$i],
                'qty' => $request->qty[$i],
                'unit' => $request->unit[$i],
                'price' => $request->price[$i],
            ]);

            Inventories::create([
                'type' => 'BL',
                'id_product' => $id_item,
                'id_size' => $request->select_size[$i],
                'id_color' => $request->select_color[$i],
                'num_transaction_one' => $bill->id,
                'num_transaction_two' => '',
                'price' => $request->price[$i],
                'qty' => $request->qty[$i]
            ]);
        }

        return redirect()->route('bills.index')->with('info', 'A record has been edited')->send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = Bills::find($id);
        $bill_items = BillsItems::where('id_bill', $bill->id)->get();
        foreach ($bill_items as $item) {
            $inventory = Inventories::where('id_item', $item->id_item)
                                    ->where('id_size', $item->id_size)
                                    ->where('id_color', $item->id_color)
                                    ->where('id_transaction', $item->id_bill)
                                    ->first();
            if($inventory){
                $inventory->delete();
            }
           
            $item->delete();
        }
        $bill->delete();

        return redirect()->route('bills.index')->with('info', 'A record has been deleted')->send();
    }
}
