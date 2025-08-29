<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch existing data
    $query = "SELECT * FROM elections WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "UPDATE elections SET name = :name, start_date = :start_date, end_date = :end_date WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':name' => $name,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':id' => $id
    ]);

    header("Location: view_elections.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Edit Election</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Election Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($election['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date and Time</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($election['start_date']) ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date and Time</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($election['end_date']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Election</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
