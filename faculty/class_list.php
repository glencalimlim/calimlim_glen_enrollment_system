<?php
include '../auth_check.php';
include '../config/db.php';

if($user['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_GET['subject_id'])){
    die("Subject not specified.");
}

$subject_id = intval($_GET['subject_id']);
$faculty_id = $user['id'];

/* VERIFY SUBJECT IS ASSIGNED TO THIS FACULTY */
$subjectQuery = mysqli_query($conn, "
    SELECT s.id, s.name 
    FROM subjects s
    JOIN faculty_assignments fa ON s.id = fa.subject_id
    WHERE s.id = $subject_id AND fa.faculty_id = $faculty_id
");

if(mysqli_num_rows($subjectQuery) == 0){
    die("Subject not assigned to you.");
}

$subject = mysqli_fetch_assoc($subjectQuery);

/* FETCH ENROLLED STUDENTS */
$students = mysqli_query($conn, "
    SELECT 
        e.id AS enroll_id,
        u.name,
        u.email,
        u.profile_pic,
        u.signature,
        e.grade
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    WHERE e.subject_id = $subject_id
    ORDER BY u.name
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Class List - <?php echo htmlspecialchars($subject['name']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.id-card-img {
    width: 70px;
    height: 50px;
    object-fit: cover;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
</head>

<body>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Class List: <?php echo htmlspecialchars($subject['name']); ?></h3>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    <?php if(mysqli_num_rows($students) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Signature</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
            <?php while($s = mysqli_fetch_assoc($students)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['email']); ?></td>

                    <td>
                        <?php if(!empty($s['profile_pic'])): ?>
                            <img src="../<?php echo $s['profile_pic']; ?>" class="id-card-img">
                        <?php else: ?>
                            <span class="text-muted">None</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if(!empty($s['signature'])): ?>
                            <img src="../<?php echo $s['signature']; ?>" class="id-card-img">
                        <?php else: ?>
                            <span class="text-muted">None</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php echo $s['grade'] ?? '<span class="text-muted">Not graded</span>'; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            No students enrolled yet.
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
