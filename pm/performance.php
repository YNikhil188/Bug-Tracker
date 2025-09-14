<?php
require_once '../includes/functions.php';
requireRole('pm');
$managerId = $_SESSION['user_id'];
include '../includes/header.php';

// Performance: Bugs resolved per user
$stmt = $pdo->prepare("SELECT u.name, u.role, COUNT(b.id) as resolved_count FROM users u LEFT JOIN bugs b ON u.id = b.assigned_to AND b.status = 'resolved' JOIN projects p ON b.project_id = p.id WHERE p.manager_id = ? GROUP BY u.id ORDER BY resolved_count DESC");
$stmt->execute([$managerId]);
$performance = $stmt->fetchAll();

$chartData = json_encode([
    'labels' => array_column($performance, 'name'),
    'datasets' => [['label' => 'Resolved Bugs', 'data' => array_column($performance, 'resolved_count'), 'backgroundColor' => 'rgba(75, 192, 192, 0.2)', 'borderColor' => 'rgba(75, 192, 192, 1)']]
]);
?>
<h1 class="h2 mb-4">Performance Analytics</h1>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5>Team Performance</h5></div>
            <div class="card-body">
                <canvas id="perfChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Top Performers</h5></div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <?php foreach (array_slice($performance, 0, 3) as $perf): ?>
                    <li><strong><?php echo htmlspecialchars($perf['name']); ?></strong> (<?php echo $perf['resolved_count']; ?> bugs)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    initCharts([{
        canvasId: 'perfChart',
        options: {
            type: 'bar',
            data: <?php echo $chartData; ?>,
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        }
    }]);
</script>
<?php include '../includes/footer.php'; ?>