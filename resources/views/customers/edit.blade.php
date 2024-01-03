@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Edit Customer</h2>
        </div>
    </div>
</div>
@stop

@section('content')

    <!--- Form --->
    <form action="/customers/{{$customer->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_company" class="col-sm-4 col-form-label form-control-sm" align="left">Company Name:</label>
                            <input autocomplete="off" id="cs_company" name="cs_company" type="text" class="form-control form-control-sm" value="{{$customer->company_name}}" tabindex="1" required/>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-check">
                            <input name="cs_inactive" class="form-check-input" type="checkbox" value="{{$customer->is_active}}" id="flexCheckDefault" <?php if($customer->is_active == 0) echo  'checked'?>>
                            <label class="form-check-label" for="flexCheckDefault">
                            Customer is inactive
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_firstname" class="col-sm-4 col-form-label form-control-sm" align="left">Full Name:</label>
                            <input autocomplete="off" id="cs_firstname" name="cs_firstname" type="text" class="form-control form-control-sm" placeholder="First Name" value="{{$customer->first_name}}" tabindex="3"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input autocomplete="off" id="cs_midlename" name="cs_midlename" type="text" class="form-control form-control-sm" placeholder="Midle Name" value="{{$customer->midle_name}}" tabindex="4"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input autocomplete="off" id="cs_lastname" name="cs_lastname" type="text" class="form-control form-control-sm" placeholder="Last Name" value="{{$customer->last_name}}" tabindex="5"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_phone" class="col-sm-4 col-form-label form-control-sm" align="left">Main Phone:</label>
                            <input autocomplete="off" id="cs_phone" name="cs_phone" type="text" class="form-control form-control-sm" value="{{$customer->phone}}" tabindex="6"/>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_mail" class="col-sm-4 col-form-label form-control-sm" align="left">Main Email:</label>
                            <input autocomplete="off" id="cs_mail" name="cs_mail" type="text" class="form-control form-control-sm" value="{{$customer->email}}" tabindex="7"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_workphone" class="col-sm-4 col-form-label form-control-sm" align="left">Work Phone:</label>
                            <input autocomplete="off" id="cs_workphone" name="cs_workphone" type="text" class="form-control form-control-sm" value="{{$customer->work_prone}}" tabindex="8"/>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="cs_ccemail" class="col-sm-4 col-form-label form-control-sm" align="left">CC Email:</label>
                            <input autocomplete="off" id="cs_ccemail" name="cs_ccemail" type="text" class="form-control form-control-sm" value="{{$customer->cc_email}}" tabindex="9"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="select_payment" class="col-sm-4 col-form-label form-control-sm" align="left">Payment Terms:</label>
                            <select id="select_payment" onchange="newTerm();" name="select_payment" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="10">
                                <option value="" selected disabled>Choose an option</option>
                                <option value="0">------------(New)------------</option>
                                @foreach ($terms as $term)
                                <option value="{{$term->id}}"  <?php echo ($term->id ==  $customer->id_terms) ? ' selected="selected"' : '';?>>{{$term->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="select_delivery" class="col-sm-4 col-form-label form-control-sm" align="left">Delivery Method:</label>
                            <select id="select_delivery" onchange="newDelivery();" name="select_delivery" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="11">
                                <option value="" selected disabled>Choose an option</option>
                                <option value="0">------------(New)------------</option>
                                @foreach ($deliveries as $delivery)
                                <option value="{{$delivery->id}}" <?php echo ($delivery->id ==  $customer->id_delivery) ? ' selected="selected"' : '';?>>{{$delivery->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mt-2">
                            <label for="cs_billto" class="col-sm-4 col-form-label form-control-sm" align="left">Invoice/Bill To:</label>
                        </div>
                        <div class="row-md-4">
                            <div class="container" style="border: 1px solid gray">
                                <br>
                                <input class="form-control form-control-sm" id="street_billto" name="street_billto" type="text" placeholder="Street Number And Name Or P.O Box" value="{{$customer->billto_street}}">
                                <hr>
                                <input class="form-control form-control-sm" id="company_billto" name="company_billto" type="text" placeholder="Specify Company, APT, Suite, Unit"  value="{{$customer->billto_company}}">
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-sm" id="city_billto" name="city_billto" type="text" placeholder="City"  value="{{$customer->billto_city}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-sm" id="postal_billto" name="postal_billto" type="text" placeholder="Postal Code"  value="{{$customer->billto_postal}}">
                                    </div>
                                </div>
                                <hr>
                                <input class="form-control form-control-sm" id="state_billto" name="state_billto" type="text" placeholder="State"  value="{{$customer->billto_state}}">
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="cs_shipto" class="form-control-sm" align="left">Ship To:</label>
                            </div>
                            <div class="col">
                                <select id="select_shipto" onchange="newShipTo();" name="select_shipto[]" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                    <option value=""></option>
                                    <option value="0">------------(New)------------</option>
                                    @if ($shipto->count() > 0)
                                        @foreach ($shipto as $to)
                                        <option value="{{$to->id}}" <?php if ($shipto[0]->id == $to->id) { echo ' selected="selected"'; } ?>>{{$to->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col">
                                <button onclick="updateShipTo();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-floppy-disk"></i></button>
                                <button onclick="deleteShipTo();" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>

                        @if ($shipto->count() > 0)
                            <div class="row-md-4">
                                <div class="container" style="border: 1px solid gray">
                                    <br>
                                    <input class="form-control form-control-sm" id="street_shipto" name="street_shipto" type="text" placeholder="Street Number And Name Or P.O Box" value="{{$shipto[0]->address}}">
                                    <hr>
                                    <input class="form-control form-control-sm" id="company_shipto" name="company_shipto" type="text" placeholder="Specify Company, APT, Suite, Unit"  value="{{$shipto[0]->company}}">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" id="city_shipto" name="city_shipto" type="text" placeholder="City"  value="{{$shipto[0]->city}}">
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" id="postal_shipto" name="postal_shipto" type="text" placeholder="Postal Code"  value="{{$shipto[0]->postal}}">
                                        </div>
                                    </div>
                                    <hr>
                                    <input class="form-control form-control-sm" id="state_shipto" name="state_shipto" type="text" placeholder="State"  value="{{$shipto[0]->state}}">
                                    <br>
                                </div>
                            </div>
                        @else
                            <div class="row-md-4">
                                <div class="container" style="border: 1px solid gray">
                                    <br>
                                    <input class="form-control form-control-sm" id="street_shipto" name="street_shipto" type="text" placeholder="Street Number And Name Or P.O Box">
                                    <hr>
                                    <input class="form-control form-control-sm" id="company_shipto" name="company_shipto" type="text" placeholder="Specify Company, APT, Suite, Unit">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" id="city_shipto" name="city_shipto" type="text" placeholder="City">
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" id="postal_shipto" name="postal_shipto" type="text" placeholder="Postal Code">
                                        </div>
                                    </div>
                                    <hr>
                                    <input class="form-control form-control-sm" id="state_shipto" name="state_shipto" type="text" placeholder="State">
                                    <br>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width:100px;">Save</button>
                        &nbsp; &nbsp;
                        <button type="button" onclick="salir();" class="btn btn-sm btn-outline-danger" style="width:100px;">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modals -->
    <div class="modal fade" id="createTerms" tabindex="-1" role="dialog" aria-labelledby="createTermsLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Payment Terms</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                          
                    <div class="row g-3">
                        <div class="col">
                            <label for="cl_ruc" class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="terms_name" name="terms_name" type="text" value="">
                            </div>
                        </div>
                    </div>
    
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="saveTerm();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="createDelivery" tabindex="-1" role="dialog" aria-labelledby="createDeliveryLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Delivery Method</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                          
                    <div class="row g-3">
                        <div class="col">
                            <label for="cl_ruc" class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="delivery_name" name="delivery_name" type="text" value="">
                            </div>
                        </div>
                    </div>
    
                </div>  

                <div class="modal-footer">
                    <button type="button" onclick="saveDelivery();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="createShipTo" tabindex="-1" role="dialog" aria-labelledby="createShipToLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_shipto" name="cs_shipto" type="text" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Street Number And Name Or P.O Box:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address" name="cs_address" type="text">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Specify Company, APT, Suite, Unit:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address2" name="cs_address2" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>City:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_city" name="cs_city" type="text">
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-control-sm"><b>Postal Code:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_postal" name="cs_postal" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>State:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_state" name="cs_state" type="text">
                            </div>
                        </div>
                    </div> 
                </div>  

                <div class="modal-footer">
                    <button type="button" onclick="saveShipTo();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
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
<script type="text/javascript" src="/js/customer.actions.js"></script>
<script type="text/javascript">
    $(function () {
        $('#customer-id').children().addClass('active');
    })

    function saveShipTo() {
        let name_address = $('#createShipTo #cs_shipto').val();
        let address_detail = $('#createShipTo #cs_address').val();
        let address2_detail = $('#createShipTo #cs_address2').val();
        let city_detail = $('#createShipTo #cs_city').val();
        let postal_detail = $('#createShipTo #cs_postal').val();
        let state_detail = $('#createShipTo #cs_state').val();
        let url = '/operations/shipto';
        
        $.ajax({
            type:'POST',
            url: url,
            dataType: 'json',
            async: "false",
            data: {
                "_token": "{{ csrf_token() }}",
                name:name_address, 
                address:address_detail, 
                company:address2_detail, 
                city:city_detail, 
                postal:postal_detail,
                state:state_detail
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#select_shipto').append($('<option>', {
                    value: data['id'],
                    text: data['name']
                }));
                $('#street_shipto').val(data['address']);
                $('#company_shipto').val(data['company']);
                $('#city_shipto').val(data['city']);
                $('#postal_shipto').val(data['postal']);
                $('#state_shipto').val(data['state']);

                $('#createShipTo #cs_shipto').val("");
                $('#createShipTo #cs_address').val("");
                $('#createShipTo #cs_address2').val("");
                $('#createShipTo #cs_city').val("");
                $('#createShipTo #cs_postal').val("");
                $('#createShipTo #cs_state').val("");
                
                const selected = document.querySelector('#select_shipto');
                selected.value = data['id'];
                showToast();
            }
        });
    }

    function updateShipTo() {
        let id = $('#select_shipto').val();
        if(id){
            let name_address = $('#select_shipto option:selected').text();
            let address_detail = $('#street_shipto').val();
            let address2_detail = $('#company_shipto').val();
            let city_detail = $('#city_shipto').val();
            let postal_detail = $('#postal_shipto').val();
            let state_detail = $('#state_shipto').val();
            let url = '/operations/shipto/update/' + id;
            $.ajax({
                type:'POST',
                url: url,
                async: "false",
                data: {
                    "_token": "{{ csrf_token() }}", 
                    name:name_address, 
                    address:address_detail, 
                    company:address2_detail, 
                    city:city_detail, 
                    postal:postal_detail, 
                    state:state_detail
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    Swal.fire('Updated!', '', 'success')
                }
            });
        }
    }

    function deleteShipTo() {
        let id = $('#select_shipto').val();
        if(id){
            Swal.fire({
                title: 'Do you want to delete this address?',
                showDenyButton: true,
                confirmButtonText: 'Delete',
                denyButtonText: `Cancel`,
                }).then((result) => {
            
                if (result.isConfirmed) {
                    $.ajax({
                        type:'GET',
                        url: '/operations/shipto/delete/' + id,
                        async: "false",
                        data: {},
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                        success : function(data){
                            $('#select_shipto option:selected').remove();
                            $('#street_shipto').val(" ");
                            $('#company_shipto').val(" ");
                            $('#city_shipto').val(" ");
                            $('#postal_shipto').val(" ");
                            $('#state_shipto').val(" ");
                            Swal.fire('Deleted!', '', 'success');
                        }
                    });
                
                }
            })    
        }  
    }
</script>
@stop