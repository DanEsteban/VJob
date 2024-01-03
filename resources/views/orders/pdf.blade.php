@extends('adminlte::page')

@section('title', 'PDF')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Print Estimate</h2>
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
            ?>
        
    <br>
        <div class="card" >
            <div class="card-body">
            <div class="container">
                    <div class="row">
                        <div class="col">
                            <img src="/img/Logo-web-1.png" width="350px" alt="">
                        </div>
                        <div class="col">
                            <div class="nav justify-content-end">
                                <label style="font-size: 50px;">ESTIMATE</label>
                            </div>
                            <div class="text-end">
                                <label>ART & COLOR LLC</label>
                                <p>9 West Street</p>
                                <p>Danbury, Connecticut 06810</p>
                                <p>United States</p>
                            </div>
                            <br>
                            <div class="text-end">
                                <p>Phone: (475) 294-3628</p>
                                <p>Mobile: (203) 592-0527</p>
                                <p>artandcolor.io</p>
                            </div>
                        </div>             
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label class="lb">Bill To</label>
                            <p>{{$customer->company_name}}</p>
                            <br>

                            <p style="width: 150px">{{$estimate->billto}}</p>
                            <br>

                            <p>{{$estimate->phone}}</p>
                            <p>{{$estimate->email}}</p>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col"><p class="text-end"><b>Estimate Number:</b></p></div>
                                <div class="col"><p>{{$estimate->number}}</p></div>
                            </div>
                            <div class="row">
                                <div class="col"><p class="text-end"><b>Estimate Date:</b></p></div>
                                <div class="col"><p>{{$estimate->date}}</p></div>
                            </div>
                            <div class="row">
                                <div class="col"><p class="text-end"><b>Amount (USD):</b></p></div>
                                <div class="col"><p>{{$estimate->total}}</p></div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
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
                                    <p class="text-center">${{number_format($estimate->total - $estimate->taxes, 2)}}</p>
                                </div>
                            </div>

                                <?php
                            if ($estimate->taxes > 0){
                                ?>
                                    <div class="row">
                                    <div class="col">
                                        <p class="text-end"><b>Taxes:</b></p>
                                    </div>
                                    <div class="col">
                                        <p class="text-center">${{$estimate->taxes}}</p>
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
                                    <p class="text-center">${{$estimate->total}}</p>
                                </div>
                            </div>                  
                        </div>
                    </div>
            </div>
            </div>
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
        $( document ).ready(function() {
            setTimeout(function() {
                window.print();
            }, 2000);          
        });
    </script>
@stop