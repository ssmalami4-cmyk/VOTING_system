<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

// Fetch office details
try {
    $stmt = $pdo->prepare("SELECT * FROM offices WHERE id = ?");
    $stmt->execute([$id]);
    $office = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$office) {
        header("Location: manage_offices.php");
        exit();
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    try {
        $stmt = $pdo->prepare("UPDATE offices SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        $success = "Office updated successfully.";
    } catch (PDOException $e) {
        $error = "Error updating office: " . $e->getMessage();
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Edit Office</h2>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Office Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($office['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Office</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
