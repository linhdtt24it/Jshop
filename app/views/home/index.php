<?php
// app/views/home/index.php
$page_title = "JSHOP - Trang sức cao cấp";
$is_home = true;

// INCLUDE TRỰC TIẾP HEADER VÀ FOOTER
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- === CÁC SECTION TRANG CHỦ === -->
<?php include __DIR__ . '/../sections/DanhMucGoiY.php'; ?>
<?php include __DIR__ . '/../sections/SanPhamYeuThich.php'; ?>
<?php include __DIR__ . '/../sections/SanPhamMoi.php'; ?>
<?php include __DIR__ . '/../sections/DongHo.php'; ?>
<?php include __DIR__ . '/../sections/TrangSucNam.php'; ?>
<?php include __DIR__ . '/../sections/TrangSucNu.php'; ?>

<?php include __DIR__ . '/../sections/Vang.php'; ?>
<?php include __DIR__ . '/../sections/Bac.php'; ?>
<?php include __DIR__ . '/../sections/KimCuong.php'; ?>
<?php include __DIR__ . '/../sections/DaQuy.php'; ?>
<?php include __DIR__ . '/../sections/Ngoc.php'; ?>
<?php include __DIR__ . '/../sections/SanPhamKhac.php'; ?>
<?php include __DIR__ . '/../sections/GiaVang.php'; ?>
<?php include __DIR__ . '/../sections/TinTuc.php'; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>