<?php

namespace Admin\Controller;

class GoodsController extends \Think\Controller {

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
            'index' => '商品管理',
            'add' => '添加商品',
            'edit' => '修改商品',
            'delete' => '删除商品',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Goods');
    }

    /**
     * index显示方法
     */
    public function index() {
        if(IS_POST){
//            dump($this->_model->create());
            $datas=(I('post.'));
//            dump($data);
            $cond=array();
            foreach($datas as $key=>$data){
                if($data){
               $cond[$key]=$data;
                }
            }
//            dump($cond);exit;
            $categories = D('GoodsCategory')->getList('id,name,parent_id');
            $this->assign('goods',$categories);
           $this->_before_view();
            //        dump($this->_model->getPageResult());exit;
        $this->assign($this->_model->getPageResult($cond));
        $this->display();
        }else{
//            $p=I('get.p');
//            $this->assign('p',$p);
            $categories = D('GoodsCategory')->getList('id,name,parent_id');
            $this->assign('goods',$categories);
           $this->_before_view();
            //        dump($this->_model->getPageResult());exit;
        $this->assign($this->_model->getPageResult());
        $this->display();
        }

    }
    /**
     * 添加方法
     */
    public function add() {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if ($this->_model->addGoods() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功', U('index'));
        } else {
            //传入
            $this->_before_view();
            $this->display();
        }
    }
    /**
     * 修改方法
     */
    public function edit($id) {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if ($this->_model->editGoods() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功', U('index'));
        } else {
            //        dump($this->_model->getContent($id));exit;
//        //判断是否有数据
//            dump($this->_model->getContent($id));exit;
            if (!($row = $this->_model->getContent($id))) {
                $this->error('抱歉,找不到商品!', U('index'));
            }
            //传数据
//        dump($row);exit;
            $this->assign('row', $row);
            //传入
            $this->_before_view();
            $this->display('add');
        }
    }
    /**
     * 删除方法
     */
    public function delete($id) {
//        dump($id);exit;
        if ($this->_model->deleteGoods($id) === false) {
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功', U('index'));
    }

    /**
     * 准备分类列表用于选择父级分类,ztree插件使用的是json对象,所以传递的是json字符串.
     */
    private function _before_view() {
        //商品分类
        $categories = D('GoodsCategory')->getList('id,name,parent_id');
        $this->assign('categories', json_encode($categories));
//        dump($categories);exit;
        //商品品牌
        $brands = D('Brand')->getList('id,name');
        $this->assign('brands', $brands);
        //商品供货商
        $suppliers = D('Supplier')->getList('id,name');
        $this->assign('suppliers', $suppliers);
    }

}
