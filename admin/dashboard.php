<?php
require_once '../includes/functions.php';
requireRole('admin');
include '../includes/header.php';
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalBugs = $pdo->query("SELECT COUNT(*) FROM bugs")->fetchColumn();
$openBugs = $pdo->query("SELECT COUNT(*) FROM bugs WHERE status = 'open'")->fetchColumn();

$bugStatusData = json_encode([
    'labels' => ['Open', 'In Progress', 'Resolved', 'Closed'],
    'datasets' => [
        [
            'data' => [
                $openBugs,
                $pdo->query("SELECT COUNT(*) FROM bugs WHERE status='in_progress'")->fetchColumn(),
                $pdo->query("SELECT COUNT(*) FROM bugs WHERE status='resolved'")->fetchColumn(),
                $pdo->query("SELECT COUNT(*) FROM bugs WHERE status='closed'")->fetchColumn()
            ],
            'backgroundColor' => ['#007bff', '#ffc107', '#28a745', '#6c757d'],
            'borderWidth' => 2
        ]
    ]
]);

?>
<h1 class="h2 mb-4 text-primary">Admin Dashboard</h1>
<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #6b48ff, #8e7cff); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Total Users</h5>
                <h2 class="display-6"><?php echo $totalUsers; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #ff6b6b, #ff8787); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-project-diagram fa-3x mb-3"></i>
                <h5>Total Projects</h5>
                <h2 class="display-6"><?php echo $totalProjects; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #ffc107, #ffd54f); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>Open Bugs</h5>
                <h2 class="display-6"><?php echo $openBugs; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-gradient" style="background: linear-gradient(135deg, #28a745, #66bb6a); animation: pulse 2s infinite;">
            <div class="card-body text-center">
                <i class="fas fa-bug fa-3x mb-3"></i>
                <h5>Total Bugs</h5>
                <h2 class="display-6"><?php echo $totalBugs; ?></h2>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white"><h5>Bug Status Overview</h5></div>
            <div class="card-body p-4">
                <canvas id="bugChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white"><h5>Recent Activities</h5></div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <?php
                    $stmt = $pdo->query("SELECT u.name, b.title, b.created_at FROM bugs b JOIN users u ON b.reporter_id = u.id ORDER BY b.created_at DESC LIMIT 5");
                    foreach ($stmt as $row) {
                        echo "<li class='mb-2'><strong class='text-primary'>{$row['name']}</strong> reported <span class='text-secondary'>'{$row['title']}'</span> on " . date('M j, Y', strtotime($row['created_at'])) . "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    initCharts([{
        canvasId: 'bugChart',
        options: {
            type: 'doughnut',
            data: <?php echo $bugStatusData; ?>,
            options: { responsive: true, maintainAspectRatio: false, plugins: { doughnutlabel: { labels: [{ text: 'Total', font: { size: 20 } }] } } }
        }
    }]);
</script>
<style>
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
<?php include '../includes/footer.php'; ?>