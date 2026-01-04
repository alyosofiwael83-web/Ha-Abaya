<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch Categories for Dropdown
$cat_query = "SELECT * FROM categories";
$cat_stmt = $db->prepare($cat_query);
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Add/Edit/Delete Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Add New Abaya
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Collect Data
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : NULL;
        $color = $_POST['color'];
        $size = $_POST['size'];
        $material = $_POST['material'];
        $stock_quantity = $_POST['stock_quantity'];
        
        // Image Upload
        $main_image = "";
        if(isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0){
            $target_dir = "../assets/images/abayas/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)){
                $main_image = "assets/images/abayas/" . $new_filename;
            }
        }

        $query = "INSERT INTO abayas (name, description, category_id, price, old_price, color, size, material, stock_quantity, main_image) 
                  VALUES (:name, :description, :category_id, :price, :old_price, :color, :size, :material, :stock_quantity, :main_image)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':old_price', $old_price);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':main_image', $main_image);

        if ($stmt->execute()) {
            $product_id = $db->lastInsertId();

            // Handle Gallery Images
            if(isset($_FILES['gallery_images']) && count($_FILES['gallery_images']['name']) > 0){
                foreach($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name){
                    if($_FILES['gallery_images']['error'][$key] == 0){
                        $file_extension = pathinfo($_FILES["gallery_images"]["name"][$key], PATHINFO_EXTENSION);
                        $new_filename = uniqid() . '_gallery.' . $file_extension;
                        $target_file = $target_dir . $new_filename;
                        
                        if(move_uploaded_file($tmp_name, $target_file)){
                            $img_path = "assets/images/abayas/" . $new_filename;
                            $gallery_query = "INSERT INTO product_images (product_id, image_path, is_main) VALUES (:pid, :path, 0)";
                            $gallery_stmt = $db->prepare($gallery_query);
                            $gallery_stmt->execute([':pid' => $product_id, ':path' => $img_path]);
                        }
                    }
                }
            }

            $_SESSION['success'] = "تم إضافة العباية بنجاح";
            header("Location: abayas.php");
            exit();
        }
    }

    // Edit Abaya
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : NULL;
        $color = $_POST['color'];
        $size = $_POST['size'];
        $material = $_POST['material'];
        $stock_quantity = $_POST['stock_quantity'];

        $query = "UPDATE abayas SET name=:name, description=:description, category_id=:category_id, price=:price, 
                  old_price=:old_price, color=:color, size=:size, material=:material, stock_quantity=:stock_quantity 
                  WHERE id=:id";
        
        // Handle Image Update
        if(isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0){
            $target_dir = "../assets/images/abayas/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)){
                $main_image = "assets/images/abayas/" . $new_filename;
                $query = "UPDATE abayas SET name=:name, description=:description, category_id=:category_id, price=:price, 
                          old_price=:old_price, color=:color, size=:size, material=:material, stock_quantity=:stock_quantity, 
                          main_image=:main_image WHERE id=:id";
            }
        }

        $stmt = $db->prepare($query);
        // Bind common params
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':old_price', $old_price);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':id', $id);
        
        if(isset($main_image)) {
            $stmt->bindParam(':main_image', $main_image);
        }

        if ($stmt->execute()) {
            // Handle Gallery Images (Append new ones)
            if(isset($_FILES['gallery_images']) && count($_FILES['gallery_images']['name']) > 0){
                $target_dir = "../assets/images/abayas/";
                foreach($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name){
                    if($_FILES['gallery_images']['error'][$key] == 0){
                        $file_extension = pathinfo($_FILES["gallery_images"]["name"][$key], PATHINFO_EXTENSION);
                        $new_filename = uniqid() . '_gallery.' . $file_extension;
                        $target_file = $target_dir . $new_filename;
                        
                        if(move_uploaded_file($tmp_name, $target_file)){
                            $img_path = "assets/images/abayas/" . $new_filename;
                            $gallery_query = "INSERT INTO product_images (product_id, image_path, is_main) VALUES (:pid, :path, 0)";
                            $gallery_stmt = $db->prepare($gallery_query);
                            $gallery_stmt->execute([':pid' => $id, ':path' => $img_path]);
                        }
                    }
                }
            }

            $_SESSION['success'] = "تم تحديث بيانات العباية بنجاح";
            header("Location: abayas.php");
            exit();
        }
    }
}

