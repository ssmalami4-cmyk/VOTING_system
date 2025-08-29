<?php
session_start();
include('../includes/db.php'); // Ensure this path is correct

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch voter data and elections data
$voter_id = $_GET['id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM voters WHERE id = ?");
    $stmt->execute([$voter_id]);
    $voter = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voter) {
        echo "Voter not found.";
        exit();
    }

    // Fetch elections for dropdown
    $electionStmt = $pdo->prepare("SELECT id, name FROM elections");
    $electionStmt->execute();
    $elections = $electionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update voter data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $election_id = $_POST['election_id'];

    try {
        $stmt = $pdo->prepare("UPDATE voters SET name = ?, email = ?, student_id = ?, faculty = ?, department = ?, level = ?, election_id = ? WHERE id = ?");
        $stmt->execute([$name, $email, $student_id, $faculty, $department, $level, $election_id, $voter_id]);
        header("Location: view_voters.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating voter: " . $e->getMessage());
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2>Edit Voter</h2>
    <form method="POST" action="">
        <!-- Include form fields with voter data -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($voter['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($voter['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($voter['student_id']) ?>" required>
        </div>
        <div class="form-group">
            <label for="faculty">Faculty</label>
            <input type="text" name="faculty" class="form-control" value="<?= htmlspecialchars($voter['faculty']) ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($voter['department']) ?>" required>
        </div>
        <div class="form-group">
            <label for="level">Level</label>
            <input type="text" name="level" class="form-control" value="<?= htmlspecialchars($voter['level']) ?>" required>
        </div>
        <div class="form-group">
            <label for="election_id">Election</label>
            <select name="election_id" class="form-control" required>
                <option value="">Select Election</option>
                <?php foreach ($elections as $election): ?>
                    <option value="<?= htmlspecialchars($election['id']) ?>" <?= $election['id'] == $voter['election_id'] ? 'selected' : '' ?>><?= htmlspecialchars($election['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Voter</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
