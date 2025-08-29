<?php
session_start();
include('../includes/db.php');
require('../fpdf/certificate_template.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$candidate_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM Candidates WHERE id = ?");
$stmt->execute([$candidate_id]);
$candidate = $stmt->fetch();

if ($candidate) {
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->CertificateBody($candidate['name'], $candidate['office'], $candidate['faculty'], $candidate['department'], $candidate['level']);
    $pdf->Output("D", "{$candidate['name']}_certificate.pdf");
}
?>
