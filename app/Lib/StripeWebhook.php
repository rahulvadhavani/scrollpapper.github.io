<?php
namespace App\Lib;

class StripeWebhook{
    
    public static function action($eventData=[]){
        third_party_log("StripeWebhook PARAM ", $eventData);

        try {
            $event = isset($eventData['type'])?$eventData['type']:'';
            $object = isset($eventData['data']['object'])?$eventData['data']['object']:[];
            $id  = isset($object['id'])?$object['id']:'';

            $status = \ConstType::get_type('subscription_item.status');

            if($event=="customer.subscription.created" || $event=='customer.subscription.updated'){
                $sts = $status[$object['status']];
                $sub_date = $object['current_period_start']?date('Y-m-d H:i:s',$object['current_period_start']):null;
                $due_date = $object['current_period_end']?date('Y-m-d H:i:s',$object['current_period_end']):null;
                $updateData = [
                    'status'=>$sts,
                    'subscribe_date'=>$sub_date,
                    'due_date'=>$due_date
                ];
                \App\Models\SubscriptionItem::where('subscription_id',$id)->update($updateData);
                
            }elseif($event=="invoice.created"){
                // Send mail to user
            }else if ($event == "invoice.finalized" || $event == "invoice.upcoming") {
                
            }
            return \General::success_res();
        } catch (\Exception $ex) {
            return \General::error_res($ex->getMessage());
            third_party_log("StripeWebhook ERROR ", $ex->getMessage());
        }
    }
}