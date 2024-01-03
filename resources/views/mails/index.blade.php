@extends('adminlte::page')

@section('title', 'Customize Emails')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Customize Emails</h2>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h3>Stage Module</h3>
            </div>          
        </div>
        <hr>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">Subject:</label>
                    &nbsp;&nbsp;&nbsp;<input id="subject" name="subject" class="form-control form-control-md" value="{{$stage_mod->subject}}" type="text">
                </div>
            </div>
            <div class="col">
                <button onclick="update({{$stage_mod->id}}, this)" class="btn btn-md btn-outline-primary" style="width: 120px">Save</button>
            </div>  
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <label class="col-sm-2 col-form-label form-control-sm">Message:</label>
                    <textarea class="form-control form-control-md" name="message" id="message" cols="70" rows="6" style="resize: none">{{$stage_mod->message}}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h3>Estimate Module</h3>
            </div>          
        </div>
        <hr>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">Subject:</label>
                    &nbsp;&nbsp;&nbsp;<input id="subject" name="subject" class="form-control form-control-md" value="{{$estimate_mod->subject}}" type="text">
                </div>
            </div>
            <div class="col">
                <button onclick="update({{$estimate_mod->id}}, type);" class="btn btn-md btn-outline-primary" style="width: 120px">Save</button>
            </div>  
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <label class="col-sm-2 col-form-label form-control-sm">Message:</label>
                    <textarea class="form-control form-control-md" name="message" id="message" cols="70" rows="6" style="resize: none">{{$estimate_mod->message}}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h3>Invoice Module</h3>
            </div>          
        </div>
        <hr>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">Subject:</label>
                    &nbsp;&nbsp;&nbsp;<input id="subject" name="subject" class="form-control form-control-md" value="{{$invoice_mod->subject}}" type="text">
                </div>
            </div>
            <div class="col">
                <button onclick="update({{$invoice_mod->id}}, this);" class="btn btn-md btn-outline-primary" style="width: 120px">Save</button>
            </div>  
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <label class="col-sm-2 col-form-label form-control-sm">Message:</label>
                    <textarea class="form-control form-control-md" name="message" id="message" cols="70" rows="6" style="resize: none">{{$invoice_mod->message}}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h3>Payment Module</h3>
            </div>          
        </div>
        <hr>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="col-sm-3 col-form-label form-control-sm">Subject:</label>
                    &nbsp;&nbsp;&nbsp;<input id="subject" name="subject" class="form-control form-control-md" value="{{$payment_mod->subject}}" type="text">
                </div>
            </div>
            <div class="col">
                <button onclick="update({{$payment_mod->id}}, this);" class="btn btn-md btn-outline-primary" style="width: 120px">Save</button>
            </div>  
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <label class="col-sm-2 col-form-label form-control-sm">Message:</label>
                    <textarea class="form-control form-control-md" name="message" id="message" cols="70" rows="6" style="resize: none">{{$payment_mod->message}}</textarea>
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
    function update(id, objeto) {
        let div_parent = $(objeto).parent().parent().parent();
        let subject = div_parent.find('#subject').val();
        let message = div_parent.find('#message').val();
        $.ajax({
            type:'POST',
            url:'/operations/mail/update/' + id,
            async:false,
            data:{
                "_token": "{{ csrf_token() }}",
                subject:subject,
                message:message
            },
            error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
            }
        });
    }
</script>
@stop