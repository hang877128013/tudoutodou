<?php

function get_error($errors) {
    if (!is_array($errors)) {
        $errors = array($errors);
    }
    $html = '<ul>';
    foreach ($errors as $error) {
        $html.='<li>' . $error . '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function salt_password($password, $salt) {
    return md5(md5($password) . $salt);
}

function arr2select(array $data, $name, $value_field = 'id', $name_field = 'name', $select = '') {
    $html = '<select name="' . $name . '" class="' . $name . '">';
    $html .= '<option value="">请选择...</option>';
    foreach ($data as $value) {
        if ($value[$value_field] == $select) {
            $html .= '<option value="' . $value[$value_field] . '" selected="selected">' . $value[$name_field] . '</option>';
        } else {
            $html .= '<option value="' . $value[$value_field] . '">' . $value[$name_field] . '</option>';
        }
    }
    $html .='</select>';
    return $html;
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * @param type $telphone
 */
function sendSMS($telphone, $params, $sign_name = '注册验证', $template_code = 'SMS_8085113') {
    $config = C('ALIDAYU_SETTING');
    vendor('Alidayu.Autoloader');
    $c = new \TopClient;
    $c->appkey = $config['ak'];
    $c->secretKey = $config['sk'];
    $req = new \AlibabaAliqinFcSmsNumSendRequest;
    $req->setSmsType("normal");
    $req->setSmsFreeSignName($sign_name);
    //创建随机数
//    $data = [
//        'code' => (string) mt_rand(1000, 9999),
//        'product' => '邓狗大傻逼',
//    ];
    $req->setSmsParam(json_encode($params));
    $req->setRecNum($telphone);
    $req->setSmsTemplateCode($template_code);
    $resp = $c->execute($req);
    if (isset($resp->result->success) && $resp->result->success) {
        return true;
    } else {
        return false;
    }
}

function sendEmail($address, $subject, $content, array $attachment = []) {
    $config = C('EMAIL_SETTING');
    vendor('PHPMailer.PHPMailerAutoload');
    $mail = new \PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $config['host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $config['username'];                 // SMTP username
    $mail->Password = $config['password'];                           // SMTP password
//        $mail->SMTPSecure = $config['smtpsecure'];                            // Enable TLS encryption, `ssl` also accepted
//        $mail->Port       = $config['port'];                                    // TCP port to connect to

    $mail->setFrom($config['username']);
    $mail->addAddress($address);     // Add a recipient
    //添加附件
    if ($attachment) {
        foreach ($attachment as $item) {
            $mail->addAttachment($item);
        }
    }
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->CharSet = 'utf-8';
    return $mail->send();
}

/**
 * 获取redis对象
 * @staticvar null $instance
 * @return \Redis
 */
function getRedis() {
    static $instance = null;
    if (empty($instance)) {
        $instance = new Redis();
        $instance->connect(C('REDIS_HOST'), C('REDIS_PORT'));
    }
    return $instance;
}

function money_format($number, $decimals = 2, $dec_point = '.', $thousands_sep = '') {
    return number_format($number, $decimals, $dec_point, $thousands_sep);
}

/**
 * 检查用户是否登录
 */
function check_login() {
    if (!session('MEMBER_INFO')) {
        redirect(U('Member/login'), 1, '请先登录');
    }
}

/**
 * 获取登录用户的ID
 * @return boolean
 */
function get_userId() {
    if ($userinfo = session('MEMBER_INFO')) {
        return $userinfo['id'];
    } else {
        return false;
    }
}

/**
 * 订单号
 */
function _calc_sn() {
//        若没有添加货号  则SN+当前时间
    $day = date('Ymd');
    //如果时间过了一天 count重置为1
    $count_model = M('OrderDayCount');
    //如果是今天的第一个商品,就插入一条记录
    if (!($count = $count_model->getFieldByDay($day, 'count'))) {
        $count = 1;
        $data = array(
            'day' => $day,
            'count' => $count,
        );
        $count_model->add($data);
        //如果还是当天  则count+1继续计算
    } else {
        $count++;
        $count_model->where(array('day' => $day))->setInc('count', 1);
    }
    $sn = $day . str_pad($count, 8, '0', STR_PAD_LEFT);
    return $sn;
}
