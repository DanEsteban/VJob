@extends('adminlte::page')

@section('title', 'Inventory Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Inventory Center</h2>
        </div>

        <div class="col-md-2 mt-3">
                <a class="btn btn-outline-danger" style="width:160px;" href="/inventories/create"><i class="fa fa-plus"></i> New Item</a>
        </div>
    </div>
</div>
@stop


@section('content')
@php
    use App\Models\Sizes;
    use App\Models\Colors;
    use App\Models\ImageProduct;
    use App\Models\Products_Colors;
    use App\Models\Products_Sizes;
@endphp

@if( Session::has('info') )
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('info') }}
    </div>
@endif

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="item-tab" data-bs-toggle="tab" data-bs-target="#item" type="button" role="tab" aria-controls="item" aria-selected="true">Item List</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="item" role="tabpanel" aria-labelledby="item-tab">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <!--- Table Items --->
                    <div class="div_table">
                        <div class="row">
                                <div class="col" style="overflow-y:auto;">
                                    <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                                        <thead class="bg-dark">
                                            <tr>

                                                <th id="id_item" hidden>Id</th>
                                                <th width="15%">Code</th>
                                                <th width="30%">Description</th>
                                                <th width="15%">Stack</th>
                                                <th width="15%">Price</th>
                                                <th width="20%">Type</th>
                                                <th width="10%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_filtro">
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td id="td_item" hidden>{{$product->id}}</td>
                                                    <td role="button" class="align-middle">{{$product->item_name}}</td>
                                                    <td role="button" class="align-middle" ondblclick="editar({{$product->id}});">{{$product->sales_description}}</td>
                                                    <td role="button" class="align-middle">{{$product->qty}}</td>
                                                    <td role="button" class="align-middle">{{$product->price}}</td>
                                                    <td role="button" class="align-middle">{{$product->id_type}}</td>
                                                    <td class="align-middle">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                              <span class="visually-hidden">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu" style="width: 300px">
                                                              <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showModal{{$index}}" data-bs-id="{{$product->id}}">View</a></li>
                                                              <li><a class="dropdown-item" href="/inventories/{{$product->id}}/edit">Edit</a></li>
                                                              <li><a class="dropdown-item" onclick="eliminar({{$product->id}});">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="showModal{{$index}}" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                                                          <h5 class="modal-title" id="showModalLabel">View</h5>
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="div_information" class="col">
                                                                <div class="div-fixed-item">
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <h3>Item Review</h3>                                
                                                                                </div>
                                                                            </div>
                                                                        </div>                               
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div id="div_carousel" class="col">
                                                                                    <div id="carouselItemsCaptions" class="carousel slide" data-bs-ride="carousel">
                                                                                        <div id="div_inner" class="carousel-inner">
                                                                                            @php
                                                                                                $images = ImageProduct::where('id_product', $product->id)->get()->toArray();
                                                                                                $colors = Products_Colors::where('id_item', $product->id)->get()->toArray();               
                                                
                                                                                                $sizes = Products_Sizes::where('id_item', $product->id)->get()->toArray();
                                                                                                $primero = true;
                                                                                           @endphp
                                                
                                                                                            @foreach ($images as $image)
                                                                                                @if ($primero == true)
                                                                                                    <div class="carousel-item active">
                                                                                                        <img src="{{$image['image_folder']}}" width="180px" height="350px" class="d-block w-100" alt="...">
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="carousel-item">
                                                                                                        <img src="{{$image['image_folder']}}" width="180px" height="350px" class="d-block w-100" alt="...">
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </div>
                                                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselItemsCaptions" data-bs-slide="prev">
                                                                                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                          <span class="visually-hidden">Previous</span>
                                                                                        </button>
                                                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselItemsCaptions" data-bs-slide="next">
                                                                                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                          <span class="visually-hidden">Next</span>
                                                                                        </button>
                                                                                    </div>  
                                                                                </div> 
                                                                                <div class="col">
                                                                                    <div class="row">
                                                                                        <p>{{$product->notes}}</p>
                                                                                    </div>
                                                                                    <hr>
                                                                                    @if ($product->id_type == "Inventory Part")
                                                                                        <div class="row">
                                                                                            <h4>Available Colors</h4>
                                                                                            <br>
                                                                                            <div class="row align-items-center">
                                                                                                @foreach ($colors as $color)
                                                                                                    @php
                                                                                                        $color_element = Colors::where('id', $color['id_color'])->value('description');
                                                                                                    @endphp
                                                                                                    <button class="btn btn-sm button-colors">{{$color_element}} <label class="circleBase type1" style="background-color: {{$color_element}};"></label></button>
                                                                                                @endforeach                                                                                        
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="row">
                                                                                            <h4>Available Size</h4>
                                                                                            <br>
                                                                                            <div class="row align-items-center">
                                                                                                @foreach ($sizes as $size)
                                                                                                    @php
                                                                                                        $word = "";
                                                                                                        $size_element = Sizes::where('id', $size['id_size'])->value('description');
                                                                                                        $str_array = explode(" ", $size_element);
                                                                                                        foreach ($str_array as $str) {
                                                                                                            $word .= substr($str, 0, 1);
                                                                                                        }
                                                                                                        
                                                                                                    @endphp
                                                                                                    <button class="btn btn-sm button-sizes"><b style="font-size: 35px">{{$word}}</b> <br> {{$size_element}}</button>
                                                                                                @endforeach                                                                                        
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        
                                                                                    @endif
                                                                                </div>   
                                                                            </div>                                           
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $index++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
<style>
    td.btnplus{
        top:50%;left:15px;
        height:1em;
        width:1em;
        margin-top:-9px;
        display:block;
        color:white;
        border:.15em solid white;
        border-radius:1em;
        box-shadow:0 0 .2em #444;
        box-sizing:content-box;
        text-align:center;
        text-indent:0 !important;
        font-family:"Courier New",Courier,monospace;
        line-height:1em;
        content:"+";
        background-color:#31b131;
    }

    td.btnminus{
        top:50%;left:15px;
        height:1em;
        width:1em;
        margin-top:-9px;
        display:block;
        color:white;
        border:.15em solid white;
        border-radius:1em;
        box-shadow:0 0 .2em #444;
        box-sizing:content-box;
        text-align:center;
        text-indent:0 !important;
        font-family:"Courier New",Courier,monospace;
        line-height:1em;
        content:"-";
        background-color:#d33333;
    }

    .circleBase {
        border-radius: 50%;
        behavior: url(PIE.htc); /* remove if you don't care about IE8 */
    }

    .type1 {
        width: 30px;
        height: 30px;
    }

    .button-colors{
        width: 75px; 
        height: 75px;
        background-color: #F8F9FA; 
        border: solid 1px lightgray;
        font-size: 16px;
        font-weight: bold;
        align-items: center;
        color: #444444;
    }

    .button-sizes{
        width: 100px; 
        height: 82px;
        background-color: #F8F9FA; 
        border: solid 1px lightgray;
        font-size: 16px;
        font-weight: bold;
        align-items: center;
        color: #444444;
    }
</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
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

    function cambioBtn(objeto) {
        if($(objeto).html() == "+"){
            $(objeto).removeClass('btnplus');
            $(objeto).html("-"); 
            $(objeto).addClass('btnminus');
        }
        else{
            $(objeto).removeClass('btnminus');
            $(objeto).html("+"); 
            $(objeto).addClass('btnplus');
        }
    }

    function editar(id) {
      window.location = "/inventories/"+id+"/edit";
    }

    function eliminar(id) {
        Swal.fire({
            title: 'Do you want to delete the item?',
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Cancelar`,
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "/operations/item/delete/"+id,
                        data:{},
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                        success: function (data) {
                            Swal.fire('Deleted!', '', 'success')
                            location.reload();
                        }
                    });               
                }
        })
    }

</script>
@stop