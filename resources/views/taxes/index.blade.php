@extends('adminlte::page')

@section('title', 'Taxes')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Taxes</h2>
        </div>

        <div class="col-md-2 mt-3">
            <a onclick="newTax();" class="btn btn-outline-danger" style="width:160px;"><i class="fa fa-plus"></i> New Tax</a>
        </div>
    </div>
</div>
@stop

@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Taxes List</button>
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
                                    <th width="70%">Description</th>
                                    <th width="70%">Tax</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tb_filtro">
                                @foreach ($taxes as $tax)
                                    <tr>
                                        <td hidden>{{$tax->id}}</td>
                                        <td role="button">{{$tax->description}}</td>
                                        <td role="button">{{$tax->percentage}} %</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" style="width: 300px">
                                                <li><a class="dropdown-item" onclick="editTax({{$tax->id}}, '{{$tax->description}}', {{$tax->percentage}});">Edit</a></li>
                                                <li><a class="dropdown-item" onclick="deleteTax({{$tax->id}});">Delete</a></li>
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
    <div class="modal fade" id="createTax" tabindex="-1" role="dialog" aria-labelledby="createTaxLabel" aria-hidden="true">
        <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                  <h5 class="modal-title">New Tax</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">                         
                        <div class="row g-3">
                            <div class="col">
                                <label for="tax_description" class="form-control-sm"><b>Description:</b></label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control" id="tax_description" name="tax_description" type="text" value="">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tax_percentage" class="form-control-sm"><b>Percentage:</b></label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control" id="tax_percentage" name="tax_percentage" type="text" value="">
                                    &nbsp;&nbsp;<label class="mt-2">%</label>
                                </div>
                            </div>
                        </div>    
                    </div>  
    
                    <div class="modal-footer">
                        <button type="subtmit" onclick="saveTax();" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
    
              </div>
        </div>
    </div>

    <div class="modal fade" id="editTax" tabindex="-1" role="dialog" aria-labelledby="editTaxLabel" aria-hidden="true">
        <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                  <h5 class="modal-title">Edit Tax</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row g-3" hidden>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="tax_id" name="tax_id" type="text" value="">
                            </div>
                        </div>                          
                        <div class="row g-3">
                            <div class="col">
                                <label for="tax_description" class="form-control-sm"><b>Description:</b></label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control" id="tax_description" name="tax_description" type="text" value="">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tax_percentage" class="form-control-sm"><b>Percentage:</b></label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control" id="tax_percentage" name="tax_percentage" type="text" value="">
                                    &nbsp;&nbsp;<label class="mt-2">%</label>
                                </div>
                            </div>
                        </div>    
                    </div>  
                </div>  
    
                    <div class="modal-footer">
                        <button type="subtmit" onclick="updateTax();" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
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

    function newTax() {
        $("#createTax").modal("show");
    }

    function editTax(id, description, percentage) {
        $("#editTax").modal("show");
        $("#editTax #tax_id").val(id);
        $("#editTax #tax_description").val(description);
        $("#editTax #tax_percentage").val(percentage);
    }

    function saveTax() {
        let description =  $("#createTax #tax_description").val();
        let percentage =  $("#createTax #tax_percentage").val();
        let url = '/operations/tax';
        $.ajax({
            type:'POST',
            url: url,
            dataType: 'json',
            async: "false",
            data: {
                "_token": "{{ csrf_token() }}", 
                description:description, 
                percentage:percentage
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Saved!', '', 'success')
                location.reload();
            }
        });
    }

    function updateTax() {     
        let id = $("#editTax #tax_id").val();
        let description =  $("#editTax #tax_description").val();
        let percentage =  $("#editTax #tax_percentage").val();
        let url = '/operations/tax/update';
        $.ajax({
            type:'POST',
            url: url,
            async: "false",
            data: {
                "_token": "{{ csrf_token() }}", 
                id:id, 
                description:description, 
                percentage:percentage
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
                location.reload();
            }
        });
    }

    function deleteTax(id) {
        let url = '/operations/tax/delete/'+id;
        Swal.fire({
            title: 'Do you want to delete this tax?',
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