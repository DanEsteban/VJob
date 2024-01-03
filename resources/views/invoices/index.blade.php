@extends('adminlte::page')

@section('title', 'Invoice Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>Invoice Center</h2>
            </div>

            <div class="col-md-2 mt-3">
                    <a class="btn btn-outline-danger" style="width:160px;" href="/invoices/create"><i class="fa fa-plus"></i> Nueva Factura</a>
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
    <div class="card">
    <div class="card-body">
        <table id="dTable" class="display nowrap table table-sm" style="width: 100%">
            <thead class="bg-dark">
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($invoices as $invoice)

                    
                    <tr>
                        <td role="button">{{$invoice["date"]}}</td>
                        <td role="button">{{$invoice["number"]}}</td>
                        <td role="button">{{$invoice["customer"]}}</td>
                        <td role="button">{{$invoice["total"]}}</td>
                        <td>{{$invoice["status"]}}</td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">

                                    {{-- <li hidden><a class="dropdown-item disabled" href="/invoices/{{$invoice->id}}/edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li> --}}
                                    <li><a class="dropdown-item" href="/invoices/{{$invoice['id']}}"><i class="fa-solid fa-file-lines"></i> XML</a></li>
                                    <li><a class="dropdown-item" href="/invoices/{{$invoice['id']}}"><i class="fa-solid fa-print"></i> Print</a></li>
                                    <form action="/invoices/{{$invoice['id']}}" method="POST" id="invoiceForm">
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
    <style>
    </style>
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
            "order": [[0, "desc"]],
            searching: true, 
            paging: true, 
            info: false
        });
    });

    function leerFilas() {
        var table = $('#dTable').DataTable();
        var filas = table.rows().nodes();
        var filasConDatos = {};
        
        filas.each(function (rowNode, index) {
            
            var tr = $(rowNode);
            var celdas = tr.find('td');   
            var codigo = $(celdas[0].childNodes[1]).val();

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