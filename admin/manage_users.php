<?php
include '../auth_check.php';
include '../config/db.php';
if($user['role'] != 'admin'){ header("Location: ../auth/login.php"); exit(); }

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role, name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- ADMIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php">Enrollment System</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
            <li class="nav-item"><a class="nav-link active" href="manage_users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="assign_faculty.php">Assign Faculty</a></li>
            <li class="nav-item"><a class="nav-link" href="prerequisites.php">Prerequisites</a></li>
        </ul>
        <span class="text-white me-3"><?= $_SESSION['user']['name'] ?></span>
        <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</div>
</nav>
<div class="container py-4">
    <h2>Manage Users</h2>
    <a href="add_user.php" class="btn btn-success mb-3">Add User</a>
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="manage_users.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this user?');" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Handle deletion
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header("Location: manage_users.php");
    exit();
}
?>
