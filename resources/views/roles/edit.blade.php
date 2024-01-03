@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <div class="card">
        <div class="card-body bg-white shadow">
            <h2><strong>Edit Role</strong></h2>
        </div>
    </div>
@stop

@section('content')
    <form action="/roles/{{$role->id}}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-header text-white bg-dark">
                <h6 class="card-title">Current Role Name</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <input name="nombre_rol" type="text" class="form-control form-control-sm" style="width:600px;" placeholder="Escribir aquí..." value="{{$role->name}}">
                    </div> 
                    
                    <div class="col-md-4">
                        <center>
                            <button type="submit" onclick="guardar();" class="btn btn-outline-primary btn-sm" style="width:100px;">Save</button>
                            <button id="btn_salir" type="button" onclick="salir();" class="btn btn-outline-danger btn-sm" style="width:100px;">Cancel</button>
                        </center>
                    </div>
                </div>      
            </div>
        </div>

        <div class="card">
            <div class="card-header text-white bg-dark">
                <h6 class="card-title">Permission List</h6>
            </div>

            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th hidden>Id</th>
                            <th>Descripción</th>
                            <th>Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permiso)
                            <tr>
                                <td hidden>{{$permiso->id}}</td>
                                @if (str_contains($permiso->description, "Create") || str_contains($permiso->description, "Show") || str_contains($permiso->description, "Destroy") || str_contains($permiso->description, "Edit") || str_contains($permiso->description, "Delete") || str_contains($permiso->description, "View") || str_contains($permiso->description, "Send") || str_contains($permiso->description, "Approve"))
                                    <td style="padding-left: 100px"><i class="fa-solid fa-circle" style="font-size: 8px"></i> {{$permiso->description}}</td>
                                @else
                                    <td>{{$permiso->description}}</td>
                                @endif
                                <td>
                                    @php
                                        $es_permitido = $role->hasPermissionTo($permiso);
                                    @endphp
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="permissions" name="permissions[]" value="{{$permiso->id}}" <?php if($es_permitido) echo "checked"; ?>>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div id="button-up">
        <i class="fa-solid fa-angles-up"></i>
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
<link href="/css/button.css" rel="stylesheet"> 
@stop

@section('js')
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/button.js"></script>
<script type="text/javascript">
     function salir(){
            Swal.fire({
                title: 'Do you want to exit the form?',
                showDenyButton:true,
                confirmButtonText: 'Exit',
                denyButtonText: 'Cancel',
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/roles";
                    } 
            });
        }
</script>
@stop