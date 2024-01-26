<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

date_default_timezone_set('America/Guayaquil');
class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalPayment=0;
        $totalP=0;
        $subtotal=0;
        $taxes = 0;
        $zelle = 0;
        $totalCard = 0;
        $totalCheck =0;
        $totalCash = 0;
        $totalTransfer = 0;
        $total=0;
        $inicioDelDia = date('Y-m-d');
        //$inicioDelDia = '2023-12-18';
        $dsn = "mysql:host=localhost;dbname=empresa1";
        $usuario = "root";
        $contrasena = "";

        try
        {
            $conexion = new \PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT * FROM invoices WHERE date = '{$inicioDelDia}'";
            $result= $conexion->query($consulta);
            $invoices = $result->fetchAll(\PDO::FETCH_ASSOC);
            

            $consulta2 = "SELECT A.id, A.date as fecha1, A.amount as pago1,
                    B.date as fecha2, B.id_term, B.valor,
                    C.num_doc_sri, C.date as fechafac, C.total as totfac 
                    FROM payment_customer A
                    JOIN payment_details B ON A.id = B.id_payment
                    JOIN invoices C ON A.invoice = C.number
                    WHERE C.date = :inicioDelDia";

            $statement = $conexion->prepare($consulta2);
            $statement->bindParam(':inicioDelDia', $inicioDelDia);
            $statement->execute();

            $payments = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($payments as $payment) {
                if($payment['fecha1'] === $payment['fechafac'])
                {
                    $valor = $payment['valor'];
                    switch ($payment['id_term']) {
                        case 1:
                            $totalCash += $valor;
                            break;
                        case 2:
                            $totalTransfer += $valor;
                            break;
                    }
                }
                /* 
                    else{         
                        $valor = $payment['valor'];
                        switch ($payment['id_term']) {
                            case 1:
                                $totalCash += $valor;
                                break;
                            case 2:
                                $totalTransfer += $valor;
                                break;

                            default:
                                $unpayment += $valor;
                                break;
                        }
                    }
                 */
            }
            $totalPayment = $totalCash + $totalTransfer;
            return view('cashier.index', compact('invoices', 'inicioDelDia','totalCash','totalTransfer','totalPayment'));

        }catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        } 
    }

    public function imprimirTicket(Request $request)
    {
        //return $request;
        // Variables para la impresion
        $invoices = $request->input('invoices');
        $inicioDelDia = $request->input('inicioDelDia');
        $totalCash = $request->input('totalCash');
        $totalTransfer = $request->input('totalTransfer');
        $totalPayment = $request->input('totalPayment');

        /////////////////////////////////////////////////

        $nombre_impresora = "ImpresoraTermica";

        // Crear el conector e imprimir
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);
        
        // Cabecera de la factura
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("================================\n");
        $printer->text("Cierre de Caja\n");
        $printer->text("================================\n\n");

        $printer->text("Fecha: " . $inicioDelDia . "\n\n");
        
        // Tabla de ventas
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Ventas del Día\n");
        $printer->text("================================\n");
        $printer->text("TD  Documento  Sub   IVA  Total\n");
        $printer->text("--------------------------------\n");

        foreach ($invoices as $item) {
            if ($item['tipo_documento'] === 0) {
                $tdoc = 'NV';
            } else {
                $tdoc = 'FC';
            }
            $formattedSubtotal = '$' . number_format($item['subtotal'], 2);
            $formattedTaxes = '$' . number_format($item['taxes'], 2);
            $formattedTotal = '$' . number_format($item['total'], 2);
        
            $printer->text(sprintf("%-5s%-9s%-7s%-6s%-9s\n", $tdoc, substr($item['num_doc_sri'], -8), $formattedSubtotal, $formattedTaxes, $formattedTotal));
        }
        $totalFact = number_format(array_sum(array_column($invoices, 'total')), 2);
        // Pie de página
        $printer->text("--------------------------------\n");
        $printer->text("Subtotal: $" . number_format(array_sum(array_column($invoices, 'subtotal')), 2) . "\n");
        $printer->text("IVA:      $" . number_format(array_sum(array_column($invoices, 'taxes')), 2) . "\n");
        $printer->text("Total:    $" .  $totalFact . "\n\n");


        // Tabla de Cobranzas del dia
        $printer->text("Cobranzas del dia\n");
        $printer->text("================================\n");
        $printer->text("Forma de Pago      Monto\n");
        $printer->text("--------------------------------\n");
        $printer->text(sprintf("%-20s$%-10s\n", 'Efectivo', number_format($totalCash, 2)));
        $printer->text(sprintf("%-20s$%-10s\n", 'Transferencia', number_format($totalTransfer, 2)));    
        $printer->text(sprintf("%-20s$%-10s\n", 'Credito', number_format(($totalFact-$totalPayment), 2)));

        // Pie de página
        $printer->text("\n--------------------------------\n");
        $printer->text("Total Pagos: $" .   $totalFact . "\n");

        // Cortar el papel
        $printer->cut();

        // Cerrar la conexión con la impresora
        $printer->close();
    }

}