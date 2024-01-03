@extends('adminlte::page')

@section('title', 'Edit Order')

@section('content_header')

@stop

@section('content')
@php
    use App\Models\Products;
    use App\Models\VendorOrder;
    use App\Models\VendorOrderItems;
    use App\Models\Vendors;
@endphp
    <br>
    <form action="/vendors/access/api/{{$order->id}}" enctype="multipart/form-data" method="POST" id="doc_form">
        @csrf
        @method('PUT')
        <div class="container-fluid bg-white shadow"  style="height: 5rem;">
            <div class="row align-items-center">
                <div class="bg-white col-md-8 mt-3">
                    <h2>New Bill</h2>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-4">
                        <label for="" class="col-sm-2 col-form-label form-control-sm" style="font-size: 25px">#</label>
                        <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="{{$order->number}}">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="card card-body bg-secondary">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    @php
                                        $vendor = Vendors::where('id', $order->vendor_id)->value('name');
                                    @endphp
                                    <label for="" class="col-sm-2 col-form-label form-control-sm">Vendor:</label>
                                    <input id="select_vendor" name="select_vendor" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" value="{{$vendor}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm">Date:</label>
                                    <input name="vendor_date" type="date" class="form-control form-control-sm" value="{{date('Y-m-d', strtotime($order->date))}}" width="300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            @php
                $numeroV = Vendors::where('id', $order->vendor_id)->value('phone');
                $emailV = Vendors::where('id', $order->vendor_id)->value('email');
                $billV = Vendors::where('id', $order->vendor_id)->value('billto_street');
            @endphp
            <div class="col">
                <label for="" class="col-sm-3 col-form-label form-control-sm">Bill To:</label>
                <div class="input-group">
                    <textarea id="vendor_billto" name="vendor_billto" class="form-control form-control-sm mt-2" cols="30" rows="5" style="width:170px; height: 117px; resize: none">{{$billV}}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="input-group">
                        <label for="" class="col-sm-3 col-form-label form-control-sm">Phone:</label>
                        <input id="vendor_phone" name="vendor_phone" type="text" class="form-control form-control-sm" value="{{$numeroV}}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="input-group">
                        <label for="" class="col-sm-3 col-form-label form-control-sm">Email:</label>
                        <input id="vendor_email" name="vendor_email" type="text" class="form-control form-control-sm" value="{{$emailV}}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="input-group">
                        <label for="" class="col-sm-3 col-form-label form-control-sm">Terms:</label>
                        <select id="select_term" onchange="newTerm();" name="select_term" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                            <option value="" selected disabled>Choose an option</option>
                            <option value="0">------------(New)------------</option>
                            @foreach ($terms as $term)
                                <option value="{{$term->id}}" <?php echo ($term->id ==  old('select_term')) ? ' selected="selected"' : '';?>>{{$term->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <table id="dTable" class="table table-sm" style="width: 100%">
                    <thead>
                        <tr>
                            <th width="15%">Code</th>
                            <th>Description</th>
                            <th width="10%">Qty</th>
                            <th width="10%">Order</th>
                            <th hidden width="10%">Price</th>
                            <th width="10%">Balance</th>
                            <th width="10%">Total</th>

                        </tr>
                    </thead>
                    <tbody id="tb_items">
                        @foreach ($orders_items as $bitem)
                            @php
                                $type = Products::where('id', $bitem->item_id)->value('id_type');
                                $code = Products::where('id', $bitem->item_id)->value('item_name');
                                $description = Products::where('id', $bitem->item_id)->value('sales_description');
                            @endphp
                            <tr id="tr_items">
                                <td>
                                    <input onchange="changeItem(this, {{json_encode($bitem)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" value="{{$code}}" readonly>
                                </td>
                                <td>
                                    <input id="description" name="description[]" type="text" class="form-control form-control-sm" value="{{$description}}" readonly>
                                </td>
                                <td><input onkeyup="changeQty2(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" value="{{$bitem->receive}}" required></td>
                                <td><input id="order" name="order[]" type="text" class="form-control form-control-sm" value="{{$bitem->qty}}" readonly></td>
                                <td hidden><input onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm" value="{{$bitem->price}}"></td>
                                <td><input id="balance" name="balance[]" type="text" class="form-control form-control-sm" value="{{number_format($bitem->qty - $bitem->receive, 2)}}" readonly></td>
                                <td><input id="amt" name="amt[]"type="text" class="form-control form-control-sm" value="{{$bitem->receive * $bitem->price}}" readonly></td>
                                
                            </tr>
                        @endforeach                 
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_total" id="order_total" value="${{$order->total}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <center>
                    <button onclick="save();" type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
                    <button onclick="exit();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                </center>
            </div>
        </div>
        <br>
    </form>

    <!--- Toast --->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                <strong class="me-auto">ART&COLOR</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">
                Added a new record.
              </div>
            </div>
    </div> 

@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/orders.actions.js"></script>
<script type="text/javascript">

    function save(){

        $('#doc_form').submit();

    }


    function changeQty2(value){
        let tr = $(value).parent().parent();
        let order = parseFloat(tr.find("#order").val());
        let qty = parseFloat($(value).val()) * 1;
        let price = parseFloat(tr.find('#price').val()) * 1;
        if (isNaN(qty)) {
            qty=0;
        }
        let balance= order-qty ;
        tr.find("#balance").val(balance.toFixed(2));
        let subtotal = 0;
        if(qty && price){
            subtotal = qty * price;
            tr.find('#amt').val(subtotal);
            calcular();
        }
        else{
            tr.find('#amt').val("0.00");
            calcular();
        }

    }

    function exit() {
        Swal.fire({
                title: 'Do you want to exit the form?',
                showDenyButton: true,
                confirmButtonText: 'Exit',
                denyButtonText: `Cancel`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.history.back();
                    } 
                })
    }

</script>
@stop