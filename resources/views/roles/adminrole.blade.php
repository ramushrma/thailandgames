@extends('admin.body.adminmaster')

@section('admin')



<div class="container-fluid mt-5">
    <div class="row">
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
				<div class="full graph_head">
					<div class="row justify-content-between" style="background-color: #f0f0f0; padding: 10px; border-radius: 5px;">
						<form id="filterForm" class="col-auto" method="post" action="{{route('allroles')}}">
							@csrf
							<div class="row align-items-center">
								<div class="col-auto">
									<h5 style="color: #333;">
                                       @if($admin == 2 || $vendor == 3)
                                            @if($admin == 2)
                                                Vendor Details
                                            @elseif($vendor == 3)
                                                Users Details
                                            @endif
                                        @elseif($role == 2)
                                            Admin Details
                                        @elseif($role == 3)
                                            Vendor Details
                                        @else
                                            User Details
                                        @endif
                                        </h5>
								</div>
								<div class="col-auto">
	<input type="text" class="form-control" id="dateFilter" name="u_id" placeholder="Enter user id" style="background-color: #fff; color: #333;">
								</div>
								<div class="col-auto">
   <input type="text" class="form-control" id="dateFilter" name="mobile" placeholder="Enter mobile number" style="background-color: #fff; color: #333;">
								</div>
								<div class="col-auto">
		<button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Submit</button>
								</div>
							</div>
						</form>
						<div class="col-auto">
						<form method="post" action="">
								@csrf
								<button type="submit" class="btn btn-secondary">Reset Filters</button>
					    </form>
					  
						</div>
					</div>
				</div>
				
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            {{-- <form action="" method="post">
              <input type="hidden" name="_token" value="mxanMQCY0Peqj7fCBeqZaqDaJnZTo1EZgtRhJekH" autocomplete="off">
              <div class="card-body row">
                  <div class="col-md-2">
                      <div class="form-group">
                          <label> From Date:</label> 
                          <input type="date" class="form-control" name="fdate" value="2023-07-12"> 
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          <label> To Date:</label> 
                          <input type="date" class="form-control" name="tdate" value="2024-01-11"> 
                          <span class="help-block"></span>
                      </div>
                  </div>
              
                  <div class="col-md-2" style="margin-top: 27px;">
                  <button class="btn btn-success" type="submit">Apply Filter</button>
                  </div>
                    
              </div>
          </form> --}}
             <table id="example" class="table table-striped" style="width:200%">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>User Type</th>
                        <th>User_name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Password</th>
                        @if($vendor !== 3)
                        <th>Permission</th>
                        @else
                        <th></th> <!-- Blank th -->
                        @endif
                        
                        <th> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wallet</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                        @if($vendor !== 3)
                        <th>
                            @if($role == 2)
                                Inside Admin
                            @elseif($role == 3)
                                Inside Vendor
                            @endif
                        </th>
                        @else
                        <th></th> <!-- Blank th -->
                        @endif
                    </tr>
                </thead>

                 <tbody>
                                @foreach ($users as $item )
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        {{ $item->role_id == 2 ? 'Admin' : 
                                           ($item->role_id == 3 ? 'Vendor' : 'User') }}
                                    </td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->mobile}}</td>
                                    <td>{{$item->email}}</td>
                                      <td>{{$item->password}}
                                        <i class="fa fa-edit mt-1 ml-3" data-toggle="modal"
                                            data-target="#exampleModalCenterupdate1{{$item->id}}"
                                            style="font-size:20px"></i>

                                        <div class="modal fade" id="exampleModalCenterupdate1{{$item->id}}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Change
                                                            Password</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('password.store',$item->id)}}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="wallet">Change Password</label>
                                                                        <input type="text" class="form-control" id=""
                                                                            name="password"
                                                                            placeholder="Enter Password">
                                                                        @error('password')
                                                                        <div class="alert alert-danger">{{ $message }}
                                                                        </div>
                                                                        @enderror
                                                                    </div>
                                                                    @php

                                                                    $user =
                                                                    DB::table('users')->whereNull('email')->whereNull('password')->where('id',
                                                                    $item->id)->first();
                                                                    @endphp

                                                                    @if($user)
                                                                    <div class="form-group col-md-6">
                                                                        <label for="wallet">Sponser mobile no </label>
                                                                        <input type="text" class="form-control" id=""
                                                                            name="sponser_mobile"
                                                                            placeholder="Enter Sponser mobile">
                                                                        @error('sponser_mobile')
                                                                        <div class="alert alert-danger">{{ $message }}
                                                                        </div>
                                                                        @enderror
                                                                    </div>
                                                                    @endif


                                                                </div>


                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Submit</button>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if($vendor !==3)
                                    <td>
                                      
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal{{$item->id}}">
                                            View Permission
                                        </button>
                                        <div class="modal fade" id="modal{{$item->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('update.permission', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5>Update Permission</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                    
                                                        <div class="modal-body">
                                                            @foreach($permission as $per)
                                                                <label>
                                                                    <input type="checkbox" name="permissions[]" value="{{ $per->id }}"
                                                                    {{ in_array($per->id, json_decode($item->permissions) ?? []) ? 'checked' : '' }}>
                                                                    {{ $per->name }}
                                                                </label><br>
                                                            @endforeach
                                                        </div>
                                    
                                                        <div class="modal-footer">
                                                            <button class="btn btn-success btn-sm">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                   @else
                                    <td></td> <!-- Blank td -->
                                    @endif
                                    
                                    <td style="text-align: center; vertical-align: middle;">{{$item->wallet}}
                                        <div>
                                            <div class="btn btn-info btn-sm"> <i class="fa fa-plus" data-toggle="modal"
                                                    data-target="#exampleModalCenter{{$item->id}}"
                                                    style="font-size:20px"></i></div>
                                            <div class="btn btn-danger btn-sm"> <i class="fa fa-minus"
                                                    data-toggle="modal" data-target="#subtractWalletModal{{$item->id}}"
                                                    style="font-size:20px"></i></div>
                                        </div>
                                        <div class="modal fade" id="exampleModalCenter{{$item->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Add Wallet
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('wallet.store',$item->id)}}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="wallet">Wallet Amount</label>
                                                                        <input type="text" class="form-control"
                                                                            id="wallet" name="wallet" value=""
                                                                            placeholder="Enter Amount">
                                                                        @error('wallet')
                                                                        <div class="alert alert-danger">{{ $message }}
                                                                        </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Submit</button>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Subtract Wallet Modal -->
                                        <div class="modal fade" id="subtractWalletModal{{$item->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="subtractWalletModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="subtractWalletModalTitle">Subtract
                                                            Wallet</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('wallet.subtract', $item->id)}}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="wallet">Wallet Amount</label>
                                                                        <input type="text" class="form-control"
                                                                            id="wallet" name="wallet" value=""
                                                                            placeholder="Enter Amount">
                                                                        @error('wallet')
                                                                        <div class="alert alert-danger">{{ $message }}
                                                                        </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                  
                                   <td>{{$item->created_at}}</td>
