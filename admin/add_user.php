<?php
include '../auth_check.php';
include '../config/db.php';
if($user['role'] != 'admin'){ header("Location: ../auth/login.php"); exit(); }

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pwd = md5($_POST['password']);
    $role = $_POST['role'];
    mysqli_query($conn, "INSERT INTO users(name,email,password,role) VALUES('$name','$email','$pwd','$role')");
    header("Location: manage_users.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="container py-4">
    <h2>Add User</h2>
    <form method="POST">
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label>Role</label>
            <select name="role" class="form-select">
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button class="btn btn-success" type="submit" name="submit">Add User</button>
    </form>
</div>
</body>
</html>
