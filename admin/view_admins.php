<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admins
$query = "SELECT * FROM admins";
$stmt = $pdo->prepare($query);

try {
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching admins: " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">View Admins</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?= htmlspecialchars($admin['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($admin['email'] ?? 'N/A') ?></td>
                <td>
                    <a href="edit_admin.php?id=<?= $admin['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_admin.php?id=<?= $admin['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
