<?php

namespace Admin\Controller;
class CaptchaController extends \Think\Controller {
    //put your code here
    public function captcha(){
        //创建验证码
        $option=C('CAPTCHA_SETTING');
        $verify= new \Think\Verify($option);
        //显示验证码
        $verify->entry();
    }
}
