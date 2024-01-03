@extends('adminlte::page')

@section('title', 'Vendor Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Vendor Center</h2>
        </div>

        <div class="col-md-2 mt-3">
                <a class="btn btn-outline-danger" style="width:160px;" href="/vendors/create"><i class="fa fa-plus"></i> New Vendor</a>
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

@php
    use App\Models\VendorsUsers;
@endphp

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Vendor List</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="false">Transaction</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <!--- Table Customer --->
                    <div class="div_table">
                        <div class="row">
                                <div class="col">
                                    <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th hidden>Id</th>
                                                <th width="200px">Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Balance</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_filtro">
                                            @foreach ($vendors as $vendor)
                                                <tr>
                                                    <td id="id_vendor" hidden>{{$vendor->id}}</td>
                                                    <td role="button" class="align-middle">{{$vendor->name}}</td>
                                                    <td role="button" class="align-middle">{{$vendor->email}}</td>
                                                    <td role="button" class="align-middle">{{$vendor->phone}}</td>
                                                    <td role="button" class="align-middle">{{$vendor->balance}}</td>
                                                    <td class="align-middle">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="visually-hidden">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#">Bill</a></li>
                                                                <hr>
                                                                @if (VendorsUsers::where('id_vendor', $vendor->id)->exists())
                                                                <li><a class="dropdown-item" onclick="generate('{{$vendor->id}}', '{{$vendor->name}}');">Show Access</a></li>
                                                                @else
                                                                <li><a class="dropdown-item" onclick="generate('{{$vendor->id}}', '{{$vendor->name}}');">Generate Access</a></li>
                                                                @endif
                                                                <li><a class="dropdown-item" href="#">View</a></li>
                                                                <li><a class="dropdown-item" href="/vendors/{{$vendor->id}}/edit">Edit</a></li>
                                                                <li><a class="dropdown-item" onclick="eliminar({{$vendor->id}});">Delete</a></li>
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
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">

    </div>
  </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
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
            }
        });
    });

    function editar(id) {
        window.location = "/vendor/"+id+"/edit";
    }

    function eliminar(id) {
        Swal.fire({
            title: 'Do you want to delete the vendor?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: "/operations/vendor/delete/"+id,
                                data:{},
                                error: function (xhr, status, error) {
                                    console.log(xhr.responseText);
                                },
                                success: function (data) {
                                    Swal.fire('Saved!', '', 'success')
                                    location.reload();
                                }
                            });                
                }
            })
    }

    function generate(id, name) {
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/operations/vendor/generate/user',
            data:{
                "_token": "{{ csrf_token() }}",
                id:id,
                name:name
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success: function (any) {
                Swal.fire({
                    icon: 'info',
                    title:'Please copy it and share it with your vendor',
                    text: 'Usuario: ' + any['user'] + ' - Password: ' + any['password']
                });

            }
        })
    }

    function show(id, name) {
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/operations/vendor/generate/user',
            data:{
                "_token": "{{ csrf_token() }}",
                id:id,
                name:name
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success: function (any) {
                Swal.fire({
                    icon: 'info',
                    title:'Please copy it and share it with your supplier',
                    text: 'Usuario: ' + any['user'] + ' - Password: ' + any['password']
                });

            }
        })
    }
</script>
@stop