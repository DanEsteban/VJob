<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ItemTypes;
use App\Models\SalesOrdersItems;
use App\Models\SalesOrders;
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\InvoicesItems;
use App\Models\AssamblyItems;
use App\Models\Expenditures;
use App\Models\ExpendituresItems;
use App\Models\Impuesto;
use App\Models\Incomes;
use App\Models\IncomesItems;
use App\Models\Inventories;
use App\Models\ProductsBalances;
use App\Models\Parameters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

Carbon::setLocale('es');
class ElementsController extends Controller
{
    public function addRowPriceProduct(){
        $nombreBD =  App::make('dataBase');
        $dsn = "mysql:host=localhost;dbname=" . $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM item_types";
            $result= $conexion->query($consulta);

            $consulta2 = "SELECT * FROM unit_measures";
            $result2= $conexion->query($consulta2);

            $unit_measures=[];
            foreach ($result2 as $fila) {
                $unit_measures[]=[
                    "id" => $fila['id'],
                    "abbreviation" => $fila['abbreviation']   
                ];
            }

            $item_types=[];
            foreach ($result as $fila) {
                $item_types[]=[
                    "id" => $fila['id'],
                    "name" => $fila['name']   
                ];
            }

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
        
        $p_impuestos = Impuesto::all();

        $row = '<tr id="tr_items">'.
                '<td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                <td><button onclick="editRow(this);" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button></td>
                <td><input type="text" name="id[]" class="form-control form-control-sm" readonly></td>
                <td><input type="text" name="producto[]" style="width:auto" class="form-control form-control-sm"></td>
                <td>
                    <select onchange="filtrarLinea(this);" id="type" name="type[]" class="form-control form-control-sm">
                        <option value="0" style="display: none;">--Seleccione--</option>';
                        for ($i = 0; $i < count($item_types); $i++){
                            if ($i > 0) {
                                $row .= '<option value="' . $item_types[$i]['id'] . '">' . $item_types[$i]['name'] . '</option>';
                            }
                        }
        $row .=     '</select>
                </td>
                <td>
                    <select id="group" name="group[]" class="form-control form-control-sm">
                        <option value="0">--Seleccione--</option>
                    </select>
                </td>
                <td class="text-center">
                    <input id="siIvafront" onclick="actualizarEstado(this)" type="checkbox">
                </td>
                <td hidden>
                    <input type="hidden" id="siIva" name="siIva[]" value="0">                    
                </td> 
                <td>
                    <select id="iva" name="iva[]" class="form-control form-control-sm" disabled>
                        <option value="0">--Seleccione--</option>';
                        foreach ($p_impuestos as $item) {
                            $row .= '<option value="' . $item["id"] . '">' . $item["porcentaje"] . '</option>';
                        }
        $row .=     '</select>
                </td>
                
                <td><input onchange="comprobacionbarCode(this);" type="text" id="codigoBarras" name="codigoBarras[]" class="form-control form-control-sm"></td>
                <td>
                    <select id="medida" name="medida[]" class="form-control form-control-sm">
                        <option value="0">--Seleccione--</option>';
                        foreach ($unit_measures as $item) {
                            $row .= '<option value="' . $item["id"] . '">' . $item["abbreviation"] . '</option>';
                        }
        $row .=     '</select>
                </td>
                <td><input type="text" id="pvp1" name="pvp1[]" class="form-control form-control-sm"></td>
                <td><input type="text" id="cantidad2" onchange="cambioCantidad(this);" name="cantidad2[]" class="form-control form-control-sm"></td>    
                <td><input type="text" id="pvp2" name="pvp2[]" class="form-control form-control-sm"></td>
                <td><input type="text" id="cantidad3" onchange="cambioCantidad(this);" name="cantidad3[]" class="form-control form-control-sm"></td>
                <td><input type="text" id="pvp3" name="pvp3[]" class="form-control form-control-sm"></td>
                <td><input type="text" id="cantidad4" onchange="cambioCantidad(this);" name="cantidad4[]" class="form-control form-control-sm"></td>
                <td><input type="text" id="pvp4" name=pvp4[] class="form-control form-control-sm"></td>

            </tr>';

        return $row;
    }

