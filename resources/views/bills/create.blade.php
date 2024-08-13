@extends('adminlte::page')

@section('title', 'Factura de Compra')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)


@section('content_header')

@stop

@section('content')

<?php
    use Carbon\Carbon;

    date_default_timezone_set('America/Guayaquil');

    $fechaActual = Carbon::now();
    $fechaFormateada  = $fechaActual->locale('es_ES')->isoFormat('dddd, D [de] MMMM [de] YYYY');
    
?>

    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif
    <br>

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="text-left">Logo Empresa</h1>
                    </div>

                    <div class="col-md-6 text-right">
                        <p><strong>{{$datosEmp['emp_nombre']}}</strong></p>
                        <p><strong>Ruc: </strong>{{$datosEmp['emp_ruc']}}</p>
                        <p>{{$datosEmp['emp_dir']}}</p>
                        <p><strong>Telf: </strong>{{$datosEmp['emp_tel']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="formulario" action="{{route('bills.store')}}" method="POST"> 
        @csrf
        <input type="text" name="emp_nombre" value="{{$datosEmp['emp_nombre']}}" hidden>
        <input type="text" name="emp_ruc" value="{{$datosEmp['emp_ruc']}}" hidden>
        <input type="text" name="emp_dir" value="{{$datosEmp['emp_dir']}}" hidden>
        <div class="card-body">
            <div class="form-container">
                <div class="form-group">
                    <label for="number">No. Documento:</label>
                    <input type="text" id="number" name="number" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="ruc">R.U.C.:</label>
                    <input onchange="buscarPorRuc(this);" type="text" id="ruc" name="ruc" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="fecha_fact">Fecha factura:</label>
                    <input onchange="cambioFecha()" type="date" id="fecha_fact" name="fecha_fact" value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" min="{{ now()->subDays(15)->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="proveedor">Nombre:</label>
                    <input type="text" id="proveedor" name="proveedor" required>
                    <input type="text" id="id_proveedor" name="id_proveedor" value="1" hidden>
                </div>
                <div class="form-group">
                    <label for="fecha-ingreso">Fecha de ingreso a bodega:</label>
                    <input onchange="cambioFecha()" type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" min="{{ now()->subDays(15)->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="dTable" class="table table-responsive" style="width: 100%; max-height: 329px;">
                    <thead class="bg-dark sticky-header">
                        <th width="2%"></th>
                        <th style="width: 90px;">Código</th>
                        <th style="width: 300px;">Descripción</th>    
                        <th style="width: 5px;">Cant.Unid.</th>
                        <th style="width: 20px;">U/M</th>        
                        <th style="width: 20px;" id="th_cost">Precio</th>                       
                        <th hidden>PrecioIva</th>
                        <th style="width: 10px;">IVA</th>
                        <th style="width: 20px;">Subtotal</th>
                    </thead>
                    <tbody id="tb_items">
                        <?php for ($i = 0; $i < 10; $i++) : ?>
                            <tr id="tr_items">
                                <td>
                                    <button onclick="vaciarRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </td>
                                <td>
                                    <input onchange="changeItem(this, {{json_encode($items)}});" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" list="itemsList">
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['id']}}"></option> 
                                        @endforeach
                                    </datalist>
                                </td>
                                <td>
                                    <input onchange="changeDescription(this, {{json_encode($items)}})" id="description" type="text" autocomplete="off" class="form-control form-control-sm" list="itemDesList">
                                    <datalist id="itemDesList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['item_name']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td id="tdC1"><input type="text" onchange="changeQty(this);" id="cantidad" name="cantidad[]" class="form-control form-control-sm"></td>
                                <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" ></td>  
                                <td><input id="price" onchange="changePrice(this);" name="price[]" type="text" class="form-control form-control-sm"></td>
                                <td hidden><input type="text" id="id_iva" name="iva[]" hidden></td>
                                <td><input type="text" id="iva" class="form-control form-control-sm" readonly></td>
                                <td><input type="text" id="subtotal" name="subtotal[]" class="form-control form-control-sm" readonly></td>
                            </tr>
                        <?php endfor; ?> 
                    </tbody>
                </table>
                <hr>
                <center>
                    <button onclick="addRow();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Row</button>
                </center> 
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <button onclick="guardar();" type="button" class="btn btn-sm btn-outline-success mr-3">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary mr-3">
                            <i class="fa-solid fa-print"></i> Imprimir
                        </button>
                        <button onclick="salir();" type="button" class="btn btn-sm btn-outline-success mr-3">
                            <i class="fa-solid fa-door-open"></i> Salir
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-md-6 mb-2 text-md-end">
                                    <strong>Subtotal:</strong>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input class="border-0 col-sm-12 text-right font-weight-bold" id="sumaSub" name="sumaSub" type="text" value="0.00" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-md-6 mb-2 text-md-end">
                                    <strong>Base 0%:</strong>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input class="border-0 col-sm-12 text-right font-weight-bold" id="baseCero" name="baseCero" type="text" value="0.00" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-md-6 mb-2 text-md-end">
                                    <strong>Base IVA:</strong>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input class="border-0 col-sm-12 text-right font-weight-bold" id="baseIva" name="baseIva" type="text" value="0.00" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-md-6 mb-2 text-md-end">
                                    <strong>I.V.A:</strong>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <input class="border-0 col-sm-12 text-right font-weight-bold" id="siIva" name="siIva" type="text" value="0.00" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-sm-12 col-md-6 mb-2 text-md-end">
                                <strong>Total:</strong>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                <input class="border-0 col-sm-12 text-right font-weight-bold" id="total" name="total" type="text" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@stop


@section('css')
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .form-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-group {
        flex: 1 1 45%;
        display: flex;
        align-items: center;
    }

    .form-group label {
        width: 150px; /* Ancho fijo para las etiquetas */
        margin-right: 10px;
        text-align: right;
    }

    .form-group input {
        width: 40%;
        box-sizing: border-box;
        padding: 0.25rem; /* Reduce el padding para hacer los inputs más finos */
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.875rem; /* Reduce el tamaño de la fuente para inputs más finos */
    }

        @media screen and (min-width:1200px) {
            .custom-paragraph {
                font-size: 20px;
            }
        }

        @media screen and (min-width: 900px) and (max-width: 1200px) {
            .custom-paragraph {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 719px) {
            .custom-paragraph {
                font-size: 12px;
            }
        }

        #PVP1,#PVP2,#PVP3, #PVP4 {
            color: #ffff;
            font-weight: 600;
            border-radius: 5px;
        }
        
        #sumaSub, #total{
            color: red;
        }
        #total{
            font-size: 25px;
        }
        .custom-input {
            width: 150px;
            display: inline-block; 
        }


        #savemodal input{
            height: 27px;
        }

        .form-group {
            margin: 0;
        }

        .form-group select{
            height: 1.8rem;
            width: 7rem;
            font-size: 0.8rem;
        }

        .form-control{
            height: 1.8rem;
        }

        .ntransfer{
            margin-left: 1rem;
        }

        .card-header .col-md-6 p {
            margin-bottom: 3px;
        }

    </style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>
<script type="text/javascript">
    
    
    $(document).ready(function () {

        $('#dTable').DataTable({
            searching: false, 
            paging: false,    
            // scrollY: '300px', 
            scrollCollapse: true, 
            info: false 
        });

        $('form').on('keydown', 'input, select, textarea', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });        
    });

    function cambioFecha() {
        var fechaInput = $('#fecha_fact').val()
        var fechaSpan = $('#fechaSpan')
        var fechaFormateada = obtenerFechaFormateada(fechaInput);
        fechaSpan.html(fechaFormateada);
        
    }

    function obtenerFechaFormateada(fecha) {
        var fechaFormateada = moment(fecha).locale('es_ES').format('dddd, D [de] MMMM [de] YYYY');
        return fechaFormateada;
    }

    function vaciarRow(objeto) {
        let fila = $(objeto).parent().parent();
        const primerInput = $(fila).find('td input').first();
        Swal.fire({
        title: 'Do you want to delete this item?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                $(fila).find('td input').each(function() {
                    $(this).val('');
                });
                primerInput.focus();
                var filasConDatos = leerFilas();

                calcular(filasConDatos);
            }
        })
    }


    function changeQty(objeto) {
        let tr = $(objeto).parent().parent();
        let qty = parseFloat($(objeto).val()) * 1;
        let price = parseFloat(tr.find('#price').val()) * 1;
        let subtotal = 0;
        if(qty && price){
            subtotal = qty * price;
            tr.find('#subtotal').val(subtotal.toFixed(2));
            var filasConDatos = leerFilas();        
            calcular(filasConDatos);
        }
        else{
            tr.find('#subtotal').val("0.00");
            var filasConDatos = leerFilas();        
            calcular(filasConDatos);
        }
    }



    function leerFilas() {
        var table = $('#dTable').DataTable();
        var filas = table.rows().nodes();
        var filasConDatos = {};
        
        filas.each(function (rowNode, index) {
            
            var tr = $(rowNode);
            var celdas = tr.find('td');   
            var codigo = $(celdas[1].childNodes[1]).val();
            //console.log(codigo);
            if (codigo !== '') {
                if (!filasConDatos[codigo]) {
                    // Si no existe, crea un nuevo arreglo
                    filasConDatos[codigo] = [];
                }
                filasConDatos[codigo].push(tr);              
            }    
        }); 
        return filasConDatos;
    }

    function changeItem(objeto, items) { 
        let code = $(objeto).val().trim();
        let tr = $(objeto).closest('tr');
        let firstDuplicateRow = false;
        let duplicateRowIndex = -1;
        let itemData = items.find(item => item.id == code);
        if (itemData) {
            $('table tbody tr').each(function(index) {
                if ($(this)[0] !== tr[0]) {
                    let existingValue = $(this).find('#items').val();
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
                    text: "Ya existe este producto en la tabla, se le sumo 1 a la fila existente con el producto",
                    icon: "question",
                })

                tr.find('#items').val('');
                var qty = firstDuplicateRow.find('#cantidad').val();  
                firstDuplicateRow.find('#cantidad').val((parseInt(qty)+1));
                changeQty(firstDuplicateRow.find('#cantidad'));
            } else {
                let url = "/operations/item/code/" + itemData.id;        
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'json',
                    async: false,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.error);
                    },
                    success : function(data){
                        //console.log(data)
                        tr.find('#items').val(data[0]['id']);
                        tr.find('#description').val(data[0]['item_name']);
                        tr.find('#cantidad').val("1");
                        let precio = parseFloat(data[0]['cost']);
                        tr.find('#price').val(precio.toFixed(2));
                        tr.find('#id_iva').val(data[0]['iva']);
                        tr.find('#iva').val(data[0]['porcentajeIva']+"%");
                        let subtotal = precio;
                        tr.find('#subtotal').val(subtotal);
                        
                        var select = $(objeto);
                        var currentRow = select.closest('tr');
                        var nextRow = currentRow.next();
                        // Mueve el foco a la siguiente fila
                        if (nextRow.length > 0) {
                            nextDatalist = nextRow.find('#items');
                            nextDatalist.focus();
                        } 
                    }
                });
            }
            var filasConDatos = leerFilas();
            calcular(filasConDatos);

        }else{
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

    function changeDescription(objeto, items) {
        let descripcion = $(objeto).val();
        function isMatch(item) {
            return item.item_name === descripcion;
        }
        descripcion = items.find(isMatch);
        let tr = $(objeto).parent().parent();
        if(descripcion){
            let url = "/operations/item/description";    
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                async: false,
                data:{"_token": "{{ csrf_token() }}", descripcion: descripcion.item_name },
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(data){ 
                    //console.log(data) 
                    tr.find('#items').val(data[0]['bar_code']);
                    tr.find('#description').val(data[0]['item_name']);
                    tr.find('#cantidad').val("1");
                    tr.find('#pvp0_neto').val(data[0]['pvp1_neto']);
                    tr.find('#pvp1_neto').val(data[0]['pvp1_neto']);
                    tr.find('#pvp1').val(data[0]['pvp1']);
                    tr.find('#cantidad2').val(data[0]['cantidad2']);
                    tr.find('#pvp2_neto').val(data[0]['pvp2_neto']);
                    tr.find('#pvp2').val(data[0]['pvp2']);
                    tr.find('#cantidad3').val(data[0]['cantidad3']);
                    tr.find('#pvp3_neto').val(data[0]['pvp3_neto']);
                    tr.find('#pvp3').val(data[0]['pvp3']);
                    tr.find('#cantidad4').val(data[0]['cantidad4']);
                    tr.find('#pvp4_neto').val(data[0]['pvp4_neto']);
                    tr.find('#pvp4').val(data[0]['pvp4']);
                    tr.find('#id_iva').val(data[0]['iva']);
                    tr.find('#iva').val(data[0]['porcentajeIva']+"%");
                    
                    let precio_neto =  (parseFloat(data[0]['pvp1_neto'])).toFixed(2)  
                    let subtotal = precio_neto ;
                    tr.find('#subtotal').val(subtotal);
                    
                    var select = $(objeto);
                    var currentRow = select.closest('tr');
                    var nextRow = currentRow.next();
                    // Mueve el foco a la siguiente fila
                    if (nextRow.length > 0) {
                        nextDatalist = nextRow.find('#items');
                        nextDatalist.focus();
                    } 
                }
            });

            var filasConDatos = leerFilas();
            calcular(filasConDatos);
        }else{
            Swal.fire({
                title: "Error",
                text: "Por favor seleccione un producto existente",
                icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    tr.find('input').val('');
                    tr.find('td').eq(2).find('input').focus();
                } 
            });
        }
    }


    function changePrice(objeto) {
        let tr = $(objeto).parent().parent();
        let price = parseFloat($(objeto).val()) * 1;
        let qty = parseFloat(tr.find('#cantidad').val()) * 1;
        let subtotal = 0;
        if(qty && price){
            subtotal = qty * price;
            tr.find('#subtotal').val(subtotal.toFixed(2));
            var filasConDatos = leerFilas();
            calcular(filasConDatos);
        }
        else{
            tr.find('#subtotal').val("0.00");
            var filasConDatos = leerFilas();
            calcular(filasConDatos);
        }
    }


    function calcular(filasConDatos) {
        var codigoCantidadSuma = {};
        var sumaSubtotal = 0;  
        var sumaBaseIva = 0;
        var sumaBaseCero = 0;
        var sumaTotal = 0;

        for (var codigo in filasConDatos) {
            if (filasConDatos.hasOwnProperty(codigo)) {
                var subArray = filasConDatos[codigo];
                for (var i = 0; i < subArray.length; i++) {
                    var subElemento = subArray[i];

                    var total = parseFloat(($(subElemento[0]).find('#subtotal')).val());
                    var cantidad = parseInt(($(subElemento[0]).find('#cantidad')).val());
                    var precio_neto = parseFloat(($(subElemento[0]).find('#price')).val());
                    var porcentajeIva = ($(subElemento[0]).find('#iva')).val()
                    

                    if (porcentajeIva !== "0%") {
                        var baseIva = parseFloat(cantidad*precio_neto).toFixed(2);
                        sumaBaseIva += parseFloat(baseIva);
                    }else{
                        var baseCero = parseFloat(cantidad*precio_neto).toFixed(2);
                        sumaBaseCero += parseFloat(baseCero);
                    } 
                    var subtotal = parseFloat(cantidad*precio_neto).toFixed(2);
                    sumaSubtotal += parseFloat(subtotal);

                    sumaTotal += parseFloat(total);

                }
            }
        }

        $('#sumaSub').val(sumaSubtotal.toFixed(2));
        $('#baseIva').val(sumaBaseIva.toFixed(2));
        $('#baseCero').val(sumaBaseCero.toFixed(2));
        $('#total').val(sumaTotal.toFixed(2));
        $('#siIva').val((sumaTotal-sumaSubtotal).toFixed(2));

        //Modal
        $('#apagar').val(sumaTotal.toFixed(2));
    }


    function guardar() {
        const number = $("#number").val();        
        
        if (number) {
            Swal.fire({
                title: 'Guardar Factura',
                text: 'Si la información es correcta, haga clic en "Sí" para enviar el formulario.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formulario').submit();
                }
            });
        }else{
            Swal.fire({
                title: 'Error',
                text: 'No se puede guardar la factura sin un número de documento.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
        
    }

    function cerrarmodal() {
        $('#savemodal').modal('toggle');
    }

    function buscarPorRuc(object){

        let ruc = object.value;
        if(ruc){
            let url = "/bills/buscarVendedor/" + ruc;        
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(data){
                    if (data.length !== 0) {
                        $('#proveedor').val(data[0]['vendedor'])
                        $('#direccion').val(data[0]['direccion'])
                        $('#id_proveedor').val(data[0]['id'])
                    } else {
                        $('#proveedor').val('')
                        $('#direccion').val('')
                        $('#id_proveedor').val('')
                    }
                }
            });            

            var nombreABuscar = "Factura";
            var tabla = $("#modal-tabla tbody tr");
            tabla.removeClass("table-active");

            tabla.filter(function() {
                selectDoc(this);
                return $(this).find("td:eq(1)").text() === nombreABuscar;
                }).addClass("table-active");
        }
    }

    function selectDoc(object){
        //console.log(object);
        $('tr').removeClass('table-active');
        $(object).addClass('table-active');

        let idtipoDoc = ($(object).find('#id_TipoDoc')).html();
        let tipoDoc = ($(object).find('#tipoDoc')).html();
        let numeros = ($(object).find('#serie')).html();
        let secuencial = ($(object).find('#secuencial')).html();
        let serie = numeros.split("-")[0];
        let establecimiento = numeros.split("-")[1];
        $('#serieNumero').val(serie);
        $('#estableNumero').val(establecimiento);
        $('#id_tipo_doc').val(idtipoDoc);
        
        let numero = secuencial.toString().padStart(9, '0');
        $('#secuencialNumero').val(numero);
        

    }

    function calculoSaldo(){
        
        let apagar = parseFloat($('#apagar').val());
        let abono1 = parseFloat($('#abono1').val()) || 0.00;
        let abono2 = parseFloat($('#abono2').val()) || 0.00;

        let totalAbonos = abono1 + abono2; 

        if (abono1 > apagar) {
            showError("El Abono no puede ser mayor al valor TOTAL", '#abono1');
            abono1 = apagar;
            abono2 = 0.00;
        } else if (abono2 > apagar - abono1) {
            showError("El Abono 2 no puede ser mayor al valor TOTAL", '#abono2');
            abono2 = apagar - abono1;
        } else if (totalAbonos > apagar) {
            showError("La Suma de los Abonos no puede ser mayor al valor TOTAL", '#saldo');
            abono2 = apagar - abono1;
        }

        let saldo = apagar - abono1 - abono2;
        $('#abono1').val(abono1.toFixed(2));
        $('#abono2').val(abono2.toFixed(2));
        $('#saldo').val(saldo.toFixed(2));

        // if (apagar != abono1) {
        //     $('#abono2').val(parseFloat(apagar-abono1).toFixed(2));
        //     $('#saldo').val(0.00);
        // }  

        function showError(message, element) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: message,
            });
            $(element).val(0);
        }
    }

    function changeFormaPago(){
        let formaPago1 = $('#formaPago1').val();
        let formaPago2 = $('#formaPago2').val();

        if (formaPago1 === "2") {
            $("#numTransfer1").removeAttr('hidden');
            $("#banco1").removeAttr('hidden');
        }else{
            $("#numTransfer1").attr('hidden', true);
            $("#banco1").attr('hidden', true);
        }

        if (formaPago2 === "2") {
            $("#numTransfer2").removeAttr('hidden');
            $("#banco2").removeAttr('hidden');
        }else{
            $("#numTransfer2").attr('hidden', true);
            $("#banco2").attr('hidden', true);
        }

    }

    function addRow() {
        let url = "/elements/order/row";
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            async: 'false',
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#tb_items').append(data);
                $('#tb_items').children().last().find('input[type="text"]').first().focus();
            }
        });
    }

    function salir() {
        Swal.fire({
            title: 'Do you want to exit the form?',
            showDenyButton: true,
            confirmButtonText: 'Exit',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/invoices";
                }
            });
    }


</script>
@stop