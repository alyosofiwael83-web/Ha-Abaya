<?php
include 'includes/public_header.php';

// Static Data for Display Only

// 1. Categories
$categories = [
    ['id' => 1, 'name' => 'عبايات يومية', 'image' => 'assets/images/abayas/6957eccb21b9f.jpeg'], 
    ['id' => 2, 'name' => 'عبايات مناسبات', 'image' => 'assets/images/categories/6957eef8e076d.jpeg'],
    ['id' => 3, 'name' => 'عبايات شتوية', 'image' => 'assets/images/categories/6957ef00c205f.jpeg'],
    ['id' => 4, 'name' => 'طرح ونقابات', 'image' => 'assets/images/categories/6957ef073ac5e.jpeg'],
    ['id' => 5, 'name' => 'اطقم كاملة', 'image' => 'assets/images/abayas/6957ecafdbe1b.jpeg']
];

// 2. New Arrivals (Abayas)
$new_arrivals = [
    [
        'id' => 101,
        'name' => 'عباية سوداء تطريز ذهبي',
        'price' => 350,
        'old_price' => 450,
        'main_image' => 'assets/images/products/abaya_emb_main.jpg'
    ],
    [
        'id' => 102,
        'name' => 'عباية كلوش كلاسيك',
        'price' => 290,
        'old_price' => null,
        'main_image' => 'assets/images/abayas/6957eca57781a.jpeg'
    ],
    [
        'id' => 103,
        'name' => 'عباية مخمل شتوية',
        'price' => 420,
        'old_price' => 500,
        'main_image' => 'assets/images/abayas/6957ecafdbe1b.jpeg'
    ],
    [
        'id' => 104,
        'name' => 'عباية لينن صيفي',
        'price' => 250,
        'old_price' => 300,
        'main_image' => 'assets/images/abayas/6957eccb21b9f.jpeg'
    ],
    [
        'id' => 105,
        'name' => 'عباية رأس فاخرة',
        'price' => 180,
        'old_price' => null,
        'main_image' => 'assets/images/abayas/6957ecd26adaf.jpeg'
    ]
];

// 3. Hero Abayas
$hero_abayas = [
   [
        'id' => 201,
        'name' => 'تشكيلة رمضان 2026',
        'description' => 'اكتشفي أحدث تصاميم العبايات الرمضانية الفاخرة، أناقة لا تضاهى لكل يوم.',
        'main_image' => 'assets/images/abayas/6957ece406177.jpeg'
   ],
   [
        'id' => 202,
        'name' => 'أناقة الشتاء',
        'description' => 'دفء وفخامة في تصاميمنا الجديدة لموسم الشتاء.',
        'main_image' => 'assets/images/abayas/6957ecafdbe1b.jpeg'
   ]
];
?>

<!-- 1. Category Circles Section (Story Style) -->
<section class="category-circles-wrapper border-bottom mb-4">
    <div class="container container-custom">
        <div class="category-circles-container">
            <!-- Add a static "All" or "New" circle if desired, or just DB categories -->
            <a href="shop.php" class="cat-circle-item">
                <div class="cat-circle-img d-flex align-items-center justify-content-center bg-dark text-white">
                    <i class="fas fa-th-large fa-2x"></i>
                </div>
                <span class="cat-circle-name">الكل</span>
            </a>

            <?php foreach($categories as $cat): ?>
            <a href="shop.php?category=<?php echo $cat['id']; ?>" class="cat-circle-item">
                <img src="<?php echo $cat['image']; ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>" class="cat-circle-img">
                <span class="cat-circle-name"><?php echo htmlspecialchars($cat['name']); ?></span>
            </a>
            <?php endforeach; ?>
            

        </div>
    </div>
</section>

<!-- 2. Hero Ads Slider Section -->
<section class="hero-slider-section mb-5">
    <div class="swiper adsSwiper">
        <div class="swiper-wrapper">
            <?php if(!empty($hero_abayas)): ?>
                <?php foreach($hero_abayas as $index => $hero): ?>
                <div class="swiper-slide hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo $hero['main_image']; ?>');">
                    <div class="container h-100 d-flex align-items-center justify-content-center text-center">
                        <div class="slide-content text-white">
                            <h1 class="display-3 fw-bold mb-3 animate-text"><?php echo htmlspecialchars($hero['name']); ?></h1>
                            <p class="lead mb-4 animate-text"><?php echo mb_strimwidth(htmlspecialchars($hero['description']), 0, 100, "..."); ?></p>
                            <a href="product.php?id=<?php echo $hero['id']; ?>" class="btn btn-outline-light btn-lg rounded-0 px-5 fw-bold animate-text">اكتشفي المجموعة</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback slide if No Abayas -->
                <div class="swiper-slide hero-slide dark-theme" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/abayas/6957ece406177.jpeg');">
                    <div class="container h-100 d-flex align-items-center justify-content-center text-center">
                        <div class="slide-content">
                            <h1 class="display-3 fw-bold mb-3 animate-text">اكتشفي المزيد</h1>
                            <p class="lead mb-4 animate-text">أفخم العبايات العصرية بلمسة من الرقي والفخامة</p>
                            <a href="shop.php" class="btn btn-light btn-lg rounded-0 px-5 fw-bold animate-text">تسوقي الآن</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- Pagination & Navigation -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>



