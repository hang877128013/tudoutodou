<?php
namespace Admin\Controller;
class CategoryController extends \Think\Controller {
        protected $_model = null;
        protected $tablePrefix='Article_';

    protected function _initialize() {
        $meta_titles = array(
            'index' => '分类管理',
            'add' => '添加分类',
            'edit' => '修改分类',
            'delete' => '删除分类',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Category');
    }
    //put your code here
        public function index() {
        $cond=array();
        //当有搜索内容时
        if(I('get.seach_name')){
            $cond['name']=array("like","%".I('get.seach_name')."%");
        }
        $this->assign($this->_model->getPageResult($cond));
        $this->display();
    }
     //添加
    public function add() {
        if (IS_POST) {
            //得到数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if ($this->_model->add() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    //修改
    public function edit($id) {
        if (IS_POST) {
            //获得数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if ($this->_model->save() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功', U('index'));
        } else {
            $this->assign('rows', $this->_model->find($id));
            $this->display('add');
        }
    }

    //删除
    public function delete($id) {
        //设置修改数据
        $data = array(
            'status' => -1,
            'name' => array('exp', "CONCAT(name,'_delete')")
        );
        //进行修改数据  逻辑删除思路
        if ($this->_model->where(array('id' => $id))->setField($data) === false) {
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功!', U('index'));
    }

}
