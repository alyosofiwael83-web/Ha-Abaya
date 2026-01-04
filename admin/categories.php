<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$message = "";
$error = "";

// Handle Add/Edit/Delete Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Add New Category
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        
        // Image Upload
        $image = "";
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            $target_dir = "../assets/images/categories/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image = "assets/images/categories/" . $new_filename;
            }
        }

        $query = "INSERT INTO categories (name, description, image) VALUES (:name, :description, :image)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            $_SESSION['success'] = "تم إضافة التصنيف بنجاح";
            header("Location: categories.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء الإضافة";
        }
    }

    // Edit Category
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        
        $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        
        // Handle Image Update if new image is uploaded
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
             $target_dir = "../assets/images/categories/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                $image = "assets/images/categories/" . $new_filename;
                $query = "UPDATE categories SET name = :name, description = :description, image = :image WHERE id = :id";
            }
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        if(isset($image)) {
            $stmt->bindParam(':image', $image);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "تم تعديل التصنيف بنجاح";
            header("Location: categories.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء التعديل";
        }
    }
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "تم حذف التصنيف بنجاح";
        header("Location: categories.php");
        exit();
    }
}

$query = "SELECT * FROM categories ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="container-fluid animate-fade-in">
    <div class="card p-0 overflow-hidden border-0">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-white">إدارة تصنيفات المتجر</h5>
                    <p class="text-muted small mb-0 mt-1">تنظيم العبايات حسب الفئات المناسبة</p>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-1"></i> إضافة تصنيف جديد
                    </button>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="table-responsive">
            <table class="table premium-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">التصنيف</th>
                        <th>الوصف</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($categories) > 0): ?>
                        <?php foreach($categories as $category): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="category-img-frame" style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border);">
                                        <?php if($category['image']): ?>
                                            <img src="../<?php echo $category['image']; ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 bg-glass d-flex align-items-center justify-content-center">
                                                <i class="fas fa-tags text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($category['name']); ?></div>
                                        <div class="text-muted small">#CAT-<?php echo $category['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-muted small mb-0" style="max-width: 300px;"><?php echo htmlspecialchars(mb_substr($category['description'], 0, 60)) . (mb_strlen($category['description']) > 60 ? '...' : ''); ?></p>
                            </td>
                            <td class="small font-jost text-muted"><?php echo date('Y-m-d', strtotime($category['created_at'])); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-icon bg-glass edit-btn" 
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;"
                                            data-id="<?php echo $category['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                            data-description="<?php echo htmlspecialchars($category['description']); ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="fas fa-edit small text-primary"></i>
                                    </button>
                                    <button onclick="confirmDelete('categories.php?delete=<?php echo $category['id']; ?>')" 
                                            class="btn btn-sm btn-icon bg-glass"
                                            style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--glass-border); color: #fff;">
                                        <i class="fas fa-trash-alt small text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3 d-block"></i>
                                <span class="text-muted">لم يتم إضافة أي تصنيفات بعد</span>
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
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">إضافة تصنيف جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">صورة التصنيف</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التصنيف</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">تعديل التصنيف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تغيير الصورة (اختياري)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث التصنيف</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script to pass data to edit modal
    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var description = this.getAttribute('data-description');
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_description').value = description;
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
