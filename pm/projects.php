<?php
require_once '../includes/functions.php';
requireRole('pm');
$managerId = $_SESSION['user_id'];
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO projects (name, description, manager_id) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $managerId]);
        $projectId = $pdo->lastInsertId();
        // Assign example dev/tester (in real, select users)
        $devStmt = $pdo->prepare("INSERT INTO project_assignments (project_id, user_id, assigned_role) VALUES (?, ?, 'dev')");
        $devStmt->execute([$projectId, 3]); // dev1 id=3
        $testerStmt = $pdo->prepare("INSERT INTO project_assignments (project_id, user_id, assigned_role) VALUES (?, ?, 'tester')");
        $testerStmt->execute([$projectId, 4]); // tester1 id=4
        // Email notification
        $dev = getUserById($pdo, 3);
        sendEmail($dev['email'], 'Project Assigned', '<h3>Project: ' . $name . '</h3><p>Assigned as Developer.</p>');
        $tester = getUserById($pdo, 4);
        sendEmail($tester['email'], 'Project Assigned', '<h3>Project: ' . $name . '</h3><p>Assigned as Tester.</p>');
    }
}

// Fetch managed projects
$stmt = $pdo->prepare("SELECT p.*, COUNT(pa.user_id) as assigned_count FROM projects p LEFT JOIN project_assignments pa ON p.id = pa.project_id WHERE p.manager_id = ? GROUP BY p.id ORDER BY p.created_at DESC");
$stmt->execute([$managerId]);
$projects = $stmt->fetchAll();
?>
<h1 class="h2 mb-4">Manage Projects</h1>
<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="add" value="1">
                    <div class="mb-3"><input type="text" class="form-control" name="name" placeholder="Project Name" required></div>
                    <div class="mb-3"><textarea class="form-control" name="description" placeholder="Description" required></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add & Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProjectModal"><i class="fas fa-plus me-1"></i>Add Project</button>
<div class="row">
    <?php foreach ($projects as $project): ?>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($project['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                <div class="progress mb-2">
                    <div class="progress-bar" style="width: <?php echo rand(20,100); ?>%"></div> <!-- Example progress -->
                </div>
                <p class="text-muted">Assigned: <?php echo $project['assigned_count']; ?> members</p>
                <span class="badge bg-<?php echo $project['status']=='active'?'success':'secondary'; ?>"><?php echo ucfirst($project['status']); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>