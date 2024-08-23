<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activacion;
use App\Models\Empresas;
use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;
use PDO;
use PDOException;


class EmpresasController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('can:empresa.index')->only('index'); 
    //     $this->middleware('can:empresa.create')->only('create', 'store');
    //     $this->middleware('can:empresa.edit')->only('edit', 'update');
    //     $this->middleware('can:empresa.show')->only('show');
    //     $this->middleware('can:empresa.destroy')->only('destroy');  
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $ruc = $request->ruc;
        $email = $request->email;
        return view('empresa.create', compact('ruc','email'));
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
        //return $_FILES['rutaFirma'];
        $palabras = explode(" ", $request->cs_company); // Divide el string en palabras

        $nombreBD = "";
        foreach ($palabras as $palabra) {
            $nombreBD .= substr($palabra, 0, 4); // Toma las primeras 4 letras de cada palabra
        }

            $nombreBD = strtolower($nombreBD);

            $cadena_conexion = "mysql:host=localhost;dbname=$nombreBD;charset=utf8mb4";
            $servername = "localhost";
            $username = "root";
            $password = "";

            $tablas = array(

                "bills" => "id int(20) NOT NULL AUTO_INCREMENT,
                    number varchar(20) NOT NULL,
                    tipo_documento int(2) DEFAULT NULL,
                    num_doc_sri varchar(20) DEFAULT NULL,
                    id_vendor int(11) NOT NULL,
                    date date NOT NULL,
                    date_ingreso_bodega date DEFAULT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(255) DEFAULT NULL,
                    id_term int(11) DEFAULT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    direccion varchar(255) DEFAULT NULL,
                    id_taxes int(11) NOT NULL,
                    taxes decimal(8,2) DEFAULT NULL,
                    base0 decimal(8,2) DEFAULT NULL,
                    base_iva decimal(12,2) DEFAULT NULL,
                    subtotal decimal(8,2) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    saldo decimal(8,2) DEFAULT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "bills_items" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_bill int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_taxes int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "contact_customers" => " id int(20) NOT NULL AUTO_INCREMENT,
                    id_customer int(11) NOT NULL,
                    name varchar(100) NOT NULL,
                    email varchar(100) NOT NULL,
                    phone varchar(20) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",
                
                "customers" => "id int(20) NOT NULL AUTO_INCREMENT,
                    tipo_ident varchar(2) NOT NULL,
                    numero_ident varchar(13) NOT NULL,
                    name varchar(200) DEFAULT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(100) DEFAULT NULL,
                    direccion varchar(60) DEFAULT NULL,
                    id_vendedor int(11) NOT NULL,
                    balance decimal(8,2) DEFAULT 0.00,
                    is_active tinyint(1) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "customize_mails" => " id int(20) NOT NULL AUTO_INCREMENT,
                    type varchar(20) NOT NULL,
                    subject varchar(50) NOT NULL,
                    message varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",
                
                "delivery_methods" => "id int(20) NOT NULL AUTO_INCREMENT,
                    name varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "documents" => "id int(11) NOT NULL AUTO_INCREMENT,
                    number varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    tipo_documento int(2) NOT NULL,
                    comentario varchar(255) NOT NULL,
                    num_doc_sri varchar(20) NOT NULL,
                    id_customer int(11) NOT NULL,
                    date date NOT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(255) DEFAULT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    subtotal decimal(8,2) DEFAULT NULL,
                    id_taxes int(11) DEFAULT NULL,
                    taxes decimal(8,2) DEFAULT NULL,
                    base0 decimal(12,2) NOT NULL,
                    base_iva decimal(12,2) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    saldo decimal(8,2) NOT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    clave varchar(50) DEFAULT NULL,
                    autorizacion varchar(50) DEFAULT NULL,
                    fecha_autorizacion datetime DEFAULT NULL,
                    doc_genera int(11) NOT NULL,
                    estado_sri varchar(20) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "documents_items" => "id int(11) NOT NULL AUTO_INCREMENT,
                    id_document int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_taxes tinyint(1) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    precio_neto decimal(12,5) NOT NULL,
                    pvp decimal(12,5) NOT NULL,
                    num_precio int(11) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "document_numbers" => "id int(20) NOT NULL AUTO_INCREMENT,
                    type varchar(50) NOT NULL,
                    number int(11) NOT NULL,
                    PRIMARY KEY (id)", 

                "groups" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_type int(11) NOT NULL,
                    name varchar(150) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "image_products" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_product int(11) NOT NULL,
                    image_name varchar(255) NOT NULL,
                    image_folder varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "movements" => "id int(20) NOT NULL AUTO_INCREMENT,
                    number varchar(20) NOT NULL,
                    comments varchar(255) DEFAULT NULL,
                    date date NOT NULL,
                    total decimal(8,2) NOT NULL,
                    tipo varchar(10) NOT NULL,
                    clave varchar(50) DEFAULT NULL,
                    autorizacion varchar(50) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "movements_items" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_movement int(11) NOT NULL,
                    id_item int(11) NOT NULL,
                    qty int(11) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    cost decimal(8,2) NOT NULL,
                    total_cost decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",
                    
                "inventories" => "id int(20) NOT NULL AUTO_INCREMENT,
                    type varchar(255) NOT NULL,
                    date date DEFAULT NULL,
                    id_transaction int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    cost decimal(12,5) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "invoices" => "id int(20) NOT NULL AUTO_INCREMENT,
                    number varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    tipo_documento int(2) NOT NULL,
                    num_doc_sri varchar(20) NOT NULL,
                    id_customer int(11) NOT NULL,
                    date date NOT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(255) DEFAULT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    subtotal decimal(8,2) DEFAULT NULL,
                    id_taxes int(11) DEFAULT NULL,
                    taxes decimal(8,2) DEFAULT NULL,
                    base0 decimal(12,2) NOT NULL,
                    base_iva decimal(12,2) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    saldo decimal(8,2) NOT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    clave varchar(50) DEFAULT NULL,
                    autorizacion varchar(50) DEFAULT NULL,
                    fecha_autorizacion datetime DEFAULT NULL,
                    doc_genera int(11) NOT NULL,
                    estado_sri varchar(20) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "invoices_items" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_invoice int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_taxes tinyint(1) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    precio_neto decimal(12,5) NOT NULL,
                    pvp decimal(12,5) NOT NULL,
                    num_precio int(11) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "item_types" => "id int(20) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "migrations" => "id int(10) NOT NULL AUTO_INCREMENT,
                    migration varchar(255) NOT NULL,
                    batch int(11) NOT NULL,
                    PRIMARY KEY (id)",

                "notes_customers" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_customer int(11) NOT NULL,
                    note varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "parameters" => "id int(11) NOT NULL AUTO_INCREMENT,
                    name varchar(100) NOT NULL,
                    type varchar(1) NOT NULL,
                    value varchar(100) NOT NULL,
                    PRIMARY KEY (id)",

                "payment_customer" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_customer int(11) NOT NULL,
                    date date NOT NULL,
                    invoice varchar(20) NOT NULL,
                    amount decimal(12,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "payment_details" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_payment int(20) NOT NULL,
                    date date NOT NULL,
                    id_term int(11) NOT NULL,
                    valor decimal(12,2) NOT NULL,
                    reference varchar(50) DEFAULT NULL,
                    banco varchar(30) DEFAULT NULL,
                    card_number varchar(20) DEFAULT NULL,
                    exp_date varchar(5) DEFAULT NULL,
                    memo varchar(255) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",


                "payment_terms" => "id int(20) NOT NULL AUTO_INCREMENT,
                    name varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "price_products" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_product int(11) NOT NULL,
                    num_precio int(11) NOT NULL,
                    precio decimal(10,5) DEFAULT NULL,
                    precio_iva decimal(10,5) NOT NULL,
                    desde int(10) NOT NULL,
                    hasta int(10) NOT NULL,
                    PRIMARY KEY (id)",

                "products" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_type int(11) NOT NULL,
                    id_group int(11) DEFAULT NULL,
                    item_name varchar(100) NOT NULL,
                    bar_code varchar(100) DEFAULT NULL,
                    si_iva tinyint(1) NOT NULL,
                    iva tinyint(1) NOT NULL,
                    id_unit_measure int(11) DEFAULT NULL,
                    notes varchar(255) DEFAULT NULL,
                    is_active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)", 

                "products__label_bars" => " id int(20) NOT NULL AUTO_INCREMENT,
                    id_item int(11) NOT NULL,
                    code varchar(50) NOT NULL,
                    id_vendor int(11) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "product_balances" => "id int(11) NOT NULL AUTO_INCREMENT,
                    id_item int(11) NOT NULL,
                    year varchar(4) NOT NULL,
                    month int(11) NOT NULL,
                    qty int(11) NOT NULL,
                    cost decimal(8,2) NOT NULL,
                    avg_cost decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "sales_orders" => "id int(20) NOT NULL AUTO_INCREMENT,
                    number varchar(20) NOT NULL,
                    id_customer int(11) NOT NULL,
                    date date NOT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(255) DEFAULT NULL,
                    id_term int(11) DEFAULT NULL,
                    billto varchar(255) DEFAULT NULL,
                    id_shipto int(11) DEFAULT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    porcentage decimal(11,0) DEFAULT NULL,
                    id_taxes int(11) DEFAULT NULL,
                    taxes decimal(8,2) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",
                
                "sales_orders_items" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_order int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "serie_factura" => "id int(20) NOT NULL AUTO_INCREMENT,
                    nombre varchar(20) NOT NULL,
                    tipo_documento int(2) NOT NULL,
                    establecimiento varchar(3) NOT NULL,
                    punto_emision varchar(3) NOT NULL,
                    secuencial int(9) NOT NULL,
                    PRIMARY KEY (id)",

                "ship_to_customers" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_customer int(11) DEFAULT NULL,
                    name varchar(100) NOT NULL,
                    address varchar(255) NOT NULL,
                    company varchar(255) DEFAULT NULL,
                    city varchar(100) DEFAULT NULL,
                    postal varchar(20) DEFAULT NULL,
                    state varchar(100) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "taxes" => "id int(20) NOT NULL AUTO_INCREMENT,
                    description varchar(20) NOT NULL,
                    percentage decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "unit_measures" => "id int(20) NOT NULL AUTO_INCREMENT,
                    abbreviation varchar(50) NOT NULL,
                    description varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",
                
                "vendors" => "id int(20) NOT NULL AUTO_INCREMENT,
                    tipo_ident varchar(2) NOT NULL,
                    numero_ident varchar(13) NOT NULL,
                    name varchar(200) DEFAULT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(200) DEFAULT NULL,
                    direccion varchar(255) DEFAULT NULL,
                    balance decimal(8,2) DEFAULT NULL,
                    is_active tinyint(1) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "vendor_orders" => "id int(20) NOT NULL AUTO_INCREMENT,
                    date date NOT NULL,
                    number varchar(50) NOT NULL,
                    vendor_id int(11) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    status varchar(50) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",    
                
                "vendor_order_items" => "id int(20) NOT NULL AUTO_INCREMENT,
                    order_id int(11) NOT NULL,
                    item_id int(11) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    price decimal(8,2) NOT NULL,
                    receive decimal(8,2) DEFAULT NULL,
                    balance decimal(8,2) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "warehouses" => "id int(20) NOT NULL AUTO_INCREMENT,
                    wh_name varchar(50) NOT NULL,
                    direction varchar(250) NOT NULL,
                    phone varchar(10) NOT NULL,
                    city varchar(40) NOT NULL,
                    estab_pv varchar(6) NOT NULL,
                    is_active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)",

                "warehouse_document" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_warehouse int(11) NOT NULL,
                    document_type varchar(2) NOT NULL,
                    secuencial int(11) NOT NULL,
                    PRIMARY KEY (id)",

                "warehouse_product" => "id int(20) NOT NULL AUTO_INCREMENT,
                    id_warehouse int(11) NOT NULL,
                    id_product int(11) NOT NULL,
                    qty decimal(12,2) NOT NULL,
                    cost decimal(12,5) NOT NULL,
                    PRIMARY KEY (id)",
            );

            // Crea una conexión
            $conn = mysqli_connect($servername, $username, $password);

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            if ($_FILES['logo']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['logo']['tmp_name'])) {
                // Obtener la información del archivo
                $nombreArchivo = $_FILES['logo']['name'];
                $archivoTemporal = $_FILES['logo']['tmp_name'];
                // Verificar si el archivo es una imagen
                $esImagen = getimagesize($archivoTemporal);
                if ($esImagen !== false) {
                    // Definir la carpeta de destino con el nombre de la empresa
                    $nombreEmpresa = $request->input('cs_company');
                    $carpetaDestino = "img/" . $nombreEmpresa . "/";
                    
                    // Verificar si la carpeta de destino existe, si no, crearla
                    if (!file_exists($carpetaDestino)) {
                        if (!mkdir($carpetaDestino, 0777, true)) {
                            die('Error al crear la carpeta de destino.');
                        }
                    }
                    // Mover el archivo a la carpeta de destino
                    $rutaArchivo = $carpetaDestino . $nombreArchivo;
                    if (move_uploaded_file($archivoTemporal, $rutaArchivo)) {
                        echo "La imagen se ha subido correctamente.";
                    } else {
                        echo "Error al subir la imagen.";
                        $rutaArchivo = null;
                    }
                } else {
                    echo "El archivo no es una imagen válida.";
                    $rutaArchivo = null;
                }
            } else {
                echo "Error al subir el archivo.";
                $rutaArchivo = null;
            }

            //FIRMA ARCHIVO.P12
            /*
                // $file = $request->file('rutaFirma');
                // $nuevoNombre = $request->ruc.'.p12';

                // $carpeta = 'firmas';
                // $fullPath = $carpeta . '/' . $nuevoNombre;
                //Storage::disk('local')->put($fullPath, file_get_contents($file));
                // Storage::disk('local')->put($fullPath, file_get_contents($file));
                // if (Storage::disk('local')->exists($fullPath)) {
                //     $empresa = Empresas::create([
                //         'nombre' => $request->cs_company,
                //         'ruc' => $request->ruc,
                //         'direccion' => $request->direccion,
                //         'telefono' => $request->cs_phone,
                //         'correo' => $request->cs_mail,
                //         'id_tipo_contribuyente' => $request->tipoContribuyente,
                //         'base_datos' => $nombreBD,
                //         'cadena_conexion' => $cadena_conexion,
                //         'ruta_firma' => $fullPath,
                //         'clave_firma' => $request->claveFirma,
                //         'ruta_logo' => $rutaArchivo,
                //         'fecha_creacion' => date("Y-m-d")
                //     ]);
                //     //dd($empresa);
                //     $user = User::create([
                //         'id_empresa' => $empresa->id_empresa,
                //         'name' => 'admin',
                //         'email' => $request->cs_mail,
                //         'password' => bcrypt($request->ruc),
                //         'role_id' => 1,             
                //     ]);    

                //     ModelHasRole::create([
                //         'role_id' => $user->role_id,  
                //         'model_type' => 'App\Models\User',  
                //         'model_id' => $user->id
                //     ]);
                    
                // }
            */
            $filename = $_FILES['rutaFirma']['name'];
            $directory = "firmas/".$request->input('cs_company')."/";
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true); // Añadido 'true' para permitir la creación recursiva
            }
            $dir = opendir($directory);

            if(copy($_FILES['rutaFirma']['tmp_name'], $directory.$filename)){
                $empresa = Empresas::create([
                        'nombre' => $request->cs_company,
                        'ruc' => $request->ruc,
                        'direccion' => $request->direccion,
                        'telefono' => $request->cs_phone,
                        'correo' => $request->cs_mail,
                        'id_tipo_contribuyente' => $request->tipoContribuyente,
                        'base_datos' => $nombreBD,
                        'cadena_conexion' => $cadena_conexion,
                        'ruta_firma' => $directory.$filename,
                        'clave_firma' => $request->claveFirma,
                        'ruta_logo' => $rutaArchivo,
                        'fecha_creacion' => date("Y-m-d")
                    ]);

                $user = User::create([
                    'id_empresa' => $empresa->id_empresa,
                    'name' => 'admin',
                    'email' => $request->cs_mail,
                    'password' => bcrypt($request->ruc),
                    'role_id' => 1,             
                ]);    

                ModelHasRole::create([
                    'role_id' => $user->role_id,  
                    'model_type' => 'App\Models\User',  
                    'model_id' => $user->id
                ]);
                
            }
            closedir($dir);
            
            $databaseExists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$nombreBD]);

            if (empty($databaseExists)) {
                $sql = "CREATE DATABASE $nombreBD";

                if ($conn->query($sql) === TRUE) {
                    echo "Base de datos creada exitosamente";
                } else {
                    echo "Error al crear la base de datos: " . $conn->error;
                }
        
                $conn->select_db("$nombreBD");
                        
                // Función para crear una tabla
                function crearTabla($conn, $nombreTabla, $columnas) {
                    $sql = "CREATE TABLE $nombreTabla ($columnas)";
                    if ($conn->query($sql) === TRUE) {
                        echo "Tabla '$nombreTabla' creada exitosamente<br>";
                    } else {
                        echo "Error al crear la tabla '$nombreTabla': " . $conn->error . "<br>";
                    }
                }

                // Función para insertar datos iniciales en la tabla parameters
                function insertarDatosInicialesCustomers($conn, $datos) {
                    $sql = "INSERT INTO customers (tipo_ident, numero_ident, name, phone, direccion, id_vendedor, balance) VALUES $datos";
                
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la tabla 'customers' exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la tabla 'customers': " . $conn->error . "<br>";
                    }
                }

                function insertarDatosInicialesParameters($conn, $datos) {
                    $sql = "INSERT INTO parameters (name, type, value) VALUES $datos";
                
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la tabla 'parameters' exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la tabla 'parameters': " . $conn->error . "<br>";
                    }
                }
                
                function insertarDatosInicialesdocument_numbers($conn, $datos) {
                    $sql = "INSERT INTO document_numbers (type, number) VALUES $datos";
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la otra tabla exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la otra tabla: " . $conn->error . "<br>";
                    }
                }

                function insertarDatosInicialesserie_factura($conn, $datos) {
                    $sql = "INSERT INTO serie_factura (nombre, tipo_documento, establecimiento, punto_emision, secuencial) VALUES $datos";
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la otra tabla exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la otra tabla: " . $conn->error . "<br>";
                    }
                }

                function insertarDatosInicialespayment_terms($conn, $datos) {
                    $sql = "INSERT INTO payment_terms (name) VALUES $datos";
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la otra tabla exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la otra tabla: " . $conn->error . "<br>";
                    }
                }

                function insertarDatosInicialesitem_types($conn, $datos) {
                    // Prepara la consulta SQL para insertar los datos
                    $sql = "INSERT INTO item_types (name) VALUES $datos";
                
                    // Ejecuta la consulta
                    if ($conn->query($sql) === TRUE) {
                        echo "Datos insertados en la tabla exitosamente<br>";
                    } else {
                        echo "Error al insertar datos en la tabla: " . $conn->error . "<br>";
                    }
                }
                
                // Array de datos para insertar en cada tabla
                $datosInicialesCustomers = array(
                    "('07', '9999999999999', 'CONSUMIDOR FINAL (SOLO CONTADOS)', '999999999','SIN DIRECCIÓN', '0', '0.00')"
        
                );

                $datosInicialesParameters = array(
                    "('PERIODO ACTIVO', 'N', '" . date('Y') . "')",
                    "('SERIE FACTURA', 'C', '001001')",
                    "('emp_nombre', 'C', '{$request->cs_company}')",
                    "('emp_ruc', 'N', '{$request->ruc}')",
                    "('emp_dir', 'C', '{$request->direccion}')",
                    "('emp_tel', 'C', '{$request->cs_phone}')",
                    "('emp_email', 'N', '{$request->cs_mail}')",
                    "('emp_firmaElec', 'N', '{$request->claveFirma}')",
                    "('emp_ruta_p12', 'N', '{$directory }')",
                    "('emp_ruta_logo', 'N', '{$rutaArchivo}')"
                );

                $datosInicialesdocument_numbers = array(
                    "('Factura', '0')",
                    "('Egreso', '1')",
                    "('Ingreso', '1')",
                    "('FacturaCompra', '1')",
                    "('NotaCreditoCliente', '1')",
                );
                
                $datosInicialesserie_factura = array(
                    "('Nota de Venta', '0', '999', '999', '1')",
                    "('Factura', '1', '001', '001', '1')",
                    "('Nota de Credito', '4', '001', '001', '1')",
                );

                $datosInicialespayment_terms = array(
                    "('Efectivo')",
                    "('Transferencia')",
                );

                $datosInicialesitem_types = array(
                    "('Servicio')",
                    "('ParteInventario')",
                    "('No-ParteInventario')",
                );


                // Itera a través del arreglo de definiciones de tablas y crea cada tabla
                foreach ($tablas as $nombreTabla => $columnas) {
                    crearTabla($conn, $nombreTabla, $columnas);
                    
                    // Si la tabla es 'customers', inserta los datos iniciales
                    if ($nombreTabla === 'customers') {
                        foreach ($datosInicialesCustomers as $datos) {
                            insertarDatosInicialesCustomers($conn, $datos);
                        }
                    }

                    if ($nombreTabla === 'parameters') {
                        foreach ($datosInicialesParameters as $datos) {
                            insertarDatosInicialesParameters($conn, $datos);
                        }
                    }

                    if ($nombreTabla === 'document_numbers') {
                        foreach ($datosInicialesdocument_numbers as $datos) {
                            insertarDatosInicialesdocument_numbers($conn, $datos);
                        }
                    }

                    if ($nombreTabla === 'serie_factura') {
                        foreach ($datosInicialesserie_factura as $datos) {
                            insertarDatosInicialesserie_factura($conn, $datos);
                        }
                    }

                    if ($nombreTabla === 'payment_terms') {
                        foreach ($datosInicialespayment_terms as $datos) {
                            insertarDatosInicialespayment_terms($conn, $datos);
                        }
                    }

                    if ($nombreTabla === 'item_types') {
                        foreach ($datosInicialesitem_types as $datos) {
                            insertarDatosInicialesitem_types($conn, $datos);
                        }
                    }
                }
                // Cerrar la conexión
                $conn->close();
            }           
            
            $rol_activacion = Activacion::where('ruc', $request->ruc)->first();
            $rol_activacion->es_activo = 1;
            $rol_activacion->save();

            return redirect()->route('login')->with('message', 'Se ha creado una nueva empresa!');

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
