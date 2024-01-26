
@extends('adminlte::page')

@section('title', 'Cierre de Caja')

@section('content_header')

@stop

@section('content')
<!DOCTYPE html>
<html lang="en">
<body>
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
                        @php
                            $fechaFormateada = date("d/m/Y", strtotime($inicioDelDia));
                        @endphp

                        <p><em>Fecha: <b id="fecha_invoices">{{$fechaFormateada}}</b></em></p>
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
                            @foreach ($invoices as $item)
                                <tr>
                                    <td>{{ $item['tipo_documento'] === 0 ? 'NV' : 'FC' }}</td>
                                    <td>{{ substr($item['num_doc_sri'], -8) }}</td>
                                    <td>${{ $item['subtotal'] }}</td>
                                    <td>${{ $item['taxes'] }}</td>
                                    <td>${{ $item['total'] }}</td>
                                </tr>
                            @endforeach       
                            
                        </tbody>
                        <tfoot id="tf_invoices">
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <p><strong>${{$subtotal_foot = number_format(array_sum(array_column($invoices, 'subtotal')), 2) }}</strong></p>
                                </td>
                                <td>
                                    <p><strong>${{$taxes_foot = number_format(array_sum(array_column($invoices, 'taxes')), 2) }}</strong></p>
                                </td>
                                <td>
                                    <p><strong>${{$total_foot = number_format(array_sum(array_column($invoices, 'total')), 2) }}</strong></p>
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
                                <td class="tdT total">${{ $totalCash }}</td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Transferencia</td>
                                <td class="tdT total">${{ $totalTransfer }}</td>
                            </tr>
                            <tr class="trT">
                                <td class="tdT nombre">Credito</td>
                                @php
                                    $credito = $total_foot - $totalPayment;
                                @endphp
                                <td class="tdT total">${{number_format($credito, 2) }}</td>
                            </tr>
                        </tbody>

                        <tfoot id="tf_cobranzas">
                            <tr>
                                <td>Total Pago</td>
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

    function formatDate(dateString) {
        // Convertir la cadena de fecha a un objeto de fecha
        var date = new Date(dateString);

        // Obtener los componentes de la fecha
        var day = date.getDate() + 1;
        var month = date.getMonth() + 1; 
        var year = date.getFullYear();

        // Agregar ceros iniciales si es necesario
        day = (day < 10) ? '0' + day : day;
        month = (month < 10) ? '0' + month : month;

        // Construir la cadena de fecha formateada
        var formattedDate = day + '/' + month + '/' + year;

        return formattedDate;
    }

    function filtrar(objeto) {

        let fecha = $(objeto).parent().parent().find('#fecha').val();
        $('#tb_invoices tr').remove();
        $('#tf_invoices tr').remove();
        $('#tb_cobranzas tr').remove();
        $('#tf_cobranzas tr').remove();

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

                var formattedDate = formatDate(fecha);
                $('#fecha_invoices').text(formattedDate);
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

                
                payFila4 += "<td>" + "Total Pago" + "</td>";
                payFila4 += "<td>" + total + "</td>";
                payFila4 += "</tr>";
                $("#tf_cobranzas").append(payFila4);
            }
        });

    }

    function imprimirPagina() {

        var fecha = $('#fecha').val();
        var titulosVentas = ["TD", "Documento", "Sub", "IVA", "Total"];
        var rowsVentas = $("#tb_invoices tr").map(function(rowIndex, row) {
            var rowObject = {};
            $(row).find("td").each(function(cellIndex, cell) {
                var name = titulosVentas[cellIndex];
                var value = $(cell).text();
                var valueFormateado = value.replace("$", "")
                rowObject[name] = valueFormateado;
            });
            return rowObject;
        }).get();

        var titulosCobranza = ["Formas de Pago", "Monto"];
        var rowsCobranzas = $("#tb_cobranzas tr").map(function(rowIndex, row) {
            var rowObject = {};
            $(row).find("td").each(function(cellIndex, cell) {
                var name = titulosCobranza[cellIndex];
                var value = $(cell).text();
                var valueFormateado = value.replace("$", "");
                rowObject[name] = valueFormateado;
            });
            return rowObject;
        }).get();

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
                data: {"_token": "{{ csrf_token() }}",  invoices: rowsVentas,
                fecha: fecha, cobranzas: rowsCobranzas},
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