<?php

namespace Admin\Model;

class GoodsCategoryModel extends \Think\Model {

    /**
     * 添加方法
     * @return type
     */
    public function addCategory() {
//        dump($this->data);
//             dump($this->trueTableName);exit;
        //创建nestedsets
        $mysql_db = new \Admin\Logic\DbMysqlLogic;
        $nestedsets = new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
//         dump($mysql_db);exit;
        //传入参数  parent_id 数据 方向 并且返回
        return $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom');
    }

    /**
     * 修改方法
     * @return boolean
     */
    public function editCategory() {
        //获取原父级的ID
        $parent_id = $this->getFieldById($this->data['id'], 'parent_id');
//        dump($parent_id);exit;
        //判断原父级ID和当前父级ID相同时
        if ($parent_id == $this->data['parent_id']) {
            //直接保存
            return $this->save();
        } else {
            //创建nestedsets
            $mysql_db = new \Admin\Logic\DbMysqlLogic;
            $nestedsets = new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], "bottom")===false) {
                //错误提示
                $this->error = '不能将当前分类移动到子类中';
                return false;
            }
            //保存
            return $this->save();
        }
    }

    /**
     * 删除方法
     * @param type $id
     * @return type
     */
    public function deleteCategory($id) {
        //获取数据
        $cate = $this->where(array('id' => $id))->getField('id,lft,rght');
        //定义条件
        $cond = array(
            'lft' => array('egt', $cate[$id]['lft']),
            'rght' => array('elt', $cate[$id]['rght'])
        );
        //数据修改模板
        $data = array('status' => -1, 'name' => array('exp', "CONCAT(name,'_delete')"));
        //修改返回
        return $this->where($cond)->setField($data);
    }
     public function getList($field = '*') {
        return $this->field($field)->where(array('status' => 1))->select();
    }

}
