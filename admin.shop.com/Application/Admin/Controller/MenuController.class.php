<?php

namespace Admin\Controller;
class MenuController extends \Think\Controller {
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
            'index' => '菜单管理',
            'add' => '添加菜单',
            'edit' => '修改菜单',
            'delete' => '删除菜单',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Menu');
    }
    //显示
    public  function index(){
        $this->assign('rows', $this->_model->where(['status'=>1])->getList());
        $this->display();
    }
    //添加
    public function add(){
        if(IS_POST){
           if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->addMenu()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        }else{
        $this->_before_view();
        $this->display();    
        }
    }
    //修改
    public function edit($id){
        if(IS_POST){
           if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->editMenu()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        }else{
            $this->assign('row', $this->_model->getMenuInfo($id));
        $this->_before_view();
        $this->display('add');    
        }
    }
    //删除
    public function delete($id){
        if($this->_model->deleteMenu($id)===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功');
    }
    //页面数据
    private function _before_view(){
        //准备权限列表
        $permission_model = D('Permission');
        $permissions = $permission_model->getList('id,name,parent_id');
        $this->assign('permissions', json_encode($permissions));
        //准备菜单列表
        $menus = $this->_model->getList('id,name,parent_id');
        array_unshift($menus, ['id'=>0,'parent_id'=>0,'name'=>'顶级菜单']);
        $this->assign('menus', json_encode($menus));
    }
}
