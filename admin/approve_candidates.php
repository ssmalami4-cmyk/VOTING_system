<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['approve'])) {
    $candidate_id = $_POST['candidate_id'];
    $stmt = $pdo->prepare("UPDATE Candidates SET approved = 1 WHERE id = ?");
    $stmt->execute([$candidate_id]);
}

$stmt = $pdo->query("SELECT * FROM Candidates WHERE approved = 0");
$candidates = $stmt->fetchAll();
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Approve Candidates</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Office</th>
                <th>Faculty</th>
                <th>Department</th>
                <th>Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($candidates as $candidate): ?>
            <tr>
                <td><?= $candidate['name'] ?></td>
                <td><?= $candidate['email'] ?></td>
                <td><?= $candidate['office'] ?></td>
                <td><?= $candidate['faculty'] ?></td>
                <td><?= $candidate['department'] ?></td>
                <td><?= $candidate['level'] ?></td>
                <td>
                    <form method="post" action="approve_candidates.php">
                        <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
                        <button type="submit" name="approve" class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
