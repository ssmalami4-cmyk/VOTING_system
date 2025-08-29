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

// Fetch all winners
$query = "SELECT 
            c.name as candidate_name, 
            c.office, 
            c.votes, 
            c.photo,
            e.name as election_name,
            e.end_date as election_date
          FROM candidates c
          JOIN elections e ON c.election_id = e.id
          WHERE (c.votes = (
              SELECT MAX(c2.votes)
              FROM candidates c2
              WHERE c2.office = c.office AND c2.election_id = c.election_id
          ))";
$stmt = $pdo->query($query);
$winners = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate Certificate Function
function generateCertificate($candidate_name, $office, $election_name, $election_date, $admin_name) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('../assets/images/background3.jpg', 0, 0, 210, 297); 
    $pdf->SetTextColor(139, 69, 19); 
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
    $pdf->Cell(0, 5, $admin_name, 0, 1, 'C');
    $pdf->Ln(20);
    $pdf->Cell(0, 5, 'Signature of Returning Officer:', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->Cell(0, 5, '______________________', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->Cell(0, 5, 'Date:', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 5, date("F j, Y"), 0, 1, 'C'); 
    $pdf->Ln(10);
    $pdf->Image('../assets/images/seal.png', 90, 240, 30); 
    $pdf->Output('D', "$candidate_name-Certificate.pdf");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_certificate'])) {
    $candidate_name = $_POST['candidate_name'];
    $office = $_POST['office'];
    $election_name = $_POST['election_name'];
    $election_date = $_POST['election_date'];
    generateCertificate($candidate_name, $office, $election_name, $election_date, $admin_name);
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Winners List</h2>
    <div class="row">
        <?php foreach ($winners as $winner): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="../uploads/<?= htmlspecialchars($winner['photo'] ?? 'default.png') ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($winner['candidate_name'] ?? 'Unknown') ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($winner['candidate_name'] ?? 'Unknown') ?></h5>
                    <p class="card-text">
                        <strong>Office:</strong> <?= htmlspecialchars($winner['office'] ?? 'Unknown') ?><br>
                        <strong>Election:</strong> <?= htmlspecialchars($winner['election_name'] ?? 'Unknown') ?><br>
                        <strong>Election Date:</strong> <?= htmlspecialchars($winner['election_date'] ?? 'Unknown') ?><br>
                        <strong>Votes:</strong> <?= htmlspecialchars($winner['votes'] ?? '0') ?>
                    </p>
                    <form action="" method="post">
                        <input type="hidden" name="candidate_name" value="<?= htmlspecialchars($winner['candidate_name']) ?>">
                        <input type="hidden" name="office" value="<?= htmlspecialchars($winner['office']) ?>">
                        <input type="hidden" name="election_name" value="<?= htmlspecialchars($winner['election_name']) ?>">
                        <input type="hidden" name="election_date" value="<?= htmlspecialchars($winner['election_date']) ?>">
                        <button type="submit" name="generate_certificate" class="btn btn-primary mt-3">Generate Certificate</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
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
