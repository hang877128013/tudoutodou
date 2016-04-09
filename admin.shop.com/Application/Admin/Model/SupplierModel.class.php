<?php
namespace Admin\Model;
class SupplierModel extends \Think\Model {
    protected $_validate=array(
        array('name','require','供应商名称不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('name','','供应商已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT),
    );
        public function getPageResult(array $cond=array()){
        $cond =$cond+array( 'status'=>array('gt',-1));//条件:status大于-1 和搜索关键字条件
        //获取总行数
        $count = $this->where($cond)->count();
       // return $count;
        $size = C('PAGE_SIZE');//获取每页显示数
        $page_obj = new \Think\Page($count,$size);//创建分页模型
        $page_obj->setConfig('theme', C('PAGE_THEME'));//设置分页属性
        $page_html = $page_obj->show();//显示分页
        $rows = $this->where($cond)->page(I('get.p'),$size)->select();
        //return $rows;
        return array(
            'rows'=>$rows,
            'page_html'=>$page_html,
        );
    }
}
