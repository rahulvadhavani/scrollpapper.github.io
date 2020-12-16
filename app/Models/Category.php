<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','image'];
    protected $hidden = ['created_at','updated_at'];
    public function getImageAttribute()
    {
        return url('images/category/').'/'.$this->attributes['image'];
    }
    public static function filterCategory($param){
    	$category = self::orderBy('id','desc');
        if(isset($param['user_id']) && $param['user_id'] != ''){
            $category = $category->where('user_id',$param['user_id']);
        }
        if(isset($param['status']) && $param['status'] != ''){
            $category = $category->where('status',$param['status']);
        }
        $count = $category->count();
        $len = $param['itemPerPage'];
        $start = ($param['currentPage']-1) * $len;
        
        $category = $category->skip($start)->take($len)->get()->toArray();
        $res['data'] = $category;
        $res['total_record'] = $count;
        
        return $res;
    }
    public static function addCategory($param){
        $cat = new self; 
        $cat->name = $param['category'];
        if (isset($param['file'])) {
            $file = $param['file'];
            $name = time().'.'.$file->getClientOriginalExtension();
            $path = public_path('\images\category');
            if (!$file->move($path,$name)) {
                $res = \General::error_res('someting went wrong');
                return $res;
            }
              $cat->image = $name;

         }
          $cat->save();
          return  \General::success_res('Ctegory Added Success');

    }
    public static function updateCategory($param){
        $cat = self::where('id',$param['category_id'])->get()->first();

        $oldimg = pathinfo($cat->image)['basename'];
        $cat->name = $param['category'];
        if (isset($param['category_img'])) {
            $file = $param['category_img'];
            $name = time().'.'.$file->getClientOriginalExtension();
            $path = public_path('\images\category');
            if (!$file->move($path,$name)) {
                $res = \General::error_res('someting went wrong');
                return $res;
            }
            File::delete('images/category/'.$oldimg);
             $cat->image = $name;
         }
          $cat->save();
          return  \General::success_res('Ctegory update Success');

    }
    public static function deleteCategory($param){
        $category = self::where('id',$param['delete'])->first()->delete();
        return  \General::success_res('Category Delete success');
    }
    public static function getCategory(){
        $category = self::orderBy('id','desc')->get();
        $res = [];
        $res = \General::success_res();
        $res['category'] = $category->toArray();
        $res['total_record'] = $category->count();
        return $res;
    }
}
