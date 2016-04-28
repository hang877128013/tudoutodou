<?php

namespace Admin\Controller;

class OrderInfoController extends \Think\Controller {
       /**
     * 存储模型对象.
     * @var \Admin\Model\OrderInfoModel 
     */
    private $_model = null;

     protected function _initialize(){
        $member_info=  session('MEMBER_INFO');
        if($member_info){
            $this->assign('member_info',$member_info);
        }
        $this->_model = D('OrderInfo');
    }
    //put your code here
    public function add(){
        if($this->_model->create()===false || $this->_model->addOrder()===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('订单添加成功',U('index'));
    }
}
