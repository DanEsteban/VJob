@extends('adminlte::page')

@section('title', 'Groups')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Groups</h2>
        </div>

        <div class="col-md-2 mt-3">
            <button onclick="newGroup();" class="btn btn-outline-danger" style="width:200px;"><i class="fa fa-plus"></i> New Group</button>
        </div>
    </div>
</div>
@stop

@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Group List</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 60%">
                            <thead class="table-dark">
                                <tr>
                                    <th hidden>Id</th>
                                    <th width="70%">Name</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tb_filtro">
                                @foreach ($groups as $group)
                                    <tr>
                                        <td hidden>{{$group->id}}</td>
                                        <td role="button">{{$group->name}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                  <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" style="width: 300px">
                                                  <li><a class="dropdown-item" onclick="editGroup({{$group->id}}, '{{$group->name}}');">Edit</a></li>
                                                  <li><a class="dropdown-item" onclick="deleteGroup({{$group->id}});">Delete</a></li>
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

<!-- Modals -->
<div class="modal fade" id="createGroup" tabindex="-1" role="dialog" aria-labelledby="createGroupLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Group</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                         
                    <div class="row g-3">
                        <div class="col">
                            <label for="group_name" class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="group_name" name="group_name" type="text" value="">
                            </div>
                        </div>
                    </div>    
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="saveGroup();" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
    </div>
</div>

<div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="editGroupLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">Edit Group</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row g-3" hidden>
                        <div class="input-group">
                            <input autocomplete="off" class="form-control" id="group_id" name="group_id" type="text" value="">
                        </div>
                    </div>                          
                    <div class="row g-3">
                        <div class="col">
                            <label for="group_name" class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="group_name" name="group_name" type="text" value="">
                            </div>
                        </div>
                    </div>    
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="updateGroup();" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

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
    $(function () {
        $('#options-id').children().addClass('active');
    })

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

     function newGroup() {
        $("#createGroup").modal("show");
    }

    function editGroup(id, name) {
        $("#editGroup").modal("show");
        $("#editGroup #group_id").val(id);
        $("#editGroup #group_name").val(name);
    }

    function saveGroup() {
        let group = $('#createGroup #group_name').val();
        let url = '/operations/group/' + group;
        $.ajax({
            type:'GET',
            url: url,
            dataType: 'json',
            async: "false",
            data: {},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Saved!', '', 'success')
                location.reload();
            }
        });
    }

    function updateGroup() {     
        let id = $("#editGroup #group_id").val();
        let name =  $("#editGroup #group_name").val();
        let url = '/operations/group/update/'+id;
        $.ajax({
            type:'POST',
            url: url,
            async: "false",
            data: {"_token": "{{ csrf_token() }}", id:id, name:name},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
                location.reload();
            }
        });
    }

    function deleteGroup(id) {
        let url = '/operations/group/delete/'+id;
        Swal.fire({
            title: 'Do you want to delete this group?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type:'GET',
                        url: url,
                        async: "false",
                        data: {},
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                        success : function(data){
                            Swal.fire('Deleted!', '', 'success')
                            location.reload();
                        }
                    });
                }
            })
    }
</script>
@stop