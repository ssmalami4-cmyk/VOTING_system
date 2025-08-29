<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    try {
        $query = "INSERT INTO offices (name, description) VALUES (:name, :description)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
        ]);

        // Redirect to the admin dashboard after successful addition
        header("Location: admin_dashboard.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error adding office: " . $e->getMessage();
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Add New Office</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="form-group">
            <label for="name">Office Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Office</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
