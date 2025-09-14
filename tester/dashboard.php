<?php
require_once '../includes/functions.php';
requireRole('tester');
$userId = $_SESSION['user_id'];
include '../includes/header.php';

// Assigned projects
$stmt = $pdo->prepare("SELECT DISTINCT p.* FROM projects p JOIN project_assignments pa ON p.id = pa.project_id WHERE pa.user_id = ? AND pa.assigned_role = 'tester'");
$stmt->execute([$userId]);
$projects = $stmt->fetchAll();
?>
<h1 class="h2 mb-4">Tester Dashboard</h1>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Assigned Projects</h5>
                <div class="row">
                    <?php foreach ($projects as $project): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6><?php echo htmlspecialchars($project['name']); ?></h6>
                                <p><?php echo htmlspecialchars($project['description']); ?></p>
                                <a href="report_bug.php?project_id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary">Report Bug</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>