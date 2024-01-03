<?php

namespace App\Http\Controllers;

use App\Models\VendorsUsers;
use App\Models\Products;
use App\Models\VendorOrder;
use App\Models\VendorOrderItems;
use App\Models\PaymentTerms;
use App\Models\Vendors;
use App\Models\Bills;
use App\Models\BillsItems;
use App\Models\Inventories;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = VendorOrder::where('status', 'Pending')->get();
        return view('receiveorders.index', compact('orders'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Products::where('is_active', 1)->get();
        return view('providers.createorder', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $total = str_replace("$", " ", str_replace(",", "", $request->order_total));
        $order = VendorOrder::create([
            'date' => $request->vendor_date,
            'number' => $request->number,
            'vendor_id' => Vendors::where('name', $request->select_vendor)->value('id'),
            'total' => $total,
            'status'=> 'Pending'
        ]);

        $count = Count($request->items);
        for ($i=0; $i < $count; $i++) { 
            VendorOrderItems::create([
                'order_id' => $order->id,
                'item_id' => Products::where('item_name', $request->items[$i])->value('id'),
                'qty' => $request->qty[$i],
                'price' => $request->price[$i],
            ]);
        }

        return view('providers.main')->with('info', 'A new order has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $vendor_id = Vendors::where('name', $name)->value('id');
        $orders = VendorOrder::where('vendor_id', $vendor_id)->orderBy('id', 'desc')->get();

        return view('providers.listorder', compact('orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = VendorOrder::where('id', $id)->first();
        $orders_items = VendorOrderItems::where('order_id', $id)->get();
        $terms = PaymentTerms::all();
        return view('receiveorders.bill', compact('order', 'orders_items','terms'));
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
        $total = str_replace("$", " ", $request->order_total);
        $sales_number = null;
        $count = count($request->items);
        $id_vendor = Vendors::where('name', $request->select_vendor)->value('id');

        ///Crea la cabecera del Bills
        $bill = Bills::create([
            'number' => $request->number,
            'id_vendor' => $id_vendor,
            'date' => $request->vendor_date,
            'phone' => $request->vendor_phone,
            'email' => $request->vendor_email,
            'id_term' => null,
            'billto' => $request->vendor_billto,
            'total' => $total
        ]);

        $order = VendorOrder::find($id);
        $order->number = $request->number;
        $order->vendor_id = $id_vendor;
        $order->date = $request->vendor_date;
        $order->total = $total;
        $order->status ="Complete";
        $order->save();

        ///Crea los detalles de items Bills
        for ($i=0; $i < $count; $i++) { 
            $id_item = Products::where('item_name', $request->items[$i])->value('id');
            BillsItems::create([
                'id_bill' => $bill->id,
                'id_item' => $id_item,
                'id_size' => null,
                'id_color' => null,
                'qty' => $request->qty[$i],
                'unit' => null,
                'price' => $request->price[$i],
                
            ]);
            $order_item = VendorOrderItems::where('order_id',$id)->where('item_id',$id_item)->first();
            $order_item->receive=$request->qty[$i];
            $order_item->balance = $order_item->qty - $request->qty[$i];
            $order_item->save();


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
                'id_size' => null,
                'id_color' => null ,
                'price' => $request->price[$i],
                'cost' => $cost_prom,
                'qty' => $request->qty[$i]
            ]);

        }
        $orders = VendorOrder::where('status', 'Pending')->get();
        return view('receiveorders.index', compact('orders'));
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

       /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        if (VendorsUsers::where('user', $request->email)->where('password', $request->password)->exists()) {         
            $vendor = $request->email;
            return view('providers.main', compact('vendor'));
        } else {
            return back()->withInput();
        }
        
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdf($id){
        $order = VendorOrder::where('id', $id)->first();
        $order_items = VendorOrderItems::where('order_id', $id)->get();

        return view('providers.pdf', compact('order', 'order_items'));
    }
}