


@php
use Spatie\Permission\Models\Role;
 $roles = Role::All();    
@endphp

<div>
    <!-- User table and user field search-->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">User List</button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <!--- Table User --->
                        @if($users->count())
                            <div class="card-body">
                                <table id="dTable" class="display nowrap table table-sm table-hover" style="width: 100%">
                                    <thead class="table-dark">
                                        <tr> 
                                            <th hidden scope="col">Id</th>  
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Role</th>
                                            <th width="10%" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>                  
                                        @foreach($users as $user)
                                        <tr>
                                            <td hidden>{{$user->id}}</td>
                                            <td role="button">{{$user->name}}</td>  
                                            <td role="button">{{$user->email}}</td>
                                            @if ($user->getRoleNames()->count() > 0)
                                                <td role="button">{{$user->getRoleNames()[0]}}</td> 
                                            @else
                                            <td role="button"></td> 
                                            @endif
                                      
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if ($user->getRoleNames()->count() > 0)
                                                            <li><a class="dropdown-item editbtn" data-toggle="modal" data-target="#editModal" data-id="{{$user->id}}" data-name="{{$user->name}}" data-email="{{$user->email}}" data-role="{{$user->getRoleNames()[0]}}">Edit</a></li>
                                                        @else
                                                            <li><a class="dropdown-item editbtn" data-toggle="modal" data-target="#editModal" data-id="{{$user->id}}" data-name="{{$user->name}}" data-email="{{$user->email}}" data-role="">Edit</a></li> 
                                                        @endif
                                                   
                                                    <li><a class="dropdown-item deletebtn" data-toggle="modal" data-target="#deleteModal" data-id="{{$user->id}}">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach             
                                    </tbody>
                                </table>
                            </div>     
                            
                            <div class="card-footer">
                                {{$users->links()}}
                            </div>
                        @else
                            <div class="card-body">
                                <strong>No records</strong>
                            </div>
                
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
