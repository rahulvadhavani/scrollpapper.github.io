<?php
namespace App\Lib;

class Stripe{
    
    public static function set_api_version(){//used
        \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION','2020-03-02'));
    }
    public static function set_keys(){//used
        $settings = app('settings');
        $secret = $settings['strip_secret_key'];
        \Stripe\Stripe::setApiKey($secret);
        self::set_api_version();
    }
    public static function create_subscription($param){
        try{
            self::set_keys();
            
            $data = [
                'customer'=>$param['stripe_customer_id'],
                'items'=>$param['items']
            ]; 
            third_party_log("Stripe PARAM #create_subscription", $param);

            $token = \Stripe\Subscription::create($data);
            
            $res = success_res();
            $res['data'] = $token;

        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_subscription", $res);
        
        return $res;
    }
    public static function delete_subscription_item($param){
        try{
            self::set_keys();
            
            third_party_log("Stripe PARAM #delete_subscription_item", $param);

            $items = \Stripe\Subscription::retrieve($param['subscription_id']);
            if (isset($items['items']) && count($items['items']['data']) <= 1) {
                // cancel subscription
                $cancel = $items->cancel();

                $res = success_res();
                $res['data'] = $cancel;
            }else{
                // delete Subscription Item
                $subscription_item = \Stripe\SubscriptionItem::retrieve($param['stripe_item_id']);
                $subscription_item->delete();
                
                $res = success_res();
                $res['data'] = $subscription_item;   
            }

        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #delete_subscription_item", $res);
        
        return $res;
    }
    public static function create_card_token($card){//used
        try{
            self::set_keys();
            $param = [
                "card" => $card,
            ];
            
            third_party_log("Stripe PARAM #create_card_token", $param);

            $token = \Stripe\Token::create($param);
            
            $res = success_res();
            $res['data'] = $token;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_card_token", $res);
        return $res;
    }
    
    public static function check_token_exist($token=''){//used
        try{
            self::set_keys();
            
            third_party_log("Stripe PARAM #check_token_exist", $token);
            
            $data = \Stripe\Token::retrieve($token);
            
            $res = success_res();
            $res['data'] = $data;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #check_token_exist", $res);

        return $res;
    }
    
    public static function create_customer($param=[]){//used
        try{
            
            self::set_keys();
            $param = [
                'name'=>$param['name'],
                'phone'=>$param['mobile'],
                'email'=>$param['email'],  //create customer
                'address'=>[
                    'line1'=>$param['billing_address']
                ],
            ];
            third_party_log("Stripe PARAM #create_customer", $param);

            $customer=\Stripe\Customer::create($param);
            
            $res = success_res();
            $res['data'] = $customer;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_customer", $res);
        
        return $res;
    }
    public static function update_customer($param=[]){//used
        try{
            
            self::set_keys();
            $data = [
                'name'=>$param['name'],
                'phone'=>$param['mobile'],
                'address'=>[
                    'line1'=>$param['billing_address']
                ],
            ];
            if (isset($param['email'])) {
                $data['email'] = $param['email'];
            }
            third_party_log("Stripe PARAM #create_customer", $param);

            $customer = \Stripe\Customer::update($param['stripe_customer_id'],$data);
            
            $res = success_res();
            $res['data'] = $customer;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_customer", $res);
        
        return $res;
    }
    
    public static function get_customer($stripe_customer_id=''){//used
        
        try{
            
            self::set_keys();
            
            third_party_log("Stripe PARAM #get_customer", $stripe_customer_id);

            $customer = \Stripe\Customer::retrieve($stripe_customer_id);

            $res = success_res();
            $res['data'] = $customer;

        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #get_customer", $res);

        return $res;
    }
    
    public static function charge($param){//used
        // https://api.stripe.com/v1/customers/cus_EVxTCdLI5fRMbc/sources/card_1E5mCVEsm7SboVHbpGfBeOQF \-u sk_test_dC8XXpZqkfIH1oP0B3i7fOh0: \-d name="Jaykishan patel"
        try{
            
            self::set_keys();
            
            $amount = $param['amount'] * 100;
            
            $charge = [
                "amount" => $amount,
                "currency" => "usd",
                "source" => $param['stripe_id'], // obtained with Stripe.js
                "customer" => $param['stripe_customer_id'], // obtained with Stripe.js
                "description" => $param['description']??'',
              ];
              // dd($charge);
            if(isset($param['metadata'])){
                $charge['metadata'] = $param['metadata'];
            }
                
            third_party_log("Stripe PARAM #charge", $charge);

            $account = \Stripe\Charge::create($charge);
            
            $res = success_res();
            $res['data'] = $account;
           
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
         
        third_party_log("Stripe RESPONSE #charge", $res);

        return $res;
    }
    
    public static function get_charge($charge_id=''){//used
        try{
            self::set_keys();
            
            third_party_log("Stripe PARAM #get_charge", $charge_id);

            $charge = \Stripe\Charge::retrieve($charge_id);
            
            $res = success_res();
            $res['data'] = $charge;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #get_charge", $res);

        return $res;
    }

    public static function add_customer_card_or_bank_account($param){//used
        $data = [];
        try{
            self::set_keys();
            third_party_log("Stripe PARAM #add_customer_card_or_bank_account", $param);

            $customer = \Stripe\Customer::retrieve($param['stripe_customer_id']);
            $ba = $customer->sources->create(["source" => $param['card_token']]);
            
            $data = $ba;
            
            $res = success_res();
            $res['data'] = $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        third_party_log("Stripe RESPONSE #add_customer_card_or_bank_account", $res);

        
        return $res;
    }
    public static function delete_customer_card_or_bank_account($param){//used
        $data = [];
        try{
            self::set_keys();
            
            third_party_log("Stripe PARAM #delete_customer_card_or_bank_account", $param);

            $customer = \Stripe\Customer::retrieve($param['stripe_customer_id']);
            $ba = $customer->sources->retrieve($param['stripe_id'])->delete();
            
            $data = $ba;
            $res = success_res();
            $res['data'] = $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #delete_customer_card_or_bank_account", $res);
        
        return $res;
    }
    
    public static function get_customer_bank_or_card_detail($param){//not used
        
        try{
            
            self::set_keys();
            
            third_party_log("Stripe PARAM #get_customer_bank_or_card_detail", $param);

            $customer = \Stripe\Customer::retrieve($param['stripe_customer_id']);
            $bank_account = $customer->sources->retrieve($param['stripe_id']);
            $res = success_res();
            $res['data'] = $bank_account;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #get_customer_bank_or_card_detail", $res);

        return $res;
    }
    public static function create_product($param){
        try{
            
            self::set_keys();
            $param = [
                'name'=>$param['name'],
                'description'=>$param['description'],
            ];
            third_party_log("Stripe PARAM #create_product", $param);

            $product=\Stripe\Product::create($param);
            
            $res = success_res();
            $res['data'] = $product;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_product", $res);
        
        return $res;
    }
    public static function create_plan($param){
        try{
            self::set_keys();
            $amount = $param['amount'] * 100;

            $param = [
                'amount'=>$amount,
                'currency'=>'usd',
                'interval'=>$param['interval'],
                'product'=>$param['stripe_product_id'],
            ];
            third_party_log("Stripe PARAM #create_plan", $param);

            $plan=\Stripe\Plan::create($param);
            
            $res = success_res();
            $res['data'] = $plan;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #create_plan", $res);
        
        return $res;
    }

    public static function update_product($param){
        try{
            
            self::set_keys();
            $data = [
                'name'=>$param['name'],
                'description'=>$param['description'],
            ];
            third_party_log("Stripe PARAM #update_product", $param);

            $product = \Stripe\Product::update(
              $param['stripe_product_id'],
              $data
            );
            
            $res = success_res();
            $res['data'] = $product;
            
        } catch (\Stripe\Error\InvalidRequest $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            $res = error_res($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $res = error_res($e->getMessage());
        } catch (\Exception $e) {
            $res = error_res($e->getMessage());
        }
        
        third_party_log("Stripe RESPONSE #update_product", $res);
        
        return $res;
    }
}