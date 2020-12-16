<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Wallpapper;
use Validator;

class WallpapperController extends Controller
{
    //
    public function postCategory(){
    	$res = Category::getCategory();
    	return response()->json($res);
    }
    public function postWallpapperfilter(){
    	$param = \Input::all();
    	$rules = ['category_id'=> 'nullable|exists:categories,id'];
    	$validator = Validator::make($param,$rules);
    	if ($validator->fails()) {
    		$res['msg']= $validator->getMessageBag()->first();
            $res = \General::validation_error_res($res['msg']);
            return $res;
    	}
    	$param['itemPerPage'] = 999;
    	$param['currentPage'] = 1;
    	$res = Wallpapper::filterWallpapper($param,'api');
    	return response()->json($res);
    }
}
