<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en" class="light-theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bug Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-bug me-2"></i>Bug Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="../profile.php"><i class="fas fa-user me-1"></i>Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                        <button id="themeToggle" class="btn btn-outline-light ms-2" style="border-radius: 50%; width: 40px; height: 40px;">
                            <i class="fas fa-moon"></i>
                        </button>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <?php if (isLoggedIn()): $role = $_SESSION['role']; ?>
                            <?php if ($role == 'admin'): ?>
                                <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="../admin/users.php"><i class="fas fa-users me-2"></i>Users</a></li>
                                <li class="nav-item"><a class="nav-link" href="../admin/projects.php"><i class="fas fa-project-diagram me-2"></i>Projects</a></li>
                            <?php elseif ($role == 'pm'): ?>
                                <li class="nav-item"><a class="nav-link" href="../pm/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="../pm/projects.php"><i class="fas fa-project-diagram me-2"></i>Projects</a></li>
                                <li class="nav-item"><a class="nav-link" href="../pm/bugs.php"><i class="fas fa-bug me-2"></i>Bugs</a></li>
                                <li class="nav-item"><a class="nav-link" href="../pm/performance.php"><i class="fas fa-chart-bar me-2"></i>Performance</a></li>
                            <?php elseif ($role == 'dev'): ?>
                                <li class="nav-item"><a class="nav-link" href="../dev/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="../dev/bugs.php"><i class="fas fa-bug me-2"></i>Bugs</a></li>
                            <?php elseif ($role == 'tester'): ?>
                                <li class="nav-item"><a class="nav-link" href="../tester/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="../tester/report_bug.php"><i class="fas fa-plus-circle me-2"></i>Report Bug</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">