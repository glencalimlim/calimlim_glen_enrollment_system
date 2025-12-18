<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit;
}

// Fetch subjects student is enrolled in with grades
$enrollments = mysqli_query($conn, "
    SELECT s.name AS subject, e.grade
    FROM enrollments e
    JOIN subjects s ON s.id = e.subject_id
    WHERE e.student_id = '{$user['id']}'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Grades</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
          <a class="nav-link active" href="grades.php">Grades</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../auth/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
    <h2 class="mb-4 text-center">My Grades</h2>

    <?php if(mysqli_num_rows($enrollments) == 0): ?>
        <div class="alert alert-warning">You are not enrolled in any subjects yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($enrollments)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= $row['grade'] !== null ? htmlspecialchars($row['grade']) : 'Not graded yet' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
