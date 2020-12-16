<?php

namespace App\Lib;

class ConstType {

    private static $rules = array(
        "users" => [
            "status" => [
                'rejected'=>0,
                'active'=>1,
                'pending'=>2,
                'suspended'=>3,
            ],
        ],
        "users_token" => [
            "status" => [
                'expired'=>0,
                'active' => 1,
            ],
            "type" => [
                'auth'=>0,
                'activate_account'=>1,
                'forgot_password'=>2,
                'otp'=>3,
                'set_password'=>4,
            ],
        ],
        "properties"=>[
            "property_type"=>[
                "single_family_resid_ranch"=>0,
                "condo_resident_14"=>1,
            ],
            "is_verify"=>[
                "unverified"=>0,
                "verified"=>1,
                "archived"=>2
            ],
            "status" =>[
                "cancelled"=>0,
                "active"=>1
            ],
            "action" =>[
                1=>"view",
                2=>"pass"
            ],
            "HVAC_heating_detail"=>[
                'forced_air'=>0,
                'central'=>1,
                'other' => 2
            ],
            "status_owner_occupied_flag" =>[
                "no"=>0,
                "yes"=>1
            ],
            "company_flag" =>[
                "no"=>0,
                "yes"=>1
            ],
            "record_type" =>[
                "lis"=>0,
                "nts"=>1,
                "nod"=>2,
                "other"=>3
            ],
        ],
        "property_details"=>[
            "construction_type"=>[
                "vinyl"=>0,
                "plywood"=>1,
                "wood_brick"=>2
            ],
            "basement_type"=>[
                "unfinished"=>0,
                "finished"=>1,
                "partial_finished"=>2
            ],
            "heat"=>[
                'forced_air'=>0,
                'central'=>1
            ],
            "owner_occupied"=>[
                'no'=>0,
                'yes'=>1
            ],
            "ac"=>[
                'no'=>0,
                'yes'=>1
            ],
        ],
        "mortgage_info"=>[
            'deed_type'=>[
                'warranty_deed'=>0,
                'trustee_deed'=>1,
                'deed_reg'=>2      
            ],
        ],
        "counties"=>[
            "status"=>[
                "inactive"=>0,
                "active"=>1
            ],
        ],
        "cart_data"=>[
            "payment_type"=>[
                "monthly"=>0,
                "annual"=>1
            ],
        ],
        "payment_cards"=>[
            "card_type"=>[
                "Visa"=>0,
                "MasterCard"=>1,
                "amex"=>2,
                "Discover"=>3,
                "American Express"=>4,
                "unknown"=>5,
            ],
        ],
        "subscription_item"=>[
            "payment_type"=>[
                "monthly"=>0,
                "annual"=>1
            ],
            "status"=>[
                "canceled"=>0,
                "active"=>1,
                "incomplete"=>2,
                "incomplete_expired"=>3,
                "unpaid"=>4,
                "past_due"=>5,
                "trialing"=>6
            ],
        ],
    );

    private static $rules_view = array(
        "users" => [
          "status" => [
                0=>'Rejected',
                1=>'Active',
                2=>'Pending',
                3=>'Suspended',
            ],
        ],
        "users_token" => [
            "status" => [
                0=>'Expired',
                1=>'Active' ,
            ],
            "type" => [
                0=>'Auth',
                1=>'Activate account',
                2=>'Forgot password',
                3=>'Otp',
                4=>'Set Password'
            ],
        ],
        "properties"=>[
            "property_type"=>[
                0=>"Single Family Resid Ranch",
                1=>"Condominium Residential 14 Condominium",
            ],
            "is_verify"=>[
                0=>"Unverified",
                1=>"Verified",
                2=>"Archived"
            ],
            "status" =>[
                0=>"Cancelled",
                1=>"Active"
            ],
            "action" =>[
                1=>"View",
                2=>"Pass"
            ],
            "HVAC_heating_detail"=>[
                0=>'Forced Air',
                1=>'Central',
                2=>'Other'
            ],
            "status_owner_occupied_flag" =>[
                0=>"No",
                1=>"Yes"
            ],
            "company_flag" =>[
                0=>"No",
                1=>"Yes"
            ],
            "record_type" =>[
                0=>"LIS",
                1=>"NTS",
                2=>"NOD",
                3=>'OTHER'
            ],
        ],
        "property_details"=>[
            "construction_type"=>[
                0=>"Vinyl",
                1=>"Plywood",
                2=>"Wood/brick"
            ],
            "basement_type"=>[
                0=>"Unfinished",
                1=>"Finished",
                2=>"Partial Finished"
            ],
            "heat"=>[
                0=>'Forced Air',
                1=>'Central'
            ],
            "owner_occupied"=>[
                0=>'No',
                1=>'Yes'
            ],
            "ac"=>[
                0=>'No',
                1=>'Yes'
            ],
        ],
        "mortgage_info"=>[
            'deed_type'=>[
                0=>'Warranty Deed',
                1=>'Trustee Deed',
                2=>'Deed Reg'      
            ],
        ],
        "counties"=>[
            "status"=>[
                0=>"Inactive",
                1=>"Active"
            ],
        ],
        "subscription_item"=>[
            "payment_type"=>[
                0=>"Monthly",
                1=>"Annual"
            ],
            "status"=>[
                0=>"Canceled",
                1=>"Active",
                2=>"Incomplete",
                3=>"Incomplete Expired",
                4=>"Unpaid",
                5=>"Past Due",
                6=>"Trialing"
            ],
        ],
    );
    
   
    public static function get_type($table_n_field, $rules_name = '') {
        $type= explode('.', $table_n_field);
        if(!isset($type[0]) || !isset($type[1])){
            return array();
        }
        $table=$type[0];
        $field=$type[1];
        if(isset(self::$rules[$table])){
            if(isset(self::$rules[$table][$field])){
                if(isset(self::$rules[$table][$field][$rules_name])){
                    return self::$rules[$table][$field][$rules_name];
                }
            }
            return self::$rules[$table][$field];
        }
            
        return array();
    }
    
    
     public static function type_list($table_n_field, $rules_code = '') {
        $type= explode('.', $table_n_field);
        if(!isset($type[0]) || !isset($type[1])){
            return array();
        }
        $table=$type[0];
        $field=$type[1];
       
        if(isset(self::$rules_view[$table])){
          
            if(isset(self::$rules_view[$table][$field])){
                if(isset(self::$rules_view[$table][$field][$rules_code])){
                    return self::$rules_view[$table][$field][$rules_code];
                }
               return self::$rules_view[$table][$field];
            }
            return self::$rules_view[$table];
        }
            
        return array();
    }
}
