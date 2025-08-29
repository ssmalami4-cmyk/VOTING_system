<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch elections
$query = "SELECT * FROM elections";
$stmt = $pdo->query($query);
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">View Elections</h2>
    <a href="create_election.php" class="btn btn-primary mb-3">Create New Election</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($elections as $election): ?>
            <tr>
                <td><?= htmlspecialchars($election['id']) ?></td>
                <td><?= htmlspecialchars($election['name']) ?></td>
                <td><?= htmlspecialchars($election['start_date']) ?></td>
                <td><?= htmlspecialchars($election['end_date']) ?></td>
                <td>
                    <a href="edit_election.php?id=<?= $election['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_election.php?id=<?= $election['id'] ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
