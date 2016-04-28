<?php

namespace Admin\Controller;

class AddressController extends \Think\Controller {
        /**
     * 存储模型对象.
     * @var \Admin\Model\AddressModel 
     */
    private $_model = null;

    /**
     * 设置标题和初始化模型.
     */
    protected function _initialize() {
        $meta_titles  = array(
            'index'    => '收货地址管理',
        );
        $meta_title   = isset($meta_titles[ACTION_NAME]) ? $meta_titles[ACTION_NAME] : '收货地址管理';
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Address');
        
        $member_info=  session('MEMBER_INFO');
        if($member_info){
            $this->assign('member_info',$member_info);
        }
        
        //使用数据缓存出处商品分类和文章列表
        if(!$categories = S('goods_categories')){
            //获取所有的商品分类
            $categories =  D('GoodsCategory')->getList();
            S('goods_categories',$categories);
        }
        $this->assign('categories', $categories);
        
        if(!$help_articles=S('help_articles')){
            $help_articles = D('Article')->getHelpArticleList();
            S('help_articles',$help_articles);
        }
        //获取帮助文章列表
        $this->assign('help_articles',$help_articles);
        
        //首页才展示分类列表
            $this->assign('show_category', false);
        //检测是否登录
        check_login();
    }
    public function index(){
        //获得用户的收货地址
        $rows=$this->_model->getList();
        $this->assign('rows',$rows);
        $provinces=$this->_model->getListByParentId();
        $this->assign('provinces',$provinces);
        $this->display();
    }
    public function getListParentId($parent_id){
        echo json_encode($this->_model->getListByParentId($parent_id));
        exit;
    }
    
    public function add(){
        if($this->_model->create()===false || $this->_model->addAddress()===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('添加成功',U('index'));
    }
    
    public function edit($id){
        if(IS_POST){
         if($this->_model->create()===false || $this->_model->editAddress($id)===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('修改成功',U('index'));
        }else{
        //获取当前的地址
        $row=$this->_model->find($id);
//        dump($row);exit;
        $this->assign('row',$row);
        //获得用户的收货地址
        $rows=$this->_model->getList();
        $this->assign('rows',$rows);
        $provinces=$this->_model->getListByParentId();
        $this->assign('provinces',$provinces);
        $this->display();
        }
        
    }
    public function editDefault($id){
        if($this->_model->editDefault($id)===false){
           $this->error(get_error($this->_model->getError()));
        }
           $this->success('设置成功',U('index'));
    }
    public function deleteAddress($id){
        if($this->_model->deleteAddress($id)===false){
           $this->error(get_error($this->_model->getError()));
        }
           $this->success('删除成功',U('index'));
    }

}
