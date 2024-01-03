<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use App\Models\Invoices;
use App\Models\Inventories;
use App\Models\Customers;
use App\Models\PaymentsDetails;
use App\Models\Products;
use App\Models\Vendors;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesreport(){
        $month = intval(date('m'));
        $year = date('Y');
        $invoices = Invoices::whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        
        return view('reports.sales', compact('invoices'));
    }

    public function findSales(Request $request){
        $invoices = Invoices::whereDate('created_at', '>=', $request->from)->whereDate('created_at', '<=', $request->to)
                                ->get();

        foreach ($invoices as $invoice) {
            $customer = Customers::where('id', $invoice->id_customer)->value('company_name');  
            $invoice->id_customer = $customer; 
        }

        return json_encode($invoices);
    }

    public function productreport(){
        $items = Products::where('id_type', 2)->get();

        return view('reports.product', compact('items'));
    }

    public function findProduct(Request $request){
        $transactions = array();
        $id_item = Products::where('item_name', $request->product)->value('id');
        $inventories = Inventories::where('id_item', $id_item)->whereDate('created_at', '>=', $request->from)
                                ->whereDate('created_at', '<=', $request->to)->get();

        foreach ($inventories as $inventory) {
            $number = null;
            $csorvn = null;
            if ($inventory->type == "Invoice") {
                $number = Invoices::where('id', $inventory->id_transaction)->value('number');
                $id_customer = Invoices::where('id', $inventory->id_transaction)->value('id_customer');
                $csorvn = Customers::where('id', $id_customer)->value('company_name');
            } else {
                $number = Bills::where('id', $inventory->id_transaction)->value('number');
                $id_customer = Bills::where('id', $inventory->id_transaction)->value('id_vendor');
                $csorvn = Vendors::where('id', $id_customer)->value('name');
            }
            

            $transactions[] = [
                'type' => $inventory->type,
                'date' => date('Y-m-d', strtotime($inventory->created_at)),
                'Number' => $number,
                'name' => $csorvn,
                'qty' => $inventory->qty
            ];
        }
        
        return json_encode($transactions);
    }

    public function customereport(){
        $customers = Customers::all();

        return view('reports.customer', compact('customers'));
    }

    public function findCustomer(Request $request){

        if ($request->to == null) {
            $id_customer = Customers::where('company_name', $request->customer)->value('id');

            $invoices = Invoices::select('invoices.date', 'invoices.number', 'invoices.id_customer', 'invoices.total','invoices.status', 'payment_customers.reference', 'payments_details.amount', 'payment_terms.name AS terms', 'payments_details.id AS id_detail', 'payments_details.id_payment', 'payment_customers.date AS paymentdate')
                ->leftJoin('payments_details', 'payments_details.invoice', '=', 'invoices.number')
                ->leftJoin('payment_customers', 'payment_customers.id', '=', 'payments_details.id_payment')
                ->leftJoin('payment_terms', 'payment_terms.id', '=', 'payment_customers.id_term')
                ->where('invoices.id_customer', $id_customer)
                ->whereBetween('invoices.date', [$request->desde, $request->hasta])
                ->groupBy('invoices.date', 'invoices.number','invoices.status','invoices.id_customer', 'invoices.total', 'payments_details.amount', 'payment_terms.name', 'payment_customers.reference', 'payment_customers.date', 'payments_details.id_payment', 'payments_details.id')
                ->distinct()
                ->get();
            
            $groupedInvoices = $invoices->groupBy('number');
            $group = array();
            $is_primero = false;
            
            foreach ($groupedInvoices as $number => $groupinvoice) {
                $customer = Customers::where('id', $groupinvoice[0]->id_customer)->value('company_name');
                $total =  $groupinvoice[0]->total;
                
                if($groupinvoice[0]->status == "Void")
                {
                    $balance = 0;
                    $total = 0;
                }else{
                    $balance =  $total - PaymentsDetails::where('invoice', $groupinvoice[0]->number)->sum('amount');
                }
                
                $terms = array();
                $processedInvoices = array();
            
                foreach ($groupinvoice as $invoice) {
                    if (!in_array($invoice->id_detail, $processedInvoices)) {
                        $terms[] = [
                            'type' => $invoice->terms,
                            'paymentDate' => $invoice->paymentdate,
                            'reference' => $invoice->reference,
                            'amount' => $invoice->amount
                        ];
                        
                        $processedInvoices[] = $invoice->id_detail;
                    }
                }
            
                $group[] = [
                    'date' => $groupinvoice[0]->date,
                    'number' => $number,
                    'customer' => $customer,
                    'status' => $groupinvoice[0]->status,
                    'total' => $total,
                    'balance' => $balance,
                    'formaPago' => $terms
                ];
            }           
            
            return json_encode($group);
        } 
        else {
            $group = array();
            $id_customer = Customers::where('company_name', $request->customer)->value('id');
            $invoices = Invoices::where('id_customer', $id_customer)->whereDate('created_at', '<=', $request->to)
                            ->where('status', 'Pending')->get();

            foreach ($invoices as $invoice) {
                $customer = Customers::where('id', $invoice->id_customer)->value('company_name');
                $total =  $invoice->total;
                if ($invoice->status == "Void") {
                    $balance=0;
                    $total=0;
                }
                else{
                    $balance = $total - PaymentsDetails::where('invoice', $invoice->number)->sum('amount');
                }
                
                $group[] = [
                    
                    'date' => $invoice->date,
                    'number' => $invoice->number,
                    'customer' => $customer,
                    'status' => $invoice->status,
                    'total' => $total,
                    'balance' => $balance
                ];
            }

            return json_encode($group);
        } 
    }
}
