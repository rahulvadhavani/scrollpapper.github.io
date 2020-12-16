<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Admin\User;
use App\Models\Serviceprovider;
use Illuminate\Support\Facades\Mail;
use App\Imports\PropertyImport;
use DB;
use App\Models\Category;
use App\Models\Wallpapper;
use Validator;

class AdminController extends Controller {

    private static $bypass_url = ['getIndex', 'getLogin', 'postLogin','getLogout'];
    private static $logger = '';

    public function __construct() {
        $this->middleware('AdminAuth', ['except' => self::$bypass_url]);
        self::$logger = config('constant.LOGGER');
        
    }

    public function getIndex() {
       
        if (!\Auth::guard('admin')->check()) {
//            return \Response::view('errors.401', array(), 401);
            return \Redirect::to('/');
        }
        return \Redirect::to('admin/dashboard');
    }
    public function postUserName() {
        $param = \Input::all();
        $user = \App\Models\Users::get_user($param);
        if (is_null($user)) {
            return \General::error_res('no users found');
        }
        $res = \General::success_res();
        $isMobile = isset($param['is_mobile']) ? $param['is_mobile'] : 0;
        $r = [];
        foreach ($user as $a) {
            $r[] = [
                'key' => $a['id'],
                'value' => $isMobile ? $a['name'] . ' - ' . $a['email'] : $a['name'],
            ];
        }

        $res['data'] = $r;
        return \Response::json($res, 200);
    }
    public function getLogin($sec_token = "") {
//        dd($sec_token);
        $s = \App\Models\Admin\Settings::get_config('login_url_token');
        if ($sec_token != $s['login_url_token']) {
            return \Response::view('errors.404', array(), 404);
        }

//        dd(\Auth::guard("admin")->check());
        if (\Auth::guard("admin")->check()) {
            return \Redirect::to("admin/dashboard");
        }
        $view_data = [
            'header' => [
                'title' => config('constant.PLATFORM_NAME'),
            ],
            'body'=> [
                'logger' => 'Admin',
               
            ]
        ];
        return view('admin.login',$view_data);
    }

    public function postLogin(Request $req) {
        $view_data = [
            'header' => [
                'title' => '',
            ],
            'body'=> [
                'logger' => 'Admin',
                'type' => 'A'
            ]
        ];
        
         $custome_msg = [
            'g-recaptcha-response.required'   => 'Please ensure that you not robot.',
        ];
        
        $validator = \Validator::make(\Input::all(), \Validation::get_rules("admin", "login"));
        if ($validator->fails()) {
            $messages = $validator->messages();
            $error = $messages->all();
            
            return view('admin.login',$view_data)->withErrors($validator);
        }

        $param = $req->input();
        $res = \App\Models\Admin\User::doLogin($param);

        if ($res['flag'] == 0) {
            return view('admin.login',$view_data)->withErrors('Wrong User Id or Password !!');
        }

        return \Redirect::to("admin/dashboard");
    }

    public function getLogout() {
        // \App\Models\Admin\Token::delete_token();
        \Auth::guard('admin')->logout();
        $s = \App\Models\Admin\Settings::get_config('login_url_token');
        return redirect("admin/login/" . $s['login_url_token']);
    }
    
    public function getDashboard() {
       
        $view_data = [
            'header' => [
                "title" => 'Dashboard | Admin Panel ',
            ],
            'body' => [
                'id'    => 'dashboard',
                'label' => 'Dashboard',
            ],
        ];
       
        return view("admin.dashboard", $view_data);
    }
    
    public function getProfile($msg = "") {
        $res = User::getProfile();
        $view_data = [
            'header' => [
                "title" => 'Profile | Admin Panel ',
                "js" => [],
                "css" => [],
            ],
            'body' => [
                'name' => isset($res['name']) ? $res['name'] : "",
                'email' => isset($res['email']) ? $res['email'] : "",
                'msg' => $msg
            ],
            'footer' => [
                "js" => [],
                "css" => []
            ],
        ];
        return view("admin.profile", $view_data);
    }
    
    public function postChangeAdminPassword() {
        $param = \Input::all();
//        dd($param);
        $res = User::change_admin_password($param);
//        dd($res);
        if (isset($res['flag'])) {
            if ($res['flag'] == 0) {
                return \Redirect::to('admin/profile/' . $res['msg']);
            } else if ($res['flag'] == 1) {
//                return \Redirect::to("admin/dashboard");
                return \Redirect::to("admin/logout");
            }
        }
    }
    
