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

function ArrSelected(array $data,$name,$value_field='id',$name_field='name',$select=''){
    $html = '<select name="' . $name .'">';
    $html .= '<option value="">请选择...</option>';
    foreach($data as $value){
        if($value[$value_field] == $select){
            $html .= '<option value="'.$value[$value_field].'" selected="selected">' .$value[$name_field]. '</option>';
        }else{
            $html .= '<option value="'.$value[$value_field].'">' .$value[$name_field]. '</option>';
        }
    }
    $html .='</select>';
    return $html;
}
function salt_password($password,$salt){
    return md5(md5($password).$salt);
}
