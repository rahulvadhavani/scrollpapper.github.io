<?php 

/*
 * Developed By : Suresh Prajapati on 23-nov-2016
 */

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable {

    
    use AuthenticableTrait;
    
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    public function getAuthIdentifierName() {
        return $this->getKeyName();
    }

    public function getAuthPassword() {
        return $this->password;
    }

    public function getRememberToken() {
        return $this->{$this->getRememberTokenName()};
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    public function setRememberToken($value) {
        $this->{$this->getRememberTokenName()} = $value;
    }
    
    public $table = 'admin';
   
    protected $hidden = array('password', 'remember_token', 'auth_token', 'device_token', 'device_type');
    public static $id_prefix = 'aid_';
    protected $fillable = array('username', 'email', 'password', 'avatar', 'mobile', 'status','remember_token');
//    protected $appends = array('role');
    
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }
    
    public static function doLogin($param){
        if(isset($param['remember']))
        {
            \Cookie::get("remember",1);
            if($param['remember']=='on')
                $param['remember']=1;
            else
                $param['remember']=0;
//            \App\Models\Admin\Settings::set_config(['sanitize_input' => $param['remember']]);
        }
        $user = User::where("email", $param['uname'])->first();
        $res['data']=$user;
        $res['flag']=0;
        if (is_null($user)) {
            $res['flag']=0;
            return $res;
        }
        if (!\Hash::check($param['password'], $user->password)) {
            $res['flag']=0;
            return $res;
        }
        if(isset($param['remember']) && $param['remember']==1)
        {
            $auth_token = \App\Models\Admin\Token::generate_auth_token();
            
            $token_data = ['admin_id' => $user->id,'token' => $auth_token,'type' => 'auth'];
            \App\Models\Admin\Token::save_token($token_data);
            \Auth::guard("admin")->loginUsingId($user->id,true);
        }
        else{
            \Auth::guard("admin")->loginUsingId($user->id);
        }
        
        \Auth::guard('user')->logout();
        $res['flag']=1;
        return $res;
    }
    
    
    public static function change_admin_password($param)
    {
        $admin_detail = self::where("id", \Auth::guard('admin')->user()->id)->first();
        $res['data']= $admin_detail;
        $res['flag']=0;
        $res['msg']="";
        
        if (is_null($admin_detail)) {
            return $res;
        }
           
        if(\Hash::check($param['old_password'],$admin_detail->password))
        {
            if($param['new_password'] == $param['confirm_password'])
            {
//                $admin_detail->username = $param['name'];
//                $admin_detail->email = $param['email'];
                $admin_detail->password = \Hash::make($param['new_password']);
                $admin_detail->save();
                
//                \App\Models\Admin\Token::delete_token();
                
                $res['data']= $admin_detail;
                $res['flag']=1;
                $res['msg']="Admin password updated successfullly.";
                return $res;
            }
            else
            {
                $res['data']= $admin_detail;
                $res['flag']=0;
                $res['msg']="New and Confirm password do not match.";
                return $res;
            }
        }
        else
        {
            $res['msg']="Wrong Old Password.";
            return $res;
//            return \General::error_res("Wrong Password.");
        }
    }
    
    public static function getProfile(){
        $admin_detail = self::where("id", \Auth::guard('admin')->user()->id)->first()->toArray();
//        dd($admin_detail);
        $res['name']=$admin_detail['username'];
        $res['email']=$admin_detail['email'];
        $res['flag']=1;
        
        return $res;
    }
        
}
