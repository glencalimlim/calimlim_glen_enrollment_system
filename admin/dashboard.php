<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f8f9fa;
}
.id-card-img {
    width: 150px;
    height: 100px;
    object-fit: cover;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.card-header {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 500;
}
</style>
</head>
<body>

<!-- ADMIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php">Enrollment System</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link " href="subjects.php">Subjects</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="assign_faculty.php">Assign Faculty</a></li>
            <li class="nav-item"><a class="nav-link" href="prerequisites.php">Prerequisites</a></li>
        </ul>
        <span class="text-white me-3"><?= $_SESSION['user']['name'] ?></span>
        <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</div>
</nav>

<!-- CONTENT -->
<div class="container py-4">

    <h2 class="mb-4 text-center">
        Welcome, <?= htmlspecialchars($user['name']); ?>
    </h2>

    <!-- PROFILE + SIGNATURE -->
    <div class="row justify-content-center g-4 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-header">Profile Picture</div>
                <div class="card-body">
                    <?php if(!empty($user['profile_pic'])): ?>
                        <img src="../<?= $user['profile_pic']; ?>" class="id-card-img">
                    <?php else: ?>
                        <p class="text-muted mb-0">No profile picture</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-header">Signature</div>
                <div class="card-body">
                    <?php if(!empty($user['signature'])): ?>
                        <img src="../<?= $user['signature']; ?>" class="id-card-img">
                    <?php else: ?>
                        <p class="text-muted mb-0">No signature</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- INFO -->
    <div class="alert alert-info text-center shadow-sm">
        Use the navigation bar above to manage:
        <strong>Users</strong>,
        <strong>Subjects</strong>,
        <strong>Faculty Assignments</strong>,
        <strong>Prerequisites</strong>,
        and <strong>Enrollment Overrides</strong>.
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
