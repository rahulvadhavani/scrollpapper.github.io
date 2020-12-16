@extends('layouts.admin')
@section('header')
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
</style>
@stop
@section('content')
<?php $counties = $body['counties']; ?>
<div class="main-filter default_margin">
  <div class="panel-group " id="accordion" >
        <div class="panel panel-default">
            <div class="panel-heading" style="height: 45px;">
            <div class="panel-title">
                <span class="col-sm-10 col-lg-11 col-md-11" style="margin-top: -5px;">Filter </span>
                <span class="col-sm-2 col-lg-1 col-md-1">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="float: right;"><button style="margin-top: -8px;" class="btn btn-icon btn-default btn-icon-sm md-ink-ripple"><i class=" fa-filter fa editable i-16"></i></button></a>
                </span>
            </div>
          </div>
          <div id="collapse1" class="panel-collapse collapse ">
            <div class="panel-body">
                <div class="col-xs-2">
                    <div class="col-lg-12 p-0 md-form-group float-label">
                        <input class="md-input" type="text" id="name">
                        <label>Name</label>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="col-lg-12 p-0 md-form-group float-label">
                        <input class="md-input" type="text" id="email">
                        <label>Email</label>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="col-lg-12 p-0 md-form-group float-label">
                        <select class="md-input has-value" id="cstatus">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="3">Suspended</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                
                <div class="col-xs-2 md-form-group">
                  <button type="button" class="btn btn-default btn-sm waves-effect " id="search_reset" tabindex="0">Reset</button>
                    <button type="button" class="btn  btn-sm md-raised indigo waves-effect " id="search_option" tabindex="0">Search</button>
                </div>
            </div>
          </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-lg-8 col-md-8">
            <div class="btn-group dropdown pagination" style="margin: 0;"> 
                <a class="btn btn-default" href="#">First</a>
                <a class="btn btn-default " href="#" ng-if="directionLinks">Previous</a>
                <a href="#" class="btn btn-default">1</a>
                <a href="#" class="btn btn-default">Next</a>
                <a href="#" class="btn btn-default">Last</a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 " style="text-align:right;">
            <span>Record per page : </span>
            <input type="text" class="md-input blue-grey  ng-touched" id="recordPerPage" value="">
           <!--  <input type="text" id="recordPerPage"  value=""/> -->
            <button type="button" class="btn md-raised btn-sm indigo waves-effect" onclick="changeRecordPerPage('{{URL::to("admin/users-filter")}}');" ><i class="mdi-av-replay editable i-16"></i>
            </button>
        </div>
    </div>
</div>
            
<div class="panel panel-default default_margin">
    <div class="" style="padding:10px;color:#607d8b;font-size:16px;"> <b>{{$body['panel_title']}}</b></div>
    <div id="table-data" class="table-responsive">
    
    </div>
</div>

