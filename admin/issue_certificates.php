<?php
session_start();
include('../includes/db.php');
require('../fpdf/fpdf.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$stmt = $pdo->query("SELECT Candidates.name, Candidates.office, Candidates.faculty, Candidates.department, Candidates.level, COUNT(Votes.id) AS vote_count 
                     FROM Votes 
                     JOIN Candidates ON Votes.candidate_id = Candidates.id 
                     GROUP BY Candidates.id 
                     ORDER BY vote_count DESC");
$results = $stmt->fetchAll();

foreach ($results as $result) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Certificate of Election', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "This is to certify that {$result['name']} has been elected as {$result['office']} of the", 0, 1, 'C');
    $pdf->Cell(0, 10, "faculty of {$result['faculty']}, department of {$result['department']} at the level of {$result['level']}.", 0, 1, 'C');
    $pdf->Ln(20);
    $pdf->Cell(0, 10, 'Date: ' . date('d/m/Y'), 0, 1, 'C');
    $pdf->Output("certificates/{$result['name']}_certificate.pdf", 'F');
}

header("Location: admin_dashboard.php");
exit();
?>
