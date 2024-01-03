@extends('adminlte::page')

@section('title', 'Report')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white  mt-3">
            <h2>Sales Report.</h2>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    use App\Models\Customers;

    # Devuelve 0 para domingo, 6 para sabado
    $primerdia=date("Y-m-d",mktime(0,0,0,date('n'),1,date('Y')));
    # Obtenemos el ultimo dia del mes
    $ultimodia=date("Y-m-d",(mktime(0,0,0,date('n')+1,1,date('Y'))-1));
@endphp
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">From:</label>
                    <input id="from" type="date" class="form-control form-control-sm" value="{{$primerdia}}">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">to:</label>
                    <input id="to" type="date" class="form-control form-control-sm" value="{{$ultimodia}}">
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
            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Document #</th>
                        <th>Customer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $total = 0;
                        @endphp
                    @foreach ($invoices as $invoice)
                        @php
                            $customer = Customers::where('id', $invoice->id_customer)->value('company_name');
                            $total += $invoice->total;
                        @endphp
                        <tr>
                            <td>{{$invoice->date}}</td>
                            <td>{{$invoice->number}}</td>
                            <td>{{$customer}}</td>
                            <td>{{$invoice->total}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot id="tb_footer">
                    <tr>
                        <td colspan="3"><b>Sales Total</b></td>
                        <td id="tb_total"><b>{{number_format($total, 2)}}</b></td>
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
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
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
        let from = $('#from').val();
        let to = $('#to').val();

        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/reports/sales/find',
            async:false,
            data:{
                "_token": "{{ csrf_token() }}",
                from:from,
                to:to
            },
            error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            success : function(data){
                var table = $('#dTable').DataTable();
                data.forEach(element => {
                    table.row.add([element['date'], element['number'], element['id_customer'], element['total']]).draw(); 
                }); 
                let total = table.column(3).data().sum();
                $('#tb_footer #tb_total').text(total.toFixed(2));
            }
        });
    }
</script>
@stop
