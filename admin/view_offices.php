<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch offices from the database
$query = "SELECT * FROM offices";
$stmt = $pdo->prepare($query);
$stmt->execute();
$offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">View Offices</h2>
    <a href="add_office.php" class="btn btn-primary mb-3">Add New Office</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Office Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offices as $office): ?>
            <tr>
                <td><?= htmlspecialchars($office['id']) ?></td>
                <td><?= htmlspecialchars($office['name']) ?></td>
                <td><?= htmlspecialchars($office['description'] ?? 'N/A') ?></td>
                <td>
                    <a href="edit_office.php?id=<?= $office['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_office.php?id=<?= $office['id'] ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
