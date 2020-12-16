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
              <select  name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm col-2 float-right" id="recordPerPage" onchange ="changeRecordPerPage('{{URL::to("admin/category-filter")}}');">
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

<a class="btn bg-dark" style="border-radius: 50%; position: fixed; bottom: 40px; right: 30px;" data-toggle="modal" data-target="#modalLoginForm"><i class=" fa fa-plus"></i></a>
</div>
</div>


{{--  --}}
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Add Category</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
       <form id="addcategory" action="{{url('admin/add-category')}}" method="post" >
         <div class="form-group">
          @csrf
          <label for="exampleInputEmail1">Category Name :</label>
          <input type="text" class="form-control" id="category" name="category">
        </div>
        <div class="form-group">
          <label for="exampleInputFile">Category Image :</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="bg-secondary text-danger rounded"  name="category_img" id="category_img">
              {{-- <label class="custom-file-label" for="exampleInputFile">Choose file</label> --}}
            </div>
          </div>
        </div>
       </form> 

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success" id="add-category-button">Add Category</button>
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
        <button class="btn btn-danger float-right" onclick="deletecategory()";>delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updatecategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Update Category</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
       <form id="updatecategroyform" action="{{url('admin/update-category')}}" method="post" enctype="multipart/form_data">
         <div class="form-group">
          @csrf
          <label for="exampleInputEmail1">Category Name :</label>
          <input type="hidden" class="form-control" id="upcategory_id" name="category_id">
          <input type="text" class="form-control" id="upcategoryname" name="category">
        </div>
        <div class="form-group">
          <label for="exampleInputFile">Category Image :</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="bg-secondary text-danger rounded w-75"  name="category_img" id="cat_img">
              <img src="" alt="img" height="80px" width="80px" id="catimg" class="rounded" style="position: absolute; right: 10px;">
            </div>
          </div>
        </div>
      

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success"  type="submit" >Update Category</button>
      </div>
       </form> 
    </div>
  </div>
</div>

{{--  --}}
@endsection
@section('footer')
<script>
var url  ='{{url('admin/category-filter')}}';
window.onload=function(){
    filterData(url);
    
};

function editcat(id,name,img){
  $('#upcategoryname').val(name);
  $('#upcategory_id').val(id);
  $('#catimg').attr({
    src: img
  });
  $('#updatecategory').modal('show');
}

</script>

<script>
$('#add-category-button').on('click', function() {
        var token = document.getElementById('token').value;
        var file_data = $('#category_img').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('category',document.getElementById('category').value);
        $.ajax({
                url         : '{{url('admin/add-category')}}',
                cache       : false,
                contentType : false,
                processData : false,
                data        : form_data,
                headers: {
                    'X-CSRF-Token': token 
                },                        
                type        : 'post',
                success     : function(output){
                   if(output.flag == 1){
                   $('#modalLoginForm').modal('hide');
                   $('#addcategory').trigger('reset');
                   filterData(url);
                   notify(res.flag,res.msg);
                   }else{
                   notify(res.flag,res.msg);
                   }
                }
         });
    });

</script>
<script>
  function deleteCat(id){
  $('#deletemodal').modal('show');
  $('#delete').val(id);
}

 function deletecategory(){
 $.ajax({
   url: '{{url('admin/delete-category')}}',
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
  $('#updatecategroyform').submit(function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      $.ajax({
                type:'POST',
                url: "{{ url('admin/update-category')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (res) => {
                  if(res.flag == 1){
                   $('#updatecategory').modal('hide');
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