<!-- 3. New Arrivals (Swiper for beautiful scrolling) -->
<section class="py-4">
    <div class="container-fluid px-3 px-md-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
             <a href="shop.php" class="text-white text-decoration-none small border-bottom border-secondary pb-1">عرض الكل</a>
             <h5 class="fw-bold mb-0">الأكثر مبيعاً</h5>
        </div>
        
        <?php if(count($new_arrivals) > 0): ?>
        <div class="swiper productSwiper">
            <div class="swiper-wrapper">
                <?php foreach($new_arrivals as $abaya): ?>
                <div class="swiper-slide px-2">
                    <div class="product-card shadow-sm rounded-4 overflow-hidden" style="background: #0a0a0a; border: 1px solid #1a1a1a;">
                        <div class="product-img-wrapper" style="height: 260px;">
                            <a href="product.php?id=<?php echo $abaya['id']; ?>">
                                <img src="<?php echo $abaya['main_image']; ?>" alt="<?php echo htmlspecialchars($abaya['name']); ?>">
                            </a>
                            <div class="product-like-icon"><i class="far fa-heart"></i></div>
                            <div class="product-bag-icon quick-view-trigger cursor-pointer" data-id="<?php echo $abaya['id']; ?>">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="product-title text-white mb-1"><?php echo htmlspecialchars($abaya['name']); ?></div>
                            <div class="product-price text-primary">
                                <?php echo number_format($abaya['price'], 0); ?> ر.س
                                <?php if($abaya['old_price']): ?>
                                    <span class="old-price small ms-2" style="color: #666; text-decoration: line-through;"><?php echo number_format($abaya['old_price'], 0); ?> ر.س</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination position-relative mt-4"></div>
        </div>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted">جاري إضافة المنتجات...</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 4. Wide Banners Section (Premium Abaya Collections) -->
<section class="mb-4">
    <div class="container-fluid px-0">
        <!-- Banner 1: Winter/Modern Abayas -->
        <div class="position-relative mb-2 overflow-hidden shadow-sm" style="height: 250px;">
             <img src="https://images.unsplash.com/photo-1622345511059-4f7f631bc0d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="w-100 h-100 object-fit-cover" alt="Winter Collection">
             <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.35);">
                <div class="text-center px-3">
                    <h2 class="fw-bold text-white mb-2" style="font-size: 2.2rem; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">عبايات شتوية</h2>
                    <a href="shop.php" class="btn btn-outline-light rounded-pill px-5 py-2 fw-bold" style="border-width: 2px;">اكتشفي الآن</a>
                </div>
             </div>
        </div>
        
        <!-- Banner 2: Classic/Official Abayas -->
        <div class="position-relative overflow-hidden shadow-sm" style="height: 250px;">
             <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="w-100 h-100 object-fit-cover" alt="Classic Collection">
             <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.35);">
                <div class="text-center px-3">
                    <h2 class="fw-bold text-white mb-2" style="font-size: 2.2rem; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">عبايات مناسبات</h2>
                    <a href="shop.php" class="btn btn-outline-light rounded-pill px-5 py-2 fw-bold" style="border-width: 2px;">اكتشفي الآن</a>
                </div>
             </div>
        </div>
    </div>
</section>

<!-- 5. Second Product Strip (Swiper for Recommendations) -->
<section class="py-4 mb-5">
    <div class="container-fluid px-3 px-md-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
             <a href="shop.php" class="text-white text-decoration-none small border-bottom border-secondary pb-1">عرض الكل</a>
             <h5 class="fw-bold mb-0">منتجات قد تعجبك</h5>
        </div>
        
        <div class="swiper productSwiper">
            <div class="swiper-wrapper">
                <?php foreach(array_reverse($new_arrivals) as $abaya): ?>
                <div class="swiper-slide px-2">
                    <div class="product-card shadow-sm rounded-4 overflow-hidden" style="background: #0a0a0a; border: 1px solid #1a1a1a;">
                        <div class="product-img-wrapper" style="height: 260px;">
                            <a href="product.php?id=<?php echo $abaya['id']; ?>">
                                <img src="<?php echo $abaya['main_image']; ?>" alt="<?php echo htmlspecialchars($abaya['name']); ?>">
                            </a>
                            <div class="product-like-icon"><i class="far fa-heart"></i></div>
                            <div class="product-bag-icon quick-view-trigger cursor-pointer" data-id="<?php echo $abaya['id']; ?>">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                         <div class="card-body p-3">
                            <div class="product-title text-white mb-1"><?php echo htmlspecialchars($abaya['name']); ?></div>
                            <div class="product-price text-primary"><?php echo number_format($abaya['price'], 0); ?> ر.س</div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination position-relative mt-4"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Hero Ads Slider
    var adsSwiper = new Swiper(".adsSwiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        effect: "fade",
        fadeEffect: {
            crossFade: true
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // 2. Product Horizontal Swipers
    var productSwipers = new Swiper(".productSwiper", {
        slidesPerView: 2.2,
        spaceBetween: 12,
        freeMode: true,
        grabCursor: true,
        breakpoints: {
            // when window width is >= 768px
            768: {
                slidesPerView: 4,
                spaceBetween: 20
            },
            // when window width is >= 1200px
            1200: {
                slidesPerView: 5,
                spaceBetween: 25
            }
        }
    });
});
</script>

<?php include 'includes/public_footer.php'; ?>
