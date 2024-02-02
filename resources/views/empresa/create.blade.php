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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/08def23c06.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Facturacion</title>
</head>
<body>
    <div class="wrapper">
        <div class="content-wrapper" style="min-height: 569.148px">          
            <div class="content-header">
                <div class="container-fluid bg-white shadow"  style="height: 5rem;">
                    <div class="row align-items-center">
                        <div class="bg-white mt-3">
                            <h2>Nueva Empresa</h2>
                        </div>
                    </div>
                </div>
            <br>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <!--- Form --->
                    <form action="/empresa" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="cs_company" class="col-sm-4 col-form-label form-control-sm" align="left">Nombre:</label>
                                            <input autocomplete="off" id="cs_company" name="cs_company" type="text" class="form-control form-control-sm" tabindex="1" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="ruc" class="col-sm-4 col-form-label form-control-sm" align="left">Ruc:</label>
                                            <input autocomplete="off" id="ruc" name="ruc" type="text" class="form-control form-control-sm" tabindex="2" value="{{$ruc}}" required/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="cs_phone" class="col-sm-4 col-form-label form-control-sm" align="left">Telefono:</label>
                                            <input autocomplete="off" id="cs_phone" name="cs_phone" type="text" class="form-control form-control-sm" tabindex="3"/>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="cs_mail" class="col-sm-4 col-form-label form-control-sm" align="left">Correo:</label>
                                            <input autocomplete="off" id="cs_mail" name="cs_mail" type="text" class="form-control form-control-sm" tabindex="4" value="{{$email}}" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <label class="col-sm-4 col-form-label form-control-sm" for="rutaFirma">Archivo Firma</label>
                                            <input type="file" class="form-control" id="rutaFirma" name="rutaFirma" accept=".p12" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="claveFirma" class="col-sm-4 col-form-label form-control-sm" align="left">Clave Firma:</label>
                                            <input autocomplete="off" id="claveFirma" name="claveFirma" type="text" class="form-control form-control-sm" tabindex="5"/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="tipoContribuyente" class="col-sm-4 col-form-label form-control-sm" align="left">Tipo Contribuyete:</label>
                                            <select id="tipoContribuyente" onchange="newShipTo();" name="tipoContribuyente" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="6">
                                                <option value=""></option>
                                                <option value="0">------------(New)------------</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mt-2">
                                            <label for="direccion" class="col-sm-4 col-form-label form-control-sm" align="left">Direccion:</label>
                                            <textarea id="direccion" name="direccion" rows="4" cols="50" placeholder="Porfavor Ingresar una Direccion" tabindex="7">    
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="nav justify-content-center">
                                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width:100px;" tabindex="8">Guardar</button>
                                        &nbsp; &nbsp;
                                        <button type="button" onclick="salir();" class="btn btn-sm btn-outline-danger" style="width:100px;" tabindex="9">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>                 
            </div> 
        </div> 
        <footer class="main-footer">
            <div class="ml-4 text-sm text-gray-500 sm:text-right sm:ml-0">
                <img src="../img/ISOTIPO.png" width="30px" alt="isotipo_logo"> Copyright © 2022-2024 Visual Job. All rights reserved.
            </div>
        </footer>
    </div>
    <style>
        .content {
            width: 95%;
            max-width: 120rem;
            margin: 0 auto;
        }
        
    </style>
</body>
</html>
