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
    
    <div class="container-fluid">
        <br>
        <div class="card" >
            <div class="card-body">
                <div class=".container-fluid">
                        <div class="row">
                            <div class="col">
                                <img src="/img/Logo-web-1.png" width="350px" alt="">
                            </div>
                            <div class="col">
                                <div class="nav justify-content-end">
                                    <label style="font-size: 50px;">INVOICE</label>
                                </div>
                                <div class="text-end">
                                    <label>Flowerist Wholesale</label> 
                                    <p>929 New Brunswick Ave</p>
                                    <p>Rahway, New Jersey 07065</p>
                                    <p>United States</p>
                                </div>
                                <br>
                                <div class="text-end">
                                    <p>Phone: 732-827-5624</p>
                                    <p>info@flowerist.us</p>
                                </div>
                            </div>             
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <label class="lb">Bill To</label>
                                <p>{{$customer->company_name}}</p>
                                <br>

                                <p style="width: 150px">{{$invoice->billto}}</p>
                                <br>

                                <p>{{$invoice->phone}}</p>
                                <p>{{$invoice->email}}</p>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col"><p class="text-end"><b>Invoice Number:</b></p></div>
                                    <div class="col"><p>{{$invoice->number}}</p></div>
                                </div>
                                <div class="row">
                                    <div class="col"><p class="text-end"><b>Invoice Date:</b></p></div>
                                    <div class="col"><p>{{$invoice->date}}</p></div>
                                </div>
                                <div class="row">
                                    <div class="col"><p class="text-end"><b>Amount (USD):</b></p></div>
                                    <div class="col"><p>{{$invoice->total}}</p></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover" style="border: 2px solid">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th width="53%">Items</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>     
                                            <?php                              
                                        foreach ($items as $item){
                                            ?>
                                            <tr>
                                                    <?php
                                                    $product = Products::where('id', $item['id_item'])->value('sales_description');
                                                    ?>
                                                    <td>{{$product}}</td>
                                                    <td>{{$item['qty']}}</td>
                                                    <td>${{$item['price']}}</td>
                                                    <td>${{number_format($item['qty'] * $item['price'], 2)}}</td>
                                            </tr>
                                            <?php
                                        }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                
                            </div>
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
                                
                                <hr>
                                <div class="row">
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

    @foreach ($items as $item)
        @if(AssamblyItems::where('id_item_main', $item['id_item'])->exists())
            <div class="container1" style="height: 1250px;">
                <br>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <img src="/img/Logo-web-1.png" width="350px" alt="">
                            </div>
                            <div class="col">
                                <div class="nav justify-content-end">
                                    <label style="font-size: 50px;">PRODUCTION LIST</label>
                                </div>
                            </div>             
                        </div>
                        <br>
                        <div class="row">
                            <?php
                                $product = Products::where('id', $item['id_item'])->value('sales_description');
                                $items_production = AssamblyItems::where('id_item_main', $item['id_item'])->get();
                            ?>
                            <label style="font-size: 20px;">Elements to create {{$product}}:  {{$item['qty'] * 1}}</label>
                        </div>
                        <br>
                        <div class="row">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items_production as $itm)
                                        <tr>
                                            <td>{{Products::where('id', $itm->id_item)->value('sales_description')}}</td>
                                            <td>{{$itm->qty * $item['qty']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
                <br>
            </div>
        @endif
    @endforeach
    </body>
</html>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
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