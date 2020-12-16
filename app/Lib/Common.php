<?php

if (!function_exists('success_res')) {
    function success_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "success" : $msg;
    //        $msg = \Lang::get('response.'.$msg_id, array('name' => 'Paresh Bhai'));
        $msg_id = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 1, 'msg' => $msg);
        return $json;
    }
}

if (!function_exists('error_res')) {
    function error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "error" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 0, 'msg' => $msg);
        return $json;
    }
}
if (!function_exists('api_log')) {
    function api_log($msg = "", $args = array()) {
        $status = config('constant.ENABLE_API_LOG');
        if($status){
            if(is_array($args)){
                \Log::info($msg,$args);
            }else{
                \Log::info($msg,[$args]);
            }
        }
    }
}
if (!function_exists('third_party_log')) {
    function third_party_log($msg = "", $args = array()) {
        $status = config('constant.ENABLE_THIRD_PARTY_LOG');
        if($status){
            if(is_array($args)){
                \Log::info($msg,$args);
            }else{
                \Log::info($msg,[$args]);
            }
        }
    }
}