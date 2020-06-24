<?php
session_start();
if (empty($_SESSION['user_id'])) {
    require_once('get_url.php');
    $_SESSION['url']=full_path();
    header('location:login.php');
} else {
    echo 'Welcome',$_SESSION['name'].'<br>';
    echo 'Your user id :'.$_SESSION['user_id'];
}
