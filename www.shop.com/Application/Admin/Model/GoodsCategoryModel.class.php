<?php
namespace Admin\Model;

class GoodsCategoryModel extends \Think\Model{
    
    /**
     * 获取分类列表.
     * @return array
     */
    public function getList(){
        $cond = [
            'status'=>1,
        ];
        return $this->where($cond)->select();
    }
}
