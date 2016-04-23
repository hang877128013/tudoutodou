<?php

namespace Admin\Controller;
class RoleController extends \Think\Controller {
       protected $_model = null;
        /**
         * VIEW视图的文字   模型的调用
         */
    protected function _initialize() {
        if(!session('USERINFO')){
            if(ACTION_NAME=='login'){
                return true;
            }
            $this->success('请先登录',U('Admin/login'));
            return false;
        }
        $meta_titles = array(
            'index' => '角色管理',
            'add' => '添加角色',
            'edit' => '修改角色',
            'delete' => '删除角色',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Role');
    }
    //put your code here
    public function index(){
                //得到数据库数据
//        dump($rows);exit;
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    
    }
    //添加方法
    public function add(){
        if(IS_POST){
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->addRole()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        }else{
            $this->_before_view();
            $this->display();
        }
    
    }
    //修改方法
    public function edit($id){
        if(IS_POST){
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->editRole($id)===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功',U('index'));
        }else{
//            dump(M('RolePermission')->where(array('role_id'=>$id))->find());exit;
            $row = $this->_model->getRoleInfo($id);
            $this->assign('rows', $row);
            $this->_before_view();
            $this->display('add');
        }
    }
    //逻辑删除方法
    public function delete($id){
        if($this->_model->deleteRole($id)===false){
            $this->error(get_error($this->_model->getError()));
        }$this->success('删除成功',U('index'));
    }
     /**
     * 准备分类列表用于选择父级分类,ztree插件使用的是json对象,所以传递的是json字符串.
     */
    private function _before_view() {
        //准备所有的权限,用于ztree展示
        $categories = D('Permission')->getList('id,name,parent_id');

        $this->assign('categories', json_encode($categories));
    }
}
