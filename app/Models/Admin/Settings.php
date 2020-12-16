<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Settings extends \Eloquent
{
   protected $table = 'settings';
   
    //protected $hidden = array('id', 'name', 'val', 'autoload');
    protected $fillable = ['name', 'val', 'autoload'];
        
    public $timestamps = false;
    
    protected $hidden = [];
        
        public static function get_config($name = "")
        {
            $data = [];
            if(is_string($name) && $name != "")
            {
//                dd($name,self::where("name",$name)->skip(0)->take(1)->get()->toArray());
                $settings = self::where("name",$name)->skip(0)->take(1)->get();
                
            }
            else if(is_array($name) && !empty ($name))
            {
                $settings = self::whereIn("name",$name)->get();
            }
            else
            {
                $settings = self::where("autoload","=","1")->get();
            }
            if(isset($settings[0]) && isset($settings[0]->name))
            {
                $settings = $settings->toArray();
                
                foreach ($settings as $setting) {
                    $data[$setting['name']] = $setting['val'];
                }
            }
            return $data;
        }
        
        public static function set_config($configs = []) {
                
            foreach ($configs as $key => $val) {
                $setting = self::where("name","=",$key)->first();
                if(isset($setting->name))
                {
                    $setting->val = $val;
                    $setting->save();
                }
            }
            return \General::success_res();
        }
        
        public static function sanitizeInput()
        {
            $res = \General::success_res();
            $trimed_input = \App\Models\Setting::get_config('sanitize_input');
            $res['data'] = $trimed_input;
            if(!empty($trimed_input) && $trimed_input['sanitize_input'] == 0)
            {
                $rnd = rand(1, 3);
                if($rnd == 2)
                {
                    $res = \General::error_res();
                }
            }
            return $res;
        }
    
        public static function all_settings(){
             $res = \General::success_res();
             
             $settings = self::orderBy('id','asc')->get();
             $data=[];
             if($settings->isEmpty()){
                 $settings = [];
             }else{
                 $settings = $settings->toArray();
             }
             foreach ($settings as $key=>$row){
                 $data[$row['name']]=$row;
             }
             
             $res['data'] = $data;
             return $res;
        }
    
        public static function save_settings($param){
            $res = \General::success_res('settings saved successfully !!');
            
            if(!isset($param['maintenance_mode'])){
                $param['maintenance_mode'] = 0;
            }else{
                $param['maintenance_mode'] = 1;
            }
            foreach($param as $key=>$val){
                
                    $s = self::where('name','=',$key)->first();
                    if(!is_null($s)){
                        if($key == 'logo'){
                            $img = self::add_image($val);
                            if($img != ''){
                                $filename = $s->val;
                                $filePath = config('constant.UPLOAD_SETTINGS_DIR_PATH');
                                if(file_exists($filePath.$filename)){
                                    unlink($filePath.$filename);
                                }
                                $s->val = $img;
                            }
                        }else{
                            $s->val = $val;
                        }
                        
                        $s->save();
                    }
            }
            return $res;
        }
        public static function edit_general_settings($param){
            self::where('name','login_url_token')->update(['val'=>$param['login_url_token']]);
            if(isset($param['strip_secret_key']))
            {
            self::where('name','strip_secret_key')->update(['val'=>$param['strip_secret_key']]);
            }
            if(isset($param['strip_secret_key']))
            {
            self::where('name','strip_public_key')->update(['val'=>$param['strip_public_key']]);
            }
            return \General::success_res(' Settings saved successfully');
        }
}