@if($item->status == 1)
<td>
    <a href="{{route('user.inactive',$item->id)}}" title="click me for order Disable">
        <i class="fa fa-check-square-o green_color" aria-hidden="true" style="font-size:30px"></i>
    </a>
</td>
@elseif($item->status == 0)
<td>
    <a href="{{route('user.active',$item->id)}}" title="click me for order Enable">
        <i class="fa fa-ban red_color" aria-hidden="true" style="font-size:30px"></i>
    </a>
</td>
@else
<td>
    <span class="text-danger">No Action</span> <!-- Ye Blank td ke jagah text diya -->
</td>
@endif

                                    <td>
                                        <a href="{{route('userdetail',$item->id)}}" class=""><i
                                                class="fa fa-eye mt-1 ml-2" style="font-size:30px"></i></a>
                                    </td>
                                   <td>
                                        @if($item->role_id == 2)
                                            <form action="{{ route('allroles') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="admin_id" value="{{ $item->id }}">
                                                <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                    <i class="fa fa-caret-down mt-1 ml-2" style="font-size:20px; color:green;">
                                                        {{ DB::table('users')->where('admin_id', $item->id)->count() }} Vendor
                                                    </i>
                                                </button>
                                            </form>
                                        
                                        @elseif($item->role_id == 3)
                                            <form action="{{ route('allroles') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="vendor_id" value="{{ $item->id }}">
                                                <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                    <i class="fa fa-caret-down mt-1 ml-2" style="font-size:20px; color:blue;">
                                                        {{ DB::table('users')->where('vendor_id', $item->id)->count() }} Users
                                                    </i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
              
             </table>
			  
			  
			  		<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $users->url(1) }}" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo;</span>
            </a>
        </li>
        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @php
            $half_total_links = floor(9 / 2);
            $from = $users->currentPage() - $half_total_links;
            $to = $users->currentPage() + $half_total_links;

            if ($users->currentPage() < $half_total_links) {
                $to += $half_total_links - $users->currentPage();
            }

            if ($users->lastPage() - $users->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($users->lastPage() - $users->currentPage()) - 1;
            }
        @endphp

        @for ($i = $from; $i <= $to; $i++)
            @if ($i > 0 && $i <= $users->lastPage())
                <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <li class="page-item {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $users->url($users->lastPage()) }}" aria-label="Last">
                <span aria-hidden="true">&raquo;&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
			  
			  
          </div>
       </div>
    </div>
 </div>
</div>
</div> 
	
	
  <!-- DataTables JS -->
 
 <script>
    $('#myModal').on('shown.bs.modal', function () {
  $('#myInputs').trigger('focus')
})
</script> 

 @endsection

