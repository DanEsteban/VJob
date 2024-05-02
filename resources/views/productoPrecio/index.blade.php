@extends('adminlte::page')

@section('title', 'Producto Precio')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)



@section('content_header')
    <div class="container-fluid bg-white shadow" style="min-height: 5rem;">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 mt-3 mb-3">
                <div class="bg-white">
                    <h2>Producto-Precio</h2>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-3 mb-3">
                <button onclick="newProduct();" type="button" class="btn btn-outline-danger btn-block mb-3">
                    <i class="fa fa-plus"></i> Nuevo Producto
                </button>
            </div>
        </div>
    </div>

@stop


@section('content')

    @php
        use App\Models\Customers;
        use App\Models\Impuesto;
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
                                <th>Si IVA</th>
                                <th hidden></th>
                                <th>IVA</th>
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
                                        <button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
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
                                    <td class="text-center">
                                        <input id="siIvafront" onclick="actualizarEstado(this)" type="checkbox"  {{ $resultado['si_iva'] ? 'checked' : '' }} disabled>
                                    </td> 
                                    <td hidden>
                                        <input id="siIva" type="hidden" value={{$resultado['iva']}}>  
                                    </td>     
                                    <td>
                                        <select id="iva" class="form-control form-control-sm" disabled>

                                            @if($resultado['si_iva'] == 1 && $resultado['iva'] == 1)
                                                <option value="{{ $impuestoActual['id'] }}">{{ $impuestoActual['porcentaje'] }}</option>
                                                @foreach ($p_impuestos as $impuesto)
                                                    @if ($impuestoActual['id'] != $impuesto['id'])
                                                        <option value="{{ $impuesto['id'] }}">{{ $impuesto['porcentaje'] }}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @php
                                                    $porcentaje = Impuesto::where('id', $resultado['iva'])->value('porcentaje');
                                                @endphp
                                                <option value="{{ $resultado['iva'] }}">{{ $porcentaje }}</option>
                                                @foreach ($p_impuestos as $impuesto)
                                                    @if ($resultado['iva'] != $impuesto['id'])
                                                        <option value="{{ $impuesto['id'] }}">{{ $impuesto['porcentaje'] }}</option>
                                                    @endif
                                                @endforeach 
                                            @endif

                                        </select>
                                    </td>
                                    
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
                                    <td><input type="text" id="pvp1" value="{{ number_format($resultado['pvp1'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad2" onchange="cambioCantidad(this);" value="{{ number_format($resultado['cantidad2'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>    
                                    <td><input type="text" id="pvp2" value="{{ number_format($resultado['pvp2'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad3" onchange="cambioCantidad(this);" value="{{ number_format($resultado['cantidad3']) }}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="pvp3" value="{{ number_format($resultado['pvp3'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="cantidad4" onchange="cambioCantidad(this);" value="{{ number_format($resultado['cantidad4'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>
                                    <td><input type="text" id="pvp4" value="{{ number_format($resultado['pvp4'], 2, '.', '') }}" class="form-control form-control-sm" disabled></td>
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
                    <button onclick="guardar();" type="button" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
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

    function cambioCantidad(input) {
        // Obtener el valor del campo de cantidad
        var cantidad = $(input).val();
        
        // Obtener el valor del campo adyacente (pvp)
        var pvpInput  = $(input).closest('td').next().find('input[type="text"]');
        
        // Obtener el valor del campo adyacente (pvp)
        var pvp = pvpInput.val();

        if (pvp.trim() === '') {
            Swal.fire({
                icon: "warning",
                text: "No puede haber una cantidad sin su respectivo precio!",
                didClose: function() {
                    // Establecer nuevamente el foco en el campo de PVP
                    pvpInput.focus();
                }
            });
        } else {
            // Establecer el foco en el campo de PVP si no hay problemas
            pvpInput.focus();
        }

    }

    function guardar() {

        $('#tb_items tr td input[name="siIva[]"]').each(function() {
            var $input = $(this);
            var $td = $input.closest('td');
            if ($input.val() == 0) {                
                var $iva = $td.next().find('select[name="iva[]"]');
                $iva.prop("disabled", false);
            }

            var $codigoBarra =  $td.next().next().find('input');
            var $pvp1 =  $td.next().next().next().next().find('input');
            console.log($codigoBarra.val());
            if ($codigoBarra.val() != "") {

                if($pvp1.val() != "" ){
                    $("#formulario").submit();
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No puede guardar un producto si no tiene un precio!",
                    });
                }  
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No puede guardar un producto sin un codigo de barras",
                });
            }
        });
    }

    function comprobacionbarCode(objeto) {
        console.log(objeto)
        var tr = $(objeto).closest('tr')[0];
        var filaActual= $(tr).addClass('fila-Actual');
        var tabla = $('#dTable').DataTable();
        var allRows = tabla.rows().nodes();
        var filasSinClase = $(allRows).filter('tr:not(.fila-Actual)');
        var valor = objeto.value;
        filasSinClase.each(function() {
            var fila = $(this);
            var valorFila = fila.find('td:eq(9) input').val();
            if (valorFila === valor && valorFila != "") {
                Swal.fire({
                    title: 'Ya existe ese codigo de Barras', 
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        //var producto = $(tr).find('td:eq(3) input');
                        //producto.val("");
                        objeto.value = "";
                        objeto.focus();
                    }
                });
            }
        });

        let removerClaseFila= $(allRows).filter('tr.fila-Actual').removeClass('fila-Actual');
    }

    function newProduct() {
        
        if ($("#dTable tbody tr").prop('innerText') == 'No data available in table') {
            $("#dTable tbody tr").remove();
            let url = "/elements/priceProduct/row";
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'html',
                async: 'false',
                data: {},
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success: function(data) {
                    $('#tb_items').prepend(data);
                }
            });
        }
        else{
            let primerTd = $("#dTable tbody tr:first td:nth-child(3) input").val().trim()

            if (primerTd !== '') {
                let url = "/elements/priceProduct/row";
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'html',
                    async: 'false',
                    data: {},
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success: function(data) {
                        $('#tb_items').prepend(data);
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Solo se puede ingresar un producto a la vez!",
                });
            }
        }
    }

    function filtrarLinea(object){
        $.ajax({
                type:'POST',
                dataType: 'json',
                url: '/operations/linea',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    nombre: object.value
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    //console.log(data)
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

    function delRow(object) {

        var tr = $(object).closest('tr')
        var id = $(object).closest('tr').find('td:eq(2) input').val();

        Swal.fire({
            title: 'Esta seguro de borrar el Producto?',
            showDenyButton: true,
            confirmButtonText: 'Borrar',
            denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) { 
                $.ajax({
                    type:'POST',
                    dataType: 'json',
                    url: 'productoPrecio/delete/' + id,
                    async: false,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success : function(response){
                        if (response.success) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success",
                                title: response.message
                            }); 
                            tr.remove();
                        } else {
                            alert(response.message); 
                        }
                    }

                });
            }
        })
    }

    function editRow(object) {
        let tr = $(object).closest('tr'); 
        tr.find("td:not(:eq(2)) input, select").prop("disabled", false); // Habilita los <input> en todas las celdas excepto en la tercera

        let nombres = ['','', 'id[]', 'producto[]', 'type[]', 'group[]', '', 'siIva[]', 'iva[]', 'codigoBarras[]', 'medida[]', 'pvp1[]', 'cantidad2[]',
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

    function actualizarEstado(object) {

        let fila = $(object).closest('tr') 
        let si_iva = fila.find('#siIva')
        let iva = fila.find('#iva')

        if(object.checked){
            //console.log('si entra')
            si_iva.val('1')
            iva.prop("disabled", false)
            
            let url = "/operations/iva";
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: 'false',
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    //console.log(data)
                    iva.val(data.id)
                }
            });

        }else{
            //console.log('si entra al desclick')
            si_iva.val('0');
            iva.val('1');
            iva.prop("disabled", true);
        }

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