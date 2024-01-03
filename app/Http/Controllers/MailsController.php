<?php

namespace App\Http\Controllers;

use App\Models\CustomizeMail;
use Illuminate\Http\Request;

class MailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:email.index')->only('index'); 
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stage_mod = CustomizeMail::where('type', 'Stage')->first();
        $estimate_mod = CustomizeMail::where('type', 'Estimate')->first();
        $invoice_mod = CustomizeMail::where('type', 'Invoice')->first();
        $payment_mod = CustomizeMail::where('type', 'Payment')->first();
        return view('mails.index', compact('stage_mod', 'estimate_mod', 'invoice_mod', 'payment_mod'));
    }

}
