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
);