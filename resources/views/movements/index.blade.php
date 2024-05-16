@extends('adminlte::page') 

@section('title', 'Movements Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow" style="min-height: 5rem;">
    <div class="row align-items-center">
        <div class="col-12 col-md-8 mt-3">
            <h2>Centro de Movimientos</h2>
        </div>

        <div class="col-12 col-md-4 mt-3 mb-3">
            {{-- <a onclick="newMovement();" class="btn btn-outline-danger" href="/movements/create"><i class="fa fa-plus"></i> Nuevo Movimiento</a> --}}
            <button type="button" class="btn btn-outline-danger" onclick="newMovement();" >
                <i class="fa fa-plus"></i> Nuevo Movimiento
            </button>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-header text-white bg-danger">
            <h5 class="card-title">Movimientos</h5>
        </div>     
        <div class="card-body">
            <div class="table-responsive">
                <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                    <thead class="bg-dark">
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Referencia</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        @foreach ($movements as $mov)
                            <tr>
                                <td role="button">{{ $mov['date']}} </td>
                                <td role="button">{{ $mov['tipo']}} </td>
                                <td role="button">{{ $mov['number']}} </td>
                                <td role="button">{{ $mov['total']}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" style="width: 300px">
                                            <li role="button"><a class="dropdown-item" href="/elements/movement/pdf/{{$mov['id']}}-D"><i class="fa-solid fa-print"></i> Print</a></li> 
                                            <form action="/movements/{{$mov['id']}}-D" method="POST"> 
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

    function newMovement(){

        let url = "/operations/product";
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: 'false',
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    console.log(data)
                    if(data){
                        window.location.href = '/movements/create';
                    }else{
                        Swal.fire({
                            title: "Resultado",
                            text: "No puede crear un movimiento, si no tiene un producto!",
                            icon: "error"
                        });
                    }
                    
                }
            });
    }

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