// Delete Gallery Image
if (isset($_GET['delete_gallery'])) {
    $img_id = $_GET['delete_gallery'];
    $aba_id = $_GET['aba_id'];
    
    // Get path to delete file
    $stmt = $db->prepare("SELECT image_path FROM product_images WHERE id = ?");
    $stmt->execute([$img_id]);
    $path = $stmt->fetchColumn();
    
    if($path && file_exists("../" . $path)){
        unlink("../" . $path);
    }
    
    $query = "DELETE FROM product_images WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $img_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "تم حذف الصورة بنجاح";
        header("Location: abayas.php");
        exit();
    }
}

// Delete Abaya
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Delete associated gallery images files
    $stmt = $db->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stmt->execute([$id]);
    $imgs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach($imgs as $p) {
        if(file_exists("../" . $p)) unlink("../" . $p);
    }

    $query = "DELETE FROM abayas WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "تم حذف العباية بنجاح";
        header("Location: abayas.php");
        exit();
    }
}

// Fetch All Abayas with Category Name
$query = "SELECT abayas.*, categories.name as category_name 
          FROM abayas 
          LEFT JOIN categories ON abayas.category_id = categories.id 
          ORDER BY abayas.id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$abayas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all gallery images for all abayas to pass to UI
$gallery_items = [];
$g_stmt = $db->query("SELECT * FROM product_images ORDER BY id ASC");
while($row = $g_stmt->fetch(PDO::FETCH_ASSOC)){
    $gallery_items[$row['product_id']][] = $row;
}

include '../includes/header.php';
?>

