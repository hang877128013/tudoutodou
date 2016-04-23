<?php

namespace Admin\Controller;

class ArticleController extends \Think\Controller {
    protected $_model = null;

    protected function _initialize() {
        if(!session('USERINFO')){
            if(ACTION_NAME=='login'){
                return true;
            }
            $this->success('请先登录',U('Admin/login'));
            return false;
        }
        $meta_titles = array(
            'index' => '文章管理',
            'add' => '添加文章',
            'edit' => '修改文章',
            'delete' => '删除文章',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model=D('Article');
    }
    //put your code here
    public function index() {
        $cond=array();
        //当有搜索内容时
        if(I('get.seach_name')){
            $cond['name']=array("like","%".I('get.seach_name')."%");
        }
        $this->assign($this->_model->getPageResult($cond));
        $this->assign('categorys', M('ArticleCategory')->select());
        $this->display();
    }

    //添加文章
    public function add() {
        if (IS_POST) {
            $content=I('post.content');
            //获得数据
            if($this->_model->create()===false){
                $this->error(get_error($this->_model->getError()));
            }
            $id=$this->_model->add();
            if($id===false){
                $this->error(get_error($this->_model->getError()));
            }
            $data=array(
                'article_id'=>$id,
                'content'=>I('post.content')
            );
            if(M('Article_content')->add($data)===false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('添加成功',U('index'));
        } else {
            $this->assign('articles', M('ArticleCategory')->select());
            $this->display();
        }
    }
    //编辑
    public function edit($id){
        if(IS_POST){
            //获得数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            if ($this->_model->save() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功', U('index'));
        }else{
             $this->assign('articles', M('ArticleCategory')->where(array('status'=>array('gt',-1)))->select());
             $this->assign('rows',$this->_model->find($id));
             $this->assign('content',M('ArticleContent')->find($id));
            $this->display('add');
        }
    }
    public function delete($id){
        //逻辑删除
        $data=array(
            'status'=>-1,
            'name'=>array('exp','CONCAT(name,"_delete")')
        );
         //进行修改数据  逻辑删除思路
        if ($this->_model->where(array('id' => $id))->setField($data) === false) {
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功!', U('index'));
    }
}
