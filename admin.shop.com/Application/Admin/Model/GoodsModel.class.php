<?php

namespace Admin\Model;
class GoodsModel extends \Think\Model {
    //验证规则
    protected $_validate=array(
      array('name','require','商品名不能空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('goods_category_id','require','商品类别不能空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('stock','require','商品库存不能空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('shop_price','require','本店售价不能空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
      array('market_price','require','市场售价不能空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
    );
    //自动完成
    protected $_auto=array(
      array('goods_status','array_sum',self::MODEL_INSERT,'function'),
      array('inputtime',NOW_TIME,self::MODEL_INSERT),  
    );
    /**
     * 获取商品列表.
     */
    public function getList($field = '*') {
        return $this->field($field)->where(array('status' => 1))->select();
    }
    /**
     * 数据入库
     * @return boolean
     */
    public function addGoods(){
        unset($this->data['id']);
        $this->_calc_sn();
//        $data=$this->data;
       //保存其他的数据
        if(($goods_id=$this->add())===false){
            return false;
        }
        //保存商品的详细描述
        if($this->_save_content($goods_id)===false){
            $this->error='商品描述保存失败';
            return false;
        }
//       保存相册
        if($this->_save_gallery($goods_id)===false){
             $this->error='相册保存失败';
            return false;
        }
       
    }
    
    /**
     * 货号
     */
    private function _calc_sn() {
//        计算货号
        $sn = $this->data['sn'];
//        若没有添加货号  则SN+当前时间
        if (empty($sn)) {
            $day = date('Ymd');
            //如果时间过了一天 count重置为1
            $count_model = M('GoodsDayCount');
            //如果是今天的第一个商品,就插入一条记录
            if (!($count = $count_model->getFieldByDay($day, 'count'))) {
                $count = 1;
                $data  = array(
                    'day'   => $day,
                    'count' => $count,
                );
                $count_model->add($data);
                //如果还是当天  则count+1继续计算
            } else {
                $count++;
                $count_model->where(array('day' => $day))->setInc('count', 1);
            }
        }
        $this->data['sn'] = 'SN' . $day . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
    /**
     * 保存详细信息
     * @param integer $goods_id
     * @param boolean $is_new
     * @return 
     */
    public function _save_content($goods_id,$is_new = true){
        $content=  I('post.goods_content','',false);
//        dump($content);
        //创建保存数据的数组
        $data=array(
          'goods_id'=>$goods_id,
          'content'=>$content
        );
        if($is_new){
            //存储数据并返回
            return M('GoodsIntro')->add($data);
        }else{
             //保存修改数据并返回
            return M('GoodsIntro')->save($data);
        }
        
    }
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
          //循环判断精品等
          foreach ($rows as $key=>$val){
              $rows[$key][is_jing]=$val['goods_status'] & 1?1:0;
              $rows[$key][is_xin]=$val['goods_status'] & 2?1:0;
              $rows[$key][is_re]=$val['goods_status'] & 4?1:0;
          }
         return array(
           'rows'=>$rows,
           'page_html'=>$page_html
         );
    }
    /**
     * 取得详细信息方法
     * @param integer $goods_id
     * @return boolean
     */
    public function getContent($goods_id){
        $row=$this->where(array('status'=>1))->find($goods_id);
        if(empty($row)){
            $this->error='商品不存在';
            return false;
        }
        //判断精品等
        $goods_status=$row['goods_status'];
        $row['goods_status']=array();
        if($goods_status & 1){$row['goods_status'][]=1;}
         if($goods_status & 2){$row['goods_status'][]=2;}
          if($goods_status & 4){$row['goods_status'][]=4;}
         //转为json
          $row['goods_status']=  json_encode($row['goods_status']);
        $content= M('GoodsIntro')->getFieldByGoodsId($goods_id, 'content');
        $row['content']=$content?$content:'';
        //获取相册的内容
        $paths = M('GoodsGallery')->where(array('goods_id'=>$goods_id))->getField('goods_id,goods_id,path',true);
        $row['paths'] = $paths ? $paths : array();
//        dump($row);exit;
        return $row;
    }
    /**
     * 修改数据方法
     * @return boolean
     */
    public function editGoods(){
        //获得ID
        $good_id=$this->data['id'];
//        dump(I('post.'));exit;
        //保存goods表对应数据
        if($this->save() === false){
            return false;
        }
        $this->_save_content($good_id, false);
         //保存相册
        if($this->_save_gallery($good_id)===false){
             $this->error='相册保存失败';
            return false;
        }
    }
    /**
     * 删除图片方法
     * @param integer $id
     * @return type
     */
    public function deleteGoods($id){
        //数据修改模板
        $data = array('status' => -1, 'name' => array('exp', "CONCAT(name,'_delete')"));
        //修改返回
        return $this->where(array('id' => $id))->setField($data);
    }
    /**
     * 保存相册.
     * @param integer $goods_id
     * @return boolean
     */
    private function _save_gallery($goods_id){
        $paths = I('post.path');
        if(!$paths){
            return true;
        }
        $gallery_model = M('GoodsGallery');
        //用于保存所有的图片信息
        $data = array();
        foreach ($paths as $path){
            $data[] = array(
                'goods_id'=>$goods_id,
                'path'=>$path,
            );
        }
        return $gallery_model->addAll($data);
        
    } 
 }
   

