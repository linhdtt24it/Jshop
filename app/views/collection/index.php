<?php
// app/views/collection/index.php
$page_title = "Bộ Sưu Tập - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
    /* Font chung */
    .collection-page {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        background-color: #fff;
        color: #1a1a1a;
    }

    h1, h2, h3, h4, h5 {
        font-family: 'Playfair Display', serif !important;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* BANNER HERO (Đen Trắng) */
    .coll-hero {
        background-color: #f9f9f9; 
        padding: 80px 0;
        text-align: center;
        border-bottom: 1px solid #eeeeee;
    }
    
    .hero-title {
        font-size: 3rem;
        color: #000;
        margin-bottom: 15px;
    }
    .hero-subtitle {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        color: #6c757d;
        font-size: 1.1rem;
    }

    /* --- CARD CHÍNH (CÓ ĐỔ BÓNG NHẸ) --- */
    .coll-card {
        border: 1px solid #eee;
        background: #fff;
        height: 100%;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        /* THÊM ĐỔ BÓNG NHẸ Ở ĐÂY */
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .coll-card:hover {
        border-color: #000; 
        transform: translateY(-5px);
        /* Hover thì bóng đậm hơn chút để tạo cảm giác nổi lên */
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .coll-img-wrap {
        height: 350px;
        overflow: hidden;
        position: relative;
    }
    .coll-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s;
    }
    .coll-card:hover .coll-img { transform: scale(1.1); }

    .coll-badge {
        position: absolute; top: 15px; right: 15px;
        background: #000; color: #fff;
        padding: 5px 12px; text-transform: uppercase;
        font-size: 0.75rem; font-weight: 600; letter-spacing: 1px;
    }

    .coll-body { 
        padding: 30px 20px; 
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center; 
    }
    
    .coll-title { 
        font-size: 1.5rem; margin-bottom: 10px; color: #000; 
        font-family: 'Playfair Display', serif;
    }
    
    .coll-desc { color: #666; font-size: 0.95rem; margin-bottom: 20px; }

    .tag-pill {
        display: inline-block;
        border: 1px solid #eee;
        padding: 4px 12px;
        font-size: 0.8rem;
        color: #555;
        margin: 0 3px;
        background: #fcfcfc;
    }

    /* NÚT KHÁM PHÁ (CÓ ICON) */
    .btn-discover {
        margin-top: auto;
        border: 1px solid #000;
        color: #000;
        background: transparent;
        padding: 12px 30px;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-discover:hover {
        background: #000;
        color: #fff;
    }

    /* --- MINI CARD (CÓ ĐỔ BÓNG NHẸ) --- */
    .mini-card {
        border: 1px solid #eee;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s;
        height: 100%;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        /* THÊM ĐỔ BÓNG NHẸ Ở ĐÂY */
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .mini-card:hover {
        border-color: #000;
        background: #fcfcfc;
        transform: translateY(-5px);
        /* Hover thì bóng đậm hơn chút */
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .gem-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        display: inline-block;
    }

    .mini-title { font-size: 1.2rem; margin-bottom: 15px; color: #000; }
    
    /* NÚT XEM CHI TIẾT (ĐÓNG KHUNG) */
    .btn-detail {
        margin-top: auto;
        border: 1px solid #e0e0e0;
        color: #555;
        padding: 8px 20px;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        letter-spacing: 1px;
        transition: all 0.3s;
    }
    .mini-card:hover .btn-detail {
        border-color: #000;
        background: #000;
        color: #fff;
    }

    /* STATS (Số liệu cuối trang) */
    .stats-section {
        background-color: #f9f9f9;
        padding: 80px 0;
        border-top: 1px solid #eee;
        margin-top: 80px;
    }
    .stat-number { 
        font-size: 2.5rem; 
        font-weight: 400; 
        color: #000; 
        margin-bottom: 5px; 
        font-family: 'Playfair Display', serif;
    }
    .stat-label { 
        font-family: 'Be Vietnam Pro', sans-serif; 
        color: #666; 
        font-size: 0.9rem; 
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="collection-page">
    
    <section class="coll-hero">
        <div class="container">
            <h1 class="hero-title">Bộ Sưu Tập Độc Bản</h1>
            <p class="hero-subtitle">Tuyệt tác trang sức dành riêng cho bạn</p>
        </div>
    </section>

    <div class="container my-5">
        
        <div class="text-center mb-5">
            <h2 style="font-size: 2.5rem;">Tất cả Bộ Sưu Tập (<?= count($collections) ?>)</h2>
            <div style="width: 60px; height: 1px; background: #000; margin: 20px auto;"></div>
        </div>

        <div class="row g-5">
            <?php 
            // Vòng lặp qua dữ liệu thực từ Controller
            $featured_collections = array_slice($collections, 0, 3);
            $tags = ['Nhẫn', 'Dây chuyền', 'Bông tai', 'Vòng tay', 'Trang sức cưới'];
            $badges = ['Best Seller', 'New Arrival', 'Hot Deal'];
            
            foreach ($featured_collections as $index => $coll): 
                // Tạo link chi tiết
                $link = BASE_URL . 'collection/detail/' . htmlspecialchars($coll['slug']);
            ?>
                <div class="col-lg-4 col-md-6">
                    <a href="<?= $link ?>" style="text-decoration:none;">
                        <div class="coll-card">
                            <div class="coll-img-wrap">
                                <img src="<?= htmlspecialchars($coll['image'] ?? 'https://images.unsplash.com/photo-1548357194-9e1aace4e94d?q=80&w=600') ?>" 
                                     class="coll-img" 
                                     alt="<?= htmlspecialchars($coll['name']) ?>">
                                     
                                <?php if (isset($badges[$index])): ?>
                                    <span class="coll-badge"><?= $badges[$index] ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="coll-body">
                                <h3 class="coll-title"><?= htmlspecialchars($coll['name']) ?></h3>
                                <p class="coll-desc">
                                    <?= htmlspecialchars($coll['description'] ?? 'Bộ sưu tập trang sức độc đáo.') ?>
                                </p>
                                <div class="mb-4">
                                    <span class="tag-pill"><?= $tags[$index % count($tags)] ?></span>
                                    <span class="tag-pill"><?= $coll['product_count'] ?> SP</span>
                                </div>
                                <div class="btn-discover">
                                    <i class="bi bi-gem"></i> Khám phá ngay
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($collections) > 3): ?>
        <div class="text-center mb-5 mt-5 pt-5">
            <h2 style="font-size: 2.5rem;">Khám Phá Thêm</h2>
            <div style="width: 60px; height: 1px; background: #000; margin: 20px auto;"></div>
        </div>

        <div class="row g-4">
            <?php 
            $remaining_collections = array_slice($collections, 3);
            $colors = ['#0dcaf0', '#198754', '#dc3545', '#212529', '#ffc107', '#6610f2'];
            
            foreach($remaining_collections as $index => $item): 
                $link = BASE_URL . 'collection/detail/' . htmlspecialchars($item['slug']);
            ?>
            <div class="col-lg-4 col-md-6">
                <a href="<?= $link ?>" style="text-decoration:none;">
                    <div class="mini-card">
                        <i class="bi bi-gem gem-icon" style="color: <?= $colors[$index % count($colors)] ?>"></i>
                        <h4 class="mini-title"><?= htmlspecialchars($item['name']) ?> (<?= $item['product_count'] ?>)</h4>
                        <div class="btn-detail">
                            Xem chi tiết
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Bộ Sưu Tập</div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Sản Phẩm</div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-number">5★</div>
                    <div class="stat-label">Đánh Giá</div>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Khách Hàng</div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>