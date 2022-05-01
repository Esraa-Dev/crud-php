<?php
$dsn='mysql:host=localhost;dbname=blog;';
$user='root';
$pass='';

$option=array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',);
try{
    $connection = new PDO($dsn,$user,$pass,$option);
    $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Failed to connect". $e-> getMessage();
}