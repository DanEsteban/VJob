<?php

namespace App\Http\Controllers;
use App\Models\AssamblyItems;
use App\Models\Taxes;
use App\Models\Products;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\PaymentTerms;
use App\Models\DeliveryMethod;
use App\Models\DocumentNumber;
use App\Models\DocumentNumbers;
use App\Models\InvoicesItems;
use App\Models\Process_Order;
use App\Models\Processes;
use App\Models\ProcessPhases;
use App\Models\ProcessStage;
use App\Models\ProcessCondition;
use App\Models\ProcessData;
use App\Models\ProcessDataPhase;
use App\Models\ProcessDataStage;
use App\Models\ProcessDataCondition;
use App\Models\SalesOrders;
use App\Models\SalesOrdersItems;
use App\Models\ShipToCustomer;
use App\Models\ItemTypes;
use App\Models\Inventories;
use App\Models\InventoriesCustomers;
use App\Models\AttachmentCustomer;
use App\Models\PaymentCustomers;
use App\Models\PaymentsDetails;
use App\Models\Colors;
use App\Models\Impuesto;
use App\Models\Products_LabelBar;
use App\Models\Sizes;
use App\Models\UnitMeasure;
use App\Models\TicketSetItems;
use App\Models\Warehouses;
use DateTime;
use Illuminate\Http\Request;
use DOMDocument;
use Illuminate\Support\Facades\App;


