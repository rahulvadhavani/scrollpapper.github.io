<table class="table table-hover" style="border-top: 1px solid #ddd;">
   <thead>
      <tr>
         <th>#ID</th>
         <th>Name</th>
         <th>Email</th>
         <th>Mobile No.</th>
         <th>Total Subscriptions</th>
         <th>Status</th>
         <th>Created At</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      <?php
         if($total_record > 0 ){
             foreach($data as $r){
               $status = \General::type_cls_txt($r['status'],'users.status'); 
               $total = \App\Models\SubscriptionItem::get_total_active_item_by_user($r['id']);
         ?>
      <tr>
         <td>#{{$r['id']}}</td>
         <td id="name-{{$r['id']}}" data-name="{{$r['name']}}">{{$r['name']}}</td>
         <td id="email-{{$r['id']}}" data-email="{{$r['email']}}">{{$r['email']}}</td>
         <td id="mobile-{{$r['id']}}" data-mobile="{{$r['mobile']}}">{{$r['mobile']}}</td>
         <td>{{$total}}</td>
         <td id="status-{{$r['id']}}" data-status="{{$r['status']}}">
            <!-- <span class="{{ $status['cls']}}">{{ $status['txt']}}</span> -->
            <label class="ui-switch m-t-xs m-r">
               <input type="checkbox" class="user_status" data-id="{{$r['id']}}" <?php if($r['status'] == 1){ echo 'checked'; } ?> >
               <i></i>
            </label>
         </td>
         <td>{{date('Y-m-d H:i:s', strtotime($r['created_at']))}}</td>
         <td>
            <a class="uico" href="{{url('admin/user-detail/'.$r['id'])}}" tooltip="Details" style="cursor: pointer;"><i class="fa fa-info m-t-sm" style="font-size: 18px;"></i></a>
         </td>
      </tr>
      <?php 
         }
         }else{
         ?>
      <tr>
         <td colspan="7"  style="text-align: center;font-weight: bold;">No Records Found</td>
      </tr>
      <?php
         }
         ?>
   </tbody>
</table>
