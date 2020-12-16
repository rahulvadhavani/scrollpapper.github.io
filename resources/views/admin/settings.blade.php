@extends('layouts.admin')
@section('content')
<?php
   $settings = $body['settings'];
   ?>
<link rel="stylesheet" type="text/css" href="{{url('assets/css/bootstrap-select.min.css')}}">
<script type="text/javascript" src="{{url('assets/js/bootstrap-select.min.js')}}"></script>
<link rel="stylesheet" href="{{url('assets/css/datepicker.min.css')}}" type="text/css">
<style type="text/css">
   a.md-button.md-default-theme:not([disabled]):hover, 
   a.md-button:not([disabled]):hover, 
   .md-button.md-default-theme:not([disabled]):hover, 
   .md-button:not([disabled]):hover {
   background-color: rgba(158,158,158,0.2);
   }
   a.md-button.md-default-theme.md-primary, 
   a.md-button.md-primary, 
   .md-button.md-default-theme.md-primary, 
   .md-button.md-primary {
   color: rgb(63,81,181);
   }
   .modal-text{
   padding: 24px;
   }
   .modal-text h2{
   margin-top:0px; 
   }
   .modal-backdrop {
   position: fixed;
   top: 0;
   right: 0;
   bottom: 0;
   left: 0;
   background-color:rgba(0,0,0,.4);
   }
   @media (min-width: 768px){
   .modal-sm {
   width:360px;
   }
   .modal-dialog {
   margin: 18% auto;
   }
   }
   .datepicker{
   top:265px !important;
   }
</style>
<script>
   var hash = window.location.hash;
   window.onload = function () {
       $("#<?php echo $body['id'] ?>").addClass("active");
   
   };
   $(document).ready(function () {
       
       if(hash=="#general"){
           $("#general-tab").trigger("click");
       }else if(hash=="#change_pwd"){
           $("#password-tab").trigger("click");
       }
   
       $('[data-toggle="tab"]').on("click",function(){
           var cur_hash = $(this).attr("href");
           var url = "{{url('admin/settings/')}}"+hash;
           window.location.hash = cur_hash;
       });
       $('[data-toggle="tooltip"]').tooltip();
   
       $('#general_save').click(function(){
           $('#g_setting').ajaxForm(function(res){
               
               if(res.flag == 1){
                   Toast(res.flag,res.msg,4000);
                   $('.general-msg').html('');
               }else{
                   $('.general-msg').html(res.msg);
               }
           }).submit();
       });
       $('#password_save').click(function () {
           $('#c_password').ajaxForm(function (res) {
               if (res.flag == 1) {
                   Toast(res.flag,res.msg);
                   $('.pwd-msg').html('');
               } else {
                   $('.pwd-msg').html(res.msg);
               }
               $('#old_password').val('');
               $('#new_password').val('');
               $('#confirm_password').val('');
           }).submit();
       });
       $('#pwd_cancel').click(function () {
           $('#old_password').val('');
           $('#new_password').val('');
           $('#confirm_password').val('');
           $('.pwd-msg').html('');
       });
   
   });
</script>
<style>
   .email-state-off {
   cursor: default;
   background: #d9534f;
   border: #d9534f;
   width: 50px;
   color: #fff;
   margin-left: 8px;
   }
   .item-header {
   font-weight: 600;
   color: #0067c8;
   font-size: 16px;
   }
   .email-state-on {
   cursor: default;
   background: #5cb85c;
   border: #5cb85c;
   width: 50px;
   color: #fff;
   margin-left: 8px;
   }
   .mgn-top{
   margin-top: 10px;
   }
