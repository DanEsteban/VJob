<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
    <title>Flowerist Suppliers</title>
</head>
<body>
    @php
        session_start();
        if (isset($vendor) || isset( $_SESSION['vendor'])) {
          if (isset($vendor)) {
            $_SESSION['vendor'] = $vendor;
          }  
        }
    @endphp

    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif

@if (isset($vendor) || isset( $_SESSION['vendor']))
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand">
            <img src="/img/logo.png" width="30px">
            Flowerist
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/vendors/access/api/main">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Order Center
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="/vendors/access/api/{{$_SESSION['vendor']}}">List</a></li>
                  <li><a class="dropdown-item" href="/vendors/access/api/create">Create</a></li>
                </ul>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="http://system.floweristadmin.us/">Log out</a>
                </li>
              </li>
            </ul>
          </div>
        </div>
    </nav>

      <center>
        <p style="font-size: 30px; color: green"><b>Welcome {{$_SESSION['vendor']}}</b></p>
    </center>
    <br>
    <div class="card">
        <div class="card-body">
            <br>
            <center>
                <div id="efecto" class="efecto">
                    <img src="/img/Logo-web-1.png" alt="Company Logo">
                </div>
            </center>
            <br>
            <br>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <center>
        <p style="color: green">Powered by <img src="/img/ISOTIPO.png" width="30px" alt="isotipo_logo"> Visual Job</p>
    </center>
@else
    @php
        header("Location: /vendors/access/api/page");
        exit();
    @endphp
@endif
</body>
</html>