    public function getSettings(){
        $mch = \Auth::guard('admin')->user()->toArray();
        $settings = app('settings');
        $view_data = [
            'header' => [
                'title' => 'Settings | '.config('constant.PLATFORM_NAME'),
                'css'=>[],
                'js'=>[],
            ],
            'body'=> [
                'id'=>'settings',
                'label'=>'Settings',
                'settings'=>$settings,
                 'user'  => $mch,
            ],
            'footer'=>[
                'js'=>['bootstrap-datepicker.min.js'],
            ],
        ];
        return view('admin.settings',$view_data);
    }
    public function postSaveSettings(){
        $param = \Input::all();
        $setting_type = $param['settting_type'];
        if($setting_type == 'general'){
                $res = \App\Models\Admin\Settings::edit_general_settings($param);
        }else if($setting_type == 'password'){
            $res = User::change_admin_password($param);
        }else{
            $res = \General::error_res('setting type is not proper');
        }
        
        return $res;
    }
    public function getwallpappers(){
       $category = Category::get()->toArray();
       $view_data = [
            'header' => [
                'title' => 'Wallpappers | '.config('constant.PLATFORM_NAME'),
                
            ],
            'body'=> [
                'id'=>'wallpappers',
                'label'=>'Wallpappers',
                'category' => $category,
            ]
           
        ];
        return view('admin.wallpappers',$view_data);
    }
    public function postUploadImages(Request $request){
        $param = \Input::all();
        $rules = array('category'=>'required|exists:categories,id','files' => 'required',
       'files.*' => 'image|mimes:jpeg,png,jpg,svg|max:2048');
        $validator = Validator::make($param, $rules);
        if ($validator->fails())
        {  
        $res["flag"] = 1; 
        $res['msg']= $validator->getMessageBag()->first();
        $res = \General::validation_error_res($res['msg']);
        return $res;
        }
        $res = Wallpapper::addWallpapper($param);
        return $res;

    }
    public function postWallpapperFilter(){
        $param = \Input::all();
        $client = Wallpapper::filterWallpapper($param);
        $res = \General::success_res();
        $res['blade'] = view("admin.wallpapper_filter", $client)->render();
        $res['total_record'] = $client['total_record'];
        return $res;
    }
    public function postDeletewallpapper(){
        $param = \Input::all();
        $res = Wallpapper::deleteWallpapper($param);
        return $res;
    }
       public function postUpdateWallpapper(){
        $param = \Input::all();
        $rules = array('wallppper_id'=>'required|exists:wallpappers,id','category' => 'required|exists:categories,id', 'wallpapper_img' => 'nullable|mimes:jpeg,jpg,png|max:2500');
        $validator = Validator::make($param, $rules);
        if ($validator->fails())
        {  
        $res["flag"] = 1; 
        $res['msg']= $validator->getMessageBag()->first();
        $res = \General::validation_error_res($res['msg']);
        return $res;
        }
        $res = Wallpapper::updateWallpapper($param);
        return $res;
    }
    public function getCategory(){
       $category = Category::simplePaginate(1);
       $view_data = [
            'header' => [
                'title' => 'Category',
                
            ],
            'body'=> [
                'id'=>'category',
                'label'=>'Category',
                'category'=> $category,
            ]
           
        ];
        return view('admin.category',$view_data);
    }
    public function postCategoryFilter(){
        $param = \Input::all();
        $client = Category::filterCategory($param);
        $res = \General::success_res();
        $res['blade'] = view("admin.category_filter", $client)->render();
        $res['total_record'] = $client['total_record'];
        return $res;
    } 
    public function postAddCategory(){
        $param = \Input::all();
        $rules = array('category' => 'required|max:20', 'file' => 'required|mimes:jpeg,jpg,png');
        $validator = Validator::make($param, $rules);
        if ($validator->fails())
        {  
          $res["flag"] = 1; 
          $res['msg']= $validator->getMessageBag()->first();
           $res = \General::validation_error_res($res['msg']);
          return $res;
        }
        $res = Category::addCategory($param);
        return $res;
    }
    public function postDeleteCategory(){
        $param = \Input::all();
        $res = Category::deleteCategory($param);
        return $res;
    }
    public function postUpdateCategory(){
        $param = \Input::all();
        $rules = array('category_id'=>'required|exists:categories,id','category' => 'required|min:3|max:20', 'category_img' => 'nullable|mimes:jpeg,jpg,png|max:2500');
        $validator = Validator::make($param, $rules);
        if ($validator->fails())
        {  
        $res["flag"] = 1; 
        $res['msg']= $validator->getMessageBag()->first();
        $res = \General::validation_error_res($res['msg']);
        return $res;
        }
        $res = Category::updateCategory($param);
        return $res;
    }

 
}