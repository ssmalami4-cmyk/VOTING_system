<?php
session_start();
include('../includes/db.php'); // Ensure this path is correct

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch voters from the database along with the election they registered for
try {
    $query = "
        SELECT v.*, e.name AS election_name 
        FROM voters v
        LEFT JOIN elections e ON v.election_id = e.id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all elections for the print selection
    $electionQuery = "SELECT id, name FROM elections";
    $electionStmt = $pdo->prepare($electionQuery);
    $electionStmt->execute();
    $elections = $electionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">View Voters</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="add_voter.php" class="btn btn-primary">Add New Voter</a>
        <div class="d-flex align-items-center">
            <select id="print_election" class="form-control mr-2">
                <option value="all">Print All Voters</option>
                <?php foreach ($elections as $election): ?>
                    <option value="<?= htmlspecialchars($election['id']) ?>"><?= htmlspecialchars($election['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button id="print_voters_btn" class="btn btn-secondary">Print Voters</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Faculty</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Registered Election</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('print_voters_btn').addEventListener('click', function() {
        var electionId = document.getElementById('print_election').value;
        var url = 'print_voters.php?election_id=' + electionId;
        window.open(url, '_blank');
    });
</script>

<?php include('../includes/footer.php'); ?>
