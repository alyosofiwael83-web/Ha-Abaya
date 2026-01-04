<?php
if(session_status() === PHP_SESSION_NONE) session_start();
// Check if database is already included
// Hardcoded Settings for Static Display
$site_name = 'هَــ عبايه';
$logo = 'assets/images/official_logo.jpg';

// Count Cart Items
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($site_name); ?></title>
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-white">

<!-- 1. Desktop Navbar (Hidden on Mobile) -->
<nav class="navbar-zahraah d-none d-lg-block sticky-top">
    <div class="container-fluid px-5"> <!-- Wider container for desktop -->
        <div class="row align-items-center">
            
            <!-- Right: Search Bar -->
            <div class="col-4">
                <form action="shop.php" method="GET" class="search-container-desktop">
                    <input type="text" name="search" class="search-input-desktop" placeholder="ابحث عن ما تحتاجه هنا...">
                    <button type="submit" class="search-icon-desktop">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Center: Logo -->
            <div class="col-4 text-center">
                <a href="index.php" class="brand-center-desktop">
                    <img src="assets/images/official_logo.jpg" alt="Logo" class="main-logo-img">
                    <div class="brand-text">
                        <?php echo htmlspecialchars($site_name); ?>
                        <span>ABAYA STORE</span>
                    </div>
                </a>
            </div>

            <!-- Left: Icons & Login -->
            <div class="col-4">
                <div class="nav-left-desktop">
                    <a href="contact.php" class="nav-item-icon">
                        <i class="far fa-comments"></i>
                        <span>خدمة العملاء</span>
                    </a>
                    
                    <a href="#" class="nav-item-icon">
                        <i class="far fa-heart"></i>
                        <span>المفضلة</span>
                    </a>
                    
                    <a href="cart.php" class="nav-item-icon position-relative">
                        <i class="fas fa-shopping-bag"></i>
                        <?php if($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem; transform: translate(-50%, 0);">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                        <span>السلة</span>
                    </a>

                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="login.php" class="btn-login-pink">تسجيل الدخول</a>
                    <?php else: ?>
                        <!-- User Dropdown text link -->
                        <div class="dropdown d-inline-block ms-3">
                            <a href="#" class="nav-item-icon dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="far fa-user"></i>
                                <span>حسابي</span>
                            </a>
                             <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li><a class="dropdown-item" href="profile.php">الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="my_orders.php">طلباتي</a></li>
                                <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin/dashboard.php">لوحة التحكم</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">تسجيل خروج</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</nav>

<!-- 2. Mobile Header (Visible Only on Mobile < 992px) -->
<header class="mobile-header d-lg-none sticky-top">
    <!-- Search Icon Left -->
    <a href="shop.php" class="mobile-icon">
        <i class="fas fa-search"></i>
    </a>
    
    <!-- Logo Center -->
    <a href="index.php" class="mobile-logo text-decoration-none">
        <img src="assets/images/official_logo.jpg" alt="Logo" class="mobile-logo-img">
        <span class="ms-2"><?php echo htmlspecialchars($site_name); ?></span>
    </a>
    
    <!-- Notification/Bell Right -->
    <button class="mobile-icon">
        <i class="far fa-bell"></i>
    </button>
</header>

<!-- 3. Mobile Bottom Navigation (Visible Only on Mobile < 992px) -->
<nav class="bottom-nav d-lg-none">
    <a href="index.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i> <!-- Changed from flower icon to Home based on standard apps, or match image -->
        <span>الرئيسية</span>
    </a>
    
    <a href="categories.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i> <!-- Categories/Shop -->
        <span>الفئات</span>
    </a>
    
    <a href="cart.php" class="bottom-nav-item position-relative <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
        <i class="fas fa-shopping-bag"></i>
        <?php if($cart_count > 0): ?>
            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem; margin-left: 10px; margin-top: 5px;">
                <?php echo $cart_count; ?>
            </span>
        <?php endif; ?>
        <span>السلة</span>
    </a>
    
    <a href="#" class="bottom-nav-item">
        <i class="far fa-heart"></i>
        <span>المفضلة</span>
    </a>
    
    <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="bottom-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php' || basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>">
        <i class="far fa-user"></i>
        <span>حسابي</span>
    </a>
</nav>
