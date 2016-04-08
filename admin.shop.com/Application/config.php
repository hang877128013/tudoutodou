<?php

return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__IMG__' => 'http://www.xiangmu.com/Public/img',
        '__CSS__' => 'http://www.xiangmu.com/Public/css',
        '__JS__' => 'http://www.xiangmu.com/Public/js',
    ),
    'UPLOAD_SETTING'    => array(
        'mimes'    => array('image/jpeg', 'image/png', 'image/gif'), //允许上传的文件MiMe类型
        'maxSize'  => 1024000, //上传的文件大小限制 (0-不做限制)
        'exts'     => array('jpg', 'jpeg', 'png', 'gif'), //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/', //保存根路径
        'savePath' => 'logo/', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    ),
);
