
@extends('admin.body.adminmaster')

@section('admin')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <form style="margin-top:200px;" method="post" action="{{route('notification_store')}}">
   @csrf
 
     
                                    <label for="accumulated_amount">Name</label>
                                    <input type="text" class="form-control" id="accumulated_amount" name="name" value="">  <br>
                            
                                  
     <label for="accumulated_amount">Description</label>
     <textarea class="form-control" id="editor" name="disc">
        
       </textarea><br>
      
     <button class="btn btn-primary" type="submit" style="float:right; margin-right:5%;">Submit</button>
 </form><br><br>
 
  
 
<script src="https://shoptriangle.in/public/assets/back-end/js/vendor.min.js"></script>

    
    <script src="https://shoptriangle.in/vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="https://shoptriangle.in/vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor({
            contentsLangDirection : 'ltr',
        });
    </script>
 @endsection