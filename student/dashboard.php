<?php
include '../auth_check.php';
include '../config/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .id-card-img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Student Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNavbar" aria-controls="studentNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="studentNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="enroll.php">Enroll Subjects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my_subjects.php">My Subjects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="grades.php">Grades</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../auth/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4 text-center">Welcome, <?= htmlspecialchars($user['name']); ?></h2>

    <!-- Profile & Signature ID Card Style -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 text-center">
            <div class="card shadow-sm">
                <div class="card-header">Profile Picture</div>
                <div class="card-body">
                    <?php if(!empty($user['profile_pic'])): ?>
                        <img src="../<?= $user['profile_pic']; ?>" class="id-card-img" alt="Profile Picture">
                    <?php else: ?>
                        <p class="text-muted">No profile picture</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="card shadow-sm">
                <div class="card-header">Signature</div>
                <div class="card-body">
                    <?php if(!empty($user['signature'])): ?>
                        <img src="../<?= $user['signature']; ?>" class="id-card-img" alt="Signature">
                    <?php else: ?>
                        <p class="text-muted">No signature</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
