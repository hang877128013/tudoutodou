<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
        'DB_TYPE'=>'MYSQL',//数据库的类型
        'DB_HOST'=>'127.0.0.1',//数据库的地址
        'DB_NAME'=>'shopdb',//数据库名
        'DB_USER'=>'root',//数据库用户名
        'DB_PWD'=>'123456',//数据库密码
        'DB_PORT'=>'3306',//数据库端口
    'ALIDAYU_SETTING'=>[
        'ak'=>'23351012',
        'sk'=>'f69475e2798427a88c50cd123dc9e778',
    ],
        'EMAIL_SETTING' => [
        'host'       => 'smtp.163.com',
        'username'   => '18717001517@163.com',
        'password'   => 'zhaohang124',
//        'smtpsecure' => 'ssl',
//        'port'       => 465,
    ],
//    'EMAIL_SETTING'=>[
//      'host'=>'smtp.163.com',
//      'username'=>'18717001517@163.com',
//      'password'=>'zhaohang124',
//      'smtpsecure'=>'ssl',
//      'port'=>'465',
//      ''
//    ],
//    //Redis Session配置
//    'SESSION_AUTO_START' => true, // 是否自动开启Session
//    'SESSION_TYPE'       => 'Redis', //session类型
//    'SESSION_PERSISTENT' => 1, //是否长连接(对于php来说0和1都一样)
//    'SESSION_CACHE_TIME' => 1, //连接超时时间(秒)
//    'SESSION_EXPIRE'     => 0, //session有效期(单位:秒) 0表示永久缓存
//    'SESSION_PREFIX'     => 'sess_', //session前缀
//    'SESSION_REDIS_HOST' => '127.0.0.1', //分布式Redis,默认第一个为主服务器
//    'SESSION_REDIS_PORT' => '6379', //端口,如果相同只填一个,用英文逗号分隔
//    'SESSION_REDIS_AUTH' => '', //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
//      'HTML_CACHE_ON'     =>    true, 
//        // 开启静态缓存
//      'HTML_CACHE_TIME'   =>    60,   
//// 全局静态缓存有效期（秒）
//      'HTML_FILE_SUFFIX'  =>    '.shtml', 
//// 设置静态缓存文件后缀
//      'HTML_CACHE_RULES'  =>     array(
////          'goods'=>['goods_info{id}',60],
//          'Index:goods'=>['goods/goods_{id}',60],
//          'Index:index'=>['{:action}',3600]
//          ),
////     /* 数据缓存设置 */
//    'DATA_CACHE_TYPE'       =>  'Redis',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
//    'REDIS_HOST'=>'127.0.0.1',
//    'REDIS_PORT'=>6379,
//    'DATA_CACHE_TIMEOUT'=>3600,
        );