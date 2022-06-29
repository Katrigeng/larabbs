<?php

namespace App\Handlers;

use Illuminate\Support\Str;

class ImageUploadHandler
{
    //只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "jpeg", "gif"];

    public function save($file, $folder, $file_prefix){
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        //获取文件后缀名 因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在


        $upload_path = public_path() . '/' . $folder_name;

        $extension = strtolower($file->extension()) ?: 'png';

        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }
}
