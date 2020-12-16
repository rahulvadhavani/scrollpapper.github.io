<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        \Config::set('constant.LOGGER','A');
        if (\Request::wantsJson()) {
            $token = \Request::header('AuthToken');
            if ($token == "") {
                return \Response::json(\General::session_expire_res(),401);
            }
            $already_login = \App\Models\Admin\Token::is_active("auth",$token);
            if (!$already_login)
                return \Response::json(\General::session_expire_res("unauthorise"),401);
        }
        else {
            if (!\Auth::guard('admin')->check()) {
                $validator = \Validator::make([], []);
                $validator->errors()->add('attempt', \Lang::get('error.session_expired', []));
                return redirect()->to("admin")->withErrors($validator, 'login');
            }
        }
        return $next($request);
    }

}
