<?php
include 'includes/public_header.php';

// Simulate Session Check
/*
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
*/
$user_id = 1;
$msg = "";
$error = "";

// Handle Profile Update Simulation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    // Simulate updating session name
    if(session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['user_name'] = $name; 
    
    // Simulate delay
    sleep(1);
    
    $msg = "تم تحديث البيانات بنجاح";
}

// Static User Data
$user = [
    'email' => 'client@zahraah.com',
    'full_name' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'ضيف زهراء',
    'phone' => '0500000000'
];
?>

<div class="account-page-wrapper">
    <!-- Header -->
    <div class="account-header">
        <a href="profile.php" style="color: #fff;"><i class="fas fa-chevron-right"></i></a>
        <h5>الملف الشخصي</h5>
        <div style="width: 20px;"></div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm" style="background: #0d0d0d; border: 1px solid #1a1a1a !important;">
                    <div class="card-body p-4">
                        
                        <?php if($msg): ?>
                            <div class="alert alert-success bg-success text-white border-0"><?php echo $msg; ?></div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger bg-danger text-white border-0"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label text-muted small mb-2">البريد الإلكتروني</label>
                                <input type="email" class="form-control" style="background: #000; border: 1px solid #222; color: #555;" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label text-white small mb-2">الاسم الكامل</label>
                                <input type="text" name="name" class="form-control" style="background: #000; border: 1px solid #222; color: #fff;" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label text-white small mb-2">رقم الهاتف</label>
                                <input type="tel" name="phone" class="form-control" style="background: #000; border: 1px solid #222; color: #fff;" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            
                            <div class="mb-5">
                                <label class="form-label text-white small mb-2">كلمة المرور الجديدة (اتركها فارغة إذا لم ترد التغيير)</label>
                                <input type="password" name="password" class="form-control" style="background: #000; border: 1px solid #222; color: #fff;" autocomplete="new-password">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold" style="background: var(--primary-color); border: none; padding: 15px;">حفظ التغييرات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/public_footer.php'; ?>
