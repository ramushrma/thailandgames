@extends('admin.body.adminmaster')
@section('admin')
<div class="container-fluid mt-5">
         <style>
       th{
    white-space: nowrap; 
  
    text-overflow: ellipsis;
}td{
    white-space: nowrap; 
     
    text-overflow: ellipsis;
}
    </style> 
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0 d-flex">
                        <h2>Deposit List</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Mobile</th>
                                    <th>Order Id</th>
                                    <th>USDT Amount</th>
                                    <th>Coins</th>
                                    <th>Screenshot</th>
                                    <th>Status</th>
                                    @if($id == 3)
                                    <th>Reasion</th>
                                    @endif
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deposits as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->userid }}</td>
                                    <td>{{ $item->uname }}</td>
                                    <td>{{ $item->mobile }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td> {{$item->usdt_amount}}</td>
                                    <td>{{$item->cash}}</td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#modal-{{ $item->id }}">View</a>
                                    </td>
                                    <!-- Modal -->
                                    <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Screenshot</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ $item->screenshot }}" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Bootstrap JS (For Modal) -->
                                    <script
                                        src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js">
                                    </script>
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
                                                    href="{{ route('usdt_success', $item->id) }}">Success</a>
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
            <form id="rejectForm" method="POST" action="{{ route('usdt_reject') }}">
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
{{-- Include jQuery and Bootstrap JS --}}
@endsection