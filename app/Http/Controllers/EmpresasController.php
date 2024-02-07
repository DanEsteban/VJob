<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activacion;
use App\Models\Empresas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
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

                "bills" => "id bigint(20) UNSIGNED NOT NULL,
                    number varchar(20) NOT NULL,
                    id_vendor int(11) NOT NULL,
                    date date NOT NULL,
                    phone varchar(100) DEFAULT NULL,
                    email varchar(255) DEFAULT NULL,
                    id_term int(11) DEFAULT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    billto varchar(255) DEFAULT NULL,
                    total decimal(8,2) NOT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "bills_items" => "id bigint(20) UNSIGNED NOT NULL,
                    id_bill int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "contact_customers" => " id bigint(20) UNSIGNED NOT NULL,
                    id_customer int(11) NOT NULL,
                    name varchar(100) NOT NULL,
                    email varchar(100) NOT NULL,
                    phone varchar(20) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
                
                "customers" => "id bigint(20) UNSIGNED NOT NULL,
                    company_name varchar(250) NOT NULL,
                    first_name varchar(200) DEFAULT NULL,
                    midle_name varchar(200) DEFAULT NULL,
                    last_name varchar(200) DEFAULT NULL,
                    phone varchar(100) DEFAULT NULL,
                    work_phone varchar(100) DEFAULT NULL,
                    email varchar(100) DEFAULT NULL,
                    cc_email varchar(100) DEFAULT NULL,
                    id_terms int(11) DEFAULT NULL,
                    id_delivery int(11) DEFAULT NULL,
                    billto_street varchar(255) NOT NULL,
                    billto_company varchar(255) DEFAULT NULL,
                    billto_city varchar(100) DEFAULT NULL,
                    billto_postal varchar(20) DEFAULT NULL,
                    billto_state varchar(100) DEFAULT NULL,
                    balance decimal(8,2) DEFAULT 0.00,
                    is_active tinyint(1) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "customize_mails" => " id bigint(20) UNSIGNED NOT NULL,
                    type varchar(20) NOT NULL,
                    subject varchar(50) NOT NULL,
                    message varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
                
                "delivery_methods" => "id bigint(20) UNSIGNED NOT NULL,
                    name varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "document_numbers" => "id bigint(20) UNSIGNED NOT NULL,
                    type varchar(50) NOT NULL,
                    number int(11) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL", 
                
                "expenditures" => "id bigint(20) UNSIGNED NOT NULL,
                    number varchar(20) NOT NULL,
                    comments varchar(255) DEFAULT NULL,
                    date date NOT NULL,
                    total decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "expenditures_items" => "id bigint(20) UNSIGNED NOT NULL,
                    id_expenditure int(11) NOT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    cost decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "groups" => "id bigint(20) UNSIGNED NOT NULL,
                    name varchar(150) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "image_products" => "id bigint(20) UNSIGNED NOT NULL,
                    id_product int(11) NOT NULL,
                    image_name varchar(255) NOT NULL,
                    image_folder varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "incomes" => "id bigint(20) UNSIGNED NOT NULL,
                    number varchar(20) NOT NULL,
                    comments varchar(255) NOT NULL,
                    date date NOT NULL,
                    total decimal(8,2) NOT NULL,
                    tipo varchar(10) NOT NULL,
                    clave varchar(50) DEFAULT NULL,
                    autorizacion varchar(50) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "incomes_items" => "id bigint(20) UNSIGNED NOT NULL,
                    id_income int(11) NOT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    cost decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
                    
                "inventories" => "id bigint(20) UNSIGNED NOT NULL,
                    type varchar(255) NOT NULL,
                    id_transaction int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    num_transaction_one varchar(50) DEFAULT NULL,
                    num_transaction_two varchar(50) DEFAULT NULL,
                    cost decimal(12,5) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "invoices" => "id bigint(20) UNSIGNED NOT NULL,
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
                    taxes decimal(8,2) DEFAULT NULL,
                    total decimal(8,2) NOT NULL,
                    status varchar(100) NOT NULL DEFAULT 'Pending',
                    active tinyint(1) NOT NULL DEFAULT 1,
                    clave varchar(50) DEFAULT NULL,
                    autorizacion varchar(50) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "invoices_items" => "id bigint(20) UNSIGNED NOT NULL,
                    id_invoice int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "item_types" => "id bigint(20) UNSIGNED NOT NULL,
                    name varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "migrations" => "id int(10) UNSIGNED NOT NULL,
                    migration varchar(255) NOT NULL,
                    batch int(11) NOT NULL",

                "notes_customers" => "id bigint(20) UNSIGNED NOT NULL,
                    id_customer int(11) NOT NULL,
                    note varchar(255) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "parameters" => "id int(11) NOT NULL,
                    name varchar(100) NOT NULL,
                    type varchar(1) NOT NULL,
                    value varchar(100) NOT NULL",

                "payments_details" => "id bigint(20) UNSIGNED NOT NULL,
                    id_payment int(11) NOT NULL,
                    invoice varchar(10) NOT NULL,
                    amount decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "payment_customers" => "id bigint(20) UNSIGNED NOT NULL,
                    id_customer int(11) NOT NULL,
                    date date NOT NULL,
                    id_term int(11) DEFAULT NULL,
                    reference varchar(50) DEFAULT NULL,
                    card_number varchar(20) DEFAULT NULL,
                    exp_date varchar(5) DEFAULT NULL,
                    memo varchar(255) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "payment_terms" => "id bigint(20) UNSIGNED NOT NULL,
                    name varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
                
                "price_item" => "id int(10) NOT NULL,
                    id_item int(10) NOT NULL,
                    escala int(10) NOT NULL,
                    desde int(10) NOT NULL,
                    hasta int(10) NOT NULL,
                    precio decimal(10,0) NOT NULL",

                "products" => "id bigint(20) UNSIGNED NOT NULL,
                    id_type int(11) NOT NULL,
                    id_group int(11) DEFAULT NULL,
                    item_name varchar(100) NOT NULL,
                    part_number varchar(100) DEFAULT NULL,
                    id_unit_measure int(11) DEFAULT NULL,
                    purchase_description varchar(255) DEFAULT NULL,
                    sales_description varchar(255) DEFAULT NULL,
                    qty decimal(15,2) DEFAULT NULL,
                    cost decimal(12,5) DEFAULT NULL,
                    cost_avg decimal(12,5) DEFAULT NULL,
                    price decimal(8,2) DEFAULT NULL,
                    max_order decimal(8,2) DEFAULT NULL,
                    min_order decimal(8,2) DEFAULT NULL,
                    notes varchar(255) DEFAULT NULL,
                    id_process int(11) DEFAULT NULL,
                    is_active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL", 

                "products__label_bars" => " id bigint(20) UNSIGNED NOT NULL,
                    id_item int(11) NOT NULL,
                    code varchar(50) NOT NULL,
                    id_vendor int(11) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "product_balances" => "id int(11) NOT NULL,
                    id_item int(11) NOT NULL,
                    year varchar(4) NOT NULL,
                    month int(11) NOT NULL,
                    qty int(11) NOT NULL,
                    cost int(11) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "sales_orders" => "id bigint(20) UNSIGNED NOT NULL,
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
                    updated_at timestamp NULL DEFAULT NULL",
                
                "sales_orders_items" => "id bigint(20) UNSIGNED NOT NULL,
                    id_order int(11) NOT NULL,
                    id_warehouse int(11) DEFAULT NULL,
                    id_item int(11) NOT NULL,
                    id_size int(11) DEFAULT NULL,
                    id_color int(11) DEFAULT NULL,
                    qty decimal(8,2) NOT NULL,
                    unit varchar(50) DEFAULT NULL,
                    price decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "ship_to_customers" => "id bigint(20) UNSIGNED NOT NULL,
                    id_customer int(11) DEFAULT NULL,
                    name varchar(100) NOT NULL,
                    address varchar(255) NOT NULL,
                    company varchar(255) DEFAULT NULL,
                    city varchar(100) DEFAULT NULL,
                    postal varchar(20) DEFAULT NULL,
                    state varchar(100) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "taxes" => "id bigint(20) UNSIGNED NOT NULL,
                    description varchar(20) NOT NULL,
                    percentage decimal(8,2) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "unit_measures" => "id bigint(20) UNSIGNED NOT NULL,
                    abbreviation varchar(50) NOT NULL,
                    description varchar(100) NOT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
                
                "vendors" => "id bigint(20) UNSIGNED NOT NULL,
                    name varchar(200) NOT NULL,
                    contact varchar(100) DEFAULT NULL,
                    phone varchar(15) DEFAULT NULL,
                    email varchar(100) DEFAULT NULL,
                    billto_street varchar(255) DEFAULT NULL,
                    billto_company varchar(255) DEFAULT NULL,
                    billto_city varchar(100) DEFAULT NULL,
                    billto_postal varchar(20) DEFAULT NULL,
                    billto_state varchar(100) DEFAULT NULL,
                    balance decimal(8,2) DEFAULT NULL,
                    is_active tinyint(1) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "vendor_orders" => "id bigint(20) UNSIGNED NOT NULL,
                    date date NOT NULL,
                    number varchar(50) NOT NULL,
                    vendor_id int(11) NOT NULL,
                    total decimal(8,2) NOT NULL,
                    status varchar(50) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",    
                
                "vendor_order_items" => "id bigint(20) UNSIGNED NOT NULL,
                    order_id int(11) NOT NULL,
                    item_id int(11) NOT NULL,
                    qty decimal(8,2) NOT NULL,
                    price decimal(8,2) NOT NULL,
                    receive decimal(8,2) DEFAULT NULL,
                    balance decimal(8,2) DEFAULT NULL,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",

                "warehouses" => "id bigint(20) UNSIGNED NOT NULL,
                    wh_name varchar(50) NOT NULL,
                    is_active tinyint(1) NOT NULL DEFAULT 1,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL",
            );

            // Crea una conexión
            $conn = mysqli_connect($servername, $username, $password);

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

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
                
                // Itera a través del arreglo de definiciones de tablas y crea cada tabla
                foreach ($tablas as $nombreTabla => $columnas) {
                    crearTabla($conn, $nombreTabla, $columnas);
                }
                // Cerrar la conexión
                $conn->close();
            }
            $filename = $_FILES['rutaFirma']['name'];
            $directory = "firmas/".$request->input('cs_company')."/";
            if(!file_exists($directory)){
                mkdir($directory, 0777);
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
                        'ruta_firma' => $directory,
                        'clave_firma' => $request->claveFirma,
                        'fecha_creacion' => date("Y-m-d")
                    ]);

                User::create([
                    'id_empresa' => $empresa->id_empresa,
                    'name' => 'admin',
                    'email' => $request->cs_mail,
                    'password' => bcrypt($request->ruc),
                    'role_id' => 1,             
                ]);    
            }
            closedir($dir);
            $rol_activacion = Activacion::where('ruc', $request->ruc)->first();
            $rol_activacion->es_activo = 1;
            $rol_activacion->save();

            return redirect()->route('login');
        //     $htmlContent = '
        //         <!DOCTYPE html>
        //         <html lang="es">
        //         <head>
        //             <meta charset="UTF-8">
        //             <meta name="viewport" content="width=device-width, initial-scale=1.0">
        //             <title>Información Creada</title>
        //             <style>
        //                 body {
        //                     font-family: Arial, sans-serif;
        //                     display: flex;
        //                     align-items: center;
        //                     justify-content: center;
        //                     height: 100vh;
        //                     margin: 0;
        //                 }

        //                 .card {
        //                     width: 300px;
        //                     padding: 20px;
        //                     border: 1px solid #ccc;
        //                     border-radius: 8px;
        //                     box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        //                     text-align: center;
        //                 }

        //                 .button {
        //                     display: inline-block;
        //                     padding: 10px 20px;
        //                     margin-top: 10px;
        //                     text-decoration: none;
        //                     color: #fff;
        //                     background-color: #007bff;
        //                     border-radius: 4px;
        //                     transition: background-color 0.3s;
        //                 }

        //                 .button:hover {
        //                     background-color: #0056b3;
        //                 }
        //             </style>
        //         </head>
        //         <body>

        //             <div class="card">
        //                 <p>Información creada correctamente</p>
        //                 <a href="/login" class="button">Ir al login</a>
        //             </div>

        //         </body>
        //         </html>
        //         ';

        // echo $htmlContent;
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
