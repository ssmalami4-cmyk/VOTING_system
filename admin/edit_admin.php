<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Fetch admin details
    $query = "SELECT * FROM admins WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching admin details: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare update query
    $updateQuery = "UPDATE admins SET name = :name, email = :email";
    
    // Include password in the query only if it's provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery .= ", password = :password";
    }
    
    $updateQuery .= " WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Bind password if it's provided
    if (!empty($password)) {
        $updateStmt->bindParam(':password', $hashedPassword);
    }

    try {
        $updateStmt->execute();
        header("Location: view_admins.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating admin details: " . $e->getMessage());
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Edit Admin</h2>
    <form method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($admin['name'] ?? '', ENT_QUOTES) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($admin['email'] ?? '', ENT_QUOTES) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password (Leave blank to keep current)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
