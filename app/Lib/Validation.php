<?php

namespace App\lib;

class Validation {
    private static $rules = array(        
        "user" => [
            "login"=>[
                "email"=>'required|email|exists:users,email',
                "password"=>'required'
            ],
            "forget_pass"=>[
                "email"=>'required|email',
            ],
            "reset_pass"=>[
                'forgottoken'=>'required',
                'password'  => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ],
            "change_password" => [
                'old_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ],
            "update_profile"=>[
                'mobile'=>'required|numeric',
                'billing_address'=>'required',
                'name'=>'required|min:2'
            ],
            "add_cart"=>[
                'county_id'=>'required|exists:counties,id',
                'payment_option'=>'required|in:0,1'
            ],
            "order_purchase"=>[
                'card_token'=>'required',
                'email'=>'required|email|unique:users,email|min:2|max:50',
                'password'=>'required|min:6',
                'mobile'=>'required|numeric|unique:users,mobile',
                'name'=>'required|min:2',
                'billing_address'=>'required'
            ],
            "send_offer"=>[
                'fname'=>'required|min:2',
                'lname'=>'required|min:2',
                'mobile'=>'required|numeric',
                'email'=>'required|email|min:2',
                'comments'=>'required'
            ],
            "sample_report"=>[
                'name'=>'required|min:2',
                'email'=>'required|email'
            ]
        ],
        
        "admin" => [
            "add_property"=>[
                "situs_county"=>'required|numeric|exists:counties,id',
                "property_address_city"=>'required|numeric|exists:cities,id',
                "legal_notice" => 'nullable|mimes:pdf',
            ],
            "update_property"=>[
                "property_id"=>'required|exists:properties,id',
                "situs_county"=>'required|numeric|exists:counties,id',
                "property_address_city"=>'required|numeric|exists:cities,id',
                "legal_notice" => 'nullable|mimes:pdf',
            ],
            "delete_property"=>[
                "id"=>'required|exists:properties,id'
            ],
            "add_county"=>[
                "state_id"=>'required|exists:states,id',
                "name"=>'required|min:2',
                "monthly_price"=>'required|numeric',
                "annual_price"=>'required|numeric'
            ],
            "update_county"=>[
                "update-id"=>'required|exists:counties,id',
                "state_id"=>'required|exists:states,id',
                "name"=>'required|min:2',
                // "monthly_price"=>'required|numeric',
                // "annual_price"=>'required|numeric'
            ],"add_user"=>[
                "name"=>'required',
                'email'=>'required|email|unique:users,email|min:2|max:50',
                "mobile"=>'required|numeric',
                'password'=>'required|min:6',
            ],
        ],
    );

    public static function get_rules($type, $rules_name) {
        if (isset(self::$rules[$type][$rules_name]))
            return self::$rules[$type][$rules_name];
        return array();
    }
    public static function validate($type,$rule_name,$custom_msg=[],$args_param=[],$niceNames=[]){
        
        $rules = self::get_rules($type,$rule_name);
       
        if(count($args_param)>0){
            $param=$args_param;
        }else{
            $param=\Input::all();
        }
        
        if(count($custom_msg) > 0){
            
            $validator = \Validator::make($param, $rules,$custom_msg);
        }else{
            $validator = \Validator::make($param, $rules);
        }
        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) {
            $error = $validator->messages()->all();
            $msg = isset($error[0])?$error[0]:"Please fill in the required field.";
            $json = \General::validation_error_res($msg);
            $json['data'] = [$msg];
            return $json;
        }
        
        return \General::success_res();
    }
    
    public static function custom_validate($param,$rules,$custom_msg=[],$custom_names=[],$sometimes=[]){
        $json=[];
        if(count($custom_msg) > 0){
            $validator = \Validator::make($param, $rules,$custom_msg);
        }else{
            $validator = \Validator::make($param, $rules);
        }
        if(!empty($sometimes)){
            foreach ($sometimes as $some){
                if(isset($some['field']) && isset($some['rules']) && isset($some['callback'])){
                    $validator->sometimes($some['field'],$some['rules'],$some['callback']);
                }
            }
        }
        
        if(!empty($custom_names)){
            $validator->setAttributeNames($custom_names); 
        }
        if ($validator->fails()) {
            $error = $validator->messages()->all();
            $msg = isset($error[0])?$error[0]:"Please fill in the required field.";
            $json = \General::validation_error_res($msg);
            $json['data'] = [$msg];
            return $json;
        }
        $json = \General::success_res();
        return  $json;
    }
}
