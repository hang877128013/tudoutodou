<?php
namespace Admin\Controller;
class GoodsGalleryController extends \Think\Controller {
    //put your code here
    //删除图片
    public function delete($id){
        $goods_gallery_model=D('GoodsGallery');
        
        if($goods_gallery_model->where(array('id' => $id))->delete()===false){
            return false;
        }
    }
}
