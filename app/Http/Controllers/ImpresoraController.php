<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ImpresoraController extends Controller
{
    public function imprimirTicket()
    {
        
        // Renderiza la vista con las variables
        $pagina = view('cashier.index')->render();

        // Llama a la función de impresión
        $this->imprimirContenido($pagina);

        // Puedes redirigir al usuario a alguna página después de la impresión
        return redirect()->back();
    }

    private function imprimirContenido($contenido)
    {
        $nombre_impresora = "ImpresoraTermica";

        // Crear el conector e imprimir
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        // Imprimir el contenido capturado
        $printer->text($contenido);

        $printer->feed();
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
}