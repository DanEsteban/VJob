
@extends('adminlte::page')

@section('title', 'Cierre de Caja')

@section('content_header')

@stop

@section('content')
<!DOCTYPE html>
<html lang="en">
<body>

    
    <div hidden id="cashier-data" data-invoices="{{ json_encode($invoices) }}" data-inicio="{{ $inicioDelDia }}"  
        data-total-cash="{{ $totalCash }}" data-total-transfer="{{ $totalTransfer }}" data-total-payment="{{ $totalPayment }}">
    </div> 

    <br>
    <div class="row">
        <div class="col-md-3">
            <div class="input-group">
                <label class="col-sm-auto col-form-label form-control-sm">Fecha:</label>
                &nbsp;
                <input id="fecha" name="desde" type="date" class="form-control form-control-sm" value="{{$inicioDelDia}}" required>
                <button type="button" onclick="filtrar(this);" class="btn btn-sm btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
    </div>
    <br>

    <div id="contenidoImprimir" class="card">
        <div class="container">
            <div class="text-center">
                <hr class="asterisk-line">
                <h1><strong>Cierre de Caja</strong></h1>
                <hr class="asterisk-line">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><em>Fecha: <b id="fecha_invoices">{{$inicioDelDia}}</b></em></p>
                        {{-- <p><em>Receipt #: 34522677W</em></p> --}}
                    </div>
                </div>

                <div class="ventas-table">
                    <div class="text-left">
                        <h2><strong>Ventas del Día</strong></h2>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>TD</th>
                                <th>Documento</th>
                                <th>Sub</th>
                                <th>IVA</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tb_invoices" >      
                            <?php
                                foreach ($invoices as $item){
                            ?> 
                                <tr>
                                    <?php
                                        if ($item['tipo_documento'] === 0) {
                                    ?>
                                            <td>NV</td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                            <td>FC</td>
                                    <?php
                                        }
                                    ?>
                                    
                                    <td>{{$lastEightDigits = substr($item['num_doc_sri'] , -8)}}</td>
                                    <td>{{$item['subtotal']}}</td>
                                    <td>{{$item['taxes']}}</td>
                                    <td>${{$item['total']}}</td>
                                </tr>                                        
                            <?php
                                }
                            ?>        
                            
                        </tbody>
                        <tfoot id="tf_invoices">
                            <tr>
                            <?php
                                $subtotal_foot = 0;
                                $taxes_foot = 0;
                                $total_foot = 0;
                                foreach ($invoices as $item){
                                    $subtotal_foot += $item['subtotal']; 
                                    $taxes_foot += $item['taxes'];
                                    $total_foot += $item['total'];
                                }
                            ?>
                                <td></td>
                                <td></td>
                                <td>
                                    <p><strong>{{$subtotal_foot}}</strong></p>
                                </td>
                                <td>
                                    <p><strong>{{$taxes_foot}}</strong></p>
                                </td>
                                <td>
                                    <p><strong>${{$total_foot}}</strong></p>
                                </td> 
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="cobranza-table">
                    <div class="text-left">
                        <h2><strong>Cobranza del Día</strong></h2>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Formas de Pago</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody id="tb_cobranzas">
                            <tr class="trT">
                                <td class="tdT nombre">Efectivo</td>
                                <td class="tdT total">${{$totalCash}}</td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Transferencia</td>
                                <td class="tdT total">${{$totalTransfer}}</td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Credito</td>
                                <td class="tdT total">${{$total_foot - $totalPayment}}</td>
                            </tr>
                        </tbody>
                        <tfoot id="tf_cobranzas">
                            <tr>
                                <td></td>
                                <td>
                                    <p><strong>${{$total_foot}}</strong></p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- <div class="cobranzaCredit-table">
                    <div class="text-left">
                        <h2><strong>Cobranza de Créditos</strong></h2>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Formas de Pago</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody id="tb_cobranzas">
                            <tr class="trT">
                                <td class="tdT nombre">Cash</td>
                                <td class="tdT total">${{$totalCash}}</td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Check</td>
                                <td class="tdT total"></td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Unpayment</td>
                                <td class="tdT total"></td>
                            </tr>
                        </tbody>
                        <tfoot id="tf_cobranzas">
                            <tr>
                                <td></td>
                                <td>
                                    <p><strong>${{$totalPayment}}</strong></p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div> --}}
            </div>
        </div>
    </div>

    <button id="impirmirInicio" onclick="imprimirPagina();">Imprimir</button>
    <button hidden id="impirmirFiltrar" onclick="imprimirPagina();">Imprimir</button>    


