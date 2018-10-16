<?php

namespace app\admin\controller;


use app\common\lib\Util;

class Image
{
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move('../public/static/imgUpload');
        if($info){
            $data = [
                'image' => config('live.host').$info->getSaveName()
            ];

            return Util::show(config('code.success'),'上传成功',$data);
        }
    }



    
}