<div class="modal fade" aria-hidden="true" role="dialog" id="user-modal">
   <div class="modal-dialog modal-md" style="height:25%; margin: 6% auto;">
      <form id="user_add_form" role='form' action="{{URL::to('admin/add-user')}}"  method="post">
         <input class="md-input" type="hidden" name="_token" value="{{csrf_token()}}" >
         <div class="modal-content modal-xs" >
            <div class="modal-header bottomBorder" >
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               <h4 class="modal-title" id="addNewIssueLabel">
                  <b><span id="mdl_title">Users</span></b>
               </h4>
            </div>
            <div class="modal-body box " style="background-color: #F0F0F0;" style='width:25%;height:100%' >
               <div class="box-row">
                  <div class="card">
                     <div class="row card-body">
                        <div class="col-xs-6 m-t-20 ">
                           <div class="md-form-group float-label ">
                              <input class="md-input" type="text" id="" name="name" />
                              <label>Name <span style="color: red;">*</span></label>
                           </div>
                        </div>
                        <div class="col-xs-6 m-t-20 ">
                           <div class="md-form-group float-label ">
                              <input class="md-input" type="email" id="" name="email" />
                              <label>Email <span style="color: red;">*</span></label>
                           </div>
                        </div>
                        <div class="col-xs-6 m-t-20 ">
                           <div class="md-form-group float-label ">
                              <input class="md-input" type="number" id="" name="mobile" />
                              <label>Mobile <span style="color: red;">*</label>
                           </div>
                        </div>
                        <div class="col-xs-6 m-t-20 ">
                           <div class="md-form-group float-label ">
                              <input class="md-input" type="password" id="" name="password" />
                              <label>Password <span style="color: red;">*</label>
                           </div>
                        </div>
                        <div class="col-xs-6 m-t-20 ">
                           <div class="md-form-group float-label">
                              <select class="md-input has-value" id="county_id" name="county_id">
                                @foreach($counties as $county)
                                    <option value="{{$county['id']}}">{{$county['name']}}</option>
                                @endforeach
                              </select>
                              <label>Select County <span style="color: red;">*</span></label>
                           </div>
                        </div>
                        <div class="col-xs-6 m-t-20">
                          <div class="md-form-group float-label" style="padding-top: 0px;">
                              <div class="county-plan-radio">
                                <label class="Radio">
                                  <input checked class="Radio-input u-hiddenVisually" name="payment_type" type="radio" value="0">
                                  <span class="Radio-label">Monthly Subscription</span>
                                </label>
                              </div>
                              <div class="county-plan-radio">
                                <label class="Radio">
                                  <input class="Radio-input u-hiddenVisually" name="payment_type" type="radio" value="1">
                                  <span class="Radio-label">Annual Subscription</span>
                                </label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer no-border">
               <button type="button" class="btn btn-default waves-effect" id="closeModel" data-dismiss="modal">Cancel</button>
               <button type="button" class="btn btn-primary waves-effect" id="saveInfo">
               <span id="btn_save_user">Save</span>
               <span id="spinner" style="display:none"><i class="fa fa-spinner fa-spin"></i></span>
               </button>
            </div>
         </div>
      </form>
   </div>
</div>
<a href="javascript:void(0)" class="md-btn md-fab md-fab-bottom-right pos-fix green" data-toggle="modal" data-target="#user-modal"><i class="mdi-content-add i-24" id="btn_add_user" style="line-height: inherit;"></i></a>




@endsection
@section('footer')
<script>
var url = '{{URL::to("admin/users-filter")}}';

window.onload=function(){
    filterData(url);
    
};
var searchField=["name","cstatus","email"];
$(document).ready(function(){
    $('#search_option').on('click',function(){
     
        setFilters(searchField);
     
        var status = $('#cstatus').val();
        filters.status = status;
        filters.currentPage = 1;
        filterData(url);
    });
    $('#search_reset').on('click',function(){
     
        resetFilters(searchField);
     
        filters.status = '';
        filters.currentPage = 1;
        filterData(url);
    });
    
    $('#btn_add_user').click(function(){
        $('#user_add_form')[0].reset();
    });

    $('#btn_save_user').click(function(){
        $('#user_add_form').ajaxForm(function(res){
            Toast(res.flag, res.msg);
            if (res.flag == 1) {
                setTimeout(function(){
                    window.location = getBaseURL() + '/admin/users';
                },2000);
            }
            /*$('#btn_text').show();
            $('#spinner').hide();
            $('#btn_add').attr('disabled',false);*/
        }).submit();
    });       
});

$(document).on('change', '.user_status', function() {
  // Does some stuff and logs the event to the console
  if ($(this).is(':checked')) {
      var status = 1;
  }else{
      var status = 3;
  }

  var change_url = '{{URL::to("admin/change-user-status")}}';

  var user_id = $(this).attr('data-id');
  postAjax(change_url,{status:status,user_id:user_id},function(response){
      Toast(response.flag,response.msg);
  });

});

</script>
@stop