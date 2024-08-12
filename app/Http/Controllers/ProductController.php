<?php

namespace App\Http\Controllers;

use App\Models\Colors;
use App\Models\Sizes;
use App\Models\Groups;
use App\Models\Vendors;
use App\Models\Products;
use App\Models\Processes;
use App\Models\ItemTypes;
use App\Models\UnitMeasure;
use App\Models\ImageProduct;
use App\Models\Products_Colors;
use App\Models\Products_Sizes;
use App\Models\AssamblyItems;
use App\Models\Products_LabelBar;
use App\Models\ProductsBalances;
use App\Models\Inventories;
use Illuminate\Http\Request;
use oasis\names\specification\ubl\schema\xsd\CommonAggregateComponents_2\ItemType;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:inventory.index')->only('index'); 
        $this->middleware('can:inventory.create')->only('create', 'store');
        $this->middleware('can:inventory.edit')->only('edit', 'update');
        $this->middleware('can:inventory.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsreport = array();
        $sums = array();
        $sumaqty=0;
        $products = Products::all();
        
        foreach ($products as $product) {
            $product->id_type = ItemTypes::where('id', $product->id_type)->value('name');
            $inventories = Inventories::where("id_item", $product->id)->get();

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
        }

        

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Products::where('id_type', 2)->get();
        $items_type = ItemTypes::all();
        $groups = Groups::all();
        $units = UnitMeasure::all();
        $process = Processes::all();
        $colors = Colors::all();
        $sizes = Sizes::all();
        $vendors = Vendors::all();

        return view('products.create', compact('items', 'items_type', 'groups', 'units', 'process', 'colors', 'sizes', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $item = new Products;
        $item->id_type = $request->select_type;
        if($request->select_type == 1){
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();
        }
        else if($request->select_type == 2){
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->part_number = $request->item_part;
            $item->id_unit_measure = $request->select_unity;
            $item->purchase_description = $request->item_description_p;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->cost = $request->item_cost;
            $item->max_order = $request->item_max;
            $item->min_order = $request->item_min;
            $item->save();

            if(isset($request->code_item)){
                $count = count($request->code_item);
                for ($i=0; $i < $count; $i++) { 
                    if($request->code_item[$i] != null){
                        $vendor = null;
                        if($request->code_vendor[$i] != null){
                            $vendor = Vendors::where('name', $request->code_vendor[$i])->value('id');
                        }

                        Products_LabelBar::create([
                            'id_item' => $item->id,
                            'code' =>  $request->code_item[$i],
                            'id_vendor' => $vendor
                        ]);
                    }
                }
            }

            for($i=1; $i<=12; $i++){
                ProductsBalances::create([
                    "id_item" => $item->id,
                    "month" => $i,
                    "year" => date('Y'),
                    "qty" => 0,
                    "cost" => 0
                ]);
                
            }
        }
        else if($request->select_type == 4){
            $item->id_group = $request->select_group;
            $item->item_name = $request->item_name;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();

            $count = count($request->cod_production);
            for ($i=0; $i < $count; $i++) { 
                AssamblyItems::create([
                    'id_item_main' => $item->id,
                    'id_item' => Products::where('item_name', $request->cod_production[$i])->value('id'),
                    'qty' => $request->qty_production[$i]
                ]);
            }

            if(isset($request->code_item)){
                $count = count($request->code_item);
                for ($i=0; $i < $count; $i++) { 
                    if($request->code_item[$i] != null){
                        $vendor = null;
                        if($request->code_vendor[$i] != null){
                            $vendor = Vendors::where('name', $request->code_vendor[$i])->value('id');
                        }

                        Products_LabelBar::create([
                            'id_item' => $item->id,
                            'code' =>  $request->code_item[$i],
                            'id_vendor' => $vendor
                        ]);
                    }
                }
            }

            for($i=1; $i<=12; $i++){
                ProductsBalances::create([
                    "id_item" => $item->id,
                    "month" => $i,
                    "year" => date('Y'),
                    "qty" => 0,
                    "cost" => 0
                ]);
                
            }
        }
        else{
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->part_number = $request->item_part;
            $item->id_unit_measure = $request->select_unity;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();
        }

        if(isset($request->select_color)){
            $count_colors = count($request->select_color);
            for ($i=0; $i < $count_colors; $i++) { 
                Products_Colors::create([
                    'id_item' => $item->id,
                    'id_color' => $request->select_color[$i]
                ]);
            }
        }

        if(isset($request->select_size)){
            $count_sizes = count($request->select_size);
            for ($i=0; $i < $count_sizes; $i++) { 
                Products_Sizes::create([
                    'id_item' => $item->id,
                    'id_size' => $request->select_size[$i]
                ]);
            }
        }

        if($request->item_files){
            if($request->item_files[0] != null){
                $directory = "img/items/";
                if(!file_exists($directory)){
                    mkdir($directory, 0777);
                }
        
                foreach ($request->item_files as $key => $tmp_name) {
                    $filename = $request->item_files[$key]->getClientOriginalName();
                    $temporal = $tmp_name;
        
                    $dir = opendir($directory);
                    $file = $directory.basename($filename);
        
                    if($request->item_files[$key]->move($directory, $filename)){
                            $attach_img = new ImageProduct;
                            $attach_img->id_product = $item->id;
                            $attach_img->image_name = $filename;
                            $attach_img->image_folder = $file;
                            $attach_img->save();
                    }
        
                    closedir($dir);
        
                }
            }
        }

        return redirect()->route('inventories.index')->with('info', 'A new record has been created')->send();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $items = Products::where('id_type', 2)->get();
        $product = Products::find($id);
        $items_production = AssamblyItems::where('id_item_main', $id)->get();
        $items_type = ItemTypes::all();
        $groups = Groups::all();
        $units = UnitMeasure::all();
        $images = ImageProduct::where('id_product', $id)->get();
        $process = Processes::all();
        $colors = Colors::all();
        $sizes = Sizes::all();
        $vendors = Vendors::all();
        $codebars = Products_LabelBar::where('id_item', $id)->get();

        return view('products.edit', compact('product', 'codebars', 'items', 'items_production', 'items_type', 'groups', 'units', 'images', 'process', 'colors', 'sizes', 'vendors'));
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
        $item = Products::find($id);

        $product_colors = Products_Colors::where('id_item', $id)->get();
        if($product_colors){
            foreach ($product_colors as $color) {
                $color->delete();
            }
        }

        $product_sizes = Products_Sizes::where('id_item', $id)->get();
        if($product_sizes){
            foreach ($product_sizes as $size) {
                $size->delete();
            }
        }

        $assembly_items = AssamblyItems::where('id_item_main', $id)->get();
        if ($assembly_items) {
            foreach ($assembly_items as $item) {
                $item->delete();
            }
        }

        $codebars = Products_LabelBar::where('id_item', $id)->get();
        if($codebars){
            foreach ($codebars as $code) {
                $code->delete();
            }
        }

        if($request->select_type == 1){
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();
        }
        else if($request->select_type == 2){
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->part_number = $request->item_part;
            $item->id_unit_measure = $request->select_unity;
            $item->purchase_description = $request->item_description_p;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->cost = $request->item_cost;
            $item->max_order = $request->item_max;
            $item->min_order = $request->item_min;
            $item->save();

            if(isset($request->code_item)){
                $count = count($request->code_item);
                for ($i=0; $i < $count; $i++) { 
                    if($request->code_item[$i] != null){
                        $vendor = null;
                        if($request->code_vendor[$i] != null){
                            $vendor = Vendors::where('name', $request->code_vendor[$i])->value('id');
                        }

                        Products_LabelBar::create([
                            'id_item' => $item->id,
                            'code' =>  $request->code_item[$i],
                            'id_vendor' => $vendor
                        ]);
                    }
                }
            }
        }
        else if($request->select_type == 4){
            $item->id_group = $request->select_group;
            $item->item_name = $request->item_name;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();

            $count = count($request->cod_production);
            for ($i=0; $i < $count; $i++) { 
                AssamblyItems::create([
                    'id_item_main' => $item->id,
                    'id_item' => Products::where('item_name', $request->cod_production[$i])->value('id'),
                    'qty' => $request->qty_production[$i]
                ]);
            }

            if(isset($request->code_item)){
                $count = count($request->code_item);
                for ($i=0; $i < $count; $i++) { 
                    if($request->code_item[$i] != null){
                        $vendor = null;
                        if($request->code_vendor[$i] != null){
                            $vendor = Vendors::where('name', $request->code_vendor[$i])->value('id');
                        }

                        Products_LabelBar::create([
                            'id_item' => $item->id,
                            'code' =>  $request->code_item[$i],
                            'id_vendor' => $vendor
                        ]);
                    }
                }
            }
        }
        else{
            $item->id_group = $request->select_group;
            $item->id_process = $request->select_process;
            $item->item_name = $request->item_name;
            $item->part_number = $request->item_part;
            $item->id_unit_measure = $request->select_unity;
            $item->sales_description = $request->item_description_s;
            $item->notes = $request->item_notes;
            $item->price = $request->item_price;
            $item->save();
        }

        if(isset($request->select_color)){
            $count_colors = count($request->select_color);
            for ($i=0; $i < $count_colors; $i++) { 
                Products_Colors::create([
                    'id_item' => $item->id,
                    'id_color' => $request->select_color[$i]
                ]);
            }
        } 

        if(isset($request->select_size)){
            $count_sizes = count($request->select_size);
            for ($i=0; $i < $count_sizes; $i++) { 
                Products_Sizes::create([
                    'id_item' => $item->id,
                    'id_size' => $request->select_size[$i]
                ]);
            }
        }

        if($request->item_files){
            if($request->item_files[0] != null){
                $directory = "img/items/";
                if(!file_exists($directory)){
                    mkdir($directory, 0777);
                }
        
                foreach ($request->item_files as $key => $tmp_name) {
                    $filename = $request->item_files[$key]->getClientOriginalName();
                    $temporal = $tmp_name;
        
                    $dir = opendir($directory);
                    $file = $directory.basename($filename);
        
                    if($request->item_files[$key]->move($directory, $filename)){
                            $attach_img = new ImageProduct;
                            $attach_img->id_product = $item->id;
                            $attach_img->image_name = $filename;
                            $attach_img->image_folder = $file;
                            $attach_img->save();
                    }
        
                    closedir($dir);
        
                }
            }
        }
        
        return redirect()->route('inventories.index')->with('info', 'A record has been edited')->send();
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
