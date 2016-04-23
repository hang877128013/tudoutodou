<?php

namespace Common\Behaviors;

class CheckPermissionBehavior extends \Think\Behavior {

    public function run(&$params) {
        
        $userinfo=  session('USERINFO');
        if(empty($userinfo)){
            D('Admin')->autoLogin();
            $userinfo=  session('USERINFO');
        }
        if($userinfo){
            //当是管理员登录时  直接显示全部
            if($userinfo['username']=='administrator'){
                return true;
            }
            
            $url    = implode('/', [MODULE_NAME, CONTROLLER_NAME, ACTION_NAME]);
            
            $paths = session('PATHS');
            if (!in_array($paths)) {
            $paths=[];
            }
            $paths = array_merge(C('IGNORE_PATHS'),$paths);
            //获取当前请求的路径
            if (!in_array($url, $paths)) {
            $url = U('Admin/Admin/login');
            redirect($url);
            }
        }  
    }

}
