<?php

namespace App\Http\Controllers;

use App\Models\Impuesto;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        $id = $request->id_fact;
        
        try
        {
            
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM serie_factura  WHERE tipo_documento = '4'";            
            $statement = $conexion->prepare($consulta);
            $statement->execute();
            $tipo_doc = $statement->fetch(\PDO::FETCH_ASSOC);

            $secuencial = str_pad($tipo_doc["secuencial"], 9, '0', STR_PAD_LEFT);

            $consulta = "SELECT * FROM invoices  WHERE id = :id";            
            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_STR);
            $statement->execute();
            $invoice = $statement->fetch(\PDO::FETCH_ASSOC);

            $number = $invoice["number"];
            $tipo_documento = $tipo_doc["tipo_documento"];
            $num_doc_sri = $tipo_doc["establecimiento"].$tipo_doc["establecimiento"].$secuencial;
            $id_customer = $invoice["id_customer"];
            $date = $invoice["date"];
            $phone = $invoice["phone"];
            $email = $invoice["email"];
            $id_warehouse = $invoice["id_warehouse"];
            $subtotal = $invoice["subtotal"];
            $id_taxes = $invoice["id_taxes"];
            $taxes = $invoice["taxes"];
            $base0 = $invoice["base0"];
            $base_iva = $invoice["base_iva"];
            $total = $invoice["total"];
            $saldo = $invoice["saldo"];
            $status = $invoice["status"];
            $active = $invoice["active"];
            $clave = $invoice["clave"];
            $autorizacion = $invoice["autorizacion"];
            $fecha_autorizacion = $invoice["fecha_autorizacion"];
            $doc_genera = $id;
            $estado_sri = $invoice["estado_sri"];
            $created_at = $invoice["created_at"];
            $updated_at = $invoice["updated_at"];

            $sql = "INSERT INTO documents (number, tipo_documento, num_doc_sri, id_customer,
            date, phone, subtotal, email, taxes,  base0, base_iva, total, saldo, doc_genera) VALUES (:number, :tipo_documento, 
            :num_doc_sri, :id_cliente, :date, :phone, :subtotal, :email, :taxes,  :base0, :base_iva, :total, :saldo, :doc_genera)";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':number', $number, \PDO::PARAM_STR);
            $stmt->bindParam(':tipo_documento', $tipo_documento, \PDO::PARAM_INT);
            $stmt->bindParam(':num_doc_sri', $num_doc_sri, \PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $id_customer, \PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stmt->bindParam(':subtotal', $subtotal, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':taxes', $taxes, \PDO::PARAM_STR);
            $stmt->bindParam(':base0', $base0, \PDO::PARAM_STR);
            $stmt->bindParam(':base_iva', $base_iva, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $total, \PDO::PARAM_STR);
            $stmt->bindParam(':saldo', $saldo, \PDO::PARAM_STR);
            $stmt->bindParam(':doc_genera', $doc_genera, \PDO::PARAM_STR);
            $stmt->execute();

            $lastId_document = $conexion->lastInsertId();


            $consulta = "SELECT * FROM invoices_items where id_invoice = :id";
            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_STR);
            $statement->execute();
            $invoice_items = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $sql = "INSERT INTO documents_items (id_document, id_item, qty, precio_neto, pvp, num_precio) 
                                        VALUES (:id_document, :id_item, :qty, :precio_neto, :pvp, :num_precio)";
            $stmt = $conexion->prepare($sql);

            foreach ($invoice_items as $item) {
                $id_item = $item["id_item"];
                $qty = $item["qty"];
                $precio_neto = $item["precio_neto"];
                $pvp = $item["pvp"];
                $num_precio = $item["num_precio"];  

                // Ejecutar consulta para insertar o actualizar elementos de la factura
                $stmt->bindParam(':id_document', $lastId_document, \PDO::PARAM_STR);
                $stmt->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                $stmt->bindParam(':qty', $qty, \PDO::PARAM_INT);
                $stmt->bindParam(':precio_neto', $precio_neto, \PDO::PARAM_STR);
                $stmt->bindParam(':pvp', $pvp, \PDO::PARAM_STR);
                $stmt->bindParam(':num_precio', $num_precio, \PDO::PARAM_STR);
                $stmt->execute();

            }

            $sec = (int)$tipo_doc["secuencial"] +1;

            $sql = "UPDATE serie_factura SET secuencial = :sec WHERE nombre = 'Nota de Credito'";


            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':sec', $sec, \PDO::PARAM_INT);
            $stmt->execute();

            return redirect()->route('documents.show', $lastId_document);
            
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
                    documents.num_doc_sri, documents.date, documents.subtotal, documents.taxes, documents.base0, 
                    documents.base_iva, documents.total, 
                    customers.numero_ident, customers.name, customers.phone, 
                    customers.email, customers.direccion, customers.balance 
                    FROM documents
                    LEFT JOIN customers ON documents.id_customer = customers.id
                    WHERE documents.id = :id";

            $consulta2 = "SELECT 
                    documents_items.qty, 
                    documents_items.unit,
                    documents_items.precio_neto, 
                    documents_items.pvp,
                    products.id,
                    products.item_name, 
                    products.iva
                FROM documents_items 
                LEFT JOIN products ON documents_items.id_item = products.id
                WHERE documents_items.id_document = :id";

            $consulta3 = "SELECT name, value FROM parameters WHERE name LIKE 'emp_%'";

            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $cabeceraInv = $statement->fetch(\PDO::FETCH_ASSOC);

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
                    "precio_neto" => $fila['precio_neto'],
                    "iva" => $porcentajeIva,
                    "pvp" => $fila['pvp'],
                ];
            }

            $result3 = $conexion->query($consulta3)->fetchAll(\PDO::FETCH_ASSOC);

            $datosEmp = [];
            foreach ($result3 as $fila) {
                $datosEmp[$fila['name']] = $fila['value'];
            }

            $titulo = "NC";

            return view('documents.show', compact('cabeceraInv', 'baseProductsInv', 'datosEmp', 'titulo'));
            
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
        //
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
