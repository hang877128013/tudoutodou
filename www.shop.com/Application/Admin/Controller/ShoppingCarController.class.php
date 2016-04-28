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
        check_login();
        //获取默认收获地址
        $address=M('Address')->where(['is_default'=>1])->find();
        $this->assign('address',$address);
        //获取送货方式
        $deliverys=M('Delivery')->where(['status'=>1])->getField('id,name,price,intro');
        $this->assign('deliverys',$deliverys);
        //获取支付方式
        $payments=M('Payment')->where(['status'=>1])->getField('id,name,intro');
        $this->assign('payments',$payments);
        //取出购物车数据
        $shopings = $this->_model->getShoppingCar();
        $this->assign('shoppings',$shopings);
//        dump($shopings);exit;
        //获取用户收入地址
        $rows=D('Address')->getList();
        $this->assign('rows',$rows);
        $this->display('flow2');
    }
}
