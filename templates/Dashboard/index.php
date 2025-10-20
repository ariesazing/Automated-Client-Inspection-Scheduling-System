<?php
/**
 * @var \App\View\AppView $this
 * @var int $totalInspections
 * @var int $completedInspections
 * @var int $scheduledInspections
 * @var int $totalClients
 * @var int $activeInspectors
 * @var array $recentInspections
 * @var array $inspectionsByStatus
 * @var array $clientsByType
 * @var array $riskLevels
 * @var float $completionRate
 * @var float $scheduledRate
 */

// Prepare data for JavaScript
$inspectionStatusLabels = [];
$inspectionStatusData = [];
foreach ($inspectionsByStatus as $status) {
    $inspectionStatusLabels[] = ucfirst($status->status);
    $inspectionStatusData[] = $status->count;
}

$clientTypeLabels = [];
$clientTypeData = [];
foreach ($clientsByType as $type) {
    $clientTypeLabels[] = ucfirst($type->type);
    $clientTypeData[] = $type->count;
}

$riskLevelLabels = [];
$riskLevelData = [];
foreach ($riskLevels as $risk) {
    $riskLevelLabels[] = ucfirst($risk->risk_level);
    $riskLevelData[] = $risk->count;
}
?>

<!-- Add data for JavaScript -->
<script>
window.dashboardData = {
    inspectionStatusLabels: <?= json_encode($inspectionStatusLabels) ?>,
    inspectionStatusData: <?= json_encode($inspectionStatusData) ?>,
    clientTypeLabels: <?= json_encode($clientTypeLabels) ?>,
    clientTypeData: <?= json_encode($clientTypeData) ?>,
    riskLevelLabels: <?= json_encode($riskLevelLabels) ?>,
    riskLevelData: <?= json_encode($riskLevelData) ?>
};
</script>

<style>
/* Full width container */
.container-fluid {
    padding-left: 20px;
    padding-right: 20px;
    max-width: 100%;
}

.dashboard-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    margin-bottom: 20px;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.stat-card {
    color: white;
    border-radius: 12px;
    padding: 25px 20px;
    height: 100%;
    position: relative;
}

