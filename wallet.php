<?php
include 'includes/public_header.php';

// Disable session check for static preview
/*
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
*/
$user_id = $_SESSION['user_id'] ?? 1;

// Static Wallet Balance
$balance = 1250.00;
?>

<style>
    .wallet-wrapper {
        background: #000 !important;
        min-height: 100vh !important;
        direction: rtl !important;
        font-family: 'Cairo', sans-serif !important;
        color: #fff !important;
    }
    .wallet-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 15px 20px !important;
        background: #000 !important;
        border-bottom: 1px solid #1a1a1a !important;
    }
    .balance-card {
        background: linear-gradient(135deg, #1a1a1a 0%, #000 100%) !important;
        border: 1px solid #222 !important;
        border-radius: 20px !important;
        padding: 40px 20px !important;
        text-align: center !important;
        margin-top: 20px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
    }
    .balance-amount {
        font-size: 3rem !important;
        font-weight: 800 !important;
        color: var(--primary-color) !important;
        margin: 10px 0 !important;
    }
    .transaction-card {
        background: #0d0d0d !important;
        border-radius: 15px !important;
        padding: 15px 20px !important;
        margin-bottom: 10px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        border: 1px solid #111 !important;
    }
</style>

<div class="wallet-wrapper">
    <div class="wallet-header">
        <div style="width: 24px;"></div>
        <h5 class="m-0 fw-bold">المحفظة</h5>
        <a href="profile.php" style="color: #fff;"><i class="fas fa-chevron-left"></i></a>
    </div>

    <div class="container py-4">
        <!-- Balance Card -->
        <div class="balance-card">
            <div class="text-muted small mb-2">الرصيد المتاح</div>
            <div class="balance-amount"><?php echo number_format($balance, 2); ?></div>
            <div class="text-white fw-bold">ريال سعودي</div>
            
            <button class="btn btn-primary rounded-pill px-5 mt-4" style="background: var(--primary-color); border: none; padding: 12px 0; width: 80%;">شحن الرصيد</button>
        </div>

        <!-- Recent Transactions Section -->
        <div class="mt-5">
            <h6 class="fw-bold mb-3 px-2">آخر العمليات</h6>
            
            <!-- Dummy Transactions -->
            <div class="transaction-card">
                <div>
                    <div class="fw-bold fs-6">رصيد افتتاحي</div>
                    <div class="text-muted small">01 Jan 2026</div>
                </div>
                <div class="text-success fw-bold">+0.00 ر.س</div>
            </div>
            
            <div class="text-center py-5 mt-3 opacity-50">
                <i class="fas fa-history fa-3x mb-3"></i>
                <p>لا توجد عمليات سابقة</p>
            </div>
        </div>
    </div>
</div>

<div style="height: 80px;"></div>

<?php include 'includes/public_footer.php'; ?>
