@extends('admin.body.adminmaster')
<<<<<<< HEAD
@section('admin')
<div class="container-fluid mt-5">
    <div class="row">
 <style>
 th{
    white-space: nowrap; 
  
    text-overflow: ellipsis;
}td{
    white-space: nowrap; 
     
    text-overflow: ellipsis;
}
  </style>
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0 d-flex">
                        <h2>Withdrawal List</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>UserId</th>
                                     <th>Perform ID</th>
                                   <th>Performed By</th>
                                   
                                    <th>Beneficiary Name</th>
                                     <th>Mobile</th>
                                     <th>Order Id</th>
                                    <th>INR Amount</th>
                                    <th>USDT Amount</th>
                                    <th>Final Payable Amount </th>
                                    <th>Final Payable USDT </th>
                                    <th>Usdt Wallet Address</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widthdrawls as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->perform_id }}</td>
                                    <td>{{ $item->perform_role }}</td>
                                    <td>{{ $item->beneficiary_name }}</td>
                                     <td>{{ $item->mobile }}</td>
                                     <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->usdt_amount }}</td>
                                    <td>{{ $item->final_payable_amt }}</td>
                                    <td>{{ $item->final_payable_usdt }}</td>
                                    <td>{{ $item->user_usdt_address }}</td>
                                    
                                    <td>
                                        @if($item->status == 1)
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Pending
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item text-success"
                                                    href="{{ route('usdt_widthdrawl.success', $item->id) }}">Success</a>
                                                <a class="dropdown-item text-danger" href="javascript:void(0);" data-toggle="modal"
                                                    data-target="#rejectModal" data-id="{{ $item->id }}">
                                                    Reject
                                                </a>
                                            </div>
                                        </div>
                                        @elseif($item->status == 2)
                                        <button class="btn btn-success">Success</button>
                                        @elseif($item->status == 3)
                                        <button class="btn btn-danger">Reject</button>
                                        @else
                                        <span class="badge badge-secondary">Unknown Status</span>
                                        @endif
                                    </td>
                                    @if($id == 3)
                                    <td style="font-size: 12px; color: red;">{{ $item->reason }}</td>
                                    @endif
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST" action="{{ route('usdt_widthdrawl.reject') }}">
                @csrf
                <input type="hidden" name="id" id="rejectId"> <!-- ID Hidden Field -->
                <div class="modal-body">
                    <label for="reason">Reason:</label>
                    <textarea class="form-control" name="reason" id="reason" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#rejectModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id'); // Button se ID lein
        $('#rejectId').val(id); // Hidden input me set karein
    });
});
</script>
@endsection
=======

@section('admin')

    <style>
  @import url("https://fonts.googleapis.com/css?family=Montserrat:400,400i,700");

body {
        background-color: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: montserrat;
}
.dropbtn {
        font-family: montserrat;
        background-color: #222228;
        color: white;
        padding: 5px;
        font-size: 16px;
        border: none;
        border-radius: 10px 10px 10px 10px;
        width: 100px;
        box-shadow: 0px 0px 100px rgba(190, 200, 255, 0.6);
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
        color: black;
 position: relative;
        display: inline-block;
        width: 100px;
        border-radius: 10px 10px 10px 10px;
        z-index: 1;
}

.dropdown-content {
        display: none;
        position: absolute;
        background-color: #222228;
        min-width: 100px;
        z-index: 1;
        border-radius: 0px 0px 14px 14px;
        box-shadow: 0px 0px 100px rgba(190, 200, 255, 0.25);
}

.dropdown-content a {
        color: white;
        padding: 5px 8px;
        text-decoration: none;
        display: block;
        border-radius: 10px;
        margin: 2px;
}
/* Change color of dropdown links on hover */
.dropdown-content a:hover {
        background-color: #33333f;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
        display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
        background-color: #222228;
        border-radius: 10px 10px 0px 0px;
        border-bottom: none;
}

</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" 
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<div class="container-fluid">
    <div class="row">

<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
       <div class="full graph_head">
          <div class="heading1 margin_0 d-flex">
             <h2>Withdrawl List</h2>
            <!-- <form action="{{route('widthdrawl.all_success')}}" method="post">-->
            <!--     @csrf-->
            <!--<button type="submit" class="btn btn-primary"  style="margin-left:550px;">All Approve</button> -->
            <!--</form>-->
          </div>
       </div>
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
             <table id="example" class="table table-striped" style="width:100%">
                <thead class="thead-dark">
                   <tr>
                      <th>Id</th>
                      <th>UserId</th>
                      <th>Beneficiary Name</th>
                      <th>INR Amount</th>
                      <th>Mobile</th>
                      <th>Usdt Wallet Address</th>
                      <th>Order Id</th>
                      <th>Status</th>
                      <th>Date</th>
 </tr>
                </thead>
                <tbody>
                  @foreach($widthdrawls as $item)
                   <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->user_id}}</td>
                      <td>{{$item->beneficiary_name}}</td>
                       <td>{{$item->amount}}</td>
                         <td>{{$item->mobile}}</td>   
                      <td>{{$item->usdt_wallet_address}}</td>
                      <td>{{$item->order_id}}</td>
                      @if($item->status==1)  
                      <td>

                         <div class="dropdown">
                          <button class="dropbtn">Pending</button>
                          <div class="dropdown-content">
                            <a href="{{route('usdt_widthdrawl.success',$item->id)}}">Success</a>
                            <a href="{{route('widthdrawl.reject',$item->id)}}">Reject</a>

                          </div>
                        </div>
                      </td>
                     @elseif($item->status==2)
                     <td><button class="btn btn-success">Success</button></td>
                      @elseif($item->status==3)
                     <td><button class="btn btn-danger">Reject</button></td>
 @else
                      <td>
                        <!--<select class="form-control">-->
                        <!--  <option>Pending</option>-->
                        <!--  <option>Success</option>-->
                        <!--  <option>Reject</option>-->
                        <!--</select>-->
                      </td> 
                      @endif


                      <td>{{$item->created_at}}</td>
                   </tr>
                  @endforeach
                </tbody>
             </table>
          </div>
       </div>
    </div>
 </div>
</div>
</div> 
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" 
integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" 
integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
 @endsection

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
