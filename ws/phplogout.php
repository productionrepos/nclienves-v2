<?php   
session_start();
session_destroy();
// $url = "https://".$_SERVER['HTTP_HOST']."/index.php";
$url = "/index.php";
echo $url;
header('Location: '.$url);
?>