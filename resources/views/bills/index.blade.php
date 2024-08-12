@extends('adminlte::page')

@section('title', 'Bills Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
    <div class="container-fluid bg-white shadow"  style="height: 5rem;">
        <div class="row align-items-center">
            <div class="bg-white col-md-8 mt-3">
                <h2>Bills Center</h2>
            </div>

            <div class="col-md-2 mt-3">
                    <a class="btn btn-outline-danger" style="width:160px;" href="/bills/create"><i class="fa fa-plus"></i> New Bill</a>
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
                    <th>Reference</th>
                    <th>Vendor</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bills as $bill)
                    @php
                            $vendor = Vendors::where('id', $bill->id_vendor)->value('name');
                    @endphp
                    <tr>
                        <td role="button">{{$bill->date}}</td>
                        <td role="button">{{$bill->number}}</td>
                        <td role="button">{{$vendor}}</td>
                        <td role="button">{{$bill->total}}</td>
                        <td>{{$bill->status}}</td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                  <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a class="dropdown-item" href="/bills/{{$bill->id}}">View</a></li>
                                  <li><a class="dropdown-item" href="/bills/{{$bill->id}}/edit">Edit</a></li>
                                  <form action="/bills/{{$bill->id}}" method="POST" id="billForm">
                                    @csrf
                                    @method('DELETE')
                                    <li><a class="dropdown-item" onclick="deleteBill();">Delete</a></li>
                                  </form>                         
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

    function deleteBill() {
        Swal.fire({
        title: 'Do you want to delete this bill?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancel`,
        }).then((result) => {
        
            if (result.isConfirmed) {
                $('#billForm').submit();
            }
        })
    }
</script>
@stop