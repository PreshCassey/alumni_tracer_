<?php
// admin_report.php
require 'header.php';
require '../config/database.php';
if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    exit();
}

// Helper to fetch single value
function fetch_count($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

// 1) Summary counts
$totalUsers = fetch_count($conn, "SELECT COUNT(*) FROM users");
$totalEvents = fetch_count($conn, "SELECT COUNT(*) FROM events");
$totalJobs = fetch_count($conn, "SELECT COUNT(*) FROM advertisement WHERE category = 'Job' OR category LIKE '%Job%'");
$totalMessages = fetch_count($conn, "SELECT COUNT(*) FROM messages");

// 2) Recent activity (latest 8)
$recentEventsStmt = $conn->prepare("SELECT id, title, location, event_date, posted_by FROM events ORDER BY event_date DESC, id DESC LIMIT 8");
$recentEventsStmt->execute();
$recentEvents = $recentEventsStmt->fetchAll(PDO::FETCH_ASSOC);

$recentJobsStmt = $conn->prepare("SELECT id, title, advertiser, category, created_at, status FROM advertisement ORDER BY created_at DESC LIMIT 8");
$recentJobsStmt->execute();
$recentJobs = $recentJobsStmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Bar chart data: events & jobs per month (last 12 months)
$months = [];
$eventsPerMonth = [];
$jobsPerMonth = [];

for ($i = 11; $i >= 0; $i--) {
    $dt = new DateTime("first day of -{$i} month");
    $label = $dt->format('M Y'); // e.g., "Aug 2025"
    $months[] = $label;

    $start = $dt->format('Y-m-01'); // first day
    $endDt = new DateTime($start);
    $endDt->modify('+1 month');
    $end = $endDt->format('Y-m-01'); // first day next month

    // events in [start, end)
    $eventsCount = fetch_count($conn,
        "SELECT COUNT(*) FROM events WHERE event_date >= ? AND event_date < ?",
        [$start, $end]
    );
    $eventsPerMonth[] = $eventsCount;

    // jobs (advertisement.created_at) in [start, end)
    $jobsCount = fetch_count($conn,
        "SELECT COUNT(*) FROM advertisement WHERE created_at >= ? AND created_at < ?",
        [$start, $end]
    );
    $jobsPerMonth[] = $jobsCount;
}

// 4) Pie chart: advertisement categories distribution
$catStmt = $conn->prepare("SELECT category, COUNT(*) as cnt FROM advertisement GROUP BY category ORDER BY cnt DESC");
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// prepare data for JS
$js_months = json_encode($months);
$js_events = json_encode($eventsPerMonth);
$js_jobs = json_encode($jobsPerMonth);

$pie_labels = json_encode(array_map(function($r){ return $r['category'] ?: 'Unspecified'; }, $categories));
$pie_data = json_encode(array_map(function($r){ return (int)$r['cnt']; }, $categories));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Reports — Greenfield Alumni</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f9f8; }
    .card-hero { border-radius: .5rem; box-shadow: 0 6px 20px rgba(10,20,10,0.04); }
    .stat-number { font-size: 1.75rem; font-weight:700; }
    .chart-card { min-height: 360px; }
    .table-sm td, .table-sm th { padding: .4rem .6rem; }
  </style>
</head>
<body>
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="mb-0 text-success">Admin Reports</h3>
      <small class="text-muted">Live statistics for Greenfield Alumni Tracer</small>
    </div>
    <div>
      <a href="dashboard.php" class="btn btn-success">Back to Dashboard</a>
    </div>
  </div>

  <!-- Summary cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card card-hero p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-uppercase small text-muted">Total Alumni</div>
            <div class="stat-number"><?= htmlspecialchars($totalUsers) ?></div>
          </div>
          <div class="text-success fs-2">🧑‍🎓</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-hero p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-uppercase small text-muted">Total Events</div>
            <div class="stat-number"><?= htmlspecialchars($totalEvents) ?></div>
          </div>
          <div class="text-success fs-2">📅</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-hero p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-uppercase small text-muted">Total Jobs</div>
            <div class="stat-number"><?= htmlspecialchars($totalJobs) ?></div>
          </div>
          <div class="text-success fs-2">💼</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-hero p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-uppercase small text-muted">Messages</div>
            <div class="stat-number"><?= htmlspecialchars($totalMessages) ?></div>
          </div>
          <div class="text-success fs-2">✉</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts row -->
  <div class="row g-3 mb-4">
    <div class="col-lg-8">
      <div class="card chart-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">Events & Jobs — Last 12 months</h5>
          <small class="text-muted">Events (by date) vs Jobs (created_at)</small>
        </div>
        <canvas id="barChart" style="max-height:360px"></canvas>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card chart-card p-3">
        <h5 class="mb-2">Job Categories</h5>
        <canvas id="pieChart" style="max-height:360px"></canvas>
        <div class="mt-3">
          <small class="text-muted">Distribution of adverts by category</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent tables -->
  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">Latest Events</h6>
          <a href="#" class="small text-success">View all</a>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-borderless">
            <thead><tr class="text-muted"><th>#</th><th>Title</th><th>Date</th><th>Location</th></tr></thead>
            <tbody>
            <?php foreach ($recentEvents as $i => $ev): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($ev['title']) ?></td>
                <td><?= htmlspecialchars($ev['event_date']) ?></td>
                <td><?= htmlspecialchars($ev['location']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($recentEvents) === 0): ?>
              <tr><td colspan="4" class="text-muted">No recent events</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">Latest Job Adverts</h6>
          <a href="#" class="small text-success">View all</a>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-borderless">
            <thead><tr class="text-muted"><th>#</th><th>Title</th><th>Advertiser</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($recentJobs as $i => $jb): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($jb['title']) ?></td>
                <td><?= htmlspecialchars($jb['advertiser']) ?></td>
                <td>
                  <?php if (strtolower($jb['status']) === 'active'): ?>
                    <span class="badge bg-success">Active</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><?= htmlspecialchars($jb['status']) ?: 'Pending' ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($recentJobs) === 0): ?>
              <tr><td colspan="4" class="text-muted">No recent job adverts</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <footer class="mt-4 text-center text-muted small">
    Admin reports — generated: <?= date('Y-m-d H:i') ?>
  </footer>
</div>

<!-- scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Data passed from PHP
  const months = <?= $js_months ?>;
  const eventsData = <?= $js_events ?>;
  const jobsData = <?= $js_jobs ?>;
  const pieLabels = <?= $pie_labels ?>;
  const pieData = <?= $pie_data ?>;

  // Bar chart
  const ctxBar = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Events',
          data: eventsData,
          backgroundColor: 'rgba(25, 135, 84, 0.85)', // green
          borderRadius: 6
        },
        {
          label: 'Jobs',
          data: jobsData,
          backgroundColor: 'rgba(0, 200, 83, 0.5)',
          borderRadius: 6
        }
      ]
    },
    options: {
      responsive: true,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { position: 'top' },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        x: { stacked: false },
        y: { beginAtZero: true }
      }
    }
  });

  // Pie chart
  const ctxPie = document.getElementById('pieChart').getContext('2d');
  const pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: pieLabels,
      datasets: [{
        data: pieData,
        backgroundColor: [
          'rgba(25,135,84,0.9)', 'rgba(102,187,106,0.8)', 'rgba(165,214,167,0.8)',
          'rgba(200,230,201,0.8)', 'rgba(129,199,132,0.8)'
        ]
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
  });
</script>
</body>
</html>