@extends('admin.body.adminmaster')

@section('admin')

<div class="container-fluid mt-5">
    <div class="row">
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
       <div class="full graph_head">
          <div class="heading1 margin_0 d-flex">
             <h2>MLM Level List</h2>
        {{--    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter" style="margin-left:550px;">Add MlM Level</button> --}}
          </div>
       </div>
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
             <table id="example" class="table table-striped" style="width:100%">
                <thead class="thead-dark">
                   <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Count</th>
                      <th>Commission</th>
                      <th>Action</th>
                   </tr>
                </thead>
                <tbody>
                  @foreach($mlmlevels as $item)
                   <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->name}}</td>
                      {{-- <td><img src="{{URL::asset('storage/'.$item->image )}}" width="50 px" height="50 px"></td> --}}
                      <td>{{$item->count}}</td>
                      <td>{{$item->commission}}</td>
                      {{-- @if($item->status==1)  
                      <td><a href="{{route('product.active',$item->id)}}" title="click me for product Disable"><i class="fa fa-check-square-o green_color" aria-hidden="true" style="font-size:25px"></i></a></td>
                     @elseif($item->status==0)
                     <td><a href="{{route('product.inactive',$item->id)}}" title="click me for product Enable"><i class="fa fa-ban red_color" aria-hidden="true" style="font-size:25px"></i></td>
                      @else
                      <td> </td>
                      @endif --}}
                      {{-- <td><i class="fa fa-toggle-on" aria-hidden="true" style="font-size:30px"></i></td> --}}
                      
                      <td>
                      <i class="fa fa-edit mt-1" data-toggle="modal" data-target="#exampleModalCenterupdate1{{$item->id}}" style="font-size:30px"></i>
                      <a href="{{route('mlmlevel.delete',$item->id)}}"><i class="fa fa-trash mt-1 ml-1" style="font-size:30px;color:red;" ></i></a>
                      </td>
                      {{-- edit form --}}
                      <div class="modal fade" id="exampleModalCenterupdate1{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">Edit MLM Level</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="{{route('mlmlevel.update',$item->id)}}" method="post" enctype="multipart/form-data">
                              @csrf
                            <div class="modal-body">
                              <div class="container-fluid">
                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" placeholder="Enter name">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="count">Count</label>
                                    <input type="text" class="form-control" id="count" value="{{$item->count}}" name="count" placeholder="Enter count">
                                    @error('count')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                  </div>
                                  
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="commission">Commission</label>
                                        <input type="text" class="form-control" name="commission" value="{{$item->commission}}" id="commission" placeholder="">
                                        
                                        @error('commission')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                      </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="form-group col-md-12">
                                      <label for="discription">Description</label>
                                      <textarea  class="form-control" id="discription" name="discription" rows="4" cols="50" placeholder="">{{$item->discription}}</textarea>
                                      @error('discription')
                                      <div class="alert alert-danger">{{ $message }}</div>
                                      @enderror
                                    </div>
                                  </div>
                            </div> --}}
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                            
                          </div>
                        </div>
                      </div>
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
{{-- popup modal form --}}
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add MLM Levels</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('mlmlevel.store')}}" method="post" enctype="multipart/form-data">
          @csrf
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group col-md-6">
                <label for="count">count</label>
                <input type="text" class="form-control" id="count" name="count" placeholder="Enter count">
                @error('count')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              
            </div>
            {{-- <div class="row">
                <div class="form-group col-md-12">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" name="image" id="image" placeholder="">
                    @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                  </div>
            </div> --}}
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="commission">Commission</label>
                  <input type="text"  class="form-control" id="commission" name="commission" placeholder="">
                  @error('commission')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
        </div>
      
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>


<script>
    $('#myModal').on('shown.bs.modal', function () {
  $('#myInputs').trigger('focus')
})
</script>
 @endsection