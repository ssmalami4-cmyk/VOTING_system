<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    $query = "INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        header("Location: view_admins.php");
        exit();
    } catch (PDOException $e) {
        die("Error adding admin: " . $e->getMessage());
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Add Admin</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Admin</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
