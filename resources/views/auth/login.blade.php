<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Visual Job">
    <meta name="author" content="Carlos Espinoza">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <link href="/css/login.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/08def23c06.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Prompt:wght@700&display=swap" rel="stylesheet">
    <title>Iniciar Sesión</title>
</head>
<body>
    <img style="width: 200px; height: 100px;" src="/img/logo_vJOB.png" alt="Company Logo">
    <div class="container-form">
        <div class="information">
            <div class="info-childs">
                <h2>Bienvenido</h2>
                <p>Si aún no tienes una cuenta por favor registrate aquí.</p>
                <a href="/register"><input type="button" value="Registrarse"></a> 
            </div>
        </div>
        <div class="form-information">
            <div class="form-information-childs">
                <h2>Iniciar Sesión</h2>
                <form method="POST" action='{{route("login")}}'>
                    @csrf
                    <label class="gp-label">
                        <i class="fa-solid fa-user"></i>
                        <input class="gp-label-input" type="text" name="email" placeholder="Usuario" required autofocus>
                    </label>
                    <label class="gp-label">
                        <i class="fa-solid fa-lock"></i>
                        <input class="gp-label-input" type="password" name="password" placeholder="Contraseña">
                    </label>
                    <input class="btn-registro" type="submit" value="Entrar">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

