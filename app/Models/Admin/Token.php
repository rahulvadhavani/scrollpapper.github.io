<?php 

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Token extends \Eloquent {
    
    public $table = 'admin_token';
    protected $hidden = [];
    protected $fillable = array('type','admin_id','token', 'ip', 'ua','status');
    
    
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }
    
    
    public static function inactive_token($type)
    {
        $token = self::active()
                ->where("type","=",$type)
                ->where("ua","=",\Request::server("HTTP_USER_AGENT"))
                ->where("ip","=",\Request::getClientIp())
                ->get()->first();
        if(!is_null($token))
        {
            $token->status = 0;
            $token->save();
        }
    }
    
    public static function generate_auth_token()
    {
        static $call_cnt = 0;
        if($call_cnt > 10)
            return "";
        ++$call_cnt;
        $token = \General::rand_str(15);
        $user = self::active()->where("type",'=',"auth")->where("token",'=',$token)->first();
        if(isset($user->token))
        {
            return self::generate_auth_token();
        }
        return $token;
    }
    
    public static function save_token($param)
    {
        
        $token = new Token();
        $token->fill($param);
        $token->ip = \Request::getClientIp();
        $token->ua = \Request::server("HTTP_USER_AGENT");
        $token->status = isset($param['status']) ? $param['status'] : 1;
        $id = $token->save();
        return \General::success_res();
    }
    public static function delete_token()
    {
//        dd(\Auth::guard('admin'));
        $token = self::where('admin_id', \Auth::guard('admin')->user()->id)->delete();
        return \General::success_res();
    }
    
    public static function is_active($type,$token)
    {
        $user = self::active()->where("type",'=',$type)->where("token",'=',$token)->first();
        if(isset($user->token))
        {
            return TRUE;
        }
        return FALSE;
    }
    
    public static function get_active_token($token_type)
    {
        $token = self::active()
                ->where("type","=",$token_type)
                ->where("ua","=",\Request::server("HTTP_USER_AGENT"))
                ->where("ip","=",\Request::getClientIp())
                ->first();
        if(!is_null($token))
        {
            $token = $token->toArray();
            return $token['token'];
        }
        return FALSE;
    }
        
}
