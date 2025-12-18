<?php
session_start();

/*
 If user is already logged in,
 redirect them based on role
*/
if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] == 'faculty') {
        header("Location: faculty/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }

} else {
    header("Location: auth/login.php");
}
