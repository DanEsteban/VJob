<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\DocumentNumbers;
use App\Models\ItemTypes;
use App\Models\Expenditures;
use App\Models\ExpendituresItems;
use App\Models\Incomes;
use App\Models\IncomesItems;
use App\Models\Inventories;
use App\Models\UnitMeasure;
use App\Models\AssamblyItems;
use App\Models\Sizes;
use App\Models\Colors; 
use App\Models\Products_LabelBar;
use App\Models\Products_Warehouses;
use App\Models\Warehouses;
use App\Models\Transactions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class MovementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nombreBD = App::make('dataBase');
        
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try{

            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT * FROM movements";
            $result= $conexion->query($consulta);
            
            $movements = [];
            foreach ($result as $fila) {
                $movements[]=[
                    "id" => $fila['id'],
                    "number" => $fila['number'],
                    "comments" => $fila['comments'],
                    "date" => $fila['date'],
                    "total" => $fila['total'],
                    "tipo" => $fila['tipo'],
                    "clave" => $fila['clave'],
                    "autorizacion" => $fila['autorizacion'],   
                ];
            }

            //return $movements;
            return view('movements.index', compact('movements')); 
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 

        return view('movements.index', compact('expenditures','incomes')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nombreBD = App::make('dataBase');
        
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try{

            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // Asi deberia ser la consulta $consulta = "SELECT * FROM movements WHERE is_active = 1 AND id_type = 2";
            $consulta = "SELECT * FROM products WHERE is_active = 1";
            $consulta2 = "SELECT * FROM item_types WHERE id = 2";
            $consulta3 = "SELECT * FROM warehouses WHERE is_active = 1";
            $consulta4 = "SELECT number FROM document_numbers WHERE type = 'Discharges' ";
            $consulta5 = "SELECT number FROM document_numbers WHERE type = 'Incomes' ";

            
            $result= $conexion->query($consulta);
            $result2= $conexion->query($consulta2);
            $result3= $conexion->query($consulta3);
            $result4= $conexion->query($consulta4);
            $result5= $conexion->query($consulta5);

            $items = $result->fetchAll(\PDO::FETCH_ASSOC);
            $types = $result2->fetchAll(\PDO::FETCH_ASSOC);
            $warehouses =  $result3->fetchAll(\PDO::FETCH_ASSOC);
            $order_numberD = $result4->fetchAll(\PDO::FETCH_ASSOC);
            $order_numberI = $result5->fetchAll(\PDO::FETCH_ASSOC); 

            //return  $items;
            //return $order_numberD[0]['number']; 

            return view('movements.create', compact('items', 'order_numberD', 'order_numberI', 'types', 'warehouses'));
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 

        // $items = Products::where('is_active', 1)->where('id_type', 2)->get()->toArray();
        // $types = ItemTypes::find(2);
        // $warehouses = Warehouses::where('is_active', 1)->get();
        // $order_numberD = DocumentNumbers::where('type', 'Discharges')->value('number');
        // $order_numberI = DocumentNumbers::where('type', 'Incomes')->value('number');

        // return view('movements.create', compact('items', 'order_numberD', 'order_numberI', 'types', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expenditure = null;
        $length = 9;
        $total = "";
        $total = str_replace("$", " ", $request->order_total);
        $total = str_replace(",", "", $total);
        $secuencial = $request->number;
        
        $sales_number = "";
        $if_exists=0;
  

        if($request->mov_transaction == 1){

            do {
                $if_exists = Expenditures::where('number', $secuencial)->exists();
                if($if_exists == 1){  
                    $number = intval($secuencial);     
                    $number += 1;
                    $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                    $sales_number = $secuencial;
                }
                else {
                    $sales_number = $secuencial;
                }
    
            } while ($if_exists == 1);

            $expenditure = Expenditures::firstOrCreate([
                'id_warehouse' => $request->select_warehouse,
                'number' => $sales_number,
                'date' => $request->date,
                'comments' => $request->comments,
                'total' => $total
            ]);

            $count = count($request->items);
            $index = 0;
            for ($i=0; $i < $count; $i++) { 
            
                    if ($request->items[$i] != null) {
                        $size = null;
                        $color = null;
        
                        if(isset($request->select_size[$index])){
                            $size = $request->select_size[$index];
                        }
        
                        if (isset($request->select_color[$index])) {
                            $color = $request->select_color[$index];
                        }
                        
                        $type =  Products::where('item_name', $request->items[$i])->value('id_type');
                        if ($type == 4) {
                            $id_item = Products::where('item_name', $request->items[$i])->value('id');
                            if(!$id_item){
                                $id_item = Products_LabelBar::where('code', $request->items[$i])->value('id_item');
                            }                            
                            $items_production = AssamblyItems::where('id_item_main', $id_item)->get();
        
                            $items = ExpendituresItems::create([
                                'id_expenditure' => $expenditure->id,
                                'id_warehouse' => $request->select_warehouse,
                                'id_item' => $id_item,
                                'id_size' => $size,
                                'id_color' => $color,
                                'qty' => $request->qty[$i],
                                'unit' => $request->unit[$i],
                                'cost' => $request->price[$i],
                            ]);

                            //Actualiza el stock en el balance del producto.
                            if(Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->exists()){
                                $warehouse_balance_item = Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->first();
                                $warehouse_balance_item->qty_balance -= $request->qty[$i];
                                $warehouse_balance_item->save();
                            }
                            else{
                                $warehouse_balance_item = Products_Warehouses::create([
                                    'id_item'=>$id_item,
                                    'id_warehouse'=>$request->select_warehouse,
                                    'qty_balance'=>$request->qty[$i]
                                ]);
                            }
                            
                            foreach ($items_production as $itm) {
                                $id_unit = Products::where('id', $itm->id_item)->value('id_unit_measure');
                                $unit = UnitMeasure::where('id', $id_unit)->value('abbreviation');
                                $cost = Products::where('id', $itm->id_item)->value('cost_avg');

                                $response=Products::where('id',$itm->id_item)->first();
                                $response->qty  -= $itm->qty;
                                $response->save();

                                Inventories::create([
                                    'type' => 'Discharge',
                                    'id_transaction' => $expenditure->id,
                                    'id_warehouse' => $request->select_warehouse,
                                    'id_item' => $itm->id_item,
                                    'id_size' => $size,
                                    'id_color' => $color,
                                    'cost' => $cost,
                                    'qty' => $itm->qty
                                ]);    
                            
                                $index++;
                            }
                        } 
                        else {

                            $id_item = Products::where('item_name', $request->items[$i])->value('id');

                            if(!$id_item){
                                $id_item = Products_LabelBar::where('code', $request->items[$i])->value('id_item');
                            }
                            $items = ExpendituresItems::create([
                                'id_expenditure' => $expenditure->id,
                                'id_warehouse' => $request->select_warehouse,
                                'id_item' => $id_item,
                                'id_size' => $size,
                                'id_color' => $color,
                                'qty' => $request->qty[$i],
                                'unit' => $request->unit[$i],
                                'cost' => $request->price[$i],
                            ]);

                            $response=Products::where('id',$id_item)->first();
                            $response->qty  -= $request->qty[$i];
                            $response->save();

                              //Actualiza el stock en el balance del producto.
                            if(Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->exists()){
                                $warehouse_balance_item = Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->first();
                                $warehouse_balance_item->qty_balance -= $request->qty[$i];
                                $warehouse_balance_item->save();
                            }
                            else{
                                $warehouse_balance_item = Products_Warehouses::create([
                                    'id_item'=>$id_item,
                                    'id_warehouse'=>$request->select_warehouse,
                                    'qty_balance'=>$request->qty[$i]
                                ]);
                            }
                            

                            Inventories::create([
                                'type' => 'Discharge',
                                'id_transaction' => $expenditure->id,
                                'id_warehouse' => $request->select_warehouse,
                                'id_item' =>  $id_item,
                                'id_size' => $size,
                                'id_color' => $color,
                                'cost' => Products::where('item_name', $request->items[$i])->value('cost_avg'),
                                'price' => $request->price[$i],
                                'qty' => $request->qty[$i]
                            ]);
                            $index++;
                        }
                    }
                        
            }
    
                $document_number = DocumentNumbers::where('type', 'Discharges')->first();
                $number = intval($sales_number) + 1;
                $document_number->number = $number;
                $document_number->save();

                return redirect()->route('movements.index')->with('info', 'A new record has been created')->send();
        }
        else{
            
            do {
                $if_exists = Incomes::where('number', $secuencial)->exists();
                if($if_exists == 1){  
                    $number = intval($secuencial);     
                    $number += 1;
                    $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                    $sales_number = $secuencial;
                }
                else {
                    $sales_number = $secuencial;
                }
                    
            } while ($if_exists == 1);

            $income = Incomes::firstOrCreate([
                'id_warehouse' => $request->select_warehouse,
                'number' => $sales_number,
                'date' => $request->date,
                'comments' => $request->comments,
                'total' => $total
            ]);
            $index = 0;
            $count = count($request->items);
            for ($i=0; $i < $count; $i++) { 
                
                if ($request->items[$i] != null) {
                        $size = null;
                        $color = null;
        
                        if(isset($request->select_size[$index])){
                            $size = $request->select_size[$index];
                        }
        
                        if (isset($request->select_color[$index])) {
                            $color = $request->select_color[$index];
                        }
                        
                        $id_item = Products::where('item_name', $request->items[$i])->value('id');
                        if(!$id_item){
                            $id_item = Products_LabelBar::where('code', $request->items[$i])->value('id_item');
                        }

                        $items = IncomesItems::create([
                            'id_income' => $income->id,
                            'id_warehouse' => $request->select_warehouse,
                            'id_item' => $id_item,
                            'id_size' => $size,
                            'id_color' => $color,
                            'qty' => $request->qty[$i],
                            'unit' => $request->unit[$i],
                            'cost' => $request->price[$i],
                        ]);
                        $response=Products::where('id',$id_item)->first();

                        //Actualiza el stock en el balance del producto.
                        if(Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->exists()){
                            $warehouse_balance_item = Products_Warehouses::where('id_item', $id_item)->where('id_warehouse', $request->select_warehouse)->first();
                            $warehouse_balance_item->qty_balance += $request->qty[$i];
                            $warehouse_balance_item->save();
                        }
                        else{
                            $warehouse_balance_item = Products_Warehouses::create([
                                'id_item'=>$id_item,
                                'id_warehouse'=>$request->select_warehouse,
                                'qty_balance'=>$request->qty[$i]
                            ]);
                        }
                        
                        $qty_total = $response->qty+$request->qty[$i];
                        if ($qty_total==0) {
                            $cost_prom=0;
                            Transactions::create([
                                'number' => $sales_number,
                                'type' => 'Income', 
                                'date' => $request->date
                            ]);
                        }
                        else{$cost_prom=(($response->cost_avg*$response->qty)+($request->qty[$i]*$request->price[$i]))/($qty_total);
                        }
                        
                        $response->qty  += $request->qty[$i];
                        $response->save();

                        $product = Products::where('item_name', $request->items[$i])->first();
                        $product->cost = $request->price[$i];
                        $product->cost_avg = $cost_prom;
                        $product->save();
                        Inventories::create([
                            'type' => 'Income',
                            'id_transaction' => $income->id,
                            'id_warehouse' => $request->select_warehouse,
                            'id_item' =>  $id_item,
                            'id_size' => $size,
                            'id_color' => $color,
                            'price' => $request->price[$i],
                            'cost' => $cost_prom,
                            'qty' => $request->qty[$i]
                        ]);
                }
                        
            }
    
                $document_number = DocumentNumbers::where('type', 'Incomes')->first();
                $number = intval($sales_number) + 1;
                $document_number->number = $number;
                $document_number->save();

                return redirect()->route('movements.index')->with('info', 'A new record has been created')->send();
        }


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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variable = explode("-", $id);
        $id = $variable[0];
        $tipo = $variable[1];

        if ($tipo == "D") {
            $expenditure = Expenditures::find($id);
            $expenditure_items = ExpendituresItems::where('id_expenditure', $expenditure->id)->get();
            foreach ($expenditure_items as $item) {
                $inventory = Inventories::where('id_item', $item->id_item)
                                        ->where('type', "Discharge")
                                        ->where('id_size', $item->id_size)
                                        ->where('id_color', $item->id_color)
                                        ->where('id_transaction', $item->id_expenditure)
                                        ->first();

                if(Products_Warehouses::where('id_item', $item->id_item)->where('id_warehouse', $item->id_warehouse)->exists()){
                    $warehouse_balance_item = Products_Warehouses::where('id_item', $item->id_item)->where('id_warehouse', $item->id_warehouse)->first();
                    $warehouse_balance_item->qty_balance += $item->qty;
                    $warehouse_balance_item->save();
                }
                                
                $product=Products::where('id',$item->id_item)->first();
                $new_qty = $product->qty+$inventory->qty;

                $product->qty = $new_qty;
                $product->save();

                if($inventory){
                    $inventory->delete();
                }
            
                $item->delete();
            }
            $expenditure->delete();

            $document_number = DocumentNumbers::where('type', 'Discharges')->first();
            $number = intval($expenditure->number) - 1;
            $document_number->number = $number;
            $document_number->save();

            return redirect()->route('movements.index')->with('info', 'A record has been deleted')->send();

        } 
        else {
            $income = Incomes::find($id);
            $income_items = IncomesItems::where('id_income', $income->id)->get();
            foreach ($income_items as $item) {
                $inventory = Inventories::where('id_item', $item->id_item)
                                        ->where('type', "Income")
                                        ->where('id_size', $item->id_size)
                                        ->where('id_color', $item->id_color)
                                        ->where('id_transaction', $item->id_income)
                                        ->first();

                if(Products_Warehouses::where('id_item', $item->id_item)->where('id_warehouse', $item->id_warehouse)->exists()){
                    $warehouse_balance_item = Products_Warehouses::where('id_item', $item->id_item)->where('id_warehouse', $item->id_warehouse)->first();
                    $warehouse_balance_item->qty_balance -= $item->qty;
                    $warehouse_balance_item->save();
                }
                // $product=Products::where('id',$item->id_item)->first();
                // $total_cost=$product->qty*$product->cost_avg;
                // $delete_cost=$inventory->qty*$inventory->price; 
                // $cost_prom=($total_cost-$delete_cost)/($product->qty-$inventory->qty);

                // $product->qty = $product->qty-$inventory->qty;
                // $product->cost_avg = $cost_prom;
                // $product->save();

                if($inventory){
                    $inventory->delete();
                }
            
                $item->delete();
            }
            $income->delete();

            $document_number = DocumentNumbers::where('type', 'Incomes')->first();
            $number = intval($income->number) - 1;
            $document_number->number = $number;
            $document_number->save();

            return redirect()->route('movements.index')->with('info', 'A record has been deleted')->send();
        }
        
        
    }
}
