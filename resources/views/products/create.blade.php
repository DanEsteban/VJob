@extends('adminlte::page')

@section('title', 'Create Item')

@section('plugins.BootstrapSelect', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>New Item</h2>
        </div>
    </div>
</div>
@stop

@section('content')
    <form action="{{route('inventories.store')}}"  enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <!--- Type --->
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="select_type" class="col-sm-4 col-form-label form-control-sm" align="left">Type:</label>
                            <select name="select_type" id="select_type" onchange="changeType();" class="form-select form-select-sm" aria-label=".form-select-sm">
                                    <option value="" selected disabled>Choose an option</option>
                                @foreach ($items_type as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                              Item is inactive
                            </label>
                          </div>
                    </div>
                </div>
                <!--- group --->
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="select_group" class="col-sm-4 col-form-label form-control-sm" align="left">Group:</label>
                            <select name="select_group" id="select_group" onchange="newGroup();" class="form-select form-select-sm" aria-label=".form-select-sm">
                                <option value="" selected disabled>Choose an option</option>
                                <option value="0">------------(New)------------</option>
                                @foreach ($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="div_process" class="col-md-5">
                        <div class="input-group">
                            <label for="select_group" class="col-sm-4 col-form-label form-control-sm" align="left">Process:</label>
                            <select name="select_process" id="select_process" class="form-select form-select-sm" aria-label=".form-select-sm">
                                <option value="" selected disabled>Choose an option</option>
                                @foreach ($process as $pr)
                                    <option value="{{$pr->id}}">{{$pr->description}}</option>
                                @endforeach                        
                            </select>
                        </div>
                    </div>
                </div>
                <!--- Item Name & Item Part --->
                <br>
                <div class="row">
                    <div id="div_item" class="col-md-5">
                        <div class="input-group">
                            <label for="item_name" class="col-sm-4 col-form-label form-control-sm" align="left">Name:</label>
                            <input autocomplete="off" id="item_name" name="item_name" type="text" class="form-control form-control-sm" tabindex="1" required/>
                        </div>
                    </div>
                    <!--- Numero de Parte --->
                    <div id="div_numpart" class="col-md-5" hidden>
                        <div class="input-group">
                            <label for="item_part" class="col-sm-4 col-form-label form-control-sm" align="left">Manufacture'rs Part Number:</label>
                            <input autocomplete="off" id="item_part" name="item_part" type="text" class="form-control form-control-sm mt-3" tabindex="2"/>
                        </div>
                    </div>
                </div>
                <!--- Unidad de Medida --->
                <br id="br_unit">
                <div id="div_unit" class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="select_unity" class="col-sm-4 col-form-label form-control-sm" align="left">Unit of Measure:</label>
                            <select name="select_unity" id="select_unity" onchange="newUnit();" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="3">
                                <option value="" selected disabled>Choose an option</option>
                                <option value="0">------------(New)------------</option>
                                @foreach ($units as $unit)
                                    <option value="{{$unit->id}}">{{$unit->description}}-{{$unit->abbreviation}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!--- Tallas --->
                <br id="br_size">
                <div id="div_size" class="row">
                    <div class="col-sm-1">
                        <label for="select_size" class="form-control-sm" align="left">Size:</label>
                    </div>
                    <div class="col-md-9">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <select name="select_size[]" id="select_size" class="basic-multiple form-select form-select-sm" multiple="multiple" aria-label=".form-select-sm" style="width: 93%">
                            @foreach ($sizes as $size)
                                <option value="{{$size->id}}">{{$size->description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--- Colores --->
                <br id="br_color">
                <div id="div_color" class="row">
                    <div class="col-sm-1">
                        <label for="select_color" class="form-control-sm" align="left">Available Colors:</label>
                    </div>
                    <div class="col-md-9">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <select name="select_color[]" id="select_color" class="basic-multiple form-select form-select-sm" multiple="multiple" aria-label=".form-select-sm" style="width: 93%">
                        @foreach ($colors as $color)
                            <option value="{{$color->id}}">{{$color->description}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <!--- Item Description --->
                <br>
                <div class="row">
                    <!--- Descripción de Compra --->
                    <div id="div_description_p" class="col-md-5">
                        <div class="input-group">
                            <label for="item_description_p" class="col-sm-4 col-form-label form-control-sm" align="left">Purchase Description:</label>
                            <textarea name="item_description_p" id="item_description_p" class="form-control form-control-sm" cols="30" rows="3" style="resize: none; width: 272px" tabindex="4"></textarea>
                        </div>
                    </div>
                    <!--- Descripción de Venta --->
                    <div id="div_description_s" class="col-md-5">
                        <div class="input-group">
                            <label id="lb_description_s" for="item_description_s" class="col-sm-4 col-form-label form-control-sm" align="left">Sales Description:</label>
                            <textarea name="item_description_s" id="item_description_s" class="form-control form-control-sm" cols="30" rows="3" style="resize: none; width: 272px" tabindex="5" required></textarea>
                        </div>
                    </div>
                </div>
                <!--- Subir Imagenes --->
                <hr>
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <label for="formFileMultiple" class="col-sm-2 col-form-label form-label">Upload Images:</label>
                            <input name="item_files[]" class="form-control" type="file" id="formFileMultiple" style="width: 500px" tabindex="6" multiple>
                        </div>
                    </div>
                </div>
                <hr id="hr_assambly" hidden>
                <div id="div_assambly" class="row" hidden>
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <label for="">Production List:</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="bg-dark">
                                            <th width="4%"></th>
                                            <th width="25%">Code</th>
                                            <th>Description</th>
                                            <th width="15%">Qty</th>  
                                        </thead>
                                        <tbody id="tb_items">
                                            <tr>
                                                <td><button type="button" onclick="deleteRow(this);" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                                <td>
                                                    <input onchange="changeItem(this, {{json_encode($items)}});" type="text" name="cod_production[]" class="form-control form-control-sm" list="itemsList" novalidate>
                                                    <datalist id="itemsList">
                                                        @foreach ($items as $item)
                                                            <option value="{{$item->item_name}}"></option>
                                                        @endforeach
                                                    </datalist>
                                                </td>
                                                <td><input type="text" id="description_production" class="form-control form-control-sm" readonly></td>
                                                <td><input type="text" id="qty_production" name="qty_production[]" class="form-control form-control-sm" novalidate></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br id="br_assambly" hidden>
                <div class="row" id="btn_assambly" hidden>
                    <center>
                        <button type="button" onclick="addNewRow();" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Add Row</button>
                    </center>
                </div>
                <hr>
                <!--- Item Notes --->
                <div class="row">
                     <!--- Descripción de Compra --->
                     <div id="div_description_p" class="">
                        <div class="input-group">
                            <label for="item_notes" class="col-form-label form-control-sm" align="left">Notes:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <textarea name="item_notes" id="item_notes" class="form-control form-control-sm" cols="30" rows="3" style="resize: none; width: 715px" tabindex="7"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <!--- Item Cost $ Item Price --->
                <div class="row">
                    <!--- Costo --->
                    <div id="div_costo" class="col-md-5" hidden>
                        <div class="input-group">
                            <label for="item_cost" class="col-sm-4 col-form-label form-control-sm" align="left">Cost:</label>
                            <input autocomplete="off" id="item_cost" name="item_cost" type="text" class="form-control form-control-sm" tabindex="8"/>
                        </div>
                    </div>
                    <!--- Precio --->
                    <div id="div_precio" class="col-md-5">
                        <div class="input-group">
                            <label for="item_price" class="col-sm-4 col-form-label form-control-sm" align="left">Price:</label>
                            <input autocomplete="off" id="item_price" name="item_price" type="text" class="form-control form-control-sm" tabindex="9"/>
                        </div>
                    </div>
                </div>
                <!--- Margen de utilidad --->
                <br id="br_margin" hidden>
                <div id="div_margin" class="row" hidden>
                    <div class="col">
                        <label for="item_price" class="col-sm-4 col-form-label form-control-sm" align="left">Margin:</label>
                        <p><b></b></p>
                    </div>
                </div>
                <!--- Item Max Order & Item Min Order --->
                <br id="br_maxmin">
                <div id="div_maxmin" class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="item_max" class="col-sm-4 col-form-label form-control-sm" align="left">Max Order:</label>
                            <input autocomplete="off" id="item_max" name="item_max" type="text" class="form-control form-control-sm" tabindex="10"/>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="item_min" class="col-sm-4 col-form-label form-control-sm" align="left">Min Order:</label>
                            <input autocomplete="off" id="item_min" name="item_min" type="text" class="form-control form-control-sm" tabindex="11"/>
                        </div>
                    </div>
                </div>
                <hr id="hr_codebar" hidden>
                <div id="div_codebar" class="row" hidden>
                    <div class="col-sm-1">
                        <label class="col-form-label form-control-sm" align="left">Barcodes:</label>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="col-md-5">                      
                        <table class="table table-sm">
                            <thead class="bg-dark">
                                <th>Code</th>
                                <th>Vendor</th>
                            </thead>
                            <tbody id="tb_body">
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td><input name="code_item[]" type="text" class="form-control form-control-sm"></td>
                                        <td>
                                            <input name="code_vendor[]" type="text" class="form-control form-control-sm" list="vendorList">
                                            <datalist id="vendorList">
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{$vendor->name}}"></option>
                                                @endforeach
                                            </datalist>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                  <!--- Buttons --->
                <div class="row">
                    <div class="nav justify-content-end">
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width:100px;" tabindex="11">Save</button>
                        &nbsp; &nbsp;
                        <button type="button" onclick="salir();" class="btn btn-sm btn-outline-danger" style="width:100px;" tabindex="12">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
                        <button type="subtmit" onclick="saveGroup();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
    
              </div>
            </div>
    </div>
    
    <div class="modal fade" id="createUnit" tabindex="-1" role="dialog" aria-labelledby="createUnitLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                  <h5 class="modal-title">New Unite Measure</h5>
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
                        <button type="button" onclick="saveUnitMeasure();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
    
              </div>
            </div>
    </div>

    <!--- Toast --->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
            <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
            <strong class="me-auto">ART&COLOR</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
                Added a new record.
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
<script type="text/javascript" src="/js/items.actions.js"></script>
<script type="text/javascript">
    function saveUnitMeasure() {
        let unit_abbreviation = $('#createUnit #unit_abbreviation').val();
        let unit_description = $('#createUnit #unit_description').val();
        let url = '/operations/unit';
        $.ajax({
            type:'POST',
            url: url,
            dataType: 'json',
            async: "false",
            data: {"_token": "{{ csrf_token() }}", abbreviation:unit_abbreviation, description:unit_description},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#select_unity').append($('<option>', {
                    value: data['id'],
                    text: data['description']+ "-" +data['abbreviation']
                }));

                $('#createUnit #unit_abbreviation').val("");
                $('#createUnit #unit_description').val("");
                const selected = document.querySelector('#select_unity');
                selected.value = data['id'];
                showToast();
            }
        });
    }

    $(document).ready(function() {
        $('.basic-multiple').select2({
            placeholder: 'Choose an option',
            allowClear: true,
            width: 'resolve' ,
            theme: "classic"
        });
        
    });

    $(function () {
        $('#inventory-id').children().addClass('active');
    })

    function addNewRow() {
        $.ajax({
            type:'GET',
            dataType:'html',
            url:'/elements/production/items',
            async:false,
            data:{},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#tb_items').append(data);
            }
        });
    }

    function deleteRow(objeto, id) {
        Swal.fire({
            title: 'Do you want to delete the file?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancel`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let tr = $(objeto).parent().parent();
                $.ajax({
                    type:'GET',
                    url:'/elements/production/item/delete/' + id,
                    async:false,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success : function(data){
                        $(tr).remove();
                    }
                });
            } 
        })
    }

</script>
@stop