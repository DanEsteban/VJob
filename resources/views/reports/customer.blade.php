@extends('adminlte::page')

@section('title', 'Report')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white  mt-3">
            <h2>Customer Balance.</h2>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    use App\Models\Customers;
@endphp
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">Customers:</label>
                    <input onchange="change();" id="customer" autocomplete="off" type="text" class="form-control form-control-sm" list="customersList">
                    <datalist id="customersList">
                        @foreach ($customers as $customer)
                            <option value="{{$customer->company_name}}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div id="fechaActual" class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">to:</label>
                    <input id="to" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}">
                </div>
            </div>
            <div class="col">
                <button onclick="find();" class="btn btn-sm btn-outline-primary">Refresh</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">     
                <div class="form-check">
                    <input onclick="actual(this);" class="form-check-input" type="radio" name="General" id="actual" checked>
                    <label class="form-check-label" for="actual">Actual</label>
                </div>
                <div class="form-check">
                    <input onclick="historial(this);" id="historico" class="form-check-input" type="radio" name="General">
                    <label class="form-check-label" for="historico">Historico</label>
                </div>
            </div>
            <div id="fechasFiltro" class="col-md-8">
                <div class="col-md-4">
                    <div class="input-group">
                        <label class="col-sm-5 col-form-label">Desde:</label>
                        &nbsp;&nbsp;<input type="date" id="start_month" name="start_month" class="form-control form-control-sm" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <label class="col-sm-5 col-form-label">Hasta:</label>
                        &nbsp;&nbsp;<input type="date" id="end_month" name="end_month" class="form-control form-control-sm" value="{{date('Y-m-d')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
<br>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th width="2%"></th>
                        <th>Date</th>
                        <th>Document #</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody id="tb_body">
                    
                </tbody>
                <tfoot id="tb_footer">
                    <tr>
                        <td colspan="5"><b>Balance Total</b></td>
                        <td id="tb_total">0.00</td>
                        <td id="tb_totalbalance">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@stop

