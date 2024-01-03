@extends('adminlte::page')

@section('title', 'Bill')

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
    <form action="{{route('bills.store')}}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="container-fluid bg-white shadow"  style="height: 5rem;">
            <div class="row align-items-center">
                <div class="bg-white col-md-8 mt-3">
                    <h2>New Bill</h2>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-4">
                        <label for="" class="col-sm-2 col-form-label form-control-sm" style="font-size: 25px">#</label>
                        @if (old('number'))
                            <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="{{old('number')}}">
                        @else
                            <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="000000001">
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
                                    <label for="" class="col-sm-2 col-form-label form-control-sm">Vendor:</label>
                                    <input onchange="selectVendor(this.value);" id="select_vendor" name="select_vendor" onchange="newVendor();" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="vendorsList" value="{{old('select_vendor')}}" required>
                                    <datalist id="vendorsList">
                                        <option id="0">------------(New)------------</option>
                                        @foreach ($vendors as $vendor)
                                            <option id="{{$vendor->id}}" value="{{$vendor->name}}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm">Date:</label>
                                    @if (old('vendor_date'))
                                        <input name="vendor_date" type="date" class="form-control form-control-sm" value="{{old('vendor_date')}}" width="300px">
                                    @else
                                        <input name="vendor_date" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" width="300px">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">              
                    <div class="col">
                        <label for="" class="col-sm-3 col-form-label form-control-sm">Bill To:</label>
                        <div class="input-group">
                            <textarea id="vendor_billto" name="vendor_billto" class="form-control form-control-sm mt-2" cols="30" rows="5" style="width:170px; height: 117px; resize: none">{{old('vendor_billto')}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Phone:</label>
                                <input id="vendor_phone" name="vendor_phone" type="text" class="form-control form-control-sm" value="{{old('vendor_phone')}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Email:</label>
                                <input id="vendor_email" name="vendor_email" type="text" class="form-control form-control-sm" value="{{old('vendor_email')}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="input-group">
                                <label for="" class="col-sm-3 col-form-label form-control-sm">Terms:</label>
                                <select id="select_term" onchange="newTerm();" name="select_term" class="form-select form-select-sm" style="width:170px;" aria-label=".form-select-sm" tabindex="13">
                                    <option value="" selected disabled>Choose an option</option>
                                    <option value="0">------------(New)------------</option>
                                    @foreach ($terms as $term)
                                        <option value="{{$term->id}}" <?php echo ($term->id ==  old('select_term')) ? ' selected="selected"' : '';?>>{{$term->name}}</option>
                                    @endforeach
                                </select>
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
                            <th width="3%"></th>
                            <th width="4%"></th>
                            <th width="15%">Code</th>
                            <th>Description</th>
                            <th width="10%">Qty</th>
                            <th width="10%">U/M</th>
                            <th width="10%">Price</th>
                            <th width="10%">Amount</th>
                        </tr
                    </thead>
                    <tbody id="tb_items">
                        @if (old('items'))
                            @foreach (old('items') as $i => $field)
                                <tr id="tr_items">
                                    <td id="td_false"></td>
                                    <td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr0" aria-expanded="false" aria-controls="collapseTr0" hidden></td>
                                    <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                    <td>
                                        <input onchange="changeItem(this, {{json_encode($items)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" value="{{old('items')[$i]}}" list="itemsList">
                                        <datalist id="itemsList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['item_name']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td>
                                        <input id="description" name="description[]" type="text" class="form-control form-control-sm" value="{{old('description')[$i]}}">
                                        <datalist id="itemsList">
                                            @foreach ($items as $item)
                                                <option value="{{$item['sales_description']}}"></option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" value="{{old('qty')[$i]}}" required></td>
                                    <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" value="{{old('unit')[$i]}}" readonly></td>
                                    <td><input onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm" value="{{old('price')[$i]}}"></td>
                                    <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" value="{{old('amt')[$i]}}" readonly></td>
                                </tr>
                                <tr class="collapse" id="collapseTr0">
                                    <td colspan="8">
                                        <div id="collapse_container" class="card card-body">
        
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="tr_items">
                                <td id="td_false"></td>
                                <td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr0" aria-expanded="false" aria-controls="collapseTr0" hidden></td>
                                <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                <td>
                                    <input onchange="changeItem(this, {{json_encode($items)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['item_name']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td>
                                    <input id="description" name="description[]" type="text" class="form-control form-control-sm">
                                    <datalist id="itemsList">
                                        @foreach ($items as $item)
                                            <option value="{{$item['sales_description']}}"></option>
                                        @endforeach
                                    </datalist>
                                </td>
                                <td><input onkeyup="changeQty(this);" id="qty" name="qty[]" type="text" class="form-control form-control-sm" required></td>
                                <td><input id="unit" name="unit[]" type="text" class="form-control form-control-sm" readonly></td>
                                <td><input onkeyup="changePrice(this);" id="price" name="price[]" type="text" class="form-control form-control-sm"></td>
                                <td><input id="amt" name="amt[]" type="text" class="form-control form-control-sm" readonly></td>
                            </tr>
                            <tr class="collapse" id="collapseTr0">
                                <td colspan="8">
                                    <div id="collapse_container" class="card card-body">

                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <hr>
                <center>
                    <button onclick="addRow();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Row</button>
                </center>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                @if (old('bill_total'))
                                    <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="bill_total" id="bill_total" value="{{old('bill_total')}}" readonly>
                                @else
                                    <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="bill_total" id="bill_total" value="$0.00" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <center>
                    <button type="submit" class="btn btn-sm btn-outline-primary" style="width: 100px">Save</button>
                    <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Cancel</button>
                </center>
            </div>
        </div>
        <br>
    </form>

     <!-- Modals -->
    <div class="modal fade" id="createVendor" tabindex="-1" role="dialog" aria-labelledby="createVendorLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
              <h5 class="modal-title">New Vendor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">                          
                    <form role="form" id="form_modal">

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
<style>
    td.btnplus{
        top:50%;left:15px;
        height:1em;
        width:1em;
        margin-top:-9px;
        display:block;
        color:white;
        border:.15em solid white;
        border-radius:1em;
        box-shadow:0 0 .2em #444;
        box-sizing:content-box;
        text-align:center;
        text-indent:0 !important;
        font-family:"Courier New",Courier,monospace;
        line-height:1em;
        content:"+";
        background-color:#31b131;
    }
    td.btnminus{
        top:50%;left:15px;
        height:1em;
        width:1em;
        margin-top:-9px;
        display:block;
        color:white;
        border:.15em solid white;
        border-radius:1em;
        box-shadow:0 0 .2em #444;
        box-sizing:content-box;
        text-align:center;
        text-indent:0 !important;
        font-family:"Courier New",Courier,monospace;
        line-height:1em;
        content:"-";
        background-color:#d33333;
    }
</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/bills.actions.js"></script>
<script type="text/javascript">
    
    function cambioBtn(objeto) {
        if($(objeto).html() == "+"){
            $(objeto).removeClass('btnplus');
            $(objeto).html("-"); 
            $(objeto).addClass('btnminus');
        }
        else{
            $(objeto).removeClass('btnminus');
            $(objeto).html("+"); 
            $(objeto).addClass('btnplus');
        }
    }
    
    function saveVendor() {
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
            data: {
                "_token": "{{ csrf_token() }}",  
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

    function selectVendor(vendor) {

        if(vendor){
            $.ajax({
                type:'GET',
                url:'/operations/vendor/get/' + vendor,
                dataType:'json',
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success: function (data) {
                    console.log(data);
                    $('#vendor_phone').val(data['phone']);
                    $('#vendor_email').val(data['email']);
                    $('#vendor_billto').val(data['billto_street'] +"\n"+ data['billto_company'] +"\n"+ data['billto_city'] +"\n"+ data['billto_postal'] +"\n"+ data['billto_state']);
                }
            });
        }
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