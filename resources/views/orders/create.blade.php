@extends('adminlte::page')

@section('title', 'Estimate')

@section('content_header')

@stop

@section('content')
    @if( Session::has('info') )
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif
    <br>
    @php
        use App\Models\Inventories;
    @endphp
    <form action="{{route('orders.store')}}" enctype="multipart/form-data" method="POST" id="doc_form">
        @csrf
        <div class="container-fluid bg-white shadow"  style="height: 5rem;">
            <div class="row align-items-center">
                <div class="bg-white col-md-8 mt-3">
                    <h2>New Estimate</h2>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-4">
                        <label for="" class="col-sm-2 col-form-label form-control-sm" style="font-size: 25px">#</label>
                        @php
                            $length = 9;
                            $number = str_pad($order_number, $length,"0", STR_PAD_LEFT);
                        @endphp
                        @if (old('number'))
                            <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="{{old('number')}}">
                        @else
                            <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="{{$number}}">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="card card-body bg-secondary">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <label for="" class="col-sm-2 col-form-label form-control-sm">Customer:</label>
                                    @if (old('select_customer'))
                                        <input id="select_customer" name="select_customer" onchange="newCustomer();" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="customersList" value="{{old('select_customer')}}">
                                    @else
                                        <input id="select_customer" name="select_customer" onchange="newCustomer();" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="customersList" value="GENERAL CONSUMER">
                                    @endif
                                   
                                    <datalist id="customersList">
                                        <option id="0">------------(New)------------</option>
                                        @foreach ($customers as $customer)
                                            <option id="{{$customer->id}}" value="{{$customer->company_name}}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm">Date:</label>
                                    @if (old('date'))
                                        <input name="date" type="date" class="form-control form-control-sm" value="{{old('date')}}" width="300px">
                                    @else
                                        <input name="date" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" width="300px">
                                    @endif                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">              
                    <div class="col-md-4">
                        <label for="" class="col-sm-3 col-form-label form-control-sm">Bill To:</label>
                        <div class="input-group">
                            @if (old('billto'))
                                <textarea id="billto" name="billto" class="form-control form-control-sm mt-2" cols="30" rows="5" style="width:170px; height: 117px; resize: none">{{old('billto')}}</textarea>
                            @else
                                <textarea id="billto" name="billto" class="form-control form-control-sm mt-2" cols="30" rows="5" style="width:170px; height: 117px; resize: none"></textarea>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-sm-5">
                                <label class="form-control-sm" align="left">Ship To:</label>
                            </div>
                            <div class="col">
                                <select id="select_shipto" onchange="newShipTo();" name="select_shipto[]" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> 
                        <div class="input-group">
                            @if (old('shipto'))
                                <textarea id="shipto" name="shipto" class="form-control form-control-sm" style="width:279px;  resize: none;" cols="33" rows="5" tabindex="11">{{old('shipto')}}</textarea>
                            @else
                                <textarea id="shipto" name="shipto" class="form-control form-control-sm" style="width:279px;  resize: none;" cols="33" rows="5" tabindex="11"></textarea> 
                            @endif
                        </div>

                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Phone:</label>
                                @if (old('phone'))
                                    <input id="phone" name="phone" type="text" class="form-control form-control-sm" value="{{old('phone')}}">
                                @else
                                    <input id="phone" name="phone" type="text" class="form-control form-control-sm">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Email:</label>
                                @if (old('email'))
                                    <input id="email" name="email" type="text" class="form-control form-control-sm" value="{{old('email')}}">
                                @else
                                    <input id="email" name="email" type="text" class="form-control form-control-sm">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Wh:</label>
                                <Select id="select_warehouse" name="select_warehouse" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                    @foreach ($warehouses as $wh)
                                    <option value="{{$wh->id}}" <?php echo (old('select_warehouse')) ? ' selected="selected"' : '';?>>{{$wh->wh_name}}</option>
                                    @endforeach
                                </Select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <table id="dTable" class="table table-sm" style="width: 100%">
                    <thead>
                        <tr>
                            <th width="4%"></th>
                            <th width="15%">Code</th>
                            <th>Description</th>
                            <th width="10%">Qty</th>
                            <th width="10%">U/M</th>
                            <th width="10%">Price</th>
                            <th width="10%">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="tb_items">
                        @if (old('items') || old('description') )
                            @php
                                $rows = 0;
                            @endphp
                            @foreach (old('items') as $i => $field)
                                <tr id="tr_items">                                    
                                    <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                    <td>
                                        <input autocomplete="off" onchange="changeItem(this, {{json_encode($items)}}, {{json_encode($types)}})" onblur="calcular();" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" value="{{old('items')[$i]}}" list="itemsList">
                                        <datalist id="itemsList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['item_name']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td>
                                        <input autocomplete="off" onchange="changeDescription(this, {{json_encode($items)}}, {{json_encode($types)}})" id="description" name="description[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" value="{{old('description')[$i]}}" list="itemDesList">
                                        <datalist id="itemDesList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['sales_description']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td hidden><input id="existencia" name="existencia[]" type="text" class="form-control form-control-sm" value="{{old('qty')[$i]}}"></td>
                                    <td><input autocomplete="off" onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" value="{{old('qty')[$i]}}" required></td> 
                                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" value="{{old('unit')[$i]}}" readonly></td>
                                    <td hidden><input id="price_real" name="price_real[]" type="text" class="form-control form-control-sm" value="{{old('price')[$i]}}"></td>
                                    <td><input autocomplete="off" onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm" value="{{old('price')[$i]}}"></td>
                                    <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" value="{{old('amt')[$i]}}" readonly></td>
                                </tr>

                                @php
                                    $rows++;
                                @endphp
                            @endforeach
                        @else
                            @for ($i = 0; $i < 8; $i++)
                                <tr id="tr_items">                            
                                    <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                    <td>
                                        <input autocomplete="off" onchange="changeItem(this, {{json_encode($items)}}, {{json_encode($types)}})" onblur="calcular();" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                                        <datalist id="itemsList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['item_name']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td>
                                        <input autocomplete="off" onchange="changeDescription(this, {{json_encode($items)}}, {{json_encode($types)}})" id="description" name="description[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemDesList">
                                        <datalist id="itemDesList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['sales_description']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td hidden><input id="existencia" name="existencia[]" type="text" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" onkeyup="changeQty(this);" onblur="calcular();" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" readonly></td>
                                    <td hidden><input id="price_real" name="price_real[]" type="text" class="form-control form-control-sm"></td>
                                    <td><input autocomplete="off" onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm" ></td>
                                    <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>
                <hr>
                <center>
                    <div class="row">                        
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Stock:</label>
                                @if (old('qty'))
                                    <input id="stock" name="stock" style="width:10%; font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm" value="{{old('qyt')}}">
                                @else
                                    <input id="stock" name="stock" style="width:10%; font-size: 17px; font-weight: bold; background-color:yellow;" type="text" class="form-control form-control-sm">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button onclick="addRow();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Row</button>
                        </div>
                        
                    </div>
                </center>
                <hr>
                
                <div class="container">
                    <div class="row">
                        <div class="nav justify-content-start">
                            <div class="col-2">
                                <label style="font-size: 32px"><b>Payment</b></label>
                            </div>
                            <div class="col-md-4">
                                <div class="row" id="Porcent">
                                    <div class="input-group">
                                        <label for="" class="col-sm-4 col-form-label form-control-sm">Porcent:</label>
                                        &nbsp;&nbsp;&nbsp;
                                        <input oninput="clearTimeout(window.typingTimer); window.typingTimer = setTimeout(function() { porcentage(); }, 900);" id="porcentaje" name="porcentaje" type="text" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <br>
                                <div class="input-group">
                                    <label for="" class="col-sm-4 col-form-label">Terms:</label>
                                    &nbsp;&nbsp;&nbsp;
                                    <select id="select_term" onchange="newTerm()" name="select_term" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                        <option value="" selected disabled>Choose an option</option>
                                        <option value="0">------------(New)------------</option>
                                        @foreach ($terms as $term)
                                        <option value="{{$term->id}}" <?php echo ($term->name ==  "Cash") ? ' selected="selected"' : '';?>>{{$term->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="nav justify-content-end">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <label class="col-sm-4 col-form-label"><b>Subtotal:</b></label>&nbsp;&nbsp;&nbsp;
                                    @if (old('order_subtotal'))
                                    <input style="font-size: 100%; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_subtotal" id="order_subtotal" style="text-align:right;" value="{{old('order_subtotal')}}" readonly>
                                    @else
                                    <input style="font-size: 100%; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_subtotal" id="order_subtotal" style="text-align:right;" value="$0.00" readonly>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="nav justify-content-end">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <label class="col-sm-4 col-form-label"><b>Taxes:</b></label>&nbsp;&nbsp;&nbsp;
                                    <select onchange="taxes(this);" name="select_tax" id="select_tax" class="form-select form-select-sm">
                                        <option value="0" selected>Choose Taxes</option>
                                        @foreach ($taxes as $tax)
                                        <option value="{{$tax->id}}">{{$tax->description}}-{{$tax->percentage}} %</option>
                                        @endforeach
                                    </select>&nbsp;&nbsp;
                                    <input style="font-size: 100%; text-align: right; font-weight: bold" type="text" class="form-control form-control-sm" id="order_tax" name="order_tax" value="$0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                    <div class="row">
                        <div class="nav justify-content-end">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <label style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                    @if (old('order_total'))
                                    <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_total" id="order_total" value="{{old('order_total')}}" readonly>
                                    @else
                                    <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_total" id="order_total" value="$0.00" readonly>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                                   
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <center>
                    <button onclick="validateCliente();" type="button" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
                    <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                </center>
            </div>
        </div>
        <br>
    </form>

     <!-- Modals -->
    <div class="modal fade" id="createCustomer" tabindex="-1" role="dialog" aria-labelledby="createCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Customer</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                          
                    <form role="form" id="form_modal">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_company" class="col-sm-4 col-form-label form-control-sm" align="left">Company Name:</label>
                                            <input autocomplete="off" id="cs_company" name="cs_company" type="text" class="form-control form-control-sm" tabindex="1" required/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_firstname" class="col-sm-4 col-form-label form-control-sm" align="left">Full Name:</label>
                                            <input autocomplete="off" id="cs_firstname" name="cs_firstname" type="text" class="form-control form-control-sm" placeholder="First Name" tabindex="3"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input autocomplete="off" id="cs_midlename" name="cs_midlename" type="text" class="form-control form-control-sm" placeholder="Midle Name" tabindex="4"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input autocomplete="off" id="cs_lastname" name="cs_lastname" type="text" class="form-control form-control-sm" placeholder="Last Name" tabindex="5"/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_phone" class="col-sm-4 col-form-label form-control-sm" align="left">Main Phone:</label>
                                            <input autocomplete="off" id="cs_phone" name="cs_phone" type="text" class="form-control form-control-sm" tabindex="6"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_mail" class="col-sm-4 col-form-label form-control-sm" align="left">Main Email:</label>
                                            <input autocomplete="off" id="cs_mail" name="cs_mail" type="text" class="form-control form-control-sm" tabindex="7"/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_workphone" class="col-sm-4 col-form-label form-control-sm" align="left">Work Phone:</label>
                                            <input autocomplete="off" id="cs_workphone" name="cs_workphone" type="text" class="form-control form-control-sm" tabindex="8"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="cs_ccemail" class="col-sm-4 col-form-label form-control-sm" align="left">CC Email:</label>
                                            <input autocomplete="off" id="cs_ccemail" name="cs_ccemail" type="text" class="form-control form-control-sm" tabindex="9"/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="select_payment" class="col-sm-4 col-form-label form-control-sm" align="left">Payment Terms:</label>
                                            <select id="select_payment" name="select_payment" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="10">
                                                <option value="" selected disabled>Choose an option</option>
                                                @foreach ($terms as $term)
                                                    <option value="{{$term->id}}">{{$term->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="select_delivery" class="col-sm-4 col-form-label form-control-sm" align="left">Delivery Method:</label>
                                            <select id="select_delivery" name="select_delivery" class="form-select form-select-sm" aria-label=".form-select-sm" tabindex="11">
                                                <option value="" selected disabled>Choose an option</option>
                                                @foreach ($deliveries as $delivery)
                                                <option value="{{$delivery->id}}">{{$delivery->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5 mt-2">
                                        <label for="cs_billto" class="col-sm-4 col-form-label form-control-sm" align="left">Invoice/Bill To:</label>
                                        <div class="row-md-4">
                                            <div class="container" style="border: 1px solid gray">
                                                <br>
                                                <input class="form-control form-control-sm" name="street_billto" type="text" placeholder="Street Number And Name Or P.O Box">
                                                <hr>
                                                <input class="form-control form-control-sm" name="company_billto" type="text" placeholder="Specify Company, APT, Suite, Unit">
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input class="form-control form-control-sm" name="city_billto" type="text" placeholder="City">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control form-control-sm" name="postal_billto" type="text" placeholder="Postal Code">
                                                    </div>
                                                </div>
                                                <hr>
                                                <input class="form-control form-control-sm" name="state_billto" type="text" placeholder="State">
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label for="cs_shipto" class="form-control-sm" align="left">Ship To:</label>
                                            </div>
                                            <div class="col">
                                                <select id="select_shipto_model" onchange="newShipToModal();" name="select_shipto[]" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                                    <option value=""></option>
                                                    <option value="0">------------(New)------------</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row-md-4">
                                            <div class="container" style="border: 1px solid gray">
                                                <br>
                                                <input class="form-control form-control-sm" name="street_shipto" type="text" placeholder="Street Number And Name Or P.O Box">
                                                <hr>
                                                <input class="form-control form-control-sm" name="company_shipto" type="text" placeholder="Specify Company, APT, Suite, Unit">
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input class="form-control form-control-sm" name="city_shipto" type="text" placeholder="City">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control form-control-sm" name="postal_shipto" type="text" placeholder="Postal Code">
                                                    </div>
                                                </div>
                                                <hr>
                                                <input class="form-control form-control-sm" name="state_shipto" type="text" placeholder="State">
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="validate();" class="btn btn-outline-primary">Save</button>
                    <button type="button" onclick="cancelar();" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="createTerms" tabindex="-1" role="dialog" aria-labelledby="createTermsLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Payment Terms</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                          
                    <div class="row g-3">
                        <div class="col">
                            <label for="cl_ruc" class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="terms_name" name="terms_name" type="text" value="">
                            </div>
                        </div>
                    </div>
    
                </div>  

                <div class="modal-footer">
                    <button type="subtmit" onclick="saveTerm();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="createShipTo" tabindex="-1" role="dialog" aria-labelledby="createShipToLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_shipto" name="cs_shipto" type="text" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Street Number And Name Or P.O Box:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address" name="cs_address" type="text">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Specify Company, APT, Suite, Unit:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address2" name="cs_address2" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>City:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_city" name="cs_city" type="text">
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-control-sm"><b>Postal Code:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_postal" name="cs_postal" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>State:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_state" name="cs_state" type="text">
                            </div>
                        </div>
                    </div> 
                </div>  

                <div class="modal-footer">
                    <button type="button" onclick="saveShipTo();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="createShipToModal" tabindex="-1" role="dialog" aria-labelledby="createShipToLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Name:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_shipto" name="cs_shipto" type="text" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Street Number And Name Or P.O Box:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address" name="cs_address" type="text">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>Specify Company, APT, Suite, Unit:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_address2" name="cs_address2" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>City:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_city" name="cs_city" type="text">
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-control-sm"><b>Postal Code:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_postal" name="cs_postal" type="text">
                            </div>
                        </div>
                    </div> 
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label class="form-control-sm"><b>State:</b></label>
                            <div class="input-group">
                                <input autocomplete="off" class="form-control" id="cs_state" name="cs_state" type="text">
                            </div>
                        </div>
                    </div> 
                </div>  

                <div class="modal-footer">
                    <button type="button" onclick="saveShipToModal();" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

          </div>
        </div>
    </div>

    <!--- Toast --->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
                <strong class="me-auto">ART&COLOR</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">
                Added a new record.
              </div>
            </div>
    </div> 
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/configuration.view.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/orders.actions.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        
        if($("#tb_items tr")!= null || $("#tb_items tr")!= "" ){
            $("#tb_items tr").click(function() {
            var existencia = $(this).find("#existencia").val();
            $("#stock").val(existencia);
            
        });
        }
        
    });

    function changeDescription(objeto, items) {
        let div_next = $(objeto).parent().parent().next();
        let code = $(objeto).val();
        var selectedWarehouse = $('#select_warehouse option:selected').val();
        
        function isMatch(item) {
            return item.purchase_description === code;
        }
        
        code = items.find(isMatch);
        let tr = $(objeto).parent().parent();
        if(code){
            let url = "/operations/item/description/" + code['id'];    
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: false,
                data:{
                    selectedWarehouse:selectedWarehouse
                },
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(data){
                    tr.find('#items').val(data['item_name']);
                    tr.find('#unit').val(data['id_unit_measure']);
                    tr.find('#existencia').val(data['qty']);
                    $("#stock").val(data['qty']);
                    tr.find('#qty').val("1");
                    tr.find('#price_real').val(data['price']);                 
                    let porcentage = parseFloat($("#porcentaje").val())/(100);
                    if (isNaN(porcentage)) {                
                        tr.find('#price').val(data['price']);
                        tr.find('#amt').val(data['price']);
                    } else{
                        let price = parseFloat(data['price']);
                        let total = price+(price*porcentage);
                        tr.find('#price').val(total.toFixed(2));
                        tr.find('#amt').val(total.toFixed(2));
                    } 
                    $(div_next).find('#collapse_container div').remove();
                    $(div_next).find('#collapse_container hr').remove();
                    calcular();  
                    
                }
            });
        }
        else{
            code = $(objeto).val(); 
            if(code){
                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:'/operations/item/codebar/' +  code,
                    async:false,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.error);
                    },
                    success : function(any){
                        tr.find('#items').val(any['item_name']);
                        tr.find('#unit').val(any['id_unit_measure']);
                        tr.find('#existencia').val(data['qty']);
                        $("#stock").val(data['qty']);

                        tr.find('#qty').val("1");
                        tr.find('#price_real').val(any['price']);
                        tr.find('#price').val(any['price']);
                        tr.find('#amt').val(any['price']);
                        $(div_next).find('#collapse_container div').remove();
                        $(div_next).find('#collapse_container hr').remove();
                        
                        let porcentage = parseFloat($("#porcentaje").val())/(100);
                        if (isNaN(porcentage)) {                       
                            tr.find('#price').val(data['price']);
                            tr.find('#amt').val(data['price']);
                        } else{
                            let price = parseFloat(data['price']);
                            let total = price+(price*porcentage);
                            tr.find('#price').val(total.toFixed(2));
                            tr.find('#amt').val(total.toFixed(2));
                        }
                        calcular();
                    }
                });
            }
            else{                
                tr.find('#description').val(" ");
                tr.find('#qty').val(" ");
                tr.find('#unit').val(" ");
                tr.find('#price_real').val(" ");
                tr.find('#price').val(" ");
                tr.find('#amt').val(" ");
            }
        }
    }

    
    function porcentage(){
        let porcent = $("#porcentaje").val();
        let cellsreal = $("td #price_real");
        let cells = $("td #price");
        
        cells.each(function() {
            let tr = $(this).parent().parent();
            let currentValue = tr.find('#price').val();
            let currentValueInicial = tr.find('#price_real').val();
            let qty = parseFloat(tr.find('#qty').val()) * 1;
            let subtotal = 0;

            if(currentValue != ""){
                if (porcent === "") {
                    let newprice = $(this).val((parseFloat(currentValueInicial)).toFixed(2));
                    subtotal = qty * newprice.val();
                    tr.find('#amt').val(subtotal);
                }
                else{
                    let precioFinal= ((parseFloat(porcent)+100)/100)*(parseFloat(currentValue));
                    let newValue = currentValue.replace(currentValue, precioFinal);
                    let newprice = $(this).val((parseFloat(newValue)).toFixed(2));
                    subtotal = qty * newprice.val();
                    tr.find('#amt').val(subtotal);
                }
            }     
        });

        calcular();

    }

    function saveShipTo() {
            let name_address = $('#createShipTo #cs_shipto').val();
            let address_detail = $('#createShipTo #cs_address').val();
            let address2_detail = $('#createShipTo #cs_address2').val();
            let city_detail = $('#createShipTo #cs_city').val();
            let postal_detail = $('#createShipTo #cs_postal').val();
            let state_detail = $('#createShipTo #cs_state').val();
            let url = '/operations/shipto';
            $.ajax({
                type:'POST',
                url: url,
                dataType: 'json',
                async: "false",
                data: {"_token": "{{ csrf_token() }}", name:name_address, address:address_detail, company:address2_detail, city:city_detail, postal:postal_detail, state:state_detail},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    $('#select_shipto').append($('<option>', {
                        value: data['id'],
                        text: data['name']
                    }));
                    $('#street_shipto').val(data['address']);
                    $('#company_shipto').val(data['company']);
                    $('#city_shipto').val(data['city']);
                    $('#postal_shipto').val(data['postal']);
                    $('#state_shipto').val(data['state']);

                    $('#createShipTo #cs_shipto').val("");
                    $('#createShipTo #cs_address').val("");
                    $('#createShipTo #cs_address2').val("");
                    $('#createShipTo #cs_city').val("");
                    $('#createShipTo #cs_postal').val("");
                    $('#createShipTo #cs_state').val("");

                    const selected = document.querySelector('#select_shipto');
                    selected.value = data['id'];
                    showToast();
                }
            });
    }

    function saveShipToModal() {
            let name_address = $('#createShipToModal #cs_shipto').val();
            let address_detail = $('#createShipToModal #cs_address').val();
            let address2_detail = $('#createShipToModal #cs_address2').val();
            let city_detail = $('#createShipToModal #cs_city').val();
            let postal_detail = $('#createShipToModal #cs_postal').val();
            let state_detail = $('#createShipToModal #cs_state').val();
            let url = '/operations/shipto';
            $.ajax({
                type:'POST',
                url: url,
                dataType: 'json',
                async: "false",
                data: {"_token": "{{ csrf_token() }}", name:name_address, address:address_detail, company:address2_detail, city:city_detail, postal:postal_detail, state:state_detail},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    $('#select_shipto_model').append($('<option>', {
                        value: data['id'],
                        text: data['name']
                    }));
  
                    $('#createCustomer #street_shipto').val(data['address']);
                    $('#createCustomer #company_shipto').val(data['company']);
                    $('#createCustomer #city_shipto').val(data['city']);
                    $('#createCustomer #postal_shipto').val(data['postal']);
                    $('#createCustomer #state_shipto').val(data['state']);

                    $('#createShipToModal #cs_shipto').val("");
                    $('#createShipToModal #cs_address').val("");
                    $('#createShipToModal #cs_address2').val("");
                    $('#createShipToModal #cs_city').val("");
                    $('#createShipToModal #cs_postal').val("");
                    $('#createShipToModal #cs_state').val("");

                    const selected = document.querySelector('#select_shipto_model');
                    selected.value = data['id'];
                    showToast();
                }
            });

    }

    function saveCustomer() {
        let url = "/operations/customer/new/1";
        let company_name = $('#createCustomer #cs_company').val();
        let first_name = $('#createCustomer #cs_firstname').val();
        let midle_name = $('#createCustomer #cs_midlename').val();
        let last_name = $('#createCustomer #cs_lastname').val();
        let phone = $('#createCustomer #cs_phone').val();
        let work_phone = $('#createCustomer #cs_workphone').val();
        let email = $('#createCustomer #cs_mail').val();
        let cc_email = $('#createCustomer #cs_ccemail').val();
        let id_terms = $('#createCustomer #select_payment').val();
        let id_delivery = $('#createCustomer #select_delivery').val();
        let billto_street = $('#createCustomer #street_billto').val();
        let billto_company = $('#createCustomer #company_billto').val();
        let billto_city = $('#createCustomer #street_city').val();
        let billto_postal = $('#createCustomer #street_postal').val();
        let billto_state = $('#createCustomer #street_state').val();

        $.ajax({
            type:'POST',
            url: url,
            dataType: 'json',
            async: "false",
            data: {"_token": "{{ csrf_token() }}",  
                cs_company:company_name,
                cs_firstname:first_name,
                cs_midlename:midle_name,
                cs_lastname:last_name,
                cs_phone:phone,
                cs_workphone:work_phone,
                cs_mail:email,
                cs_ccemail:cc_email,
                select_payment:id_terms,
                select_delivery:id_delivery,
                cs_billto_street:billto_street,
                cs_billto_company:billto_company,
                cs_billto_city:billto_city,
                cs_billto_postal:billto_postal,
                cs_billto_state:billto_state
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#customersList').append($('<option>', {
                        text: data['company_name']
                }));
                showToast();
            }
        });

    }

    function validate() {
        let company_name = $('#createCustomer #cs_company').val();
        let phone = $('#createCustomer #cs_phone').val();
        let bill_to = $('#createCustomer #cs_billto').val();

        if(company_name && phone && bill_to){
            $('#createCustomer').modal('hide');
            saveCustomer();
        }
        else{
            if(!company_name){
                $('#createCustomer #cs_company').css('border','1px solid red');
            }
            else{
                $('#createCustomer #cs_company').css('border','2px solid rgb(238, 238, 238)');
            }

            if (!phone) {
                $('#createCustomer #cs_phone').css('border','1px solid red');
            }
            else{
                $('#createCustomer #cs_phone').css('border','2px solid rgb(238, 238, 238)');
            }
            
            if(!bill_to){
                $('#createCustomer #cs_billto').css('border','1px solid red');
            }
            else{
                $('#createCustomer #cs_billto').css('border','2px solid rgb(238, 238, 238)');
            }

            Swal.fire(
                'Warning',
                'Please fill in the mandatory fields painted in red',
                'warning'
                )
        }
    }

    
</script>
@stop