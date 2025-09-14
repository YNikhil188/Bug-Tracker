<?php
require_once '../includes/functions.php';
requireRole('pm');
include '../includes/header.php';

// Initialize counts with error handling
$totalProjects = 0;
$stmt = $pdo->query("SELECT COUNT(*) FROM projects WHERE manager_id = " . $_SESSION['user_id']);
if ($stmt !== false) {
    $totalProjects = $stmt->fetchColumn();
} else {
    $errorInfo = $pdo->errorInfo();
    error_log("Query failed: " . $errorInfo[2]); // Log for debugging
}

$totalBugs = 0;
$stmt = $pdo->query("SELECT COUNT(*) FROM bugs WHERE project_id IN (SELECT id FROM projects WHERE manager_id = " . $_SESSION['user_id'] . ")");
if ($stmt !== false) {
    $totalBugs = $stmt->fetchColumn();
}

$openBugs = 0;
$stmt = $pdo->query("SELECT COUNT(*) FROM bugs WHERE status = 'open' AND project_id IN (SELECT id FROM projects WHERE manager_id = " . $_SESSION['user_id'] . ")");
if ($stmt !== false) {
    $openBugs = $stmt->fetchColumn();
}
?>
<h1 class="h2 mb-4 text-primary">PM Dashboard</h1>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #6b48ff, #8e7cff); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-project-diagram fa-3x mb-3"></i>
                <h5>Total Projects</h5>
                <h2 class="display-6"><?php echo $totalProjects; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #ff6b6b, #ff8787); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-bug fa-3x mb-3"></i>
                <h5>Total Bugs</h5>
                <h2 class="display-6"><?php echo $totalBugs; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #ffc107, #ffd54f); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>Open Bugs</h5>
                <h2 class="display-6"><?php echo $openBugs; ?></h2>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
<?php include '../includes/footer.php'; ?>