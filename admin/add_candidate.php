<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $office_id = $_POST['office_id'] ?? null;
    $faculty = $_POST['faculty'] ?? '';
    $department = $_POST['department'] ?? '';
    $level = $_POST['level'] ?? '';
    $election_id = $_POST['election_id'] ?? '';

    // Handle file upload 
    $photo = $_FILES['photo']['name'] ?? '';
    $photo_tmp = $_FILES['photo']['tmp_name'] ?? '';
    $photo_path = '../uploads/' . basename($photo);

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $error = "Error uploading photo.";
    } elseif (empty($name) || empty($email) || empty($office_id) || empty($election_id) || empty($faculty) || empty($department) || empty($level)) {
        $error = "All fields are required.";
    } else {
        // Check if candidate is already registered in the same election
        try {
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM candidates WHERE email = ? AND election_id = ?");
            $check_stmt->execute([$email, $election_id]);
            $exists = $check_stmt->fetchColumn();

            if ($exists > 0) {
                $error = "This candidate is already registered for this election.";
            } else {
                try {
                    // 1. Fetch the office name from the offices table
                    $fetch_office_stmt = $pdo->prepare("SELECT name FROM offices WHERE id = ?");
                    $fetch_office_stmt->execute([$office_id]);
                    $office_name = $fetch_office_stmt->fetchColumn();

                    if (!$office_name) { 
                        throw new Exception("Invalid office selected."); 
                    }

                    // Move uploaded photo
                    if (!move_uploaded_file($photo_tmp, $photo_path)) {
                        $error = "Error moving uploaded file.";
                        throw new Exception($error);
                    }

                    // 2. Prepare SQL statement, including the fetched office name
                    $stmt = $pdo->prepare("INSERT INTO candidates (name, email, office_id, office, faculty, department, level, photo, approved, election_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");
                    $stmt->execute([$name, $email, $office_id, $office_name, $faculty, $department, $level, $photo, $election_id]);

                    $success = "Candidate added successfully.";
                    // Redirect or handle success
                } catch (PDOException $e) {
                    $error = "Error adding candidate: " . $e->getMessage();
                } catch (Exception $e) {
                    $error = $e->getMessage(); 
                }
            }
        } catch (PDOException $e) {
            $error = "Error checking candidate: " . $e->getMessage();
        }
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Add Candidate</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="office_id">Office</label>
            <select id="office_id" name="office_id" class="form-control" required>
                <option value="">Select Office</option>
                <?php
                // Fetch offices for dropdown
                $query = "SELECT id, name FROM offices";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($offices as $office):
                ?>
                    <option value="<?= htmlspecialchars($office['id']) ?>">
                        <?= htmlspecialchars($office['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="election_id">Election</label>
            <select id="election_id" name="election_id" class="form-control" required>
                <option value="">Select Election</option>
                <?php
                // Fetch elections for dropdown
                $query = "SELECT id, name FROM elections";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($elections as $election):
                ?>
                    <option value="<?= htmlspecialchars($election['id']) ?>">
                        <?= htmlspecialchars($election['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="faculty">Faculty</label>
            <input type="text" id="faculty" name="faculty" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="level">Level</label>
            <input type="text" id="level" name="level" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Candidate</button>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    </form>
</div>

<?php include('../includes/footer.php'); ?>