class InvoiceController extends Controller
{
    public function generarXML($emp_nombre, $emp_ruc, $emp_dir, $emp_tel, $emp_email,
    $emp_firmaElec, $name, $numero_ident, $tipo_ident, $phone, $email, $direccion,
    $num_doc_sri, $tipo_documento, $subtotal, $taxes, $total, $detalles, $date)    
    {
        $fechaFormateada = date("d-m-Y", strtotime($date));
        $fechaformateadaXML = date("d/m/Y", strtotime($date));
        // dump($fechaFormateada);
        $fecha =  str_replace("-", "", $fechaFormateada);
        $tipo_emision = "2";
        
        $ultimosOchoDigitos = substr($num_doc_sri, -8);
        $numero = $fecha."0".$tipo_documento.$emp_ruc.$tipo_emision.$num_doc_sri.$ultimosOchoDigitos."1";
        $secuencialXML = substr($num_doc_sri, -9);
        //dump($num_doc_sri);
        $establecimiento = substr($num_doc_sri, 0, 3);
        $ptoEmision = substr($num_doc_sri, 3, 3);

        $digitoVerificador = $this->modulo11($numero);
        // Datos a guardar
        $clave = $numero.$digitoVerificador;
        $fechaDoc = date('Y-m-d');
        if($tipo_documento == 1){
            $tipoDoc = "FC";
        }
        $estado = "GENERAR";
        //dump($estado);
        try
        {       

            $db = new \PDO('mysql:host=localhost;dbname=docelectronica', 'root', '');       
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Crear un documento XML
            $xmlDoc = new DOMDocument('1.0', 'UTF-8');
            $xmlDoc->formatOutput = true;

            // Crear elemento raíz
            $factura = $xmlDoc->createElement('factura');
            $factura->setAttribute('id', 'comprobante');
            $factura->setAttribute('version', '1.1.0');
            $xmlDoc->appendChild($factura);

            // Función para crear elementos XML
            function createElement($doc, $name, $value) {
                $element = $doc->createElement($name);
                $element->appendChild($doc->createTextNode($value));
                return $element;
            }
            
            // Crear elementos para infoTributaria
            $infoTributaria = $xmlDoc->createElement('infoTributaria');
            $infoTributaria->appendChild(createElement($xmlDoc, 'ambiente', '2'));
            $infoTributaria->appendChild(createElement($xmlDoc, 'tipoEmision', '1'));
            $infoTributaria->appendChild(createElement($xmlDoc, 'razonSocial', $emp_nombre));
            $infoTributaria->appendChild(createElement($xmlDoc, 'nombreComercial', $emp_nombre));
            $infoTributaria->appendChild(createElement($xmlDoc, 'ruc', $emp_ruc));
            $infoTributaria->appendChild(createElement($xmlDoc, 'claveAcceso', $clave));
            $infoTributaria->appendChild(createElement($xmlDoc, 'codDoc', '01'));
            $infoTributaria->appendChild(createElement($xmlDoc, 'estab', $establecimiento));
            $infoTributaria->appendChild(createElement($xmlDoc, 'ptoEmi', $ptoEmision));
            $infoTributaria->appendChild(createElement($xmlDoc, 'secuencial', $secuencialXML));
            $infoTributaria->appendChild(createElement($xmlDoc, 'dirMatriz', $emp_dir));
            
            // Crear elementos para infoFactura
            $infoFactura = $xmlDoc->createElement('infoFactura');
            $infoFactura->appendChild(createElement($xmlDoc, 'fechaEmision', $fechaformateadaXML));
            $infoFactura->appendChild(createElement($xmlDoc, 'dirEstablecimiento', $direccion));
            $infoFactura->appendChild(createElement($xmlDoc, 'obligadoContabilidad', 'NO'));
            $infoFactura->appendChild(createElement($xmlDoc, 'tipoIdentificacionComprador', $tipo_ident));
            $infoFactura->appendChild(createElement($xmlDoc, 'razonSocialComprador', $name));
            $infoFactura->appendChild(createElement($xmlDoc, 'identificacionComprador', $numero_ident));
            $infoFactura->appendChild(createElement($xmlDoc, 'totalSinImpuestos', $subtotal));
            $infoFactura->appendChild(createElement($xmlDoc, 'totalDescuento', '0.00'));
            
            
            $totalConImpuestos = $xmlDoc->createElement('totalConImpuestos');
            $totalImpuesto = $xmlDoc->createElement('totalImpuesto');
            $totalImpuesto->appendChild(createElement($xmlDoc, 'codigo', '2'));
            $totalImpuesto->appendChild(createElement($xmlDoc, 'codigoPorcentaje', '2'));
            $totalImpuesto->appendChild(createElement($xmlDoc, 'baseImponible', $subtotal));
            $totalImpuesto->appendChild(createElement($xmlDoc, 'valor', $taxes));
            $totalConImpuestos->appendChild($totalImpuesto);

            $infoFactura->appendChild($totalConImpuestos);

            $infoFactura->appendChild(createElement($xmlDoc, 'propina', '0.00'));
            $infoFactura->appendChild(createElement($xmlDoc, 'importeTotal', $total));
            $infoFactura->appendChild(createElement($xmlDoc, 'moneda', 'DOLAR'));
            
            $pagos = $xmlDoc->createElement('pagos');
            $pago = $xmlDoc->createElement('pago');
            $pago->appendChild(createElement($xmlDoc, 'formaPago', '20'));
            $pago->appendChild(createElement($xmlDoc, 'total', $total));
            $pago->appendChild(createElement($xmlDoc, 'plazo', '0'));
            $pago->appendChild(createElement($xmlDoc, 'unidadTiempo', 'DIAS'));
            $pagos->appendChild($pago);

            $infoFactura->appendChild($pagos);

            
            $detallesXML = $xmlDoc->createElement('detalles');
            foreach($detalles as $dt)
            { 
                $baseImponible = floatval($dt['precioUnitario']) * floatVal($dt['qty']);
                $porcentaje = isset($dt['porcentaje']) ? floatval($dt['porcentaje']) : 0.00;
                $codigo_impuesto = isset($dt['codigo_impuesto']) ? $dt['codigo_impuesto'] : ''; 
                $codigo_tarifa = isset($dt['codigo_tarifa']) ? $dt['codigo_tarifa'] : ''; // o puedes asignar otro valor por defecto

                if ($porcentaje) {
                    // Calcula el valor utilizando el porcentaje
                    $valor = number_format($baseImponible * (floatval($dt['porcentaje']) / 100), 2);
                } else {
                    $valor = number_format($baseImponible, 2);
                }
                
                // Crear elementos para detalles         
                $detalle = $xmlDoc->createElement('detalle');
                $detalle->appendChild(createElement($xmlDoc, 'codigoPrincipal', $dt['codigo']));
                
                $detalle->appendChild(createElement($xmlDoc, 'descripcion', $dt['descripcion']));
                $detalle->appendChild(createElement($xmlDoc, 'cantidad', $dt['qty']));
                $detalle->appendChild(createElement($xmlDoc, 'precioUnitario',  $dt['precioUnitario']));
                $detalle->appendChild(createElement($xmlDoc, 'descuento', '0.00'));
                $detalle->appendChild(createElement($xmlDoc, 'precioTotalSinImpuesto', $dt['precioTotalSinImpuesto']));
                
                $impuestos = $xmlDoc->createElement('impuestos');
                $impuesto = $xmlDoc->createElement('impuesto');
                $impuesto->appendChild(createElement($xmlDoc, 'codigo', $codigo_impuesto));
                $impuesto->appendChild(createElement($xmlDoc, 'codigoPorcentaje', $codigo_tarifa));
                $impuesto->appendChild(createElement($xmlDoc, 'tarifa', number_format($porcentaje, 2)));
                $impuesto->appendChild(createElement($xmlDoc, 'baseImponible', $baseImponible));
                $impuesto->appendChild(createElement($xmlDoc, 'valor', $valor));
        

                $impuestos->appendChild($impuesto);
                $detalle->appendChild($impuestos);
                $detallesXML->appendChild($detalle);               
            }
            
            // Crear elementos para infoAdicional
            $infoAdicional = $xmlDoc->createElement('infoAdicional');
            $campoDireccion = $xmlDoc->createElement('campoAdicional');
            $campoDireccion->setAttribute('nombre', 'Direccion');
            $campoDireccion->appendChild($xmlDoc->createTextNode('DE LOS ARUPOS E1-171 Y PANAMERICANA NORTE KM 5 1/2'));
            $infoAdicional->appendChild($campoDireccion);

            $campoTelefono = $xmlDoc->createElement('campoAdicional');
            $campoTelefono->setAttribute('nombre', 'Telefono');
            $campoTelefono->appendChild($xmlDoc->createTextNode('022478262'));
            $infoAdicional->appendChild($campoTelefono);

            $campoEmail = $xmlDoc->createElement('campoAdicional');
            $campoEmail->setAttribute('nombre', 'Email');
            $campoEmail->appendChild($xmlDoc->createTextNode('burbano_jorge@live.com'));
            $infoAdicional->appendChild($campoEmail);

            $campoVence = $xmlDoc->createElement('campoAdicional');
            $campoVence->setAttribute('nombre', 'Vence');
            $campoVence->appendChild($xmlDoc->createTextNode('15/08/2023'));
            $infoAdicional->appendChild($campoVence);

            $campoRegimenRIMPE = $xmlDoc->createElement('campoAdicional');
            $campoRegimenRIMPE->setAttribute('nombre', 'Contribuyente Regimen RIMPE');
            $campoRegimenRIMPE->appendChild($xmlDoc->createTextNode(' ')); // Agrega un espacio en blanco
            $infoAdicional->appendChild($campoRegimenRIMPE);

            // Agregar elementos al documento principal
            $factura->appendChild($infoTributaria);
            $factura->appendChild($infoFactura);
            $factura->appendChild($detallesXML);
            $factura->appendChild($infoAdicional);

            // Convertir el documento XML a una cadena
            $xmlString = $xmlDoc->saveXML();

            // echo "<pre>";
            //     var_dump($xmlString);
            // echo "<pre/>";
            
            $sql = "INSERT INTO doc_electro (tipo, clave, empresaContrasena, estado, identificacion, nombre, email, emailCliente, num_doc, fechaDoc, rucEmpresa, fileXML) 
            VALUES (:tipo, :clave, :empresaContrasena, :estado, :identificacion, :nombre, :email, :emailCliente, :num_doc, :fechaDoc, :rucEmpresa, :fileXML)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':tipo', $tipoDoc, \PDO::PARAM_STR);
            $stmt->bindParam(':clave', $clave, \PDO::PARAM_STR);
            $stmt->bindParam(':empresaContrasena', $emp_firmaElec, \PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, \PDO::PARAM_STR);
            $stmt->bindParam(':identificacion', $numero_ident, \PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $name, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $emp_email, \PDO::PARAM_STR);
            $stmt->bindParam(':emailCliente', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':num_doc', $num_doc_sri, \PDO::PARAM_STR);
            $stmt->bindParam(':fechaDoc', $fechaDoc, \PDO::PARAM_STR);
            $stmt->bindParam(':rucEmpresa', $emp_ruc, \PDO::PARAM_STR);
            $stmt->bindParam(':fileXML', $xmlString, \PDO::PARAM_STR);
            $stmt->execute();

            //return view('invoices.index'
            
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 

    }

