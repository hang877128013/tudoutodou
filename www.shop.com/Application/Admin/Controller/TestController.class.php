<?php

namespace Admin\Controller;

class TestController extends \Think\Controller {
    public function index(){
        $a=3;$b=3;
//        echo $a.'+'.$b;
        if($a=5||$b=5){
            dump($a);
            $b++;
            dump($b++);
        }
//        echo $a.'+'.$b;
    }

    public function sendSMS() {
        $flag = sendSMS('17002810533', ['code' => (string) 1033, 'product' => '仙人跳']);
    }

    public function sendEmail() {
        $flag = sendEmail('kunx@kunx.org', '测试邮件', '今天早上没吃药,感觉自己萌萌哒');
        dump($flag);
    }

}
