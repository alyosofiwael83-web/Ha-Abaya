<?php
include 'includes/public_header.php';

// Static Categories for Display
$categories = [
    ['id' => 1, 'name' => 'عبايات يومية', 'image' => 'assets/images/abayas/6957eccb21b9f.jpeg'], 
    ['id' => 2, 'name' => 'عبايات مناسبات', 'image' => 'assets/images/categories/6957eef8e076d.jpeg'],
    ['id' => 3, 'name' => 'عبايات شتوية', 'image' => 'assets/images/categories/6957ef00c205f.jpeg'],
    ['id' => 4, 'name' => 'طرح ونقابات', 'image' => 'assets/images/categories/6957ef073ac5e.jpeg'],
    ['id' => 5, 'name' => 'اطقم كاملة', 'image' => 'assets/images/abayas/6957ecafdbe1b.jpeg']
];
?>

<div class="cat-page-fixed" style="background: #000 !important; min-height: 100vh !important; direction: rtl !important; padding: 20px 10px !important;">
    
    <!-- App Search Bar (Matching colors) -->
    <div style="background: #111 !important; border: 1px solid #222 !important; border-radius: 12px !important; margin-bottom: 25px !important; display: flex !important; align-items: center !important; padding: 12px !important;">
        <i class="fas fa-search" style="color: #666 !important; margin-left: 10px !important;"></i>
        <input type="text" placeholder="ابحث عن ما تحتاجه هنا..." style="background: transparent !important; border: none !important; color: #fff !important; width: 100% !important; outline: none !important; font-size: 0.9rem !important;">
    </div>

    <!-- Category Boxes -->
    <div style="display: flex !important; flex-direction: column !important; gap: 15px !important;">
        <?php foreach($categories as $cat): ?>
        <a href="shop.php?category=<?php echo $cat['id']; ?>" style="display: flex !important; align-items: center !important; background: #0d0d0d !important; border: 1px solid #1a1a1a !important; border-radius: 15px !important; padding: 10px !important; text-decoration: none !important;">
            
            <!-- Category Image -->
            <div style="width: 65px !important; height: 65px !important; min-width: 65px !important; border-radius: 12px !important; overflow: hidden !important; margin-left: 15px !important; background: #222 !important;">
                <img src="<?php echo !empty($cat['image']) ? $cat['image'] : 'assets/images/official_logo.jpg'; ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important;">
            </div>

            <!-- Category Name -->
            <div style="flex: 1 !important; text-align: right !important;">
                <h6 style="color: #ffffff !important; font-weight: 700 !important; font-size: 1.1rem !important; margin: 0 !important; font-family: 'Cairo', sans-serif !important;">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </h6>
            </div>

            <!-- Arrow -->
            <div style="color: #eb6b7e !important; padding-left: 5px !important;">
                <i class="fas fa-chevron-left"></i>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div style="height: 100px;"></div> <!-- Spacer for bottom nav -->

<?php include 'includes/public_footer.php'; ?>
