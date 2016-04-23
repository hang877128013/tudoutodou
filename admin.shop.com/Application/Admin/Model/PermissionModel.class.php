<?php

namespace Admin\Model;
class PermissionModel extends \Think\Model {
    //添加条件
    protected $_validate=array(
      array('name','require','角色名不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('name','','角色已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    //put your code here
         public function getList($field = '*') {
        return $this->field($field)->where(array('status' => 1))->select();
    }
    /**
     * 执行权限的添加.
     * @return boolean
     */
    public function addPermission() {
//        dump($this->data);
//             dump($this->trueTableName);exit;
        //创建nestedsets
//        $mysql_db = new \Admin\Logic\DbMysqlLogic;
        $nestedsets = $this->_get_nestedsets();
//         dump($mysql_db);exit;
        //传入参数  parent_id 数据 方向 并且返回
        return $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom');
    }
    public function editPermission(){
        //得到原来的父级ID
        $parent_id = $this->getFieldById($this->data['id'],'parent_id');
        //比较
        if($parent_id!=$this->data['parent_id']){
            //创建nestedsets
            $nestedsets = $this->_get_nestedsets();
            if($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom')===false){
                $this->error = '不能把后代权限设定为父级权限';
                return false;
            }
        }
        //否则直接保存即可
        return $this->save();
    }
        /**
     * 获取nestedsets对象.
     * @return \Admin\Service\NestedSets
     */
    private function _get_nestedsets(){
        //创建nestedsets所需的数据库对象
//        $db_obj = D('DbMySql','Logic');
        $mysql_db = new \Admin\Logic\DbMySqlLogic;
        //创建nestedsets对象
        return new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
    }
    public function deletePermission($id){
        
        //得到当前权限的左右数据
        $permission_info = $this->where(array('id'=>$id))->getField('id,lft,rght');
//        dump($permission_info);exit;
        if(!$permission_info){
            $this->error = '权限不存在';
            return false;
        }
        //设定条件  包含的权限
        $cond = array(
            'lft'=>array('egt',$permission_info[$id]['lft']),
            'rght'=>array('elt',$permission_info[$id]['rght']),
        );
        return $this->where($cond)->setField('status',0);
    }
}
