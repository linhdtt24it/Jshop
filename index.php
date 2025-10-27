<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JSHOP - Trang sức cao cấp</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include __DIR__.'/header.php'; ?>

<!-- Container để load bảng giá vàng -->
<div id="gold-price-container"></div>

<?php
// Include các section
$sections_order = [
    'sections/main_slider.php',
    'sections/featured_products.php',
    
    'sections/diamond.php',
];
foreach ($sections_order as $file) {
    if(file_exists($file)) include $file;
}

// Include các file khác
$other_pages = ['contact.php','product_detail.php'];
foreach($other_pages as $page) {
    if(file_exists(__DIR__.'/'.$page)) include __DIR__.'/'.$page;
}

// Include footer
if(file_exists(__DIR__.'/footer.php')) include __DIR__.'/footer.php';
?>

</body>
</html>
