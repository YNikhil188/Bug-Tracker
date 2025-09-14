<?php
require_once '../includes/functions.php';
requireRole('pm');
$managerId = $_SESSION['user_id'];
include '../includes/header.php';

// Fetch bugs in managed projects
$stmt = $pdo->prepare("SELECT b.*, p.name as project_name, u.name as reporter_name, v.name as assigned_name FROM bugs b JOIN projects p ON b.project_id = p.id JOIN users u ON b.reporter_id = u.id LEFT JOIN users v ON b.assigned_to = v.id WHERE p.manager_id = ? ORDER BY b.created_at DESC");
$stmt->execute([$managerId]);
$bugs = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    $bugId = $_POST['bug_id'];
    $assignedTo = $_POST['assigned_to'];
    $stmt = $pdo->prepare("UPDATE bugs SET assigned_to = ? WHERE id = ?");
    $stmt->execute([$assignedTo, $bugId]);
    $bug = $pdo->prepare("SELECT * FROM bugs WHERE id = ?")->execute([$bugId])->fetch();
    $assignedUser = getUserById($pdo, $assignedTo);
    sendEmail($assignedUser['email'], 'Bug Assigned', '<h3>Bug: ' . $bug['title'] . '</h3><p>Priority: ' . ucfirst($bug['priority']) . '</p><p>Project: ' . $bug['project_name'] . '</p>');
}
?>
<h1 class="h2 mb-4">Bugs Overview</h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Project</th>
                <th>Title</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Reporter</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bugs as $bug): ?>
            <tr>
                <td><?php echo htmlspecialchars($bug['project_name']); ?></td>
                <td><?php echo htmlspecialchars($bug['title']); ?></td>
                <td><span class="badge badge-<?php echo $bug['priority']; ?>"><?php echo ucfirst($bug['priority']); ?></span></td>
                <td><span class="badge label-<?php echo $bug['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $bug['status'])); ?></span></td>
                <td><?php echo htmlspecialchars($bug['reporter_name']); ?></td>
                <td><?php echo $bug['assigned_name'] ?? 'Unassigned'; ?></td>
                <td>
                    <?php if (!$bug['assigned_to']): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="bug_id" value="<?php echo $bug['id']; ?>">
                        <select name="assigned_to" class="form-select form-select-sm">
                            <?php
                            $devs = $pdo->query("SELECT id, name FROM users WHERE role = 'dev'")->fetchAll();
                            foreach ($devs as $dev) {
                                echo "<option value='{$dev['id']}'>{$dev['name']}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="assign" value="1" class="btn btn-sm btn-primary">Assign</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>