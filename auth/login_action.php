<?php
session_start();
include '../config/db.php';

$email = trim($_POST['email']);
$password = $_POST['password']; // plain password from form

// 1. Fetch user by email
$sql = "SELECT * FROM users WHERE email='$email'";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    $user = mysqli_fetch_assoc($res);
    
    // 2. Verify password using password_verify
    if(password_verify($password, $user['password'])){
        // Login success
        $_SESSION['user'] = $user;
        header("Location: ../" . $_SESSION['user']['role'] . "/dashboard.php");
        exit();
    } else {
        echo "Invalid credentials";
    }
} else {
    echo "Invalid credentials";
}

mysqli_close($conn);
?>
