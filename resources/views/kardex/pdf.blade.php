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
        ?>
        
        <div class="container1" style="height: 1400px;"> 
            <br>
            <div class="card" >
                <div class="card-body">
                    <div class="container">
                            <div class="row">
                                <div class="col">
                                    <img src="/img/Logo-web-1.png" width="350px" alt="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="nav justify-content-center">
                                        <label style="font-size: 50px;">Kardex from {{$nombreMesfrom}} to {{$nombreMesto}} {{$year}}</label>
                                    </div>
                                </div>  
                            </div>                                     
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                            <div class="col"><p class="text-end"><b>Id Product:</b></p></div> 
                                            <div class="col"><p>{{$id_product}}</p></div>

                                    </div>
                                    <div class="row">
                                            <div class="col"><p class="text-end"><b>Name:</b></p></div>
                                            <div class="col"><p>{{$product_name}}</p></div>
                                    
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <table class="table table-hover" style="border: 2px solid">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Number</th>
                                            <th>Customer/Vendor</th>
                                            <th>Qty</th>
                                            <th>Cost</th>
                                            <th>Total Qty</th>
                                            <th>Total Cost</th>
                                            <th>Unit Cost</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Previous Balance</td>
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
                                                <td>{{date('Y-m-d',strtotime($kx->created_at))}}</td>
                                                <td>{{$kx->type}}</td>
                                                @if ($kx->type=="Invoice")
                                                    <?php
                                                        $response = Invoices::where('id',$kx->id_transaction)->first();
                                                        if ($response) {
                                                            $number = $response->number;                                                                                                             
                                                            $name = Customers::where('id',$response->id_customer)->value('company_name');
                                                            $qty_total-=$kx->qty;
                                                            $cost_total=$cost_total-($kx->qty*$kx->cost);
                                                            $price = number_format($kx->price,5);
                                                        }
                                                        else{
                                                            $number = "";                                                                                                             
                                                            $name = "";
                                                            $qty_total=0;
                                                            $cost_total=0;
                                                            $price = 0;
                                                        }
                                                       
                                                        
                                                    ?>
                                                @elseif($kx->type=="BL")
                                                    <?php
                                                    $response = Bills::where('id',$kx->id_transaction)->first();
                                                    if ($response) {
                                                        
                                                        $number = $response->number;
                                                        $name = Vendors::where('id',$response->id_customer)->value('name');
                                                        $qty_total+=$kx->qty;
                                                        $cost_total=$cost_total+($kx->qty*$kx->price);
                                                        if ($qty_total != 0) {
                                                            $unit_cost=$cost_total/$qty_total;
                                                        } else {
                                                            $unit_cost=0;
                                                        }
                                                        
                                                        
                                                        $price = 0;
                                                    }
                                                    else{
                                                        $number = "";                                                                                                             
                                                        $name = "";
                                                        $qty_total=0;
                                                        $cost_total=0;
                                                        $price = 0;
                                                    }
                                                    ?>
                                                @elseif($kx->type=="Income")
                                                    <?php
                                                    $response = Incomes::where('id',$kx->id_transaction)->first();
                                                    if ($response) {
                                                     
                                                        $number = $response->number;
                                                        $name = $response->comments;
                                                        $qty_total+=$kx->qty;
                                                        $cost_total=$cost_total+($kx->qty*$kx->price);
                                                        if ($qty_total != 0) {
                                                            $unit_cost=$cost_total/$qty_total;
                                                        } else {
                                                            $unit_cost=0;
                                                        }
                                                        $price = 0;
                                                    }
                                                    else{
                                                        $number = "";                                                                                                             
                                                        $name = "";
                                                        $qty_total=0;
                                                        $cost_total=0;
                                                        $price = 0;
                                                    }
                                                    ?>
                                                @else
                                                    <?php
                                                    $response = Expenditures::where('id',$kx->id_transaction)->first();
                                                    if ($response) {
                                                        
                                                        $number = $response->number;
                                                        $name = $response->comments;
                                                        $qty_total-=$kx->qty;
                                                        $cost_total=$cost_total-($kx->qty*$kx->cost);
                                                        $price = 0;
                                                    }
                                                    else{
                                                        $number = "";                                                                                                             
                                                        $name = "";
                                                        $qty_total=0;
                                                        $cost_total=0;
                                                        $price = 0;
                                                    }
                                                    ?>
                                                @endif
                                                <td>{{$number}}</td>
                                                <td>{{$name}}</td>
                                                @if ($number != "")
                                                    <td>{{number_format($kx->qty,2)}}</td>
                                                    <td>{{number_format($kx->qty*$kx->price,2)}}</td>
                                                @else
                                                    <td>{{number_format($qty_total,2)}}</td>
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