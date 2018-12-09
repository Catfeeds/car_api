<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Upload;

class UploadsController extends Controller
{
    public function unmarried_upload(Request $request)
    {
    	if($request->isMethod('post'))
        {
        	$name=$request->name;
        	$order_id=$request->order_id;
        	return $this->upload_image($request,$name,$order_id,0);
            
        }
    }

    public function married_upload(Request $request)
    {
    	if($request->isMethod('post'))
        {
        	$name=$request->name;
        	$order_id=$request->orer_id;
        	return $this->upload_image($request,$name,$order_id,1);
            
        }
    }

    public function show_upload(Request $request)
    {
        
        $order_id=$request->order_id;
        $type=$request->type;
        $name=$request->name;
        $arr=[];

        $images=Upload::where([
            'order_id'=>$order_id,
            'type'=>$type,
            'name'=>$name
        ])->get();
        if($images){
            foreach ($images as $k => $v) {
                $arr['images'][]="<img src='".env('APP_URL')."/uploads/".$v->image."' style='height:auto; max-width: 100%; max-height: 100%; margin-top: 0px;'>";
                $arr['delete'][]=['url'=>env('APP_URL').'/api/delete?id='.$v->id];
            }
        }

        return response()->json($arr,200);

    }

    public function destory(Request $request)
    {
        $upload_id=$request->id;
        Upload::find($upload_id)->delete();
       return response()->json(['status'=>true],200);
    }

    
    public function upload_image($request,$name,$order_id,$type)
    {
    	$file =  $request->file($name);
    	if($file){
            $extension = $file -> guessExtension();
            $newName = str_random(18).".".$extension;
            $file -> move(base_path().'/public/uploads/'.$name.'/',$newName);
            $idCardFrontImg = $name.'/'.$newName;
            $upload=Upload::create([
	            	'order_id'=>$order_id,
	            	'type'=>$type,
	            	'name'=>$name,
	            	'image'=>$idCardFrontImg
            	]);
            if($upload){
            	return json_encode($idCardFrontImg);
            }
            
        }else{
            $idCardFrontImg = '';
            return json_encode($idCardFrontImg);
        }
    }
}
