<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $query = "DELETE FROM admins WHERE id = :id";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([':id' => $id]);
        header("Location: view_admins.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting admin: " . $e->getMessage());
    }
} else {
    header("Location: view_admins.php");
}
?>