    public function addRowOrder(){
    
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT id, item_name FROM products" ;
            $result= $conexion->query($consulta);
            $items = []; 

            foreach ($result as $fila) {
                $items[]=[
                    "id" => $fila['id'],
                    "item_name" => $fila['item_name']
                ];
            }

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
        
        $row = '<tr id="tr_items">'.
                    '<td><button onclick="vaciarRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></td>
                    <td>
                        <input onchange="changeItem(this);" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                                $row .= '<option value="{{' . $item['id'] . '}}"></option>';
                            }
        $row .=         '</datalist>
                    </td>
                    <td>
                        <input onchange="changeDescription(this)" id="description" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemDesList">
                            <datalist id="itemDesList">';                     
                            foreach ($items as $item){
                                $row .= '<option value="{{' . $item['item_name'] . '}}"></option>';
                            }
        $row .=         '</datalist>
                    </td>
                    <td id="tdC1"><input type="text" onchange="changeQty(this);" id="cantidad" name="cantidad[]" class="form-control form-control-sm"></td>
                    <td id="tdP0"><input type="text" id="pvp0_neto" name="pvp0_neto[]" class="form-control form-control-sm" readonly></td>  
                    <td hidden id="tdP1"><input type="text" id="pvp1_neto" class="form-control form-control-sm" readonly></td>                   
                    <td hidden><input hidden type="text" id="pvp1"  class="form-control form-control-sm"></td>
                    <td id="tdC2" hidden><input hidden type="text" id="cantidad2" class="form-control form-control-sm"></td>
                    <td id="tdP2" hidden><input hidden type="text" id="pvp2_neto" class="form-control form-control-sm"></td>
                    <td hidden><input hidden type="text" id="pvp2" class="form-control form-control-sm"></td>
                    <td id="tdC3" hidden><input hidden type="text" id="cantidad3" class="form-control form-control-sm"></td>
                    <td id="tdP3" hidden><input hidden type="text" id="pvp3_neto" class="form-control form-control-sm"></td>
                    <td hidden><input hidden type="text" id="pvp3"  class="form-control form-control-sm"></td>
                    <td id="tdC4" hidden><input hidden type="text" id="cantidad4" class="form-control form-control-sm"></td>
                    <td id="tdP4" hidden><input hidden type="text" id="pvp4_neto" class="form-control form-control-sm"></td>
                    <td hidden><input hidden type="text" id="pvp4" class="form-control form-control-sm"></td>
                    <td hidden><input type="text" id="id_iva" name="iva[]" hidden></td>
                    <td><input type="text" id="iva" class="form-control form-control-sm" readonly></td>
                    <td><input type="text" id="subtotal" name="subtotal[]" class="form-control form-control-sm" readonly></td>
                    <td hidden><input type="text" id="num_precio" name="num_precio[]" class="form-control form-control-sm" readonly></td> 
                </tr>';
            
