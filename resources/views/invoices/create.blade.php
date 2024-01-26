@extends('adminlte::page')

@section('title', 'Create Invoice')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)


@section('content_header')

@stop

@section('content')

<?php
    use Carbon\Carbon;
        
    $locale = App::getLocale();

    setlocale(LC_TIME, $locale);
    $fechaLarga = Carbon::now()->locale($locale)->isoFormat('dddd, DD [de] MMMM [de] YYYY');
    const porc_iva = 0.12;
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
                        <p>{{$datosEmp['emp_tel']}}</p>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                            <h4 class="invoice-color mb-2 mt-md-2">Invoice #<?php echo str_pad($numFact['number'], 9, "0", STR_PAD_LEFT); ?></h4>
                            <div class="text-right">
                                <label for="fecha">Fecha:</label>
                                <span><?php echo $fechaLarga; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="formulario" action="{{route('invoices.store')}}" method="POST"> 
        @csrf
        <input type="text" name="emp_nombre" value="{{$datosEmp['emp_nombre']}}" hidden>
        <input type="text" name="emp_ruc" value="{{$datosEmp['emp_ruc']}}" hidden>
        <input type="text" name="emp_dir" value="{{$datosEmp['emp_dir']}}" hidden>
        <input type="text" name="number" value="<?php echo str_pad($numFact['number'], 9, "0", STR_PAD_LEFT); ?>" hidden>
        <div class="card">
            <div class="card-body">
                <table id="dTable" class="table table-bordered table-responsive" style="width: 100%; max-height: 329px;">
                    <thead class="bg-dark sticky-header">
                        <th width="2%"></th>
                        <th style="width: 90px;">Código</th>
                        <th style="width: 300px;">Descripción</th>    
                        <th style="width: 10px;">Cant.Unid.</th>
                        <th style="width: 20px;" id="thP0">Pre.neto</th>        
                        <th hidden style="width: 20px;" id="thP1">Pre.neto</th>                       
                        <th hidden>PrecioIva</th>
                        <th hidden>Cantidad2</th>
                        <th style="width: 20px;" id="thP2" hidden>Pre.neto</th>
                        <th hidden>PrecioIva2</th>  
                        <th hidden>Cantidad3</th>
                        <th style="width: 20px;" id="thP3" hidden>Pre.neto</th>
                        <th hidden>PrecioIva3</th>
                        <th hidden>Cantidad4</th>
                        <th style="width: 20px;" id="thP4" hidden>Pre.neto</th>
                        <th hidden>PrecioIva4</th>
                        <th style="width: 1px;">IVA</th>
                        <th style="width: 20px;">Subtotal</th>
                        <th hidden>Num.Precio</th>           
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 50; $i++) : ?>
                            <tr>
                                <td>
                                    <button onclick="vaciarRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </td>
                                <td>
                                    <input onchange="changeItem(this);" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['id']}}"></option> 
                                        @endforeach
                                    </datalist>
                                </td>
                                <td>
                                    <input onchange="changeDescription(this)" id="description" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemDesList">
                                    <datalist id="itemDesList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['item_name']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td id="tdC1"><input type="text" onchange="changeQty(this);" id="cantidad" name="cantidad[]" class="form-control form-control-sm"></td>
                                <td id="tdP0"><input type="text" id="pvp0_neto" name="pvp0_neto[]" class="form-control form-control-sm" readonly></td>  
                                <td hidden id="tdP1"><input type="text" id="pvp1_neto" class="form-control form-control-sm" readonly></td>                   
                                <td hidden><input hidden type="text" id="pvp1"  class="form-control form-control-sm"></td>
                                <td id="tdC2" hidden><input hidden type="text" id="cantidad2" class="form-control form-control-sm"></td>
                                <td id="tdP2" hidden><input hidden type="text" id="pvp2_neto" class="form-control form-control-sm"></td>
                                <td hidden><input hidden type="text" id="pvp2" class="form-control form-control-sm"></td>
                                <td id="tdC3" hidden><input hidden type="text" id="cantidad3" class="form-control form-control-sm"></td>
                                <td id="tdP3" hidden><input hidden type="text" id="pvp3_neto" class="form-control form-control-sm"></td>
                                <td hidden><input hidden type="text" id="pvp3"  class="form-control form-control-sm"></td>
                                <td id="tdC4" hidden><input hidden type="text" id="cantidad4" class="form-control form-control-sm"></td>
                                <td id="tdP4" hidden><input hidden type="text" id="pvp4_neto" class="form-control form-control-sm"></td>
                                <td hidden><input hidden type="text" id="pvp4" class="form-control form-control-sm"></td>
                                <td><input type="checkbox" id="iva" name="iva[]" disabled></td>
                                <td><input type="text" id="subtotal" name="subtotal[]" class="form-control form-control-sm" readonly></td>
                                <td hidden><input type="text" id="num_precio" name="num_precio[]" class="form-control form-control-sm" readonly></td> 
                            </tr>
                        <?php endfor; ?>    
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="p-3 mb-3" id="PVP1" style="text-align:center;">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 mb-3" id="PVP2" style="text-align:center;">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 mb-3" id="PVP3" style="text-align:center;">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 mb-3" id="PVP4" style="text-align:center;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body botPrincipales">
                        <button onclick="seleccion();" type="button" class="btn btn-sm btn-outline-success mr-2">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary mr-2">
                            <i class="fa-solid fa-print"></i> Imprimir
                        </button>
                        <button onclick="salir();" type="button" class="btn btn-sm btn-outline-success mr-2">
                            <i class="fa-solid fa-door-open"></i> Salir
                        </button>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-3">
                                <label for="canalVenta">Canal Venta:</label>
                            </div>
                            <div class="col-9">
                                <input autocomplete="off" onchange="changeDescription(this)" id="canalVenta" type="text" class="form-control form-control-sm" width="300px" list="canalVtaList">
                                <datalist id="canalVtaList">
                                    <option value="--SELECCIONAR--"></option>
                                </datalist>
                            </div>
                            
                        </div>
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
        <!-- Modal -->
            <div class="modal fade" id="savemodal" tabindex="-1" role="dialog" aria-labelledby="savemodal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-5 shadow" style="width:700px;">
                        <div class="modal-header bg-primary" style="color: #fff;">
                            <h5 class="modal-title" id="popModalTitle">ANTES DE GRABAR, REVISE LA INFORMACIÓN</h5>
                            <button onclick="cerrarmodal();" type="button" class="close custom-close-button" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="ruc" class="col-sm-3 col-form-label">Identificación:</label>
                                <div class="col-sm-3">
                                    <input onchange="buscarPorRuc(this);" type="text" class="form-control" id="ruc" name="ruc" value="9999999999999" required autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cliente" class="col-sm-3 col-form-label">Cliente:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="cliente" name="cliente" value="CONSUMIDOR FINAL (SOLO CONTADOS)" required>
                                    <input type="text" id="id_cliente" name="id_cliente" value="1" hidden>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="direccion" class="col-sm-3 col-form-label">Dirección:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="SIN DIRECCIÓN" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for= "telefono" class="col-sm-3 col-form-label">Teléfono:</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="999999999" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email:</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="vendedor" class="col-sm-3 col-form-label">Vendedor</label>
                                <div class="col-sm-3">
                                    <select id="vendedor" id="vendedor" name="vendedor" type="text" class="form-control form-control-sm" width="300px">
                                        <option value="0">--Seleccione--</option>
                                        @foreach ($vendors as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr class="my-4">
                            <table id="modal-tabla" class="table table-xl" style="max-height: 350px;">
                                <thead class="table-dark">
                                    <tr>
                                        <th hidden></th>
                                        <th>Tipo Documento</th>
                                        <th>Serie-Establ.</th>
                                        <th>Secuencial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seriesFact as $key => $serie)
                                        <tr onclick="selectDoc(this);" class="{{ $key === 0 ? 'table-active' : '' }}">
                                            <td id="id_TipoDoc" hidden>{{$serie['tipo_documento']}}</td>
                                            <td id="tipoDoc">{{$serie['nombre']}}</td>
                                            <td id="serie">{{$serie['punto_emision']}}-{{$serie['establecimiento']}}</td>
                                            <td id="secuencial">{{$serie['secuencial']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-2">Numero:</label>
                                <input type="text" id="id_tipo_doc" name="id_tipo_doc" value="0" hidden>
                                <?php foreach ($seriesFact as $elemento) { ?>
                                    <div class="col-sm-2">
                                        <input id="serieNumero" name="serieNumero" type="text" class="form-control" value="{{$elemento['punto_emision']}}">
                                    </div>
                                    <div class="col-sm-2">
                                        <input id="estableNumero" name="estableNumero" type="text" class="form-control" value="{{$elemento['establecimiento']}}">
                                    </div>
                                    <div class="col-sm-5">
                                        <input id="secuencialNumero" name="secuencialNumero" type="text" class="form-control" value="<?php echo str_pad($elemento['secuencial'], 9, '0', STR_PAD_LEFT); ?>">
                                    </div>
                                    
                                <?php break; } ?>
                            </div>
                            <hr class="my-4">
                            <div class="my-3">        
                                <div class="form-group row modalGroup">
                                    <label for="apagar" class="col-sm-2 col-form-label text-sm-left">A pagar:</label>
                                    <div class="col-sm-2">
                                        <input type="apagar" class="form-control text-sm-left" id="apagar" name="apagar" value="0.00" readonly>
                                    </div>
                                </div>
                                <div class="form-group row modalGroup">
                                    <label for="abono1" class="col-sm-2 col-form-label text-sm-left">Abono:</label>
                                    <div class="col-sm-2">
                                        <input onkeyup="calculoSaldo();" type="text" class="form-control text-sm-left" id="abono1" name="abono1" value="0.00" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <select onchange="changeFormaPago();" id="formaPago1" name="formaPago1" type="text" class="form-control">
                                            <option value="0">--Seleccione--</option>
                                            @foreach ($payment_terms as $item)
                                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 ntransfer">
                                        <input type="text" class="form-control" id="numTransfer1" name="numTransfer1" placeholder="Nro. Transferencia" hidden>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="banco1" name="banco1" placeholder="Banco" hidden>
                                    </div>
                                </div>
                                
                                <div class="form-group row modalGroup">
                                    <label for="abono2" class="col-sm-2 col-form-label text-sm-left">Abono:</label>
                                    <div class="col-sm-2">
                                        <input onkeyup="calculoSaldo();" type="text" class="form-control" id="abono2" name="abono2" value="0.00" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <select onchange="changeFormaPago();" id="formaPago2" name="formaPago2" type="text" class="form-control">
                                            <option value="0">--Seleccione--</option>
                                            @foreach ($payment_terms as $item)
                                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 ntransfer">
                                        <input type="text" class="form-control" id="numTransfer2" name="numTransfer2" placeholder="Nro. Transferencia" hidden>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="banco2" name="banco2" placeholder="Banco" hidden>
                                    </div>
                                </div>

                                <div class="form-group row modalGroup">
                                    <label for="saldo" class="col-sm-2 col-form-label text-sm-left">Saldo:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control text-sm-left" id="saldo" name="saldo" value="0.00">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary" type="submit">Grabar</button>
                                    <button onclick="cerrarmodal();" class="btn btn-secondary ml-2" type="button">Corregir</button>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->      

        </div>
    </form>

@stop


@section('css')
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>

        @media screen and (min-width: 950px) {
            .custom-paragraph {
                font-size: 20px;
            }
        }

        @media screen and (min-width: 720px) and (max-width: 949px) {
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
    
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1;
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
                var parrafo1 = $("<p></p>").addClass("custom-paragraph");
                $('#PVP1').addClass('bg-primary shadow').empty();
                parrafo1.html("Desde " + cantidad1 + "<br>$" + pvp1_neto);
                $('#PVP1').append(parrafo1);
            } else {
                $("#PVP1").empty();
                $("#PVP1").removeClass("bg-primary shadow");
            }

            if (pvp2_neto) {
                pvp2_neto = pvp2_neto.toFixed(2);
                var parrafo2 = $("<p></p>").addClass("custom-paragraph");
                $('#PVP2').addClass('bg-secondary shadow').empty();
                parrafo2.html("Desde " + cantidad2 + "<br>$" + pvp2_neto);
                $('#PVP2').append(parrafo2);
            } else {
                $("#PVP2").empty();
                $("#PVP2").removeClass("bg-secondary shadow");
                $("#PVP3").empty();
                $("#PVP3").removeClass("bg-success shadow");
                $("#PVP4").empty();
                $("#PVP4").removeClass("bg-secondary shadow");
            }

            if (pvp3_neto) {
                pvp3_neto = pvp3_neto.toFixed(2);
                var parrafo3 = $("<p></p>").addClass("custom-paragraph");
                $('#PVP3').addClass('bg-success shadow').empty();
                parrafo3.html("Desde " + cantidad3 + "<br>$" + pvp3_neto);
                $('#PVP3').append(parrafo3);
            } else {
                $("#PVP3").empty();
                $("#PVP3").removeClass("bg-success shadow");
                $("#PVP4").empty();
                $("#PVP4").removeClass("bg-danger shadow");
            }

            if (pvp4_neto) {
                pvp4_neto = pvp4_neto.toFixed(2);
                var parrafo4 = $("<p></p>").addClass("custom-paragraph");
                $('#PVP4').addClass('bg-danger shadow').empty();
                parrafo4.html("Desde " + cantidad4 + "<br>$" + pvp4_neto);
                $('#PVP4').append(parrafo4);
            } else {
                $("#PVP4").empty();
                $("#PVP4").removeClass("bg-danger shadow");
            }
        });

        $('form').on('keydown', 'input, select, textarea', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                mostrarAlerta();
            }
        });

        $('form').submit(function (event) {
            event.preventDefault();
            mostrarAlerta();
        });
        
    });

    function mostrarAlerta() {
        
        Swal.fire({
            title: "¿Quiere guardar los cambios?",
            text: "Usted no será capaz de revertir esto!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#dc3545",
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Se ha guardado la Factura!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
                $('form').unbind('submit').submit();
            }
        });      
    }

    function vaciarRow(objeto) {
        let fila = $(objeto).parent().parent();
        const primerInput = $(fila).find('td input').first();
        Swal.fire({
        title: 'Do you want to delete this invoice?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                $(fila).find('td input').each(function() {
                    $(this).val('');
                });

                $("#PVP1").empty();
                $("#PVP1").removeClass();
                $("#PVP2").empty();
                $("#PVP2").removeClass();
                $("#PVP3").empty();
                $("#PVP3").removeClass();
                $("#PVP4").empty();
                $("#PVP4").removeClass();
                primerInput.focus();
            }
        })
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

    function changeItem(objeto) {
        let code = $(objeto).val();
        let tr = $(objeto).parent().parent();
        if(code){
            let url = "/operations/item/code/" + code;        
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
                    tr.find('#items').val(data[0]['id']);
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
                    if(data[0]['iva'] === 1){
                        tr.find('#iva').attr("checked", "checked");
                    }
                    let porcentage = 12/100;                   
                    let precio_neto =  parseFloat(data[0]['pvp1_neto'])  
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
            numeroRegistros(filasConDatos);
            calcular(filasConDatos);
        }
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

    function changeDescription(objeto) {
        let descripcion = $(objeto).val();
        let tr = $(objeto).parent().parent();
        if(descripcion){
            let url = "/operations/item/description";    
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                async: false,
                data:{"_token": "{{ csrf_token() }}", descripcion: descripcion },
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(data){  
                    tr.find('#items').val(data[0]['id']);
                    tr.find('#cantidad').val("1");
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
                    if(data[0]['iva'] === 1){
                        tr.find('#iva').attr("checked", "checked");
                    }
                    let porcentage = 12/100;                   
                    let precio_neto =  parseFloat(data[0]['pvp1_neto'])  
                    let subtotal = precio_neto  * 1;
                    tr.find('#subtotal').val(subtotal); 
                }
            });
        }
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

    function seleccion() {
        let apagar = parseFloat($('#apagar').val());  
        if(apagar !== 0.00){
            $('#abono1').prop('readonly', false);
            $('#abono2').prop('readonly', false);
            $('#abono1').val(apagar);
            $('#formaPago1').val('1');
            $('#formaPago2').val('1');

        }
        $('#savemodal').modal('show');
    }

    function cerrarmodal() {
        $('#savemodal').modal('toggle');
    }

    function buscarPorRuc(object){

        let ruc = object.value;
        
        if(ruc){
            let url = "/invoices/buscarCliente/" + ruc;        
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
                    console.log(data.length);
                    if (data.length !== 0) {
                        $('#cliente').val(data[0]['cliente'])
                        $('#direccion').val(data[0]['direccion'])
                        $('#telefono').val(data[0]['telefono'])
                        $('#email').val(data[0]['email'])
                        $('#id_cliente').val(data[0]['id'])
                    } else {
                        console.log(data);
                        $('#cliente').val('')
                        $('#direccion').val('')
                        $('#telefono').val('')
                        $('#email').val('')
                        $('#id_cliente').val('')
                    }
                }
            });    
        }

    }

    function selectDoc(object){

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
        let abono1 = isNaN(parseFloat($('#abono1').val())) ? 0.00 : parseFloat($('#abono1').val());
        let abono2 = isNaN(parseFloat($('#abono2').val())) ? 0.00 : parseFloat($('#abono2').val());

        let totalAbonos = abono1 + abono2; 
        if (abono1 > apagar) {
            showError("El Abono no puede ser mayor al valor TOTAL", '#abono1');
        } else if (abono2 > apagar) {
            showError("El Abono 2 no puede ser mayor al valor TOTAL", '#abono2');
        } else if (totalAbonos > apagar) {
            showError("La Suma de los Abonos no puede ser mayor al valor TOTAL", '#saldo');
        } else {
            let resulFinal = apagar - abono1 - abono2;
            $('#saldo').val(resulFinal.toFixed(2));
        }

        if (apagar != abono1) {
            $('#abono2').val(parseFloat(apagar-abono1).toFixed(2));
            $('#saldo').val(0.00);
        }  

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


    function modulo11(numero) {
        let suma = 0;
        let peso = 2;

        // Recorre el número de derecha a izquierda
        for (let i = numero.length - 1; i >= 0; i--) {
            suma += parseInt(numero.charAt(i)) * peso;
            peso++;

            if (peso > 7) {
            peso = 2;
            }
        }

        const digitoVerificador = 11 - (suma % 11);
        return digitoVerificador === 10 ? 1 : digitoVerificador;
    }

</script>
@stop