</style>
<div class="row default_margin">
   <div class="panel panel-card clearfix">
      <div class="b-b b-light">
         <ul class="nav nav-lines nav-md b-info nav-tabs">
            <li class="active"><a id="general-tab" data-toggle="tab" href="#general">GENERAL</a></li>
            <li ><a id="password-tab" data-toggle="tab" href="#change_pwd">Change Password</a></li>
         </ul>
      </div>
      <div class="p-h-lg m-b-lg">
         <div class="tab-content">
            <div id="general" class="tab-pane fade  in active">
               <div class="box-row" style="display: block;">
                  <div>
                     <div class="row card-body">
                        <form method="post" id="g_setting" action="{{URL::to('admin/save-settings')}}" >
                           {{csrf_field()}}
                           <input type="hidden" name="settting_type" value="general" />
                           <div class="col-xs-12">
                              <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                                 <div class="form-group">
                                    <label class="col-sm-5 control-label">Login Url Token</label>
                                    <div class="col-sm-7 m-b-10">
                                       <input type="text" class="form-control" name="login_url_token" placeholder="Enter login url token"  value="{{$settings['login_url_token']}}" tabindex="0" aria-invalid="false" >
                                    </div>
                                 </div>

                              </div>
                              <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                                 <div class="form-group">
                                    <label class="col-sm-5 control-label">Strip Secret Key</label>
                                    <div class="col-sm-7 m-b-10">
                                       <input type="text" class="form-control" name="strip_secret_key" placeholder="Enter  Strip Secret Key"  value="{{$settings['strip_secret_key']}}" tabindex="0" aria-invalid="false" >
                                    </div>
                                 </div>
                                 
                              </div>
                              <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                                 <div class="form-group">
                                    <label class="col-sm-5 control-label">Strip Public Key</label>
                                    <div class="col-sm-7 m-b-10">
                                       <input type="text" class="form-control" name="strip_public_key" placeholder="Enter Strip Public Key"  value="{{$settings['strip_public_key']}}" tabindex="0" aria-invalid="false" >
                                    </div>
                                 </div>
                                 
                              </div>
                              <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b text-right">
                                 <div class="form-group">
                                    <div class="col-sm-12 m-b-10">
                                       <div class="no-border">
                                          <span class="text-danger general-msg" aria-hidden="true"></span>
                                          <a class="btn btn-primary" id="general_save"  aria-label="Save" tabindex="0">Save</a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div id="change_pwd" class="tab-pane fade ">
               <div class="box-row">
                  <form method="post" id="c_password" action="{{URL::to('admin/save-settings')}}" >
                     {{csrf_field()}}
                     <input type="hidden" name="settting_type" value="password" />
                     <div>
                        <div class="row card-body">
                           <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label redio-label">Old Password</label>
                                 <div class="col-sm-7 m-b-10">
                                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password" tabindex="0" aria-invalid="false" style="">
                                 </div>
                              </div>
                           </div>
                           <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label redio-label">New Password</label>
                                 <div class="col-sm-7 m-b-10">
                                    <input type="password" class="form-control " id="new_password" name="new_password" placeholder="Enter New Password" tabindex="0" aria-invalid="false" style="">
                                 </div>
                              </div>
                           </div>
                           <div class="col-xs-12 m-t-10 bg-white no-padding pad_t_b">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label redio-label">Confirm Password</label>
                                 <div class="col-sm-7 m-b-10">
                                    <input type="password" class="form-control " id="confirm_password" name="confirm_password" placeholder="Enter Confirm Password" tabindex="0" aria-invalid="false" style="">
                                 </div>
                              </div>
                           </div>
                           <div class="col-xs-12 m-t-10 bg-white no-padding">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label"></label>
                                 <div class="col-sm-7 m-b-10">
                                    <div class="modal-footer no-border pull-right">
                                       <span class="text-danger pwd-msg" aria-hidden="true"></span>
                                       <a class="btn btn-primary" id="password_save" aria-label="Save" tabindex="0">
                                       <span id="cbtn_text">Change Password</span>
                                       <span id="cspinner" style="display:none"><i class="fa fa-spinner fa-spin"></i></span>
                                       </a>
                                       <a class="btn btn-default" id="pwd_cancel" tabindex="0">Cancel</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('footer')
  
@stop