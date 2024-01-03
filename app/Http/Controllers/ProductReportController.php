<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Warehouses;
use App\Models\Products;
use App\Models\UnitMeasure;
use App\Models\Inventories;

class ProductReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouse = Warehouses::all();
        return view('productsReport.index', compact('warehouse'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Products::where('id', $code)->first();
        if($product){
            $name_unit_measure = UnitMeasure::where('id', $product->id_unit_measure)->value('abbreviation');
            $product->id_unit_measure = $name_unit_measure;    
        }
        else{
            $product = Products::where('sales_description', $code)->first();
            $name_unit_measure = UnitMeasure::where('id', $product->id_unit_measure)->value('abbreviation');
            $product->id_unit_measure = $name_unit_measure;
        }
        $sumaqty=0;
        $sums = array();
        $inventories= Inventories::where("id_item",$code)->get();
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

          
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
