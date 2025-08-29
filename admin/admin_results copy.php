<?php
session_start();
include('../includes/db.php');
require('../fpdf/fpdf.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($pdo)) {
    die("Database connection is not established.");
}

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
          ORDER BY c.election_id, c.office_id, c.votes DESC, c.name ASC";
$stmt = $pdo->query($query);
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedCandidates = [];
foreach ($candidates as $candidate) {
    $groupedCandidates[$candidate['election_name']][$candidate['office']][] = $candidate;
}

// Generate Certificate Function
function generateCertificate($candidate_name, $office, $election_name, $election_date) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('../assets/images/background3.jpg', 0, 0, 210, 297); 
    $pdf->SetFont('Arial', 'B', 24);
    $pdf->Cell(0, 50, 'CERTIFICATE OF RETURN', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "This is to certify that:", 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 5, strtoupper($candidate_name), 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "has been duly elected to the office of:", 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 5, strtoupper($office), 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "in the:", 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 5, strtoupper($election_name), 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "held on:", 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 5, date("F j, Y", strtotime($election_date)), 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 5, "and has been declared the winner by the:", 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 5, '[Name of Electoral Body/Commission]', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->Cell(0, 20, 'Signature of Returning Officer:', 0, 1, 'L');
    $pdf->Cell(0, 5, '______________________', 0, 1, 'L');
    $pdf->Ln(10);
    $pdf->Cell(0, 20, 'Date:', 0, 1, 'L');
    $pdf->Cell(0, 20, '______________________', 0, 1, 'L');
    $pdf->Ln(10);
    $pdf->Image('../assets/images/seal.png', 90, 220, 30); 
    $pdf->Output('D', "$candidate_name-Certificate.pdf");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_certificate'])) {
    $candidate_name = $_POST['candidate_name'];
    $office = $_POST['office'];
    $election_name = $_POST['election_name'];
    $election_date = $_POST['election_date'];
    generateCertificate($candidate_name, $office, $election_name, $election_date);
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Election Results - Candidates & Winners</h2>

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
                                    <form action="admin_results.php" method="post">
                                        <input type="hidden" name="candidate_name" value="<?= htmlspecialchars($candidate['candidate_name']) ?>">
                                        <input type="hidden" name="office" value="<?= htmlspecialchars($candidate['office']) ?>">
                                        <input type="hidden" name="election_name" value="<?= htmlspecialchars($electionName) ?>">
                                        <input type="hidden" name="election_date" value="<?= htmlspecialchars($candidate['election_date']) ?>">
                                        <button type="submit" name="generate_certificate" class="btn btn-primary mt-3">Generate Certificate</button>
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
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.winner-badge .fas.fa-trophy {
    margin-left: 5px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>
