<?php
namespace Admin\Controller;
class UploadController extends \Think\Controller {
    //put your code here
    public function upload(){
        
        //创建upload模型

        $upload=new \Think\Upload(C('UPLOAD_SETTING'));
        //上传文件
        $upload_info=$upload->upload($_FILES);
        //保存图片地址
        $img_url=$upload_info['Filedata']['savepath'].$upload_info['Filedata']['savename'];
//        var_dump($img_url);
        $img_url=  str_replace('/', '_', $img_url);
        $return=array(
          'status'=>$img_url?1:0,
          'file_url'=>$img_url,
            'msg'=>$upload->getError()
        );
        $this->ajaxReturn($return);
}
}