.stat-card.primary { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); }
.stat-card.success { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); }
.stat-card.warning { background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); }
.stat-card.info { background: linear-gradient(135deg, #2980b9 0%, #3498db 100%); }

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 8px 0 0 0;
}

.stat-icon {
    font-size: 3rem;
    opacity: 0.8;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
}

.progress-thin {
    height: 6px;
    border-radius: 3px;
    margin-top: 15px;
}

.badge-sm {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.text-muted {
    color: #6c757d !important;
}

/* Header styling */
.page-header-main {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 25px;
    border-left: 5px solid #3498db;
    text-align: center;
}

.page-header-main h1 {
    color: white;
    margin: 0 0 8px 0;
    font-weight: 800;
    font-size: 2.2rem;
}

.page-header-main .subtitle {
    color: rgba(255,255,255,0.95);
    margin: 0;
    font-size: 1.2rem;
    font-weight: 400;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .page-header-main {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .page-header-main h1 {
        font-size: 1.8rem;
    }
    
    .page-header-main .subtitle {
        font-size: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .stat-icon {
        font-size: 2.5rem;
    }
}

/* Equal height cards for charts */
.chart-container {
    height: 100%;
}

.chart-container .dashboard-card {
    height: 100%;
}

/* Table responsive fixes */
.table-responsive {
    border-radius: 0 0 12px 12px;
}

/* Professional color scheme */
.bg-light {
    background-color: #f8f9fa !important;
}

.badge-primary { background-color: #3498db; }
.badge-success { background-color: #27ae60; }
.badge-warning { background-color: #e67e22; }
.badge-info { background-color: #2980b9; }
.badge-danger { background-color: #e74c3c; }
.badge-secondary { background-color: #95a5a6; }
</style>

<div class="container-fluid">
    <!-- Centered Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-header-main">
                <h1>
                    <i class="fas fa-fire-extinguisher mr-3"></i>
                    BFP Inspection Scheduling System
                </h1>
                <p class="subtitle">
                    Comprehensive Building's Fire Safety Inspection Scheduling
                </p>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards - Full Width -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card primary dashboard-card position-relative">
                <div class="position-relative z-1">
                    <h2 class="stat-number"><?= $this->Number->format($totalInspections) ?></h2>
                    <p class="stat-label">Total Inspections</p>
                    <div class="progress progress-thin bg-white bg-opacity-25">
                        <div class="progress-bar bg-white" style="width: 100%"></div>
                    </div>
                </div>
                <i class="fas fa-clipboard-list stat-icon"></i>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card success dashboard-card position-relative">
                <div class="position-relative z-1">
                    <h2 class="stat-number"><?= $this->Number->format($completedInspections) ?></h2>
                    <p class="stat-label">Completed</p>
                    <div class="progress progress-thin bg-white bg-opacity-25">
                        <div class="progress-bar bg-white" style="width: <?= $completionRate ?>%"></div>
                    </div>
                </div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card warning dashboard-card position-relative">
                <div class="position-relative z-1">
                    <h2 class="stat-number"><?= $this->Number->format($scheduledInspections) ?></h2>
                    <p class="stat-label">Scheduled</p>
                    <div class="progress progress-thin bg-white bg-opacity-25">
                        <div class="progress-bar bg-white" style="width: <?= $scheduledRate ?>%"></div>
                    </div>
                </div>
                <i class="fas fa-calendar-alt stat-icon"></i>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card info dashboard-card position-relative">
                <div class="position-relative z-1">
                    <h2 class="stat-number"><?= $this->Number->format($totalClients) ?></h2>
                    <p class="stat-label">Total Clients</p>
                    <div class="progress progress-thin bg-white bg-opacity-25">
                        <div class="progress-bar bg-white" style="width: 100%"></div>
                    </div>
                </div>
                <i class="fas fa-building stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Row - Equal Height -->
    <div class="row">
        <!-- Inspection Status Chart -->
        <div class="col-xl-6 col-lg-6 col-md-12 mb-4 chart-container">
            <div class="dashboard-card bg-white h-100 d-flex flex-column">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h5 class="card-title mb-0 text-dark font-weight-bold">
                        <i class="fas fa-chart-pie text-primary mr-2"></i>
                        Inspection Status
                    </h5>
                </div>
                <div class="card-body flex-grow-1 d-flex align-items-center">
                    <canvas id="inspectionStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Client Types Chart -->
        <div class="col-xl-6 col-lg-6 col-md-12 mb-4 chart-container">
            <div class="dashboard-card bg-white h-100 d-flex flex-column">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h5 class="card-title mb-0 text-dark font-weight-bold">
                        <i class="fas fa-chart-bar text-success mr-2"></i>
                        Client Types
                    </h5>
                </div>
                <div class="card-body flex-grow-1 d-flex align-items-center">
                    <canvas id="clientTypesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Risk Levels and Analytics - Equal Height -->
    <div class="row">
        <!-- Risk Levels -->
        <div class="col-xl-4 col-lg-4 col-md-12 mb-4 chart-container">
            <div class="dashboard-card bg-white h-100 d-flex flex-column">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h5 class="card-title mb-0 text-dark font-weight-bold">
                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                        Risk Distribution
                    </h5>
                </div>
                <div class="card-body flex-grow-1 d-flex align-items-center">
                    <canvas id="riskLevelsChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- System Metrics -->
        <div class="col-xl-8 col-lg-8 col-md-12 mb-4">
            <div class="dashboard-card bg-white h-100">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h5 class="card-title mb-0 text-dark font-weight-bold">
                        <i class="fas fa-tachometer-alt text-info mr-2"></i>
                        Performance Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-0 text-dark font-weight-bold">Active Inspectors</h6>
                                    <small class="text-muted">Currently available</small>
                                </div>
                                <span class="badge badge-primary badge-pill" style="font-size: 1.1rem; min-width: 50px; text-align: center;"><?= $activeInspectors ?></span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-0 text-dark font-weight-bold">Completion Rate</h6>
                                    <small class="text-muted">Overall efficiency</small>
                                </div>
                                <span class="badge badge-success badge-pill" style="font-size: 1.1rem; min-width: 50px; text-align: center;"><?= $completionRate ?>%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-0 text-dark font-weight-bold">Scheduled Rate</h6>
                                    <small class="text-muted">Planning efficiency</small>
                                </div>
                                <span class="badge badge-warning badge-pill" style="font-size: 1.1rem; min-width: 50px; text-align: center;"><?= $scheduledRate ?>%</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-0 text-dark font-weight-bold">Total Clients</h6>
                                    <small class="text-muted">Registered establishments</small>
                                </div>
                                <span class="badge badge-info badge-pill" style="font-size: 1.1rem; min-width: 50px; text-align: center;"><?= $totalClients ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inspections - Full Width -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card bg-white">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-dark font-weight-bold">
                        <i class="fas fa-history text-secondary mr-2"></i>
                        Recent Inspections
                    </h5>
                    <?= $this->Html->link(
                        'View All <i class="fas fa-arrow-right ml-1"></i>',
                        ['controller' => 'Inspections', 'action' => 'index'],
                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]
                    ) ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Establishment</th>
                                    <th class="border-0">Inspector</th>
                                    <th class="border-0">Scheduled Date</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Risk Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentInspections)): ?>
                                    <?php foreach ($recentInspections as $inspection): ?>
                                        <tr>
                                            <td class="font-weight-bold text-dark">
                                                <?= h($inspection->client->establishment_name ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <?= h($inspection->inspector->name ?? 'Unassigned') ?>
                                            </td>
                                            <td>
                                                <?php if ($inspection->scheduled_date): ?>
                                                    <?= h($inspection->scheduled_date->format('M j, Y')) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not scheduled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= 
                                                    $inspection->status === 'completed' ? 'success' : 
                                                    ($inspection->status === 'scheduled' ? 'warning' : 
                                                    ($inspection->status === 'ongoing' ? 'info' : 'secondary')) 
                                                ?> badge-sm">
                                                    <?= ucfirst($inspection->status ?? 'pending') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= 
                                                    ($inspection->client->risk_level ?? 'low') === 'high' ? 'danger' : 
                                                    (($inspection->client->risk_level ?? 'low') === 'medium' ? 'warning' : 'success') 
                                                ?> badge-sm">
                                                    <?= ucfirst($inspection->client->risk_level ?? 'low') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No recent inspections found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include external JavaScript files -->
<?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
<?= $this->Html->script('dashboard') ?>
