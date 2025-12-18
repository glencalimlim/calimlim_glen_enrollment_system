<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit();
}

$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;
$check = mysqli_query($conn, "SELECT * FROM subjects WHERE id='$subject_id' AND faculty_id='{$user['id']}'");
if(mysqli_num_rows($check) == 0){
    die("Invalid subject or not assigned to you.");
}

$students = mysqli_query($conn, "
    SELECT u.id, u.name, u.email, u.profile_pic, u.signature
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    WHERE e.subject_id='$subject_id'
    ORDER BY u.name
");
$subject = mysqli_fetch_assoc($check);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Profiles - <?= htmlspecialchars($subject['name']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.id-card-img { width: 120px; height: 90px; object-fit: cover; border: 1px solid #ccc; border-radius: 5px; }
</style>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 text-center">Student Profiles: <?= htmlspecialchars($subject['name']); ?></h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if(mysqli_num_rows($students) > 0): ?>
        <div class="row">
            <?php while($s = mysqli_fetch_assoc($students)): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-header"><?= htmlspecialchars($s['name']); ?></div>
                        <div class="card-body">
                            <p>Email: <?= htmlspecialchars($s['email']); ?></p>
                            <p>Profile Picture:</p>
                            <?php if(!empty($s['profile_pic'])): ?>
                                <img src="../<?= $s['profile_pic']; ?>" class="id-card-img mb-2">
                            <?php else: ?>
                                <p class="text-muted">No picture</p>
                            <?php endif; ?>
                            <p>Signature:</p>
                            <?php if(!empty($s['signature'])): ?>
                                <img src="../<?= $s['signature']; ?>" class="id-card-img">
                            <?php else: ?>
                                <p class="text-muted">No signature</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No students enrolled yet.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
