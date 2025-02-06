<?php
    session_start();

    require_once './functions.php';

    if(!isset($_SESSION['admin'])){
        redirect('login');
    }

    function logged_in(){
        if(isset($_SESSION['admin'])){
            return true;
        }else{
            return false;
        }
    }
?>
