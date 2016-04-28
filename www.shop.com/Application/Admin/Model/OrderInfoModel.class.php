<?php

namespace Admin\Model;

class OrderInfoModel extends \Think\Model {
    //自动完成
    protected $_auto=[
        ['sn','_calc_sn',self::MODEL_INSERT,'function']
    ];
    //put your code here
    public function addOrder(){
        //保存基本信息
        //1.保存收货信息
        $address_id=I('post.address_id');
        $delivery_id=I('post.delivery_id');
        $pay_type=I('post.payment_id');
        $price=I('post.total_price');
        //获取指定地址信息
        $address_info=D('Address')->getAddressFind($address_id);
        //获取指定收货方式信息
        $delivery_info=M('Delivery')->find($delivery_id);
        $this->data['name']=$address_info['name'];
        $this->data['province_name']=$address_info['province_name'];
        $this->data['city_name']=$address_info['city_name'];
        $this->data['area_name']=$address_info['area_name'];
        $this->data['detail_address']=$address_info['detail_address'];
        $this->data['tel']=$address_info['tel'];
        $this->data['delivery_name']=$delivery_info['name'];
        $this->data['delivery_price']=$delivery_info['price'];
        $this->data['delivery_name']=$delivery_info['name'];
        $this->data['pay_type']=$pay_type;
        $this->data['price']=$price;
//        if(M('invoice')->add($data)===false){
//            $this->error='发票创建失败';
//            return false;
//        }
//        if($this->add()===false){
//            $this->error='订单创建失败';
//            return false;
//        }
        $this->saveInvodice();exit;
dump($this->data);exit;
    }
    //保存发票
    public function saveInvodice(){
        $data=[];
        $type=I('post.type');
        if($type=='1'){
            $data['name']=$this->data['name'];
        }else{
             $data['name']=I('post.company');
        }
        $data=[
            ['content']=>$this->data['name'],
        ];
    }
    

}
