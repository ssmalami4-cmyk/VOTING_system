<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch voters and elections data from the database
try {
    $stmt = $pdo->prepare("SELECT v.*, e.name as election_name FROM voters v LEFT JOIN elections e ON v.election_id = e.id");
    $stmt->execute();
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-center">View Voters</h2>
        <a href="add_voter.php" class="btn btn-primary">Add New Voter</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Faculty</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Election</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($voters) > 0): ?>
                    <?php foreach ($voters as $voter): ?>
                        <tr>
                            <td><?= htmlspecialchars($voter['id']) ?></td>
                            <td><?= htmlspecialchars($voter['name']) ?></td>
                            <td><?= htmlspecialchars($voter['email']) ?></td>
                            <td><?= htmlspecialchars($voter['student_id']) ?></td>
                            <td><?= htmlspecialchars($voter['faculty']) ?></td>
                            <td><?= htmlspecialchars($voter['department']) ?></td>
                            <td><?= htmlspecialchars($voter['level']) ?></td>
                            <td><?= htmlspecialchars($voter['election_name']) ?></td>
                            <td>
                                <a href="edit_voter.php?id=<?= $voter['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_voter.php?id=<?= $voter['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No voters found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <form action="print_voters.php" method="GET" class="form-inline">
                <label for="election_id" class="mr-2">Print Voters for Election: </label>
                <select name="election_id" id="election_id" class="form-control mr-2">
                    <option value="">All Elections</option>
                    <?php
                    try {
                        $electionStmt = $pdo->prepare("SELECT id, name FROM elections");
                        $electionStmt->execute();
                        $elections = $electionStmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($elections as $election) {
                            echo "<option value='".htmlspecialchars($election['id'])."'>".htmlspecialchars($election['name'])."</option>";
                        }
                    } catch (PDOException $e) {
                        die("Error fetching elections: " . $e->getMessage());
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-secondary">Print</button>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
