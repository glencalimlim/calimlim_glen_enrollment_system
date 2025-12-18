<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
if($user['role'] != 'faculty'){
    header("Location: ../login.php");
    exit;
}
?>
