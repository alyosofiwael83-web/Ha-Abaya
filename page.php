<?php
include 'includes/public_header.php';

$page_name = isset($_GET['name']) ? $_GET['name'] : '';
$title = "صفحة";
$content = "";

switch($page_name) {
    case 'refund':
        $title = "سياسة الاسترجاع";
        $content = "
        <p>نحن في متجر عباية إيليجانس نهتم برضاكم التام. يمكنكم طلب استرجاع المنتجات وفق الشروط التالية:</p>
        <ul>
            <li>أن يكون المنتج بحالته الأصلية ولم يتم استخدامه.</li>
            <li>أن يتم تقديم طلب الاسترجاع خلال 7 أيام من تاريخ الاستلام.</li>
            <li>المنتجات المعدلة حسب الطلب لا تخضع لسياسة الاسترجاع إلا في حال وجود عيب مصنعي.</li>
        </ul>";
        break;
    case 'privacy':
        $title = "سياسة الخصوصية";
        $content = "
        <p>خصوصيتكم تهمنا. نحن نلتزم بحماية بياناتكم الشخصية واستخدامها فقط لتحسين تجربتكم في المتجر.</p>
        <p>نحن لا نشارك بياناتكم مع أي أطراف ثالثة إلا في حدود إتمام عملية الشحن والتوصيل.</p>";
        break;
    case 'about':
        $title = "من نحن";
        $content = "
        <div class='text-center mb-4'>
            <img src='assets/images/official_logo.jpg' style='width: 150px; border-radius: 20px;' class='shadow-sm'>
        </div>
        <p class='text-center'>نحن وجهتكم الأولى لأفخم وأرقى العبايات العصرية التي تجمع بين الأصالة والحداثة. نسعى دائماً لتقديم الأفضل لعملائنا في جميع أنحاء المملكة.</p>
        <p class='text-center'>تأسس متجرنا ليكون عنواناً للأناقة والتميز، مع التركيز على جودة الأقمشة ودقة التطريز.</p>";
        break;
    default:
        $title = "صفحة غير موجودة";
        $content = "<p class='text-center'>نعتذر، هذه الصفحة غير موجودة حالياً.</p>";
}
?>

<style>
    .static-page-wrapper {
        background: #000 !important;
        min-height: 100vh !important;
        direction: rtl !important;
        font-family: 'Cairo', sans-serif !important;
        color: #fff !important;
    }
    .page-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 15px 20px !important;
        background: #000 !important;
        border-bottom: 1px solid #1a1a1a !important;
    }
    .content-card {
        background: #0d0d0d !important;
        border: 1px solid #1a1a1a !important;
        border-radius: 20px !important;
        padding: 30px 25px !important;
        margin-top: 20px !important;
        line-height: 1.8 !important;
    }
    .content-card h2 {
        color: var(--primary-color) !important;
        margin-bottom: 20px !important;
        font-weight: 700 !important;
    }
</style>

<div class="static-page-wrapper">
    <div class="page-header">
        <div style="width: 24px;"></div>
        <h5 class="m-0 fw-bold"><?php echo $title; ?></h5>
        <a href="profile.php" style="color: #fff;"><i class="fas fa-chevron-left"></i></a>
    </div>

    <div class="container py-4">
        <div class="content-card">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<div style="height: 80px;"></div>

<?php include 'includes/public_footer.php'; ?>
