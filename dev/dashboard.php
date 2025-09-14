<?php
require_once '../includes/functions.php';
requireRole('dev');
include '../includes/header.php';

$userId = $_SESSION['user_id'];

// Count bugs assigned to the developer
$assignedBugs = 0;
$stmt = $pdo->query("SELECT COUNT(*) FROM bugs WHERE assigned_to = $userId");
if ($stmt !== false) {
    $assignedBugs = $stmt->fetchColumn();
} else {
    $errorInfo = $pdo->errorInfo();
    error_log("Query failed: " . $errorInfo[2]); // Log for debugging
}

// Count open bugs assigned to the developer
$openBugs = 0;
$stmt = $pdo->query("SELECT COUNT(*) FROM bugs WHERE assigned_to = $userId AND status = 'open'");
if ($stmt !== false) {
    $openBugs = $stmt->fetchColumn();
}

// Count projects the developer is assigned to (via project_assignments)
$assignedProjects = 0;
$stmt = $pdo->query("SELECT COUNT(DISTINCT project_id) FROM project_assignments WHERE user_id = $userId");
if ($stmt !== false) {
    $assignedProjects = $stmt->fetchColumn();
}
?>
<h1 class="h2 mb-4 text-primary">Developer Dashboard</h1>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #6b48ff, #8e7cff); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-project-diagram fa-3x mb-3"></i>
                <h5>Assigned Projects</h5>
                <h2 class="display-6"><?php echo $assignedProjects; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #ff6b6b, #ff8787); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-bug fa-3x mb-3"></i>
                <h5>Assigned Bugs</h5>
                <h2 class="display-6"><?php echo $assignedBugs; ?></h2>
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