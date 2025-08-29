<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Ensure the correct database connection variable is used
if (!isset($pdo)) {
    die("Database connection failed.");
}

// Fetch candidates and associated elections data
try {
    $stmt = $pdo->prepare("SELECT c.*, e.name as election_name FROM candidates c LEFT JOIN elections e ON c.election_id = e.id");
    $stmt->execute();
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">View Candidates</h2>
    <a href="add_candidate.php" class="btn btn-primary mb-3">Add New Candidate</a>
    <table class="table table-bordered table-hover table-responsive">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Office</th>
                <th>Faculty</th>
                <th>Department</th>
                <th>Level</th>
                <th>Election</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($candidates) > 0): ?>
                <?php foreach ($candidates as $candidate): ?>
                    <tr>
                        <td><?= htmlspecialchars($candidate['id']) ?></td>
                        <td><?= htmlspecialchars($candidate['name']) ?></td>
                        <td><?= htmlspecialchars($candidate['email']) ?></td>
                        <td><?= htmlspecialchars($candidate['office']) ?></td>
                        <td><?= htmlspecialchars($candidate['faculty']) ?></td>
                        <td><?= htmlspecialchars($candidate['department']) ?></td>
                        <td><?= htmlspecialchars($candidate['level']) ?></td>
                        <td><?= htmlspecialchars($candidate['election_name']) ?></td>
                        <td><img src="../uploads/<?= htmlspecialchars($candidate['photo']) ?>" alt="Photo" width="50" height="50"></td>
                        <td>
                            <a href="edit_candidate.php?id=<?= $candidate['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_candidate.php?id=<?= $candidate['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No candidates found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
