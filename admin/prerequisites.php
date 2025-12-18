<?php
include('../config/db.php');
if(session_status() == PHP_SESSION_NONE) session_start();

/* ADMIN ONLY */
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

/* ADD PREREQUISITE */
if(isset($_POST['add_prereq'])){
    $subject_id = intval($_POST['subject_id']);
    $prereq_id  = intval($_POST['prereq_id']);

    if($subject_id == $prereq_id){
        $error = "A subject cannot be its own prerequisite.";
    } else {
        // prevent duplicate
        $check = mysqli_query($conn,"SELECT * FROM prerequisites 
            WHERE subject_id=$subject_id AND prerequisite_id=$prereq_id");

        if(mysqli_num_rows($check) > 0){
            $error = "Prerequisite already exists.";
        } else {
            mysqli_query($conn,"INSERT INTO prerequisites(subject_id,prerequisite_id)
                VALUES($subject_id,$prereq_id)");
            $success = "Prerequisite added successfully!";
        }
    }
}

/* DELETE PREREQUISITE */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM prerequisites WHERE id=$id");
}

/* FETCH SUBJECTS */
$subjects = mysqli_query($conn,"SELECT * FROM subjects");

/* FETCH EXISTING PREREQUISITES */
$prereqs = mysqli_query($conn,"
    SELECT p.id, s1.name AS subject, s2.name AS prereq
    FROM prerequisites p
    JOIN subjects s1 ON p.subject_id = s1.id
    JOIN subjects s2 ON p.prerequisite_id = s2.id
");

?>
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
<!-- ADMIN NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php">Enrollment System</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="subjects.php">Subjects</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="assign_faculty.php">Assign Faculty</a></li>
            <li class="nav-item"><a class="nav-link active" href="prerequisites.php">Prerequisites</a></li>
        </ul>
        <span class="text-white me-3"><?= $_SESSION['user']['name'] ?></span>
        <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</div>
</nav>
<h2 class="mb-4">Manage Prerequisites</h2>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if(isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- ADD PREREQUISITE -->
<div class="card mb-4">
<div class="card-body">
<form method="POST" class="row g-3">
    <div class="col-md-5">
        <label>Subject</label>
        <select name="subject_id" class="form-select" required>
            <option value="">Select Subject</option>
            <?php while($s=mysqli_fetch_assoc($subjects)): ?>
            <option value="<?php echo $s['id']; ?>">
                <?php echo $s['name']; ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-5">
        <label>Prerequisite</label>
        <select name="prereq_id" class="form-select" required>
            <option value="">Select Prerequisite</option>
            <?php
            mysqli_data_seek($subjects,0);
            while($p=mysqli_fetch_assoc($subjects)):
            ?>
            <option value="<?php echo $p['id']; ?>">
                <?php echo $p['name']; ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" name="add_prereq" class="btn btn-success">
            Add
        </button>
    </div>
</form>
</div>
</div>

<!-- LIST PREREQUISITES -->
<table class="table table-bordered table-striped">
<thead class="table-light">
<tr>
    <th>Subject</th>
    <th>Prerequisite</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while($r=mysqli_fetch_assoc($prereqs)): ?>
<tr>
    <td><?php echo $r['subject']; ?></td>
    <td><?php echo $r['prereq']; ?></td>
    <td>
        <a href="?delete=<?php echo $r['id']; ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this prerequisite?')">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
