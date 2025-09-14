<?php
require_once '../includes/functions.php';
requireRole('dev');
$userId = $_SESSION['user_id'];
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status'])) {
        $bugId = $_POST['bug_id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE bugs SET status = ? WHERE id = ? AND assigned_to = ?");
        $stmt->execute([$status, $bugId, $userId]);
        // Email PM on close
        $bug = $pdo->prepare("SELECT p.manager_id FROM bugs b JOIN projects p ON b.project_id = p.id WHERE b.id = ?")->execute([$bugId])->fetch();
        $pm = getUserById($pdo, $bug['manager_id']);
        sendEmail($pm['email'], 'Bug Updated', '<h3>Bug status changed to ' . ucfirst($status) . '</h3>');
    } elseif (isset($_POST['add_comment'])) {
        $bugId = $_POST['bug_id'];
        $comment = $_POST['comment'];
        $stmt = $pdo->prepare("INSERT INTO comments (bug_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$bugId, $userId, $comment]);
    }
}

// Fetch assigned bugs
$stmt = $pdo->prepare("SELECT b.*, p.name as project_name FROM bugs b JOIN projects p ON b.project_id = p.id WHERE b.assigned_to = ? ORDER BY b.created_at DESC");
$stmt->execute([$userId]);
$bugs = $stmt->fetchAll();

// For threaded comments, fetch per bug (example for first bug; in real, JS/AJAX load)
$commentsStmt = $pdo->prepare("SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.bug_id = ? ORDER BY c.created_at");
?>
<h1 class="h2 mb-4">Assigned Bugs</h1>
<div class="row">
    <?php foreach ($bugs as $bug): ?>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5><?php echo htmlspecialchars($bug['title']); ?></h5>
                <p><small>Project: <?php echo htmlspecialchars($bug['project_name']); ?></small></p>
                <p><?php echo htmlspecialchars($bug['description']); ?></p>
                <?php if ($bug['screenshot']): ?>
                <img src="../assets/uploads/<?php echo htmlspecialchars($bug['screenshot']); ?>" alt="Screenshot" class="img-thumbnail" style="max-width: 200px;">
                <?php endif; ?>
                <div class="mb-2">
                    <span class="badge badge-<?php echo $bug['priority']; ?>"><?php echo ucfirst($bug['priority']); ?></span>
                    <span class="badge label-<?php echo $bug['status']; ?> ms-2"><?php echo ucfirst(str_replace('_', ' ', $bug['status'])); ?></span>
                </div>
                <form method="POST" class="mb-2">
                    <input type="hidden" name="bug_id" value="<?php echo $bug['id']; ?>">
                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                        <option value="open" <?php echo $bug['status']=='open'?'selected':'';?>>Open</option>
                        <option value="in_progress" <?php echo $bug['status']=='in_progress'?'selected':'';?>>In Progress</option>
                        <option value="resolved" <?php echo $bug['status']=='resolved'?'selected':'';?>>Resolved</option>
                        <option value="closed" <?php echo $bug['status']=='closed'?'selected':'';?>>Closed</option>
                    </select>
                    <button type="submit" name="update_status" value="1" class="btn btn-sm btn-outline-primary ms-2">Update</button>
                </form>
                <!-- Add Comment -->
                <form method="POST">
                    <input type="hidden" name="bug_id" value="<?php echo $bug['id']; ?>">
                    <div class="input-group">
                        <input type="text" name="comment" class="form-control" placeholder="Add comment...">
                        <button type="submit" name="add_comment" value="1" class="btn btn-outline-secondary"><i class="fas fa-reply"></i></button>
                    </div>
                </form>
                <!-- Threaded Comments -->
                <ul class="threaded-comments mt-2">
                    <?php
                    $commentsStmt->execute([$bug['id']]);
                    foreach ($commentsStmt->fetchAll() as $comment) {
                        echo "<li><small><strong>{$comment['name']}</strong>: {$comment['comment']} <em>(" . date('M j H:i', strtotime($comment['created_at'])) . ")</em></small></li>";
                        // For threading, query children if parent_id
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>