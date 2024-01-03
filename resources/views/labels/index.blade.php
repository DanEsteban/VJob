@extends('adminlte::page')

@section('title', 'Barcodes')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Barcodes</h2>
        </div>
    </div>
</div>
@stop

@section('content')

@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
 function find(objeto) {
    let tr = $(objeto).parent().parent(); 
    let code = $(tr).find('#select_item').val();

    var lista_items  = @json($items);
    var lista_vendors = @json($vendors);
    
    function isMatch(item) {
        return item.item_name === code;
      }
    
    var response = lista_items.find(isMatch);
    let id = response['id'];

    if(id){
        $.ajax({
            type:'GET',
            dataType:'json',
            url:'/operations/item/codebar/' + id,
            async:false,
            data:{},
            error: function (xhr, status, error) {
                    console.log(xhr.responseText);
            },
            success: function (any) {
                if(any.length > 0){
                    const newElement = document.createElement("tr");
                    console.log(any);
                }
            }
        });
    }
 }
</script>
@stop