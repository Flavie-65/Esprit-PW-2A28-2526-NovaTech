<?php
/** @var array{total: int, available: int, assigned: int, maintenance: int, damaged: int} $equipmentStats */
/** @var array{total: int, active: int, returned: int} $assignmentStats */
/** @var array{average: int, risky: int, critical: int, top_risks: list<array{id: int, name: string, score: int, level: string, prediction: string, reason: string, assignment_count: int, active_days: int}>} $healthSummary */
$pageTitle = 'Dashboard';
$statusLabels = ['Available', 'Assigned', 'Maintenance', 'Damaged'];
$statusValues = [
    (int) ($equipmentStats['available'] ?? 0),
    (int) ($equipmentStats['assigned'] ?? 0),
    (int) ($equipmentStats['maintenance'] ?? 0),
    (int) ($equipmentStats['damaged'] ?? 0),
];
ob_start();
?>
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
    .kpi-card { background: #fff; border: 1px solid #e3e9e6; border-radius: 12px; padding: 1.1rem; box-shadow: 0 10px 28px rgba(35, 49, 45, 0.08); transition: transform 160ms ease, box-shadow 160ms ease; }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 16px 34px rgba(35, 49, 45, 0.12); }
    .kpi-label { margin: 0 0 0.4rem; color: #64716d; font-size: 0.86rem; font-weight: 700; text-transform: uppercase; }
    .kpi-value { margin: 0; color: #1D9E75; font-size: 2rem; font-weight: 850; }
    .dashboard-panels { display: grid; grid-template-columns: minmax(280px, 420px) 1fr; gap: 1rem; align-items: start; }
    .chart-card { background: #fff; border: 1px solid #e3e9e6; border-radius: 12px; padding: 1.2rem; box-shadow: 0 10px 28px rgba(35, 49, 45, 0.08); }
    .chart-card h3 { margin: 0 0 1rem; color: #1D9E75; }
    .summary-list { display: grid; gap: 0.75rem; }
    .summary-row { display: flex; justify-content: space-between; gap: 1rem; padding: 0.8rem 0; border-bottom: 1px solid #e3e9e6; }
    .summary-row:last-child { border-bottom: 0; }
    .summary-row span { color: #64716d; }
    .summary-row strong { color: #24312d; }
    .ai-health-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin: 1.25rem 0; }
    .ai-card { background: linear-gradient(135deg, #ffffff 0%, #f8fffc 100%); border: 1px solid #dce9e4; border-radius: 12px; padding: 1rem; box-shadow: 0 12px 30px rgba(29, 158, 117, 0.1); }
    .ai-card .kpi-value { color: #6D5BD0; }
    .risk-list { display: grid; gap: 0.75rem; }
    .risk-item { display: grid; grid-template-columns: 72px 1fr; gap: 0.8rem; align-items: start; padding: 0.8rem; border: 1px solid #e3e9e6; border-radius: 10px; background: #fff; }
    .score-ring { width: 58px; height: 58px; border-radius: 50%; display: grid; place-items: center; color: #fff; font-weight: 850; background: #1D9E75; }
    .score-ring.watch { background: #F5A524; }
    .score-ring.risk { background: #D97706; }
    .score-ring.critical { background: #D64545; }
    .risk-item h4 { margin: 0 0 0.25rem; color: #24312d; }
    .risk-item p { margin: 0.15rem 0; color: #64716d; }
    @media (max-width: 980px) {
        .dashboard-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dashboard-panels { grid-template-columns: 1fr; }
        .ai-health-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <h2>Dashboard</h2>
</div>

<section class="dashboard-grid">
    <article class="kpi-card">
        <p class="kpi-label">Total Equipment</p>
        <p class="kpi-value"><?= (int) ($equipmentStats['total'] ?? 0) ?></p>
    </article>
    <article class="kpi-card">
        <p class="kpi-label">Available</p>
        <p class="kpi-value"><?= (int) ($equipmentStats['available'] ?? 0) ?></p>
    </article>
    <article class="kpi-card">
        <p class="kpi-label">Assigned</p>
        <p class="kpi-value"><?= (int) ($equipmentStats['assigned'] ?? 0) ?></p>
    </article>
    <article class="kpi-card">
        <p class="kpi-label">Assignments</p>
        <p class="kpi-value"><?= (int) ($assignmentStats['total'] ?? 0) ?></p>
    </article>
</section>

<section class="ai-health-grid">
    <article class="ai-card">
        <p class="kpi-label">AI Avg Health</p>
        <p class="kpi-value"><?= (int) ($healthSummary['average'] ?? 0) ?>%</p>
    </article>
    <article class="ai-card">
        <p class="kpi-label">Risk Equipment</p>
        <p class="kpi-value"><?= (int) ($healthSummary['risky'] ?? 0) ?></p>
    </article>
    <article class="ai-card">
        <p class="kpi-label">Critical Equipment</p>
        <p class="kpi-value"><?= (int) ($healthSummary['critical'] ?? 0) ?></p>
    </article>
</section>

<section class="dashboard-panels">
    <article class="chart-card">
        <h3>Equipment Status</h3>
        <canvas id="equipmentStatusChart" width="360" height="360"></canvas>
    </article>
    <article class="chart-card">
        <h3>AI Maintenance Risk</h3>
        <?php if (($healthSummary['top_risks'] ?? []) !== []) : ?>
            <div class="risk-list">
                <?php foreach ($healthSummary['top_risks'] as $risk) : ?>
                    <?php $levelClass = strtolower($risk['level']); ?>
                    <div class="risk-item">
                        <div class="score-ring <?= htmlspecialchars($levelClass, ENT_QUOTES, 'UTF-8') ?>">
                            <?= (int) $risk['score'] ?>%
                        </div>
                        <div>
                            <h4><?= htmlspecialchars($risk['name'], ENT_QUOTES, 'UTF-8') ?></h4>
                            <p><strong><?= htmlspecialchars($risk['level'], ENT_QUOTES, 'UTF-8') ?></strong> - <?= htmlspecialchars($risk['prediction'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><?= htmlspecialchars($risk['reason'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>No equipment data available for AI health scoring.</p>
        <?php endif; ?>
    </article>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dashboardStatusLabels = <?= json_encode($statusLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const dashboardStatusValues = <?= json_encode($statusValues, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    new Chart(document.getElementById('equipmentStatusChart'), {
        type: 'pie',
        data: {
            labels: dashboardStatusLabels,
            datasets: [{
                data: dashboardStatusValues,
                backgroundColor: ['#1D9E75', '#6D5BD0', '#F5A524', '#D64545'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
