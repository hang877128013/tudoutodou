<?php

namespace Admin\Model;
class AdminModel extends \Think\Model {
    /**
     *自动验证
     * @var type 
     */
    protected $_validate=array(
        array('username','require','管理员必填',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('username','','管理员已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT),
        array('username','6,16','管理员长度不合法',self::EXISTS_VALIDATE,'length',self::MODEL_INSERT),
        array('password','require','管理员密码必填',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('password','6,16','密码长度不合法',self::EXISTS_VALIDATE,'length',self::MODEL_INSERT),
        array('email','require','邮箱必填',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('email','email','邮箱不合法',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('username', 'require', '用户名必填', self::MUST_VALIDATE, '', 'login'),
        array('password', 'require', '密码必填', self::MUST_VALIDATE, '', 'login'),
        array('captcha', 'require', '验证码必填', self::MUST_VALIDATE, '', 'login'),
        array('captcha', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback', 'login'),
    );
    /**
     *自动完成
     * @var type 
     */
    protected $_auto=array(
        array('salt','\Org\Util\String::randString',self::MODEL_BOTH,'function',6),
        array('add_time',NOW_TIME,self::MODEL_INSERT)
    );
    /**
     * 验证码机制
     * @param 验证码 $code
     * @return type
     */
    protected function check_captcha($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    /**
     * 添加方法
     * @return boolean
     */
    public function addAdmin(){
        //保存基本信息
        unset($this->data['id']);
       $this->data['password']=  salt_password($this->data['password'], $this->data['salt']);
       //保存基本数据
        if(($admin_id=$this->add())===false){
            return false;
        }
        //保存对应的角色
       if($this->save_role($admin_id)===false){
           $this->error='角色保存失败';
           return false;
       }
       //保存对应的权限
       if($this->save_permission($admin_id)===false){
           $this->error='权限保存失败';
           return false;
       }
        return true;
    }
    /**
     * 修改方法
     * @return boolean
     */
    public function editAdmin(){
        $id=$this->data['id'];
        //修改角色
        if($this->save_role($id,false)===false){
            $this->error='角色修改失败';
            return false;
        }
        //修改权限
        if($this->save_permission($id,false)===false){
            $this->error='权限修改失败';
            return false;
        }
    }
    /**
     * 重置密码
     * @return boolean
     */
    public function editAdminPwd(){
        $re_data=$this->data;
        $email=$this->field('email')->find($re_data['id']);
        if($email!=$re_data['email']){
            $this->error='邮箱和管理员不符';
            return false;
        }
//        dump($re_data);exit;
        $password=$re_data['password'];
        if(empty($password)){
            $len=  mt_rand(8, 10);
            $password = \Org\Util\String::randString($len, '', '!@#$%^&*(*)_+{}|":?><');
        }
       $this->data['password'] =  salt_password($password, $re_data['salt']);
       //保存成功后返回密码值
       return $this->save()?$password:false;
    }
    /**
     * 删除方法
     * @param interger $id
     * @return boolean
     */
    public function deleteAdmin($id){
        //删除管理员数据
        if(($this->where(['id'=>$id])->delete())===false){
            return false;
        }
        //删除对应的角色
        M('AdminRole')->where(['admin_id'=>$id])->delete();
        //删除对应的权限
        M('AdminPermission')->where(['admin_id'=>$id])->delete();
        return true;
    }
    public function login(){
        session('USERINFO',null);
        $request_data = $this->data;
        //判断是否存在用户
        $userinfo = $this->getByUsername($this->data['username']);
        if(empty($userinfo)){
            $this->error='该用户不存在';
            return false;
        }
        //当用户存在则密码是否正确
        //1.取盐值和用户输入的密码加密后和数据库中的密码比较
        $salt=$userinfo['salt'];
        $password=  salt_password($request_data['password'], $salt);
        if($password!=$userinfo['password']){
            $this->error='密码错误';
            return false;
        }
        //将数据保存到session中
        session('USERINFO',$userinfo);
        $this->_getPermission($userinfo['id']);
        
        //保存登录信息
        $this->_save_auto_login($userinfo['id']);
       return true; 
    }
    private function _save_auto_login($admin_id){
        //清楚原token
        cookie('AUTOLOGINTOKEN',null);
        //删除数据库中token
        M('AdminToken')->delete($admin_id);
        //判断是否自动登录
        $remeber=I('post.remember');
        if(!$remeber){
            return true;
        }
        $data=[
          'admin_id'=>$admin_id,
          'token'=>  sha1(mcrypt_create_iv(32))
        ];
        //存到cookie中
        cookie('AUTOLOGINTOKEN',$data,604800);
        //存到数据库
        return M('AdminToken')->add($data);
    }
    //检查令牌
    public function autoLogin(){
        $data=cookie('AUTOLOGINTOKEN');
        //查询库
        //若token不存在
        if(!M('AdminToken')->where($data)->count()){
            return false;
        }
        //当有原token
        cookie('AUTOLOGINTOKEN',null);
        M('AdminToken')->delete($data['admin_id']);
        
        //获取用户的信息  保存到session中
        $userinfo=$this->find($data['admin_id']);
        session('USERINFO',$userinfo);
        
        //再次生成token
        $data=[
          'admin_id'=>$admin_id,
          'token'=>  sha1(mcrypt_create_iv(32))
        ];
        //存到cookie中
        cookie('AUTOLOGINTOKEN',$data,604800);
        //存到数据库
        if(M('AdminToken')->add($data)===false){
            return false;
        }else{
            $this->_getPermission($data['admin_id']);
        }
    }
    public function _getPermission($admin_id){
        session('PATHS',null);
        session('PERM_IDS',null);
        //获取通过角色得到的权限
        $role_permssions = $this->distinct(true)->table('__ADMIN_ROLE__ as ar')->join('__ROLE_PERMISSION__ as rp ON ar.`role_id`=rp.`role_id`')->join('__PERMISSION__ as p ON rp.`permission_id`=p.`id`')->where(['admin_id'=>$admin_id ,'path'=>['neq','']])->getField('permission_id,path',true);
        //获取额外权限
        $admin_permissions = $this->distinct(true)->table('__ADMIN_PERMISSION__ as ap')->join('__PERMISSION__ as p ON ap.`permission_id` = p.`id`')->where(['admin_id'=>$admin_id ,'path'=>['neq','']])->getField('permission_id,path',true);
        $role_permssions=$role_permssions?:[];
        $admin_permissions=$admin_permissions?:[];        
        //得到角色对应的所有权限
        $permissions = $role_permssions+$admin_permissions;
        
        //得到权限对应的id
        $permission_ids = array_keys($permissions);
        //得到权限的path值
        $paths = array_values($permissions);
        //将路径和权限值都保存到会话中
        session('PATHS',$paths);
        session('PERM_IDS',$permission_ids);
    }
    //获取管理员数据
    public function getAdminInfo($id) {
        //得到管理员基本数据
        $row = $this->find($id);
        //判断是否存在管理员
        if (empty($row)) {
            $this->error = '管理员不存在';
            return false;
        }
        //获得角色
        $role_ids  = M('AdminRole')->where(array('admin_id' => $id))->getField('role_id', true);
        $row['role_ids'] = $role_ids;
        //获得权限
        $permission_ids= M('AdminPermission')->where(array('admin_id' => $id))->getField('permission_id', true);
        $row['permission_ids'] = json_encode($permission_ids);
        return $row;
    }
    //保存或修改角色
    private function save_role($admin_id, $is_new = true) {
        if (!$is_new) {
            //修改时删除原来的角色
            if((M('AdminRole')->where(['admin_id'=>$admin_id])->delete())===false){
                return false;
            }
        }
        //获取勾选的角色,如果没有勾选任何角色,就不再执行添加操作.
        $roles = I('post.role');
        if (empty($roles)) {
            return true;
        }
        $data = array();
        foreach ($roles as $role) {
            $data[] = array(
                'admin_id' => $admin_id,
                'role_id'  => $role,
            );
        }
        return M('AdminRole')->addAll($data);
    }
    //保存或修改权限
    public function save_permission($admin_id,$is_new=true){
        if(!$is_new){
            //修改时删除原来的权限
           if((M('AdminPermission')->where(['admin_id'=>$admin_id])->delete())===false){
               return false;
           }
        }
        //保存权限
        $permission=I('post.perm');
        $perms=[];
        foreach($permission as $perm){
            $perms[]=array(
              'admin_id'=>$admin_id,
              'permission_id'=>$perm
            );
        }
        $permission_model=M('AdminPermission');
        return $permission_model->addAll($perms);
    }
}
