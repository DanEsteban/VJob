@extends('adminlte::page')

@section('title', 'Pantalla de Inicio')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white  mt-3">
            <h2>INICIO <span style="font-weight: lighter;">/ Información resumida de tu gestión</span></h2>
            @if(app('cadenaConexion'))
                <p>El valor de CadenaConexion es: {{ app('cadenaConexion') }}</p>
                <p>El valor de Configuracion es: {{ var_dump(app('configuracion')) }}</p>
            @endif
        </div>
    </div>
</div>

<script type="text/css">
        h2{
            font-weight: lighter;
        }
        span{
            font-weight: bold;
        }
</script>
@stop

@section('content')

@stop

@section('footer')
    <div class="ml-4 text-sm text-gray-500 sm:text-right sm:ml-0">
        <img src="../img/ISOTIPO.png" width="30px" alt="isotipo_logo"> Copyright © 2022-2024 Visual Job. All rights reserved.
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">


@stop

@section('js')
<script src="/js/bootstrap.bundle.min.js"></script>

