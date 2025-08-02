
<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>JobFind | Find Best Jobs</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}" />
	<link rel="shortcut icon" type="image/x-icon" href="#" />
</head>
<body data-instant-intensity="mousedown">
@include('front.layouts.header')
@yield('main')
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profilePicForm" name="profilePicForm" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image"  name="image">
                <span class="text-danger" id="image_error"></span>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            
        </form>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js')}}"></script>
<script src="{{ asset('assets/js/instantpages.5.1.0.min.js')}}"></script>
<script src="{{ asset('assets/js/lazyload.17.6.0.min.js')}}"></script>
<script src="{{ asset('assets/js/slick.min.js')}}"></script>
<script src="{{ asset('assets/js/lightbox.min.js')}}"></script>
<script src="{{ asset('assets/js/custom.js')}}"></script>

<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  $("#profilePicForm").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url:'{{ route("account.updateProfilePic") }}',
      type:'POST',
      data:formData,
      datatype:'json',
      contentType: false,
      processData: false,
      success:function(response){
        if(response.status == false){
          var errors = response.errors;
          if(errors.image){
            $("#image_error").html(errors.image);
          }
        }else{
          window.location.href = "{{ url()->current() }}";
        }
      }
    })
  })
</script>
@yield('customJs')
</body>
</html>