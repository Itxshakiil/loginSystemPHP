<?php
$dbname= "loginsystem";
$dsn="mysql:host=localhost;dbname={$dbname}";
$server_username="root";
$server_password="";
$options=[];
try {
    $connection=new PDO($dsn, $server_username, $server_password, $options);
} catch (PDOException $e) {
    $message= "An Error Occured.";
    echo "<script>console.log('{$e}');</script>";
}
