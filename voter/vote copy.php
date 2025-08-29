<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_vote'])) {
    $votes = $_POST['votes'];

    try {
        $pdo->beginTransaction();
        foreach ($votes as $office_id => $candidate_id) {
            $query = "INSERT INTO votes (voter_id, office_id, candidate_id) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$_SESSION['voter_id'], $office_id, $candidate_id]);
        }
        $pdo->commit();
        echo "<div class='alert alert-success text-center'>Vote cast successfully!</div>";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger text-center'>Error casting vote: " . $e->getMessage() . "</div>";
    }
}

try {
    $query = "SELECT * FROM elections WHERE id IN (SELECT DISTINCT election_id FROM voters WHERE id = ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['voter_id']]);
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching elections: " . $e->getMessage();
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Vote</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="election_id">Select Election</label>
            <select class="form-control" id="election_id" name="election_id" onchange="this.form.submit()">
                <option value="">Select Election</option>
                <?php foreach ($elections as $row): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>" <?= (isset($_POST['election_id']) && $_POST['election_id'] == $row['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (isset($_POST['election_id']) && !empty($_POST['election_id'])): 
        $election_id = $_POST['election_id'];
        try {
            $query = "SELECT * FROM candidates WHERE election_id = ? AND approved = 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$election_id]);
            $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching candidates: " . $e->getMessage();
        }

        $candidatesByOffice = [];
        foreach ($candidates as $candidate) {
            $candidatesByOffice[$candidate['office']][] = $candidate;
        }
    ?>
    <form method="post" action="">
        <input type="hidden" name="election_id" value="<?= htmlspecialchars($election_id) ?>">
        <?php foreach ($candidatesByOffice as $office => $candidates): ?>
            <h3 class="text-center mt-4"><?= htmlspecialchars($office) ?></h3>
            <div class="row">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../uploads/<?= htmlspecialchars($candidate['photo']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($candidate['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($candidate['name']) ?></h5>
                                <p class="card-text">
                                    Faculty: <?= htmlspecialchars($candidate['faculty']) ?><br>
                                    Department: <?= htmlspecialchars($candidate['department']) ?><br>
                                    Level: <?= htmlspecialchars($candidate['level']) ?>
                                </p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="votes[<?= htmlspecialchars($candidate['office_id']) ?>]" value="<?= htmlspecialchars($candidate['id']) ?>" id="candidate_<?= htmlspecialchars($candidate['id']) ?>">
                                    <label class="form-check-label" for="candidate_<?= htmlspecialchars($candidate['id']) ?>">
                                        Select <?= htmlspecialchars($candidate['name']) ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" name="submit_vote" class="btn btn-primary btn-block">Submit Vote</button>
    </form>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
<style>
.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.btn-block {
    display: block;
    width: 100%;
}

.alert {
    margin-top: 20px;
}
</style>
