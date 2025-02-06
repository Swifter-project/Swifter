<?php
$img_url = '../static/img/';
$classes_url = dirname(__DIR__) . '/static/classes/';
function redirect($url){
    $url = $url . ".php";
    header('Location: ' . $url);
}
function validate_form($fields){
    if(!isset($_POST))
        return false;
    for ($i = 0; $i < count($fields); $i++) {
        if (!isset($_POST[$fields[$i]])) {
            return false;
        }

        if ($_POST[$fields[$i]] == null) {
            return false;
        }
    }
    return true;
}
?>