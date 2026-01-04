<?php
include 'includes/public_header.php';

// Simulate Cart Check
$_SESSION['cart'] = true; // Force non-empty
/*
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}
*/

// Static Checkout Data
$total_amount = 440.00;
$order_items_data = [
    [
        'abaya_id' => 101,
        'quantity' => 1,
        'price' => 350.00,
        'name' => 'عباية سوداء تطريز ذهبي فاخر',
        'subtotal' => 350.00
    ],
    [
        'abaya_id' => 106,
        'quantity' => 2,
        'price' => 45.00,
        'name' => 'طرحة شيفون بيج',
        'subtotal' => 90.00
    ]
];

// Handle POST Request (Place Order Simulation)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $order_number = "ORD-" . date('Ymd') . "-" . rand(1000, 9999);
    
    // Simulate Success Delay
    sleep(1);
    
    // Redirect to Success
    echo "<script>window.location.href='order_success.php?order=$order_number';</script>";
    exit();
}
?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="fw-bold">إتمام الطلب</h2>
            <p class="text-muted">أكمل ملء بياناتك لتأكيد عملية الشراء</p>
        </div>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="animate__animated animate__fadeIn">
        <div class="row g-5">
            <!-- Form Details -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> عنوان التوصيل</h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required placeholder="مثال: سارة محمد">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="example@mail.com">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" required placeholder="05xxxxxxxx">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">العنوان التفصيلي <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required placeholder="المدينة، الحي، اسم الشارع، رقم المنزل..."></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">ملاحظات إضافية (اختياري)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="أي تعليمات خاصة للتوصيل..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <h5 class="fw-bold mb-3">طريقة الدفع</h5>
                        <div class="form-check p-3 border rounded mb-2" style="background: #111; border-color: #222 !important;">
                            <input class="form-check-input float-end ms-0 me-2" type="radio" name="payment_method" id="cod" checked>
                            <label class="form-check-label me-4 fw-bold" for="cod">
                                <i class="fas fa-money-bill-wave text-success me-2"></i> الدفع عند الاستلام
                            </label>
                        </div>
                         <div class="form-check p-3 border rounded mb-2 text-muted">
                            <input class="form-check-input float-end ms-0 me-2" type="radio" name="payment_method" id="card" disabled>
                            <label class="form-check-label me-4" for="card">
                                <i class="far fa-credit-card me-2"></i> بطاقة ائتمان (قريباً)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4" style="background: #111;">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">ملخص الطلب</h5>
                    
                    <div class="cart-summary-list mb-4" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach($order_items_data as $item): ?>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2"><?php echo $item['quantity']; ?>x</span>
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </div>
                            <span class="fw-bold"><?php echo number_format($item['subtotal'], 2); ?> ر.س</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2 border-top pt-3">
                        <span>المجموع الفرعي</span>
                        <span class="fw-bold"><?php echo number_format($total_amount, 2); ?> ر.س</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>الشحن (ثابت)</span>
                        <span class="fw-bold">0.00 ر.س</span>
                    </div>
                    
                    <div class="total-box p-3 rounded border text-center mb-4" style="background: #050505; border-color: #333 !important;">
                        <small class="text-muted d-block uppercase ls-1">المبلغ الإجمالي</small>
                        <span class="text-primary fs-2 fw-bold"><?php echo number_format($total_amount, 2); ?> ر.س</span>
                    </div>

                    <button type="submit" class="btn btn-gold w-100 py-3 rounded-pill shadow-lg fw-bold fs-5">
                        تأكيد الطلب الآن <i class="fas fa-check ms-2"></i>
                    </button>
                    
                    <p class="text-center mt-3 text-muted small">
                        <i class="fas fa-lock me-1"></i> جميع بياناتك مشفرة وآمنة
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/public_footer.php'; ?>
