<?php
session_start();
include '../config/db.php';

if(isset($_POST['register'])){
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role  = trim($_POST['role']);

    // Basic validation
    if(empty($name) || empty($email) || empty($password) || empty($role)){
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.php");
        exit();
    }

    // Check for duplicate email
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0){
        $_SESSION['error'] = "Email already registered!";
        header("Location: register.php");
        exit();
    }

    // Handle profile picture
    $profile_path = null;
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $profile_dir = "../uploads/profile/";
        if(!is_dir($profile_dir)) mkdir($profile_dir, 0777, true);
        $profile_file = $profile_dir . basename($_FILES['profile_pic']['name']);
        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_file)){
            $profile_path = "uploads/profile/" . basename($_FILES['profile_pic']['name']);
        }
    }

    // Handle signature
    $signature_path = null;
    if(isset($_FILES['signature']) && $_FILES['signature']['error'] == 0){
        $signature_dir = "../uploads/signature/";
        if(!is_dir($signature_dir)) mkdir($signature_dir, 0777, true);
        $signature_file = $signature_dir . basename($_FILES['signature']['name']);
        if(move_uploaded_file($_FILES['signature']['tmp_name'], $signature_file)){
            $signature_path = "uploads/signature/" . basename($_FILES['signature']['name']);
        }
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $sql = "INSERT INTO users (name, email, password, role, profile_pic, signature) 
            VALUES ('$name', '$email', '$hashedPassword', '$role', 
                    '".($profile_path ?? '')."', 
                    '".($signature_path ?? '')."')";

    if(mysqli_query($conn, $sql)){
        $_SESSION['success'] = "Registration successful! You can now login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . mysqli_error($conn);
        header("Location: register.php");
        exit();
    }
}
?>
