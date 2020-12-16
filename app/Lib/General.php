<?php

namespace App\Lib;
use \Mycrypt;
use PragmaRX\Google2FA\Google2FA as Google2FA;
class General {

    static function error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "error" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 0, 'msg' => $msg);
        return $json;
    }

    static function success_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "success" : $msg;
//        $msg = \Lang::get('response.'.$msg_id, array('name' => 'Paresh Bhai'));
        $msg_id = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 1, 'msg' => $msg);
//        global $_EXECUTION;
//        $json['request_load'] = $_EXECUTION;
        return $json;
//        return Response::json($json);
//        Respo
    }

    static function validation_error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "validation error" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 2, 'msg' => $msg);
        return $json;
    }

    static function info_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "information" : $msg;
        $msg_id = 'info.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 3, 'msg' => $msg);
        return $json;
    }

    static function email_verify_error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "Your account is not active. Please verify your email address" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 4, 'msg' => $msg);
        return $json;
    }
    
    static function mobile_verify_error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "mobile_not_verified" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 4, 'msg' => $msg);
        return $json;
    }
    
    static function maintenance_mode_error_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "maintenance_mode_on" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 5, 'msg' => $msg);
        return $json;
    }

    static function request_token_expire_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "Request token invalid" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 7, 'msg' => $msg);
        return $json;
    }

    static function session_expire_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "Session Expired" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 8, 'msg' => $msg);
        return $json;
    }
    static function coin_disable_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "APIs for this wallet is temporary disabled. Please try after sometime " : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 9, 'msg' => $msg);
        return $json;
    }
    static function wallet_disable_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "Wallet is disabled " : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 10, 'msg' => $msg);
        return $json;
    }
    static function access_denied_res($msg = "", $args = array()) {
        $msg = $msg == "" ? "Access Denied " : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 11, 'msg' => $msg);
        return $json;
    }
    static function marchant_not_pro($msg = "", $args = array()) {
        $msg = $msg == "" ? "Please upgrade pro plan to create wallet" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array('flag' => 12, 'msg' => $msg);
        return $json;
    }
    static function _url($str) {
        if (is_string($str))
            return preg_match("/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/", $str) ? TRUE : FALSE;
        return FALSE;
    }

    static function dd($data, $exit = 0) {
        if (in_array(\App::environment(), array("production")))
            return;
        if (is_array($data) || is_object($data)) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        } else {
            echo $data . "<br>";
        }
        if ($exit == 1)
            exit;
    }

    static function rand_str($len) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-.';
        $randomString = '';
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    static function get_external_ip()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        if(\General::is_json($result))
        {
            $arr = json_decode($result,true);
            return $arr['ip'];
        }
        return "";
    }
    static function number_format($number,$dec=3){
        return number_format($number/1000,$dec,".","");
    }
    
    static function curl_call($url, $post = '', $header=[]) {
	$usecookie = __DIR__ . "/cookie.txt";
//	$header[] = 'Content-Type: application/json';
//	$header[] = "Accept-Encoding: gzip, deflate";
//	$header[] = "Cache-Control: max-age=0";
//	$header[] = "Connection: keep-alive";
//	$header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
    if ($header) {
         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);   
    }
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//	curl_setopt($ch, CURLOPT_HEADER, false);
//	curl_setopt($ch, CURLOPT_VERBOSE, false);
//	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//	curl_setopt($ch, CURLOPT_ENCODING, true);
//	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
//	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

//	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");
        
	if ($post)
	{
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$rs = curl_exec($ch);
	if(empty($rs)){
//            var_dump($rs, curl_error($ch));
            curl_close($ch);
            return false;
	}
	curl_close($ch);
//        dd($rs);
	return $rs;
//        return "Ya";
    }
    public static function post_curl($url,$param){
        $data = '';
        foreach($param as $k=>$v){
            $data .= $k.'='.$v.'&';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            $data);
        $result= curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
    static function rand_key($len) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    static function validateURL($url) {
      $pattern_1 = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";      
      if(preg_match($pattern_1, $url)){
        return true;
      } else{
        return false;
      }
    }
    public static function commonData($guard,$param) {
        
        if($param=='name' || $param=='username'){
            $user = \Auth::guard($guard)->user();
            if($user){
                $user = $user->toArray();
            }else{
                return '';
            }
            return  $user[$param];
        }    
        
        return ;
    }
    public static function status_class_msg($status,$statusList){
        $tclass['cls']='';
        $tclass['txt']='';
        $tmpClass=[];
        foreach($statusList as $key=>$value){
            if($key==$status){
                $tmpClass=  explode('|', $value);
            }
        }
        if(count($tmpClass)<2){
            $tmpClass=  explode('|',$statusList['else']);
        }
        $tclass['cls']=$tmpClass[0];
        $tclass['txt']=$tmpClass[1];
        return $tclass;
    }
    
    private static function type_color_code($type=''){
        $rules_class=array(
            0=>'text-danger',
            1=>'text-success',
            2=>'text-warning',
            3=>'text-primary',
            4=>'text-info',
            'else'=>'text-muted'
        );
        if($type!=''){
            return $rules_class[$type];
        }
        return $rules_class;
    }
    
    public static function type_cls_txt($type,$table_n_field,$paramTypeCode=[],$else=''){
        
        $typeList=ConstType::type_list($table_n_field);
        $typeColorCode=self::type_color_code();
        $nText=$else!=''?$else:'Undefined';
        $cls='';
        $txt='';
        
        if(count($typeList)<1){
            $tclass['cls']=$typeColorCode['else'];
            $tclass['txt']=$nText;
            return $tclass;
            
        }
        
        if(!isset($typeList[$type])){
            $tclass['cls']=$typeColorCode['else'];
            $tclass['txt']=$nText;
            return $tclass;
        }
        
        if(isset($paramTypeCode[$type])){
            $cls=$paramTypeCode[$type];
        }else{
            if(isset($typeColorCode[$type])){
               $cls=$typeColorCode[$type]; 
            }else{
                $cls=$typeColorCode['else'];
            }
        }
        
        $txt=$typeList[$type];
        
        
        $tclass['cls']=$cls;
        $tclass['txt']=$txt;
        return $tclass;
    }
    public static function checkUrlIsValidForLog(){
        $valid = false;
        $url = request()->path();
        $validateLogControllers  = ['api'];
        $segment = request()->segment(1);
        if(in_array($segment, $validateLogControllers)){
            $valid = true;
        }
        return $valid;
    }
}
