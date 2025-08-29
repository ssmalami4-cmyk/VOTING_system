<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "INSERT INTO elections (name, start_date, end_date) VALUES (:name, :start_date, :end_date)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':name' => $name,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    header("Location: view_elections.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Create Election</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Election Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date and Time</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date and Time</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Election</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
