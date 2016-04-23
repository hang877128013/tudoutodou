<?php
namespace Admin\Controller;
class PermissionController extends \Think\Controller {
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
            'index' => '分类管理',
            'add' => '添加分类',
            'edit' => '修改分类',
            'delete' => '删除分类',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Permission');
    }
    //put your code here
    public function index(){
        //得到数据库数据
//        dump($rows);exit;
        $this->assign('rows',$this->_model->where(array('status'=>1))->order('lft asc')->select());
        $this->display();
    }
    public function add(){
        if(IS_POST){
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->addPermission()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        }else{
            $this->_before_view();
            $this->display();
        }
    }
    public function edit($id){
        if(IS_POST){
             if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->editPermission()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功',U('index'));
        }else{
            $this->assign('rows',$this->_model->find($id));
             $this->_before_view();
            $this->display('add');
        }
    }
    public function delete($id){
        if($this->_model->deletePermission($id)===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('删除成功',U('index'));
    }
        /**
     * 准备分类列表用于选择父级分类,ztree插件使用的是json对象,所以传递的是json字符串.
     */
    private function _before_view() {
        //准备所有的权限,用于ztree展示
        $categories = $this->_model->getList('id,name,parent_id');
        array_unshift($categories,array('id'=>0,'name'=>'顶级分类','parent_id'=>0));
//        dump($categories);
        $this->assign('categories', json_encode($categories));
    }
}
