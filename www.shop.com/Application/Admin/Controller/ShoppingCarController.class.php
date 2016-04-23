<?php

namespace Admin\Controller;

class ShoppingCarController extends \Think\Controller{
    /**
     * @var \Admin\Model\ShoppingCarModel 
     */
    private $_model = null;
    protected function _initialize(){
        $member_info=  session('MEMBER_INFO');
        if($member_info){
            $this->assign('member_info',$member_info);
        }
        $this->_model = D('ShoppingCar');
    }


    public function flow1(){
        //取出购物车数据
        $rows = $this->_model->getShoppingCar();
        $this->assign($rows);
        $this->display();
    }
    public function flow2(){
        $this->display('flow1');
    }
}
