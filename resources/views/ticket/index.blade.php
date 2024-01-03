@extends('adminlte::page')

@section('title', 'Ticket Center')

@section('plugins.Datatables', true)

@section('plugins.Select.DataTable', true)

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-6 mt-3">
            <h2>Tickets Sets</h2>
        </div>
    </div>
</div>
@stop


@section('content')
    @php

        use App\Models\TicketSetItems;
        use App\Models\Customers;
        use App\Models\Products;
        use App\Models\AssamblyItems;

        $ticket = TicketSetItems::where('status', 0)->get();
        $contador=1;
        $cont=1;
        $i=1;
        $proItem = AssamblyItems::all();

    if (isset($_GET['setStatus'])) {
        $ticket_item = TicketSetItems::find($_GET['id']);
        
        if ( $ticket_item->status == 1) {
            $ticket_item->status = 0;
        }else {
            $ticket_item->status = 1;
        }
        
        $ticket_item->save();
        exit();

    }

    @endphp
    <div class="card">
        <div class="card-body">
            <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                <thead class="bg-dark">
                    <tr>
                        <th></th>
                        <th hidden>ID</th>
                        <th>Date</th>
                        <th># Invoice</th>
                        <th>Customer</th>
                        <th>item</th>
                        <th width="3%"></th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                    
                </thead>
                <tbody id="tbody">
                   {{--  <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-1"> --}}
                    @php
                        $index = 0;
                     @endphp
                        @foreach ($ticket as $tk)
                            <tr>
                                @php
                                    $hasItem =AssamblyItems::where('id_item_main', $tk->id_item)->get();
                                @endphp

                                <td  id="id_fila"><?php echo $contador++; ?></td>
                                <td id="id" hidden>{{$tk->id}}</td>
                                <td>{{$tk->date}}</td>
                                <td>{{$tk->num_factura}}</td>
                                <td>{{Customers::where('id', $tk->id_customer)->value('company_name');}}</td>
                                <td id="id_assembly" hidden>{{$tk->id_item}}</td>
                                <td>{{Products::where('id', $tk->id_item)->value('item_name')}}</td>
                                @if ($hasItem)
                                    <td><button type="button" class="btn btn-outline-warning" style="margin-right: 100px;" data-bs-toggle="collapse" data-bs-target="#collapseTr{{$index}}" aria-expanded="false" aria-controls="collapseTr{{$index}}"><i class="fa-regular fa-eye"></i></button></td>
                                @else
                                <td><button type="button" class="btn btn-outline-warning" style="margin-right: 100px;" data-bs-toggle="collapse" data-bs-target="#collapseTr{{$index}}" aria-expanded="false" aria-controls="collapseTr{{$index}}" hidden><i class="fa-regular fa-eye"></i></button></td>
                                @endif
                                <td><span style="margin-left: 25px;" >{{$tk->qty}}</span></td>
                                <td><input onclick="estado(this);" class="form-check-input" style="margin-left: 15px;" type="checkbox"  value="{{$tk->status}}" <?php if($tk->status == 1) echo  'checked'?>></td>
                            </tr>
                            <tr class="collapse" id="collapseTr{{$index}}">
                                <td colspan="8">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-sm table-bordered" style="width: 70%; border: 2px solid">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="assitem">
                                                    @foreach ($proItem as $item)
                                                    <tr>
                                                        <td>{{Products::where('id', $item->id_item)->value('item_name')}}</td>
                                                        <td>{{$item->qty}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $index++;
                            @endphp
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
<link href="/css/bootstrap.min.css" rel="stylesheet">
<style>
    #assitem {
  font-size: 95%;
  font-style: italic;
}
</style>
@stop

@section('js')
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">

     $( document ).ready(function() {
        setTimeout(function() {
            window.location.reload();
        }, 3600000);          
    });

    function estado(st) {
        let id= $(st).parent().parent().find('#id').html();
        if (st.checked) {
                Swal.fire({
                title: 'Do you want to change the status?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'GET',
                            async: "false",
                            data:{setStatus:1,id:id},
                            error: function (xhr, status, error) {
                                console.log(xhr.responseText);
                            },
                            success : function(data){
                                window.location.reload();
                                Swal.fire('Saved!', '', 'success')
                            }
                        });
                }
                else{
                    $(st).prop("checked", false)
                }
            })
        }
    }
</script>
@stop