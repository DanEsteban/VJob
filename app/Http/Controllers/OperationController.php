<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Taxes;
use App\Models\Sizes;
use App\Models\Colors;
use App\Models\Vendors;
use App\Models\Groups;
use App\Models\Products;
use App\Models\Customers;
use App\Models\UnitMeasure;
use App\Models\ImageProduct;
use App\Models\PaymentTerms;
use App\Models\SalesOrders;
use App\Models\SalesOrdersItems;
use App\Models\ShipToCustomer;
use App\Models\DocumentNumbers;
use App\Models\DeliveryMethod;
use App\Models\Attachments;
use App\Models\AttachmentCustomer;
use App\Models\ContactCustomer;
use App\Models\Invoices;
use App\Models\Inventories;
use App\Models\InventoriesCustomers;
use App\Models\Products_Colors;
use App\Models\Products_Sizes;
use App\Models\CustomizeMail;
use App\Models\NotesCustomer;
use App\Models\PaymentCustomers;
use App\Models\Products_LabelBar;
use Illuminate\Support\Facades\Mail;
use App\Mail\NoticesMail;
use App\Models\PaymentsDetails;
use App\Models\Activacion;
use App\Models\Empresas;
use App\Models\Impuesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;




class OperationController extends Controller
{

    public function comprobacionbarCode(Request $request){
        $dsn = "mysql:host=localhost;dbname=empresa1";
        $usuario = "root";
        $contrasena = "";
        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);

            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta="SELECT * FROM products WHERE bar_code = '{$request->barCode}'";

            $result= $conexion->query($consulta);

            if ($result->rowCount() === 1) {
                return "El codigo ya existe";
            }

