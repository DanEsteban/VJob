@extends('adminlte::page')

@section('title', 'Estimate')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Estimate Center</h2>
        </div>

        <div class="col-md-2 mt-3">
                <a class="btn btn-outline-danger" style="width:170px;" href="/orders/create"><i class="fa fa-plus"></i> New Estimate</a>
        </div>
    </div>
</div>
@stop

@section('content')
    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif

    <div class="card card-body">
        <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
            <thead class="table-dark">
                <tr>
                    <th width="15%">Date</th>
                    <th width="15%">Number</th>
                    <th>Customer</th>
                    <th width="15%">Total</th>
                    <th>Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->date}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->number}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->id_customer}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->total}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">
                            @if ($order->status == 'Pending')
                                <span class="badge badge-danger">Pending</span>
                            @else
                                <span class="badge badge-success">Complete</span>
                            @endif                   
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" style="width: 300px">
                                    <li role="button"><a class="dropdown-item" onclick="aprobar('{{$order->status}}', {{$order->id}});"><i class="fa-regular fa-square-check"></i> Approve Estimate</a></li>
                                    <hr>
                                    <li role="button"><a class="dropdown-item" href="/estimate/pdf/{{$order->id}}"><i class="fa-solid fa-eye"></i> View</a></li>
                                    <li role="button" hidden><a class="dropdown-item" href="#"><i class="fa-regular fa-paper-plane"></i> Send</a></li>
                                    <li role="button"><a class="dropdown-item" href="/elements/estimate/pdf/{{$order->id}}"><i class="fa-solid fa-print"></i> Print</a></li>
                                    <li role="button"><a class="dropdown-item" href="/orders/{{$order->id}}/edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                    <li role="button"><a class="dropdown-item" onclick="eliminar({{$order->id}}, '{{$order->status}}');"><i class="fa-solid fa-trash-can"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#dTable').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            },
            "order": [[0, "desc"]]
        });
    });

    function editar(id) {
        window.location = "/orders/"+id+"/edit";
    }

    function eliminar(id, status) {
        if(status == 'Pending'){
            Swal.fire({
            title: 'Do you want to delete the order?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancelar`,
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "/operations/order/delete/"+id,
                        data:{},
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                        success: function (data) {
                            Swal.fire('Deleted!', '', 'success')
                            location.reload();
                        }
                    });               
                }
            })
        }
        else{
            Swal.fire(
            'Information',
            'Cannot delete this estimate',
            'warning'
            )
        }
    }

    function aprobar(status, id) {
        if(status == "Pending"){
            Swal.fire({
                title: 'Do you want to approve and convert it into an invoice?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Cancel`,
                }).then((result) => {
  
                if (result.isConfirmed) {
                    window.location.href = "/invoices/approved/" + id;
                }
            })
        }
        else{
            Swal.fire({
                title: 'Are you sure you want to regenerate the invoice and the process?',
                showDenyButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: `Cancel`,
                }).then((result) => {
  
                if (result.isConfirmed) {
                    window.location.href = "/invoices/approved/" + id;
                }
            })
        }
    }
</script>
@stop