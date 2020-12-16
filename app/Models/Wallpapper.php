<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class Wallpapper extends Model
{
    use HasFactory;
    protected $fillable = ['category_id','image'];
    protected $hidden = ['created_at','updated_at'];
    public function getImageAttribute()
    {
        return url('images/wallpappers/').'/'.$this->attributes['image'];
    }
    public static function addWallpapper($param){
    	if (isset($param['files'])) {
    		foreach ($param['files'] as $key => $value) {
    			$file = $value;
	            $name = 'wallpapper'.time().$key.'.'.$file->getClientOriginalExtension();
	            $path = public_path('\images\wallpappers');
	            if (!$file->move($path,$name)) {
	                $res = \General::error_res('someting went wrong');
	                return $res;
	            }
	              $wallpapper = new self;
	              $wallpapper->category_id = $param['category'];
	              $wallpapper->image =  $name;
	              $wallpapper->save();
	    	}
	    		$res = \General::success_res('Wallpappers Added successfully.');
	    		return $res;
    	}
    }
    public static function filterWallpapper($param,$api=''){
    	$wallpapper = self::join('categories','wallpappers.category_id' ,'categories.id')->select('wallpappers.id','wallpappers.image','categories.name as category','categories.id as cat_id')->orderBy('id','desc');
        if(isset($param['status']) && $param['status'] != ''){
            $wallpapper = $wallpapper->where('status',$param['status']);
        }
        if(isset($param['category_id']) && $param['category_id'] != ''){
            $wallpapper = $wallpapper->where('wallpappers.category_id',$param['category_id']);
        }
        $count = $wallpapper->count();
        $len = $param['itemPerPage'];
        $start = ($param['currentPage']-1) * $len;
        $wallpapper = $wallpapper->skip($start)->take($len)->get()->toArray();
        
        if ($api == 'api') {
            $res = \General::success_res();
            $res['data'] = $wallpapper;
            $res['total_record'] = $count;
            
        }
        $res['data'] = $wallpapper;
        $res['total_record'] = $count;
        return $res;
    }
    public static function deleteWallpapper($param){
    	$wallpapper = self::where('id',$param['delete'])->get()->first();
    	if ($wallpapper->delete()) {
    		$res = \General::success_res('Wallpappers delete successfully.');
	    	return $res;
    	}

    }
    public static function updateWallpapper($param){
        $wallpapper = self::where('id',$param['wallppper_id'])->get()->first();
        $oldimg = pathinfo($wallpapper->image)['basename'];
        $wallpapper->category_id = $param['category'];
        if (isset($param['wallpapper_img'])) {
            $file = $param['wallpapper_img'];
            $name = 'wallpapper'.time().'.'.$file->getClientOriginalExtension();
            $path = public_path('\images\wallpappers');
            if (!$file->move($path,$name)) {
                $res = \General::error_res('someting went wrong');
                return $res;
            }
            File::delete('images/wallpappers/'.$oldimg);
             $wallpapper->image = $name;
         }
          $wallpapper->save();
          return  \General::success_res('Wallpapper update Successfully.');

    }
}
