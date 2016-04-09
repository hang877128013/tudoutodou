<?php
namespace Admin\Model;
class BrandModel extends \Think\Model {
    //添加条件
    protected $_validate=array(
      array('name','require','品牌名不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('name','','品牌已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    //分页方法
    public function getPageResult($cond=array()){
        //设置条件
        $cond=$cond+array('status'=>array('gt',-1));
        //获取数据总条数
         $count = $this->where($cond)->count();
         //获取设置的每页显示条数
         $size=C('PAGE_SIZE');
         //创建分页的模型
         $page_obj=new \Think\Page($count,$size);
         //设置分页的属性
         $page_obj->setConfig('theme', C('PAGE_THEME'));
         //显示分页
         $page_html=$page_obj->show();
         //查询数据
         $rows = $this->where($cond)->page(I('get.p'),$size)->select();
          
         return array(
           'rows'=>$rows,
           'page_html'=>$page_html
         );
    }
}
