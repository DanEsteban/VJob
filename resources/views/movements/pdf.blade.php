@extends('adminlte::page')

@section('title', 'PDF')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Print Movement</h2>
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
    
    <div class="container1" style="height: 1400px;"> 
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
                                    @if ($tipo == "D")
                                        <label style="font-size: 50px;">Inventory Discharge</label>
                                    @else
                                        <label style="font-size: 50px;">Inventory Income</label>
                                    @endif
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
                                <div class="row">
                                    @if ($tipo == "D")
                                        <div class="col"><p class="text-end"><b>Discharge Number:</b></p></div> 
                                        <div class="col"><p>{{$expenditure->number}}</p></div>
                                    @else
                                        <div class="col"><p class="text-end"><b>Income Number:</b></p></div> 
                                        <div class="col"><p>{{$income->number}}</p></div>
                                    @endif

                                </div>
                                <div class="row">
                                    @if ($tipo == "D")
                                        <div class="col"><p class="text-end"><b>Discharge Date:</b></p></div>
                                        <div class="col"><p>{{$expenditure->date}}</p></div>
                                    @else
                                        <div class="col"><p class="text-end"><b>Income Date:</b></p></div>
                                        <div class="col"><p>{{$income->date}}</p></div>
                                    @endif
                                   
                                </div>
                                <div class="row">
                                    <div class="col"><p class="text-end"><b>Amount (USD):</b></p></div>
                                    @if ($tipo == "D")
                                        <div class="col"><p>{{$expenditure->total}}</p></div>
                                    @else
                                        <div class="col"><p>{{$income->total}}</p></div>
                                    @endif
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="table table-hover" style="border: 2px solid">
                            @if ($tipo == "D")
                                <thead class="bg-dark">
                                    <tr>
                                        <th width="53%">Items</th>
                                        <th>Quantity</th>
                                        <th hidden>Cost</th>
                                        <th hidden>Amount</th>
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
                                                <td hidden>${{$item['cost']}}</td>
                                                <td hidden>${{number_format($item['qty'] * $item['cost'], 2)}}</td>
                                        </tr>
                                        <?php
                                    }
                                        ?>
                                </tbody>
                            @else
                                <thead class="bg-dark">
                                    <tr>
                                        <th width="53%">Items</th>
                                        <th>Quantity</th>
                                        <th>Cost</th>
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
                                                <td>${{$item['cost']}}</td>
                                                <td>${{number_format($item['qty'] * $item['cost'], 2)}}</td>
                                        </tr>
                                        <?php
                                    }
                                        ?>
                                </tbody>
                            @endif

                            </table>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                
                            </div>
                            <div class="col">
                                <hr>
                                <div class="row">
                                    <div class="col">
                                    @if ($tipo == "D")
                                        <p hidden class="text-end"><b>Total:</b></p>
                                    @else
                                        <p class="text-end"><b>Total:</b></p>
                                    @endif
                                    </div>
                                    <div class="col">
                                        @if ($tipo == "D")
                                            <p hidden class="text-center">${{$expenditure->total}}</p>
                                        @else
                                            <p class="text-center">${{$income->total}}</p>
                                        @endif
                                        
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