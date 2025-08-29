<?php
include('../includes/db.php');

$election_id = $_GET['election_id'];

try {
    if ($election_id == 'all') {
        $query = "SELECT name, email, student_id FROM voters";
        $stmt = $pdo->prepare($query);
    } else {
        $query = "SELECT name, email, student_id FROM voters WHERE election_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$election_id]);
    }
    $stmt->execute();
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Voters</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .btn { display: none; }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Voters List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Student ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($voters as $voter): ?>
            <tr>
                <td><?= htmlspecialchars($voter['name']) ?></td>
                <td><?= htmlspecialchars($voter['email']) ?></td>
                <td><?= htmlspecialchars($voter['student_id']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-center">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
</div>
</body>
</html>
