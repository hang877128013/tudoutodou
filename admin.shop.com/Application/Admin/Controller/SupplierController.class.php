<?php
namespace Admin\Controller;
class SupplierController extends \Think\Controller {
        protected $_model=null;
        protected function _initialize() {
            if(!session('USERINFO')){
            if(ACTION_NAME=='login'){
                return true;
            }
            $this->success('请先登录',U('Admin/login'));
            return false;
        }
        $meta_titles = array(
            'index'  => '供货商管理',
            'add'    => '添加供货商',
            'edit'   => '修改供货商',
            'delete' => '删除供货商',
        );
        $meta_title  = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Supplier');
    }
    //显示
    public function index(){
        $cond=array();//条件数组
        if(I('get.seach_name')){
            $cond['name']=array('like','%'.I('get.seach_name').'%');//搜索关键字的条件
            //dump($this->_model->getPageResult($cond));exit;
        }
//        dump($this->_model->getPageResult($cond));exit;
        $this->assign($this->_model->getPageResult($cond));
        $this->display();
    }
    //添加
    public function add(){
        if(IS_POST){
            //获取数据
            if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->add()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功!',U('index'));
        }else{
             $this->display();
        }
        
    }
    //修改
    public function edit($id){
        if(IS_POST){
            if($this->_model->create()===false){
                 $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->save()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功',U('index'));
           
        }else{
             $rows=$this->_model->find($id);
             $this->assign('rows',$rows);
             $this->display('add');
        }
    }
     //删除
        public function delete($id){
            //设置数据
            $data=array(
              'status'=>-1,
              'name'=>array('exp',"CONCAT(name,'_delete')")
            );
            //进行修改数据  逻辑删除思路
            if($this->_model->where(array('id'=>$id))->setField($data)===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('删除成功!',U('index'));
        }
}
