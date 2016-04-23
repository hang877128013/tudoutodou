<?php

namespace Admin\Model;
class MenuModel extends \Think\Model {
    /**
     *自动验证
     * @var type 
     */
    protected $_validate=array(
      array('name','require','菜单必填',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),  
    );
        public function getList($field='*'){
        $cond = array(
            'status'=>1,
        );
        return $this->field($field)->where($cond)->order('lft')->select();
    }
    
    /**
     * 添加方法
     * @return boolean
     */
    public function addMenu(){
        //计算节点
        $nestedsets = $this->_get_nestedsets();
        //保存
        if(($menu_id = $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom'))===false){
            $this->error = '添加菜单失败';
            return false;
        }
        //保存权限
        if($this->save_permission($menu_id)===false){
            $this->error='权限保存失败';
            return false;
        }
        return true;
    }

    /**
     * 修改方法
     * @return boolean
     */
    public function editMenu(){
        //比较父级是否更改
        $re_data = $this->data;
        //查询库得到原来的父级ID
        $parent_id=$this->where(['id'=>$re_data['id']])->getField('parent_id');
        if($parent_id!=$re_data['parent_id']){
            //重新计算节点
            $nestedsets = $this->_get_nestedsets();
            //修改节点
            if($nestedsets->moveUnder($re_data['id'], $re_data['parent_id'], 'bottom')===false){
                $this->error = '不能该菜单移动到其子菜单下';
                return false;
            }
        }
        //保存基本的信息
        if($this->save()===false){
            return false;
        }
        //保存权限
        if($this->save_permission($re_data['id'],false)===false){
            $this->error='权限修改失败';
            return false;
        }
        return true;
    }
    /**
     * 删除方法
     * @param interger $id
     * @return type
     */
    public function deleteMenu($id){
//        查询左右节点
        $menuObj=$this->where(['id'=>$id])->getField('id,lft,rght');
        $cond=[
          'lft'=>['egt',$menuObj[$id]['lft']],
          'rght'=>['elt',$menuObj[$id]['rght']]
        ];
        //修改status
         return $this->where($cond)->setField('status',0);
    }
    
    /**
     * 节点计算
     * @return \Admin\Service\NestedSets
     */
        private function _get_nestedsets(){
        $mysql_db = D('DbMySql','Logic');
        return new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
    }
        /**
         * 获取菜单数据
         * @param interger $id
         * @return boolean
         */
        public function getMenuInfo($id) {
            //得到菜单基本数据
        $row = $this->where(['status'=>1])->find($id);
        //判断菜单是否存在
        if(empty($row)){
            $this->error = '菜单不存在';
            return false;
        }
        //获取对应的权限列表
        $menu_permission_model = M('MenuPermission');
        $cond = [
            'menu_id'=>$id,
        ];
        $permission_ids = $menu_permission_model->where($cond)->getField('permission_id',true);
        $row['permission_ids'] = json_encode($permission_ids);
        return $row;
    }
    /**
     * 保存或修改权限
     * @param interger $menu_id
     * @param bool $is_new
     * @return type
     */
    public function save_permission($menu_id,$is_new=true){
        //保存权限
        $permission=I('post.perm');
        $perms=[];
        foreach($permission as $perm){
            $perms[]=array(
              'menu_id'=>$menu_id,
              'permission_id'=>$perm
            );
        }
        $permission_model=M('MenuPermission');
        if(!$is_new){
           $permission_model->where(['menu_id'=>$menu_id])->delete();
        }
        return $permission_model->addAll($perms);
    }
    //得到菜单列表
        public function getMenuList(){
        //得到会话中权限ID
        $permission_ids=  session('PERM_IDS');
//        dump($userinfo);
        $cond = [
            'permission_id'=>['in',$permission_ids],
            'status'=>1
        ];
        $menus = $this->distinct(true)->alias('m')->field('id,path,name,level,parent_id')->join('__MENU_PERMISSION__ as mp ON mp.`menu_id`=m.`id`')->where($cond)->select();
        return $menus;
    }
}