    public function modulo11($numero) {
        $suma = 0;
        $peso = 2;
    
        // Recorre el número de derecha a izquierda
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += intval($numero[$i]) * $peso;
            $peso++;
    
            if ($peso > 7) {
                $peso = 2;
            }
        }
    
        $digitoVerificador = 11 - ($suma % 11);
        return $digitoVerificador === 10 ? 1 : $digitoVerificador;
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

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT invoices.id, invoices.number, invoices.date, invoices.total,
                        invoices.status, customers.name 
                        FROM invoices 
                        LEFT JOIN customers ON invoices.id_customer = customers.id ORDER BY invoices.number DESC";

            $result= $conexion->query($consulta);
            $invoices = [];
            
            foreach ($result as $fila) {
                $invoices[]=[
                    "id" => $fila['id'],
                    "date" => $fila['date'],
                    "number" => $fila['number'],
                    "customer" => $fila['name'],
                    "total" => $fila['total'],
                    "status" => $fila['status']
                ];
            }
            return view('invoices.index', compact('invoices'));
            
        }catch (\PDOException $e) {
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
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para obtener los productos, proveedores, series de facturas, términos de pago y datos de la empresa
            $consulta = "SELECT * FROM products WHERE is_active = 1;
                        SELECT id, name FROM vendors;
                        SELECT * FROM serie_factura  WHERE tipo_documento <> 4;
                        SELECT number FROM document_numbers WHERE type = 'Factura';
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
            return view('invoices.create', compact('items','vendors','seriesFact','numFact', 'payment_terms', 'datosEmp'));
            
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

        try {
            $db = new \PDO('mysql:host=localhost;dbname='.$nombreBD, 'root', '');       
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            //Customer
            $id_cliente = $request->id_cliente ?? null;
            $numero_ident = $request->ruc;
            $tipo_ident = $this->identificarTipoIdentificacion($numero_ident);
            $name = $request->cliente;
            $phone = $request->telefono;
            $email = $request->email;
            $direccion = $request->direccion;
            $id_vendedor = $request->vendedor;
            $balance = $request->saldo;
            if($id_cliente !== null){   
                $sql = "UPDATE customers 
                        SET tipo_ident = :tipo_ident,     
                            numero_ident = :numero_ident,
                            name = :name,
                            phone = :phone,  
                            email = :email, 
                            direccion = :direccion,
                            id_vendedor = :id_vendedor,
                            balance = :balance
                        WHERE id = :id_cliente ";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_cliente', $id_cliente, \PDO::PARAM_STR);                             
            }else{
                $sql = "INSERT INTO customers 
                    (tipo_ident, numero_ident, name, phone,
                    email, direccion, id_vendedor, balance)
                    VALUES (:tipo_ident, :numero_ident, :name,
                    :phone, :email, :direccion, :id_vendedor, :balance)";     
                    $stmt = $db->prepare($sql);
            }           
            
            $stmt->bindParam(':tipo_ident', $tipo_ident, \PDO::PARAM_STR);
            $stmt->bindParam(':numero_ident', $numero_ident, \PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, \PDO::PARAM_STR);
            $stmt->bindParam(':id_vendedor', $id_vendedor, \PDO::PARAM_STR); 
            $stmt->bindParam(':balance', $balance, \PDO::PARAM_STR);

            $stmt->execute();


            // Obtener ID del cliente insertado
            $lastId_cliente = $db->lastInsertId();
            //return $lastId_cliente;
            
            $id_cliente = ($lastId_cliente !== "0") 
                    ? $lastId_cliente 
                    : $request->id_cliente;

            // Generar número de factura único
            $length = 9; 
            $sales_number = "";
            $number = intval($request->number);
            $secuencial = $request->number;

            $sql = "SELECT * FROM invoices WHERE number = :secuencial LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':secuencial', $secuencial, \PDO::PARAM_STR);
            $stmt->execute();
            $if_exists = $stmt->rowCount();

            if ($if_exists == 1) {
                while ($if_exists == 1) {
                    $number = intval($secuencial);   
                    $number += 1;
                    $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                    $sales_number = $secuencial;
                    $stmt->execute();
                    $if_exists = $stmt->rowCount();
                }
            } else {
                $sales_number = $request->number;
            }

            //Invoice
            $number = $sales_number;
            $tipo_documento = $request->id_tipo_doc;
            $num_doc_sri = $request->serieNumero.$request->estableNumero.$request->secuencialNumero;
            $date = $request->fecha_fact;
            $phone = $request->telefono;
            $email = $request->email;
            $subtotal = $request->sumaSub;
            $taxes = $request->siIva;
            $base0 = $request->baseCero;
            $base_iva = $request->baseIva;
            $total = $request->total;
            $saldo = $request->saldo;

            $sql = "INSERT INTO invoices (number, tipo_documento, num_doc_sri, id_customer,
            date, phone, subtotal, email, taxes,  base0, base_iva, total, saldo) VALUES (:number, :tipo_documento, 
            :num_doc_sri, :id_cliente, :date, :phone, :subtotal, :email, :taxes,  :base0, :base_iva, :total, :saldo)";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':number', $number, \PDO::PARAM_STR);
            $stmt->bindParam(':tipo_documento', $tipo_documento, \PDO::PARAM_INT);
            $stmt->bindParam(':num_doc_sri', $num_doc_sri, \PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $id_cliente, \PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stmt->bindParam(':subtotal', $subtotal, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':taxes', $taxes, \PDO::PARAM_STR);
            $stmt->bindParam(':base0', $base0, \PDO::PARAM_STR);
            $stmt->bindParam(':base_iva', $base_iva, \PDO::PARAM_STR);
            $stmt->bindParam(':total', $total, \PDO::PARAM_STR);
            $stmt->bindParam(':saldo', $saldo, \PDO::PARAM_STR);
            $stmt->execute();
            
            $lastId_invoice = $db->lastInsertId();

            //Invoice Items
            $id_invoice = $lastId_invoice;

            $qty = 0;
            $lastId_invoiceItem = '';
            $itemRepetido = (object) array(
                'id_item' => array(),
                'lastId_invoiceItem' => array(),
                'qty' => array()
            );
            $resultados = [];
            
            $detalles = [];


            //Inventories
            $type = 'Invoice';
            $dateObject = new DateTime($date);
            $year = $dateObject->format('Y');
            $month = $dateObject->format('m');


            $sql_select_product_cost = "SELECT cost FROM product_balances WHERE id_item = :id_item AND year = :year AND month = :month";
            $stmt_select_product_cost = $db->prepare($sql_select_product_cost);

            $sql_select_product = "SELECT item_name FROM products WHERE id = :id_item";
            $stmt_select_product = $db->prepare($sql_select_product);

            // Preparar consulta SQL para insertar o actualizar elementos de la factura
            $sql_insert_update_item = "INSERT INTO invoices_items (id_invoice, id_item, qty, precio_neto, pvp, num_precio) 
                                        VALUES (:id_invoice, :id_item, :qty, :precio_neto, :pvp, :num_precio)
                                        ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)";
            $stmt_insert_update_item = $db->prepare($sql_insert_update_item);

            $sql_insert_inventories = "INSERT INTO inventories (type, date, id_transaction, id_item, cost, price, qty) 
                                        VALUES (:type, :date, :id_transaction, :id_item, :cost, :price, :qty)
                                        ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)";
            $stmt_insert_inventories = $db->prepare($sql_insert_inventories);

            $db->beginTransaction();
            //return $request->items;

            
            foreach ($request->items as $index => $item) {
                
                if ($item !== null) {  
                    $id_item = $item;
                    $qty = intval($request->cantidad[$index]);
                    $precio_neto = $request->pvp0_neto[$index];
                    $pvp = $request->subtotal[$index];
                    $num_precio = $request->num_precio[$index]; 
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
                    $stmt_insert_update_item->bindParam(':id_invoice', $id_invoice, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_update_item->bindParam(':precio_neto', $precio_neto, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':pvp', $pvp, \PDO::PARAM_STR);
                    $stmt_insert_update_item->bindParam(':num_precio', $num_precio, \PDO::PARAM_STR);
                    $stmt_insert_update_item->execute();

                    // Ejecutar consulta para insertar o actualizar inventories
                    $stmt_insert_inventories->bindParam(':type', $type, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':date', $date, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_transaction', $id_invoice, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':id_item', $id_item, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':cost', $product_cost, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':price', $precio_neto, \PDO::PARAM_STR);
                    $stmt_insert_inventories->bindParam(':qty', $qty, \PDO::PARAM_INT);
                    $stmt_insert_inventories->execute();

                    // Guardar detalles del producto
                    $detalles[$id_item] = [
                        'codigo' => $id_item,
                        'descripcion' => $item_name,
                        'qty' => $qty,
                        'precioUnitario' => floatval($precio_neto) / floatval($qty),
                        'precioTotalSinImpuesto' => $precio_neto,
                        'codigo_impuesto' => $impuesto->codigo_impuesto,
                        'porcentaje' => $impuesto->porcentaje,
                        'codigo_tarifa' =>  $impuesto->codigo_tarifa
                    ];

                    // Actualizar objeto $itemRepetido
                    if (!isset($resultados[$id_item])) {
                        $resultados[$id_item] = [
                            'qty' => $qty,
                            'lastId_invoiceItem' => $db->lastInsertId()
                        ];
                    } else {
                        $itemRepetido->id_item[] = $id_item;
                        $itemRepetido->lastId_invoiceItem[] = $resultados[$id_item]['lastId_invoiceItem'];
                        $itemRepetido->qty[] = $qty;
                    }
                }
            }      
            
            //return $detalles;

            $db->commit();

            // Payment_Customers
            $id_customer = $request->id_cliente ?? $id_cliente;
            $invoice = $request->number;
            $amount = $request->total;

            $sql = "INSERT INTO payment_customer 
                    (id_customer, date, invoice, amount)
                    VALUES (:id_customer, :date, :invoice, :amount)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_customer', $id_customer, \PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
            $stmt->bindParam(':invoice', $invoice, \PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, \PDO::PARAM_STR);
            $stmt->execute();

            $lastId_payment = $db->lastInsertId();
            
            // Payment_Details
            $id_payment = $lastId_payment;
            $id_term = $request->formaPago1;
            $valor = floatval($request->abono1);
            $reference = $request->numTransfer1;
            $banco = $request->banco1;
            $reference2 = $request->numTransfer2;
            $banco2 = $request->banco2;
            $valor2 =  floatval($request->abono2);

            $paymentDetailsData = [
                ['valor' => $valor, 'reference' => $reference, 'banco' => $banco],
                ['valor' => $valor2, 'reference' => $reference2, 'banco' => $banco2]
            ];

            $sql = "INSERT INTO payment_details 
                    (id_payment, date, id_term, valor, reference, banco)
                    VALUES (:id_payment, :date, :id_term, :valor, :reference, :banco)";

            $stmt = $db->prepare($sql);
            foreach ($paymentDetailsData as $paymentDetail) {
                if ($paymentDetail['valor'] && $paymentDetail['valor'] > 0) {
                    $stmt->bindParam(':id_payment', $lastId_payment, \PDO::PARAM_INT); // Asignar el id_payment
                    $stmt->bindParam(':date', $date, \PDO::PARAM_STR);
                    $stmt->bindParam(':id_term', $id_term, \PDO::PARAM_STR);
                    $stmt->bindParam(':valor', $paymentDetail['valor'], \PDO::PARAM_INT);
                    $stmt->bindParam(':reference', $paymentDetail['reference'], \PDO::PARAM_STR);
                    $stmt->bindParam(':banco', $paymentDetail['banco'], \PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            $sql = "UPDATE document_numbers SET number = :number WHERE type = 'Factura'";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':number', $number, \PDO::PARAM_INT);
            $stmt->execute();

            // Incrementar el número secuencial y actualizar la serie de facturas
            $sec = $request->secuencialNumero;
            $sec = ltrim($sec, '0');
            $sec = (int)$sec +1;

            if ($request->id_tipo_doc === "1") {
                $sql = "UPDATE serie_factura SET secuencial = :sec WHERE nombre = 'Factura'";

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

            if($tipo_ident != '7' || $tipo_documento !== "0"){     
                //return $num_doc_sri;
                $this->generarXML($emp_nombre, $emp_ruc, $emp_dir, $emp_tel,
                    $emp_email,
                    $emp_firmaElec,
                    $name,
                    $numero_ident,
                    $tipo_ident,
                    $phone,
                    $email,
                    $direccion,
                    $num_doc_sri,
                    $tipo_documento,
                    $subtotal,
                    $taxes,
                    $total,
                    $detalles,
                    $date
                );
                
            } 
            
            return redirect()->route('invoices.show', $id_invoice);
        
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
        //return $id;
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
                    invoices.number, invoices.date, invoices.subtotal, invoices.taxes, invoices.base0, 
                    invoices.base_iva, invoices.total, 
                    customers.numero_ident, customers.name, customers.phone, 
                    customers.email, customers.direccion, customers.balance 
                    FROM invoices
                    LEFT JOIN customers ON invoices.id_customer = customers.id
                    WHERE invoices.id = :id";

            $consulta2 = "SELECT 
                    invoices_items.qty, 
                    invoices_items.unit,
                    invoices_items.precio_neto, 
                    invoices_items.pvp,
                    products.id,
                    products.item_name, 
                    products.iva
                FROM invoices_items 
                LEFT JOIN products ON invoices_items.id_item = products.id
                WHERE invoices_items.id_invoice = :id";

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

            $consulta = "SELECT COUNT(*) as existe FROM documents WHERE doc_genera = :id";
            $statement = $conexion->prepare($consulta);
            $statement->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $existeNC = $statement->fetch(\PDO::FETCH_ASSOC);

            
            return view('invoices.show', compact('cabeceraInv', 'baseProductsInv', 'datosEmp', 'existeNC'));
            
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
        ///Consulta datos necesarios para usar el formulario
        $invoice = Invoices::find($id);
        $invoice_items = InvoicesItems::where('id_invoice', $invoice->id)->get();
        $customers = Customers::where('is_active', 1)->get();
        $shipto = ShipToCustomer::where('id_customer', $invoice->id_customer)->get();
        $items = Products::where('is_active', 1)->get()->toArray();
        $terms = PaymentTerms::all();
        $deliveries = DeliveryMethod::all();
        $taxes = Taxes::all();
        $types = ItemTypes::find(2);
        $warehouses = Warehouses::where('is_active', 1)->get();
        $id_order = Process_Order::where('id_invoice', $invoice->id)->value('id_order');
        $attach_file = null;
        $inventories_customer = null;
        if($id_order){
            $attach_files = AttachmentCustomer::where('type_transaction', 'SO')->where('id_transaction', $id_order)->get();
            $inventories_customer = InventoriesCustomers::where('type_transaction', 'SO')->where('id_transaction', $id_order)->get();
        }
        else{
            $attach_files = AttachmentCustomer::where('type_transaction', 'INV')->where('id_transaction', $invoice->id)->get();
            $inventories_customer = InventoriesCustomers::where('type_transaction', 'INV')->where('id_transaction', $id_order)->get();
        }

        $sizes = Sizes::all();
        $colors = Colors::all();
        $taxes = Taxes::all();

        ///Redirigir a la vista Edit, se adjunta todos los registro para que funcione el formulario.
        return view('invoices.edit', compact('invoice', 'invoice_items', 'customers', 'items', 'terms', 'deliveries', 'taxes', 'shipto', 'types', 'attach_files', 'inventories_customer', 'sizes', 'colors', 'warehouses'));
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
        ///Declaración de varibales
        $invoice = null;
        $total = "";
        $total = str_replace("$", " ", $request->order_total);
        $tax = str_replace("$", " ", $request->order_tax);
        $total = str_replace(",", "", $total);
        $temp_balance = 0;

        ///Actualiza la cabecera de la Invoice
        $invoice = Invoices::find($id);
        if (isset($request->select_shipto[0])) {
            $invoice->update([
                'number' => $request->number,
                'id_customer' => Customers::where('company_name', $request->select_customer)->value('id'),
                'date' => $request->date,
                'phone' => $request->phone,
                'email' => $request->email,
                'id_term' => $request->select_term,
                'billto' => $request->billto,
                'id_shipto' => $request->select_shipto[0],
                'id_warehouse' => $request->select_warehouse,
                'id_taxes' => $request->select_tax,
                'taxes' => $tax,
                'total' => $total
            ]);
        }
        else{
            $invoice->update([
                'number' => $request->number,
                'id_customer' => Customers::where('company_name', $request->select_customer)->value('id'),
                'date' => $request->date,
                'phone' => $request->phone,
                'email' => $request->email,
                'id_term' => $request->select_term,
                'billto' => $request->billto,
                'id_warehouse' => $request->select_warehouse,
                'id_taxes' => $request->select_tax,
                'taxes' => $tax,
                'total' => $total
            ]);
        }

        ///Elimina los productos de los detalles de facturas e inventarios, para volver a crearlos.
        $items = InvoicesItems::where('id_invoice', $invoice->id)->get();
        foreach ($items as $item) {
            $item->delete();
        }

        $inventories = Inventories::where('type', 'Invoice')->where('id_transaction', $invoice->id)->get();
        if ($inventories) {
            foreach ($inventories as $inventory) {
                $product=Products::where('id',$inventory->id_item)->first();
                $total_cost=$product->qty*$product->cost_avg;
                $delete_cost=$inventory->qty*$inventory->price;
                $cost_prom=($total_cost-$delete_cost)/($product->qty-$inventory->qty);

                if ($product->qty < 0) {
                    $product->qty = $product->qty+$inventory->qty;
                }
                else{
                    $product->qty = $product->qty-$inventory->qty;
                }
                $product->cost_avg = $cost_prom;
                $product->save();

                $inventory->delete();
            }
        }

        //Verifica si hubo cambios en el total de la factura, resta el valor anterior y suma el valor nuevo al saldo
        $customer = Customers::where('company_name', $request->select_customer)->first();
        $customer->balance -= $temp_balance;
        $customer->balance += $invoice->total;
        $customer->save();

        $index = 0;
        ///Cuenta el array principal para saber cuantos registros vienen
        $count = count($request->items);
        ///Crea los detalles de items Invoices
        for ($i=0; $i < $count; $i++) { 
            $size = isset($request->select_size[$index]);
            $color = isset($request->select_color[$index]);

            $type =  Products::where('item_name', $request->items[$i])->value('id_type');
            ///Revisa si el item es de tipo ensamblaje (4) o tipo inventario (2)
            if ($type == 4) {
                $id = Products::where('item_name', $request->items[$i])->value('id');
                if(!$id){
                    $id = Products_LabelBar::where('code', $request->items[$i])->value('id_item');
                }
                $items_production = AssamblyItems::where('id_item_main', $id)->get();

                foreach ($items_production as $itm) {
                    $items = InvoicesItems::create([
                        'id_invoice' => $invoice->id,
                        'id_warehouse' => $request->select_warehouse,
                        'id_item' => $id,
                        'id_size' => $size,
                        'id_color' => $color,
                        'qty' => $request->qty[$i],
                        'unit' => $request->unit[$i],
                        'price' => $request->price[$i],
                    ]);

                    Inventories::create([
                        'type' => 'Invoice',
                        'id_transaction' => $invoice->id,
                        'id_warehouse' => $request->select_warehouse,
                        'id_item' =>  $id,
                        'id_size' => $size,
                        'id_color' => $color,
                        'price' => $request->price[$i],
                        'qty' => $request->qty[$i]
                    ]);

                    $ticket_fecha = TicketSetItems::create([
                        'date' =>$invoice->date,
                        'num_factura'=> $invoice->number,
                        'id_customer' => $invoice->id_customer,
                        'id_item'=> Products::where('item_name', $request->items[$i])->value('id'),
                        'qty' =>  $request->qty[$i],
                        'status' => '0'
                    ]);
                    $index++;
                }
            } else {
                $id = Products::where('item_name', $request->items[$i])->value('id');
                $cost = Products::where('item_name', $request->items[$i])->value('cost_avg');

                if(!$id){
                    $id = Products_LabelBar::where('code', $request->items[$i])->value('id_item');
                }

                $items = InvoicesItems::create([
                    'id_invoice' => $invoice->id,
                    'id_warehouse' => $request->select_warehouse,
                    'id_item' => $id,
                    'id_size' => $size,
                    'id_color' => $color,
                    'qty' => $request->qty[$i],
                    'unit' => $request->unit[$i],
                    'cost' => $cost,
                    'price' => $request->price[$i],
                ]);

                Inventories::create([
                    'type' => 'Invoice',
                    'id_transaction' => $invoice->id,
                    'id_warehouse' => $request->select_warehouse,
                    'id_item' =>  $id,
                    'id_size' => $size,
                    'id_color' => $color,
                    'price' => $request->price[$i],
                    'qty' => $request->qty[$i]
                ]);
                $index++;
            }
        }

        ///Redirigir a la vista Index, se adjunta mensaje.
        return redirect()->route('invoices.index')->with('info', 'A record has been edited')->send();
    }

    /**
     * Delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoices::find($id);
        $is_payment = PaymentsDetails::where('invoice', $invoice->number)->count();

        if ($is_payment == 0) {
            //Delete Items, attachments and Inventories
            $items = InvoicesItems::where('id_invoice', $invoice->id)->get();
            
            foreach ($items as $item) {
                $inventory = Inventories::where('type', 'invoice')->where('id_transaction', $id)->where('id_item', $item->id_item)->first();
                if ($inventory) {
                    $product=Products::where('id',$inventory->id_item)->first();
                    $total_cost=$product->qty*$product->cost_avg;
                    $delete_cost=$inventory->qty*$inventory->price;
                    $cost_prom=($total_cost-$delete_cost)/($product->qty-$inventory->qty);

                    $product->qty = $product->qty-$inventory->qty;
                    $product->cost_avg = $cost_prom;
                    $product->save();

                    $inventory->delete();
                }
            }

            foreach ($items as $item) {
                $item->delete();
            }

            $invoice->delete();

            return redirect()->route('invoices.index')->with('info', 'A record has been deleted')->send();
        }
        else{
            return redirect()->route('invoices.index')->with('info', 'This invoice #'. $invoice->number .' has payments and cannot be deleted')->send();
        }
    }

    /**
     * Voided the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function void($id){
        $invoice = Invoices::find($id);
        $is_payment = PaymentsDetails::where('invoice', $invoice->number)->count();
        $items = InvoicesItems::select('id_item')->where('id_invoice', $id)->get();

        foreach ($items as $item) {
            $inventory = Inventories::where('type', 'invoice')->where('id_transaction', $id)->where('id_item', $item->id_item)->first();
            if ($inventory) {
                $product=Products::where('id',$inventory->id_item)->first();
                $total_cost=$product->qty*$product->cost_avg;
                $delete_cost=$inventory->qty*$inventory->price;
                $cost_prom=($total_cost-$delete_cost)/($product->qty-$inventory->qty);

                $product->qty = $product->qty-$inventory->qty;
                $product->cost_avg = $cost_prom;
                $product->save();

                $inventory->delete();
            }/*  */
        }

        if ($is_payment == 0) {
            $invoice->status = "Void";
            $invoice->taxes = 0.00;
            $invoice->total = 0.00;
            $invoice->save();

            return redirect()->route('invoices.index')->with('info', 'The invoice #'. $invoice->number .' has been voided')->send();
        }
        else{
            return redirect()->route('invoices.index')->with('info', 'This invoice #'. $invoice->number .' has payments and cannot be voided')->send();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approved($id)
    {  
        $length = 9;
        $invoice_number = DocumentNumbers::where('type', 'Invoices')->first();
        $secuencial = str_pad($invoice_number->number, $length,"0", STR_PAD_LEFT);
        $sales_order = SalesOrders::find($id);
        $sales_order->status = 'In process';
        $sales_order->save();
        $sales_number = null;
        
        $sales_items = SalesOrdersItems::where('id_order', $sales_order->id)->get();
        
        $if_exists = Invoices::where('number', $secuencial)->exists();
        if ($if_exists == 1) {
            while ($if_exists == 1)
            {
                $number = intval($secuencial);   
                $number += 1;
                $secuencial = str_pad($number, $length,"0", STR_PAD_LEFT);
                $sales_number = $secuencial;
                $if_exists = Invoices::where('number', $secuencial)->exists();
            }
        }
        else{
            $sales_number = $secuencial;
        }
        $invoice = Invoices::create([
            'number' => $sales_number,
            'id_customer' => $sales_order->id_customer,
            'date' => $sales_order->date,
            'phone' => $sales_order->phone,
            'email' => $sales_order->email,
            'id_term' => $sales_order->id_term,
            'billto' => $sales_order->billto,
            'id_shipto' => $sales_order->id_shipto,
            'id_warehouse' => $sales_order->id_warehouse, 
            'taxes' => $sales_order->taxes,
            'total' => $sales_order->total,
            'status' => 'Pending',
            'active' => 1
        ]);

        foreach ($sales_items as $item) {
            $id_process = Products::where('id', $item->id_item)->value('id_process');
            InvoicesItems::create([
            'id_invoice' => $invoice->id, 
            'id_item' => $item->id_item, 
            'id_size' => $item->id_size, 
            'id_color' => $item->id_color, 
            'qty' => $item->qty, 
            'unit' => $item->unit, 
            'price' => $item->price
        ]);

            Inventories::create([
            'type' => 'Invoice',
            'id_transaction' => $invoice->id,
            'id_warehouse' => $invoice->id_warehouse,
            'id_item' => $item->id_item,
            'id_size' => $item->id_size, 
            'id_color' => $item->id_color, 
            'price' => $item->price,
            'qty' =>  $item->qty 
            ]);

            if($id_process){                   
                $process = Processes::where('id', $id_process)->first();          
                $process_data = ProcessData::create([
                    'name' => $process->description,
                    'id_customer' => $sales_order->id_customer,
                    'has_responsible' => $process->responsible,
                    'id_responsible' => $process->id_responsible
                ]);

                $process_order = Process_Order::create([
                    'id_order' => $sales_order->id,
                    'id_process' => $process_data->id,
                    'id_invoice' => $invoice->id,  
                ]);

                $process_phases = ProcessPhases::where('id_process', $id_process)->get();
                foreach ($process_phases as $phase) {
                    $process_data_phase = ProcessDataPhase::create([
                        'id_data' => $process_data->id,
                        'name' => $phase->description,
                        'has_responsible' => $phase->has_responsible,
                        'id_responsable' => $phase->id_responsible
                    ]);

                    $process_stage = ProcessStage::where('id_phase', $phase->id)->get();
                    foreach ($process_stage as $stage) {
                            $process_data_stage = ProcessDataStage::create([
                                'id_phases' => $process_data_phase->id,
                                'name' => $stage->description,
                                'has_condition' => $stage->has_condition,
                                'has_attachment_customer' => $stage->has_attachment_customer,
                                'has_inventory_received' => $stage->has_inventory_received,
                                'has_responsible' => $stage->has_responsible,
                                'id_responsible' => $stage->id_responsible,
                                'has_date' => $stage->has_date,
                                'has_instructions' => $stage->has_instructions,
                                'has_attachment' => $stage->has_attachment,
                                'has_comparison' => $stage->has_comparison,
                                'has_send_mail' => $stage->has_send_mail
                            ]);
                            if($process_data_stage->has_condition == 1){
                                $process_condition = ProcessCondition::where('id_stage', $stage->id)->first();

                                if($process_condition){
                                    ProcessDataCondition::create([
                                        'id_stage' => $process_data_stage->id,
                                        'action_yes' => $process_condition->action_yes,
                                        'action_no' => $process_condition->action_no
                                    ]);
                                }
                            }
                    }
                }
            }
        }

        $number = intval($invoice_number->number) + 1;
        $invoice_number->number = $number;
        $invoice_number->save();

        return redirect()->route('invoices.index')->with('info', 'A new record has been created')->send();

    }

    public function verificarCliente($ruc)
    {
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $consulta = "SELECT * FROM customers WHERE numero_ident = '{$ruc}'";
            $result= $conexion->query($consulta);

            $customer = [];

            foreach ($result as $fila) {
                $customer[]=[
                    "id" => $fila['id'],
                    "cliente" => $fila['name'],
                    "email" => $fila['email'],
                    "telefono" => $fila['phone'],
                    "direccion" => $fila['direccion']
                ];
            }

            return json_encode($customer);
            
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }

    public function tipoDocumento($tipo)
    {
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $consulta = "SELECT number FROM document_numbers WHERE type = '{$tipo}'";
            $result= $conexion->query($consulta);

            $tipo =  $result->fetch();

            return json_encode($tipo);
            
        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }
}
