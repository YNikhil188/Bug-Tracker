<?php
require_once 'includes/functions.php';
requireLogin();
$user = getUserById($pdo, $_SESSION['user_id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $_SESSION['user_id']]);
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $pic = uploadFile($_FILES['profile_pic'], 'assets/uploads/');
        if ($pic) {
            $stmt = $pdo->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->execute([$pic, $_SESSION['user_id']]);
        }
    }
    $user = getUserById($pdo, $_SESSION['user_id']); // Refresh
}
include 'includes/header.php';
?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card fade-in">
            <div class="card-body text-center">
                <img src="assets/uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p class="text-muted"><?php echo ucfirst($user['role']); ?></p>
                <p><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($user['email']); ?></p>
                <form method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_pic" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        <!-- Activity History (simple query example) -->
        <div class="card mt-4">
            <div class="card-header"><h5>Activity History</h5></div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <?php
                    // Example: Recent bugs/comments for user
                    $stmt = $pdo->prepare("SELECT 'Bug Reported' as action, created_at FROM bugs WHERE reporter_id = ? UNION SELECT 'Comment Added' as action, created_at FROM comments WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
                    foreach ($stmt->fetchAll() as $act) {
                        echo "<li><small>{$act['action']} - " . date('M j, Y', strtotime($act['created_at'])) . "</small></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>