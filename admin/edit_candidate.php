<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

$query = "SELECT * FROM candidates WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$candidate = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $office = $_POST['office'];
    $status = $_POST['status'];

    if (isset($_FILES['photo']) && $_FILES['photo']['name']) {
        $photo = $_FILES['photo']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    } else {
        $photo = $candidate['photo'];
    }

    $query = "UPDATE candidates SET name = ?, office = ?, photo = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $office, $photo, $status, $id);
    mysqli_stmt_execute($stmt);

    header('Location: view_candidates.php');
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Edit Candidate</h2>
    <form method="post" action="edit_candidate.php?id=<?= htmlspecialchars($candidate['id']) ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($candidate['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="office">Office:</label>
            <input type="text" class="form-control" id="office" name="office" value="<?= htmlspecialchars($candidate['office']) ?>" required>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($candidate['photo']) ?>">
            <img src="../uploads/<?= htmlspecialchars($candidate['photo']) ?>" alt="Candidate Photo" width="100">
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" class="form-control" id="status" name="status" value="<?= htmlspecialchars($candidate['status']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Candidate</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
