<?php

namespace Admin\Controller;

class CaptchaController extends \Think\Controller{
    public function captcha(){
        $options = C('CAPTCHA_SETTING');
        $verify = new \Think\Verify($options);
        $verify->entry();
    }
}
