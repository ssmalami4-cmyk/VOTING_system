<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM offices WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_offices.php");
    exit();
} catch (PDOException $e) {
    die("Error deleting office: " . $e->getMessage());
}
?>
