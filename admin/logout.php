<?php
session_start();//continue session
unset($_SESSION['admin']);
header('location:login.php');
exit();?>