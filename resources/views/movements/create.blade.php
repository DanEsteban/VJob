@extends('adminlte::page') 

@section('title', 'Register Inventory Movement')

@section('plugins.Sweetalert2', true)

@section('content_header')

@stop

@php
    $length = 9;
    $numberD = str_pad($order_numberD, $length,"0", STR_PAD_LEFT);
    $numberI = str_pad($order_numberI, $length,"0", STR_PAD_LEFT);
@endphp

@section('content')
    <br>
    <form action="{{route('movements.store')}}" enctype="multipart/form-data" method="POST" id="doc_form">
        @csrf
        <div class="container-fluid bg-white shadow"  style="height: 5rem;">
            <div class="row align-items-center">
                <div class="bg-white col-md-8 mt-3">
                    <h2>Register Inventory Movement</h2>
                    <p>V.1.0</p>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-4">
                        <label for="" class="col-sm-2 col-form-label form-control-sm" style="font-size: 25px">#</label>

                        <input id="number" name="number" type="text" class="form-control" style="border: 0; font-size: 25px">
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
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="select_move" class="col-sm-5 col-form-label form-control-sm">Type:</label>
                                    <select id="select_move" onchange="typenumber(this);" name="mov_transaction" style="width:100px;" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="2">
                                        <option value="none" selected disabled>Select Movement</option>
                                        <option value="1">Discharge</option>
                                        <option value="2">Income</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="" class="col-sm-5 col-form-label form-control-sm">Date:</label>
                                    <input name="date" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" width="300px">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-4 col-form-label form-control-sm">Wh:</label>
                                    <Select id="select_warehouse" name="select_warehouse" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                        @foreach ($warehouses as $wh)
                                        <option value="{{$wh->id}}" <?php echo (old('select_warehouse')) ? ' selected="selected"' : '';?>>{{$wh->wh_name}}</option>
                                        @endforeach
                                    </Select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-11">
                                <div class="input-group">
                                    <label for="" class="col-sm-2 col-form-label form-control-sm">Comments:</label>
                                    <textarea name="comments" id="comments" class="form-control form-control-sm" cols="30" rows="2" style="resize:none"></textarea>
                                </div>
                            </div>
                        </div>
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
                            <th width="4%"></th>
                            <th width="15%">Code</th>
                            <th>Description</th>
                            <th width="10%">Qty</th>
                            <th width="10%">U/M</th>
                            <th width="10%" id="th_cost">Cost</th> 
                            <th width="10%">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="tb_items">
                        @for ($i = 0; $i < 3; $i++)
                            <tr id="tr_items">
                                <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                <td>
                                    <input onchange="changeItemMov(this, {{json_encode($items)}}, {{json_encode($types)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['item_name']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td><input id="description" name="description[]" type="text" class="form-control form-control-sm" readonly></td>
                                <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                                <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" readonly></td>
                                <td><input id="price" onkeyup="changePrice(this);" name="price[]" type="text" class="form-control form-control-sm"></td>
                                <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <hr>
                <center>
                    <div class="row">                        
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Stock:</label>
                                @if (old('qty'))
                                    <input id="stock" name="stock" style="width:10%; font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm" value="{{old('qyt')}}">
                                @else
                                    <input id="stock" name="stock" style="width:10%; font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button onclick="addRow3();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Row</button>
                        </div>
                        
                    </div>
                </center>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label id="ltotal" style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_total" id="order_total" value="$0.00" readonly>
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
                    <button onclick="save();" type="button" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
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
                <strong class="me-auto">Flowerist</strong>
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
<link href="/css/configuration.view.css" rel="stylesheet">

