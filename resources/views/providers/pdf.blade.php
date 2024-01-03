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
        function salir() {
            Swal.fire({
                title: 'Do you want to exit the form?',
                showDenyButton: true,
                confirmButtonText: 'Exit',
                denyButtonText: `Cancel`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.history.back();
                    } 
                })
        }
    </script>
    <title>Flowerist Suppliers</title>
</head>
<body>
    @php
        session_start();

        use App\Models\Products;
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
                    <h2>View Order</h2>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label form-control-sm">Number:</label>
                            <p class="mt-1">{{$order->number}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label form-control-sm">Date:</label>
                            <p class="mt-1">{{date('Y-m-d', strtotime($order->date))}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label class="col-sm-3 col-form-label form-control-sm">Vendor:</label>
                            <p class="mt-1">{{$_SESSION['vendor']}}</p>
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
                            <th width="15%">Code</th>
                            <th>Description</th>
                            <th width="10%">Qty</th>
                            <th width="10%">Price</th>
                            <th width="10%">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="tb_items">
                        @foreach ($order_items as $item)
                        <tr id="tr_items">
                            @php
                                $code = Products::where('id', $item->item_id)->value('item_name');
                                $description = Products::where('id', $item->item_id)->value('sales_description');
                            @endphp
                            <td>{{$code}}</td>
                            <td>{{$description}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->price}}</td>
                            <td>{{$item->qty * $item->price}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="nav justify-content-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <label style="font-size: 32px" class="col-sm-4 col-form-label"><b>Total:</b></label>&nbsp;&nbsp;&nbsp;
                                <p style="font-size: 40px; text-align: right; font-weight: bold">${{number_format($order->total, 2)}}</p>
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
                    <button onclick="salir();" type="button" class="btn btn-sm btn-outline-danger" style="width: 100px">Back</button>
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