<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM Voters WHERE student_id = ? AND email = ?");
    $stmt->execute([$student_id, $email]);
    $voter = $stmt->fetch();

    if ($voter) {
        $_SESSION['voter_id'] = $voter['id'];
        $_SESSION['user_role'] = 'voter'; 
        header("Location: vote.php");
        exit();
    } else {
        $error = "Invalid student ID or email.";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Voter Login</h2>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="post" action="voter_login.php" class="card p-4 shadow">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
