@extends('adminlte::page')

@section('title', 'Kardex')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Kardex</h2> 
        </div>
    </div>
</div>
@stop

@section('content')

    <form action="/elements/kardex/pdf" enctype="multipart/form-data" method="POST" id="doc_form"> 
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label" style="font-size: 16px">Productos:</label>
                            <input id="item" name="item" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                            <datalist id="itemsList">
                                @foreach ($items as $item)
                                    <option value="{{$item['item_name']}}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label">Desde:</label>
                            &nbsp;&nbsp;<input type="month" id="start_month" name="start_month" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label">Hasta:</label>
                            &nbsp;&nbsp;<input type="month" id="end_month" name="end_month" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <center>
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Find</button>
                        <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                    </center>
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
<script type="text/javascript" src="/js/orders.actions.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay-form").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
    });
</script>
@stop