@section('footer')
    <div class="ml-4 text-sm text-gray-500 sm:text-right sm:ml-0">
        <img src="../img/ISOTIPO.png" width="30px" alt="isotipo_logo"> Copyright © 2022-2024 Visual Job. All rights reserved.
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet"> 
    <style>
        .collapse-table {
    width: 100%; /* Ajusta el ancho de la tabla */
    }

    .collapse-table td {
    width: 25%; /* Ajusta el ancho de las celdas */
    }

    </style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.13.1/api/sum().js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#fechasFiltro").hide();
        /* $('#dTable').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            }
        }); */
    
    });

    function find() {
        let customer = $('#customer').val();
        let historico = $('#historico').is(':checked');
        $("#tb_body tr").remove();

        if (historico) 
        {
            let desde = $('#start_month').val();
            let hasta = $('#end_month').val();
            if (customer) {
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:'/reports/customer/find', 
                    async:false,
                    data:{
                        "_token": "{{ csrf_token() }}",
                        customer:customer,
                        desde:desde,
                        hasta:hasta
                    },
                    error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                    success : function(data){
                        console.log(data);
                        let uniqueData = [];
                        let i =0;
                        $.each(data, function(index, element){
                            
                            let existingElement = uniqueData.find(function(item) {
                                return item.number === element.number; 
                            });
                            
                            if (!existingElement) {

                                uniqueData.push(element);

                                const fila = $("<tr></tr>");

                                fila.append('<td id="td_button" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr' + index + '" aria-expanded="false" aria-controls="collapseTr0">' + '<i class="fa-solid fa-plus"></i>' + "</td>");
                                fila.append('<td>' + (element['date'] ?? '') + '</td>');
                                fila.append('<td>' + (element['number'] ?? '') + '</td>');
                                fila.append('<td>' + (element['customer'] ?? '') + '</td>');
                                fila.append('<td>' + (element['status'] ?? '') + '</td>');
                                fila.append('<td>' + (parseFloat(element['total']).toFixed(2) ?? '') + '</td>');
                                fila.append('<td>' + (parseFloat(element['balance']).toFixed(2) ?? '') + '</td>');

                                const collapse = $('<tr class="collapse" id="collapseTr' + index + '"></tr>');

                                const td = $('<td></td>').attr('colspan', '10');

                                const formaPagoTable = $('<table class="collapse-table"></table>');

                                $.each(element['formaPago'], function (key, value) {
                                const row = $('<tr></tr>');
                                row.append('<td>' + "Date" + ': ' + (value['paymentDate'] ?? '') + '</td>');
                                row.append('<td>' + "Type" + ': ' + (value['type'] ?? '') + '</td>');
                                row.append('<td>' + "Reference" + ': ' + (value['reference'] ?? '') + '</td>');
                                row.append('<td>' + "Amount" + ': ' + (parseFloat(value['amount']).toFixed(2) ?? '') + '</td>');
                                formaPagoTable.append(row);
                                });

                                td.append(formaPagoTable);
                                collapse.append(td);

                                $("#tb_body").append(fila, collapse);

                                
                            }
                            
                            
                        });

                        const celdasColumna = $("#tb_body td:nth-child(6)"); 
                        let suma = 0;

                        // Recorrer las celdas de la columna y sumar sus valores
                        celdasColumna.each(function() {
                        const valor = parseFloat($(this).text());
                        console.log(valor)
                            if (!isNaN(valor)) {
                                suma += valor;
                            }
                        });
                        $('#tb_total').html(suma.toFixed(2));
                        ///////
                        const celdasBalance = $("#tb_body td:nth-child(7)"); 
                        let sum = 0;
                        celdasBalance.each(function() {
                        const val = parseFloat($(this).text());
                        console.log(val)
                            if (!isNaN(val)) {
                                sum += val;
                            }
                        });
                        $('#tb_totalbalance').html(sum.toFixed(2));                          
                    }
                });
            } 
            else {
                var myTable = $('#dTable').DataTable();
                myTable.clear().draw();
                $('#tb_footer #tb_total').text("0.00");

                Swal.fire(
                    'Information',
                    'Please select a customer',
                    'info'
                )
            } 
        } 
        else {
            let to = $('#to').val();
            if (customer) {
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:'/reports/customer/find',
                    async:false,
                    data:{
                        "_token": "{{ csrf_token() }}",
                        customer:customer,
                        to:to
                    },
                    error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                    success : function(data){
                        console.log(data);

                        var uniqueData = [];
                        $.each(data, function(index, element){
                            
                            var existingElement = uniqueData.find(function(item) {
                                return item.number === element.number; 

                            });
                            
                            if (!existingElement) {

                                uniqueData.push(element);
                                const fila = $("<tr></tr>");
                            
                                //llenar la fila con el resto de elementos del data.
                                //fila.append('<td id="td_button" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr'+index+'" aria-expanded="false" aria-controls="collapseTr0">' + '<i class="fa-solid fa-plus"></i>' + "</td>");
                                fila.append('<td>' + " " + '</td>');
                                fila.append('<td>' + (element['date'] ?? '') + '</td>');
                                fila.append('<td>' + (element['number'] ?? '') + '</td>');
                                fila.append('<td>' + (element['customer'] ?? '') + '</td>');
                                fila.append('<td>' + (element['status'] ?? '') + '</td>');
                                fila.append('<td>' + (parseFloat(element['total']).toFixed(2) ?? '') + '</td>');
                                fila.append('<td>' + (parseFloat(element['balance']).toFixed(2) ?? '') + '</td>');

                                $("#tb_body").append(fila);
                            }
                            
                        });

                        const celdasColumna = $("#tb_body td:nth-child(6)"); 
                        let suma = 0;

                        // Recorrer las celdas de la columna y sumar sus valores
                        celdasColumna.each(function() {
                        const valor = parseFloat($(this).text());
                        console.log(valor)
                            if (!isNaN(valor)) {
                                suma += valor;
                            }
                        });
                        $('#tb_total').html(suma.toFixed(2));
                        ///////
                        const celdasBalance = $("#tb_body td:nth-child(7)"); 
                        let sum = 0;
                        celdasBalance.each(function() {
                        const val = parseFloat($(this).text());
                        console.log(val)
                            if (!isNaN(val)) {
                                sum += val;
                            }
                        });
                        $('#tb_totalbalance').html(sum.toFixed(2));   
            
                    }
                });
            } 
            else {
                var myTable = $('#dTable').DataTable();
                myTable.clear().draw();
                $('#tb_footer #tb_total').text("0.00");

                Swal.fire(
                    'Information',
                    'Please select a customer',
                    'info'
                )
            } 
        }
        
    }

    function change() {
        let customer = $('#customer').val();
        if (!customer) {
            var myTable = $('#dTable').DataTable();
            myTable.clear().draw();
            $('#tb_footer #tb_total').text("0.00");
        }
    }

    function historial(valor) {

        if (valor.checked) {
            $("#actual").removeAttr("checked");
            $("#fechaActual").hide();
            $("#fechasFiltro").show();
            $("#tb_body tr").remove();
            $('#tb_totalbalance').html("0.00");
            $('#tb_total').html("0.00");
        }
    }

    function actual(valor) {

        if (valor.checked) {
            $("#historico").removeAttr("checked"); 
            $("#fechasFiltro").hide();
            $("#fechaActual").show();
            $("#tb_body tr").remove();
            $('#tb_totalbalance').html("0.00");
            $('#tb_total').html("0.00");

        }
    }   
</script>
@stop
