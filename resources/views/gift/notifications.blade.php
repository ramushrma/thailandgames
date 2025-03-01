@extends('admin.body.adminmaster')

@section('admin')
<!-- Ensure jQuery and Bootstrap JS are loaded -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush

<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="white_shd full margin_bottom_30">
        <div class="full graph_head">
          <div class="d-flex justify-content-between">
            <h2>Notification List</h2>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">Add Notification</button> 
          </div>
        </div>
        <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            <table id="example" class="table table-striped" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $item)
                <tr>
                  <td>{{$item->id}}</td>
                  <td>{{$item->name}}</td>
                  <td>{{$item->created_at}}</td>
                  <td>
                    <a href="{{route('Notification.delete', $item->id)}}" class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
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
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('notification.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
            <div class="form-group col-ms-6">
                    <label for="notification">Notification</label>
                    <textarea class="form-control w-100" id="notification" name="notification" rows="4" placeholder="Enter notification"></textarea>
            </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Corrected Modal JavaScript -->
<script>
  $(document).ready(function() {
    $('#exampleModalCenter').on('shown.bs.modal', function () {
      $('#amount').trigger('focus');
    });
  });
   $(document).ready(function() {
    $('#edit').on('shown.bs.modal', function () {
      $('#amount').trigger('focus');
    });
  });
</script>

@endsection
