@extends('adminlte::page') 

@section('title', 'Movements Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Movements Center</h2>
        </div>

        <div class="col-md-2 mt-3">
            <a class="btn btn-outline-danger" style="width:170px;" href="/movements/create"><i class="fa fa-plus"></i> New Movement</a>
    </div>
    </div>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-header text-white bg-danger">
            <h5 class="card-title">Discharges</h5>
        </div>
        <div class="card-body">
            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead class="bg-dark">
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Total</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach ($expenditures as $exp)
                        <tr>
                            <td role="button">{{$exp->date}}</td>
                            <td role="button">{{$exp->number}}</td>
                            <td role="button">{{$exp->total}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" style="width: 300px">
                                    <li role="button"><a class="dropdown-item" href="/elements/movement/pdf/{{$exp->id}}-D"><i class="fa-solid fa-print"></i> Print</a></li> 
                                    <form action="/movements/{{$exp->id}}-D" method="POST"> 
                                        @csrf
                                        @method('DELETE')
                                        <li><a type="button" class="dropdown-item" onclick="eliminar(this);"><i class="fa-solid fa-trash-can"></i> Delete</a></li>
                                    </form>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header text-white bg-danger">
            <h5 class="card-title">Incomes</h5>
        </div>
        <div class="card-body">
            <table id="iTable2" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead class="bg-dark">
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Total</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach ($incomes as $inc)
                        <tr>
                            <td role="button">{{$inc->date}}</td>
                            <td role="button">{{$inc->number}}</td>
                            <td role="button">{{$inc->total}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" style="width: 300px">
                                    <li role="button"><a class="dropdown-item" href="/elements/movement/pdf/{{$inc->id}}-I"><i class="fa-solid fa-print"></i> Print</a></li>
                                    <form action="/movements/{{$inc->id}}-I" method="POST">  
                                        @csrf
                                        @method('DELETE')
                                        <li><a type="button" class="dropdown-item" onclick="eliminar(this);"><i class="fa-solid fa-trash-can"></i> Delete</a></li>
                                    </form>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
            "order": [[0, "desc"], [1, "desc"]]
        });
    });

    $(document).ready(function () {
        $('#iTable2').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            },
            "order": [[0, "desc"], [1, "desc"]]
        });
    });

    function eliminar(objeto) {
        let form = $(objeto).parent().parent();
        Swal.fire({
        title: 'Do you want to delete this invoice?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                $(form).submit();
            }
        })
    }
</script>
@stop