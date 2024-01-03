@extends('adminlte::page')

@section('title', 'ProductReport')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Products Report</h2> 
        </div>
    </div>
</div>
@stop

@section('content')
    <form action="/elements/product/report/pdf" enctype="multipart/form-data" method="POST" id="doc_form"> 
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label" style="font-size: 16px">Warehouse:</label>
                            <select id="warehouse" name="warehouse" class="form-select" aria-label=".form-select" tabindex="5" required>
                                <option selected disabled="required" value="">Choose...</option>
                                <!-- <option value="0">All the Products</option> -->
                                    @foreach ($warehouse as $wh)
                                        <option value="{{$wh->id}}">{{$wh->wh_name}}</option>
                                    @endforeach
                            </select>
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
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Search</button>
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