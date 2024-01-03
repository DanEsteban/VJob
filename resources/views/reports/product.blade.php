@extends('adminlte::page')

@section('title', 'Report')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white  mt-3">
            <h2>Inventory Report.</h2>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    $year = date('Y');
    $from = date('Y-m-d', strtotime('01-01-'.$year));
@endphp
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-4 col-form-label form-control-sm">Product:</label>
                    <input id="product" type="text" class="form-control form-control-sm" list="productList">
                    <datalist id="productList">
                        @foreach ($items as $item)
                            <option value="{{$item->item_name}}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-4 col-form-label form-control-sm">From:</label>
                    <input id="from" type="date" class="form-control form-control-sm" value="{{$from}}">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-4 col-form-label form-control-sm">to:</label>
                    <input id="to" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}">
                </div>
            </div>
            <div class="col">
                <button onclick="find();" class="btn btn-sm btn-outline-primary">Refresh</button>
            </div>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="dTable" class="display nowrap table table-sm"  style="width: 100%">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Document #</th>
                        <th>Name</th>
                        <th>+ Qty</th>
                        <th>- Qty</th>
                    </tr>
                </thead>
                <tbody id="tb_body">

                </tbody>
                <tfoot id="tb_footer">
                    <tr>
                        <td colspan="5"><b>Balance Total</b></td>
                        <td id="tb_total"><b>0.00</b></td>
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
@stop

@section('js')
<script src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.13.1/api/sum().js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#dTable').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            }
        });
    });

    function find() {
        let product = $('#product').val();
        let from = $('#from').val();
        let to = $('#to').val();
        if (product) {
            $.ajax({
                type:'POST',
                dataType:'json',
                url:'/reports/product/find',
                async:false,
                data:{
                    "_token": "{{ csrf_token() }}",
                    product:product,
                    from:from,
                    to:to
                },
                error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                success : function(data){
                    let total = 0;
                    var myTable = $('#dTable').DataTable();
                    if(myTable.data().count()){
                        myTable.clear().draw();
                        $('#tb_footer #tb_total').text("0.00");
                    }

                    if (data.length > 0) {
                        var table = $('#dTable').DataTable();
                        data.forEach(element => {
                            if (element['type'] == "BL") {
                                total += parseInt(element['qty']);
                                table.row.add([element['type'], element['date'], element['Number'], element['name'], element['qty'], ""]).draw(); 
                            }
                            else{
                                total -= parseInt(element['qty']);
                                table.row.add([element['type'], element['date'], element['Number'], element['name'], "", element['qty']]).draw(); 
                            }
                        });

                        $('#tb_footer #tb_total').text(total.toFixed(2));   
                    }
                    else{
                        Swal.fire(
                            'Information',
                            'There are currently no records',
                            'info'
                        )
                    }
                }
            });
        } else {
            var myTable = $('#dTable').DataTable();
            myTable.clear().draw();
            $('#tb_footer #tb_total').text("0.00");

            Swal.fire(
                'Information',
                'Please select a product',
                'info'
            )
        }
    }
</script>
@stop
