@extends('adminlte::page')

@section('title', 'PDF')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Print Invoice</h2>
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
    ?>
    
    <div class="ticket" id="content" style="margin: 0%; padding: 0%;">
        <br>
        <div class="card" style="border: 0px; margin-left: 0%; padding: 0%;">
            <div class="card-body" style="margin-left:0%; padding: 0%;">
                <div class="container-fluid" style="margin-left:0%; padding: 0%;">
                        <br>
                        <div class="row">                           
                            <center>
                                <img src="/img/logo_escala_grises.png" width="350px" alt="">
                            </center>
                        </div>
                        <br>
                        <div class="row" style="line-height: 1px">
                            <div class="col"><p><b>Date:</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$invoice->date}}</b></p></div>
                        </div>
                        <div class="row">
                            <div class="row">
                                <div class="col">
                                    <label style="font-size: 18px; line-height: 8px">INVOICE  &nbsp;&nbsp;&nbsp;&nbsp;{{$invoice->number}}</label>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <label>Flowerist Wholesale</label>
                                        <p style="line-height: 1px">929 New Brunswick Ave</p>
                                        <p style="line-height: 1px">Rahway, New Jersey 07065</p>
                                        <p style="line-height: 1px">United States</p>
                                        <p style="line-height: 1px">Phone: 732-827-5624</p>
                                        <p style="line-height: 1px">info@flowerist.us</p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <hr>
                        <div class="row" style="line-height: 1px">
                            <div class="col">
                                <label class="lb" style="line-height: 15px">Bill To</label>
                                <p>{{$customer->company_name}}</p>
                                <br>

                                <p style="width: 150px">{{$invoice->billto}}</p>
                                <br>

                                <p>{{$invoice->phone}}</p>
                                <p>{{$invoice->email}}</p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="tableT">
                                <thead>
                                    <tr class="trT">
                                        <th class="thT producto">Items</th>
                                        <th class="thT cantidad">Qty</th>
                                        <th class="thT precio">$$</th>
                                    </tr>
                                </thead>
                                <tbody>     
                                        <?php                              
                                    foreach ($items as $item){
                                        ?>
                                        <tr  class="trT">
                                                <?php
                                                $product = Products::where('id', $item['id_item'])->value('sales_description');
                                                ?>
                                                <td class="tdT producto">{{$product}}</td>
                                                <td class="tdT cantidad">{{$item['qty']}}</td>
                                                <td class="tdT precio">${{number_format($item['qty'] * $item['price'], 2)}}</td>
                                        </tr>
                                        <?php
                                    }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row" style="line-height: 1px">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <p class="text-end"><b>Subtotal:</b> </p>
                                    </div>
                                    <div class="col">
                                        <p class="text-center">${{number_format($invoice->total - $invoice->taxes, 2)}}</p>
                                    </div>
                                </div>

                                    <?php
                                if ($invoice->taxes > 0){
                                    ?>
                                        <div class="row">
                                        <div class="col">
                                            <p class="text-end"><b>Taxes:</b></p>
                                        </div>
                                        <div class="col">
                                            <p class="text-center">${{$invoice->taxes}}</p>
                                        </div>
                                    </div>
                                    <?php
                                }
                                    ?>
                                
                                <hr style="line-height: 1px">
                                <div class="row" style="line-height: 1px">
                                    <div class="col">
                                        <p class="text-end"><b>Total:</b></p>
                                    </div>
                                    <div class="col">
                                        <p class="text-center">${{$invoice->total}}</p>
                                    </div>
                                </div>                  
                            </div>
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
<style>
    .content {
        font-size: 17px;
        font-family: 'Arial';
    }

    .tdT,
    .thT,
    .trT,
    .tableT {
        border-top: 1px solid black;
        border-collapse: collapse;
    }

    .tdT.producto,
    .thT.producto {
        width: 70px;
        max-width: 70px;
        word-break: break-all;
    }

    .tdT.cantidad,
    .thT.cantidad {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }

    .tdT.precio,
    .thT.precio {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }

    .centrado {
        text-align: center;
        align-content: center;
    }

    .ticket {
        width: 400px;
        max-width: 400px;
    }

</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
 $( document ).ready(function() {
        setTimeout(function() {
            window.print();
        }, 2000);          
});
</script>
@stop