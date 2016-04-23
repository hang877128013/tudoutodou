<?php
namespace Admin\Controller;
class GoodsCategoryController extends \Think\Controller {
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
        $this->_model=D('GoodsCategory');
    }
        /**
         *  显示方法
         */
    public function index(){
        //得到数据库数据
        $this->assign('rows',$this->_model->where(array('status'=>array('egt',0)))->order('lft asc')->select());
        //引入模板
        $this->display();
    }
         /**
         *  添加方法
         */
    public function add(){
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //添加到数据库
            if($this->_model->addCategory()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        } else {
            //获得数据
            $gods=$this->_model->field($field)->where(array('status' => 1))->select();
//            dump($gods);exit;
            //转为json数据后传入
        $this->assign('goods',json_encode($gods));
        $this->display();
        }
    }
        /**
         *  修改方法
         */
    public function edit($id){
        if(IS_POST){
//            dump($this->_model->create());exit;
            //获取数据
            if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            //修改数据库数据
           if($this->_model->editCategory()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功',U('index'));
        }else{
            //数据传入
             $rows=$this->_model->find($id);
         $gods=$this->_model->field($field)->where(array('status' => 1))->select();
         $this->assign('goods',json_encode($gods));
         $this->assign('rows',$rows);
        $this->display('add');
        }
    }
        /**
         *  删除方法
         */
    public function delete($id){
        //删除数据
        if($this->_model->deleteCategory($id)===false){
             $this->error(get_error($this->_model->getError()));
        }
         $this->success('删除成功',U('index'));
    }
}
