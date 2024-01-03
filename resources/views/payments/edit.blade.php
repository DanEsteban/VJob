@extends('adminlte::page')

@section('title', 'Payments')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Payments</h2>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    use App\Models\Invoices;
    use App\Models\PaymentsDetails;
@endphp

@if( Session::has('info') )
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ Session::get('info') }}
    </div>
@endif

    <form action="/payments" method="POST">
        @csrf
        @method('UPDATE')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label" style="font-size: 16px">Customer:</label>
                            <input name="customer" onchange="filterInvoice(this.value);" class="form-control form-control-sm" type="text" list="customerList" placeholder="Choose a Customer" style="font-size: 20px" value="{{$current_customer}}">
                            <datalist id="customerList">
                                @foreach ($customers as $customer)
                                    <option value="{{$customer->company_name}}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-1">
        
                    </div>
                    <div class="col-md-3" hidden>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1" style="font-size: 16px">$</span>
                            <input id="amount" class="form-control form-control-sm" type="text" style="font-size: 20px" value="0.00">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="col-sm-4 col-form-label">Date:</label>
                                    <input name="date" class="form-control form-control-sm" type="date" value="{{date('Y-m-d', strtotime($payment->date))}}" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="col-sm-4 col-form-label">Terms:</label>
                                    @php
                                        
                                    @endphp
                                    <input onchange="changeTerm(this.value);" name="term" class="form-control form-control-sm" type="text" list="termList" placeholder="Choose a Term" value="{{$payment->id_term}}" required>
                                    <datalist id="termList">
                                        @foreach ($terms as $term)
                                            <option value="{{$term->name}}" <?php if($term->id == $payment->id_term) echo 'selected'?>></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label class="col-sm-4 col-form-label">Reference:</label>
                                    <input name="reference" class="form-control form-control-sm" type="text" value="{{$payment->reference}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        @if ($payment->card_number)
                            <div class="card" id="cc-card">
                        @else
                            <div class="card" id="cc-card" hidden>
                        @endif
                            <div class="card-header">
                                <center><h5>Enter Card Information</h5></center>
                            </div>
                            <div class="card-body" style="height: 150px;">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-form-label">Card Number:</label>
                                            <input class="form-control form-control-sm mt-1 cc-number" name="ccn" id="ccn" type="tel" inputmode="numeric" pattern="[0-9\s]" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" value="{{$payment->card_number}}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group">
                                            <label class="col-sm-4 col-form-label">Exp. Date:</label>
                                            <input class="form-control form-control-sm cc-expires" name="cce" id="cce" type="tel" maxlength="5" placeholder="MM / YY" value="{{$payment->exp_date}}">
                                        </div>                             
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card body">
                <br>
                <table class="table table-sm">
                    <thead class="bg-dark">
                        <tr>
                            <th width="8%"></th>
                            <th>Date</th>
                            <th>Number</th>
                            <th>Orig. Amt.</th>
                            <th>Amt. Due</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody id="tb-facturas">
                        @foreach ($payment_detail as $detail)
                            @php
                                $date = Invoices::where('number', $detail->invoice)->value('date');
                                $original_amount = Invoices::where('number', $detail->invoice)->value('total');
                                $amount =  PaymentsDetails::where('invoice', $detail->invoice)->sum('amount');
                                $due_amount = $original_amount - $amount;
                            @endphp
                            <tr>
                                <td></td>
                                <td>{{$date}}</td>
                                <td><input id="invoice" name="invoice[]" class="form-control form-control-sm" type="text" value="{{$detail->invoice}}" style="width:50%" readonly></td>
                                <td id="original_amount">{{$original_amount}}</td>
                                <td id="due_amount">{{number_format($due_amount, 2)}}</td>
                                <td><input id="payment" name="payment[]" onkeyup="changeAmount(this);" class="form-control form-control-sm" type="text" style="width:50%" value="{{$detail->amount}}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="row bg-dark">
                    <div class="col-md-5">
                        <p><strong></strong></p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <center><h6>AMOUNTS FOR SELECTED INVOICES</h6></center>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="input-group">
                                @php
                                    $original_amount = Invoices::where('number', $detail->invoice)->value('total');
                                    $amount =  PaymentsDetails::where('invoice', $detail->invoice)->sum('amount');
                                    $due_amount = $original_amount - $amount;
                                @endphp
                                <label class="col-sm-8 col-form-label">Amount Due</label>
                                <input id="due_total" class="form-control form-control-sm" type="text" style="border: none" value="{{number_format($due_amount, 2)}}" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="input-group">
                                <label class="col-sm-8 col-form-label">Applied</label>
                                <input id="applied_total" class="form-control form-control-sm" type="text" style="border: none" value="0.00" readonly>
                            </div>
                        </div>
                        <hr class="nav" style="width: 100%;">
                        <div class="row">
                              <div class="input-group">
                                <label class="col-sm-8 col-form-label">Total</label>
                                <input id="balance_total" class="form-control form-control-sm" type="text" style="border: none" value="0.00" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">          
                <div class="card">
                    <div class="card-header">
                        <h6>MEMO</h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control form-control-sm" name="memo" id="memo" cols="30" rows="4" style="resize: none">{{$payment->memo}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <center>
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
                        <button onclick="deletePayment({{$payment->id}});" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Delete</button>
                        <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                    </center>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
   $('.cc-number').keyup(function() {
        var foo = $(this).val().split(" ").join(""); // remove hyphens
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,4}', 'g')).join(" ");
        }
        $(this).val(foo);
   });

   $('.cc-expires').keyup(function() {
        var foo = $(this).val().split("/").join(""); // remove hyphens
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,2}', 'g')).join("/");
        }
        $(this).val(foo);
   });

   function filterInvoice(customer) {
        if (customer) {
            $.ajax({
                type:'GET',
                dataType:'json',
                url:'/operations/payment/invoices/' +  customer,
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success: function (data) {
                    var balance = 0;
                    data.forEach(element => {
                        const tr = document.createElement("tr");
                        const td1 = document.createElement("td");
                        const td2 = document.createElement("td");
                        td2.innerText = element['date'];
                        const td3 = document.createElement("td");
                            const input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('id', 'invoice');
                            input.setAttribute('name', 'invoice[]');
                            input.setAttribute('readonly', 'true');
                            input.classList.add("form-control", "form-control-sm");
                            input.style = "width:50%";
                            $(input).val(element['number']);
                        td3.append(input);
                        const td4 = document.createElement("td");
                        td4.innerText = element['amount'];
                        td4.setAttribute('id', 'original_amount');
                        
                        const td5 = document.createElement("td");
                        if (element['balance']) {
                            let result = element['amount'] - element['balance'];
                            td5.innerText = result.toFixed(2);
                            balance = parseFloat(element['amount']) - parseFloat(element['balance']);
                        } else {
                            td5.innerText = element['amount'];
                            balance += parseFloat(element['amount']);
                        }
                        td5.setAttribute('id', 'due_amount');

                        const td6 = document.createElement("td");
                            const input2 = document.createElement("input");
                            input2.setAttribute('id', 'payment');
                            input2.setAttribute('type', 'text');
                            input2.setAttribute('name', 'payment[]');
                            input2.setAttribute('onkeyup', 'changeAmount(this)');
                            input2.classList.add("form-control", "form-control-sm");
                            input2.style = "width:50%";
                        td6.append(input2);    
                        $(tr).append(td1, td2, td3, td4, td5, td6);
                        $('#tb-facturas').append(tr); 
                    });

                    $('#due_total').val(balance.toFixed(3));
                }
            });
        } else {
            
        }
      
   }

   function changeAmount(objeto){
        let due = parseFloat($(objeto).parent().parent().find('#due_amount').html());
        if($(objeto).val() <= due){
            sumar();
        }else{
            $(objeto).val("")
            sumar();
        }
   }

   function sumar() {
        let suma = 0;
        let due = parseFloat($('#due_total').val());
        let total = 0;

        $('#tb-facturas tr #payment').each(function () {
            if (isNaN(parseFloat($(this).val()))) {
                suma += 0;
            } else {
                suma += parseFloat($(this).val());
            }       
        });

        total = due - suma;
        $('#applied_total').val(suma.toFixed(3));
        $('#balance_total').val(total.toFixed(3));
   }

   function changeTerm(value) {
        if(value == 'Credit Card'){
            $('#cc-card').removeAttr('hidden');
        }
        else{
            $('#cc-card').attr('hidden', true);
            $('#ccn').val("");
            $('#cce').val("");
        }
   }

   function deletePayment(id) {
    Swal.fire({
    title: 'Do you want to delete the payment?',
    showDenyButton: true,
    confirmButtonText: 'Delete',
    denyButtonText: `Cancel`,
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                type:'GET',
                url:'/operations/payment/delete/' + id,
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success: function (data) {
                    Swal.fire('Deleted!', '', 'success')
                    location.href = '/payments';
                }
            });
        }
    })
   }
</script>
@stop