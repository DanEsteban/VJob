@extends('adminlte::page') 

@section('title', 'Kardex') 

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Kardex</h2>
        </div>
    </div>
</div>
@stop

@section('content')
<!DOCTYPE html>
<html lang="en">
    <body>
        <?php
            use App\Models\Products;
            use App\Models\AssamblyItems;
            use App\Models\Invoices; 
            use App\Models\Bills;
            use App\Models\Incomes;
            use App\Models\Expenditures;     
            use App\Models\Customers;     
            use App\Models\Vendors;    
            use Carbon\Carbon;
            
            date_default_timezone_set('America/Guayaquil');
        ?>
        
        <div class="container1" style="height: 1400px;"> 
            <br>
            <div class="card" >
                <div class="card-body">
                    <div class="container">
                            <div class="row">
                                <div class="col">
                                    <img src="/{{$datosEmp['emp_ruta_logo']}}" class="img-fluid" width="200px" alt="">
                                </div>
                            
                                <div class="col">
                                    <div class="row">
                                        <div class="nav justify-content-center">
                                            <label style="font-size: 30px;">Kardex desde {{$nombreMesfrom}} hasta {{$nombreMesto}} {{$year}}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <label for="id_producto" class="me-2 mb-0">Id del Producto:</label>
                                                <input id="id_producto" type="text" value="{{$id_product}}" class="form-control border-0" style="width: auto;">
                                            </div>
                                        </div>
                                        <div class="col mt-2">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <label for="producto" class="me-2 mb-0">Producto:</label>
                                                <input id="producto" type="text" value="{{$product_name}}" class="form-control border-0" style="width: auto;">
                                            </div>
                                        </div>
                                    </div>  
                                </div>  
                            </div>                                    
                            <hr>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-hover" style="border: 2px solid">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Numero Doc.</th>
                                            <th>Cliente/Proveedor</th>
                                            <th>Cantidad</th>
                                            <th>Pre.Unit</th>
                                            <th>Pre.Total</th>
                                            <th>Cant.Total</th>
                                            <th>Costo Total</th>
                                            <th>Costo Unitario</th>
                                            <th>P.V.P</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Saldo Anterior</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <?php
                                                $qty_total=$saldo_anterior->qty;
                                                $cost_total=$saldo_anterior->cost;
                                                if ( $qty_total != 0) {
                                                    
                                                    $unit_cost=$cost_total/$qty_total;
                                                }
                                                else{
                                                    $unit_cost = 0;
                                                }
                                                
                                            ?>
                                            <td>{{$saldo_anterior->qty}}</td>
                                            <td>{{$saldo_anterior->cost}}</td>
                                            <td>{{$unit_cost}}</td>
                                            <td></td>
                                        </tr>
                                        @foreach ($kardex as $kx)
                                            <tr>
                                                <td>{{date('Y-m-d',strtotime($kx->date))}}</td>
                                                <td>{{$kx->type}}</td>
                                                @if ($kx->type=="Factura")
                                                    <?php
                                                        // $response = Invoices::where('id',$kx->id_transaction)->first();
                                                        // if ($response) {
                                                            $number = $kx->number;                                                                                                             
                                                            $name = $kx->customer;
                                                            $qty_total-=$kx->qty;
                                                            $cost_total=$cost_total-($kx->qty*$kx->cost);
                                                            $price = number_format($kx->price,5);
                                                        // }
                                                        // else{
                                                        //     $number = "";                                                                                                             
                                                        //     $name = "";
                                                        //     $qty_total=0;
                                                        //     $cost_total=0;
                                                        //     $price = 0;
                                                        // }
                                                        
                                                    ?>
                                                @elseif($kx->type=="BL")
                                                    <?php
                                                    // $response = Bills::where('id',$kx->id_transaction)->first();
                                                    // if ($response) {
                                                        
                                                        $number = $kx->id_transaction;
                                                        $name = "";
                                                        $qty_total+=$kx->qty;
                                                        $cost_total=$cost_total+($kx->qty*$kx->cost);
                                                        if ($qty_total != 0) {
                                                            $unit_cost=$cost_total/$qty_total;
                                                        } else {
                                                            $unit_cost=0;
                                                        }   
                                                        $price = 0;
                                                    // }
                                                    // else{
                                                    //     $number = "";                                                                                                             
                                                    //     $name = "";
                                                    //     $qty_total=0;
                                                    //     $cost_total=0;
                                                    //     $price = 0;
                                                    // }
                                                    ?>
                                                @elseif($kx->type=="Ingreso")
                                                    <?php
                                                    // $response = Incomes::where('id',$kx->id_transaction)->first();
                                                    // if ($response) {

                                                        $number = $kx->number;
                                                        $name = $kx->comments ?? "";
                                                        $qty_total+=$kx->qty;
                                                        $cost_total=$cost_total+($kx->qty*$kx->cost);
                                                        if ($qty_total != 0) {
                                                            $unit_cost=$cost_total/$qty_total;
                                                        } else {
                                                            $unit_cost=0;
                                                        }
                                                        $price = 0;
                                                    // }
                                                    // else{
                                                    //     $number = "";                                                                                                             
                                                    //     $name = "";
                                                    //     $qty_total=0;
                                                    //     $cost_total=0;
                                                    //     $price = 0;
                                                    // }
                                                    ?>
                                                @elseif($kx->type=="Egreso")
                                                    <?php
                                                    // $response = Expenditures::where('id',$kx->id_transaction)->first();
                                                    // if ($response) {
                                                        
                                                        $number = $kx->number;
                                                        $name = $kx->comments ?? "";
                                                        $qty_total-=$kx->qty;
                                                        $cost_total=$cost_total-($kx->qty*$kx->cost);
                                                        $price = 0;
                                                    // }
                                                    // else{
                                                    //     $number = "";                                                                                                             
                                                    //     $name = "";
                                                    //     $qty_total=0;
                                                    //     $cost_total=0;
                                                    //     $price = 0;
                                                    // }
                                                    ?>
                                                @endif
                                                <td>{{$number}}</td>
                                                <td>{{$name}}</td>
                                                @if ($number != "")
                                                    <td>{{number_format($kx->qty,2)}}</td>
                                                    <td>{{number_format($kx->cost,2)}}</td>
                                                    <td>{{number_format($kx->qty*$kx->cost,2)}}</td>
                                                @else
                                                    <td>{{number_format($qty_total,2)}}</td>
                                                    <td>{{number_format($price,2)}}</td>
                                                    <td>{{number_format($qty_total*$price,2)}}</td>
                                                @endif
                                                @if($qty_total < 0)
                                                    <td>{{number_format(0,2)}}</td>
                                                    <td>{{number_format(0,2)}}</td>
                                                    <td>{{number_format(0,5)}}</td>
                                                    <td>{{number_format(0,5)}}</td>
                                                @else
                                                    <td>{{number_format($qty_total,2)}}</td>
                                                    <td>{{number_format($cost_total,2)}}</td>
                                                    <td>{{number_format($unit_cost,5)}}</td>
                                                    <td>{{$price}}</td>
                                                @endif
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </body>
</html>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    // $( document ).ready(function() {
    //     setTimeout(function() {
    //         window.print();
    //     }, 2000);          
    // });
</script>
@stop