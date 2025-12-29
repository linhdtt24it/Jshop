<?php
$page_title = "Tin tức - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';

function getNldNews() {
    $cache_dir = __DIR__ . '/../../cache';
    $cache_file = $cache_dir . '/news_nld.json';
    $cache_time = 1800;

    if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);

    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
        $data = json_decode(file_get_contents($cache_file), true);
        if ($data) return $data;
    }

    $url = 'https://nld.com.vn/thi-truong-vang-trang-suc.html';
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $news_list = [];
    $featured = null;

    if ($html && $httpCode == 200) {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $articles = $xpath->query('//div[contains(@class, "news-item")] | //div[@class="item"]');

        foreach ($articles as $index => $article) {
            $titleNode = $xpath->query('.//h3/a | .//h2/a | .//a[@class="title"]', $article)->item(0);
            
            if ($titleNode) {
                $title = trim($titleNode->textContent);
                $link = $titleNode->getAttribute('href');
                if (strpos($link, 'http') === false) $link = 'https://nld.com.vn' . $link;

                $imgNode = $xpath->query('.//img', $article)->item(0);
                $image = '';
                if ($imgNode) {
                    $image = $imgNode->getAttribute('data-src') ?: $imgNode->getAttribute('src');
                }
                if (empty($image)) $image = 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?q=80&w=800';

                $descNode = $xpath->query('.//p[contains(@class, "sapo")] | .//div[@class="sapo"]', $article)->item(0);
                $desc = $descNode ? trim($descNode->textContent) : '';

                $item = [
                    'title' => $title,
                    'link' => $link,
                    'image' => $image,
                    'desc' => $desc,
                    'date' => date('d/m/Y')
                ];

                if ($index === 0) {
                    $featured = $item;
                } else {
                    if (count($news_list) < 10) $news_list[] = $item;
                }
            }
        }
    }

    if (!$featured) {
        $featured = [
            'title' => 'Giá vàng hôm nay tiếp tục phá đỉnh lịch sử',
            'link' => '#',
            'image' => 'https://images.unsplash.com/photo-1610375461246-3f19204c3803?q=80&w=1000',
            'desc' => 'Thị trường vàng trong nước sáng nay ghi nhận mức tăng kỷ lục, người dân đổ xô đi mua tích trữ...',
            'date' => date('d/m/Y')
        ];
        for($i=1; $i<=6; $i++) {
            $news_list[] = [
                'title' => "Biến động thị trường trang sức mùa cưới $i",
                'link' => '#',
                'image' => "https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=500&id=$i",
                'desc' => 'Các mẫu trang sức cưới năm nay thiên về sự tối giản nhưng tinh tế...',
                'date' => date('d/m/Y')
            ];
        }
    }

    $data = ['featured' => $featured, 'list' => $news_list];
    file_put_contents($cache_file, json_encode($data));
    return $data;
}

