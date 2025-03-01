@extends('admin.body.adminmaster')
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