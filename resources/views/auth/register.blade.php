

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
    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif
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
                <form id="formVerificar" method="POST" action="{{ route('operation.verificarCodigo') }}">
                    @csrf
                    <label class="gp-label">
                        <i class="fa-solid fa-user"></i>
                        <input class="gp-label-input" type="text" id="codigoRegistro" name="codigoRegistro" placeholder="Código de Registro">
                    </label>
                    <button class="btn-registro" type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </div>

    <script>

    </script>
</body>
</html>