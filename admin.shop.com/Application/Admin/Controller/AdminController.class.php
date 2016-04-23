<?php
namespace Admin\Controller;
class adminController extends \Think\Controller {
                protected $_model = null;
        /**
         * VIEW视图的文字   模型的调用
         */
    protected function _initialize() {
        $meta_titles = array(
            'index' => '管理员管理',
            'add' => '添加管理员',
            'edit' => '修改管理员',
            'delete' => '删除管理员',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Admin');
        if(!session('USERINFO')){
            if(ACTION_NAME=='login'){
                return true;
            }
            $this->success('请先登录',U('Admin/login'));
            return false;
        }
    }
    public function login(){
        
        if(IS_POST){
            if($this->_model->create('','login')===false){
                $this->error(get_error($this->_model->getError()));
            }
//            dump($_POST);exit;
            if($this->_model->login()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('登录成功',U('Index/index'));
        }else{
        $this->display(); 
        }
    }
    public function logout(){
        //清除session和cookie
        session(null);
        cookie(null);
        $this->success('退出成功',U('login'));
    }
    //显示方法
    public function index(){
        $this->assign('rows',$this->_model->select());
        $this->display();
    }
    //添加方法
    public function add(){
        if(IS_POST){
            if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->addAdmin()===false){
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
            if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->editAdmin()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        }else{
//            $this->assign('rows', $this->_model->find($id));
            $this->assign('row',$this->_model->getAdminInfo($id));
//            dump($this->_model->getAdminInfo($id));
            $this->_before_view();
            $this->display('add');
        }
    }
    //重置密码
    public function editPwd($id){
        if(IS_POST){
           if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if(($pwd=$this->_model->editAdminPwd())===false){
                $this->error(get_error($this->_model->getError()));
            }
            session('pwd',$pwd);
            $this->success('密码重置成功',U('succe'));
        }else{
            $this->assign('row',$this->_model->getAdminInfo($id));
            $this->display();
        }
    }
    //重置成功后跳转
    public function succe(){
        $pwd=  session('pwd');
        session('pwd',null);
        $this->assign('pwd',$pwd);
        $this->display();
    }
    //删除方法
    public function delete($id){
        if($this->_model->deleteAdmin($id)===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功',U('index'));
    }
    //权限和角色的数据
    private function _before_view(){
        $permission=D('Permission')->getList('id,name,parent_id');
        array_unshift($permission, ['id'=>0,'parent_id'=>0,'name'=>'顶级分类']);
        $this->assign('permissions', json_encode($permission));
        $roles=D('Role')->getList('id,name');
        $this->assign('roles', $roles);
    }
}
