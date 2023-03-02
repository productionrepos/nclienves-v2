<?php session_start();
    if(!isset( $_SESSION['cliente'])) {
        require_once('login.php');
    }
    else {
        require_once('inicio.php');
    }
?>