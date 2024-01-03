@extends('adminlte::page')

@section('title', 'Producto Precio')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div id="producto-precio" class="container-fluid bg-white shadow" style="min-height: 6rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 col-12 mt-3">
            <h2 class=" text-md-left">Producto-Precio</h2>
        </div>

        <div class="col-md-2 col-12 mt-3 text-center text-md-right">
            <button onclick="newProduct();" type="button" class="btn btn-outline-danger" style="width:100%;">
                <i class="fa fa-plus"></i>  Nuevo Producto
            </button>
        </div>
    </div>
</div>
@stop

@section('content')
    @php
        use App\Models\Customers;
        $anteriorID = null;
    @endphp
    <form action="/productoPrecio" method="POST" id="formulario">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                        <thead class="bg-dark">
                            <tr>
                                <th width="4%"></th>
                                <th width="4%"></th>
                                <th>Codigo</th>
                                <th>Producto</th>
                                <th>Linea</th>
                                <th>Familia</th>
                                <th>IVA</th>
                                <th hidden>siIva</th>
                                <th>Codigo Barras</th>
                                <th>U.M.</th>
                                <th>PVP</th>
                                <th>Cantidad</th>
                                <th>PVP</th>
                                <th>Cantidad</th>
                                <th>PVP</th>
                                <th>Cantidad</th>
                                <th>PVP</th>
                            </tr>
                        </thead>
                        <tbody id="tb_items">
                            @foreach ($resultados as $resultado)                                               
                                <tr id="tr_items">
                                    <td>
                                        <form id="delete" action="/productoPrecio/{{$resultado['id']}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </td>
                                    <td><button onclick="editRow(this);" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></button></td>
                                    <td><input type="text" class="form-control form-control-sm" value="{{$resultado['id']}}" readonly></td>
                                    <td><input type="text" value="{{$resultado['item_name']}}" style="width:auto" class="form-control form-control-sm" disabled></td>
                                    <td>
                                        <select onchange="filtrarLinea(this);" id="type" class="form-control form-control-sm" disabled>
                                        @if(isset($resultado['id_product_type']))
                                            <option value="{{ $resultado['id_product_type'] }}" selected>{{ $resultado['type'] }}</option>
                                        @else
                                            <option value="0">--Seleccione--</option>
                                        @endif
                                        
                                        @foreach ($item_types as $item)
                                            @if ($resultado['id_product_type'] != $item['id'])
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                            @endif
                                        @endforeach 
                                        </select>
                                    </td>
                                    <td>
                                        <select id="group" class="form-control form-control-sm" disabled>
                                            @if(isset($resultado['id_product_group']))
                                                <option value="{{ $resultado['id_product_group'] }}" selected>{{ $resultado['group'] }}</option>
                                            @else
                                                <option value="0">--Seleccione--</option>                                             
                                            @endif 
                                            
                                        </select>
                                    </td>
                                    @if($resultado['iva'] == "1")
                                        <td><input type="checkbox" id="iva" onchange="siIva(this);"  checked disabled></td>
                                        <td hidden><input type="text" id="valorIva" value="1"></td> 
                                    @else
                                        <td><input type="checkbox" id="iva" onchange="siIva(this);" disabled></td>    
                                        <td hidden><input type="text" id="valorIva" value="0"></td>                                            
                                    @endif            
                                    <td><input type="text" id="codigoBarras" onchange="comprobacionbarCode(this);" value="{{$resultado['bar_code']}}" class="form-control form-control-sm" disabled></td>
                                    <td>
                                        <select id="medida" class="form-control form-control-sm" disabled>
                                            @if(isset($resultado['id_unit_measure']))
                                                <option value="{{$resultado['id_unit_measure']}}" selected>{{ $resultado['unit_measure'] }}</option>
                                            @else
                                                <option value="0">--Seleccione--</option>                                             
                                            @endif  
                                            
                                            @foreach ($unit_measures as $unit)
                                                @if ($resultado['id_product_type'] != $unit['id'])
                                                    <option value="{{ $unit['id'] }}">{{ $unit['abbreviation'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>   
                                    </td>
                                    <td><input type="text" id="pvp1" value="{{$resultado['pvp1']}}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad2" value="{{$resultado['cantidad2']}}" class="form-control form-control-sm" disabled></td>    
                                    <td><input type="text" id="pvp2" value="{{$resultado['pvp2']}}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad3" value="{{$resultado['cantidad3']}}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="pvp3" value="{{$resultado['pvp3']}}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad4" value="{{$resultado['cantidad4']}}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="pvp4" value="{{$resultado['pvp4']}}" class="form-control form-control-sm" disabled></td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <center>
                    <button type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
                    <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                </center>
            </div>
        </div>
    </form>
    

@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<style>
    @media (min-width: 758px){
        #producto-precio{
            height: 8rem;
        }
    } 
</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready( function () {  
        $('#dTable').DataTable({
            "paging": true,
            "searching": false,
            "info": false
        });
    });

    function newProduct() {
        let url = "/elements/priceProduct/row";
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            async: 'false',
            data:{},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#tb_items').append(data)
            }
        });
    }

    function filtrarLinea(object){
        $.ajax({
                type:'POST',
                dataType: 'json',
                url: '/operations/linea',
                asinc: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    nombre: object.value
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    let tr=$(object).parent().parent();
                    let select=$(tr).find('td:eq(5)').find('select')[0];
                    while (select.options.length > 0) {
                        select.remove(0);
                    }
                    var optionIni = document.createElement("option");
                    optionIni.value = "0";
                    optionIni.text = "--Seleccione--";
                    select.appendChild(optionIni);
                    // Llenar el datalist con las opciones del array
                    data.forEach(objeto => {
                        var option = document.createElement("option");
                        option.value = objeto.id;
                        option.text = objeto.nombre;
                        select.appendChild(option);
                    });
                }

            });
    }

    function comprobacionbarCode(objeto) {

        var tr = $(objeto).closest('tr')[0];
        var filaActual= $(tr).addClass('fila-Actual');
        
        var tabla = $('#dTable').DataTable();
        var allRows = tabla.rows().nodes();
        var filasSinClase = $(allRows).filter('tr:not(.fila-Actual)');
        var valor = objeto.value;

        filasSinClase.each(function() {
            var fila = $(this);
            var valorFila = fila.find('td:eq(8) input').val();
            console.log(valorFila);
            if (valorFila === valor) {
                Swal.fire({
                    title: 'Ya existe ese codigo de Barras', 
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        //var producto = $(tr).find('td:eq(3) input');
                        //producto.val("");
                        objeto.value = "";
                    }
                });
            }
        });

        let removerClaseFila= $(allRows).filter('tr.fila-Actual').removeClass('fila-Actual');

    }

    function delRow(object) {

        console.log($(object).closest('tr').find('td:eq(2) input').val());
        Swal.fire({
        title: 'Esta seguro de borrar el Producto?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete').submit();
            }
        })
    }

    function editRow(object) {
        let tr = $(object).closest('tr'); 
        tr.find("td:not(:eq(2)) input, select").prop("disabled", false); // Habilita los <input> en todas las celdas excepto en la tercera

        let nombres = ['','', 'id[]', 'producto[]', 'type[]', 'group[]','', 'iva[]', 'codigoBarras[]', 'medida[]', 'pvp1[]', 'cantidad2[]',
        'pvp2[]', 'cantidad3[]', 'pvp3[]','cantidad4[]','pvp4[]']; // Lista de nombres diferentes
        let cadena = nombres.map(function(valor) {
            return "#" + valor ;
        }).join(', ');

        cadena = "'" + cadena + "'";

        tr.find('td').each(function(index) {
            let elemento = $(this).find('input, select'); // Encuentra los elementos dentro del td actual
            if (index < nombres.length) {                
                elemento.attr('name', nombres[index]); // Asigna un nombre de la lista según el índice
            }
        });
        
    }

    function siIva(object) {
        let tr = $(object).closest('tr');
        let valorIva = tr.find("td input#valorIva")[0];
        
        var valor = object.checked;
        if (valor) {
            valorIva.value = "1";
        }else{
            valorIva.value = "0";
        }

        console.log(valorIva.value);

    }

    function salir() {
        Swal.fire({
            title: 'Do you want to exit the form?',
            showDenyButton: true,
            confirmButtonText: 'Exit',
            denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/orders";
                }
            })
    }

</script>
@stop