<?php
include '../auth_check.php';
include '../config/db.php';
if($user['role'] != 'admin'){ header("Location: ../auth/login.php"); exit(); }

$id = intval($_GET['id']);
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password_sql = $_POST['password'] ? ", password='".md5($_POST['password'])."'" : "";
    mysqli_query($conn, "UPDATE users SET name='$name', email='$email', role='$role' $password_sql WHERE id='$id'");
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
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
    <h2>Edit User</h2>
    <form method="POST">
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user_data['name']) ?>" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['email']) ?>" required></div>
        <div class="mb-3"><label>Password (leave blank to keep current)</label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3"><label>Role</label>
            <select name="role" class="form-select">
                <option value="student" <?= $user_data['role']=='student'?'selected':'' ?>>Student</option>
                <option value="faculty" <?= $user_data['role']=='faculty'?'selected':'' ?>>Faculty</option>
                <option value="admin" <?= $user_data['role']=='admin'?'selected':'' ?>>Admin</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Update User</button>
    </form>
</div>
</body>
</html>
