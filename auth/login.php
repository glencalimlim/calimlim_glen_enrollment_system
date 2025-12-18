<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
<div class="row justify-content-center mt-5">
<div class="col-md-4">

<div class="card shadow">
<div class="card-header bg-primary text-white text-center">
<h4>Login</h4>
</div>

<div class="card-body">
<form method="POST" action="login_action.php">
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn btn-primary w-100">Login</button>
</form>
</div>

<div class="card-footer text-center">
<a href="register.php">Create Account</a>
</div>

</div>
</div>
</div>
</div>

</body>
</html>
