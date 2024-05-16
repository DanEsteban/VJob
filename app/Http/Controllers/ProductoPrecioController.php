<?php

namespace App\Http\Controllers;

use App\Models\Impuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProductoPrecioController extends Controller
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
        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $consulta2 = "SELECT * FROM item_types"; 
            $result2= $conexion->query($consulta2);

            $consulta3="SELECT * FROM groups";
            $result3= $conexion->query($consulta3);

            $consulta4="SELECT * FROM unit_measures";
            $result4= $conexion->query($consulta4);

            /* De aqui partimos SELECT products.id, products.id_type, products.id_group, item_types.name, groups.name, item_name, bar_code, unit_measures.abbreviation, num_precio, precio, desde
                FROM products
                LEFT JOIN price_products ON products.id = price_products.id_product
                LEFT JOIN item_types ON products.id_type = item_types.id
                LEFT JOIN groups ON products.id_group = groups.id
                LEFT JOIN unit_measures ON products.id_unit_measure = unit_measures.id
            */ 
            $consulta = "SELECT 
                p.id AS product_id,
                t.id AS id_product_type,
                t.name AS product_type,
                g.id AS id_product_group,
                g.name AS product_group,
                p.item_name AS product_name,
                p.bar_code AS product_bar_code,
                p.si_iva AS product_si_iva,
                p.iva AS product_iva,
                u.id AS id_unit_measure,
                u.abbreviation AS product_unit_measure,
                pp.num_precio,
                pp.precio,
                pp.precio_iva,
                pp.desde
            FROM products AS p
            LEFT JOIN price_products AS pp ON p.id = pp.id_product
            LEFT JOIN item_types AS t ON p.id_type = t.id
            LEFT JOIN groups AS g ON p.id_group = g.id
            LEFT JOIN unit_measures AS u ON p.id_unit_measure = u.id
            ORDER BY p.id DESC, pp.num_precio DESC";

            $resultados = [];
            $currentProduct = null;

            foreach ($conexion->query($consulta) as $row) {
                $productId = $row['product_id'];

                if ($currentProduct === null || $currentProduct['id'] !== $productId) {
                    if ($currentProduct !== null) {
                        $resultados[] = $currentProduct;
                    }
                    $currentProduct = [
                        "id" => $productId,
                        "id_product_type" => $row['id_product_type'],
                        "type" => $row['product_type'],
                        "id_product_group" => $row['id_product_group'],
                        "group" => $row['product_group'],
                        "item_name" => $row['product_name'],
                        "bar_code" => $row['product_bar_code'],
                        "si_iva" => $row['product_si_iva'],
                        "iva" => $row['product_iva'],
                        "id_unit_measure" => $row['id_unit_measure'],
                        "unit_measure" => $row['product_unit_measure'],
                        "pvp1" => null,
                        "cantidad2" => null,
                        "pvp2" => null,
                        "cantidad3" => null,
                        "pvp3" => null,
                        "cantidad4" => null,
                        "pvp4" => null,
                    ];
                }

                $numPrecio = $row['num_precio'];
                $precio = $row['precio_iva'];
                $desde = $row['desde'];

                switch ($numPrecio) {
                    case 1:
                        $currentProduct['pvp1'] = $precio;
                        break;
                    case 2:
                        $currentProduct['pvp2'] = $precio;
                        $currentProduct['cantidad2'] = $desde;
                        break;
                    case 3:
                        $currentProduct['pvp3'] = $precio;
                        $currentProduct['cantidad3'] = $desde;
                        break;
                    case 4:
                        $currentProduct['pvp4'] = $precio;
                        $currentProduct['cantidad4'] = $desde;
                        break;
                }
            }

            if ($currentProduct !== null) {
                $resultados[] = $currentProduct;
            }

            $item_types=[];
            foreach ($result2 as $fila) {
                $item_types[]=[
                    "id" => $fila['id'],
                    "name" => $fila['name']   
                ];
            }
            $groups=[];
            foreach ($result3 as $familia) {
                foreach ($item_types as $linea) {                
                    if ($linea['id'] === $familia['id_type']) {
                        $groups[]=[
                            "id" => $familia['id'],
                            "id_type" => $familia['id_type'],
                            "name" => $familia['name']   
                        ];
                    }
                }
            }

            $unit_measures=[];
            foreach ($result4 as $fila) {
                $unit_measures[]=[
                    "id" => $fila['id'],
                    "abbreviation" => $fila['abbreviation']   
                ];
            }
            //return $item_types;
            //return view('productoPrecio.index', compact('resultados', 'item_types', 'groups', 'unit_measures'));

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }  
        
        $p_impuestos = Impuesto::all();
        $fechaActual = Carbon::now()->toDateString(); 
        $impuestoActual = Impuesto::where('desde', '<=', $fechaActual)
                                ->where('hasta', '>=', $fechaActual)
                                ->first();
        //return $p_impuestos;

        return view('productoPrecio.index', compact('resultados', 'item_types', 'groups', 'unit_measures', 'p_impuestos', 'impuestoActual'));

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
        //return $request;
        $p_impuestos = Impuesto::all(); 
        
        $fechaActual = Carbon::now()->toDateString(); 
        
        //return $fechaActual;
        $impuestoActual = Impuesto::where('desde', '<=', $fechaActual)
                                ->where('hasta', '>=', $fechaActual)
                                ->first();
        $nombreBD =  App::make('dataBase');

        try {

            $db = new \PDO('mysql:host=localhost;dbname='. $nombreBD, 'root', '');       
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
            // Itera sobre el array de productos
            foreach ($request->producto as $index => $producto) {
                
                //Para verificar si existe un id para asi poder editar
                $id= $request->id[$index];
                // Actualiza los valores de los parámetros de la consulta SQL
                $id_type = $request->type[$index];
                $id_group = $request->group[$index];
                $item_name = $producto;
                $bar_code = $request->codigoBarras[$index];
                $siIva = $request->siIva[$index];
                $iva = $request->iva[$index] ?? 1;
                
                if (($siIva == 1 && ($iva == 0 || $iva == 1)) || ($siIva == 1 && $iva == $impuestoActual->id) || $siIva == 0) {
                    $iva = 1;
                }
                
                //return $iva;
                $id_unit_measure = $request->medida[$index];
                $notes = null;
                $is_active = 1;

                
                if (!is_null($id) || $id != "") {  
                    $sql = "UPDATE products 
                    SET id_type = :id_type, 
                        id_group = :id_group, 
                        item_name = :item_name, 
                        bar_code = :bar_code,
                        si_iva = :si_iva,
                        iva = :iva, 
                        id_unit_measure = :id_unit_measure, 
                        notes = :notes, 
                        is_active = :is_active
                    WHERE id = '{$id}'";   

                    $stmt = $db->prepare($sql);

                    // Vincula los valores a los marcadores de posición
                    $stmt->bindParam(':id_type', $id_type, \PDO::PARAM_INT);
                    $stmt->bindParam(':id_group', $id_group, \PDO::PARAM_INT);
                    $stmt->bindParam(':item_name', $item_name, \PDO::PARAM_STR);
                    $stmt->bindParam(':bar_code', $bar_code, \PDO::PARAM_STR);
                    $stmt->bindParam(':si_iva', $siIva, \PDO::PARAM_STR);
                    $stmt->bindParam(':iva', $iva, \PDO::PARAM_STR);
                    $stmt->bindParam(':id_unit_measure', $id_unit_measure, \PDO::PARAM_INT);
                    $stmt->bindParam(':notes', $notes, \PDO::PARAM_NULL);
                    $stmt->bindParam(':is_active', $is_active, \PDO::PARAM_INT);

                    // Ejecuta la consulta para insertar en la tabla "products"
                    $stmt->execute();

                    $prices = [];
                    if (isset($request->pvp4[$index]) && isset($request->cantidad4[$index])) {
                        $prices[] = ['pvp' => $request->pvp4[$index], 'cantidad' => $request->cantidad4[$index], 'num' => 4];
                    }
                    if (isset($request->pvp3[$index]) && isset($request->cantidad3[$index])) {
                        $prices[] = ['pvp' => $request->pvp3[$index], 'cantidad' => $request->cantidad3[$index], 'num' => 3];
                    }
                    if (isset($request->pvp2[$index]) && isset($request->cantidad2[$index])) {
                        $prices[] = ['pvp' => $request->pvp2[$index], 'cantidad' => $request->cantidad2[$index], 'num' => 2];
                    }
                    if (isset($request->pvp1[$index])) {
                        $prices[] = ['pvp' => $request->pvp1[$index], 'cantidad' => 1, 'num' => 1];
                    }

                    // $prices = [
                    //     ['pvp' => $request->pvp4[$index], 'cantidad' => $request->cantidad4[$index], 'num' => 4],
                    //     ['pvp' => $request->pvp3[$index], 'cantidad' => $request->cantidad3[$index], 'num' => 3],
                    //     ['pvp' => $request->pvp2[$index], 'cantidad' => $request->cantidad2[$index], 'num' => 2],
                    //     ['pvp' => $request->pvp1[$index], 'cantidad' => 1, 'num' => 1]
                    // ];
                    $desde_anterior="";

                    if($siIva == 1 && $iva == 1){
                        $iva = $impuestoActual->id;
                    }

                    foreach ($prices as $price) {
                        if ($price['pvp'] != null) {
                            $num_precio = $price['num'];
                            $precio_iva = $price['pvp'];
                            for ($i=0; $i < count($p_impuestos); $i++) { 
                                if ($iva === strval($p_impuestos[$i]['id'])) {
                                    $precio =  floatval($precio_iva/(1+(floatval($p_impuestos[$i]['porcentaje'])/100)));
                                    $precio = strval($precio);
                                }
                            }

                            $desde = $price['cantidad'];
                            $hasta = ($desde_anterior != null) ? intval($desde_anterior) - 1 : 999999999;
                            $desde_anterior = $desde;

                            $sql2 = "SELECT * FROM price_products WHERE id_product = '{$id}' AND num_precio = '{$num_precio}'";  
                            $result2 = $db->query($sql2);                       
                            if ($result2->rowCount() > 0) {
                                // Se encontraron registros
                                $sql = "UPDATE price_products 
                                        SET precio = :precio,
                                            precio_iva = :precio_iva,  
                                            desde = :desde, 
                                            hasta = :hasta
                                        WHERE id_product = :id_product  AND  num_precio = :num_precio";
                            }else{
                                $sql = "INSERT INTO price_products (id_product, num_precio, precio, precio_iva, desde, hasta)
                                VALUES (:id_product, :num_precio, :precio, :precio_iva, :desde, :hasta)";
                            }

                            $stmtPrice = $db->prepare($sql);
                            $stmtPrice->bindParam(':id_product', $id, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':num_precio', $num_precio, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':precio', $precio, \PDO::PARAM_STR);
                            $stmtPrice->bindParam(':precio_iva', $precio_iva, \PDO::PARAM_STR);
                            $stmtPrice->bindParam(':desde', $desde, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':hasta', $hasta, \PDO::PARAM_INT);

                            $stmtPrice->execute();

                        }
                    }

                }else{
    
                    // Prepara la consulta SQL con marcadores de posición
                    $sql = "INSERT INTO products (id_type, id_group, item_name, bar_code, si_iva, iva, id_unit_measure, notes, is_active)
                    VALUES (:id_type, :id_group, :item_name, :bar_code, :si_iva, :iva, :id_unit_measure, :notes, :is_active)";
                    $stmt = $db->prepare($sql);

                    if($iva === $impuestoActual->id){
                        $iva = 1;
                    }

                    // Vincula los valores a los marcadores de posición
                    $stmt->bindParam(':id_type', $id_type, \PDO::PARAM_INT);
                    $stmt->bindParam(':id_group', $id_group, \PDO::PARAM_INT);
                    $stmt->bindParam(':item_name', $item_name, \PDO::PARAM_STR);
                    $stmt->bindParam(':bar_code', $bar_code, \PDO::PARAM_STR);
                    $stmt->bindParam(':si_iva', $siIva, \PDO::PARAM_STR);
                    $stmt->bindParam(':iva', $iva, \PDO::PARAM_STR);
                    $stmt->bindParam(':id_unit_measure', $id_unit_measure, \PDO::PARAM_INT);
                    $stmt->bindParam(':notes', $notes, \PDO::PARAM_NULL);
                    $stmt->bindParam(':is_active', $is_active, \PDO::PARAM_INT);
            
                    $stmt->execute();

                    // Obtiene el ID del registro insertado
                    $lastInsertedId = $db->lastInsertId();
        

                    $prices = [
                        ['pvp' => $request->pvp4[$index], 'cantidad' => $request->cantidad4[$index], 'num' => 4],
                        ['pvp' => $request->pvp3[$index], 'cantidad' => $request->cantidad3[$index], 'num' => 3],
                        ['pvp' => $request->pvp2[$index], 'cantidad' => $request->cantidad2[$index], 'num' => 2],
                        ['pvp' => $request->pvp1[$index], 'cantidad' => 1, 'num' => 1]
                    ];
                    $desde_anterior="";

                    if($siIva == 1 && $iva == 1){
                        $iva = $impuestoActual->id;
                    }
                    //return $iva;
                    
                    foreach ($prices as $price) {
                        if ($price['pvp'] != null) {
                            $num_precio = $price['num'];
                            $precio_iva = $price['pvp'];
                            for ($i=0; $i < count($p_impuestos); $i++) { 
                                
                                if ($iva == strval($p_impuestos[$i]['id'])) {
                                    $precio =  floatval($precio_iva/(1+(floatval($p_impuestos[$i]['porcentaje'])/100)));
                                    $precio = strval($precio);
                                    //return $precio;
                                }
                            }
                            
                            $desde = $price['cantidad'];
                            $hasta = ($desde_anterior != null) ? intval($desde_anterior) - 1 : 999999999;
                            $desde_anterior = $desde;
                            

                            $sql = "INSERT INTO price_products (id_product, num_precio, precio, precio_iva, desde, hasta)
                            VALUES (:id_product, :num_precio, :precio, :precio_iva, :desde, :hasta)";

                            $stmtPrice = $db->prepare($sql);
                            $stmtPrice->bindParam(':id_product', $lastInsertedId, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':num_precio', $num_precio, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':precio', $precio, \PDO::PARAM_STR);
                            $stmtPrice->bindParam(':precio_iva', $precio_iva, \PDO::PARAM_STR);
                            $stmtPrice->bindParam(':desde', $desde, \PDO::PARAM_INT);
                            $stmtPrice->bindParam(':hasta', $hasta, \PDO::PARAM_INT);
                
                            $stmtPrice->execute();
                        }
                    }

                    $year = Carbon::now()->year;
                    $qty = 0;
                    $cost = 0;
                    for($month = 1; $month <= 12 ; $month ++){
                        $sql = "INSERT INTO product_balances (id_item, year, month, qty, cost)
                        VALUES (:id_item, :year, :month, :qty, :cost)";
                        
                        $stmtPrice = $db->prepare($sql);
                        $stmtPrice->bindParam(':id_item', $lastInsertedId, \PDO::PARAM_INT);
                        $stmtPrice->bindParam(':year', $year, \PDO::PARAM_INT);
                        $stmtPrice->bindParam(':month', $month, \PDO::PARAM_STR);
                        $stmtPrice->bindParam(':qty', $qty, \PDO::PARAM_STR);
                        $stmtPrice->bindParam(':cost', $cost, \PDO::PARAM_STR);
            
                        $stmtPrice->execute();
                    }
                }
            }
            
        
        } catch (\PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
        return redirect()->route('productoPrecio.index');
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

    }

    public function delete($id){
        $nombreBD =  App::make('dataBase');
        try {
            $db = new \PDO('mysql:host=localhost;dbname=' . $nombreBD, 'root', '');       
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();


            $sql2 = "DELETE FROM price_products WHERE id_product = :id";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt2->execute();

            $sql3= "DELETE FROM product_balances WHERE id_item = :id";
            $stmt3 = $db->prepare($sql3);
            $stmt3->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt3->execute();

            
            return response()->json(['success' => true, 'message' => 'El registro se eliminó correctamente']);
            //return redirect()->route('productoPrecio.index');
    
        
        } catch (\PDOException $e) {
            return response()->json(['success' => true, 'message' => 'El registro se eliminó correctamente']);
        }
    }
}
