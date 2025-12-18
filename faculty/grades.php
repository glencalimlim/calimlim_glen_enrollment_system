<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

// Handle grade submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $grade = intval($_POST['grade']);

    if($grade >= 1 && $grade <= 5){
        $update = mysqli_query($conn, "
            UPDATE enrollments 
            SET grade='$grade' 
            WHERE student_id='$student_id' AND subject_id='$subject_id'
        ");

        if($update){
            $msg = "Grade submitted successfully!";
        } else {
            $msg = "Failed to submit grade.";
        }
    } else {
        $msg = "Invalid grade selected.";
    }

    // Redirect back to dashboard with message
    header("Location: dashboard.php?msg=" . urlencode($msg));
    exit;
}

// If accessed directly without POST
header("Location: dashboard.php");
exit;
