<?php
session_start();
require_once '../config/database.php';

// إنشاء اتصال بالقاعدة
$database = new Database();
$db = $database->getConnection();

// استعلامات بسيطة للإحصائيات (سنضيف البيانات لاحقاً، حالياً ستعرض أصفار إذا الجدول فارغ)
function getCount($db, $table) {
    $query = "SELECT COUNT(*) as total FROM " . $table;
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

$users_count = getCount($db, 'users');
$categories_count = getCount($db, 'categories');
$abayas_count = getCount($db, 'abayas');
$orders_count = getCount($db, 'orders');

include '../includes/header.php';
?>

<div class="container-fluid py-4 animate-fade-in">
    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card p-4 bg-primary-gradient border-0 text-white overflow-hidden position-relative h-100">
                <div class="position-relative z-index-2">
                    <h2 class="fw-900 mb-2">أهلاً بك ي قلبي، <?php echo $_SESSION['admin_name'] ?? 'المدير العام'; ?> ✨</h2>
                    <p class="opacity-75 mb-4">إليك نظرة سريعة على ما يحدث في متجرك اليوم. الأمور تبدو رائعة!</p>
                    <div class="d-flex gap-2">
                        <a href="abayas.php" class="btn btn-white btn-sm rounded-pill px-4 fw-bold shadow-sm">إضافة منتج</a>
                        <a href="orders.php" class="btn btn-glass-white btn-sm rounded-pill px-4 fw-bold">تحميل التقارير</a>
                    </div>
                </div>
                <!-- Abstract Background Shapes -->
                <div class="position-absolute top-0 end-0 p-5 opacity-25">
                    <i class="fas fa-crown fa-8x rotate-12"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold mb-3">حالة النظام</h5>
                <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded-4 bg-soft-success">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dot bg-success"></div>
                        <span class="small fw-bold text-white">الموقع متصل</span>
                    </div>
                    <i class="fas fa-check-circle text-success"></i>
                </div>
                <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-soft-primary">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dot bg-primary"></div>
                        <span class="small fw-bold text-white">وضع الصيانة: مغلق</span>
                    </div>
                    <i class="fas fa-toggle-off text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Revenue Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-success">
                        <i class="fas fa-money-bill-trend-up fa-xl text-success"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">+15.4%</span>
                </div>
                <div class="stats-label text-muted mb-1">إجمالي المبيعات</div>
                <h3 class="stats-number font-jost text-white">
                    <?php 
                        $sales_query = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'";
                        $sales_stmt = $db->prepare($sales_query);
                        $sales_stmt->execute();
                        $sales_total = $sales_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
                        echo number_format($sales_total, 0);
                    ?> <span class="fs-6">ر.س</span>
                </h3>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-primary">
                        <i class="fas fa-shopping-bag fa-xl text-primary"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-bold">+8%</span>
                </div>
                <div class="stats-label text-muted mb-1">الطلبات الكلية</div>
                <h3 class="stats-number font-jost text-white"><?php echo $orders_count; ?></h3>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-info">
                        <i class="fas fa-users fa-xl text-info"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill fw-bold">+12%</span>
                </div>
                <div class="stats-label text-muted mb-1">العملاء النشطون</div>
                <h3 class="stats-number font-jost text-white"><?php echo $users_count; ?></h3>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-warning">
                        <i class="fas fa-tags fa-xl text-warning"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill fw-bold">جديد</span>
                </div>
                <div class="stats-label text-muted mb-1">إجمالي الموديلات</div>
                <h3 class="stats-number font-jost text-white"><?php echo $abayas_count; ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <!-- Sales Analysis -->
        <div class="col-lg-8">
            <div class="card p-4 h-100">
                <div class="card-header bg-transparent border-0 p-0 mb-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">تحليل المبيعات والنمو</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">هذا الشهر</button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="#">آخر 7 أيام</a></li>
                            <li><a class="dropdown-item" href="#">الشهور الماضية</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Categories Distribution -->
        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <div class="card-header bg-transparent border-0 p-0 mb-4">
                    <h5 class="mb-0 fw-bold">توزيع التصنيفات</h5>
                </div>
                <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-4">
                    <div class="small d-flex justify-content-between mb-2">
                        <span class="text-muted">أكثر التصنيفات طلباً:</span>
                        <span class="text-primary fw-bold">عبايات سوداء</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row">
        <div class="col-12">
            <div class="card p-0 overflow-hidden">
                <div class="card-header border-0 bg-transparent d-flex justify-content-between align-items-center p-4">
                    <h5 class="mb-0 fw-bold text-white">آخر الطلبات المباشرة</h5>
                    <a href="orders.php" class="btn btn-primary btn-sm px-4 rounded-pill">مشاهدة الجميع</a>
                </div>
                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">رقم الطلب</th>
                                <th>العميل</th>
                                <th>التاريخ</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Fetch recent orders for real data
                            $recent_query = "SELECT orders.*, users.full_name FROM orders JOIN users ON orders.user_id = users.id ORDER BY created_at DESC LIMIT 5";
                            $recent_stmt = $db->prepare($recent_query);
                            $recent_stmt->execute();
                            $recent_orders = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if(count($recent_orders) > 0):
                                foreach($recent_orders as $order):
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold font-jost text-primary">#ORD-<?php echo $order['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                <?php echo mb_substr($order['full_name'], 0, 1); ?>
                                            </div>
                                            <?php echo htmlspecialchars($order['full_name']); ?>
                                        </div>
                                    </td>
                                    <td class="small text-muted font-jost"><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                    <td class="fw-bold font-jost"><?php echo number_format($order['total_amount'], 2); ?> ر.س</td>
                                    <td>
                                        <?php 
                                            $s = $order['status'];
                                            $class = ($s == 'pending' ? 'bg-pending' : ($s == 'delivered' ? 'bg-success' : 'bg-processing'));
                                            $text = ($s == 'pending' ? 'جاري' : ($s == 'delivered' ? 'مكتمل' : 'معالجة'));
                                        ?>
                                        <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                        <span class="text-muted small">لا توجد طلبات مسجلة بعد</span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Safety check for Chart.js
    if (typeof Chart === 'undefined') return;

    // Helper for chart init
    const initCharts = () => {
        const primaryColor = '#eb6b7e';
        const primaryLight = 'rgba(235, 107, 126, 0.1)';

        // Sales Chart
        const salesEl = document.getElementById('salesChart');
        if (salesEl) {
            new Chart(salesEl, {
                type: 'line',
                data: {
                    labels: ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
                    datasets: [{
                        label: 'المبيعات',
                        data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                        borderColor: primaryColor,
                        backgroundColor: primaryLight,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#666' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#666' }
                        }
                    }
                }
            });
        }

        // Category Chart
        const catEl = document.getElementById('categoryChart');
        if (catEl) {
            new Chart(catEl, {
                type: 'doughnut',
                data: {
                    labels: ['سوداء', 'ملونة', 'مناسبات'],
                    datasets: [{
                        data: [50, 30, 20],
                        backgroundColor: [primaryColor, '#3498db', '#9b59b6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#888', font: { family: 'Tajawal' } }
                        }
                    }
                }
            });
        }
    };

    // Small delay to ensure layout is ready
    setTimeout(initCharts, 200);
});
</script>

<?php include '../includes/footer.php'; ?>
