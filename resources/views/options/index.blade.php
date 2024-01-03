@extends('adminlte::page')

@section('title', 'Options')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Options</h2>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    use App\Models\DocumentNumbers;

    $order_number = DocumentNumbers::where('type', 'Orders')->value('number');
    $invoice_number = DocumentNumbers::where('type', 'Invoices')->value('number');
@endphp

 <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">               
                <div class="row">
                    <center>
                        <h3>Customize Elements</h3>
                    </center>
                </div>
                <hr>
                <!--- Colores -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/colors" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-droplet"></i> Colors</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Forma de Entrega -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/deliveries" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-truck"></i> Deliveries</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Numeración de Documentos -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a class="btn btn-outline-danger btn-lg btn-block" data-toggle="modal" data-target="#editModal"><i class="fa-regular fa-file-lines"></i> Document Numbers</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Personalizar Correos -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/mails" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-envelope-open-text"></i> Emails Letters</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Grupos -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/group" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-regular fa-object-group"></i> Groups</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Unidad de Medida -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/unite" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-weight-scale"></i> Measurement Units</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Tallas -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/sizes" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-ruler-combined"></i> Sizes</a>
                        </div>
                    </center>   
                </div>
                <br>
                 <!--- Tallas -->
                 <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/taxes" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-money-bill-trend-up"></i> Taxes</a>
                        </div>
                    </center>   
                </div>
                <br>
                <!--- Formas de pago -->
                <div class="row">
                    <center>
                        <div class="col-md-6">
                            <a href="/terms" class="btn btn-outline-danger btn-lg btn-block"><i class="fa-solid fa-money-bill"></i> Terms</a>
                        </div>
                    </center>   
                </div>
                <br>
            </div>
        </div>
    </div>
 </div>

 <!-- Modal -->
 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="/img/logo.png" width="30px" alt="...">&nbsp;&nbsp;
                    <h5 class="modal-title" id="popModalTitle">ART&COLOR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <h4><strong>Document Numbers</strong></h4>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-lg">
                                    <label for="" class="col-sm-5 col-form-label form-control-md" style="font-size: 20px; font-weight: bold">Estimates:</label>
                                    <input type="number" id="order_number" class="form-control form-control-sm" value="{{$order_number}}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-lg">
                                    <label for="" class="col-sm-5 col-form-label form-control-md" style="font-size: 20px; font-weight: bold">Invoices:</label>
                                    <input type="number" id="invoice_number" class="form-control form-control-sm" value="{{$invoice_number}}">
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer">                            
                        <button type="button" onclick="saveNumbers();" name="edituser" class="btn btn-outline-primary btn-sm">Save</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Cancel</button>            
                    </div>
                </div>
            </div>
        </div>
 </div>
 <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    function saveNumbers() {
        let order_number = $("#order_number").val();
        let invoice_number =  $("#invoice_number").val();

        let url = '/operations/documents/update';
        $.ajax({
            type:'GET',
            url: url,
            async: "false",
            data: {"_token": "{{ csrf_token() }}", orders:order_number, invoices:invoice_number},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
                location.reload();
            }
        });
    }

    function salir() {
        Swal.fire({
            title: 'Do you want to exit the form?',
            showDenyButton: true,
            confirmButtonText: 'Exit',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/dashboard";
                }
            })
    }
</script>
@stop