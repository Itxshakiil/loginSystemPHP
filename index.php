<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
    echo 'Welcome',$_SESSION['name'].'<br>';
    echo 'Your user id :'.$_SESSION['user_id'];
}
