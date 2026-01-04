<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "تم حذف المستخدم بنجاح";
        header("Location: users.php");
        exit();
    }
}

// Toggle Admin/Customer Role (Simple Promotion logic)
if (isset($_GET['promote'])) {
    $id = $_GET['promote'];
    $query = "UPDATE users SET user_type = CASE WHEN user_type = 'customer' THEN 'admin' ELSE 'customer' END WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "تم تغيير صلاحية المستخدم بنجاح";
        header("Location: users.php");
        exit();
    }
}

$query = "SELECT * FROM users ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="container-fluid animate-fade-in">
    <div class="card p-0 overflow-hidden border-0">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-white">إدارة الحسابات والمسؤولين</h5>
                    <p class="text-muted small mb-0 mt-1">التحكم في صلاحيات الوصول للمستخدمين والموظفين</p>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table premium-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الرتبة</th>
                        <th>الحالة</th>
                        <th>تاريخ الانضمام</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($users) > 0): ?>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm rounded-circle bg-glass d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background: var(--glass-bg); border: 1px solid var(--glass-border);">
                                        <?php echo strtoupper(mb_substr($user['full_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                        <div class="text-muted small">ID: #USR-<?php echo $user['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="font-jost text-muted small"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if($user['user_type'] == 'admin'): ?>
                                    <span class="badge bg-primary px-3 rounded-pill"><i class="fas fa-crown me-1 small"></i> مدير</span>
                                <?php else: ?>
                                    <span class="badge bg-glass text-muted px-3 rounded-pill">عميل</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user['is_active']): ?>
                                    <span class="text-success small fw-bold"><i class="fas fa-circle ms-1" style="font-size: 0.5rem;"></i> نشط حالياً</span>
                                <?php else: ?>
                                    <span class="text-danger small fw-bold"><i class="fas fa-circle ms-1" style="font-size: 0.5rem;"></i> محظور</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted font-jost"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="users.php?promote=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-icon bg-glass"
                                       style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;"
                                       title="تغيير الرتبة">
                                        <i class="fas fa-shield-halved small text-warning"></i>
                                    </a>
                                    <button onclick="confirmDelete('users.php?delete=<?php echo $user['id']; ?>')" 
                                            class="btn btn-sm btn-icon bg-glass"
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;">
                                        <i class="fas fa-user-xmark small text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users-slash fa-3x text-muted mb-3 d-block"></i>
                                <span class="text-muted">لم يتم العثور على أي مستخدمين</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
