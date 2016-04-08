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