$data = getNldNews();
$featured = $data['featured'];
$list = $data['list'];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
    .news-wrapper {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        background-color: #fff;
        color: #1a1a1a;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Playfair Display', serif !important;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .news-hero {
        background-color: #f9f9f9;
        padding: 80px 0;
        border-bottom: 1px solid #eeeeee;
    }
    .hero-title { font-size: 3rem; color: #000; margin-bottom: 15px; }
    .hero-text { font-family: 'Playfair Display', serif; font-style: italic; color: #666; font-size: 1.1rem; }

    .card {
        border: 1px solid #e5e5e5;
        border-radius: 0 !important;
        background: #fff;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-color: #d4af37;
        transform: translateY(-2px);
    }
    
    .card-img-top { border-radius: 0 !important; }

    .card-title {
        font-size: 1.25rem;
        line-height: 1.4;
        margin-bottom: 10px;
    }
    .card-title a { color: #000; text-decoration: none; transition: color 0.2s; }
    .card-title a:hover { color: #d4af37; }

    .card-text { font-size: 0.9rem; color: #555; }

    .badge-lux {
        background-color: #000;
        color: #d4af37;
        border-radius: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
        padding: 6px 10px;
    }

    .btn-outline-lux {
        color: #d4af37;
        border: 1px solid #d4af37;
        border-radius: 0;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 8px 20px;
    }
    .btn-outline-lux:hover {
        background-color: #d4af37;
        color: #fff;
    }

    .card-header-lux {
        background-color: #fff;
        border-bottom: 2px solid #000;
        padding: 15px 20px;
    }
    .card-header-lux h6 { margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 1rem; }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 20px;
    }
    .list-group-item:hover { background-color: #fcfcfc; }
    
    .sidebar-link {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        display: block;
        margin-bottom: 5px;
    }
    .sidebar-link:hover { color: #d4af37; }
</style>
   
<div class="news-wrapper">
    
    <section class="news-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 fw-bold mb-3 hero-title">Tin Tức JSHOP</h1>
                    <p class="lead hero-text">Cập nhật xu hướng thị trường vàng & trang sức từ NLD</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-5"> <div class="col-lg-8">
                
                <?php if ($featured): ?>
                <div class="card mb-5 border-0">
                    <a href="<?= $featured['link'] ?>" target="_blank">
                        <img src="<?= $featured['image'] ?>" class="card-img-top" alt="Featured" style="height: 450px; object-fit: cover;">
                    </a>
                    <div class="card-body px-0 pt-4">
                        <span class="badge badge-lux mb-2">Tiêu Điểm</span>
                        <h3 class="card-title" style="font-size: 2rem;">
                            <a href="<?= $featured['link'] ?>" target="_blank"><?= $featured['title'] ?></a>
                        </h3>
                        <p class="card-text text-muted mb-3"><?= $featured['desc'] ?></p>
                        <a href="<?= $featured['link'] ?>" target="_blank" class="btn btn-outline-lux">Đọc tiếp</a>
                    </div>
                </div>
                <?php endif; ?>

                <hr class="mb-5" style="opacity: 0.1">

                <div class="row g-4">
                    <?php foreach ($list as $item): ?>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <a href="<?= $item['link'] ?>" target="_blank" class="overflow-hidden">
                                <img src="<?= $item['image'] ?>" class="card-img-top" alt="News" style="height: 220px; object-fit: cover; transition: transform 0.5s;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="<?= $item['link'] ?>" target="_blank"><?= $item['title'] ?></a>
                                </h5>
                                <p class="card-text text-muted small mb-3">
                                    <?= mb_strimwidth($item['desc'], 0, 100, "...") ?>
                                </p>
                                <a href="<?= $item['link'] ?>" target="_blank" class="mt-auto text-decoration-none text-uppercase fw-bold" style="font-size: 0.8rem; color: #d4af37;">
                                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="card mb-4">
                    <div class="card-header-lux">
                        <h6><i class="bi bi-lightning-charge me-2 text-warning"></i> Tin mới nhất</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php 
                        $sidebar_news = array_slice($list, 0, 5);
                        foreach($sidebar_news as $item): 
                        ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <a href="<?= $item['link'] ?>" target="_blank" class="sidebar-link w-75">
                                    <?= $item['title'] ?>
                                </a>
                                <small class="text-muted text-end" style="font-size: 0.7rem;">Mới</small>
                            </div>
                            <small class="text-muted fst-italic"><?= $item['date'] ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header-lux">
                        <h6><i class="bi bi-bookmarks me-2 text-dark"></i> Danh mục</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item d-flex justify-content-between align-items-center text-dark text-decoration-none">
                            <span>Xu hướng</span>
                            <span class="badge bg-light text-dark border rounded-0">5</span>
                        </a>
                        <a href="#" class="list-group-item d-flex justify-content-between align-items-center text-dark text-decoration-none">
                            <span>Bảo quản trang sức</span>
                            <span class="badge bg-light text-dark border rounded-0">3</span>
                        </a>
                        <a href="#" class="list-group-item d-flex justify-content-between align-items-center text-dark text-decoration-none">
                            <span>Phong thủy</span>
                            <span class="badge bg-light text-dark border rounded-0">7</span>
                        </a>
                        <a href="#" class="list-group-item d-flex justify-content-between align-items-center text-dark text-decoration-none">
                            <span>Khuyến mãi</span>
                            <span class="badge bg-light text-dark border rounded-0">2</span>
                        </a>
                    </div>
                </div>

                <div class="mt-4">
                    <img src="https://images.unsplash.com/photo-1602173574767-37ac01994b2a?q=80&w=600" 
                         alt="Ad" class="w-100" style="height: 350px; object-fit: cover;">
                    <div class="bg-black text-white text-center p-3">
                        <h5 style="font-family: 'Playfair Display', serif; margin:0;">JSHOP COLLECTION</h5>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>