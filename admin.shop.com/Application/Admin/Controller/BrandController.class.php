<?php

namespace Admin\Controller;

class BrandController extends \Think\Controller {

    protected $_model = null;

    protected function _initialize() {
        $meta_titles = array(
            'index' => '品牌管理',
            'add' => '添加品牌',
            'edit' => '修改品牌',
            'delete' => '删除品牌',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Brand');
    }

    public function index() {
        //条件数组
        $cond = array();
        if (I('get.seach_name')) {
            $cond['name'] = array('like', '%' . I('get.seach_name') . '%');
        }
//        dump($this->_model->getPageResult());exit;
//        $rows=$this->_model->getPageResult($cond);
        $suppliers = D('Supplier')->where(array('status' => array('gt', -1)))->select();
        $this->assign($this->_model->getPageResult($cond));
        $this->assign('suppliers', $suppliers);
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
            $this->assign('suppliers', D('Supplier')->where(array('status' => array('gt', -1)))->select());
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
            $this->assign('suppliers', D('Supplier')->where(array('status' => array('gt', -1)))->select());
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
