<?php
include '../auth_check.php';
include '../config/db.php'; 

$student_id = $user['id'];
$success = $error = "";

// Handle enrollment
if(isset($_POST['enroll'])){
    $subject_id = $_POST['subject'] ?? null;

    if(!$subject_id){
        $error = "Please select a subject.";
    } else {
        $check_enroll = mysqli_query($conn, "
            SELECT * FROM enrollments 
            WHERE student_id='$student_id' AND subject_id='$subject_id'
        ");
        if(mysqli_num_rows($check_enroll) > 0){
            $error = "Already enrolled!";
        } else {
            $can_enroll = true;
            $pre = mysqli_query($conn, "
                SELECT prerequisite_id FROM prerequisites WHERE subject_id='$subject_id'
            ");
            if(mysqli_num_rows($pre) > 0){
                $pre_id = mysqli_fetch_assoc($pre)['prerequisite_id'];

                // Get prerequisite grade from enrollments table
                $passed = mysqli_query($conn, "
                    SELECT grade FROM enrollments
                    WHERE student_id='$student_id'
                    AND subject_id='$pre_id'
                ");

                if(mysqli_num_rows($passed) > 0){
                    $pre_grade = mysqli_fetch_assoc($passed)['grade'];
                    if(!in_array($pre_grade, [1,2,3])){
                        $can_enroll = false;
                        $error = "You must pass the prerequisite before enrolling.";
                    }
                } else {
                    $can_enroll = false;
                    $error = "Prerequisite not graded yet.";
                }
            }

            if($can_enroll){
                mysqli_query($conn, "
                    INSERT INTO enrollments(student_id, subject_id) 
                    VALUES('$student_id','$subject_id')
                ");
                $success = "Enrolled successfully!";
            }
        }
    }
}

// Fetch all subjects
$subjects_result = mysqli_query($conn, "SELECT * FROM subjects ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Enroll Subjects</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Student Dashboard</a>
    <div class="collapse navbar-collapse justify-content-end" id="studentNavbar">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="enroll.php">Enroll Subjects</a></li>
        <li class="nav-item"><a class="nav-link" href="my_subjects.php">My Subjects</a></li>
        <li class="nav-item"><a class="nav-link" href="grades.php">Grades</a></li>
        <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4 text-center">Enroll Subjects</h2>

    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Subject</th>
            <th>Prerequisite</th>
            <th>Prerequisite Grade</th>
            <th>Subject Grade</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($subject = mysqli_fetch_assoc($subjects_result)):
    $pre_name = "None";
    $pre_grade_display = "-";
    $subject_grade_display = "-";
    $can_enroll = true;

    // Check prerequisite
    $pre_check = mysqli_query($conn, "SELECT prerequisite_id FROM prerequisites WHERE subject_id='{$subject['id']}'");
    if(mysqli_num_rows($pre_check) > 0){
        $pre_row = mysqli_fetch_assoc($pre_check);
        $pre_id = $pre_row['prerequisite_id'];

        // Get prerequisite name
        $pre_sub = mysqli_query($conn, "SELECT name FROM subjects WHERE id='$pre_id'");
        if(mysqli_num_rows($pre_sub) > 0){
            $pre_name = mysqli_fetch_assoc($pre_sub)['name'];
        }

        // Get prerequisite grade
        $pre_grade_q = mysqli_query($conn, "
            SELECT grade FROM enrollments 
            WHERE student_id='$student_id' AND subject_id='$pre_id'
        ");
        if(mysqli_num_rows($pre_grade_q) > 0){
            $pre_grade_display = mysqli_fetch_assoc($pre_grade_q)['grade'];
            if(!in_array($pre_grade_display, [1,2,3])){
                $can_enroll = false; // prerequisite not passed
            }
        } else {
            $pre_grade_display = "Not graded";
            $can_enroll = false; // prerequisite not graded yet
        }
    }

    // Check current subject grade
    $enrolled_check = mysqli_query($conn, "
        SELECT grade FROM enrollments WHERE student_id='$student_id' AND subject_id='{$subject['id']}'
    ");
    if(mysqli_num_rows($enrolled_check) > 0){
        $subject_grade_display = mysqli_fetch_assoc($enrolled_check)['grade'] ?? "Not graded";

        if(in_array($subject_grade_display, [1,2,3])){
            // Already passed → cannot enroll
            $can_enroll = false;
        } else {
            // Failed (4,5) → can enroll again
            $can_enroll = true;
        }
    }
?>
<tr>
    <td><?= htmlspecialchars($subject['name']); ?></td>
    <td><?= htmlspecialchars($pre_name); ?></td>
    <td><?= htmlspecialchars($pre_grade_display); ?></td>
    <td><?= htmlspecialchars($subject_grade_display); ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="subject" value="<?= $subject['id']; ?>">
            <button type="submit" name="enroll" class="btn btn-primary btn-sm" <?= !$can_enroll ? 'disabled' : '' ?>>Enroll</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>

    </tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
