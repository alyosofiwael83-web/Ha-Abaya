<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Update Order Status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $order_id);
    
    if($stmt->execute()){
        $_SESSION['success'] = "تم تحديث حالة الطلب بنجاح";
        header("Location: orders.php");
        exit();
    }
}

// Fetch All Orders with User Details
$query = "SELECT orders.*, users.full_name, users.phone 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="container-fluid animate-fade-in">
    <!-- Orders Table -->
    <div class="card p-0 overflow-hidden border-0">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-white">إدارة الطلبات المباشرة</h5>
                    <p class="text-muted small mb-0 mt-1">تتبع وإدارة جميع طلبات العملاء من هنا</p>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-light btn-sm px-3 rounded-pill">
                            <i class="fas fa-filter me-1"></i> تصفية
                        </button>
                        <button class="btn btn-primary btn-sm px-4 rounded-pill">
                            <i class="fas fa-file-export me-1"></i> تصدير
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table premium-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">رقم الطلب</th>
                        <th>العميل</th>
                        <th>رقم الهاتف</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>تاريخ الطلب</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($orders) > 0): ?>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold font-jost text-primary">#ORD-<?php echo $order['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm rounded-circle bg-glass d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem; background: var(--glass-bg);">
                                        <?php echo mb_substr($order['full_name'], 0, 1); ?>
                                    </div>
                                    <span class="fw-600"><?php echo htmlspecialchars($order['full_name']); ?></span>
                                </div>
                            </td>
                            <td class="font-jost text-muted small"><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td class="fw-bold font-jost"><?php echo number_format($order['total_amount'], 2); ?> ر.س</td>
                            <td>
                                <?php 
                                $status_class = '';
                                $status_text = '';
                                switch($order['status']) {
                                    case 'pending': $status_class='bg-pending'; $status_text='قيد الانتظار'; break;
                                    case 'processing': $status_class='bg-processing'; $status_text='قيد المعالجة'; break;
                                    case 'shipped': $status_class='bg-primary text-white'; $status_text='تم الشحن'; break;
                                    case 'delivered': $status_class='bg-success'; $status_text='تم التوصيل'; break;
                                    case 'cancelled': $status_class='bg-danger'; $status_text='ملغي'; break;
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?> small fw-700"><?php echo $status_text; ?></span>
                            </td>
                            <td class="small text-muted font-jost"><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-icon bg-glass status-btn" 
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;"
                                            data-id="<?php echo $order['id']; ?>"
                                            data-status="<?php echo $order['status']; ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal">
                                        <i class="fas fa-edit small text-primary"></i>
                                    </button>
                                    <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-icon bg-glass"
                                       style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;">
                                        <i class="fas fa-eye small text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">لا توجد أي طلبات حالياً</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">تحديث حالة الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="order_id" id="status_order_id">
                    
                    <div class="mb-3">
                        <label class="form-label">حالة الطلب الجديدة</label>
                        <select name="status" id="status_select" class="form-select">
                            <option value="pending">قيد الانتظار</option>
                            <option value="processing">قيد المعالجة</option>
                            <option value="shipped">تم الشحن</option>
                            <option value="delivered">تم التوصيل</option>
                            <option value="cancelled">ملغي</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusButtons = document.querySelectorAll('.status-btn');
        statusButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var orderId = this.getAttribute('data-id');
                var currentStatus = this.getAttribute('data-status');
                
                document.getElementById('status_order_id').value = orderId;
                document.getElementById('status_select').value = currentStatus;
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
