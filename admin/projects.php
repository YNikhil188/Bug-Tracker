<?php
require_once '../includes/functions.php';
requireRole('admin');
include '../includes/header.php';

// Fetch all projects with manager
$stmt = $pdo->query("SELECT p.*, u.name as manager_name FROM projects p JOIN users u ON p.manager_id = u.id ORDER BY p.created_at DESC");
$projects = $stmt->fetchAll();
?>
<h1 class="h2 mb-4">All Projects</h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?php echo htmlspecialchars($project['name']); ?></td>
                <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?></td>
                <td><?php echo htmlspecialchars($project['manager_name']); ?></td>
                <td><span class="badge bg-<?php echo $project['status']=='active'?'success':'secondary'; ?>"><?php echo ucfirst($project['status']); ?></span></td>
                <td><?php echo date('M j, Y', strtotime($project['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>