<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System</title>
    <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Voting System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Home</a>
            </li>
            <?php if ($user_role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="./admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./view_candidates.php">Candidates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./view_voters.php">Voters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php elseif ($user_role === 'voter'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="./vote.php">Vote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php elseif ($user_role === 'candidate'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="./candidate_register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="./admin/admin_login.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./candidate/candidate_register.php">Candidate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./voter/voter_login.php">Voter</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
