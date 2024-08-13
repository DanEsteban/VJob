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
use App\Models\Impuesto;
use DateTime;
use Illuminate\Support\Facades\App;

class BillController extends Controller
{


    public function verificarVendedor($ruc)
    {
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname=' . $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM vendors WHERE numero_ident = '{$ruc}'";
            $result = $conexion->query($consulta);

            $customer = [];

            foreach ($result as $fila) {
                $customer[] = [
                    "id" => $fila['id'],
                    "vendedor" => $fila['name'],
                    "email" => $fila['email'],
                    "telefono" => $fila['phone'],
                    "direccion" => $fila['direccion']
                ];
            }

            return json_encode($customer);
        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    function identificarTipoIdentificacion($numero) {
        $numero = trim($numero);

        //consumidor
        if (preg_match('/^9+$/', $numero)) {
            return '07';
        }
        // Verificar si es ruc
        if (preg_match('/^[0-9]{10}001$/', $numero)) {
            return '04';
        }
    
        // Verificar si es una cédula
        if (preg_match('/^[0-9]{10}$/', $numero)) {
            return '05';
        }

        // Verificar si es un pasaporte
        if (preg_match('/^([A-Z]{1}[0-9]{6,9})$/', $numero)) {
            return '06';
        }
    
        return false;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname=' . $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT bills.id, bills.number, bills.date, bills.total,
                        bills.status, vendors.name 
                        FROM bills 
                        LEFT JOIN vendors ON bills.id_vendor = vendors.id ORDER BY bills.number DESC";

            $result = $conexion->query($consulta);
            $bills = [];

            foreach ($result as $fila) {
                $bills[] = [
                    "id" => $fila['id'],
                    "date" => $fila['date'],
                    "number" => $fila['number'],
                    "customer" => $fila['name'],
                    "total" => $fila['total'],
                    "status" => $fila['status']
                ];
            }
            return view('bills.index', compact('bills'));
        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nombreBD = App::make('dataBase');
        $dsn = 'mysql:host=localhost;dbname=' . $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para obtener los productos, proveedores, series de facturas, términos de pago y datos de la empresa
            $consulta = "SELECT * FROM products WHERE is_active = 1;
                        SELECT id, name FROM vendors;
                        SELECT * FROM serie_factura  WHERE tipo_documento <> 4;
                        SELECT number FROM document_numbers WHERE type = 'FacturaCompra';
                        SELECT id, name FROM payment_terms;
                        SELECT name, value FROM parameters WHERE name LIKE 'emp_%';";

            // Ejecutar las consultas
            $resultado = $conexion->query($consulta);

            // Obtener los resultados de todas las consultas
            $resultados = [];
            do {
                $resultados[] = $resultado->fetchAll(\PDO::FETCH_ASSOC);
            } while ($resultado->nextRowset());

            // Asignar los resultados a las variables correspondientes
            $items = $resultados[0];
            $vendors = $resultados[1];
            $seriesFact = $resultados[2];
            $numFact = intval($resultados[3][0]['number']) + 1;
            $payment_terms = $resultados[4];
            $datosEmp = [];
            foreach ($resultados[5] as $fila) {
                $datosEmp[$fila['name']] = $fila['value'];
            }

            //return $items;
            return view('bills.create', compact('items', 'vendors', 'seriesFact', 'numFact', 'payment_terms', 'datosEmp'));
        } catch (\PDOException $e) {
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

        $nombreBD = App::make('dataBase');

        try {
            $db = new \PDO('mysql:host=localhost;dbname=' . $nombreBD, 'root', '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            //Customer
            $id_proveedor = $request->id_proveedor ?? null;
            $numero_ident = $request->ruc;
            $tipo_ident = $this->identificarTipoIdentificacion($numero_ident);
            $name = $request->proveedor;
            $phone = $request->telefono ?? null;
            $email = $request->email ?? null;
            $direccion = $request->direccion;
            $balance = $request->saldo ?? null;
            if ($id_proveedor !== null) {
                $sql = "UPDATE vendors 
                        SET tipo_ident = :tipo_ident,     
                            numero_ident = :numero_ident,
                            name = :name,
                            phone = :phone,  
                            email = :email, 
                            direccion = :direccion,
                            balance = :balance
                        WHERE id = :id_proveedor ";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_proveedor', $id_proveedor, \PDO::PARAM_STR);
            } else {
                $sql = "INSERT INTO vendors 
                    (tipo_ident, numero_ident, name, phone,
                    email, direccion, balance)
                    VALUES (:tipo_ident, :numero_ident, :name,
                    :phone, :email, :direccion, :balance)";
                $stmt = $db->prepare($sql);
            }

            $stmt->bindParam(':tipo_ident', $tipo_ident, \PDO::PARAM_STR);
            $stmt->bindParam(':numero_ident', $numero_ident, \PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, \PDO::PARAM_STR);
            $stmt->bindParam(':balance', $balance, \PDO::PARAM_STR);

            $stmt->execute();


            // Obtener ID del proveedor insertado
            $lastId_proveedor = $db->lastInsertId();

            $id_proveedor = ($lastId_proveedor !== "0")
                ? $lastId_proveedor
                : $request->id_proveedor;

            // Generar número de factura único
            $length = 9;
            $sales_number = "";
            $number = intval($request->number);
            $secuencial = $request->number;

            $sql = "SELECT * FROM bills WHERE number = :secuencial LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':secuencial', $secuencial, \PDO::PARAM_STR);
            $stmt->execute();
            $if_exists = $stmt->rowCount();

            if ($if_exists == 1) {
                while ($if_exists == 1) {
                    $number = intval($secuencial);
                    $number += 1;
                    $secuencial = str_pad($number, $length, "0", STR_PAD_LEFT);
                    $sales_number = $secuencial;
                    $stmt->execute();
                    $if_exists = $stmt->rowCount();
                }
            } else {
                $sales_number = $request->number;
            }

            //Bill
            $number = $sales_number;
            $tipo_documento = $request->id_tipo_doc;
            $num_doc_sri = $request->serieNumero . $request->estableNumero . $request->secuencialNumero;
            $date = $request->fecha_fact;
            $phone = $request->telefono;
            $email = $request->email;
            $direccion = $request->direccion;
            $subtotal = $request->sumaSub;
            $taxes = $request->siIva;
            $base0 = $request->baseCero;
            $base_iva = $request->baseIva;
            $total = $request->total;
            $saldo = $request->saldo;

            $sql = "INSERT INTO bills (number, tipo_documento, num_doc_sri, id_vendor,
            date, phone, email, direccion, subtotal, total, taxes, base0, base_iva, saldo) VALUES 
            (:number, :tipo_documento, :num_doc_sri, :id_proveedor, :date, :phone, 
            :email, :direccion, :subtotal, :total, :taxes, :base0, :base_iva, :saldo)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':number', $number, \PDO::PARAM_STR);
            $stmt->bindParam(':tipo_documento', $tipo_documento, \PDO::PARAM_INT);
            $stmt->bindParam(':num_doc_sri', $num_doc_sri, \PDO::PARAM_STR);
            $stmt->bindParam(':id_proveedor', $id_proveedor, \PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, \PDO::PARAM_STR);
            $stmt->bindParam(':subtotal', $subtotal, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $total, \PDO::PARAM_STR);
            $stmt->bindParam(':taxes', $taxes, \PDO::PARAM_STR);
            $stmt->bindParam(':base0', $base0, \PDO::PARAM_STR);
            $stmt->bindParam(':base_iva', $base_iva, \PDO::PARAM_STR);
            $stmt->bindParam(':saldo', $saldo, \PDO::PARAM_STR);
            $stmt->execute();

            $lastId_bill = $db->lastInsertId();

            //Invoice Items
            $id_bill = $lastId_bill;

            $qty = 0;
            $lastId_billItem = '';
            $itemRepetido = (object) array(
                'id_item' => array(),
                'lastId_billItem' => array(),
                'qty' => array()
            );
            $resultados = [];

            $detalles = [];


            //Inventories
            $type = 'Bill';
            $dateObject = new DateTime($date);
            $year = $dateObject->format('Y');
            $month = $dateObject->format('m');


            $sql_select_product_cost = "SELECT avg_cost FROM product_balances WHERE id_item = :id_item AND year = :year AND month = :month";
            $stmt_select_product_cost = $db->prepare($sql_select_product_cost);

            $sql_select_product = "SELECT item_name FROM products WHERE id = :id_item";
            $stmt_select_product = $db->prepare($sql_select_product);

            // Preparar consulta SQL para insertar o actualizar elementos de la factura
            $sql_insert_update_item = "INSERT INTO bills_items (id_bill, id_item, qty, price) 
                                        VALUES (:id_bill, :id_item, :qty, :price)";
            $stmt_insert_update_item = $db->prepare($sql_insert_update_item);

            $sql_insert_inventories = "INSERT INTO inventories (type, date, id_transaction, id_item, cost, qty) 
                                        VALUES (:type, :date, :id_transaction, :id_item, :cost, :qty)";
            $stmt_insert_inventories = $db->prepare($sql_insert_inventories);

            // Preparar consulta SQL para insertar o actualizar elementos de product balance
            $sql_get_future_costs = " SELECT cost, qty, avg_cost 
            FROM product_balances 
            WHERE id_item = :id_item 
            AND year = :year 
            AND month = :month";
            $stmt_get_future_costs = $db->prepare($sql_get_future_costs);


            foreach ($request->items as $index => $item) {

                if ($item !== null) {
                    $id_item = $item;
                    $qty = intval($request->cantidad[$index]);
                    $price = $request->price[$index];
                    $iva = $request->iva[$index];
                    $impuesto = Impuesto::where('id', $iva)->first();

                    // Ejecutar consulta para obtener el nombre del producto
                    $stmt_select_product->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_select_product->execute();
                    $item_name = $stmt_select_product->fetchColumn();

                    $stmt_select_product_cost->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_select_product_cost->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_select_product_cost->bindParam(':month', $month, \PDO::PARAM_STR);
                    $stmt_select_product_cost->execute();
                    $product_cost = $stmt_select_product_cost->fetchColumn() ?? '0';

                    // Ejecutar consulta para insertar o actualizar elementos de la factura
                    $stmt_insert_update_item->bindParam(':id_bill', $id_bill, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_update_item->bindParam(':price', $price, \PDO::PARAM_STR);
                    $stmt_insert_update_item->execute();

                    // Ejecutar consulta para insertar o actualizar inventories
                    $stmt_insert_inventories->bindParam(':type', $type, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':date', $date, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_transaction', $id_bill, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':cost', $price, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_inventories->execute();

                    $stmt_get_future_costs->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_get_future_costs->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_get_future_costs->bindParam(':month', $month, \PDO::PARAM_INT);
                    $stmt_get_future_costs->execute();

                    $futureCosts = $stmt_get_future_costs->fetch(\PDO::FETCH_ASSOC);

                    $averageCost = 0;
                    $totalQty = 0;
                    $totalCost = 0;

                    $totalQty = $futureCosts['qty'] + $qty ;
                    $totalCost = $futureCosts['cost'] + ($price * $qty);
                    $averageCost = $totalCost / $totalQty;



                    $sql_update_productBalance = "UPDATE product_balances 
                        SET qty = :totalQty, cost = :totalCost , avg_cost = :averageCost
                        WHERE id_item = :id_item 
                        AND year = :year 
                        AND month = :month";


                    $stmt_update_productBalance = $db->prepare($sql_update_productBalance);
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

                    $stmt_update_future_productBalance = $db->prepare($sql_update_futureMonths);
                    $stmt_update_future_productBalance->bindParam(':totalQty', $totalQty, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->bindParam(':totalCost', $totalCost, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':averageCost', $averageCost, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':id_item', $id_item, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->bindParam(':year', $year, \PDO::PARAM_STR);
                    $stmt_update_future_productBalance->bindParam(':month', $month, \PDO::PARAM_INT);
                    $stmt_update_future_productBalance->execute();
                }
            }

            //return $detalles;

            // $db->commit();

            // // Payment_Customers
            // $id_customer = $request->id_cliente ?? $id_cliente;
            // $invoice = $request->number;
            // $amount = $request->total;

            // $sql = "INSERT INTO payment_customer 
            //         (id_customer, date, invoice, amount)
            //         VALUES (:id_customer, :date, :invoice, :amount)";
            // $stmt = $db->prepare($sql);
            // $stmt->bindParam(':id_customer', $id_customer, \PDO::PARAM_INT);
            // $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            // $stmt->bindParam(':invoice', $invoice, \PDO::PARAM_STR);
            // $stmt->bindParam(':amount', $amount, \PDO::PARAM_STR);
            // $stmt->execute();

            // $lastId_payment = $db->lastInsertId();

            // // Payment_Details
            // $id_payment = $lastId_payment;
            // $id_term = $request->formaPago1;
            // $valor = floatval($request->abono1);
            // $reference = $request->numTransfer1;
            // $banco = $request->banco1;
            // $reference2 = $request->numTransfer2;
            // $banco2 = $request->banco2;
            // $valor2 =  floatval($request->abono2);

            // $paymentDetailsData = [
            //     ['valor' => $valor, 'reference' => $reference, 'banco' => $banco],
            //     ['valor' => $valor2, 'reference' => $reference2, 'banco' => $banco2]
            // ];

            // $sql = "INSERT INTO payment_details 
            //         (id_payment, date, id_term, valor, reference, banco)
            //         VALUES (:id_payment, :date, :id_term, :valor, :reference, :banco)";

            // $stmt = $db->prepare($sql);
            // foreach ($paymentDetailsData as $paymentDetail) {
            //     if ($paymentDetail['valor'] && $paymentDetail['valor'] > 0) {
            //         $stmt->bindParam(':id_payment', $lastId_payment, \PDO::PARAM_INT); // Asignar el id_payment
            //         $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            //         $stmt->bindParam(':id_term', $id_term, \PDO::PARAM_STR);
            //         $stmt->bindParam(':valor', $paymentDetail['valor'], \PDO::PARAM_INT);
            //         $stmt->bindParam(':reference', $paymentDetail['reference'], \PDO::PARAM_STR);
            //         $stmt->bindParam(':banco', $paymentDetail['banco'], \PDO::PARAM_STR);
            //         $stmt->execute();
            //     }
            // }

            $sql = "UPDATE document_numbers SET number = :number WHERE type = 'FacturaCompra'";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':number', $number, \PDO::PARAM_INT);
            $stmt->execute();

            // Incrementar el número secuencial y actualizar la serie de facturas
            $sec = $request->secuencialNumero;
            $sec = ltrim($sec, '0');
            $sec = (int)$sec + 1;

            if ($request->id_tipo_doc === "1") {
                $sql = "UPDATE serie_factura SET secuencial = :sec WHERE nombre = 'FacturaCompra'";
            } else {
                $sql = "UPDATE serie_factura SET secuencial = :sec WHERE nombre = 'Nota de Venta'";
            }

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':sec', $sec, \PDO::PARAM_INT);
            $stmt->execute();

            $consulta = "SELECT * FROM parameters";
            $stmt = $db->prepare($consulta);
            $stmt->execute();

            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $datos = [];
            foreach ($resultados as $fila) {
                // Acceder a los datos de cada fila
                $nombre = $fila['name'];
                $valor = $fila['value'];
                $datos[$nombre] = $valor;
            }
            $emp_nombre = $datos['emp_nombre'];
            $emp_ruc = $datos['emp_ruc'];
            $emp_dir = $datos['emp_dir'];
            $emp_tel = $datos['emp_tel'];
            $emp_email = $datos['emp_email'];
            $emp_firmaElec = $datos['emp_firmaElec'];

            // if ($tipo_ident != '7' || $tipo_documento !== "0") {
            //     //return $num_doc_sri;
            //     $this->generarXML(
            //         $emp_nombre,
            //         $emp_ruc,
            //         $emp_dir,
            //         $emp_tel,
            //         $emp_email,
            //         $emp_firmaElec,
            //         $name,
            //         $numero_ident,
            //         $tipo_ident,
            //         $phone,
            //         $email,
            //         $direccion,
            //         $num_doc_sri,
            //         $tipo_documento,
            //         $subtotal,
            //         $taxes,
            //         $total,
            //         $detalles,
            //         $date
            //     );
            // }

            return redirect()->route('bills.show', $id_bill);
        } catch (\PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
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
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $p_impuestos = Impuesto::pluck('porcentaje', 'id')->toArray();
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT 
                    bills.number, bills.date, bills.subtotal, bills.taxes, bills.base0, 
                    bills.base_iva, bills.total, 
                    vendors.numero_ident, vendors.name, vendors.phone, 
                    vendors.email, vendors.direccion, vendors.balance 
                    FROM bills
                    LEFT JOIN vendors ON bills.id_vendor = vendors.id
                    WHERE bills.id = :id";

            $consulta2 = "SELECT 
                    bills_items.qty, 
                    bills_items.unit,
                    bills_items.price, 
                    products.id,
                    products.item_name, 
                    products.iva
                FROM bills_items 
                LEFT JOIN products ON bills_items.id_item = products.id
                WHERE bills_items.id_bill = :id";

            $consulta3 = "SELECT name, value FROM parameters WHERE name LIKE 'emp_%'";

            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $cabeceraInv = $statement->fetch(\PDO::FETCH_ASSOC);

            //return $cabeceraInv;

            $statement2 = $conexion->prepare($consulta2);
            $statement2->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement2->execute();
            $baseProductsInv = [];
            while ($fila = $statement2->fetch(\PDO::FETCH_ASSOC)) {
                $porcentajeIva = $p_impuestos[$fila['iva']] ?? 0;
                $baseProductsInv[] = [
                    "id" => $fila['id'],
                    "item_name" => $fila['item_name'],
                    "qty" => $fila['qty'],
                    "price" => $fila['price'],
                    "iva" => $porcentajeIva,
                ];
            }

            $result3 = $conexion->query($consulta3)->fetchAll(\PDO::FETCH_ASSOC);

            $datosEmp = [];
            foreach ($result3 as $fila) {
                $datosEmp[$fila['name']] = $fila['value'];
            }

            $consulta = "SELECT COUNT(*) as existe FROM documents WHERE doc_genera = :id";
            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $existeNC = $statement->fetch(\PDO::FETCH_ASSOC);

            return view('bills.show', compact('cabeceraInv', 'baseProductsInv', 'datosEmp', 'existeNC'));
            
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
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
        $bill->phone = $request->vendor_phone;
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
            if ($inventory) {
                $inventory->delete();
            }

            $item->delete();
        }

        $count = count($request->items);
        for ($i = 0; $i < $count; $i++) {
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
            if ($inventory) {
                $inventory->delete();
            }

            $item->delete();
        }
        $bill->delete();

        return redirect()->route('bills.index')->with('info', 'A record has been deleted')->send();
    }
}
