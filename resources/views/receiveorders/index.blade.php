@extends('adminlte::page')

@section('title', 'Orders Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>Orders Center</h2>
            </div>
        </div>
    </div>
@stop

@section('content')
@php
    use App\Models\Vendors;
@endphp
<div class="card">
    <div class="card-body">
        <table id="dTable" class="display nowrap table table-sm" style="width: 100%">
            <thead class="bg-dark">
                <tr>
                    <th>Date</th>
                    <th>Number</th>
                    <th>Vendor</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    @php
                            $vendor = Vendors::where('id', $order->vendor_id)->value('name');
                    @endphp
                    <tr>
                        <td role="button">{{$order->date}}</td>
                        <td role="button">{{$order->number}}</td>
                        <td role="button">{{$vendor}}</td>
                        <td role="button">{{$order->total}}</td>
                        <td><span class="badge badge-danger">{{$order->status}}</span></td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                  <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a class="dropdown-item" href="/vendors/access/api/{{$order->id}}/edit">Receive</a></li>                      
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
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

</script>
@stop