@extends('adminlte::page')

@section('title', 'Customer Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Customer Center</h2>
        </div>

        <div class="col-md-2 mt-3">
                <a class="btn btn-outline-danger" style="width:160px;" href="/customers/create"><i class="fa fa-plus"></i> New Customer</a>
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

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Customer List</button>
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
                                    <div class="table-responsive">
                                        <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th hidden>Id</th>
                                                    <th width="200px">Name</th>
                                                    <th>FullName</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Balance</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tb_filtro">
                                                @foreach ($customers as $customer)
                                                    <tr onclick="">
                                                        <td id="id_customer" hidden>{{$customer->id}}</td>
                                                        <td role="button" class="align-middle">{{$customer->company_name}}</td>
                                                        <td role="button" class="align-middle" ondblclick="editar({{$customer->id}});">{{$customer->first_name}} {{$customer->midle_name}} {{$customer->last_name}}</td>
                                                        <td role="button" class="align-middle">{{$customer->email}}</td>
                                                        <td role="button" class="align-middle">{{$customer->phone}}</td>
                                                        <td role="button" class="align-middle">{{$customer->balance}}</td>
                                                        <td class="align-middle">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                                  <span class="visually-hidden">Toggle Dropdown</span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                  <li><a class="dropdown-item" href="/orders/create">Estimate</a></li>
                                                                  <li><a class="dropdown-item" href="#">Invoice</a></li>
                                                                  <hr>
                                                                  <li><a class="dropdown-item" href="/customers/{{$customer->id}}">View</a></li>
                                                                  <li><a class="dropdown-item" href="/customers/{{$customer->id}}/edit">Edit</a></li>
                                                                  <li><a class="dropdown-item" onclick="eliminar({{$customer->id}});">Delete</a></li>
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
    </div>
    <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">

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
            }
        });
    });

    function editar(id) {
      window.location = "/customers/"+id+"/edit";
    }

    function eliminar(id) {
        Swal.fire({
            title: 'Do you want to delete the customer?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancelar`,
            }).then((result) => {

                if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                dataType:"json",
                                url: "/operations/customer/delete/" + id,
                                data:{},
                                error: function (xhr, status, error) {
                                    console.log(xhr.responseText);
                                },
                                success: function (data) {
                                    if(data == true){
                                        Swal.fire('Deleted!', '', 'success')
                                        location.reload();
                                    }
                                    else{                          
                                        Swal.fire(
                                            'Information',
                                            'Cannot delete customer with records',
                                            'warning'
                                        )
                                    }
                                }
                            });
                
                }
            })
    }

    function seleccion(id) {
        $.ajax({
            type:'GET',
            dataType:'json',
            url:'',
            async:false,
            data:{},
            error: function (xhr, status, error) {
                    console.log(xhr.responseText);
            },
            success: function (data) {
                
            }
        });
    }
</script>
@stop