            //return json_encode($groups);

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }

    public function filtrarLinea(Request $request){
        
        $nombreBD =  App::make('dataBase');
        $dsn = "mysql:host=localhost;dbname=" . $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);

            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta2="SELECT * FROM groups WHERE id_type = '{$request->nombre}'";

            $result2= $conexion->query($consulta2);
            
            $groups=[];

            foreach ($result2 as $item) {
                $groups[]= [
                    'id' => $item['id'],
                    'nombre' => $item['name']
                ];   
            }

            return json_encode($groups);

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }
    // public function verificarUser(Request $request)
    // {
    //     $credentials = $request->only('usuario', 'password');
    //     $clave = $request->password;
    //     $usuarios = User::where('usuario', $request->usuario)->first();
        
    //     if($usuarios && password_verify($clave, $usuarios->password)){
    //         return response()->json(['success' => true]);
    //     }
    //     else{
    //         return response()->json(['success' => false, 'message' => 'Credenciales incorrectas']);
    //     }
    // }

    //Customers Operation
    public function setCustomer(Request $request, $id){ 

        $customer = new Customers;
        $customer->company_name = $request->cs_company;
        $customer->first_name = $request->cs_firstname;
        $customer->midle_name = $request->cs_midlename;
        $customer->last_name = $request->cs_lastname;
        $customer->phone = $request->cs_phone;
        $customer->work_phone = $request->cs_workphone;
        $customer->email = $request->cs_mail;
        $customer->cc_email = $request->cs_ccemail;
        $customer->id_terms = $request->select_payment;
        $customer->id_delivery = $request->select_delivery;
        $customer->billto_street = $request->cs_billto_street;
        $customer->billto_company = $request->cs_billto_company;
        $customer->billto_city = $request->cs_billto_city;
        $customer->billto_postal = $request->cs_billto_postal;
        $customer->billto_state = $request->cs_billto_state;
        if(isset($request->cs_inactive)){
            $customer->is_active = 0;
        }
        else{
            $customer->is_active = 1;
        }
        $customer->save();

        $shipto_array = ShipToCustomer::where('id_customer', null)->get();
        if($shipto_array){
            foreach ($shipto_array as $shipto) {
                $shipto->id_customer = $customer->id;
                $shipto->save();
            }
        }

        return $customer;
    }

    public function getCustomer($id){
        $customer = Customers::find($id);

        return $customer;
    }

    public function getCustomerbyName($name){
        $customer = Customers::where('company_name', $name)->first();

        return json_encode($customer);
    }

    public function delCustomer($id){

        $is_delete = null;
        $customer = Customers::find($id);
        $count_invoices = Invoices::where('id_customer', $id)->exists();

        if($count_invoices){
            $is_delete = false;
        }
        else{
            $notes = NotesCustomer::where('id_customer', $id)->first();
            if($notes){
                $notes->delete();
            }
    
            $contacts = ContactCustomer::where('id_customer', $id)->get();
            foreach ($contacts as $contact) {
                $contact->delete();
            }   
    
            $customer->delete();
            $is_delete = true;
        }

        return json_encode($is_delete);
    }

    public function updateNotes(Request $request, $id){
        $notes = NotesCustomer::where('id_customer', $id)->first();
        if($notes){
            $notes->note = $request->note;
            $notes->save();
        }
        else{
            NotesCustomer::create([
                'id_customer' => $id,
                'note' => $request->note
            ]);
        }
            
        return; 
    }

    public function setContact(Request $request, $id){
        $contact = ContactCustomer::create([
            'id_customer' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return json_encode($contact);
    }

    public function deleteContact($id){
        $contact = ContactCustomer::find($id);
        $contact->delete();

        return;
    }

    public function getTransacciones($id){
        $estimates = SalesOrders::where('id_customer', $id)->get();
        $invoices = Invoices::where('id_customer', $id)->get();
        $payments = PaymentCustomers::where('id_customer', $id)->get();
        foreach ($payments as $payment) {
            $balance = PaymentsDetails::where('id_payment', $payment->id)->sum('amount');
            $payment->memo = $balance;
        }

        $group = [
            'estimates' => $estimates,
            'invoices' => $invoices,
            'payments' => $payments
        ]; 
        
        return json_encode($group);
    }

     //Groups Operation
    public function setNewGroup($name){
        $group = new Groups;
        $group->name = $name;
        $group->save();

        return $group;
    }

    public function updateGroup(Request $request){
        $group = Groups::find($request->id);
        $group->name = $request->name;
        $group->save();
 
        return;
    }

    public function deleteGroup($id){
        $group = Groups::find($id);
        $group->delete();

        return;
    }


     //Terms Operation
    public function setNewterm($name){
        $term = new PaymentTerms;
        $term->name = $name;
        $term->save();

        return $term;
    }

    public function updateTerm(Request $request){
        $term = PaymentTerms::find($request->id);
        $term->name = $request->name;
        $term->save();

        return;
    }

    public function deleteTerm($id){
        $term = PaymentTerms::find($id);
        $term->delete();

        return;
    }


     //Deliveries Operation
    public function setNewDelivery($name){
        $delivery = new DeliveryMethod;
        $delivery->name = $name;
        $delivery->save();

        return $delivery;
    }

    public function updateDelivery(Request $request){
        $delivery = DeliveryMethod::find($request->id);
        $delivery->name = $request->name;
        $delivery->save();

        return;
    }

    public function deleteDelivery($id){
        $delivery = DeliveryMethod::find($id);
        $delivery->delete();

        return;
    }


     //Unit Operation
    public function setUnitMeasure(Request $request){
        $unit_measure = new UnitMeasure;
        $unit_measure->abbreviation = $request->abbreviation;
        $unit_measure->description = $request->description;
        $unit_measure->save();

        return $unit_measure;
    }

    public function updateUnitMeasure(Request $request){
        $unit_measure = UnitMeasure::find($request->id);
        $unit_measure->abbreviation = $request->abbreviation;
        $unit_measure->description = $request->description;
        $unit_measure->save();

        return;
    }

    public function deleteUnitMeasure($id){
        $unit_measure = UnitMeasure::find($id);
        $unit_measure->delete();

        return;
    }


     //Shipto Operation
    public function setShipTo(Request $request){
        $shipto = new ShipToCustomer;
        $shipto->name = $request->name;
        $shipto->address = $request->address;
        $shipto->company = $request->company;
        $shipto->city = $request->city;
        $shipto->postal = $request->postal;
        $shipto->state = $request->state;
        $shipto->save();

        return $shipto;
    }

    public function updateShipTo(Request $request, $id){
        $shipto = ShipToCustomer::find($id);
        $shipto->name = $request->name;
        $shipto->address = $request->address;
        $shipto->company = $request->company;
        $shipto->city = $request->city;
        $shipto->postal = $request->postal;
        $shipto->state = $request->state;
        $shipto->save();

        return;
    }

    public function getShipTo($id){
        $address = ShipToCustomer::where('id', $id)->first();
        
        return $address;
    }

    public function getShipList($id){
        $list = ShipToCustomer::where('id_customer', $id)->get();
        
        return $list;
    }

    public function delShipTo(){
        $shipto_list = ShipToCustomer::where('id_customer', null)->get();
        foreach ($shipto_list as $shipto) {
            $shipto->delete();
        }
        
        return;
    }

    public function destroyShipTo($id){
        $shipto = ShipToCustomer::find($id);
        $shipto->delete();
        
        return;
    }


     //Items Operation
    public function getItem($id){
        $product = Products::find($id);
        $name_unit_measure = UnitMeasure::where('id', $product->id_unit_measure)->value('abbreviation');
        $product->id_unit_measure = $name_unit_measure;

        return $product;
    }

    public function getItemByCode($code){

        $nombreBD = App::make('dataBase');

        try {
            $db = new \PDO('mysql:host=localhost;dbname='. $nombreBD, 'root', '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $p_impuestos = Impuesto::select('id', 'porcentaje')->get();
            $consulta = "SELECT products.id, item_name, bar_code, iva, unit_measures.abbreviation, num_precio, precio, precio_iva, desde, hasta
                        FROM products
                        LEFT JOIN price_products ON products.id = price_products.id_product
                        LEFT JOIN unit_measures ON products.id_unit_measure = unit_measures.id
                        WHERE products.id = :code OR bar_code = :code
                        ORDER BY products.id DESC, price_products.num_precio DESC";

            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':code', $code, \PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $productos = [];
            
            $currentProduct = null;

            foreach ($resultados as $row) {
                $productId = $row['id'];
                for ($i=0; $i < count($p_impuestos); $i++) { 
                                
                    if ($row['iva'] == strval($p_impuestos[$i]['id'])) {
                        $porcentajeIva = $p_impuestos[$i]['porcentaje'];
                    }
                }

                if ($currentProduct === null || $currentProduct['id'] !== $productId) {
                    if ($currentProduct !== null) {
                        $productos[] = $currentProduct;
                    }
                    $currentProduct = [
                        "id" => $productId,
                        "item_name" => $row['item_name'],
                        "bar_code" => $row['bar_code'],
                        "iva" => $row['iva'],
                        "porcentajeIva" => $porcentajeIva,
                        "abbreviation" => $row['abbreviation'],
                        "pvp1_neto" => null,
                        "pvp1" => null,
                        "cantidad2" => null,
                        "pvp2_neto" => null,
                        "pvp2" => null,
                        "cantidad3" => null,
                        "pvp3" => null,
                        "pvp3_neto" => null,
                        "cantidad4" => null,
                        "pvp4" => null,
                        "pvp4_neto" => null,
                    ];
                }

                $numPrecio = $row['num_precio'];
                $precio_iva = $row['precio_iva'];
                $precio_neto = $row['precio'];
                $desde = $row['desde'];

                switch ($numPrecio) {
                    case 1:
                        $currentProduct['pvp1'] = floatval($precio_iva);
                        $currentProduct['pvp1_neto'] = $precio_neto;
                        break;
                    case 2:
                        $currentProduct['pvp2'] = $precio_iva;
                        $currentProduct['pvp2_neto'] = $precio_neto;
                        $currentProduct['cantidad2'] = $desde;
                        break;
                    case 3:
                        $currentProduct['pvp3'] = $precio_iva;
                        $currentProduct['pvp3_neto'] = $precio_neto;
                        $currentProduct['cantidad3'] = $desde;
                        break;
                    case 4:
                        $currentProduct['pvp4'] = $precio_iva;
                        $currentProduct['pvp4_neto'] = $precio_neto;
                        $currentProduct['cantidad4'] = $desde;
                        break;
                }
            }

            if ($currentProduct !== null) {
                $productos[] = $currentProduct;
            }

            return json_encode($productos);

        } catch (\PDOException $e) {
            // Manejo de errores de la base de datos
            echo "Error de base de datos: " . $e->getMessage();
        }
    }
    
    public function getItemByDescription(Request $request){
        
        $nombreBD = App::make('dataBase');

        $db = new \PDO('mysql:host=localhost;dbname='. $nombreBD, 'root', '');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {

            $p_impuestos = Impuesto::pluck('porcentaje', 'id');
            $descripcion = $request->descripcion;
            $consulta = "SELECT products.id, item_name, bar_code, iva, unit_measures.abbreviation, num_precio, precio, precio_iva, desde, hasta
                        FROM products
                        LEFT JOIN price_products ON products.id = price_products.id_product
                        LEFT JOIN unit_measures ON products.id_unit_measure = unit_measures.id
                        WHERE products.item_name = :descripcion
                        ORDER BY products.id DESC, price_products.num_precio DESC";

            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':descripcion',$descripcion, \PDO::PARAM_STR);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $productos = [];

            foreach ($resultados as $row) {
                $productId = $row['id'];
                $porcentajeIva = $p_impuestos[$row['iva']] ?? null;

                // Verificar si el producto ya existe en el array de productos
                if (!isset($productos[$productId])) {
                    $productos[$productId] = [
                        "id" => $productId,
                        "item_name" => $row['item_name'],
                        "bar_code" => $row['bar_code'],
                        "iva" => $row['iva'],
                        "porcentajeIva" => $porcentajeIva,
                        "pvp1_neto" => null,
                        "pvp1" => null,
                        "cantidad2" => null,
                        "pvp2_neto" => null,
                        "pvp2" => null,
                        "cantidad3" => null,
                        "pvp3" => null,
                        "pvp3_neto" => null,
                        "cantidad4" => null,
                        "pvp4" => null,
                        "pvp4_neto" => null,
                    ];
                }

                switch ($row['num_precio']) {
                    case 1:
                        $productos[$productId]['pvp1'] = $row['precio_iva'];
                        $productos[$productId]['pvp1_neto'] = $row['precio'];
                        break;
                    case 2:
                        $productos[$productId]['pvp2'] = $row['precio_iva'];
                        $productos[$productId]['pvp2_neto'] = $row['precio'];
                        $productos[$productId]['cantidad2'] = $row['desde'];
                        break;
                    case 3:
                        $productos[$productId]['pvp3'] = $row['precio_iva'];
                        $productos[$productId]['pvp3_neto'] = $row['precio'];
                        $productos[$productId]['cantidad3'] = $row['desde'];
                        break;
                    case 4:
                        $productos[$productId]['pvp4'] = $row['precio_iva'];
                        $productos[$productId]['pvp4_neto'] = $row['precio'];
                        $productos[$productId]['cantidad4'] = $row['desde'];
                        break;
                }
            }
            
            // Convertir el array de productos a JSON y retornarlo
            return json_encode(array_values($productos));

        } catch (\PDOException $e) {
            // Manejo de errores de la base de datos
            echo "Error de base de datos: " . $e->getMessage();
        }
    }

    public function getItemCodebar($code){
        $id_product = Products_LabelBar::where('code', $code)->value('id_item');
        $product = Products::find($id_product);
        $name_unit_measure = UnitMeasure::where('id', $product->id_unit_measure)->value('abbreviation');
        $product->id_unit_measure = $name_unit_measure;
        $sumaqty=0;
        $sums = array();
        $selectedWarehouse = $_GET['selectedWarehouse'];
        $inventories = Inventories::where("id_item", $code)->where("id_warehouse", "=", $selectedWarehouse)->get();

        foreach ($inventories as $inventory) {
            $id = $inventory['id_item'];
            $qty = $inventory['qty'];
            if (array_key_exists($id, $sums)) {
                if($inventory->type == "Invoice" || $inventory->type == "Discharge"){
                    $sums[$id] -= $qty;
                }
                else{
                    $sums[$id] += $qty;
                }
            } else {
                if($inventory->type == "Invoice" || $inventory->type == "Discharge"){
                    $sums[$id] = -$qty;
                }
                else{
                    $sums[$id] = $qty;
                }
            }
        }

        foreach ($sums as $id => $sum) {
            $sumaqty=$sum;
        }
    
        $product->qty = $sumaqty;


        return json_encode($product);
    }

    public function delItem($id){
        $product = Products::find($id);
        $product->delete();
            
        return;
    }

    public function getImage($id){
        $images = ImageProduct::where('id_product', $id)->get();

        return $images;
    }

    public function delImage($id){
        $image = ImageProduct::find($id);
        $delete_image  = unlink($image->image_folder);
        $image->delete();

        return;
    }

    public function delFile($id){
        $file = AttachmentCustomer::find($id);
        $delete_file  = unlink($file->file_location);
        $file->delete();

        return;
    }


     //Documents Sales Order Operation
    public function updateDocumentNumber(Request $request){
        $order = DocumentNumbers::where('type', 'Orders')->first();
        $invoice = DocumentNumbers::where('type', 'Invoices')->first();

        $order->number = $request->orders;
        $order->save();
        $invoice->number = $request->invoices;
        $invoice->save();

        return;
    }

    public function deleteOrder($id){
        $sales_order = SalesOrders::find($id);
        $items = SalesOrdersItems::where('id_order', $sales_order->id)->get();
        foreach ($items as $item) {
            $item->delete();
        }

        $attachments = AttachmentCustomer::where('id_transaction', $sales_order->id)->get();
        if($attachments){
            foreach ($attachments as $attachment) {
                unlink($attachment->file_location);
                $attachment->delete();
            }
        }

        $inventories = InventoriesCustomers::where('id_transaction', $sales_order->id)->get();
        if($inventories){
            foreach ($inventories as $inventory) {
                $inventory->delete();
            }
        }
    /*
        // $relation_process_order = Process_Order::where('id_order', $sales_order->id)->first();
        // if($relation_process_order){
        //     $process = ProcessData::where('id', $relation_process_order->id_process)->first();
        //     $phases = ProcessDataPhase::where('id_data', $process->id)->get();
        //     if($phases){
        //         foreach ($phases as $phase) {
        //             $stages = ProcessDataStage::where('id_phases', $phase->id)->get();
        //             if($stages){
        //                 foreach ($stages as $stage) {
        //                     $stage->delete();
        //                 }
        //             }
        //             $phase->delete();
        //         }
        //     }
    
        //     $relation_process_order->delete();
        //     $process->delete();
        // }
    */

        $sales_order->delete();

        return;
    }


     //Users Operation
    public function getUsers(){
        $users = User::all();

        return $users;
    }

    public function getUsersImage($id){
        $user_image = User::where('id', $id)->value('profile_photo_path');

        return $user_image;
    }

    /*
        //Process Operation
        // public function getProcess($id){
        //     $group_process = array();
        //     $group_phases = array();

        //     $process = Processes::find($id);
        //     $phases = ProcessPhases::where('id_process', $process->id)->get();
        //      if($phases){
        //         foreach ($phases as $phase) {
        //             $stages = ProcessStage::where('id_phase', $phase->id)->get();
        //             $group_phases[] = [
        //                 'phase' => $phase,
        //                 'stages' => $stages
        //             ];
        //         }
        //     }

        //     $group_process[] = [
        //         "process" => $process,
        //         "phases" => $group_phases
        //     ];

        //     return $group_process;
        // }

        // public function getCondition($id){
        //     $group_condition = array();
        //     $id_process = Processes::where('id', $id)->value('id');
        //     $phases = ProcessPhases::where('id_process', $id_process)->get();
        //     if($phases){
        //         foreach ($phases as $phase) {
        //             $stages = ProcessStage::where('id_phase', $phase->id)->get();
        //             if($stages){
        //                 foreach ($stages as $stage) {
        //                     if($stage->has_condition == 1){
        //                         $group_condition[] = [
        //                             "Condition" => ProcessCondition::where('id_stage', $stage->id)->first(),
        //                             "stage" => $stage
        //                         ];
        //                     }
        //                 }
        //             }               
        //         }
        //     }

        //     return $group_condition;
        // }

        // public function getProcessName($name){
        //     $response = Processes::where('description', $name)->exists();
        //     if(!$response){
        //         $response = 0;
        //     }

        //     return $response;
        // }

        // public function updateStageData(Request $request){
        //     $stage = ProcessDataStage::where('id', $request->id)->first();
        //     $stage->start_date = $request->start;
        //     $stage->end_date =  $request->end;
        //     $stage->instructions = $request->instructions;
        //     $stage->percentage = $request->progress;
        //     $stage->save();
        //     $message = null;

        //     $phase = ProcessDataPhase::where('id', $stage->id_phases)->first();
        //     $process = ProcessData::where('id', $phase->id_data)->first();
        //     if($process->percentage == 100){
        //         $process->status = "Complete";
        //         $process->save();
        //     }

        //     if ( $stage->percentage == 100) {
        //         if($stage->has_send_mail == 1){
        //             ///envía correo al cliente
        //             $email = Customers::where('id', $process->id_customer)->value('email');
        //             $customer = Customers::where('id', $process->id_customer)->value('company_name');
        //             $message = CustomizeMail::where('type', 'Stage')->value('message');
        //             if($email){
        //                 Mail::to($email)->send(new NoticesMail($stage, $customer, $message));   
        //             }
        //         }
        //     }

        //     $stage_count = ProcessDataStage::where('id_phases', $phase->id)->count();
        //     $stage_percentage = ProcessDataStage::where('id_phases', $phase->id)->sum('percentage');
        //     $phase_percentage = $stage_percentage / $stage_count;
        //     $phase->percentage = $phase_percentage;
        //     $phase->save();

        //     $phase_count = ProcessDataPhase::where('id_data', $process->id)->count();
        //     $phase_percentage = ProcessDataPhase::where('id_data', $process->id)->sum('percentage');
        //     $process_percentage = $phase_percentage / $phase_count; 
        //     $process->percentage = $process_percentage;
        //     $process->save();

        //     return;
        // }

        // public function setStageApprove(Request $request){
        //     $stage = ProcessDataStage::find($request->id);
        //     if($stage){
        //         if ($request->type == 1) {
        //             $stage->is_approved = 1;
        //             $stage->save();
        //         } else {
        //             $stage->is_approved = 0;
        //             $stage->save();
        //         }
        //     }
            
        //     return;
        // }

        // public function setCodebar(Request $request){
        //     $codebar = new ProcessDataCodebar;
        //     $codebar->id_process = $request->process_id;
        //     $codebar->id_stage = $request->stage_id;
        //     $codebar->id_invoice = $request->invoice_id;
        //     $codebar->code = $request->code;
        //     $codebar->image = $request->image;
        //     $codebar->save();

        //     return;
        // }

        // public function deleteCodebar($id){
        //     $codebar = ProcessDataCodebar::find($id);
        //     $codebar->delete();

        //     return;
        // }
    */    

     //Colors Operation
    public function setNewColor($name){
        $color = new Colors;
        $color->description = $name;
        $color->save();

        return $color;
    }

    public function updateColor(Request $request){
        $color = Colors::find($request->color_id);
        $color->description = $request->color_description;
        $color->save();

        return;
    }

    public function deleteColor($id){
        $color = Colors::find($id);
        $color->delete();

        return;
    }

    public function getColor($id){
        $colors = Products_Colors::select('id_color')->where('id_item', $id)->get();

        return json_encode($colors);
    }

    public function getAllColors(){
        $colors = Colors::all();

        return json_encode($colors);
    }


     //Sizes Operation
    public function setNewSize($name){
        $size = new Sizes;
        $size->description = $name;
        $size->save();

        return $size;
    }

    public function updateSize(Request $request){
        $size = Sizes::find($request->id);
        $size->description = $request->description;
        $size->save();

        return;
    }

    public function deleteSize($id){
        $size = Sizes::find($id);
        $size->delete();

        return;
    }

    public function getSize($id){
        $sizes = Products_Sizes::select('id_size')->where('id_item', $id)->get();

        return json_encode($sizes);
    }

    public function getAllSizes(){
        $sizes = Sizes::all();

        return json_encode($sizes);
    }


    /* 
        // //Upload Files Operation
        // public function uploadCustomerFile(Request $request){
        //     $attachment = null;
        //     $filename = $_FILES['file']['name'];
        //     $directory = "customer/files/";
        //     if(!file_exists($directory)){
        //         mkdir($directory, 0777);
        //     }

        //     $dir = opendir($directory);
            
        //     if(copy($_FILES['file']['tmp_name'], $directory.$filename)){
        //             $attachment = AttachmentCustomer::create([
        //                 'type_transaction' => $request->type,
        //                 'id_transaction' => $request->order,
        //                 'id_customer' => $request->customer,
        //                 'type' => $_FILES['file']['type'],
        //                 'file_name' =>  $_FILES['file']['name'],
        //                 'file_size' => $_FILES['file']['size'],
        //                 'file_location' => "customer/files/".$_FILES['file']['name'],
        //             ]);
        //     }
        
        //     closedir($dir);
        //     return json_encode($attachment);
        // }

        // public function uploadStageFile(Request $request){                   
        //     $attachment = null;
        //     $filename = $_FILES['file']['name'];
        //     $directory = "files/stages/".$request->input('stage')."/";
        //     if(!file_exists($directory)){
        //         mkdir($directory, 0777);
        //     }

        //     $dir = opendir($directory);
            
        //    if(copy($_FILES['file']['tmp_name'], $directory.$filename)){
        //         $attachment = Attachments::create([
        //             'id_process' => $request->process,
        //             'id_phase' => $request->phase,
        //             'id_stage' => $request->stage,
        //             'type' => $_FILES['file']['type'],
        //             'file_name' =>  $_FILES['file']['name'],
        //             'file_size' => $_FILES['file']['size'],
        //             'file_location' => "files/stages/".$request->stage."/".$_FILES['file']['name'],
        //         ]);
        //    }
        //    closedir($dir);       
        //    return json_encode($attachment);
        // }

        // public function deleteCustomerFile($id){
        //     $file = AttachmentCustomer::find($id);
        //     $delete_file  = unlink($file->file_location);
        //     $file->delete();

        //     return json_encode($file);
        // }

        // public function deleteStageFile($id){
        //     $file = Attachments::find($id);
        //     $delete_file  = unlink($file->file_location);
        //     $file->delete();

        //     return;
        // }


        // //Items Comparer Operation
        // public function setItemComparer(Request $request){
        //     $id_product = Products::where('item_name', $request->item_code)->value('id'); 
        //     $ingresos = null; $egresos = null;

        //     if($request->item_size && $request->item_color){
        //         $ingresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_size', $request->item_size)
        //                 ->where('id_color', $request->item_color)
        //                 ->where('type', 'BL')
        //                 ->sum('qty');
        //     }
        //     else if($request->item_size){
        //         $ingresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_size', $request->item_size)
        //                 ->where('type', 'BL')
        //                 ->sum('qty');
        //     }
        //     else if($request->item_color){
        //         $ingresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_color', $request->item_color)
        //                 ->where('type', 'BL')
        //                 ->sum('qty');
        //     }
        //     else{
        //         $ingresos = Inventories::where('id_product', $id_product)
        //                 ->where('type', 'BL')
        //                 ->sum('qty');
        //     }

        
        //     if ($request->item_size && $request->item_color) {
        //         $egresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_size', $request->item_size)
        //                 ->where('id_color', $request->item_color)
        //                 ->where('type', 'INV')
        //                 ->sum('qty');
        //     } 
        //     else if($request->item_size) {
        //         $egresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_size', $request->item_size)
        //                 ->where('type', 'INV')
        //                 ->sum('qty');
        //     }
        //     else if($request->item_color){
        //         $egresos = Inventories::where('id_product', $id_product)
        //                 ->where('id_color', $request->item_color)
        //                 ->where('type', 'INV')
        //                 ->sum('qty');
        //     }
        //     else{
        //         $egresos = Inventories::where('id_product', $id_product)
        //                 ->where('type', 'INV')
        //                 ->sum('qty');
        //     }
            
            
        //     $inventory_balance = $ingresos - $egresos;
        //     $balance = $inventory_balance - $request->item_qty;

        //     $item = ProcessComparerItems::create([
        //         'id_stage' => $request->id_stage,
        //         'id_product' => $id_product,
        //         'id_size' => $request->item_size,
        //         'id_color' => $request->item_color,
        //         'qty' => $request->item_qty,
        //         'inventory' => $inventory_balance,
        //         'balance' => $balance
        //     ]);
            
        //     $objeto = [
        //         "id" => $item->id,
        //         "code" => $request->item_code,
        //         "description" => Products::where('item_name', $request->item_code)->value('sales_description'),
        //         "size" => Sizes::where('id', $request->item_size)->value('description'),
        //         "color" => Colors::where('id', $request->item_color)->value('description'),
        //         "qty" => $request->item_qty,
        //         "inventory" => $inventory_balance,
        //         "balance" => $balance
        //     ];

        //     return json_encode($objeto);
        // }

        // public function deleteItemComparer($id){
        //     $item = ProcessComparerItems::where('id', $id)->first();
        //     $item->delete();

        //     return;
        // }


        // //Items Inventory Operation
        // public function setCustomerInventory(Request $request, $id){
        //     $item = null;
        //     $id_transaction = $id;
        //     if ($request->type == "SO") {
        //         $item = InventoriesCustomers::firstOrCreate([
        //             'type_transaction' => 'SO',
        //             'id_transaction' => $id_transaction,
        //             'id_customer' => $request->id_customer,
        //             'id_product' => Products::where('item_name', $request->item_name)->value('id'),
        //             'qty' => $request->item_qty,
        //             'id_size' => $request->item_size,
        //             'id_color' => $request->item_color,
        //         ]);   
        //     } else {
        //         $item = InventoriesCustomers::firstOrCreate([
        //             'type_transaction' => 'INV',
        //             'id_transaction' => $id_transaction,
        //             'id_customer' => $request->id_customer,
        //             'id_product' => Products::where('item_name', $request->item_name)->value('id'),
        //             'qty' => $request->item_qty,
        //             'id_size' => $request->item_size,
        //             'id_color' => $request->item_color,
        //         ]);   
        //     }
            
        //     $row = [
        //         'id' => $item->id,
        //         'code' => $request->item_name,
        //         'description' => $request->item_description,
        //         'size' => Sizes::where('id', $request->item_size)->value('description'),
        //         'color' => Colors::where('id', $request->item_color)->value('description'),
        //         'qty' => $request->item_qty
        //     ];

        //     return json_encode($row);
        // }

        // public function deleteCustomerInventory($id){
        //     $item = InventoriesCustomers::find($id);
        //     $item->delete();

        //     return;
        // }
    */

    //Vendor Operation
    public function setVendor(Request $request){
        $vendor = Vendors::create([
            'name' => $request->v_company,
            'contact' => $request->v_contact,
            'phone' => $request->v_phone,
            'email' => $request->v_mail,
            'billto_street' => $request->v_billto_street,
            'billto_company' => $request->v_billto_company,
            'billto_city' => $request->v_billto_city,
            'billto_postal' => $request->v_billto_postal,
            'billto_state' => $request->v_billto_state,
        ]);

        return json_encode($vendor);
    }

    public function getVendor($id){
        $vendor = Vendors::where('id', $id)->first();

        return json_encode($vendor);
    }

    public function getVendor2($name){
        $vendor = Vendors::where('name', $name)->first();

        return json_encode($vendor);
    }

    // public function generatePassVendor(Request $request){
    //     $user = null;
    //     if (VendorsUsers::where('id_vendor', $request->id)->where('user', $request->name)->exists()) {
    //         $user = VendorsUsers::where('id_vendor', $request->id)->where('user', $request->name)->first();
    //     } else {
    //         $user = VendorsUsers::create([
    //             'id_vendor' => $request->id,
    //             'user' => $request->name,
    //             'password' => uniqid()
    //         ]);   
    //     }

    //     return json_encode($user);
    // }

    public function deleteVendor($id){
        $vendor = Vendors::find($id);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('info', 'The vendor has been deleted')->send();
    }

    //Taxes Operation
    public function setNewTax(Request $request){
        $tax = new Taxes;
        $tax->description = $request->description;
        $tax->percentage = $request->percentage;
        $tax->save();

        return $tax;
    }

    public function updateTax(Request $request){
        $tax = Taxes::find($request->id);
        $tax->description = $request->description;
        $tax->percentage = $request->percentage;
        $tax->save();

        return;
    }

    public function deleteTax($id){
        $tax = Taxes::find($id);
        $tax->delete();

        return;
    }

    //Mails Operation
    public function updateMail(Request $request, $id){
        $mail = CustomizeMail::find($id);
        $mail->subject = $request->subject;
        $mail->message = $request->message;
        $mail->save();

        return;
    }

    //Payment Operation
    public function getInvoices($customer){
        $group_invoices = array();
        $id_customer = Customers::where('company_name', $customer)->value('id');
        $invoices = Invoices::where('id_customer', $id_customer)->where('status', 'Pending')->get();

        foreach ($invoices as $invoice) {
            $balance = PaymentsDetails::where('invoice', $invoice->number)->sum('amount');
            $group_invoices[] = [
                'date' => $invoice->date,
                'number' => $invoice->number,
                'amount' => $invoice->total,
                'balance' => $balance
            ];
        }

        return json_encode($group_invoices);
    }

    public function getCashier($fecha){

        $nombreBD = App::make('dataBase');

        $totalPayment=0;
        $totalCash = 0;
        $totalTransfer = 0;
        $general=[];
        $inicioDelDia =$fecha;


        $dsn = "mysql:host=localhost;dbname=". $nombreBD;
        $usuario = "root";
        $contrasena = "";
        try {
            
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT * FROM invoices WHERE date = '{$inicioDelDia}'";
            $result= $conexion->query($consulta);
            $invoices = $result->fetchAll(\PDO::FETCH_ASSOC);
            

            $consulta2 = "SELECT A.id, A.date as fecha1, A.amount as pago1,
                    B.date as fecha2, B.id_term, B.valor,
                    C.num_doc_sri, C.date as fechafac, C.total as totfac 
                    FROM payment_customer A
                    JOIN payment_details B ON A.id = B.id_payment
                    JOIN invoices C ON A.invoice = C.number
                    WHERE C.date = :inicioDelDia";

            $statement = $conexion->prepare($consulta2);
            $statement->bindParam(':inicioDelDia', $inicioDelDia);
            $statement->execute();

            $payments = $statement->fetchAll(\PDO::FETCH_ASSOC);


            foreach ($payments as $payment) {
                if($payment['fecha1'] === $payment['fechafac'])
                {
                    $valor = $payment['valor'];
                    switch ($payment['id_term']) {
                        case 1:
                            $totalCash += $valor;
                            break;
                        case 2:
                            $totalTransfer += $valor;
                            break;
                    }
                }
                /* 
                    else{         
                        $valor = $payment['valor'];
                        switch ($payment['id_term']) {
                            case 1:
                                $totalCash += $valor;
                                break;
                            case 2:
                                $totalTransfer += $valor;
                                break;

                            default:
                                $unpayment += $valor;
                                break;
                        }
                    }
                 */
            }
            $totalPayment = $totalCash + $totalTransfer;
            
            $general[] = [
                'invoices' => $invoices,
                'inicioDelDia'=>$inicioDelDia,
                'totalCash' => $totalCash,
                'totalTransfer' => $totalTransfer,
                'totalPayment' => $totalPayment
            ];
            

            return json_encode($general);

        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    } 
    
    //Verifica el código de registro
    public function verificarCodigo(Request $request)
    {       
        $codigoIngresado = $request->codigoRegistro;
        $registro = Activacion::where('codigo_activacion', $codigoIngresado)
                                ->where('es_activo', 0)
                                ->first();

            
        if($registro){

            //$activacion = Activacion::where('codigo_activacion', $codigoIngresado)->first();    
            $empresa = Empresas::where('ruc', $registro->ruc)->first();

            if($empresa){
                $registro->es_activo = 1;
                $registro->save();
                return redirect()->route('login')->with('message', 'La empresa y ha sido creada correctamente!');
            }else{
                return redirect()->route('empresa.create', ['ruc' => $registro->ruc, 'email' => $registro->correo]);
            }
        
        }else{
            return back()->with('error', 'El código ingresado es incorrecto.');
        }    
    }

    // public function obtenerCadenaConexion(){
    //     if (Auth::check()) {
    //         // Obtiene el modelo del usuario autenticado
    //         $usuarioAutenticado = Auth::user();
    
    //         // Accede al valor del campo id_empresa
    //         $idEmpresa = $usuarioAutenticado->id_empresa;
    
    //         if ($idEmpresa) {
    //             $datosEmpresa = Empresas::Select('base_datos', 'cadena_conexion')->Where('id_empresa', $idEmpresa)->Where('es_activo', 1)->first();
    //             App::instance('cadenaConexion', $datosEmpresa->cadena_conexion);
    //             App::instance('nombreBaseDatos', $datosEmpresa->base_datos);
    //         }
    //     }
    // }
}


