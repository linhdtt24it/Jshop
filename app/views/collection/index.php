<?php
$page_title = "Bộ Sưu Tập - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';

function slugToTitle($slug) {
    return ucwords(str_replace('-', ' ', $slug));
}

$featured_details = [
    'mua-xuan-2025' => ['tags' => ['Xuân', 'Đá Quý'], 'badge' => 'New Arrival', 'icon' => 'bi-flower1', 'desc' => 'Sắc xuân rạng ngời trong từng thiết kế.'],
    'tinh-yeu-vinh-cuu' => ['tags' => ['Nhẫn Cưới', 'Kim Cương'], 'badge' => 'Best Seller', 'icon' => 'bi-heart-fill', 'desc' => 'Biểu tượng của tình yêu và sự vĩnh cửu.'],
    'phong-cach-hien-dai' => ['tags' => ['Bạc', 'Unisex'], 'badge' => 'Hot Trends', 'icon' => 'bi-star', 'desc' => 'Thiết kế tối giản, hợp thời trang.'],
];

?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
.collection-page {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    color: #1a1a1a;
}

h1, h2, h3, h4, h5 {
    font-family: 'Playfair Display', serif !important;
    font-weight: 700;
    letter-spacing: 0.5px;
}

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

.coll-card {
    border: 1px solid #eee;
    background: #fff;
    height: 100%;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.coll-card:hover {
    border-color: #000; 
    transform: translateY(-5px);
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
    background: #c2185b; color: #fff;
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
            <h2 style="font-size: 2.5rem;">Khám Phá Bộ Sưu Tập</h2>
            <div style="width: 60px; height: 1px; background: #000; margin: 20px auto;"></div>
        </div>

        <div class="row g-5">
        <?php 
        $colors = ['#c2185b', '#003366', '#d4af37', '#6610f2', '#dc3545', '#198754'];
        $i = 0;
        foreach ($collections as $coll): 
            $slug = $coll['slug'] ?? 'default-slug';
            $details = $featured_details[$slug] ?? [
                'tags' => ['Trang sức', 'Mới'], 
                'badge' => 'Mới', 
                'icon' => 'bi-gem', 
                'desc' => $coll['description'] ?: slugToTitle($slug) . ' - Bộ sưu tập độc đáo từ JSHOP.'
            ];
            
            $title = $coll['name'] ?: slugToTitle($slug);
            
            $image = $coll['image'] ?: 'https://images.unsplash.com/photo-1548357194-9e1aace4e94d?q=80&w=600';
            $badge_color = $colors[$i % count($colors)];
            $i++;
        ?>
            <div class="col-lg-4 col-md-6">
                <div class="coll-card">
                    <div class="coll-img-wrap">
                        <img src="<?= htmlspecialchars($image) ?>" class="coll-img" alt="<?= htmlspecialchars($title) ?>">
                        <span class="coll-badge" style="background-color: <?= $badge_color ?>;"><?= $details['badge'] ?></span>
                    </div>
                    <div class="coll-body">
                        <h3 class="coll-title"><?= htmlspecialchars($title) ?></h3>
                        <p class="coll-desc"><?= htmlspecialchars($details['desc']) ?></p>
                        <div class="mb-4">
                            <?php foreach ($details['tags'] as $tag): ?>
                                <span class="tag-pill"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= BASE_URL ?>collection/detail/<?= htmlspecialchars($slug) ?>" class="btn-discover">
                            <i class="bi <?= $details['icon'] ?>"></i> Khám phá ngay
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

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