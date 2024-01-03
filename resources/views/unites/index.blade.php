@extends('adminlte::page')

@section('title', 'Unit Measure')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Measurement Units</h2>
        </div>

        <div class="col-md-2 mt-3">
            <button onclick="newUnit();" class="btn btn-outline-danger" style="width:200px;"><i class="fa fa-plus"></i> New unit of measure</button>
        </div>
    </div>
</div>
@stop

@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Unit List</button>
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
                                    <th width="15%">Abbreviation</th>
                                    <th width="50%">Description</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tb_filtro">
                                @foreach ($units as $unit)
                                    <tr>
                                        <td hidden>{{$unit->id}}</td>
                                        <td role="button">{{$unit->abbreviation}}</td>
                                        <td role="button">{{$unit->description}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                  <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" style="width: 300px">
                                                  <li><a class="dropdown-item" onclick="editUnit({{$unit->id}}, '{{$unit->abbreviation}}', '{{$unit->description}}');">Edit</a></li>
                                                  <li><a class="dropdown-item" onclick="deleteUnit({{$unit->id}});">Delete</a></li>
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
<div class="modal fade" id="createUnit" tabindex="-1" role="dialog" aria-labelledby="createUnitLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Measure Unit</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body"> 
                    <div class="row g-3">
                        <div class="col">
                            <label for="unit_abbreviation" class="form-control-sm"><b>Abbreviation:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="unit_abbreviation" name="unit_abbreviation" type="text" value="">
                            </div>
                        </div>
                    </div>  
                    <br>                        
                    <div class="row g-3">
                        <div class="col">
                            <label for="unit_description" class="form-control-sm"><b>Description:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="unit_description" name="unit_description" type="text" value="">
                            </div>
                        </div>
                    </div>    
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="saveUnit();" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
    </div>
</div>

<div class="modal fade" id="editUnit" tabindex="-1" role="dialog" aria-labelledby="editUnitLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">Edit Payment Terms</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row g-3" hidden>
                        <div class="input-group">
                            <input autocomplete="off" class="form-control" id="unit_id" name="unit_id" type="text" value="">
                        </div>
                    </div>                          
                    <div class="row g-3">
                        <div class="col">
                            <label for="unit_abbreviation" class="form-control-sm"><b>Abbreviation:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="unit_abbreviation" name="unit_abbreviation" type="text" value="">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="unit_description" class="form-control-sm"><b>Description:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="unit_description" name="unit_description" type="text" value="">
                            </div>
                        </div>
                    </div>     
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="updateUnit();" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
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
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

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

    function newUnit() {
        $("#createUnit").modal("show");
    }

    function editUnit(id, abbreviation, description) {
        $("#editUnit").modal("show");
        $("#editUnit #unit_id").val(id);
        $("#editUnit #unit_abbreviation").val(abbreviation);
        $("#editUnit #unit_description").val(description);
    }

    function saveUnit() {
        let abbreviation = $('#createUnit #unit_abbreviation').val();
        let description = $('#createUnit #unit_description').val();
        let url = '/operations/unit/'+1;
        $.ajax({
            type:'POST',
            url: url,
            dataType: 'json',
            async: "false",
            data: {"_token": "{{ csrf_token() }}", abbreviation:abbreviation, description:description},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Saved!', '', 'success')
                location.reload();
            }
        });
    }

    function updateUnit() {     
        let id = $("#editUnit #unit_id").val();
        let abbreviation =  $("#editUnit #unit_abbreviation").val();
        let description =  $("#editUnit #unit_description").val();
        let url = '/operations/unit/update/'+id;
        $.ajax({
            type:'POST',
            url: url,
            async: "false",
            data: {"_token": "{{ csrf_token() }}", id:id, abbreviation:abbreviation, description:description},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
                location.reload();
            }
        });
    }

    function deleteUnit(id) {
        let url = '/operations/unit/delete/'+id;
        Swal.fire({
            title: 'Do you want to delete this measure unit?',
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