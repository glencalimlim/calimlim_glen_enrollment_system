<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

// Fetch subjects assigned to this faculty via faculty_assignments
$subjects = mysqli_query($conn, "
    SELECT s.id, s.name 
    FROM subjects s
    JOIN faculty_assignments fa ON fa.subject_id = s.id
    WHERE fa.faculty_id = '{$user['id']}'
    ORDER BY s.name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.id-card-img { width: 100px; height: 70px; object-fit: cover; border: 1px solid #ccc; border-radius: 5px; }
.card-header { background-color: #198754; color: white; font-weight: 500; }
</style>
</head>
<body>
    <!-- Navbar --> 
     <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4"> 
        <div class="container"> 
            <a class="navbar-brand" href="dashboard.php">Faculty Dashboard</a>
             <button class="navbar-toggler" type="button" 
             data-bs-toggle="collapse" data-bs-target="#facultyNavbar" 
             aria-controls="facultyNavbar" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span> 
            </button> 
            <div class="collapse navbar-collapse justify-content-end" id="facultyNavbar"> 
                <ul class="navbar-nav">
                     <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
             <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a>
            </li> 
            </ul>
             </div> 
            </div>
             </nav>

<div class="container py-4">
    <h2 class="mb-4 text-center">Welcome, <?= htmlspecialchars($user['name']); ?></h2>

    <!-- Faculty Profile & Signature -->
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

    <!-- Subjects & Enrolled Students -->
    <?php if(mysqli_num_rows($subjects) == 0): ?>
        <div class="alert alert-warning">No subjects assigned.</div>
    <?php else: ?>
        <?php while($sub = mysqli_fetch_assoc($subjects)): ?>
            <div class="card shadow mb-4">
                <div class="card-header"><?= htmlspecialchars($sub['name']) ?></div>
                <div class="card-body">

                    <?php
                   
                    // Fetch students enrolled in this subject with current grade
$students = mysqli_query($conn, "
    SELECT u.id, u.name, u.profile_pic, u.signature, e.grade
    FROM enrollments e
    JOIN users u ON u.id = e.student_id
    WHERE e.subject_id='{$sub['id']}'
    ORDER BY u.name ASC
");

                    ?>

                    <?php if(mysqli_num_rows($students) == 0): ?>
                        <div class="alert alert-warning">No students enrolled in this subject.</div>
                    <?php else: ?>
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Profile Picture</th>
                                    <th>Signature</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($stu = mysqli_fetch_assoc($students)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($stu['name']) ?></td>

                                    <td>
                                        <?php if(!empty($stu['profile_pic'])): ?>
                                            <img src="../<?= $stu['profile_pic'] ?>" class="id-card-img rounded border">
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($stu['signature'])): ?>
                                            <img src="../<?= $stu['signature'] ?>" class="id-card-img rounded border">
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="post" action="grades.php" class="d-flex gap-1">
                                            <input type="hidden" name="student_id" value="<?= $stu['id'] ?>">
                                            <input type="hidden" name="subject_id" value="<?= $sub['id'] ?>">
                                            <select name="grade" class="form-select form-select-sm" required>
                                                <option value="">Select Grade</option>
                                                <?php for($i=1;$i<=5;$i++): ?>
                                                    <option value="<?= $i ?>" <?= ($stu['grade']==$i)?'selected':'' ?>><?= $i ?></option>

                                                <?php endfor; ?>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <?= $stu['grade'] ? 'Update' : 'Submit' ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
