<?php
namespace Admin\Controller;
class SupplierController extends \Think\Controller {
    public function index(){
        $this->assign('rows',M('Supplier')->select());
        $this->display();
    }
}
