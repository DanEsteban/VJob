@extends('adminlte::page') 

@section('title', 'Movimientos de Inventario')

@section('plugins.Sweetalert2', true)

@section('content_header')

@stop

@php
    $length = 9;
    $numberD = str_pad($document_numbers[1]['number'], $length,"0", STR_PAD_LEFT);
    $numberI = str_pad($document_numbers[2]['number'], $length,"0", STR_PAD_LEFT);
    //$numberFC = str_pad($document_numbers[4]['number'], $length,"0", STR_PAD_LEFT);
    // $numberD = str_pad($order_numberD[0]['number'], $length,"0", STR_PAD_LEFT);
    // $numberI = str_pad($order_numberI[0]['number'], $length,"0", STR_PAD_LEFT);
@endphp

@section('content')
    <br>
    <form action="{{route('movements.store')}}" enctype="multipart/form-data" method="POST" id="doc_form">
        @csrf
        <div class="container-fluid bg-white shadow" style="min-height: 5rem;">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mt-3">
                    <h2>Movimientos de Inventario</h2>
                </div>
                <div class="col-12 col-md-4 mt-3">
                    <div class="input-group">
                        <label for="" style="font-size: 25px">#</label>
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
                                    <label for="select_move" class="col-sm-3 col-form-label form-control-sm">Tipo:</label>
                                    <select id="select_move" onchange="typenumber(this);" name="mov_transaction" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="2">
                                        <option value="none" selected disabled>Seleccione Movimiento</option>
                                        @for ($i = 1; $i < 3; $i++)
                                            <option value="{{$document_numbers[$i]['id']}}">{{ $document_numbers[$i]['type'] }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm">Fecha:</label>
                                    <input name="date" id="date" type="date" onchange="changeDate(this);" class="form-control form-control-sm" value="{{date('Y-m-d')}}" width="300px">
                                </div>
                            </div>
                            {{-- <div hidden class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm">Wh:</label>
                                    <Select id="select_warehouse" name="select_warehouse" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="13">
                                        @foreach ($warehouses as $wh)
                                            <option value="{{$wh['id']}}" <?php echo (old('select_warehouse')) ? ' selected="selected"' : '';?>>{{$wh['wh_name']}}</option>
                                        @endforeach
                                    </Select>
                                </div>
                            </div> --}}
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-11">
                                <div class="input-group">
                                    <label for="" class="col-sm-2 col-form-label form-control-sm">Comentarios:</label>
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
                <table id="dTable" class="table table-responsive" style="width: 100%; max-height: 329px;">
                    <thead class="bg-dark sticky-top">
                        <tr>
                            <th width="4%"></th>
                            <th width="15%">Codigo</th>
                            <th>Descripcion</th>
                            <th width="10%">Cantidad</th>
                            <th width="10%">U/M</th>
                            <th width="10%" id="th_cost">Costo</th> 
                            <th width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody id="tb_items">
                        @for ($i = 0; $i < 3; $i++)
                            <tr id="tr_items">
                                <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                
                                <td>
                                    <input onchange="changeItemMov(this, {{json_encode($items)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" list="itemsList" >
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['id']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td><input id="description" name="description[]" type="text" class="form-control form-control-sm" readonly></td>
                                <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                                <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" ></td>
                                <td><input id="price" onkeyup="changePrice(this);" name="price[]" type="text" class="form-control form-control-sm"></td>
                                <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <hr>
                <center>
                    <div class="row">                        
                        <div class="col-md-5">
                            <div class="input-group">
                                <label for="" class="col-sm-4 col-form-label form-control-sm">Stock:</label>
                                @if (old('qty'))
                                    <input id="stock" name="stock" style="font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm" value="{{old('qyt')}}">
                                @else
                                    <input id="stock" name="stock" style="font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button onclick="addRow3();" id="addRow" type="button" class="btn btn-sm btn-outline-primary mt-3"><i class="fa-solid fa-plus"></i> Row</button>
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
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">

@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
{{-- <script type="text/javascript" src="/js/orders.actions.js"></script> --}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $("#tb_items").hide();
        $("#addrow").hide();
        $("#addRow").prop("disabled", true);
        
        $("#select_move").change(function() { 
            if ($(this).val() != "") { 
                $("#tb_items").show();
                $("#addrow").show();
                $('#dTable').find('input').val('');
                $("#addRow").prop("disabled", false);
            } else {
                $("#tb_items").hide(); 
                $("#addrow").hide();
            }
        });
    });   
    
    function changeDate(objeto){
        var filas = $('#tb_items #tr_items');

        // Itera sobre cada fila y vacía los datos de cada celda
        filas.each(function() {
            $(this).find('td').each(function() {
                $(this).find('input, select').val('');
            });
        });
    }

    function changeQty(objeto) {
        let tr = $(objeto).parent().parent();
        let qty = parseFloat($(objeto).val()) * 1;
        let price = parseFloat(tr.find('#price').val()) * 1;
        let subtotal = 0;
        if(qty && price){
            subtotal = qty * price;
            tr.find('#amt').val(subtotal.toFixed(2));
            calcularMov();
        }
        else{
            tr.find('#amt').val("0.00");
            calcularMov();
        }
    }

    function changePrice(objeto) {
        let tr = $(objeto).parent().parent();
        let price = parseFloat($(objeto).val()) * 1;
        let qty = parseFloat(tr.find('#qty').val()) * 1;
        let subtotal = 0;
        if(qty && price){
            subtotal = qty * price;
            tr.find('#amt').val(subtotal.toFixed(2));
            calcularMov();
        }
        else{
            tr.find('#amt').val("0.00");
            calcularMov();
        }
    }

    function calcularMov() {
        var subtotal = 0;

        $("#tb_items #amt").each(function(){
            if($(this).val()){
                subtotal = subtotal + (parseFloat($(this).val().replace(',','')) * 1);
            }     
        })

        var resultado = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD',}).format(subtotal);
        //console.log(resultado);
        $("#order_total").val(resultado);

    }
    
    function changeItemMov(objeto, items) {
        //console.log("Te amo mom")
        let date = $("#date").val();
        let code = $(objeto).val().trim();
        let tr = $(objeto).closest('tr');

        let firstDuplicateRow = false;
        let duplicateRowIndex = -1;
        let itemData = items.find(item => item.id == code);
        if (itemData) {
            $('table tbody tr').each(function(index) {
                if ($(this)[0] !== tr[0]) {
                    let existingValue = $(this).find('#items').val().trim();
                    if (existingValue == code) {
                        firstDuplicateRow = $(this);
                        duplicateRowIndex = index;
                        return false;
                    }
                }
            });

            if (firstDuplicateRow) {
                Swal.fire({
                    title: "Error",
                    text: "Ya existe este producto en la tabla",
                    icon: "question",
                })

                tr.find('#items').val('');
                var qty = firstDuplicateRow.find('#qty').val();  
                firstDuplicateRow.find('#qty').val((parseInt(qty)+1));
                changeQty(firstDuplicateRow.find('#qty'));
            } else {
                let url = "/operations/item/code/" + itemData.id;
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'json',
                    data:{date},
                    error: function(xhr, status, error) {
                        console.log(xhr.error);
                        Swal.fire({
                            title: "Error",
                            text: "Ha ocurrido un error al cargar la información del producto.",
                            icon: "error"
                        });
                    },
                    success: function(data) {
                        // console.log(data[0]);
                        tr.find('#description').val(data[0]['item_name']);
                        tr.find('#unit').val((data[0]['abbreviation'] == null) ? 0 : data[0]['abbreviation']);
                        tr.find('#qty').val("1");
                        let precio = parseFloat(data[0]['cost']);
                        tr.find('#price').val(precio.toFixed(2));
                        tr.find('#amt').val(precio.toFixed(2));

                        calcularMov();
                    }
                });
            }
            
            
        } else {
            Swal.fire({
                title: "Error",
                text: "Por favor seleccione un producto existente",
                icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    tr.find('input').val('');
                    tr.find('td').eq(1).find('input').focus();
                }
            });
        }
    }

    function save() {
        var tipo = $("#select_move option:selected").text(); 
        var aprobado = false;

        if (tipo == "Select Movement") {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Seleccione un tipo de movimiento',
            });
            aprobado = true;
        }

        var rows = 0;

        $('#tb_items tr').find('#amt').each(function () {
            var amountValue = $(this).val();
            var $currentRow = $(this).closest('tr');

            if (amountValue !== "") {
                rows++;
            }

            if (amountValue == '0.00') {
                aprobado = true; // Marcar como aprobado para evitar el envío
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "¡El Total de un ingreso no puede ser cero!"
                }).then(() => {
                    $currentRow.find("#price").focus(); // Enfocar el campo anterior
                });
                return false; // Salir del each para evitar múltiples alertas
            }
        });

        if (rows < 1 && !aprobado) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Por favor elija los productos y añádalos a la lista',
            });
            aprobado = true;
        }

        // Enviar el formulario solo si no hubo ningún error
        if (!aprobado) {
            $('#doc_form').submit();
        }
    }


    function typenumber(objeto){
        $type = $(objeto).val();
        console.log($type);
        if ($type == 2) {
            var variablejs = "<?php echo $numberD; ?>" ;
            $("#number").val(variablejs);
            $("#dTable td:nth-child(6) input").prop('readonly', true);
        } else {
            var variablejs = "<?php echo $numberI; ?>" ;
            $("#number").val(variablejs);
            $("#dTable td:nth-child(6) input").prop('readonly', false);

        }
    }
    
    function delRow(object) {
        var filas = $('#tb_items #tr_items').length;
        if(filas > 1){
            $(object).closest('tr').remove();
            calcularMov();
        }
    }

    function addRow3() {
        let type = $('#select_move').val();
        let url = "/elements/order/row/movements";
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            async: 'false',
            data:{type:type},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                //console.log(data)
                $('#tb_items').append(data)
            }
        });
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