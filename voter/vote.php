<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit();
}

$voter_id = $_SESSION['voter_id'];

// Fetch registered elections for the voter
try {
    $query = "SELECT * FROM elections WHERE id IN (SELECT election_id FROM voters WHERE id = ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$voter_id]);
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching elections: " . $e->getMessage();
    exit();
}

// Check if the voter has already voted
$voted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_vote'])) {
    $votes = $_POST['votes'];
    $election_id = $_POST['election_id'];

    // Check if voter has already voted for this election
    try {
        $query = "SELECT COUNT(*) FROM votes WHERE voter_id = ? AND election_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$voter_id, $election_id]);
        $voted = $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error checking previous votes: " . $e->getMessage() . "</div>";
    }

    if (!$voted) {
        try {
            $pdo->beginTransaction();
            foreach ($votes as $office_id => $candidate_id) {
                $query = "INSERT INTO votes (voter_id, election_id, office_id, candidate_id) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$voter_id, $election_id, $office_id, $candidate_id]);
            }
            $pdo->commit();
            echo "<div class='alert alert-success text-center'>Vote cast successfully!</div>";

            // Update votes in the candidates table
            try {
                // Fetch all candidates
                $query = "SELECT id, office_id FROM candidates WHERE election_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$election_id]);
                $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Begin transaction
                $pdo->beginTransaction();

                // Update votes for each candidate
                foreach ($candidates as $candidate) {
                    // Count votes for this candidate
                    $query = "SELECT COUNT(*) AS total_votes FROM votes WHERE candidate_id = ?";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$candidate['id']]);
                    $total_votes = $stmt->fetchColumn();

                    // Update the votes count in the candidates table
                    $updateQuery = "UPDATE candidates SET votes = ? WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->execute([$total_votes, $candidate['id']]);
                }

                // Commit transaction
                $pdo->commit();
                echo "<div class='alert alert-success text-center'>Votes counted and candidates table updated successfully!</div>";
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo "<div class='alert alert-danger text-center'>Error updating votes: " . $e->getMessage() . "</div>";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<div class='alert alert-danger text-center'>Error casting vote: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>You have already voted in this election.</div>";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Vote</h2>
    <div id="countdown-timer" class="countdown-timer text-center mb-4">
        <div class="countdown-element">
            <span id="days" class="countdown-number">0</span>
            <span class="countdown-label">Days</span>
        </div>
        <div class="countdown-element">
            <span id="hours" class="countdown-number">0</span>
            <span class="countdown-label">Hours</span>
        </div>
        <div class="countdown-element">
            <span id="minutes" class="countdown-number">0</span>
            <span class="countdown-label">Minutes</span>
        </div>
        <div class="countdown-element">
            <span id="seconds" class="countdown-number">0</span>
            <span class="countdown-label">Seconds</span>
        </div>
    </div>
    <form method="post" action="">
        <div class="form-group">
            <label for="election_id">Select Election</label>
            <select class="form-control" id="election_id" name="election_id" onchange="this.form.submit()">
                <option value="">Select Election</option>
                <?php foreach ($elections as $row): 
                    $start_date = new DateTime($row['start_date']);
                    $end_date = new DateTime($row['end_date']);
                    $now = new DateTime();
                ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>" <?= (isset($_POST['election_id']) && $_POST['election_id'] == $row['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (isset($_POST['election_id']) && !empty($_POST['election_id'])):
        $election_id = $_POST['election_id'];
        $election_selected = null;
        foreach ($elections as $election) {
            if ($election['id'] == $election_id) {
                $election_selected = $election;
                break;
            }
        }

        if ($election_selected) {
            $start_date = new DateTime($election_selected['start_date']);
            $end_date = new DateTime($election_selected['end_date']);
            $now = new DateTime();

            if ($now >= $start_date && $now <= $end_date) {
                // Fetch candidates for the selected election
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
            } else {
                echo "<div class='alert alert-warning text-center'>This election is not currently active.</div>";
            }
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
                                    <input class="form-check-input" type="radio" name="votes[<?= htmlspecialchars($candidate['office_id']) ?>]" value="<?= htmlspecialchars($candidate['id']) ?>" id="candidate_<?= htmlspecialchars($candidate['id']) ?>" required>
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
        <button type="submit" name="submit_vote" class="btn btn-primary"><i class="fas fa-vote-yea"></i>Submit Vote</button>
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

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 15px;
    font-family: 'Arial', sans-serif;
    color: #333;
    margin-bottom: 20px;
}

.countdown-element {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.countdown-number {
    font-size: 2.5em;
    font-weight: bold;
}

.countdown-label {
    font-size: 0.75em;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var endDateTime = new Date("<?= isset($election_selected) ? $election_selected['end_date'] : '' ?>").getTime();

    var countdownTimer = setInterval(function() {
        var now = new Date().getTime();
        var distance = endDateTime - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days;
        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;

        if (distance < 0) {
            clearInterval(countdownTimer);
            document.getElementById("days").innerText = "0";
            document.getElementById("hours").innerText = "0";
            document.getElementById("minutes").innerText = "0";
            document.getElementById("seconds").innerText = "0";
        }
    }, 1000);
});
</script>
