<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dTable').DataTable({
                fixedHeader: {
                    header: true,
                    footer: true
                },
                select: {
                    style: 'single'
                }
            });
        });

        function deleteOrder(objeto) {
            let form = $(objeto).parent();
            Swal.fire({
                title: 'Do you want to delete the order?',
                showDenyButton: true,
                confirmButtonText: 'Delete',
                denyButtonText: `Cancel`,
                }).then((result) => {

                    if (result.isConfirmed) {
                        $(form).submit();
                    } 
                })
        }
    </script>
    <title>Flowerist Suppliers</title>
</head>
<body>
    @php
        session_start();
        if (isset( $_SESSION['vendor'])) {
          if (isset($vendor)) {
            $_SESSION['vendor'] = $vendor;
          }  
        }
    @endphp

    @if( Session::has('info') )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('info') }}
        </div>
    @endif

@if (isset( $_SESSION['vendor']))
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
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="http://flowerist.net/">Log out</a>
                </li>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <br>
    <div class="card card-body">
        <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
            <thead class="table-dark">
                <tr>
                    <th width="15%">Date</th>
                    <th width="20%">Number</th>
                    <th width="20%">Total</th>
                    <th width="20%">Status</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->date}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">{{$order->number}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">${{$order->total}}</td>
                        <td role="button" ondblclick="editar({{$order->id}});">
                            @if ($order->status == 'Pending')
                                <span class="badge bg-danger" style="font-size: 16px">Pending</span>
                            @else
                                <span class="badge bg-success" style="font-size: 16px">Complete</span>
                            @endif                   
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" style="width: 300px">
                                    <li role="button"><a class="dropdown-item" href="/vendors/access/api/order/{{$order->id}}"><i class="fa-solid fa-eye"></i> View</a></li>
                                    <li role="button">
                                        <form action="/vendors/access/api/{{$order->id}}" method="POST" id="order_form{{$order->id}}">
                                            @csrf
                                            @method('DELETE')
                                            <a onclick="deleteOrder(this)" type="button" class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Delete</a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @php
        header("Location: /vendors/access/api/page");
        exit();
    @endphp
@endif
</body>
</html>

