@extends('adminlte::page')

@section('title', 'Assign role')

@section('content_header')
    <div class="card shadow">
        <div class="card-header bg-white">
          <h2>Assign role</h2>
        </div>
    </div>
@stop

@section('content')
    <div class="card">

        <!-- Colective Form with list of roles and relation table-->
        <div class="card-body">
            <p>Name</p>
            <p class="form-control" style="font-size: 20px; font-weight: bold;">{{$user->name}}</p>

            <hr>

            <h2 class="h5">Roles List</h2>
            {!! Form::model($user, ['url' => ['admin/users', $user], 'method' => 'put']) !!}
                @foreach ($roles as $role)
                    <div>
                        <input hidden type="text" name="user_type" value="Assing">
                        <label>
                          {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-1']) !!}
                          {{$role->name}}
                        </label>
                    </div>
                @endforeach

                <hr>

                {!! Form::submit('Assign', ['class' => 'btn btn-success mt-2', 'style' => "width:120px;"]) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
      $(function () {
          $('#users-id').children().addClass('active');
      })
</script>
@stop