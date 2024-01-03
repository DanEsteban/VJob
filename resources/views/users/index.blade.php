@extends('adminlte::page')

@section('title', 'Users')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')  
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>Users</h2>
            </div>

            <div class="col-md-2 mt-3">
                <a class="btn btn-outline-danger" style="width:160px;" href="/users/create"><i class="fa fa-plus"></i> New User</a>
            </div>
        </div>
    </div>
@stop

@section('content')
    @livewireStyles

    <!-- Receive parameter information from controller -->
    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif

    <!-- Render from users-index.blade.php -->
    @livewire('users-index')

    @livewireScripts
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/general.actions.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.deletebtn').on('click', function(){
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#delForm').attr("action", "users" + "/" + id);
            })
        });
    });
    
    $(document).ready(function(){
        $('.editbtn').on('click', function(){
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#editForm').attr("action", "users" + "/" + id);
            })
        });
    });

    $(document).ready(function(){
        $("#editModal").on('shown.bs.modal', function(event){
            var user = $(event.relatedTarget);
            let id = user.data('id');
            let name = user.data('name');
            let email = user.data('email');
            let role = user.data('role');
            $(this).find('#user_id').val(id);
            $(this).find('#user_name').val(name);
            $(this).find('#user_email').val(email);
            $(this).find('#user_role').val(role);
            $.ajax({
                type:'GET',
                dataType:'text',
                url:'/operations/user/image/' + id,
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    if(data){
                        $("#user_image").attr('src', data);
                    }
                }
            })
        });
    });

    $(document).ready(function () {
        $('#dTable').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            select: {
                style: 'single'
            },
        });
    });

    function validationPass() {
        var pass = $('#user_pass').val();
        var repass = $('#user_repass').val();
        if(pass && repass == null){
            return true;
        }
        else if(pass == repass){
            return true;
        }
        else if(pass != repass){
            return false
        }
    }
  </script>
@stop