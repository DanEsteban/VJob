@extends('adminlte::page')

@section('title', 'Warehouse')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>Warehouses</h2>
            </div>
            <div class="col-md-2 mt-3">
                <a type="button" class="btn btn-success" style="width:180px;" data-toggle="modal" data-target="#warehouseModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Create a new Warehouse"><i class="fa fa-plus"></i> New Warehouse</a>
            </div>
            <!-- Modal for new warehouse -->
                <div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="warehouseModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="warehouseModalLabel">Flowerist</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <form action="/warehouses" method="POST" id="warehouseForm">
                                @csrf
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Name</label>
                                    <input type="text" autocomplete="off" class="form-control" id="warehouse_name" name="warehouse_name" placeholder="Type here..." required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div> 
            <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->   
        </div>
    </div>
@stop

@section('content')
    <?php

        use App\Models\Warehouses;
        $warehouse = Warehouses::where('is_active', 1)->get();
        
    if(isset($_GET["setActive"])){
        $whouse = Warehouses::find($_GET['is_id']);
        $whouse->is_active = $_GET['is_active'];
        $whouse->save();
    exit();
}
    ?>
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
                    <th width="2%"></th>
                    <th hidden scope="col">Id</th>   
                    <th scope="col">Warehouse</th>
                    <th width="10%" scope="col">Active</th>
                    <th width="20%" scope="col">Actions</th>
                </thead>
                <tbody>
                    @foreach($warehouse as $wh)
                        <tr>
                            <td></td>
                            <td hidden>{{$wh->id}}</td>
                            <td>{{$wh->wh_name}}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active[]" onchange="Checked({{$wh->id}});" value="active" <?php if($wh->is_active) echo "checked"; ?> >
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" style="width: 300px">
                                        <li><a class="dropdown-item editbtn" data-toggle="modal" data-target="#editModal" data-id="{{$wh->id}}" data-name="{{$wh->wh_name}}"><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                        
                                        <li><a class="dropdown-item deletebtn" data-toggle="modal" data-target="#deleteModal" data-id="{{$wh->id}}"><i class="fa fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal Edit -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="popModalTitle">Flowerist</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="edit_id" id="edit_id">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Name</label>
                                    <input type="text" autocomplete="off" class="form-control" id="warehouse_name" name="warehouse_name" placeholder="Type here..." required>
                                </div>

                                <div class="modal-footer">                            
                                    <button type="submit" name="editrep" class="btn btn-primary">Edit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>                           
                                </div>
                            </form>
                        </div>
        
                    </div>
                </div>
            </div>
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->

        <!-- Modal Delete -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="popModalTitle">Flowerist</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to delete this user?</h4>
                        </div>
                        <div class="modal-footer">
                            <form id="delForm" action="" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" name="deleteuser" class="btn btn-primary">Delete</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->           
        
       
    </div>

@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop
@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

    function Checked(id) {
        $(document).on('change', '#is_active', function() {
            var active = 0;
            if(this.checked) {              
                active = 1;
            }
            $.ajax({
                type : "GET",
                data : {setActive:1, is_id:id, is_active:active},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    },
                success : function(data){
                    console.log(data);
                }
            })
        });
    }
    $(document).ready(function(){
        $('.deletebtn').on('click', function(){
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#delForm').attr("action", "warehouses" + "/" + id);
            })
        });
    });
    
    $(document).ready(function(){
        $('.editbtn').on('click', function(){
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                $('#editForm').attr("action", "warehouses" + "/" + id);
            })
        });
    });


</script>
@stop