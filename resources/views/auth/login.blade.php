<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Visual Job">
    <meta name="author" content="Carlos Espinoza">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/configuration.view.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/08def23c06.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>

    <body>
        @php
            
        @endphp
        <x-guest-layout>
            <x-jet-authentication-card>
                <x-slot name="logo">
                    
                </x-slot>
        
                <x-jet-validation-errors class="mb-4" />
        
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                    <div style=" background-color: grey">
                        <img style="width: 350px; height: 190px;" src="../img/logo_vJOB.png" alt="Company Logo">
                    </div>
                    <br>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
        
                    <div>
                        <x-jet-label style="font-family: 'Prompt', sans-serif;" for="user" value="{{ __('Usuario') }}" />
                        <x-jet-input id="user" class="block mt-1 w-full" type="text" name="user" required autofocus />
                    </div>
        
                    <div class="mt-4">
                        <x-jet-label style="font-family: 'Prompt', sans-serif; font-size: 14px" for="password" value="{{ __('Password') }}" />
                        <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>
        
                    <div class="flex items-center justify-end mt-4">
                        <x-jet-button onclick="loguear();" type="button" class="ml-4">
                            {{ __('Log in') }}
                        </x-jet-button>
                        
                        <x-jet-button onclick="crearEmpresa();" type="button" class="ml-4">
                            {{ __('Crear Empresa') }}
                        </x-jet-button> 
                    </div>
                </form>
            
            </x-jet-authentication-card>
        </x-guest-layout>
        
        <!-- Modal -->
            <div class="modal fade" id="verificarCodigo" tabindex="-1" role="dialog" aria-labelledby="verificarCodigo" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="popModalTitle">VisualJob</h5>
                        <button onclick="cerrarmodal();" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <form method="POST" action="/empresa/crearEmpresa">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-form-label">Codigo</label>
                                    <input type="text" autocomplete="off" class="form-control" id="codigo" name="codigo" required>
                                </div>
                                <div class="modal-footer">
                                    <x-jet-button type="submit" class="ml-4">
                                        {{ __('Verificar') }}
                                    </x-jet-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->

        <script>
            function loguear() {
                let usuario = $("#user").val();
                let password = $("#password").val();
                $.ajax({
                    type:'POST',
                    url: '/operations/verificar/login',
                    dataType: 'json',
                    async: "false",
                    data: {"_token": "{{ csrf_token() }}", usuario: usuario, password: password},
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success : function(data){
                        if (data.success) {                    
                            window.location.href = '/dashboard'; 
                        } 
                        else {                            
                            alert('Error: ' + data.message); 
                        }
                    }
                });
            }
            function crearEmpresa() {    
                $('#verificarCodigo').modal('show');        
            }
            function cerrarmodal() {
                $('#verificarCodigo').modal('toggle');
            }


        </script>
    </body>
</html>

