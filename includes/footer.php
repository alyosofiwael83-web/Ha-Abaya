    </div>
    <!-- End Page Content -->
</div>
<!-- End Wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script>
    // Toggle Sidebar for Mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        if(sidebar.classList.contains('active')) {
            sidebar.style.marginRight = "0";
            overlay.style.display = "block";
            setTimeout(() => overlay.style.opacity = "1", 10);
        } else {
            sidebar.style.marginRight = "-280px";
            overlay.style.opacity = "0";
            setTimeout(() => overlay.style.display = "none", 400);
        }
    }

    // Premium SweetAlert configuration
    const premiumSwal = Swal.mixin({
        background: 'rgba(20, 20, 20, 0.95)',
        color: '#fff',
        confirmButtonColor: '#eb6b7e',
        cancelButtonColor: 'rgba(255,255,255,0.1)',
        backdrop: `rgba(0,0,0,0.6) blur(4px)`,
        customClass: {
            popup: 'premium-popup'
        }
    });

    // Generic Delete Confirmation
    function confirmDelete(url) {
        premiumSwal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من استرجاع هذا العنصر!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
    
    // Generic Logout Confirmation
    function confirmLogout() {
        premiumSwal.fire({
            title: 'تسجيل خروج',
            text: "هل تود تسجيل الخروج من النظام فعلاً؟",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'خروج',
            cancelButtonText: 'بقاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php'; 
            }
        })
    }

    // Success/Error Messages
    <?php if(isset($_SESSION['success'])): ?>
        premiumSwal.fire({
            icon: 'success',
            title: 'تم بنجاح!',
            text: '<?php echo $_SESSION['success']; unset($_SESSION['success']); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        premiumSwal.fire({
            icon: 'error',
            title: 'عذراً...',
            text: '<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>',
        });
    <?php endif; ?>
</script>

</body>
</html>
