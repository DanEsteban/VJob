<!DOCTYPE html>
<html>
<head>
    <title>Firma Electrónica</title>
</head>
<body>
    <form action="{{ route('firma') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="documento">Documento:</label>
        <input type="file" name="documento" id="documento">
        <br>
        <br>
        <hr>
        <label for="clave_acceso">Clave de Acceso:</label>
        <input type="text" name="clave_acceso" id="clave_acceso">
        <br>
        <br>
        <hr>
        <button type="submit">Firmar</button>
    </form>
</body>
</html>