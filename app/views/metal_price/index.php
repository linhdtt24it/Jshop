<?php
// app/views/metal_price/index.php
$page_title = "Giá vàng hôm nay - JSHOP";
require_once __DIR__ . '/../layouts/header.php';

// ============================================================
// 1. PHP LOGIC: GIỮ NGUYÊN (KHÔNG ĐỤNG VÀO)
// ============================================================
function getGoldDataFrom24h() {
    $cache_dir = __DIR__ . '/../../cache';
    $cache_file = $cache_dir . '/gold_data_24h.json';
    $cache_time = 600;

    if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);

    $fallback = [
        'updated' => date('H:i d/m/Y'),
        'source' => 'giavang.net (dự phòng)',
        'today' => [
            'SJC' => ['mua' => 74000000, 'ban' => 76500000],
            'DOJI HN' => ['mua' => 73950000, 'ban' => 76450000],
            'PNJ TP.HCM' => ['mua' => 74100000, 'ban' => 76600000],
        ],
        'yesterday' => [],
        'chart' => [
            'dates' => ['01/12', '05/12', '10/12', '15/12', '20/12', '25/12', '30/12'],
            'buy' => [72000, 72500, 73000, 72800, 73500, 74000, 74200],
            'sell' => [74000, 74500, 75000, 74800, 75500, 76000, 76500]
        ]
    ];

    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
        $cached = json_decode(file_get_contents($cache_file), true);
        if ($cached) return $cached;
    }

    $url = 'https://www.24h.com.vn/gia-vang-hom-nay-c425.html';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$html || $http_code !== 200) {
        file_put_contents($cache_file, json_encode($fallback));
        return $fallback;
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $today = [];
    $yesterday = [];
    $updated = date('H:i d/m/Y');

    $time_nodes = $xpath->query('//*[contains(text(), "Cập nhật") or contains(text(), "cập nhật")]');
    foreach ($time_nodes as $node) {
        $text = $node->textContent;
        if (preg_match('/(\d{1,2}:\d{2})\s*[\(\[]?\s*(\d{1,2}\/\d{1,2}\/\d{4})/', $text, $m)) {
            $updated = $m[1] . ' ' . $m[2];
            break;
        }
    }

    $tables = $xpath->query('//table');
    foreach ($tables as $table) {
        $rows = $xpath->query('.//tr', $table);
        $is_today_section = true;
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td | .//th', $row);
            if ($cells->length >= 3) {
                $brand = trim($cells->item(0)->textContent);
                $row_text = strtolower($row->textContent);
                if (strpos($row_text, 'hôm qua') !== false || strpos($row_text, 'yesterday') !== false) {
                    $is_today_section = false;
                    continue;
                }
                if (strpos($brand, 'thương hiệu') !== false || strpos($brand, 'địa điểm') !== false) {
                    continue;
                }
                $mua = (int)preg_replace('/[^\d]/', '', $cells->item(1)->textContent);
                $ban = (int)preg_replace('/[^\d]/', '', $cells->item(2)->textContent);
                if ($brand && $mua > 1000) {
                    if ($is_today_section) {
                        $today[$brand] = ['mua' => $mua, 'ban' => $ban];
                    } else {
                        $yesterday[$brand] = ['mua' => $mua, 'ban' => $ban];
                    }
                }
            }
        }
    }

    if (empty($today)) {
        $result = $fallback;
    } else {
        $result = [
            'updated' => $updated,
            'source' => '24h.com.vn',
            'today' => $today,
            'yesterday' => !empty($yesterday) ? $yesterday : $today,
            'chart' => $fallback['chart']
        ];
    }
    file_put_contents($cache_file, json_encode($result));
    return $result;
}
$data = getGoldDataFrom24h();
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
    /* Font chung */
    .gold-page {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        color: #1a1a1a;
        background-color: #fff;
    }

    /* Tiêu đề dùng Playfair */
    h1, h2, h3, h4, h5 {
        font-family: 'Playfair Display', serif !important;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* HERO SECTION - Copy y hệt Contact */
    .gold-hero {
        background-color: #f9f9f9;
        padding: 80px 0;
        border-bottom: 1px solid #eeeeee;
        margin-bottom: 50px;
    }

    /* CARD STYLE - Copy y hệt Contact */
    .luxury-card {
        border: 1px solid #eeeeee;
        border-radius: 0; /* Vuông vức */
        box-shadow: none;
        transition: all 0.3s ease;
        background: #fff;
        margin-bottom: 30px;
    }
    
    .luxury-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #dddddd;
    }

    .luxury-header {
        background-color: #ffffff;
        border-bottom: 1px solid #eeeeee;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .luxury-header h3 {
        margin: 0;
        font-size: 1.25rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* TABLE STYLE */
    .table-luxury {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .table-luxury thead th {
        background-color: #fff;
        color: #333;
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 15px 20px;
        border-bottom: 1px solid #000; /* Viền đen thay vì xám */
        text-align: center;
        letter-spacing: 0.5px;
    }

    .table-luxury tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    /* Zebra Striping nhẹ */
    .table-luxury tbody tr:nth-child(even) { background-color: #fafafa; }
    .table-luxury tbody tr:hover { background-color: #fcfcfc; }

    /* Typography Bảng */
    .brand-name {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: #000;
        font-size: 1.05rem;
    }

    .price-main {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 600;
        color: #000;
        font-size: 1rem;
    }

    .price-sub {
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 400;
        color: #999; /* Xám nhạt cho giá hôm qua */
        font-size: 0.9rem;
    }

    /* Biến động giá */
    .diff {
        display: block;
        font-size: 0.75rem;
        margin-top: 4px;
        font-weight: 500;
    }
    .up { color: #15803d; }
    .down { color: #b91c1c; }

    /* Responsive */
    @media (max-width: 992px) {
        .table-luxury thead { display: none; }
        .table-luxury tbody tr {
            display: block; border: 1px solid #eee; margin-bottom: 20px;
        }
        .table-luxury tbody td {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 15px; text-align: right;
        }
        .table-luxury td:first-child {
            background: #f9f9f9; font-weight: 600; text-align: left;
        }
        .table-luxury td::before {
            content: attr(data-label); font-weight: 500; color: #666; font-size: 0.85rem;
        }
    }
</style>

<div class="gold-page">
    
    <section class="gold-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">Thị Trường Vàng</h1>
                    <p class="lead mb-0 text-muted" style="font-family: 'Playfair Display', serif; font-style: italic;">
                        Cập nhật lúc: <?= htmlspecialchars($data['updated']) ?> (Nguồn: <?= htmlspecialchars($data['source']) ?>)
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-5">
            <div class="col-lg-10 mx-auto">
                
                <div class="luxury-card">
                    <div class="luxury-header">
                        <h3>Bảng Giá Chi Tiết</h3>
                        <div class="text-muted small" style="font-family: 'Be Vietnam Pro', sans-serif;">
                            Đơn vị: VNĐ/Lượng
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-luxury">
                                <thead>
                                    <tr>
                                        <th style="width: 25%; text-align: left;">Thương hiệu</th>
                                        <th style="width: 18%">Mua (Hôm nay)</th>
                                        <th style="width: 18%">Bán (Hôm nay)</th>
                                        <th style="width: 18%; color: #999;">Mua (Hôm qua)</th>
                                        <th style="width: 18%; color: #999;">Bán (Hôm qua)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($data['today'] as $brand => $t):
                                        $y = $data['yesterday'][$brand] ?? $t;
                                        $diff_mua = $t['mua'] - $y['mua'];
                                        $diff_ban = $t['ban'] - $y['ban'];
                                    ?>
                                    <tr>
                                        <td data-label="Thương hiệu">
                                            <span class="brand-name"><?= htmlspecialchars($brand) ?></span>
                                        </td>

                                        <td data-label="Mua hôm nay" class="text-end text-lg-center">
                                            <span class="price-main"><?= number_format($t['mua']) ?></span>
                                            <?php if($diff_mua != 0): ?>
                                                <span class="diff <?= $diff_mua > 0 ? 'up' : 'down' ?>">
                                                    <?= $diff_mua > 0 ? '▲' : '▼' ?> <?= number_format(abs($diff_mua)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td data-label="Bán hôm nay" class="text-end text-lg-center">
                                            <span class="price-main"><?= number_format($t['ban']) ?></span>
                                            <?php if($diff_ban != 0): ?>
                                                <span class="diff <?= $diff_ban > 0 ? 'up' : 'down' ?>">
                                                    <?= $diff_ban > 0 ? '▲' : '▼' ?> <?= number_format(abs($diff_ban)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td data-label="Mua hôm qua" class="text-end text-lg-center">
                                            <span class="price-sub"><?= number_format($y['mua']) ?></span>
                                        </td>

                                        <td data-label="Bán hôm qua" class="text-end text-lg-center">
                                            <span class="price-sub"><?= number_format($y['ban']) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="luxury-header">
                        <h3>Biểu Đồ SJC (7 Ngày Gần Nhất)</h3>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="goldChart" height="100"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('goldChart').getContext('2d');
    
    // Gradient vàng sang trọng
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(212, 175, 55, 0.4)'); 
    gradient.addColorStop(1, 'rgba(212, 175, 55, 0.0)'); 

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($data['chart']['dates']) ?>,
            datasets: [
                {
                    label: 'Giá Bán (Triệu đồng)',
                    data: <?= json_encode($data['chart']['sell']) ?>,
                    borderColor: '#d4af37', // Vàng Gold nổi bật
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#d4af37',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Giá Mua (Triệu đồng)',
                    data: <?= json_encode($data['chart']['buy']) ?>,
                    borderColor: '#1a1a1a', // Đen nổi bật
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#1a1a1a',
                    pointRadius: 4,
                    fill: false,
                    tension: 0.4,
                    borderDash: [5, 5] // Nét đứt
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: { 
                        font: { family: "'Be Vietnam Pro', sans-serif", size: 12 },
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    backgroundColor: '#000',
                    titleFont: { family: "'Playfair Display', serif" },
                    bodyFont: { family: "'Be Vietnam Pro', sans-serif" },
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        font: { family: "'Be Vietnam Pro', sans-serif" },
                        callback: function(value) { return (value/1000) + 'k'; }
                    },
                    grid: { color: '#f0f0f0' }
                },
                x: {
                    ticks: { font: { family: "'Be Vietnam Pro', sans-serif" } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>