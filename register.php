<?php
include 'includes/public_header.php';

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$error = "";

// Simulate Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "كلمات المرور غير متطابقة";
    } else {
        // Simulate Success
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'تم التسجيل بنجاح',
                    text: 'يمكنك الآن تسجيل الدخول',
                    confirmButtonText: 'حسناً'
                }).then((result) => {
                    window.location.href = 'login.php';
                });
              </script>";
    }
}
?>

<div class="auth-wrapper">
    <div class="auth-container animate__animated animate__fadeInUp">
        <div class="auth-tabs">
            <a href="register.php" class="auth-tab active">حساب جديد</a>
            <a href="login.php" class="auth-tab">تسجيل الدخول</a>
        </div>
        
        <div class="auth-body">
            <?php if($error): ?>
                <div class="alert alert-danger text-center small mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="auth-form-group">
                    <input type="text" name="name" class="auth-input" placeholder="الاسم الكامل" required>
                </div>

                <div class="auth-form-group">
                    <input type="email" name="email" class="auth-input" placeholder="عنوان البريد الإلكتروني" required>
                </div>

                <div class="auth-form-group">
                    <input type="tel" name="phone" class="auth-input" placeholder="رقم الهاتف" required>
                </div>
                
                <div class="auth-form-group">
                    <input type="password" name="password" id="password" class="auth-input" placeholder="كلمة السر" required>
                    <i class="far fa-eye auth-password-toggle" onclick="togglePassword('password')"></i>
                </div>

                <div class="auth-form-group">
                    <input type="password" name="confirm_password" id="confirm_password" class="auth-input" placeholder="تأكيد كلمة السر" required>
                    <i class="far fa-eye auth-password-toggle" onclick="togglePassword('confirm_password')"></i>
                </div>
                
                <button type="submit" class="auth-submit-btn">إنشاء حساب</button>
            </form>

            <div class="social-login">
                <div class="social-title">أو سجل عبر</div>
                <div class="social-group">
                    <a href="#" class="social-btn google">
                        <i class="fab fa-google"></i>
                        جوجل
                    </a>
                    <a href="#" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                        فيس بوك
                    </a>
                </div>
            </div>

            <div class="auth-skip">
                <a href="index.php">تخطي</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const pwdInput = document.getElementById(id);
    const eyeIcon = pwdInput.nextElementSibling;
    if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        pwdInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

<?php include 'includes/public_footer.php'; ?>
