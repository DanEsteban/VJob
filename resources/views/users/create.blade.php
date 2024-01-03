@extends('adminlte::page')

@section('title', 'New User')

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white mt-3">
                <h2>New User</h2>
            </div>
        </div>
    </div>
    
    <br>
@stop

@section('content')
   <div class="card">
        <div class="card-body">
            <center>
                <form action="{{route('users.store')}}" enctype="multipart/form-data" method="POST" id="form">
                    @csrf
                        <br>  

                        @error('name')
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ $message }}
                            </div>
                        @enderror

                        @error('email')
                             <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{ $message }}
                             </div>
                         @enderror

                       <!-- User name group -->
                       <div class="col-md-5">
                           <div class="input-group">
                               <label for="user_name" class="col-sm-4 col-form-label form-control-sm">Name:</label>
                               <input id="user_name" name="user_name" type="text" autocomplete="off" class="form-control" tabindex="1" value="{{old('user_name')}}" required/>                          
                            </div>
                       </div>
       
                       <br>
       
                       <!-- User email group -->
                       <div class="col-md-5">
                           <div class="input-group">
                               <label for="user_email" class="col-sm-4 col-form-label form-control-sm">Email:</label>
                               <input id="user_email" name="user_email" type="email" autocomplete="off" class="form-control" tabindex="2" value="{{old('user_email')}}" required/>
                            </div>
                       </div>

                       <br>
       
                       <!-- User password group -->
                       <div class="col-md-5">
                           <div class="input-group">
                               <label for="user_pass" class="col-sm-4 col-form-label form-control-sm">Password:</label>
                               <input id="user_pass" name="user_pass" type="password" autocomplete="off" class="form-control" tabindex="3" required/>
                           </div>
                       </div>
       
                       <br>
       
                       <!-- User password confirmation group -->
                       <div class="col-md-5">
                           <div class="input-group">
                               <label for="user_repass" class="col-sm-4 col-form-label form-control-sm">Re-Enter Password:</label>
                               <input id="user_repass" name="user_repass" type="password" autocomplete="off" class="form-control" tabindex="4" required autocomplete="user_pass"/>
                           </div>
                       </div>
                       <div id="div_pass" hidden>
                           <div class="card col-md-5">
                               <label class="label danger">&nbsp; Password does not match</label>
                           </div>
                       </div>
                       
                       <br>
       
                       <!-- User rol select group -->
                       <div class="col-md-5">
                           <div class="input-group">
                               <label for="user_role" class="col-sm-4 col-form-label form-control-sm">Asing Role:</label>
                               <select id="user_role" name="user_role" class="form-select" aria-label=".form-select" tabindex="5" required>
                                  <option selected disabled="required" value="">Choose...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                               </select>
                           </div>
                       </div>
       
                       <br>

                       <div class="col-md-5">
                            <div class="input-group">
                                <label for="formFile" class="col-sm-4 col-form-label form-label form-control-sm">User Image:</label>
                                <input name="user_files[]" class="form-control" type="file" id="formFile">
                            </div> 
                       </div>

                       <br>
       
                       <div class="card-footer">
                   
                           <br>
               
                           <center>
                               <button type="submit" onclick="return validation();" class="btn btn-sm btn-primary" style="width:100px;" tabindex="6">Save</button>
                               <button type="button" onclick="cancel();" class="btn btn-sm btn-danger" style="width:100px;" tabindex="7">Cancel</button>
                           </center>
                       </div>
                   </form>
            </center>
        </div>
        
        <br>

   </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
<!-- For Label advise -->
<style>

</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    function validation() {
        var pass = $('#user_pass').val();
        var repass = $('#user_repass').val();
        if(pass == repass){
            $('#div_pass').attr('hidden', true);
            return true;
        }
        else{
            $('#div_pass').removeAttr('hidden');
            return false;
        }
    }

    function cancel() {
        window.location = "/users";
    }   

    $(function () {
        $('#users-id').children().addClass('active');
    })
 </script>
@stop