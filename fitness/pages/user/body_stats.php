<?php
// ============================================
//  ELEV8 FITNESS — Body Stats Page
// ============================================
$pageTitle = 'Body Stats — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight  = (float)($_POST['weight']      ?? 0);
    $fat     = (float)($_POST['body_fat']    ?? 0);
    $muscle  = (float)($_POST['muscle_mass'] ?? 0);

    if ($weight > 0) {
        Database::execute(
            "INSERT INTO body_stats (user_id, weight, body_fat, muscle_mass) VALUES (?, ?, ?, ?)",
            [$uid, $weight, $fat ?: null, $muscle ?: null]
        );
        $success = 'Stats recorded successfully!';
    } else {
        $error = 'Weight is required.';
    }
}

// Fetch history
$history = Database::query(
    "SELECT * FROM body_stats WHERE user_id = ? ORDER BY recorded_at DESC LIMIT 12",
    [$uid]
);
$latest = $history[0] ?? null;

// Chart data
$chartData = array_reverse(array_slice($history, 0, 10));
$chartLabels  = array_map(fn($r) => date('d/m', strtotime($r['recorded_at'])), $chartData);
$chartWeights = array_map(fn($r) => (float)$r['weight'], $chartData);
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Body Stats</span></div>
        <h1>Body <em>Statistics</em></h1>
        <p>Track your physical progress over time. Data tells the truth.</p>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="grid-2">
        <!-- ── LOG FORM ── -->
        <div class="card">
            <h3 style="font-family:var(--font-display); margin-bottom:24px;">Log Today's Stats</h3>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Weight (kg) *</label>
                    <input type="number" name="weight" class="form-control" step="0.1" min="0" placeholder="e.g. 72.5" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Body Fat (%)</label>
                    <input type="number" name="body_fat" class="form-control" step="0.1" min="0" max="100" placeholder="e.g. 18.5">
                </div>
                <div class="form-group">
                    <label class="form-label">Muscle Mass (kg)</label>
                    <input type="number" name="muscle_mass" class="form-control" step="0.1" min="0" placeholder="e.g. 38.0">
                </div>
                <button type="submit" class="btn btn-gold" style="width:100%; justify-content:center;">
                    <i class="fa-solid fa-plus"></i> Record Stats
                </button>
            </form>
        </div>

        <!-- ── LATEST ── -->
        <div>
            <?php if ($latest): ?>
            <div class="card card-gold mb-3">
                <h4 style="font-family:var(--font-display); color:var(--text-3); font-size:1rem; margin-bottom:20px; text-transform:uppercase; letter-spacing:0.1em;">Latest Reading</h4>
                <div class="grid-3" style="gap:16px; text-align:center;">
                    <div>
                        <div style="font-family:var(--font-display);font-size:2.5rem;color:var(--gold);line-height:1;"><?= $latest['weight'] ?></div>
                        <div class="stat-label mt-1">kg</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display);font-size:2.5rem;color:var(--gold);line-height:1;"><?= $latest['body_fat'] ?? '—' ?></div>
                        <div class="stat-label mt-1">% fat</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display);font-size:2.5rem;color:var(--gold);line-height:1;"><?= $latest['muscle_mass'] ?? '—' ?></div>
                        <div class="stat-label mt-1">kg muscle</div>
                    </div>
                </div>
                <p class="text-muted mt-3" style="font-size:12px; text-align:center;">
                    <?= date('d M Y H:i', strtotime($latest['recorded_at'])) ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Weight Chart -->
            <?php if (count($chartData) > 1): ?>
            <div class="card">
                <h4 style="font-family:var(--font-display); margin-bottom:16px;">Weight Trend</h4>
                <div class="chart-container">
                    <canvas id="weightChart"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── HISTORY TABLE ── -->
    <?php if (!empty($history)): ?>
    <div class="card mt-4">
        <h3 style="font-family:var(--font-display); margin-bottom:20px;">History</h3>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Weight (kg)</th>
                        <th>Body Fat (%)</th>
                        <th>Muscle Mass (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $r): ?>
                    <tr>
                        <td style="color:var(--text-1);"><?= date('d M Y H:i', strtotime($r['recorded_at'])) ?></td>
                        <td><?= $r['weight'] ?></td>
                        <td><?= $r['body_fat'] ?? '—' ?></td>
                        <td><?= $r['muscle_mass'] ?? '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$extraScript = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById("weightChart");
if (ctx) {
    new Chart(ctx, {
        type: "line",
        data: {
            labels: ' . json_encode($chartLabels) . ',
            datasets: [{
                label: "Weight (kg)",
                data: ' . json_encode($chartWeights) . ',
                borderColor: "#c9a84c",
                backgroundColor: "rgba(201,168,76,0.08)",
                borderWidth: 2,
                pointBackgroundColor: "#c9a84c",
                pointRadius: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: "rgba(255,255,255,0.04)" }, ticks: { color: "#5a5854", font: { size: 11 } } },
                y: { grid: { color: "rgba(255,255,255,0.04)" }, ticks: { color: "#5a5854", font: { size: 11 } } }
            }
        }
    });
}
</script>';
require_once __DIR__ . '/../../includes/footer.php';
?>
