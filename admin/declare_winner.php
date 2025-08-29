<?php
session_start();
include('../includes/db.php');
require('../fpdf/fpdf.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

if (!isset($pdo)) {
    die("Database connection is not established.");
}

// Fetch admin details
$query = "SELECT name FROM admins WHERE id = :admin_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}

$admin_name = $admin['name'];

$query = "SELECT 
            c.id as candidate_id, 
            c.name as candidate_name, 
            c.office, 
            c.votes, 
            c.photo,
            c.faculty,
            c.department,
            c.level,
            e.name as election_name,
            e.end_date as election_date,
            (c.votes = (
                SELECT MAX(c2.votes)
                FROM candidates c2
                WHERE c2.office = c.office AND c2.election_id = c.election_id
            )) AS is_winner
          FROM candidates c
          JOIN elections e ON c.election_id = e.id
          ORDER BY c.election_id, c.office, c.votes DESC, c.name ASC";
$stmt = $pdo->query($query);
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedCandidates = [];
foreach ($candidates as $candidate) {
    $groupedCandidates[$candidate['election_name']][$candidate['office']][] = $candidate;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['declare_winner'])) {
    $candidate_id = $_POST['candidate_id'];
    $query = "UPDATE candidates SET is_winner = 1 WHERE id = :candidate_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['candidate_id' => $candidate_id]);
    header("Location: declare_winner.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Declare Winners</h2>

    <?php foreach ($groupedCandidates as $electionName => $offices): ?>
        <h3 class="text-center mt-5"><?= htmlspecialchars($electionName ?? 'Unknown Election') ?></h3>
        <?php foreach ($offices as $office => $candidates): ?>
            <h4 class="mt-4"><?= htmlspecialchars($office ?? 'Unknown Office') ?></h4>
            <div class="row">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../uploads/<?= htmlspecialchars($candidate['photo'] ?? 'default.png') ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($candidate['candidate_name'] ?? 'Unknown') ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($candidate['candidate_name'] ?? 'Unknown') ?></h5>
                                <p class="card-text">
                                    <strong>Votes:</strong> <?= htmlspecialchars($candidate['votes'] ?? '0') ?><br>
                                    <strong>Faculty:</strong> <?= htmlspecialchars($candidate['faculty'] ?? 'Unknown') ?><br>
                                    <strong>Department:</strong> <?= htmlspecialchars($candidate['department'] ?? 'Unknown') ?><br>
                                    <strong>Level:</strong> <?= htmlspecialchars($candidate['level'] ?? 'Unknown') ?>
                                </p>
                                <?php if ($candidate['is_winner']): ?>
                                    <div class="winner-badge">
                                        <span class="badge badge-success">Winner <i class="fas fa-trophy"></i></span>
                                    </div>
                                    <form action="declare_winner.php" method="post">
                                        <input type="hidden" name="candidate_id" value="<?= htmlspecialchars($candidate['candidate_id']) ?>">
                                        <button type="submit" name="declare_winner" class="btn btn-primary mt-3">Declare Winner</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>

<?php include('../includes/footer.php'); ?>

<style>
.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.winner-badge {
    margin-top: 10px;
    font-size: 1.2rem;
    animation: fadeIn 1s ease-in-out;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.winner-badge .fas.fa-trophy {
    margin-left: 5px;
    animation: bounce 1s infinite;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-15px);
    }
    60% {
        transform: translateY(-7px);
    }
}
</style>
