<?php

namespace Admin\Controller;

class LianxiController extends \Think\Controller {

    public function index() {
        /**
         * 直接实例化
          可以和实例化其他类库一样实例化模型类，例如：
          $User = new \Home\Model\UserModel();
          $Info = new \Admin\Model\InfoModel();
          带参数实例化
          $New  = new \Home\Model\NewModel('blog','think_',$connection);
         * * */
//        $lianxi = new \Admin\Model\LianxiModel();
//        dump($lianxi);
//         带参数实例化
//          $new=new \Admin\Model\LianxiModel('lianxi','','mysql://root:123456@127.0.0.1:3306/shopdb');//三个参数(模型名,表前缀,数据库连接信息)
//          dump($new);                                   //数据库类型://用户名:密码@地址:端口/数据库名----type://username:passwd@hostname:port/DbName
//            D方法还可以支持跨模块调用，
//          需要使用：//实例化Admin模块的User模型D('Admin/User');
//          实例化Extend扩展命名空间下的Info模型D('Extend://Editor/Info');
        }

}
