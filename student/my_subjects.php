<?php
include '../auth_check.php';
include '../config/db.php';

$student_id = $user['id'];

$enrollments = mysqli_query($conn, "
    SELECT s.name AS subject_name
    FROM enrollments e
    JOIN subjects s ON e.subject_id = s.id
    WHERE e.student_id='$student_id'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Subjects</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
          <a class="nav-link active" href="my_subjects.php">My Subjects</a>
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
    <h2 class="mb-4 text-center">My Subjects</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Enrolled Subjects</div>
                <div class="card-body">
                    <?php if(mysqli_num_rows($enrollments) > 0): ?>
                        <ul class="list-group">
                            <?php while($row = mysqli_fetch_assoc($enrollments)): ?>
                                <li class="list-group-item"><?= htmlspecialchars($row['subject_name']); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted text-center">No subjects enrolled yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
