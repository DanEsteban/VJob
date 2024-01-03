<?php
namespace App\Models;
//require __DIR__ . '/vendor/autoload.php';


use Exception;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Ticket
{
    function imprimirTicket(){

        $nombre_impresora = "ImpresoraTermica";
        $connector = new WindowsPrintConnector($nombre_impresora);

        $printer = new Printer($connector);

        
        /* Start the printer */

        $printer -> text("Prueba de impresion exitosa!\n");
        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();
        
        $printer -> close();
    }   
}

class Item {
    public $name;
    public $price;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    public function __toString() {
        return "<td>{$this->name}</td><td>{$this->price}</td>";
    }
}

