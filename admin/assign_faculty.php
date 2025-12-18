<?php
include '../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();

/* ADMIN ONLY */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ASSIGN FACULTY */
if (isset($_POST['assign'])) {
    $faculty_id = intval($_POST['faculty_id']);
    $subject_id = intval($_POST['subject_id']);

    // prevent duplicate assignment
    $check = mysqli_query($conn, "
        SELECT * FROM faculty_assignments 
        WHERE faculty_id=$faculty_id AND subject_id=$subject_id
    ");

    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "
            INSERT INTO faculty_assignments (faculty_id, subject_id)
            VALUES ($faculty_id, $subject_id)
        ");
        $success = "Faculty assigned successfully!";
    } else {
        $error = "This faculty is already assigned to that subject.";
    }
}

/* DELETE ASSIGNMENT */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM faculty_assignments WHERE id=$id");
}

/* FETCH FACULTY */
$faculty = mysqli_query($conn, "SELECT id, name FROM users WHERE role='faculty'");

/* FETCH SUBJECTS */
$subjects = mysqli_query($conn, "SELECT id, name FROM subjects");

/* FETCH ASSIGNMENTS */
$assignments = mysqli_query($conn, "
    SELECT fa.id, u.name AS faculty, s.name AS subject
    FROM faculty_assignments fa
    JOIN users u ON fa.faculty_id = u.id
    JOIN subjects s ON fa.subject_id = s.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Faculty</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
 <!-- ADMIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php">Enrollment System</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link active" href="assign_faculty.php">Assign Faculty</a></li>
            <li class="nav-item"><a class="nav-link" href="prerequisites.php">Prerequisites</a></li>
        </ul>
        <span class="text-white me-3"><?= $_SESSION['user']['name'] ?></span>
        <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">

    <h3 class="mb-4">Assign Faculty to Subjects</h3>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- ASSIGN FORM -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Faculty</label>
                    <select name="faculty_id" class="form-select" required>
                        <option value="">Select Faculty</option>
                        <?php while ($f = mysqli_fetch_assoc($faculty)): ?>
                            <option value="<?php echo $f['id']; ?>">
                                <?php echo $f['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Select Subject</option>
                        <?php while ($s = mysqli_fetch_assoc($subjects)): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo $s['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" name="assign" class="btn btn-primary mt-4">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ASSIGNMENT LIST -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            Current Assignments
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Faculty</th>
                        <th>Subject</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($a = mysqli_fetch_assoc($assignments)): ?>
                    <tr>
                        <td><?php echo $a['faculty']; ?></td>
                        <td><?php echo $a['subject']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $a['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Remove this assignment?')">
                               Remove
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
