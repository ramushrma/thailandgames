

@extends('admin.body.adminmaster')

@section('admin')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="white_shd full margin_bottom_30">
        <div class="full graph_head">
          <div class="d-flex justify-content-between">
            <h2>Feedback List</h2>
          </div>
        </div>
        <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            <table id="example" class="table table-striped" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th>ID</th>
                  <th>User ID</th>
                  <th>Description</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->userid }}</td>
                  <td>{{ $item->description }}</td>
                  <td>{{ $item->created_at }}</td>
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
@endsection
