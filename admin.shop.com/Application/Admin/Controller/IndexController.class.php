<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
        protected function _initialize() {
        if(!session('USERINFO')){
            if(ACTION_NAME=='login'){
                return true;
            }
            $this->success('请先登录',U('Admin/login'));
            return false;
        }
    }
    public function index(){
        $this->display();
    }
    public function top(){

        $this->display();
    }
    public function menu(){
        $menu_model=D('Menu');
        $menus=$menu_model->getMenuList();
//        dump($menus);exit;
        $this->assign('menus',$menus);
//        dump($menus);exit;
        $this->display();
    }
    public function main(){
        $this->display();
    }
}