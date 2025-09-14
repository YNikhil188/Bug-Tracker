<?php
require_once 'includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && verifyPassword($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card fade-in" style="background: rgba(255, 255, 255, 0.85); border-radius: 20px; overflow: hidden;">
            <div class="card-body p-5 text-center" style="backdrop-filter: blur(5px);">
                <h2 class="card-title mb-4"><i class="fas fa-sign-in-alt me-2" style="color: var(--primary);"></i>Login</h2>
                <?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo $error; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control bg-transparent border-0 border-bottom" name="username" placeholder="Username" required>
                        </div>
                        <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control bg-transparent border-0 border-bottom" name="password" placeholder="Password" required>
                        </div>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background: var(--primary); border: none;">Login <i class="fas fa-arrow-right ms-2"></i></button>
                </form>
                <p class="mt-3 text-muted"><a href="register.php" class="text-decoration-none text-primary">Register</a> | <a href="#" class="text-decoration-none text-primary">Forgot Password?</a></p>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
<?php include 'includes/footer.php'; ?>