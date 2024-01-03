@extends('adminlte::page')

@section('title', 'Edit Vendor')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Edit Vendor</h2>
        </div>
    </div>
</div>
@stop

@section('content')

    <!--- Form --->
    <form action="/vendors/{{$vendor->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="v_company" class="col-sm-4 col-form-label form-control-sm" align="left">Company Name:</label>
                            <input autocomplete="off" id="v_company" name="v_company" type="text" class="form-control form-control-sm" value="{{$vendor->name}}" tabindex="1" required/>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{$vendor->is_active}}" name="v_inactive" id="flexCheckDefault" tabindex="2" <?php if($vendor->is_active == 1) echo  'checked'?>>
                            <label class="form-check-label" for="flexCheckDefault">
                            Vendor is inactive
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="v_phone" class="col-sm-4 col-form-label form-control-sm" align="left">Main Phone:</label>
                            <input autocomplete="off" id="v_phone" name="v_phone" type="text" class="form-control form-control-sm" value="{{$vendor->phone}}" tabindex="3"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="v_mail" class="col-sm-4 col-form-label form-control-sm" align="left">Main Email:</label>
                            <input autocomplete="off" id="v_mail" name="v_mail" type="text" class="form-control form-control-sm" value="{{$vendor->email}}" tabindex="4"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="v_contact" class="col-sm-4 col-form-label form-control-sm" align="left">Contact:</label>
                            <input autocomplete="off" id="v_contact" name="v_contact" type="text" class="form-control form-control-sm" value="{{$vendor->contact}}" tabindex="5"/>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mt-2">
                            <label for="v_billto" class="col-sm-4 col-form-label form-control-sm" align="left">Bill To:</label>
                        </div>
                        <div class="row-md-4">
                            <div class="container" style="border: 1px solid gray">
                                <br>
                                <input class="form-control form-control-sm" name="street_billto" type="text" placeholder="Street Number And Name Or P.O Box" value="{{$vendor->billto_street}}" tabindex="6">
                                <hr>
                                <input class="form-control form-control-sm" name="company_billto" type="text" placeholder="Specify Company, APT, Suite, Unit" value="{{$vendor->billto_company}}" tabindex="7">
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-sm" name="city_billto" type="text" placeholder="City" value="{{$vendor->billto_city}}" tabindex="8">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-sm" name="postal_billto" type="text" placeholder="Postal Code" value="{{$vendor->billto_postal}}" tabindex="9">
                                    </div>
                                </div>
                                <hr>
                                <input class="form-control form-control-sm" name="state_billto" type="text" placeholder="State" value="{{$vendor->billto_state}}" tabindex="10">
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary" style="width:100px;" tabindex="11">Save</button>
                        &nbsp; &nbsp;
                        <button type="button" onclick="salir();" class="btn btn-sm btn-danger" style="width:100px;" tabindex="12">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    
    $(function () {
        $('#vendor-id').children().addClass('active');
    })

    function salir() {
        Swal.fire({
        title: 'Do you want to exit the form?',
        showDenyButton: true,
        confirmButtonText: 'Exit',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/vendors";
            }
        })
    }
</script>
@stop