</body>
</html>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<style>          
    .card {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
    }
    .text-center {
        text-align: center;
    }
    .asterisk-line {
        border: none;
        border-top: 1px dashed #ccc;
        margin: 20px 0;
    }
    h1, h2 {
        font-weight: bold;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        padding: 5px;
        border: 1px solid #ccc;
    }
    thead {
        background-color: #f2f2f2;
    }
    tfoot {
        font-weight: bold;
    }
    .nombre {
        width: 70%;
    }
    .total {
        width: 30%;
    }

</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

    function filtrar(objeto) {

        $('#impirmirInicio').hide();
        $('#imprimirFiltrar').removeAttr('hidden');

        let fecha = $(objeto).parent().parent().find('#fecha').val();

        $('#tb_invoices tr').remove();
        $('#tf_invoices tr').remove();
        $('#tb_cobranzas tr').remove();
        $('#tf_cobranzas tr').remove();

        $('#fecha').val(fecha);

        $.ajax ({
            type: "GET",
            dataType: "json",
            url: "/operations/cashier/" + fecha,
            async: false,
            data: {},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(any){
                console.log(any);

                $('#fecha_invoices').text(fecha);
                var totalTransfer = 0;
                var totalCash = 0;
                var subtotal = 0;
                var taxes = 0;
                var total = 0;
                var lastEightDigits;

                $.each(any[0]['invoices'], function(index, value) {                                
                    var fila = "<tr>";
                    if (value.tipo_documento === 0) {
                        fila += "<td>" + 'NV' + "</td>"; 
                    }else{
                        fila += "<td>" + 'FC' + "</td>";
                    }
                    
                    fila += "<td>" + ((value.num_doc_sri).substring(value.num_doc_sri.length - 8)) + "</td>";
                    fila += "<td>" + (value.subtotal) + "</td>";
                    fila += "<td>" + (value.taxes) + "</td>";
                    fila += "<td>" + (value.total) + "</td>";
                    fila += "</tr>";
                    $("#tb_invoices").append(fila);

                    subtotal+= (parseFloat(value.subtotal));
                    taxes+= (parseFloat(value.taxes));
                    total+= (parseFloat(value.total));
                    totalCash+= (parseFloat(value.totalCash));
                    totalTransfer+= (parseFloat(value.totalTransfer));

                });  
                
                var foot = "<tr>";
                foot += "<td>" + "" + "</td>";
                foot += "<td>" + "" + "</td>";
                foot += "<td>" + subtotal + "</td>";
                foot += "<td>" + taxes + "</td>";
                foot += "<td>" + total + "</td>";
                foot += "</tr>";
                $("#tf_invoices").append(foot);
                
                var payFila1 = "<tr>";
                var payFila2 = "<tr>";
                var payFila3 = "<tr>";
                var payFila4 = "<tr>";

                payFila1 += "<td>" + "Efectivo" + "</td>";
                payFila1 += "<td>" + any[0].totalCash + "</td>";
                payFila1 += "</tr>";
                $("#tb_cobranzas").append(payFila1);

                payFila2 += "<td>" + "Transferencia" + "</td>";
                payFila2 += "<td>" + any[0].totalTransfer + "</td>";
                payFila2 += "</tr>";
                $("#tb_cobranzas").append(payFila2);

                payFila3 += "<td>" + "Credito" + "</td>";
                payFila3 += "<td>" + (total - any[0].totalPayment) + "</td>";
                payFila3 += "</tr>";
                $("#tb_cobranzas").append(payFila3);

                
                payFila4 += "<td>" + "" + "</td>";
                payFila4 += "<td>" + total + "</td>";
                payFila4 += "</tr>";
                $("#tf_cobranzas").append(payFila4);
            }
        });

    }


    function imprimirPagina() {
        var invoices = @json($invoices);
        var inicioDelDia = @json($inicioDelDia);
        var totalCash = @json($totalCash);
        var totalTransfer = @json($totalTransfer);
        var totalPayment = @json($totalPayment);

        var primerTD  = $('#tb_invoices tr:first td:first');

        if (primerTD.text().trim() === '') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No hay ningun campo en la tabla para mandar a imprimir! ",
            });
        } else {

            $.ajax({
                type:'POST',
                url: "imprimir-ticket",
                dataType: 'json',
                async: "false",
                data: {"_token": "{{ csrf_token() }}",  invoices: invoices,
                inicioDelDia: inicioDelDia, totalCash: totalCash, totalTransfer: totalTransfer,
                totalPayment: totalPayment},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    console.log(data);
                }
            });
        }
    }
</script>
@stop