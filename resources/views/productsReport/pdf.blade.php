@extends('adminlte::page')

@section('title', 'Product List')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Product List</h2>
        </div>
    </div>
</div>
@stop

@section('content')
    @php
        use App\Models\Customers;
        use App\Models\Products;
    @endphp

    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif
    

    <div class="card">
        <div class="card-body">
            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead class="bg-dark">
                    <th>Code Bar</th>
                    <th>Description</th>
                    <th>QTY</th>
                    <th>Price</th>
                </thead>
                <tbody>
                    @foreach ($productsreport as $pr)
                        @php
                            $nombreItem = Products::where('id', $pr["id_item"])->value('item_name');
                            $description = Products::where('id', $pr["id_item"])->value('sales_description');
                            $price = Products::where('id', $pr["id_item"])->value('price');
                        @endphp
                        @if($nombreItem != "")
                            <tr>                          
                                <td>{{$nombreItem}}</td>
                                <td>{{$description}}</td>
                                <td>{{$pr["QTY"]}}</td>
                                <td>{{$price}}</td>
                            </tr>
                        @endif
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
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script type="text/javascript">
     $(document).ready(function () {
        $('#dTable').DataTable({
            dom:'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ],
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            }
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