<?php
include '../config/db.php';
if(session_status() == PHP_SESSION_NONE) session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

/* ADD SUBJECT */
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $credits = intval($_POST['credits']);
    mysqli_query($conn,"INSERT INTO subjects(name,credits) VALUES('$name',$credits)");
}

/* DELETE SUBJECT */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM subjects WHERE id=$id");
    header("Location: subjects.php");
    exit;
}

/* EDIT SUBJECT */
if(isset($_POST['edit'])){
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $credits = intval($_POST['credits']);
    mysqli_query($conn,"UPDATE subjects SET name='$name', credits=$credits WHERE id=$id");
    header("Location: subjects.php");
    exit;
}

$subjects = mysqli_query($conn,"SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Subjects</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php">Enrollment System</a>
    <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="subjects.php">Subjects</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="assign_faculty.php">Assign Faculty</a></li>
        <li class="nav-item"><a class="nav-link" href="prerequisites.php">Prerequisites</a></li>
    </ul>
    <span class="text-white me-3"><?= $_SESSION['user']['name'] ?></span>
    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
</div>
</nav>

<div class="container mt-4">

<h3>Add Subject</h3>

<div class="card mb-4">
<div class="card-body">
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <label>Subject Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label>Credits</label>
        <input type="number" name="credits" class="form-control" required>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-success" name="add">Add</button>
    </div>
</form>
</div>
</div>

<h3>Subject List</h3>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>Subject</th>
    <th>Credits</th>
    <th width="180">Actions</th>
</tr>
</thead>
<tbody>

<?php while($s = mysqli_fetch_assoc($subjects)): ?>
<tr>
    <td><?= $s['name'] ?></td>
    <td><?= $s['credits'] ?></td>
    <td>
        <!-- EDIT BUTTON -->
        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                data-bs-target="#edit<?= $s['id'] ?>">Edit</button>

        <!-- DELETE BUTTON -->
        <a href="subjects.php?delete=<?= $s['id'] ?>"
           onclick="return confirm('Delete this subject?')"
           class="btn btn-danger btn-sm">Delete</a>
    </td>
</tr>

<!-- EDIT MODAL -->
<div class="modal fade" id="edit<?= $s['id'] ?>">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">

<div class="modal-header">
    <h5 class="modal-title">Edit Subject</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <input type="hidden" name="id" value="<?= $s['id'] ?>">

    <div class="mb-3">
        <label>Subject Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= $s['name'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Credits</label>
        <input type="number" name="credits" class="form-control"
               value="<?= $s['credits'] ?>" required>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button class="btn btn-primary" name="edit">Save Changes</button>
</div>

</form>
</div>
</div>
</div>

<?php endwhile; ?>

</tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
