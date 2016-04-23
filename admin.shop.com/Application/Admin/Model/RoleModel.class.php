<?php

namespace Admin\Model;

class RoleModel extends \Think\Model {
    //添加条件
    protected $_validate=array(
      array('name','require','角色名不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('name','','角色已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    //put your code here
        public function getList($field = '*') {
        return $this->field($field)->where(array('status' => 1))->select();
    }
    public function addRole(){
//        $roleObj=$this->data;
//        $parent_id=I('post.parent_id');
        //保存基本数据
        if(($role_id=$this->add())===false){
            return false;
        }
        //保存角色的权限
        if($this->_save_permission($role_id)===false){
            $this->error = '保存权限失败';
            return false;
        }
        return true;
    }
      /**
     * 获取角色和权限
     * @param integer $id 
     * @return boolean
     */
    public function getRoleInfo($id){
        //得到基本数据
        $row = $this->where(array('status' => 1))->find($id);
//        dump($row);exit;
        if(empty($row)){
            $this->error = '角色不存在';
            return false;
        }
        //得到角色对应的权限
        $rolePermission_model =M('RolePermission');
        $ropes = $rolePermission_model->where(array('role_id'=>$id))->getField('permission_id',true);
        $row['permission_ids'] = json_encode($ropes);
        return $row;
    }
    /**
     * 修改角色信息和权限
     * @return boolean
     */
        public function editRole(){
        $role_data = $this->data;
        //保存角色的基本数据
        if($this->save() === false){
            return false;
        }
        //保存角色的权限
        if($this->_save_permission($role_data['id'], false) === false){
            $this->error = '保存权限失败';
            return false;
        }
        return true;
    }
    
     /**
     * 保存权限
     * @param integer $role_id
     * @param boolean $is_new
     * @return 
     */
    private function _save_permission($role_id,$is_new=true){
        $perms = I('post.perm');
        if(empty($perms)){
            return true;
        }
        $data = array();
        foreach ($perms as $perm){
            $data[] = array(
                'role_id'=>$role_id,
                'permission_id'=>$perm,
            );
        }
        $model = M('RolePermission');
        if(!$is_new){
            $model->where(array('role_id'=>$role_id))->delete();
        }
        return $model->addAll($data);
    }
    /**
     * 删除
     * @param type $id
     * @return type
     */
        public function deleteRole($id){
        return $this->where(array('id'=>$id))->setField('status',0);
    }
}
