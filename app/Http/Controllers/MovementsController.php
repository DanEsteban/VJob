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
use Carbon\Carbon;
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
            $consulta4 = "SELECT * FROM document_numbers";
            // $consulta4 = "SELECT number FROM document_numbers WHERE type = 'Egreso'";
            // $consulta5 = "SELECT number FROM document_numbers WHERE type = 'Ingreso'";
            
            $result= $conexion->query($consulta);
            $result2= $conexion->query($consulta2);
            $result3= $conexion->query($consulta3);
            $result4= $conexion->query($consulta4);
            // $result5= $conexion->query($consulta5); 

            $items = $result->fetchAll(\PDO::FETCH_ASSOC);
            //$types = $result2->fetchAll(\PDO::FETCH_ASSOC);
            $warehouses = $result3->fetchAll(\PDO::FETCH_ASSOC);
            $document_numbers =  $result4->fetchAll(\PDO::FETCH_ASSOC);
            // $order_numberD = $result4->fetchAll(\PDO::FETCH_ASSOC);
            // $order_numberI = $result5->fetchAll(\PDO::FETCH_ASSOC); 

            //return  $document_numbers;
            //return $order_numberD[0]['number']; 
            // return view('movements.create', compact('items', 'order_numberD', 'order_numberI', 'warehouses'));
    
            return view('movements.create', compact('items', 'document_numbers', 'warehouses'));
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        
        $nombreBD = App::make('dataBase');
        
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";
        try{
            $length = 9;
            $total = str_replace("$", " ", $request->order_total);
            $total = str_replace(",", "", $total);
            $secuencial = $request->number;
            $mov_transac = $request->mov_transaction;
            $mov_transac_str = '';

            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


            $mov_transac_str = ($mov_transac == 2) ? "Egreso" : "Ingreso";

            // Función para obtener un número secuencial único
            function getUniqueSecuencial($conexion, $secuencial, $length, $mov_transac_str) {
                do {
                    $consulta = "SELECT COUNT(*) AS count FROM movements WHERE number = :number AND tipo = :mov_transac";
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(':number', $secuencial, \PDO::PARAM_STR);
                    $stmt->bindParam(':mov_transac', $mov_transac_str, \PDO::PARAM_STR);
                    $stmt->execute();
                    $if_exists = $stmt->fetchColumn();
        
                    if ($if_exists == 1) {
                        $number = intval($secuencial);     
                        $number += 1;
                        $secuencial = str_pad($number, $length, "0", STR_PAD_LEFT);
                    }
                } while ($if_exists == 1);
        
                return $secuencial;
            }
        
            // Llamada a la función para obtener un número único
            $sales_number = getUniqueSecuencial($conexion, $secuencial, $length, $mov_transac_str);
        
            // Obtener el valor entero del número secuencial final para actualizarlo
            $final_number = intval($sales_number) + 1;
        
            // Actualiza el número en la tabla document_numbers
            $consulta2 = "UPDATE document_numbers SET number = :final_number WHERE type = :mov_transac_str";
            $stmt2 = $conexion->prepare($consulta2);
            $stmt2->bindParam(':final_number', $final_number, \PDO::PARAM_INT);
            $stmt2->bindParam(':mov_transac_str', $mov_transac_str, \PDO::PARAM_STR);
            $stmt2->execute();

            $fechaActual = Carbon::now()->toDateString(); 
            $fechaPartsActual = explode('-', $fechaActual);
            // $yearActual = $fechaPartsActual[0]; //Year
            // $monthActual = $fechaPartsActual[1]; // Mes

            $comments = $request->comments ?? null;
            $date = $request->date;
            $formatoFecha = explode('-', $date);
            $year = $formatoFecha[0];
            $month = $formatoFecha[1];

            
            $consulta = "INSERT INTO movements (number, comments, date, total, tipo) VALUES (:number, :comments, :date, :total, :tipo)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':number', $sales_number, \PDO::PARAM_STR);
            $stmt->bindParam(':comments', $comments, \PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $total, \PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $mov_transac, \PDO::PARAM_STR);
            $stmt->execute();

            $lastId_movements = $conexion->lastInsertId();
            $id_movement = intval($lastId_movements);
            //return $id_movement;

            // Preparar consulta SQL para insertar o actualizar elementos de movimientos
            $sql_insert_item = "INSERT INTO movements_items (id_movement, id_item, qty, unit, cost) 
                            VALUES (:id_movement, :id_item, :qty, :unit, :cost)";
            $stmt_insert_item = $conexion->prepare($sql_insert_item);           
            
            // Preparar consulta SQL para insertar o actualizar elementos de product balance
            $sql_get_future_costs = " SELECT cost, qty, avg_cost 
                FROM product_balances 
                WHERE id_item = :id_item 
                AND year = :year 
                AND month = :month";

            $stmt_get_future_costs = $conexion->prepare($sql_get_future_costs);
            
            $sql_insert_inventories = "INSERT INTO inventories (type, date, id_transaction, id_item, cost, price, qty) 
            VALUES (:mov_transac_str, :date, :id_transaction, :id_item, :cost, :price, :qty)";
            $stmt_insert_inventories = $conexion->prepare($sql_insert_inventories);


            $precio_neto=0;
            foreach ($request->items as $index => $item) {
                if ($item !== null) {  
                    $id_item = intval($item) ?? 0;
                    $qty = intval($request->qty[$index]) ?? 0;
                    $unit = $request->unit[$index] ?? 0;
                    $cost = $request->price[$index] ?? 0;
    
                    // Ejecutar consulta para insertar elementos del movimiento
                    $stmt_insert_item->bindParam(':id_movement', $id_movement, \PDO::PARAM_INT);
                    $stmt_insert_item->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_insert_item->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_item->bindParam(':unit', $unit, \PDO::PARAM_STR);
                    $stmt_insert_item->bindParam(':cost', $cost, \PDO::PARAM_STR); 
                    $stmt_insert_item->execute();

                    $stmt_get_future_costs->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_get_future_costs->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_get_future_costs->bindParam(':month', $month, \PDO::PARAM_INT);
                    $stmt_get_future_costs->execute();

                    $stmt_insert_inventories->bindParam(':mov_transac_str', $mov_transac_str, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':date', $date, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_transaction', $id_movement, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':cost', $cost, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':price', $precio_neto, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_inventories->execute();

                    $futureCosts = $stmt_get_future_costs->fetch(\PDO::FETCH_ASSOC);
                    
                    $averageCost = 0;
                    $totalQty = 0;
                    $totalCost = 0;

                    if ($mov_transac == 2) {
                        $totalQty = $futureCosts['qty'] - $qty;
                        $averageCost = $futureCosts['avg_cost'];

                        $totalCost = $averageCost * $totalQty;                
                        
                    }
                    elseif ($mov_transac == 3) {
                        $totalQty = $futureCosts['qty'] + $qty ;
                        $totalCost = $futureCosts['cost'] + ($cost * $qty);
                        $averageCost = $totalCost / $totalQty;

                    }

                    $sql_update_productBalance = "UPDATE product_balances 
                        SET qty = :totalQty, cost = :totalCost , avg_cost = :averageCost
                        WHERE id_item = :id_item 
                        AND year = :year 
                        AND month = :month";
                        
        
                    $stmt_update_productBalance = $conexion->prepare($sql_update_productBalance);
                    $stmt_update_productBalance->bindParam(':totalQty', $totalQty, \PDO::PARAM_INT);
                    $stmt_update_productBalance->bindParam(':totalCost', $totalCost, \PDO::PARAM_STR);
                    $stmt_update_productBalance->bindParam(':averageCost', $averageCost, \PDO::PARAM_STR);
                    $stmt_update_productBalance->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_update_productBalance->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_update_productBalance->bindParam(':month', $month, \PDO::PARAM_INT);
                    $stmt_update_productBalance->execute();

                    $sql_update_futureMonths = "UPDATE product_balances 
                        SET qty = :totalQty, cost = :totalCost, avg_cost = :averageCost
                        WHERE id_item = :id_item 
                        AND year = :year 
                        AND month > :month ";

                    $stmt_update_future_productBalance = $conexion->prepare($sql_update_futureMonths);
                    $stmt_update_future_productBalance->bindParam(':totalQty', $totalQty, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->bindParam(':totalCost', $totalCost, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':averageCost', $averageCost, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':month', $month, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->execute();

                }
            }   
            return redirect()->route('movements.index')->with('info', 'A new record has been created')->send();
    
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
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
