<?php

namespace App\Http\Controllers;

use App\Models\Taxes;
use App\Models\Products;
use App\Models\Customers;
use App\Models\ItemTypes;
use App\Models\PaymentTerms;
use App\Models\DeliveryMethod;
use App\Models\DocumentNumbers;
use App\Models\SalesOrders;
use App\Models\SalesOrdersItems;
use App\Models\InventoriesCustomers;
use App\Models\AttachmentCustomer;
use App\Models\Colors;
use App\Models\SalesOrder;
use App\Models\Sizes;
use App\Models\ShipToCustomer;
use App\Models\Warehouses;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:estimate.index')->only('index'); 
        $this->middleware('can:estimate.create')->only('create', 'store');
        $this->middleware('can:estimate.edit')->only('edit', 'update');
        $this->middleware('can:estimate.show')->only('show');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = SalesOrders::all();
        foreach ($orders as $order) {
                $order->id_customer = Customers::where('id', $order->id_customer)->value('company_name');    
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customers::where('is_active', 1)->get();
        $items = Products::where('is_active', 1)->get()->toArray();
        $terms = PaymentTerms::all();
        $warehouses = Warehouses::where('is_active', 1)->get();
        $deliveries = DeliveryMethod::all();
        $order_number = DocumentNumbers::where('type', 'Orders')->value('number');
        $types = ItemTypes::find(2);
        $taxes = Taxes::all();

        return view('orders.create', compact('customers', 'items', 'terms', 'warehouses', 'deliveries', 'order_number', 'types', 'taxes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sales_order = null;
        $id_process = 0;
        $length = 9; $sales_number = ""; $total = "";
        $total = str_replace("$", " ", $request->order_total);
        $tax = str_replace("$", " ", $request->order_tax);
        $total = str_replace(",", "", $total);
        $secuencial = $request->number;
        $number = 0;
        
        $if_exists = SalesOrders::where('number', $secuencial)->exists();
        if ($if_exists == 1) {
            while ($if_exists == 1)
            {
                $number = intval($secuencial);   
                $number += 1;
                $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                $sales_number = $secuencial;
                $if_exists = SalesOrders::where('number', $secuencial)->exists();
            }
        }
        else{
            $sales_number = $request->number;
        }
        //Sales Order Header
        $sales_order = SalesOrders::firstOrCreate([
            'number' => $sales_number,
            'id_customer' => Customers::where('company_name', $request->select_customer)->value('id'),
            'date' => $request->date,
            'phone' => $request->phone,
            'email' => $request->email,
            'id_term' => $request->select_term,
            'billto' => $request->billto,
            'id_shipto' => $request->select_shipto[0],
            'id_warehouse' => $request->select_warehouse,
            'porcentage'=> $request->porcentaje,
            'taxes' => $tax,
            'total' => $total
        ]);
        //Sales Order items
        $index = 0;
        $count = count($request->items);
        
        for ($i=0; $i < $count; $i++) { 
            if ($request->items[$i] != null) {
                $id_process = Products::where('item_name', $request->items[$i])->value('id_process');
                if($id_process){

                    $items = SalesOrdersItems::firstOrCreate([
                        'id_order' => $sales_order->id,
                        'id_warehouse' => $request->select_warehouse,
                        'id_item' => Products::where('item_name', $request->items[$i])->value('id'),
                        'qty' => $request->qty[$i],
                        'unit' => $request->unit[$i],
                        'price' => $request->price[$i],
                    ]);

                    //Inventario de cliente
                    if(isset($request->code_inv)){
                        $count_inventory = count($request->code_inv);
                        for ($j=0; $j < $count_inventory; $j++) { 
                            if($request->code_inv[$i]){
                                $items = InventoriesCustomers::firstOrCreate([
                                    'id_order' => $sales_order->id,
                                    'id_customer' => $sales_order->id_customer,
                                    'id_product' => Products::where('item_name', $request->code_inv[$j])->value('id'),
                                    'id_size' => $request->size_inv[$j],
                                    'id_color' => $request->color_inv[$j],
                                    'qty' => $request->qty_inv[$j]
                            ]);           
                            }              
                        }
                    }

                    //Archivos de cliente
                    if(isset($request->customer_files)){
                        if($request->customer_files[0] != null){
                            $directory = "customer/files/";
                            if(!file_exists($directory)){
                                mkdir($directory, 0777);
                            }
                    
                            foreach ($request->customer_files as $key => $tmp_name) {
                                $filename = $request->customer_files[$key]->getClientOriginalName();
                                $temporal = $tmp_name;
                                $extension = $request->customer_files[$key]->getMimeType();
                                $size = $request->customer_files[$key]->getSize();

                                $dir = opendir($directory);
                                $file = $directory.basename($filename);
                    
                                if($request->customer_files[$key]->move($directory, $filename)){
                                        $attach_file = new AttachmentCustomer;
                                        $attach_file->type_transaction = "SO";
                                        $attach_file->id_transaction = $sales_order->id;
                                        $attach_file->id_customer = $sales_order->id_customer;
                                        $attach_file->type = $extension;
                                        $attach_file->file_name = $filename;
                                        $attach_file->file_location = $file;
                                        $attach_file->file_size= $size;
                                        $attach_file->save();
                                }
                    
                                closedir($dir);
                    
                            }
                        }
                    }
                }
                else{
                    if (isset($request->select_size[$index])) {
                        $size = $request->select_size[$index];
                    }

                    if (isset($request->select_color[$index])) {
                        $color = $request->select_color[$index];
                    }
                    $items = SalesOrdersItems::create([
                        'id_order' => $sales_order->id,
                        'id_warehouse' => $request->select_warehouse,
                        'id_item' => Products::where('item_name', $request->items[$i])->value('id'),
                        'qty' => $request->qty[$i],
                        'unit' => $request->unit[$i],
                        'price' => $request->price[$i],
                    ]);
                    $index++;
                }
            }
        }

        //Asignar el siguiente número de documento
        $document_number = DocumentNumbers::where('type', 'Orders')->first();
        $number = intval($sales_number) + 1;
        $document_number->number = $number;
        $document_number->save();

         ///Redirigir a la vista Index, se adjunta mensaje en la ruta.
        return redirect()->route('orders.index')->with('info', 'A new record has been created')->send();
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
        $customers = Customers::where('is_active', 1)->get();
        $items = Products::where('is_active', 1)->get()->toArray();
        $terms = PaymentTerms::all();
        $warehouses = Warehouses::where('is_active', 1)->get();
        $deliveries = DeliveryMethod::all();
        $types = ItemTypes::find(2);
        $order = SalesOrders::find($id);
        $order_items = SalesOrdersItems::where('id_order', $id)->get();
        $shipto = ShipToCustomer::where('id_customer',  $order->id_customer)->get();
        $order->id_customer = Customers::where('id', $order->id_customer)->value('company_name');
        $attach_files = AttachmentCustomer::where('type_transaction', 'SO')->where('id_transaction', $order->id)->get();
        $inventories_customer = InventoriesCustomers::where('type_transaction', 'SO')->where('id_transaction', $order->id)->get();
        $sizes = Sizes::all();
        $colors = Colors::all();
        $taxes = Taxes::all();
        
        return view('orders.edit', compact('customers', 'items', 'terms', 'warehouses', 'deliveries', 'types', 'order', 'order_items', 'shipto', 'attach_files', 'inventories_customer', 'sizes', 'colors', 'taxes'));
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
        $id_process = 0;
        $total = "";
        $total = str_replace("$", " ", $request->order_total);
        $tax = str_replace("$", " ", $request->order_tax);
        $total = str_replace(",", "", $total);
        $number = intval($request->number);  

        //Sales Order Header
        $sales_order = SalesOrders::find($id);
        $sales_order->update([
            'number' => $request->number,
            'id_customer' => Customers::where('company_name', $request->select_customer)->value('id'),
            'date' => $request->date,
            'phone' => $request->phone,
            'email' => $request->email,
            'id_term' => $request->select_term,
            'billto' => $request->billto,
            'id_shipto' => $request->select_shipto[0],
            'id_warehouse' => $request->select_warehouse,
            'taxes' => $tax,
            'total' => $total
        ]);

        //Delete Items, attachments and Inventories
        $items = SalesOrdersItems::where('id_order', $sales_order->id)->get();
        foreach ($items as $item) {
            $item->delete();
        }

        $attachments = AttachmentCustomer::where('type_transaction', 'SO')->where('id_transaction', $sales_order->id)->get();
        if($attachments){
            foreach ($attachments as $attachment) {
                $attachment->delete();
            }
        }

        $inventories = InventoriesCustomers::where('type_transaction', 'SO')->where('id_transaction', $sales_order->id)->get();
        if($inventories){
            foreach ($inventories as $inventory) {
                $inventory->delete();
            }
        }

        //Sales Order items
        $index = 0;
        $count = count($request->items);
        for ($i=0; $i < $count; $i++) { 
            $id_process = Products::where('item_name', $request->items[$i])->value('id_process');
            if($id_process){

                $items = SalesOrdersItems::firstOrCreate([
                    'id_order' => $sales_order->id,
                    'id_warehouse' => $request->select_warehouse,
                    'id_item' => Products::where('item_name', $request->items[$i])->value('id'),
                    'qty' => $request->qty[$i],
                    'unit' => $request->unit[$i],
                    'price' => $request->price[$i]
                ]);

                //Inventario de cliente
                if(isset($request->code_inv)){
                    $count_inventory = count($request->code_inv);
                    for ($j=0; $j < $count_inventory; $j++) { 
                            $items = InventoriesCustomers::firstOrCreate([
                                    'id_order' => $sales_order->id,
                                    'id_customer' => $sales_order->id_customer,
                                    'id_product' => Products::where('item_name', $request->code_inv[$j])->value('id'),
                                    'qty' => $request->qty_inv[$j]
                            ]);             
                    }
                }            

                //Archivos de cliente
                if(isset($request->customer_files)){
                    if($request->customer_files[0] != null){
                        $directory = "customer/files/";
                        if(!file_exists($directory)){
                            mkdir($directory, 0777);
                        }
                
                        foreach ($request->customer_files as $key => $tmp_name) {
                            $filename = $request->customer_files[$key]->getClientOriginalName();
                            $temporal = $tmp_name;
                            $extension = $request->customer_files[$key]->getMimeType();
                            $size = $request->customer_files[$key]->getSize();

                            $dir = opendir($directory);
                            $file = $directory.basename($filename);
                
                            if($request->customer_files[$key]->move($directory, $filename)){
                                    $attach_file = new AttachmentCustomer;
                                    $attach_file->type_transaction = "SO";
                                    $attach_file->id_transaction = $sales_order->id;
                                    $attach_file->id_customer = $sales_order->id_customer;
                                    $attach_file->type = $extension;
                                    $attach_file->file_name = $filename;
                                    $attach_file->file_location = $file;
                                    $attach_file->file_size= $size;
                                    $attach_file->save();
                            }
                
                            closedir($dir);
                
                        }
                    }
                }
            }
            else{
                $size = null;
                $color = null;
                $items = SalesOrdersItems::create([
                    'id_order' => $sales_order->id,
                    'id_warehouse' => $request->select_warehouse,
                    'id_item' => Products::where('item_name', $request->items[$i])->value('id'),
                    'id_size' => $size,
                    'id_color' => $color,
                    'qty' => $request->qty[$i],
                    'unit' => $request->unit[$i],
                    'price' => $request->price[$i],
                ]);
                $index++;
            }
        }

        return redirect()->route('orders.index')->with('info', 'A record has been edited')->send();
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
        $salesorder = SalesOrders::find($id);
        if($salesorder->status == " In process")
        {       
        $items = SalesOrdersItems::where('id_order', $salesorder->id)->get();

        foreach ($items as $item) {
            $item->delete();
        }

        return redirect()->route('estimate.index')->with('info', 'A record has been deleted')->send();
       }

       else{
           return redirect()->route('estimate.index')->with('info', 'This estimate #'. $salesorder->number .' has been approved and cannot be deleted')->send();
       }
   }
}
