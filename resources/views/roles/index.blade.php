@extends('adminlte::page')

@section('title', 'User Role')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>User Role</h2>
            </div>

            <div class="col-md-2 mt-3">
                    <a class="btn btn-outline-danger" style="width:160px;" href="/roles/create"><i class="fa fa-plus"></i> New Role</a>
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
          <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Roles List</button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        @if($roles->count())
                            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 60%">
                                <thead class="bg-dark">
                                    <tr> 
                                        <th>Nombre</th> 
                                        <th width="15%">Actions</th>                      
                                    </tr>
                                </thead>
                                <tbody>                  
                                    @foreach($roles as $role)
                                    <tr>
                                        <td role="button" id="nombre">{{$role->name}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                  <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" style="width: 300px">
                                                    @can('roles.show')
                                                        <li><a class="dropdown-item" href="/roles/{{$role->id}}">View</a></li>
                                                    @endcan
                                                    @can('roles.edit')
                                                        <li><a class="dropdown-item" href="/roles/{{$role->id}}/edit">Edit</a></li>
                                                    @endcan
                                                    @can('roles.destroy')
                                                        <li><a class="dropdown-item" onclick="deleteRole({{$role->id}});">Delete</a></li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>  
                                    </tr>
                                    @endforeach             
                                </tbody>
                            </table>
                        @else
                            <div class="card-body">
                               <strong>Sin Registros</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
      </div>

     <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="popModalTitle">SafeImplant</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <h4>Quiere eliminar este Usuario?</h4>
            </div>
            <div class="modal-footer">
                <form id="delForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" name="deleteuser" class="btn btn-primary">Eliminar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="popModalTitle">SafeImplant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <input hidden type="text" name="rol_id" id="rol_id" value="">
                            <label for="recipient-name" class="col-form-label">Nombre</label>
                            <input type="text" autocomplete="off" class="form-control" id="rol_name" name="rol_name" placeholder="Escribir aquí..." required>
                        </div>

                        <div class="modal-footer">                            
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>        
                                <button type="submit" onclick="return validationPass();" name="edituser" class="btn btn-primary btn-sm">Guardar</button>    
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
     <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->
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
</script>
@stop