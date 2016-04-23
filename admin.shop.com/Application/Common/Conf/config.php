<?php
return array(
	//'配置项'=>'配置值'
     'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    
         /* 数据库设置 */
    'DB_TYPE'               =>  'MYSQL',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'shopdb',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口    
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'SHOW_PAGE_TRACE'=>true,
    'PAGE_SIZE'=>2,//分页数
    'PAGE_THEME'=>'%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
    'URL_MODEL'=>2,
    'IGNORE_PATHS' => [
            'Admin/Admin/login',
            'Admin/Captcha/captcha',
            'Admin/Index/index',
            'Admin/Index/top',
            'Admin/Index/menu',
            'Admin/Index/main',   
        ],
    
         'UPLOAD_SETTING'=>array(
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  './Uploads/', //保存根路径
        'savePath'      =>  '', //保存路径
        'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
        'hash'          =>  true, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
        'driver'        =>  'QINIU', // 文件上传驱动
        'driverConfig'  =>  array(
            'secrectKey'     => 'wvXVzD86_RPdoiHw5WF1U4yqQ4pHFIcfWHU-GILL', //七牛sk
            'accessKey'      => 'GeYqabAMn2ghGKu9Rba7mdhQI6CzL-Vb7gx25tXL', //七牛ak
            'domain'         => '7xsvc8.com1.z0.glb.clouddn.com', //七牛空间域名
            'bucket'         => 'shop', //空间名称
            'timeout'        => 300, //超时时间
        ), // 上传驱动配置
        )

);