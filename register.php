<?php
require_once 'includes/functions.php';
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $name = $_POST['name'];
    if (strlen($password) < 6) {
        $error = 'Password too short';
    } else {
        $hashed = hashPassword($password);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, name) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed, $email, $role, $name]);
            $success = 'Registered! <a href="login.php">Login</a>';
        } catch (PDOException $e) {
            $error = 'Username/Email already exists';
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-body">
                <h2 class="card-title text-center"><i class="fas fa-user-plus me-2"></i>Register</h2>
                <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="dev">Developer</option>
                            <option value="tester">Tester</option>
                            <option value="pm">Project Manager</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3"><a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>