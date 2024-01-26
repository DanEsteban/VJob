{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}

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
    {{-- <link href="/css/configuration.view.css" rel="stylesheet"> --}}
    <link href="/css/login.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/08def23c06.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Prompt:wght@700&display=swap" rel="stylesheet">
    <title>Registrarse</title>
</head>
<body>
    <img style="width: 200px; height: 100px;" src="/img/logo_vJOB.png" alt="Company Logo">
    <div class="container-form">
        <div class="information">
            <div class="info-childs">
                <h2>Bienvenido</h2>
                <p>Para unirte a nuestra comunidad por favor inicia sesión con tus datos.</p>
                <a href="/login"><input type="button" value="Iniciar Sesión"></a>
            </div>
        </div>
        <div class="form-information">
            <div class="form-information-childs">
                <h2>Crear una Empresa</h2>
                <p>Para poder crear una cuenta, necesitas comunicarte con el departamento de ventas y te entregarán un código para poder registrar tu empresa y usuarios.</p>
                <form>
                    <label class="gp-label">
                        <i class="fa-solid fa-user"></i>
                        <input class="gp-label-input" type="text" name="codigoRegistro" placeholder="Código de Registro">
                    </label>
                    <input class="btn-registro" type="button" onclick="verificarCodigo()" value="Registrarse">
                </form>
            </div>
        </div>
    </div>

    <script>
        function verificarCodigo() {
            var codigoRegistro = $("#codigoRegistro").val();
            if (codigoRegistro === "") {
                $.ajax({
                    type: 'GET',
                    url: '/operations/verificarCodigo', 
                    success: function (data) {
                        // Manejar la respuesta del controlador (data)
                        console.log('Respuesta del controlador:', data);
                    },
                    error: function (error) {
                        console.error('Error al comunicarse con el controlador:', error);
                    }
                });        
            }
            else{
                alert('Debe ingresar un código valido de registro'); 
            }
        }
    </script>
</body>
</html>