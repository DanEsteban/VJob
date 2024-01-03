<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DOMDocument;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('firma.index');
    }

    public function firmarXML(Request $request)
    {
        $archivo = $request->file('documento');
        $nombreArchivo = $archivo->getClientOriginalName();
        //$rutaArchivoXML = "documentos/" . $nombreArchivo;
        $rutaArchivoXML = "http://localhost/flowerist/public/documentos/0607202301171081871500120020010000011290100124919.xml";
        
        // URL de la API en ASP.NET Core para recibir el archivo
        $apiUrl = "https://localhost:44356/api/facturacion/FirmaXml";

        $opensslCnf = 'D:\xampp\apache\conf\openssl.cnf';
        // Crear una nueva instancia de cURL
        $ch = curl_init();
        // Configurar la solicitud POST con el archivo XML
        $postData = array(
            'xmlFile' => $rutaArchivoXML,
            'nombreArchivo' => $nombreArchivo
        );

        $data = http_build_query($postData);

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
      
        curl_setopt($ch, CURLOPT_CAINFO, $opensslCnf);
        // Ejecutar la solicitud cURL y guardar la respuesta
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // Comprobar si hubo errores en la solicitud
        if (curl_errno($ch)) {
            // Manejar el error apropiadamente
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        } else {
            // Manejar la respuesta de la API ASP.NET Core
            echo 'Respuesta de la API: ' . $response;
        }

        // Cerrar la conexión cURL
        curl_close($ch);

        
    }
}