@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/orders.actions.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $("#tb_items").hide();
        $("#addrow").hide();
        
        $("#select_move").change(function() { 
            if ($(this).val() != "") { 
            $("#tb_items").show();
            $("#addrow").show();
            } else {
            $("#tb_items").hide(); 
            $("#addrow").hide();
            }
        });
    });    

    function calcularMov() {
        var subtotal = 0;

        $("#tb_items #amt").each(function(){
            if($(this).val()){
                subtotal = subtotal + (parseFloat($(this).val().replace(',','')) * 1);
            }     
        })

        var resultado = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD',}).format(subtotal);
        //$('#order_total').val(resultado);
        console.log(resultado);
        $("#order_total").val(subtotal.toFixed(2));

    }
    
    function changeItemMov(objeto, items) {
        let div_next = $(objeto).parent().parent().next();
        let code = $(objeto).val();
        var selectedWarehouse = $('#select_warehouse option:selected').val();

        function isMatch(item) {
            return item.item_name === code;
        }
        
        code = items.find(isMatch);
        let tr = $(objeto).parent().parent();

        if(code){
            console.log(code['id']);
            let url = "/operations/item/code/" + code['id'];        
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: false,
                data:{selectedWarehouse:selectedWarehouse},
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(data){
                    console.log(data);
                    tr.find('#description').val(data['sales_description']);
                    tr.find('#unit').val(data['id_unit_measure']);
                    $("#stock").val(data['qty']);
                    tr.find('#qty').val("1");
                    tr.find('#price').val(data['price']);
                    tr.find('#amt').val(data['price']);
                
                    $(div_next).find('#collapse_container div').remove();
                    $(div_next).find('#collapse_container hr').remove();
                    
                    calcularMov();  
                }
            });
        }
        else{
            code = $(objeto).val();
            if(code){
                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:'/operations/item/codebar/' +  code,
                    async:false,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.error);
                    },
                    success : function(any){
                        tr.find('#description').val(any['sales_description']);
                        tr.find('#unit').val(any['id_unit_measure']);
                        $("#stock").val(any['qty']);
                        tr.find('#qty').val("1");
                        tr.find('#price').val(any['price']);
                        tr.find('#amt').val(any['price']);
                        $(div_next).find('#collapse_container div').remove();
                        $(div_next).find('#collapse_container hr').remove();
                        calcularMov();
                    }
                });
            }
            else{
                let td_button = $(objeto).parent().prev().prev();
                let td_false = $(objeto).parent().prev().prev().prev();
                $(td_false).removeAttr('hidden');
                $(td_button).attr('hidden', true);
                $(div_next).collapse("hide");
                $(td_button).removeClass("btnminus");
                $(td_button).addClass("btnplus");
        
                tr.find('#description').val(" ");
                tr.find('#qty').val(" ");
                tr.find('#unit').val(" ");
                tr.find('#price').val(" ");
                tr.find('#amt').val(" ");
                calcularMov();
            }
        }
    }

    function save(){

        var tipo = $("#select_move option:selected").text(); 
        var aprobado = 0;
        var rows = 0;

        if(tipo == "Select Movement"){
            Swal.fire(
                'Warning',
                'Select a type of movement',
                'warning'
            )
            aprobado++;
        }

        $('#tb_items tr').find('#amt').each(function () {
            codigo = $(this).val();
            if (codigo != "") {
                rows++;
            }
        })

        if (rows < 1 && aprobado == 0) {
            Swal.fire(
                    'Warning',
                    'Please choose products and add them to the list',
                    'warning'
            )
            aprobado++;
        }

        if(aprobado == 0){
            $('#doc_form').submit();
        }

    }

    function typenumber(objeto){
        $type = $(objeto).val();
        if ($type == 1) {
            var variablejs = "<?php echo $numberD; ?>" ;
            $("#number").val(variablejs);
            $("#dTable th:nth-child(6), #dTable td:nth-child(6)").hide();
            $("#dTable th:nth-child(7), #dTable td:nth-child(7)").hide();
            $("#ltotal").hide();
            $("#order_total").hide();

            // $("#th_cost").attr("hidden",true);
            // $('#tb_items tr #td_cost').each(function () {
            //     console.log('aqui');
            //     $(this).attr('hidden', true)
            // });
            // console.log($('#td_cost'));
        } else {
            var variablejs = "<?php echo $numberI; ?>" ;
            $("#number").val(variablejs);

            $("#dTable th:nth-child(7), #dTable td:nth-child(7)").show();
            $("#dTable th:nth-child(8), #dTable td:nth-child(8)").show();
            $("#ltotal").show();
            $("#order_total").show();
            // $("#tb_items tr #price").removeAttr("readonly");

            // $("#th_cost").removeAttr("hidden");
            // $('#tb_items tr #td_cost').each(function () {
            //     $(this).removeAttr('hidden')
            // });
        }
    }

    function exit() {
        Swal.fire({
            title: 'Do you want to exit the form?',
            showDenyButton: true,
            confirmButtonText: 'Exit',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/movements";
                }
            })
    }
</script>
@stop