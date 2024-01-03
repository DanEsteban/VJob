<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="//kit.fontawesome.com/08def23c06.js"></script>
    <script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="/js/orders.actions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function validate() {
            Swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Cancel`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    Swal.fire('Saved!', '', 'success')
                } 
                })
        }

        function salir() {
            Swal.fire({
                title: 'Do you want to exit the form?',
                showDenyButton: true,
                confirmButtonText: 'Exit',
                denyButtonText: `Cancel`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = "/vendors/access/api/main";
                    } 
                })
        }
    </script>
    <title>Flowerist Suppliers</title>
</head>
<body>
    @php
        session_start();
    @endphp

@if (isset($_SESSION['vendor']))
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
        <a class="navbar-brand">
            <img src="/img/logo.png" width="30px">
            Flowerist
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/vendors/access/api/main">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Order Center
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="/vendors/access/api/{{$_SESSION['vendor']}}">List</a></li>
                <li><a class="dropdown-item" href="/vendors/access/api/create">Create</a></li>
                </ul>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <br>
    <form action="/vendors/access/api" method="POST">
        @csrf
        <div class="container-fluid bg-white shadow"  style="height: 5rem;">
            <div class="row align-items-center">
                <div class="bg-white col-md-8 mt-3">
                    <h2>New Order</h2>
                </div>
                <div class="col-md-3">
                    <div class="input-group mt-4">
                        <label for="" class="col-sm-2 col-form-label form-control-sm" style="font-size: 25px">#</label>
                        <input name="number" type="text" class="form-control" style="border: 0; font-size: 25px" value="000000001">
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
                                    <label class="col-sm-2 col-form-label form-control-sm text-white">Vendor:</label>
                                    <input id="select_vendor" name="select_vendor" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="vendorsList" value="{{$_SESSION['vendor']}}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label for="" class="col-sm-3 col-form-label form-control-sm text-white">Date:</label>
                                    <input name="vendor_date" type="date" class="form-control form-control-sm" value="{{date('Y-m-d')}}" width="300px">
                                </div>
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
                        </tr>
                    </thead>
                    <tbody id="tb_items">
                            <tr id="tr_items">
                                <td id="td_false"></td>
                                <td id="td_button" onclick="cambioBtn(this);" role="button" class="mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTr0" aria-expanded="false" aria-controls="collapseTr0" hidden></td>
                                <td><button onclick="delRow(this);" type="button" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></td>
                                <td>
                                    <input onchange="changeItem2(this, {{json_encode($items)}})" id="items" name="items[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsList">
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
                    </tbody>
                </table>
                <hr>
                
                <center>
                    <button onclick="addRow2();" type="button" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Row</button>
                </center>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                <input style="font-size: 40px; text-align: right; font-weight: bold" class="form-control form-control-sm" type="text" name="order_total" id="order_total" value="$0.00" readonly>
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
@else
    @php
        header("Location: /vendors/access/api/page");
        exit();
    @endphp
@endif
</body>
</html>