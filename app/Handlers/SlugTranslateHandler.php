<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SlugTranslateHandler{

    public function translate($text){

        $http = new Client;
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return $this->pinyin($text);
        }

        // appid+q+salt+密钥 的MD5值
        $sign = md5($appid. $text . $salt . $key);

        $query = http_build_query([
            "q"     =>  $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);
        $respone = $http->get($api.$query);

        $result = json_decode($respone->getBody(), true);
        if(isset($result['trans_result'][0]['dst'])){
            return Str::slug($result['trans_result'][0]['dst']);
        }else{
            return $this->pinyin($text);
        }

    }

    public function pinyin($text){
        return Str::slug(app(Pinyin::class)->permalink($text));
    }
}
