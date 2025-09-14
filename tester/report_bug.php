<?php
require_once '../includes/functions.php';
requireRole('tester');
include '../includes/header.php';

$userId = $_SESSION['user_id'];
$projects = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $priority = $_POST['priority'] ?? 'medium';
    $projectId = $_POST['project_id'] ?? null;

    if ($title && $description && $projectId) {
        $stmt = $pdo->prepare("INSERT INTO bugs (project_id, title, description, priority, reporter_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$projectId, $title, $description, $priority, $userId]);
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        $error = "All fields are required.";
    }
}

// Fetch projects assigned to the tester
$stmt = $pdo->query("SELECT p.id, p.name FROM projects p INNER JOIN project_assignments pa ON p.id = pa.project_id WHERE pa.user_id = $userId");
if ($stmt !== false) {
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $pdo->errorInfo();
    error_log("Query failed: " . $errorInfo[2]); // Log for debugging
}
?>
<h1 class="h2 mb-4 text-primary">Report a Bug</h1>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" action="">
    <div class="mb-3">
        <label for="project_id" class="form-label">Project</label>
        <select name="project_id" id="project_id" class="form-select" required>
            <option value="">Select a Project</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?php echo htmlspecialchars($project['id']); ?>">
                    <?php echo htmlspecialchars($project['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <select name="priority" id="priority" class="form-select">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Submit Bug</button>
</form>
<?php include '../includes/footer.php'; ?>