        return $row;
    }

    public function addRowOrder3(Request $request){
        
        $nombreBD = App::make('dataBase');

        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM products WHERE is_active = 1";
            $result= $conexion->query($consulta);
            $items = []; 

            foreach ($result as $fila) {
                $items[]=[
                    "id" => $fila['id'],
                    "id_type" => $fila['id_type'],
                    "id_group" => $fila['id_group'],
                    "item_name" => $fila['item_name'],
                    "bar_code" => $fila['bar_code'],
                    "si_iva" => $fila['si_iva'],
                    "iva" => $fila['iva'],
                    "id_unit_measure" => $fila['id_unit_measure'],
                    "notes" => $fila['notes'],
                    "is_active" => $fila['is_active'],
                ];
            }

            $jitems = json_encode($items);

            if( $request->type ==1){
                $row = '<tr id="tr_items">'.
                    '<td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                    <td>
                        <input onchange="changeItemMov(this, '.htmlspecialchars($jitems).')" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                            $row .= '<option value="{{'.$item["id"].'}}"></option>';
                            }
                $row .= '</datalist>
                    </td>
                    <td><input id="description" name="description[]" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm"></td>
                    <td><input id="price" onkeyup="changePrice(this);" name="price[]" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                </tr>';
    
            }
            else
            {
                $row = '<tr id="tr_items">'.
                    '<td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                    <td>
                        <input onchange="changeItemMov(this, '.htmlspecialchars($jitems).')" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                            $row .= '<option value="{{'.$item["item_name"].'}}"></option>';
                            }
                $row .= '</datalist>
                    </td>
                    <td><input id="description" name="description[]" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm"></td>
                    <td><input autocomplete="off" onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm"></td>
                    <td><input id="amt" type="text" class="form-control form-control-sm" readonly></td>
                </tr>';
            }
            return $row;    

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }

    public function addRowOrder2(Request $request){
        $items = Products::where('is_active', 1)->get()->toArray();
        $types = ItemTypes::find(2);
        $jitems = json_encode($items);
        $jtypes = json_encode($types); 

        $row = '<tr id="tr_items">'.
                    '<td id="td_false"></td>'.
                    '<td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr'.$request->count.'" aria-expanded="false" aria-controls="collapseTr'.$request->count.'" hidden></td>'.
                    '<td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                     <td>
                        <input autocomplete="off" onchange="changeItem2(this, '.htmlspecialchars($jitems).' , '.htmlspecialchars($jtypes).')" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                               $row .= '<option value="{{'.$item["item_name"].'}}"></option>';
                            }
        $row .=         '</datalist>
                    </td>
                    <td><input autocomplete="off" id="description" name="description[]" type="text" class="form-control form-control-sm"></td>
                    <td><input autocomplete="off" onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input autocomplete="off" onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm"></td>
                    <td><input id="amt" type="text" class="form-control form-control-sm" readonly></td>
                </tr>';

        $row .= ' <tr class="collapse" id="collapseTr'.$request->count.'">
                        <td colspan="8">
                            <div id="collapse_container" class="card card-body">
                            </div>
                        </td>
                    </tr>';
        
        return $row;
    }


    public function addRowVendorOrder(Request $request){
        $items = Products::where('is_active', 1)->get()->toArray();
        $types = ItemTypes::find(2);
        $jitems = json_encode($items);
        $jtypes = json_encode($types); 
    
        $row = '<tr id="tr_items">'.
                    '<td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr'.$request->count.'" aria-expanded="false" aria-controls="collapseTr'.$request->count.'"><i class="fa-solid fa-plus"></i></td>'.
                    '<td><button onclick="deleteRow(this);" onblur="calcular();" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                    <td>
                        <input autocomplete="off" id="codebox" name="codebox" type="text" class="form-control form-control-sm" required>
                    </td>
                    <td>
                        <select name="tbox" id="tbox" class="form-select form-select-sm">
                            <option value="0" selected></option>
                            <option value="1">QB</option>
                            <option value="2">MB</option>
                            <option value="3">FB</option>
                        </select>
                    </td>
                    <td>
                        <input autocomplete="off" onchange="changeItem(this, '.htmlspecialchars($jitems).' , '.htmlspecialchars($jtypes).')" onblur="calcular();" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                            $row .= '<option value="'.$item["item_name"].'"></option>';
                            }
        $row .=         '</datalist>
                    <td>
                        <input autocomplete="off" onchange="changeDescription(this, '.htmlspecialchars($jitems).' , '.htmlspecialchars($jtypes).')" id="description" name="description[]" autocomplete="off" type="text" class="form-control form-control-sm" list="descriptionList">
                        <datalist id="descriptionList">';
                            foreach ($items as $item){
                            $row .=  '<option value="'.$item['sales_description']. '"></option>';
                            }
        $row .=        '</datalist>
                    </td>
                    </td>
                    <td><input autocomplete="off" onkeyup="changeBn(this)" id="bnbox" name="bnbox" type="text" class="form-control form-control-sm" required></td>
                    <td><input autocomplete="off" onkeyup="changeSt(this)" id="stbox" name="stbox" type="text" class="form-control form-control-sm" required></td>
                    <td><input autocomplete="off" id="totsteam" name="totsteam" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input autocomplete="off" onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm"></td>
                    <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                </tr>';
    
        $row .= ' <tr class="collapse" id="collapseTr'.$request->count.'">
                        <td colspan="8">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label class="col-sm-2 col-form-label form-control-sm">Comments:</label>
                                    <textarea name="comments" id="comments" class="form-control form-control-sm" cols="100" rows="2"></textarea>
                                </div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div class="col">
                                <input name="ship_customer" id="ship_customer" class="form-select form-select-sm" type="text" list="customerList">
                                <datalist id="customerList">
                                    <option selected disabled>Choose a Customer</option>
                                </datalist>
                            </div>
                        </td>
                    </tr>';
    
        return $row;
    }

    public function addRowBill(Request $request){
        $items = Products::where('is_active', 1)->get()->toArray();
        $types = ItemTypes::find(2);
        $jitems = json_encode($items);
        $jtypes = json_encode($types); 

        $row = '<tr id="tr_items">'.
                    '<td id="td_false"></td>'.
                    '<td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr'.$request->count.'" aria-expanded="false" aria-controls="collapseTr'.$request->count.'" hidden></td>'.
                    '<td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                     <td>
                        <input autocomplete="off" onchange="changeItem(this, '.htmlspecialchars($jitems).' , '.htmlspecialchars($jtypes).')" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                        <datalist id="itemsList">';                     
                            foreach ($items as $item){
                               $row .= '<option value="{{'.$item["item_name"].'}}"></option>';
                            }
        $row .=         '</datalist>
                    </td>
                    <td><input autocomplete="off" id="description" name="description[]" type="text" class="form-control form-control-sm"></td>
                    <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" readonly></td>
                    <td><input onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm"></td>
                    <td><input id="amt" type="text" class="form-control form-control-sm" readonly></td>
                </tr>';

        $row .= ' <tr class="collapse" id="collapseTr'.$request->count.'">
                        <td colspan="8">
                            <div id="collapse_container" class="card card-body">
                            </div>
                        </td>
                    </tr>';
        
        return $row;
    }

    public function addRowItemProd(Request $request){
        $items = Products::where('id_type', 2)->get();
        ?>
        <tr>
            <td><button type="button" onclick="deleteRow(this);" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
            <td>
                <input onchange="changeItem(this, <?php echo htmlentities($items); ?>);" type="text" name="cod_production[]" class="form-control form-control-sm" list="itemsList">
                <datalist id="itemsList">
                        <?php
                        foreach ($items as $item) {
                            ?>
                                <option value="<?php echo $item->item_name; ?>"></option>
                            <?php
                        }
                        ?>
                </datalist>
            </td>
            </td>
            <td><input type="text" id="description_production" class="form-control form-control-sm" readonly></td>
            <td><input type="text" id="qty_production" name="qty_production[]" class="form-control form-control-sm"></td>
        </tr>
        <?php

        return;
    }

    public function delRowItemProd($id){
        $items = AssamblyItems::where('id_item_main', $id)->get();
        if ($items) {
            foreach ($items as $item) {
                $item->delete();
            }
        }

        return;
    }

    public function estimate($id){                
        $estimate = SalesOrders::where('id', $id)->first();
        $id_customer = SalesOrders::where('id', $id)->value('id_customer');
        $customer = Customers::find($id_customer);

        $items = SalesOrdersItems::where('id_order', $id)->get()->toArray();

        return view('orders.pdf', compact('estimate', 'items', 'customer'));

    }

    public function invoice($id){
        $invoice = Invoices::where('id', $id)->first();
        $id_customer = Invoices::where('id', $id)->value('id_customer');
        $customer = Customers::find($id_customer);

        $items = InvoicesItems::where('id_invoice', $id)->get()->toArray();

        return view('invoices.pdf', compact('invoice', 'items', 'customer'));
    }

    public function invoiceTicket($id){
        $invoice = Invoices::where('id', $id)->first();
        $id_customer = Invoices::where('id', $id)->value('id_customer');
        $customer = Customers::find($id_customer);

        $items = InvoicesItems::where('id_invoice', $id)->get()->toArray();

        return view('invoices.print', compact('invoice', 'items', 'customer'));
    }

    public function movement($id){
        $variable = explode("-", $id);
        $id = $variable[0];
        $tipo = $variable[1];

        if($tipo == "D"){
            $expenditure = Expenditures::where('id', $id)->first();
            $items = ExpendituresItems::where('id_expenditure', $id)->get()->toArray();
    
            return view('movements.pdf', compact('expenditure', 'items', 'tipo'));
        }
        else{
            $income = Incomes::where('id', $id)->first();
            $items = IncomesItems::where('id_income', $id)->get()->toArray();
    
            return view('movements.pdf', compact('income', 'items', 'tipo'));
        }

    }

    public function kardex(Request $request){
        $product_name= $request->item;
        $from = explode('-',$request->start_month)[1];
        $to = explode('-',$request->end_month)[1];
        $year = explode('-',$request->end_month)[0];
        $fechaActual =  date("Y");

        $nombreBD = App::make('dataBase');
        $dsn = 'mysql:host=localhost;dbname='. $nombreBD;
        $usuario = "root";
        $contrasena = "";

        try {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta = "SELECT * FROM parameters WHERE value = :fechaActual"; 
            $consulta2 =  "SELECT name, value FROM parameters WHERE name LIKE 'emp_%'";
            $stmt = $conexion->prepare($consulta);      
            $stmt->bindParam(':fechaActual', $fechaActual, \PDO::PARAM_STR);
            $stmt->execute();

            $parameter = $stmt->fetch(\PDO::FETCH_ASSOC);

            $resultado = $conexion->query($consulta2);

            $datosEmp = [];
            foreach ($resultado as $fila) {
                $datosEmp[$fila['name']] = $fila['value'];
            }

            if($parameter['name'] == "PERIODO ACTIVO" && $parameter['value'] == $year)
            {
                $fechafrom = Carbon::create(null, $from, 1);
                $nombreMesfrom = ucfirst($fechafrom->translatedFormat('F'));
        
                $fechato = Carbon::create(null, $to, 1);
                $nombreMesto = ucfirst($fechato->translatedFormat('F'));

                $fechaInicio = date('Y-m-01', strtotime($request->start_month)); 
                $fechaFin = date('Y-m-31', strtotime($request->end_month));
        
                $consultaidProducto = "SELECT id FROM Products WHERE item_name = :product_name LIMIT 1"; 
                $stmt = $conexion->prepare($consultaidProducto);
                $stmt->bindParam(':product_name', $product_name, \PDO::PARAM_STR);
                $stmt->execute();

                $productoId = $stmt->fetch(\PDO::FETCH_ASSOC);
                $id_product = $productoId['id'];
                /* 
                    //$response = ProductsBalances::where('id_item',$product->id)->first();
                    // if(!isset($response)){
                    //     for($i=0; $i<=12; $i++){
                    //         ProductsBalances::create([
                    //             "id_item" => $product->id,
                    //             "month" => $i,
                    //             "year" => date('Y'),
                    //             "qty" => 1,
                    //             "cost" => 1
                    //         ]);
                            
                    //     }

                    // }
                */
                
                $consultaSaldoAnterior = "SELECT * FROM product_balances WHERE id_item = :id_product AND month = :previousMonth LIMIT 1";
                $previousMonth = intval($from) - 1;
                $stmt = $conexion->prepare($consultaSaldoAnterior);
                $stmt->bindParam(':id_product', $id_product, \PDO::PARAM_INT);
                $stmt->bindParam(':previousMonth', $previousMonth, \PDO::PARAM_INT);

                $stmt->execute();

                $saldo_anterior = $stmt->fetch(\PDO::FETCH_ASSOC);
                $saldo_anterior = (object) $saldo_anterior;

                $consultaKardex = "SELECT * FROM inventories WHERE id_item = :id_product AND date BETWEEN :fechaInicio AND :fechaFin";
                $stmt = $conexion->prepare($consultaKardex);
                $stmt->bindParam(':id_product', $id_product, \PDO::PARAM_INT);
                $stmt->bindParam(':fechaInicio', $fechaInicio, \PDO::PARAM_STR);
                $stmt->bindParam(':fechaFin', $fechaFin, \PDO::PARAM_STR);
                $stmt->execute();

                $kardex = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                $kardex = array_map(function($row) {
                    return (object) $row;
                }, $kardex);
                
                //return $kardex;
                foreach ($kardex as &$kx) {
                    if ($kx->type == 'Ingreso' || $kx->type == 'Egreso') {
                        $id_transaction = $kx->id_transaction;
                        $consultaIngreso = "SELECT number, comments FROM movements WHERE id = :id_transaction LIMIT 1";
                        $stmt = $conexion->prepare($consultaIngreso);
                        $stmt->bindParam(':id_transaction', $id_transaction, \PDO::PARAM_INT);
                        $stmt->execute();

                        $movimiento = $stmt->fetch(\PDO::FETCH_ASSOC);
                        $movimiento = (object) $movimiento;
                        //return $movimiento;
                        if ($movimiento) {
                            $movimiento = (object) $movimiento;
                            $kx->number = $movimiento->number;
                            $kx->comments = $movimiento->comments;
                        } else {
                            $kx->number = null; // O un valor por defecto
                            $kx->comments = null; // O un valor por defecto
                        }

                    }
                    else if($kx->type == 'Factura'){
                        $id_transaction = $kx->id_transaction;

                        $consultaIngreso = "SELECT number, id_customer FROM invoices WHERE id = :id_transaction LIMIT 1";
                        $stmt = $conexion->prepare($consultaIngreso);
                        $stmt->bindParam(':id_transaction', $id_transaction, \PDO::PARAM_INT);
                        $stmt->execute();

                        $invoice = $stmt->fetch(\PDO::FETCH_ASSOC);
                        $invoice = (object) $invoice;

                        $id_customer = $invoice->id_customer;
                        $consultaCustomer = "SELECT name FROM customers WHERE id = :id_customer LIMIT 1";
                        $stmt = $conexion->prepare($consultaCustomer);
                        $stmt->bindParam(':id_customer', $id_customer, \PDO::PARAM_INT);
                        $stmt->execute();

                        $customer = $stmt->fetch(\PDO::FETCH_ASSOC);
                        $customer = (object) $customer;

                        $kx->number = $invoice->number;
                        $kx->customer = $customer->name;
                    }
                }

                //return $datosEmp;
        
                return view('kardex.pdf', compact('product_name', 'kardex', 'nombreMesfrom', 'nombreMesto', 'year', 'id_product', 'saldo_anterior', 'datosEmp')); 
            }
            else{
                return redirect()->route('kardex.index')->with('info', 'Select a date within the current period')->send();
            }     

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }

    public function productsReport(Request $request){
        
        $sums = array();
        $productsreport = array();
        if($request->warehouse == 0)
        {
            $products = Products::all();
            foreach ($products as $product) {
                $productsreport[]=[
                    "id_item" => $product->id,
                    "QTY" => $product->qty
                ];
            }
        }
        
        else{
            $inventories= Inventories::where("id_warehouse",$request->warehouse)->get();
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
                $productsreport[]=[
                    "id_item" => $id,
                    "QTY" => $sum
                ];
            }
        }
        //return $productsreport;
        return view('productsReport.pdf', compact('productsreport')); 
    }
}
