<?php
require_once '../includes/functions.php';
requireRole('admin');
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $name = $_POST['name'];
        $password = hashPassword($_POST['password']);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, name) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role, $name]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['update_role'])) {
        $id = $_POST['id'];
        $role = $_POST['role'];
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
    }
}

// Fetch users
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<h1 class="h2 mb-4">Manage Users</h1>
<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="add" value="1">
                    <div class="mb-3"><input type="text" class="form-control" name="name" placeholder="Name" required></div>
                    <div class="mb-3"><input type="text" class="form-control" name="username" placeholder="Username" required></div>
                    <div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                    <div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                    <div class="mb-3">
                        <select class="form-select" name="role" required>
                            <option value="dev">Developer</option>
                            <option value="tester">Tester</option>
                            <option value="pm">Project Manager</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus me-1"></i>Add User</button>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="badge bg-secondary"><?php echo ucfirst($user['role']); ?></span>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="dev" <?php echo $user['role']=='dev'?'selected':'';?>>Dev</option>
                            <option value="tester" <?php echo $user['role']=='tester'?'selected':'';?>>Tester</option>
                            <option value="pm" <?php echo $user['role']=='pm'?'selected':'';?>>PM</option>
                        </select>
                        <input type="hidden" name="update_role" value="1">
                    </form>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Delete?')">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete" value="1" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>