<div class="container-fluid animate-fade-in">
    <div class="card p-0 overflow-hidden border-0">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-white">إدارة مجموعة العبايات</h5>
                    <p class="text-muted small mb-0 mt-1">تعديل، إضافة، وحذف العبايات من المخزون</p>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-1"></i> إضافة عباية جديدة
                    </button>
                </div>
            </div>
        </div>

        <!-- Abayas Table -->
        <div class="table-responsive">
            <table class="table premium-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">المنتج</th>
                        <th>التصنيف</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>المقاسات</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($abayas) > 0): ?>
                        <?php foreach($abayas as $abaya): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-img-frame" style="width: 50px; height: 65px; border-radius: 10px; overflow: hidden; border: 1px solid var(--glass-border);">
                                        <?php if($abaya['main_image']): ?>
                                            <img src="../<?php echo $abaya['main_image']; ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 bg-glass d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($abaya['name']); ?></div>
                                        <div class="text-muted small">ID: #ABA-<?php echo $abaya['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-glass text-muted border-0 px-3"><?php echo htmlspecialchars($abaya['category_name'] ?? 'غير مصنف'); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold font-jost text-primary"><?php echo number_format($abaya['price'], 2); ?> ر.س</div>
                                <?php if($abaya['old_price']): ?>
                                    <div class="text-muted small text-decoration-line-through font-jost"><?php echo number_format($abaya['old_price'], 2); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($abaya['stock_quantity'] > 5): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                                        <span class="small font-jost"><?php echo $abaya['stock_quantity']; ?> متوفر</span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                                        <span class="small text-danger fw-bold font-jost"><?php echo $abaya['stock_quantity']; ?> منخفض!</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="font-jost small text-muted"><?php echo $abaya['size']; ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-icon bg-glass edit-btn" 
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;"
                                            data-id="<?php echo $abaya['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($abaya['name']); ?>"
                                            data-desc="<?php echo htmlspecialchars($abaya['description']); ?>"
                                            data-cat="<?php echo $abaya['category_id']; ?>"
                                            data-price="<?php echo $abaya['price']; ?>"
                                            data-old="<?php echo $abaya['old_price']; ?>"
                                            data-color="<?php echo htmlspecialchars($abaya['color']); ?>"
                                            data-size="<?php echo $abaya['size']; ?>"
                                            data-material="<?php echo htmlspecialchars($abaya['material']); ?>"
                                            data-stock="<?php echo $abaya['stock_quantity']; ?>"
                                            data-gallery='<?php echo json_encode($gallery_items[$abaya['id']] ?? []); ?>'
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="fas fa-pen-to-square small text-primary"></i>
                                    </button>
                                    <button onclick="confirmDelete('abayas.php?delete=<?php echo $abaya['id']; ?>')" 
                                            class="btn btn-sm btn-icon bg-glass"
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;">
                                        <i class="fas fa-trash-can small text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-shirt fa-3x text-muted mb-3 d-block"></i>
                                <span class="text-muted">لا يوجد منتجات في القائمة</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">إضافة عباية جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم العباية</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" class="form-select">
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">السعر (ر.س)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">السعر القديم (اختياري)</label>
                            <input type="number" step="0.01" name="old_price" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">اللون</label>
                            <input type="text" name="color" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">المقاس</label>
                            <select name="size" class="form-select">
                                <option value="S">S</option>
                                <option value="M" selected>M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label class="form-label">الخامة</label>
                            <input type="text" name="material" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الكمية المتوفرة</label>
                            <input type="number" name="stock_quantity" class="form-control" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الصورة الرئيسية</label>
                            <input type="file" name="main_image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">صور المعرض (اختياري - عدة صور)</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">الوصف التفصيلي</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ العباية</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">تعديل العباية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم العباية</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" id="edit_category_id" class="form-select">
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">السعر (ر.س)</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">السعر القديم (اختياري)</label>
                            <input type="number" step="0.01" name="old_price" id="edit_old_price" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">اللون</label>
                            <input type="text" name="color" id="edit_color" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">المقاس</label>
                            <select name="size" id="edit_size" class="form-select">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label class="form-label">الخامة</label>
                            <input type="text" name="material" id="edit_material" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الكمية المتوفرة</label>
                            <input type="number" name="stock_quantity" id="edit_stock" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تغيير الصورة الرئيسية (اختياري)</label>
                            <input type="file" name="main_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">إضافة صور للمعرض (اختياري)</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">سيتم إضافة هذه الصور للصور الموجودة مسبقاً</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">الوصف التفصيلي</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">الصور الحالية في المعرض</label>
                            <div id="edit_gallery_preview" class="d-flex flex-wrap gap-2 p-2 border rounded bg-light">
                                <!-- Images will be loaded here via JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث العباية</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('edit_id').value = this.getAttribute('data-id');
                document.getElementById('edit_name').value = this.getAttribute('data-name');
                document.getElementById('edit_description').value = this.getAttribute('data-desc');
                document.getElementById('edit_category_id').value = this.getAttribute('data-cat');
                document.getElementById('edit_price').value = this.getAttribute('data-price');
                document.getElementById('edit_old_price').value = this.getAttribute('data-old');
                document.getElementById('edit_color').value = this.getAttribute('data-color');
                document.getElementById('edit_size').value = this.getAttribute('data-size');
                document.getElementById('edit_material').value = this.getAttribute('data-material');
                document.getElementById('edit_stock').value = this.getAttribute('data-stock');

                // Load Gallery Images
                const gallery = JSON.parse(this.getAttribute('data-gallery'));
                const preview = document.getElementById('edit_gallery_preview');
                preview.innerHTML = '';
                if(gallery.length > 0) {
                    gallery.forEach(img => {
                        const div = document.createElement('div');
                        div.className = 'position-relative';
                        div.innerHTML = `
                            <img src="../${img.image_path}" class="rounded" width="80" height="80" style="object-fit:cover;">
                            <a href="abayas.php?delete_gallery=${img.id}&aba_id=${img.product_id}" 
                               class="btn btn-danger btn-sm position-absolute top-0 start-0 p-0 shadow-sm" 
                               style="width:20px; height:20px; line-height:18px;" 
                               onclick="return confirm('حذف هذه الصورة؟')">
                               &times;
                            </a>
                        `;
                        preview.appendChild(div);
                    });
                } else {
                    preview.innerHTML = '<small class="text-muted w-100 text-center">لا توجد صور إضافية</small>';
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
