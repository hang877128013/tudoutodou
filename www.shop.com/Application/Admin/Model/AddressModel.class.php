<?php

namespace Admin\Model;

class AddressModel extends \Think\Model {

    protected $_validate = [
        ['name', 'require', '收货人必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['province_id', 'require', '省份必选', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['city_id', 'require', '市区必选', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['area_id', 'require', '街道必选', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['tel', 'require', '手机号必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['tel', '/^(13|14|15|17|18)\d{9}$/', '手机号码不合法', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT],
    ];
    protected $_auto = [
        ['member_id', 'get_userId', self::MODEL_INSERT, 'function'],
    ];
    
    public function getListByParentId($parent_id = 0) {
        return M('Locations')->where(['parent_id' => $parent_id])->select();
    }
    
    /**
     * 获取用户的收获地址
     */
    public function getList(){
        //得到用户登录信息
        $userinfo=  session('MEMBER_INFO');
        $cond=[
            'member_id'=>$userinfo['id']
        ];
        return $this->where($cond)->select();
    }
    
    /**
     * 添加地址时  如果是默认 就把其他的地址设置成非默认
     * @return type
     */
    public function addAddress(){
//        dump($this->data);exit;
        if($this->data['is_default']){
            $userinfo=  session('MEMBER_INFO');
            $cond=[
                 'member_id'=>$userinfo['id']
            ];
            $this->where($cond)->setField('is_default',0);
        }
        return $this->add();
    }
    
    /**
     * 修改地址
     * @param type $id
     * @return type
     */
    public function editAddress($id){
        if($this->data['is_default']){
            $userinfo=  session('MEMBER_INFO');
            $cond=[
                 'member_id'=>$userinfo['id']
            ];
            $this->where($cond)->setField('is_default',0);
        }
        return $this->where(['id'=>$id])->save();
    }
    
    /**
     * 修改默认地址
     * @param type $id
     * @return boolean
     */
    public function editDefault($id){
        $userinfo=  session('MEMBER_INFO');
        $cond=[
                 'member_id'=>$userinfo['id']
            ];
        if(($this->where($cond)->setField('is_default',0))===false){
            return false;
        }
        if(($this->where(['id'=>$id])->setField('is_default',1))===false){
            return false;
        }
        return true;
    }
    
    /**
     * 删除地址
     * @param type $id
     * @return type
     */
    public function deleteAddress($id){
        return $this->where(['id'=>$id])->delete();
    }
    
    /**
     * 获取指定地址
     * @param type $id
     * @return type
     */
    public function getAddressFind($id){
        return $this->find($id);
    }
}
