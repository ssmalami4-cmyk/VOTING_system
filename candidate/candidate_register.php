<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $office = $_POST['office'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $photo = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];
    
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($photo);
    move_uploaded_file($photo_tmp, $target_file);

    $stmt = $pdo->prepare("INSERT INTO Candidates (name, email, office, faculty, department, level, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $office, $faculty, $department, $level, $photo]);

    $_SESSION['message'] = "Registration successful. Your profile will be reviewed.";
    header("Location: candidate_register.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Candidate Registration</h2>
    <?php if (isset($_SESSION['message'])) { echo "<div class='alert alert-success'>{$_SESSION['message']}</div>"; unset($_SESSION['message']); } ?>
    <form method="post" enctype="multipart/form-data" class="card p-4 shadow">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="office">Office Contesting For</label>
            <input type="text" class="form-control" id="office" name="office" required>
        </div>
        <div class="form-group">
            <label for="faculty">Faculty</label>
            <input type="text" class="form-control" id="faculty" name="faculty" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" class="form-control" id="department" name="department" required>
        </div>
        <div class="form-group">
            <label for="level">Level</label>
            <input type="text" class="form-control" id="level" name="level" required>
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
