<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all elections for the dropdown
try {
    $query = "SELECT id, name FROM elections";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $election_id = $_POST['election_id'];
    $file = $_FILES['voters_csv']['tmp_name'];

    if ($election_id) {
        if ($file) {
            if (($handle = fopen($file, "r")) !== FALSE) {
                $firstRow = true; // To skip header row if present
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue; // Skip header row
                    }

                    try {
                        $stmt = $pdo->prepare("INSERT INTO voters (name, email, student_id, faculty, department, level, election_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $election_id]);
                    } catch (PDOException $e) {
                        echo "Error inserting data: " . $e->getMessage();
                    }
                }
                fclose($handle);
                $success = "Voters uploaded successfully.";
            } else {
                $error = "Error opening the file.";
            }
        } else {
            $error = "No file selected.";
        }
    } else {
        $error = "Please select an election.";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Upload Voters</h2>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="election_id">Select Election</label>
            <select id="election_id" name="election_id" class="form-control" required>
                <option value="">Select Election</option>
                <?php foreach ($elections as $election): ?>
                    <option value="<?= htmlspecialchars($election['id']) ?>"><?= htmlspecialchars($election['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="voters_csv">Upload Voters CSV</label>
            <input type="file" class="form-control-file" id="voters_csv" name="voters_csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Voters</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
