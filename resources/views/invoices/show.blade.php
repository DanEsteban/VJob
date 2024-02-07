@extends('adminlte::page')

@section('title', 'Show Invoice')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)


@section('content_header')

@stop

@section('content')

<?php
    use Carbon\Carbon;
        
    date_default_timezone_set('America/Guayaquil');

    $fechaActual = $cabeceraInv['date'];
    //$fechaActual = 2024-01-31
    $fechaFormateada = Carbon::createFromFormat('Y-m-d', $fechaActual)->locale('es_ES')->isoFormat('dddd, D [de] MMMM [de] YYYY');

    const porc_iva = 0.12;
?>

    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <h1>{{$datosEmp['emp_nombre']}}</h1>
                        <p>{{$datosEmp['emp_ruc']}}</p>
                        <p>{{$datosEmp['emp_dir']}}</p>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <h1 class="text-rigth">Logo Empresa</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                            <h4 class="invoice-color mb-2 mt-md-2">Factura #<?php echo $cabeceraInv['number']; ?></h4>
                            <div class="text-right">
                                <label for="fecha">Fecha:</label>
                                <span>{{$fechaFormateada}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <p><b>Nombre:</b> {{$cabeceraInv['name']}} </p>
                        <p><b>RUC:</b> {{$cabeceraInv['numero_ident']}} </p>
                        <p><b>Dirección:</b> {{$cabeceraInv['direccion']}}</p>                         
                    </div>
                    <div class="col-md-5">
                        <p><b>Correo electrónico:</b> {{$cabeceraInv['email']}}</p>
                        <p><b>Teléfono:</b> {{$cabeceraInv['phone']}}</p>                     
                    </div>
                    <div id="botones" class="col-md-3">
                        <div class="d-flex justify-content-end">
                            <a href="/invoices/create" class="btn btn-sm btn-outline-primary mr-2">
                                <i class="fas fa-file"></i> Nuevo
                            </a>
                            <button onclick="imprimir();" type="button" class="btn btn-sm btn-outline-primary mr-2">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <button onclick="salir();" type="button" class="btn btn-sm btn-outline-success mr-2">
                                <i class="fas fa-door-open"></i> Salir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dTable" class="table table-bordered">
                    <thead class="bg-dark">
                        <th style="width: auto;">Código</th>
                        <th style="width: auto;">Descripción</th>    
                        <th style="width: auto;">Cant.Unid.</th>
                        <th style="width: auto;">Pre.neto</th>        
                        <th style="width: auto;">IVA</th>
                        <th style="width: auto;">Subtotal</th>         
                    </thead>
                    <tbody>
                        <?php foreach ($baseProductsInv as $item) : ?>
                            <tr>
                                <td>
                                    <input autocomplete="off" id="items" value="{{$item['id']}}" type="text" autocomplete="off" class="form-control form-control-sm" width="300px">
                                </td>
                                <td>
                                    <input autocomplete="off" id="description" value="{{$item['item_name']}}" type="text" autocomplete="off" class="form-control form-control-sm" width="300px">
                                </td>
                                <td><input type="text" onchange="changeQty(this);" id="cantidad" value="{{$item['qty']}}" class="form-control form-control-sm"></td>
                                <td><input type="text" id="pvp0_neto" value="{{number_format($item['precio_neto'], 2)}}" class="form-control form-control-sm" readonly></td>  
                                
                                
                                <td><input type="text" id="iva" value="{{$item['iva']}}%" class="form-control form-control-sm" readonly></td>
                                
                                <td><input type="text" id="subtotal" value="{{number_format($item['pvp'], 2)}}" class="form-control form-control-sm" readonly></td>
                            </tr>
                        <?php endforeach; ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <strong>Subtotal:</strong>
                            </div>
                            <div class="col-md-6 col-sm-12 text-end">
                                <input class="border-0 col-sm-12 text-right font-weight-bold" id="sumaSub" name="sumaSub" type="text" value="{{$cabeceraInv['subtotal']}}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <strong>Base 0%:</strong>
                            </div>
                            <div class="col-md-6 col-sm-12 text-end">
                                <input class="border-0 col-sm-12 text-right font-weight-bold" id="baseCero" name="baseCero" type="text" value="{{$cabeceraInv['base0']}}" readonly>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <strong>Base IVA:</strong>
                            </div>
                            <div class="col-md-6 col-sm-12 text-end">
                            <input class="border-0 col-sm-12 text-right font-weight-bold" id="baseIva" name="baseIva" type="text" value="{{$cabeceraInv['base_iva']}}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <strong>I.V.A:</strong>
                            </div>
                            <div class="col-md-6 col-sm-12 text-end">
                                <input class="border-0 col-sm-12 text-right font-weight-bold" id="siIva" name="siIva" type="text" value="{{$cabeceraInv['taxes']}}" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <strong>Total:</strong>
                            </div>
                            <div class="col-md-6 text-end">
                                <input class="border-0 col-sm-12 text-right font-weight-bold" id="total" name="total" type="text" value="{{$cabeceraInv['total']}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop


@section('css')
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>

        .btn {
            height: 60px;
            padding: 10px
        }
        #PVP1,#PVP2,#PVP3, #PVP4 {
            color: #ffff;
            font-weight: 700;
            font-size: 19px;
        }
        #dTable td input{
            text-align: right;
        }

        #sumaSub, #total{
            color: red;
        }
        #total{
            font-size: 25px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group select{
            height: 1.8rem;
            width: 7rem;
            font-size: 0.8rem;
        }

        .form-control{
            height: 1.8rem;
        }

        @media print {
            #botones {
                display: none;
            }
        }
    </style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('#dTable').DataTable({
            searching: false, 
            paging: false,    
           // scrollY: '300px', 
            scrollCollapse: true, 
            responsive: true,
            info: false 
        });

        $('#dTable tbody').on('click', 'tr', function () {
                
            let tr = $(this);
            let cantidad1 = "1";
            let cantidad2 = tr.find('#cantidad2').val();
            let cantidad3 = tr.find('#cantidad3').val();
            let cantidad4 = tr.find('#cantidad4').val();
            let pvp1_neto = parseFloat(tr.find('#pvp1_neto').val());
            let pvp2_neto = parseFloat(tr.find('#pvp2_neto').val());
            let pvp3_neto = parseFloat(tr.find('#pvp3_neto').val());
            let pvp4_neto = parseFloat(tr.find('#pvp4_neto').val());

            if (pvp1_neto) {
                pvp1_neto = pvp1_neto.toFixed(2);
                var parrafo1 = $("<p></p>");
                $('#PVP1').addClass('bg-primary').empty();
                var parrafo1 = $("<p>Desde "+ cantidad1  + "<br>$" + pvp1_neto + "</p>");
                $('#PVP1').append(parrafo1);
            }else{
                $("#PVP1").empty();
                $("#PVP1").removeClass();
            }

            if (pvp2_neto) {
                pvp2_neto = pvp2_neto.toFixed(2);
                var parrafo2 = $("<p></p>");
                $('#PVP2').addClass('bg-success').empty();
                var parrafo2 = $("<p>Desde " + cantidad2 + "<br>$" + pvp2_neto + "</p>");
                $('#PVP2').append(parrafo2);
            }else{

                $("#PVP2").empty();
                $("#PVP2").removeClass();
                $("#PVP3").empty();
                $("#PVP3").removeClass();
                $("#PVP4").empty();
                $("#PVP4").removeClass();
            }

            if (pvp3_neto) {
                pvp3_neto = pvp3_neto.toFixed(2);
                var parrafo3 = $("<p></p>");
                $('#PVP3').addClass('bg-danger').empty();
                var parrafo3 = $("<p>Desde " + cantidad3 + "<br>$" + pvp3_neto + "</p>");
                $('#PVP3').append(parrafo3);
            }else{
                $("#PVP3").empty();
                $("#PVP3").removeClass();
                $("#PVP4").empty();
                $("#PVP4").removeClass();
            }

            if (pvp4_neto) {
                pvp4_neto = pvp4_neto.toFixed(2);
                var parrafo4 = $("<p></p>");
                $('#PVP4').addClass('bg-warning').empty();
                var parrafo4 = $("<p>Desde " + cantidad4 + "<br>$" + pvp4_neto + "</p>");
                $('#PVP4').append(parrafo4);
            }else{
                $("#PVP4").empty();
                $("#PVP4").removeClass();
            }
        });

    });

    function imprimir(){
        window.print()
    }

    function changeQty(objeto) {
        var filasConDatos = leerFilas();
        numeroRegistros(filasConDatos);
        calcular(filasConDatos);
    }

    var codigoCantidadSuma = {};
    var sumaSubtotal = 0;  
    var sumaBaseIva = 0;
    var sumaBaseCero = 0;
    var sumaTotal = 0;

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

    function cambioPrecio(filasDuplicadas, indice, precio, precio_iva, num_precio){
        var subarray = filasDuplicadas[indice];
        subarray.forEach((subarreglo)  => {            
            var cantidad = parseFloat(($(subarreglo[0]).find('#tdC1 input')).val())
            var subtotal_final = parseFloat(cantidad*precio_iva).toFixed(2);
            ($(subarreglo[0]).find('#tdP0 input')).val(precio.toFixed(5));
            ($(subarreglo[0]).find('#subtotal')).val(subtotal_final);
            ($(subarreglo[0]).find('#num_precio')).val(num_precio);
            
        });


    }

    function numeroRegistros(filasConDatos) {
        for (var codigo in filasConDatos) {
            if (filasConDatos.hasOwnProperty(codigo)) {
                var subArray = filasConDatos[codigo];
                for (var i = 0; i < subArray.length; i++) {
                    
                    var subElemento = subArray[i];
                    var cantidad1 = parseInt(($(subElemento[0]).find('#tdC1 input')).val());
                    var cantidad2 = parseInt(($(subElemento[0]).find('#tdC2 input')).val());
                    var cantidad3 = parseInt(($(subElemento[0]).find('#tdC3 input')).val());
                    var cantidad4 = parseInt(($(subElemento[0]).find('#tdC4 input')).val());
                    
                    var precio1 = parseFloat(($(subElemento[0]).find('#tdP1 input')).val());
                    var precio2 = parseFloat(($(subElemento[0]).find('#tdP2 input')).val());
                    var precio3 = parseFloat(($(subElemento[0]).find('#tdP3 input')).val());
                    var precio4 = parseFloat(($(subElemento[0]).find('#tdP4 input')).val());

                    var precio_iva1 = parseFloat(($(subElemento[0]).find('#pvp1')).val());
                    var precio_iva2 = parseFloat(($(subElemento[0]).find('#pvp2')).val());
                    var precio_iva3 = parseFloat(($(subElemento[0]).find('#pvp3')).val());
                    var precio_iva4 = parseFloat(($(subElemento[0]).find('#pvp4')).val());

                    if (codigoCantidadSuma[codigo]) {
                    codigoCantidadSuma[codigo].cantidad += cantidad1;
                    } else {
                        codigoCantidadSuma[codigo] = { cantidad: cantidad1 };
                    }

                    if (codigoCantidadSuma[codigo].cantidad >= cantidad1 && (cantidad2 ? codigoCantidadSuma[codigo].cantidad < cantidad2 : true)) {
                        var subtotal = parseFloat(cantidad1*precio1).toFixed(5);
                        sumaSubtotal += parseFloat(subtotal);
                        var num_precio = 1;
                        cambioPrecio(filasConDatos, codigo, precio1, precio_iva1, num_precio);
                        calcular(sumaSubtotal)

                    } 
                    else if (codigoCantidadSuma[codigo].cantidad >= cantidad2 && (cantidad3 ? codigoCantidadSuma[codigo].cantidad < cantidad3 : true)) {
                        var subtotal = parseFloat(cantidad1*precio2).toFixed(5);
                        sumaSubtotal += parseFloat(subtotal);
                        var num_precio = 2;
                        cambioPrecio(filasConDatos, codigo, precio2, precio_iva2, num_precio);
                        calcular(sumaSubtotal)
                        
                    } 
                    else if(codigoCantidadSuma[codigo].cantidad >= cantidad3 && (cantidad4 ? codigoCantidadSuma[codigo].cantidad < cantidad4 : true)) {
                        var subtotal = parseFloat(cantidad1*precio3).toFixed(5);
                        sumaSubtotal += parseFloat(subtotal);
                        var num_precio = 3;
                        cambioPrecio(filasConDatos, codigo, precio3, precio_iva3, num_precio);
                        calcular(sumaSubtotal)
                        
                    }
                    else if(codigoCantidadSuma[codigo].cantidad >= cantidad4 ){
                        var subtotal = parseFloat(cantidad1*precio4).toFixed(5);
                        sumaSubtotal += parseFloat(subtotal);
                        var num_precio = 4;
                        cambioPrecio(filasConDatos, codigo, precio4, precio_iva4, num_precio);
                        calcular(sumaSubtotal) 

                    }
                    //console.log("Elemento " + codigo + ":", cantidad1);
                }
            }
        }
        //console.log(codigoCantidadSuma);
        sumaSubtotal = 0;
        sumaBaseIva = 0;
        sumaBaseCero = 0;
        sumaTotal = 0;
        codigoCantidadSuma = {};

    }

    function calcular(filasConDatos) {

        for (var codigo in filasConDatos) {
            if (filasConDatos.hasOwnProperty(codigo)) {
                var subArray = filasConDatos[codigo];
                for (var i = 0; i < subArray.length; i++) {
                    var subElemento = subArray[i];

                    var total = parseFloat(($(subElemento[0]).find('#subtotal')).val());
                    var cantidad = parseInt(($(subElemento[0]).find('#tdC1 input')).val());
                    var precio_neto = parseFloat(($(subElemento[0]).find('#tdP0 input')).val());
                    var checkbox = $(subElemento[0]).find('#iva');
                    if (checkbox.prop("checked")) {
                        var baseIva = parseFloat(cantidad*precio_neto).toFixed(5);
                        sumaBaseIva += parseFloat(baseIva);
                    }else{
                        var baseCero = parseFloat(cantidad*precio_neto).toFixed(5);
                        sumaBaseCero += parseFloat(baseCero);
                    } 
                    var subtotal = parseFloat(cantidad*precio_neto).toFixed(5);
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

    function salir() {
        Swal.fire({
            title: 'Quiere salir del formulario??',
            showDenyButton: true,
            confirmButtonText: 'Salir',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/invoices";
                }
            });
    }

</script>
@stop