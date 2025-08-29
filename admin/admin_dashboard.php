<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="row">
        <!-- Manage Voters Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h5 class="card-title">Manage Voters</h5>
                    <a href="upload_voters.php" class="btn btn-primary">Upload Voters</a>
                    <a href="view_voters.php" class="btn btn-secondary mt-2">View Voters</a>
                </div>
            </div>
        </div>

        <!-- Approve Candidates Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-3x mb-3"></i>
                    <h5 class="card-title">Approve Candidates</h5>
                    <a href="approve_candidates.php" class="btn btn-success">Approve</a>
                    <a href="view_candidates.php" class="btn btn-secondary mt-2">View Candidates</a>
                </div>
            </div>
        </div>

        <!-- View Results Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-poll-h fa-3x mb-3"></i>
                    <h5 class="card-title">View Results</h5>
                    <a href="admin_results.php" class="btn btn-info">Results</a>
                </div>
            </div>
        </div>
        
        <!-- Declare Winners Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-3x mb-3"></i>
                    <h5 class="card-title">Declare Winners</h5>
                    <a href="declare_winner.php" class="btn btn-warning">Declare Winners</a>
                </div>
            </div>
        </div>
        
        <!-- Generate Certificates Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-certificate fa-3x mb-3"></i>
                    <h5 class="card-title">Generate Certificates</h5>
                    <a href="generate_certificate.php" class="btn btn-success">Generate Certificates</a>
                </div>
            </div>
        </div>
        
        <!-- Create Election Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-plus fa-3x mb-3"></i>
                    <h5 class="card-title">Create Election</h5>
                    <a href="create_election.php" class="btn btn-primary">Create</a>
                </div>
            </div>
        </div>

        <!-- View Elections Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <h5 class="card-title">View Elections</h5>
                    <a href="view_elections.php" class="btn btn-secondary">View Elections</a>
                </div>
            </div>
        </div>

        <!-- Manage Offices Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-3x mb-3"></i>
                    <h5 class="card-title">Manage Offices</h5>
                    <a href="add_office.php" class="btn btn-primary">Add Office</a>
                    <a href="view_offices.php" class="btn btn-secondary mt-2">View Offices</a>
                </div>
            </div>
        </div>

        <!-- Manage Admin Section -->
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-shield fa-3x mb-3"></i>
                    <h5 class="card-title">Manage Admins</h5>
                    <a href="add_admin.php" class="btn btn-primary">Add Admin</a>
                    <a href="view_admins.php" class="btn btn-secondary mt-2">View Admins</a>
                </div>
            </div>
        </div>

    </div>
    <div class="text-center mt-4">
        <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
