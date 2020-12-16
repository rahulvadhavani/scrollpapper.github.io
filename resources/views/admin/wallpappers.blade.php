@extends('layouts.admin')
@section('header')

@stop
@section('content')

<style type="text/css">
#preview img{
   margin: 5px;
}
    a.md-button.md-secondary-theme:not([disabled]):hover, 
    a.md-button:not([disabled]):hover, 
    .md-button.md-secondary-theme:not([disabled]):hover, 
    .md-button:not([disabled]):hover {
        background-color: rgba(158,158,158,0.2);
    }
    a.md-button.md-secondary-theme.md-primary, 
    a.md-button.md-primary, 
    .md-button.md-secondary-theme.md-primary, 
    .md-button.md-primary {
        color: rgb(63,81,181);
    }
    .active{
      background: red!important;
    }
  
</style>
<div class="container-fluid">
 <div class="container-fluid p-0">     
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{$body['label']}}</h6>
        </div>
        <div class="row mt-3 px-4">
        
          <div class="col-lg-6 col-md-8">
              <div class="btn-group dropdown pagination" style="margin: 0;"> 
                  <a class="btn-sm px-3 btn-secondary ml-1 page_no" href="#">First</a>
                  <a class="btn-sm px-3 btn-secondary ml-1 page_no " href="#" ng-if="directionLinks">Previous</a>
                  <a href="#" class="btn-sm px-3 btn-secondary ml-1 page_no">1</a>
                  <a href="#" class="btn-sm px-3 btn-secondary ml-1 page_no">Next</a>
                  <a href="#" class="btn-sm px-3 btn-secondary ml-1 page_no">Last</a>
              </div>
          </div>
       
          <div class="col-lg-6 col-md-4">
            <div class="dataTables_length" id="dataTable_length">
              <select  name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm col-2 float-right" id="recordPerPage" onchange ="changeRecordPerPage('{{URL::to("admin/wallpapper-filter")}}');">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50" selected="">50</option>
              </select>
           </div>
         </div>
       </div>
        <div class="card-body">

            <div class="table-responsive " id="table-data">
               
            </div>

        </div>
    </div>

</div>
  

<!-- Preview -->
<div id='preview'></div>

<!-- Content Row -->
<div class="row">

<a class="btn bg-dark" style="border-radius: 50%; position: fixed; bottom: 40px; right: 30px;" data-toggle="modal" data-target="#addwallpappermodal"><i class=" fa fa-plus"></i></a>
</div>
</div>


{{--  --}}
<div class="modal fade" id="addwallpappermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Add wallpapper</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <form method='post' action='' enctype="multipart/form-data" id="upload-image">
           @csrf
              <div class="form-group my-2 w-50">
                  <label for="exampleSelectBorder">Category</label>
                  <select class="custom-select form-control-border" id="category" name="category" required="">
                    @if (isset($body['category']) && !empty($body['category']))
                      <option disabled="" selected="">select category</option>
                    @foreach ($body['category'] as $r)
                      <option value="{{$r['id']}}">{{$r['name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
               <div class="form-group mt-5">
                <label for="exampleSelectBorder">Upload Multiple Images</label>
                <br><br><br>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" id='files' name="files[]" multiple class="bg-gray-500 p-5 border-success rounded text-center text-danger" required=""><br>
                  </div>
                  <div class="input-group-append">
                  </div>
                </div>
              </div>
            <br><br>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success" id="submit">Add Wallpapper</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <form action="" id="deleteform">
          @csrf
        <input type="hidden" name="delete" id="delete">
        </form>
        Are You sure You want to delete this record
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success float-left "  data-dismiss="modal" aria-label="Close">cancle</button>
        <button class="btn btn-danger float-right" onclick="deleterecord()";>delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updatewallpapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-lg" role="document" style="overflow: hidden;">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Update Wallapper</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
       <form id="updatewallpapperform" action="{{url('admin/update-category')}}" method="post" enctype="multipart/form_data">
         <div class="form-group">
          @csrf
          <input type="hidden" class="form-control w-50" id="upcatewallpapper_id" name="wallppper_id">
          <div class="form-group my-2 w-50">
         <label>Category</label>
            <select class="custom-select form-control-border" id="update_category" name="category" required="">
              @if (isset($body['category']) && !empty($body['category']))
                <option disabled="" selected="">select category</option>
              @foreach ($body['category'] as $r)
                <option value="{{$r['id']}}">{{$r['name']}}</option>
              @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputFile">Wallpapper Image :</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="bg-secondary text-danger rounded w-50"  name="wallpapper_img" id="">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success"  type="submit" >Update Wallpapper</button>
      </div>
       </form> 
       <img src="" alt="img"  width="25%" id="wallimg" class="rounded" style="position: absolute; right: 5%; top:30%;">
    </div>
  </div>
</div>

{{--  --}}
@endsection
@section('footer')
<script>
$(document).ready(function(){

$('#submit').click(function(){

   var form_data = new FormData();
   var token = document.getElementById('token').value;
 
   form_data.append('category',document.getElementById('category').value);
   // Read selected files
   var totalfiles = document.getElementById('files').files.length;
   for (var index = 0; index < totalfiles; index++) {
      form_data.append("files[]", document.getElementById('files').files[index]);
   }

   // AJAX request
   $.ajax({
     url: '{{url('admin/upload-image')}}', 
     type: 'post',
     data: form_data,
     contentType: false,
     headers: {
                    'X-CSRF-Token': token 
               },
     processData: false,
     success: function (response) {
      if(response.flag == 1){
      $("#upload-image").trigger('reset');
      $("#addwallpappermodal").modal('hide');
      notify(response.flag,response.msg);
       filterData(url);
      }else{
      notify(response.flag,response.msg);
      }
    }
   });
});

});
</script>
<script>
var url  ='{{url('admin/wallpapper-filter')}}';
window.onload=function(){
    filterData(url);
    
};

function editwallpapper(id,img,category,cat_id){

  $('#upcatewallpapper_id').val(id);
  $('#update_category').val(cat_id);
  $('#wallimg').attr({
    src: img
  });
  $('#updatewallpapper').modal('show');
}
</script>
<script>
  function deletewallpapper(id){
  $('#deletemodal').modal('show');
  $('#delete').val(id);
}

 function deleterecord(){
 $.ajax({
   url: '{{url('admin/delete-wallpapper')}}',
   type: 'POST',
   data: $('#deleteform').serialize(),
   success: function(res){
       if(res.flag == 1){
       $('#deletemodal').modal('hide');
       $('#deleteform').trigger('reset');
       filterData(url);
        notify(res.flag,res.msg);
       }else{
        notify(res.flag,res.msg);
     }

   }
 });
};

</script>
<script>
  $('#updatewallpapperform').submit(function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      $.ajax({
                type:'POST',
                url: "{{ url('admin/update-wallpapper')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (res) => {
                  if(res.flag == 1){
                   $('#updatewallpapper').modal('hide');
                   filterData(url);
                   $(this).trigger('reset');
                   notify(res.flag,res.msg);
                   }else{
                   notify(res.flag,res.msg);
                   }
               
            }
      });
  });
  $('#update-category-button').on('click', function(event) {
   
        var token = document.getElementById('token').value;
        var file_data = $('#cat_img').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        alert(token); 
      
 });


</script>
@stop