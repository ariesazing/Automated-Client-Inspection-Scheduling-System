$(function () {
    initializeDashboard();

    // Initialize dashboard charts and interactions
    function initializeDashboard() {
        initializeDashboardCharts();
        initializeDashboardInteractions();
    }

    function initializeDashboardCharts() {
        // Inspection Status Chart
        const inspectionStatusCtx = document.getElementById('inspectionStatusChart');
        if (inspectionStatusCtx) {
            const inspectionStatusChart = new Chart(inspectionStatusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: window.dashboardData?.inspectionStatusLabels || [],
                    datasets: [{
                        data: window.dashboardData?.inspectionStatusData || [],
                        backgroundColor: [
                            '#27ae60', '#e67e22', '#3498db', '#e74c3c', '#95a5a6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }

        // Client Types Chart
        const clientTypesCtx = document.getElementById('clientTypesChart');
        if (clientTypesCtx) {
            const clientTypesChart = new Chart(clientTypesCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: window.dashboardData?.clientTypeLabels || [],
                    datasets: [{
                        label: 'Number of Clients',
                        data: window.dashboardData?.clientTypeData || [],
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: '#3498db',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Risk Levels Chart
        const riskLevelsCtx = document.getElementById('riskLevelsChart');
        if (riskLevelsCtx) {
            const riskLevelsChart = new Chart(riskLevelsCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: window.dashboardData?.riskLevelLabels || [],
                    datasets: [{
                        data: window.dashboardData?.riskLevelData || [],
                        backgroundColor: [
                            '#27ae60', '#e67e22', '#e74c3c'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }
    }

    function initializeDashboardInteractions() {
        // Add enhanced hover effects to cards
        $('.dashboard-card').each(function () {
            const $card = $(this);
            $card.css('transition', 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)');

            $card.on('mouseenter', function () {
                $card.css({
                    'transform': 'translateY(-8px)',
                    'box-shadow': '0 12px 30px rgba(0,0,0,0.15)'
                });
            });

            $card.on('mouseleave', function () {
                $card.css({
                    'transform': 'translateY(0)',
                    'box-shadow': '0 4px 12px rgba(0,0,0,0.08)'
                });
            });
        });

        // Add loading animation to stat cards
        $('.stat-card').each(function (index) {
            const $card = $(this);
            $card.css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            });

            setTimeout(function () {
                $card.css({
                    'transition': 'all 0.6s ease',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, index * 150);
        });

        // Auto-refresh data every 5 minutes
        setInterval(function () {
            console.log('Dashboard data refresh triggered');
            refreshDashboardData();
        }, 300000);
    }

    function refreshDashboardData() {
        // Check if we have an inspector ID
        const inspectorId = window.currentInspectorId;

        // Prepare data object, add inspector_id only if it exists
        const requestData = {};
        if (inspectorId) {
            requestData.inspector_id = inspectorId;
        }

        $.ajax({
            url: BASE_URL + '/api/Dashboard/getData',
            type: 'GET',
            dataType: 'json',
            data: requestData  // Send the data object
        })
            .done(function (data) {
                if (data && data.status === 'success') {
                    updateDashboardCards(data.data);
                    updateDashboardCharts(data.data);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error('Error refreshing dashboard data:', errorThrown);
            });
    }

    function updateDashboardCards(data) {
        if (data.totalInspections) {
            $('.stat-card.primary .stat-number').text(formatNumber(data.totalInspections));
        }
        if (data.completedInspections) {
            $('.stat-card.success .stat-number').text(formatNumber(data.completedInspections));
        }
        if (data.scheduledInspections) {
            $('.stat-card.warning .stat-number').text(formatNumber(data.scheduledInspections));
        }
        if (data.totalClients) {
            $('.stat-card.info .stat-number').text(formatNumber(data.totalClients));
        }
        if (data.activeInspectors) {
            $('.badge-primary.badge-pill').first().text(data.activeInspectors);
        }
    }

    function updateDashboardCharts(data) {
        // This would require destroying and recreating charts with new data
        // Implementation depends on your API response structure
        console.log('Updating charts with new data:', data);
    }
});

// Utility function to format numbers
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}

// Smooth scrolling function
function smoothScrollTo(element) {
    $('html, body').animate({
        scrollTop: $(element).offset().top
    }, 500);
}

// Export functions for potential reuse
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeDashboard: function () { $(initializeDashboard); },
        formatNumber: formatNumber,
        smoothScrollTo: smoothScrollTo
    };
}