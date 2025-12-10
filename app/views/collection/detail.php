// File: Jshop/app/views/collection/detail.php

<?php 
$page_title = $collection['name'] ?? "Chi tiết Bộ Sưu Tập"; 
include __DIR__ . "/../layouts/header.php"; 
?>

<style>
/* CSS cho Banner Collection */
.collection-hero-banner {
    height: 400px; /* Chiều cao cố định */
    /* Sử dụng hình ảnh của Collection */
    background-image: url(<?= htmlspecialchars($collection['image']) ?>); 
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    margin-bottom: 30px;
    position: relative;
    z-index: 1;
}
.collection-hero-banner::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4); /* Overlay tối để chữ dễ đọc */
    z-index: 2;
}
.hero-content {
    position: relative;
    z-index: 3;
    max-width: 800px;
    text-align: center;
}
.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 800;
}
.hero-content p {
    font-size: 1.2rem;
    font-style: italic;
}
/* Style cho Sidebar Filter */
.form-select, .form-control { border-radius: 4px; }
.form-select:focus { border-color: #000; box-shadow: none; }
</style>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
           
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($collection['name']) ?></li>
        </ol>
    </nav>
</div>

<?php if (!empty($collection['image'])): ?>
<section class="collection-hero-banner">
    <div class="hero-content">
        <h1 class="text-uppercase"><?= htmlspecialchars($collection['name']) ?></h1>
        <p><?= nl2br(htmlspecialchars($collection['description'] ?? '')) ?></p>
    </div>
</section>
<?php endif; ?>


<div class="container my-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card p-3 shadow-sm sticky-top" style="top: 20px;">
                <h5 class="card-title fw-bold">Lọc & Sắp xếp</h5>
                <p class="text-muted small"><?= count($products) ?> sản phẩm</p>
                <hr>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Sắp xếp</label>
                    <select class="form-select">
                        <option>Mới nhất</option>
                        <option>Giá: Thấp đến Cao</option>
                        <option>Giá: Cao đến Thấp</option>
                        <option>Theo tên (A-Z)</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Chất liệu</label>
                    <select class="form-select">
                        <option>Tất cả</option>
                        <option>Vàng</option>
                        <option>Kim cương</option>
                        <option>Bạc</option>
                    </select>
                </div>
                
                <button class="btn btn-dark w-100 mt-2">Áp dụng</button>
            </div>
        </div>

        <div class="col-lg-9">
            <h2 class="fw-bold mb-4 border-bottom pb-2">
                Sản phẩm của bộ sưu tập
            </h2>
        
            <div class="row g-4">
                <?php foreach ($products as $p): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm">

                            <img src="<?= htmlspecialchars($p['image'] ?? BASE_URL.'images/no-image.jpg') ?>"
                                 class="card-img-top"
                                 style="height:220px; object-fit:cover;"
                                 alt="<?= htmlspecialchars($p['name']) ?>">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="text-danger fw-bold"><?= number_format($p['price']) ?>₫</p>

                                <a href="<?= BASE_URL ?>product/detail/<?= $p['product_id'] ?>"
                                   class="btn btn-outline-dark mt-auto">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                    <p class="text-center text-muted col-12">Không có sản phẩm nào trong bộ sưu tập này.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>