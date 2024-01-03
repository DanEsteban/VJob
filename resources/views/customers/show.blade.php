@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
<div class="container-fluid bg-white shadow"  style="height: 5rem;">
    <div class="row align-items-center">
        <div class="bg-white col-md-8 mt-3">
            <h2>Customer</h2>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4>{{$customer->company_name}}</h4>
            </div>
            <div class="col">
                <center>
                    <a href="/customers/{{$customer->id}}/edit" class="btn btn-sm btn-outline-danger">Edit Customer</a>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-danger">More</button>
                        <button type="button" class="btn btn-sm btn-outline-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                          <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createContact">Contacts</a></li>
                          <hr>
                          <li><a class="dropdown-item" href="/orders/create">Estimate</a></li>
                          <li><a class="dropdown-item" href="#">Invoice</a></li>
                        </ul>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                         <div class="row">
                            <strong><p>Billing Address</p></strong>
                            <hr style="width: 93%">
                            <p>{{$customer->billto_street}}</p>
                            <p>{{$customer->billto_company}}</p>
                            <p>{{$customer->billto_city}} - {{$customer->billto_postal}}</p> 
                            <p>{{$customer->billto_state}}</p>
                         </div>
                         <br>                       
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <strong><p>Contact List</p></strong>
                            <hr style="width: 93%">
                         </div>
                         <div id="div_contact">
                            @foreach ($contacts as $contact)
                                <div class="row">
                                    <p><strong>Name:</strong> {{$contact->name}}</p>
                                    <p><strong>Email:</strong> {{$contact->email}}</p>
                                    <p><strong>Phone:</strong> {{$contact->phone}}</p>
                                    <div class="row">
                                        <button onclick="deleteContact({{$contact->id}}, this);" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                                <br>
                            @endforeach
                         </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <strong><p>Notes</p></strong>
                                </div>
                                <div class="col-md-1">
                                    <center>
                                        <button onclick="saveNote({{$customer->id}});" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-floppy-disk"></i></button>
                                    </center>
                                </div>
                                <hr>
                            </div>
                            @if (isset($notes->note))
                                <textarea class="form-control form-control-sm" name="customer_notes" id="customer_notes" cols="84" rows="4" style="resize: none">{{$notes->note}}</textarea>
                            @else
                                <textarea class="form-control form-control-sm" name="customer_notes" id="customer_notes" cols="84" rows="4" style="resize: none"></textarea>
                            @endif
                           
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="container">
                        <div class="row bg-danger">
                           <strong><p class="mt-2">Total Unpaid: $0.00 USD</p></strong>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="container" style="border: 1px solid gray; height: 200px">
                        <table class="table table-sm table-hover">
                            <thead class="bg-dark">
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Reference</th>
                                <th>Total</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="createContact" tabindex="-1" role="dialog" aria-labelledby="createContactLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <img src="/img/logo.png" width="30px" class="rounded me-2" alt="...">
          <h5 class="modal-title">New Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">                          
                <div class="row g-3">
                    <div class="col">
                        <label for="name" class="form-control-sm"><b>Name:</b></label>
                        <div class="input-group">
                            <input autocomplete="off" class="form-control" id="name" name="name" type="text" value="">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col">
                        <label for="email" class="form-control-sm"><b>Email:</b></label>
                        <div class="input-group">
                            <input autocomplete="off" class="form-control" id="email" name="email" type="text" value="">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col">
                        <label for="phone" class="form-control-sm"><b>Phone:</b></label>
                        <div class="input-group">
                            <input autocomplete="off" class="form-control" id="phone" name="phone" type="text" value="">
                        </div>
                    </div>
                </div>
            </div>  
        </div>  
            <div class="modal-footer">
                <button type="subtmit" onclick="saveContact({{$customer->id}});" class="btn btn-outline-primary" data-bs-dismiss="modal">Save</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>

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
<script type="text/javascript">
    function saveNote(id) {
        let note = $('#customer_notes').val();
        $.ajax({
            type:'POST',
            url:'/operations/customer/notes/' + id,
            async:false,
            data:{
                "_token": "{{ csrf_token() }}",
                note:note
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                Swal.fire('Updated!', '', 'success')
            }
        });
    }

    function saveContact(id) {
        let name = $('#createContact #name').val();
        let email = $('#createContact #email').val();
        let phone = $('#createContact #phone').val();
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/operations/customer/contacts/' + id,
            async:false,
            data:{
                "_token": "{{ csrf_token() }}",
                name:name,
                email:email,
                phone:phone
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                const div = document.createElement("div");
                div.classList.add('row');

                const p_name = document.createElement("p");
                p_name.innerHTML = '<strong>Name:</strong> ';
                p_name.append(data['name']);

                const p_email = document.createElement("p");
                p_email.innerHTML = '<strong>Email:</strong> ';
                p_email.append(data['email']);

                const p_phone = document.createElement("p");
                p_phone.innerHTML = '<strong>Phone:</strong> ';
                p_phone.append(data['phone']);

                const div_button = document.createElement("div");
                div.classList.add('row');

                const button = document.createElement("button");
                button.setAttribute("onclick", "deleteContact('" + data['id'] + "', this);");
                button.innerHTML = '<i class="fa-solid fa-trash-can"></i> ';
                button.classList.add('btn', 'btn-sm', 'btn-outline-danger');

                div.append(p_name, p_email, p_phone, button);
                $('#div_contact').append(div);

                $('#createContact #name').val(" ");
                $('#createContact #email').val(" ");
                $('#createContact #phone').val(" ");

                Swal.fire('Updated!', '', 'success')
            }
        });
    }

    function deleteContact(id, objeto) {       
        Swal.fire({
        title: 'Do you want to delete this contact?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancel`,
        }).then((result) => {
       
            if (result.isConfirmed) {
                $.ajax({
                    type:'GET',
                    url:'/operations/customer/contacts/delete/' + id,
                    async:false,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success : function(data){
                        $(objeto).parent().parent().remove()
                        Swal.fire('Deleted!', '', 'success')
                    }
                });
            } 
        })
    }
</script>
@stop