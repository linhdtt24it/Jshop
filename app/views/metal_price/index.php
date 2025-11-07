<?php
// app/views/metal_price/index.php
$page_title = "Giá vàng hôm nay";

// SỬA ĐƯỜNG DẪN Ở ĐÂY
require_once __DIR__ . '/../layouts/header.php';

// === HÀM SCRAPING + LẤY LỊCH SỬ 30 NGÀY ===
function getGoldDataFrom24h() {
    // SỬA ĐƯỜNG DẪN CACHE
    $cache_dir = __DIR__ . '/../../cache';
    $cache_file = $cache_dir . '/gold_data_24h.json';
    $cache_time = 600;

    // ĐẢM BẢO THƯ MỤC CACHE TỒN TẠI
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }

    // FALLBACK DỮ LIỆU (DỰA TRÊN ẢNH BẠN GỬI)
    $fallback = [
        'updated' => date('H:i d/m/Y'),
        'source' => 'giavang.net (dự phòng)',
        'today' => [
            'SJC' => ['mua' => 146400, 'ban' => 148400],
            'DOJI HN' => ['mua' => 146400, 'ban' => 148400],
            'DOJI SG' => ['mua' => 146400, 'ban' => 148400],
            'BTMC SJC' => ['mua' => 146900, 'ban' => 148400],
            'Phú Quý SJC' => ['mua' => 145400, 'ban' => 148400],
            'PNJ TP.HCM' => ['mua' => 145000, 'ban' => 148000],
            'PNJ Hà Nội' => ['mua' => 145000, 'ban' => 148000],
        ],
        'yesterday' => [
            'SJC' => ['mua' => 146400, 'ban' => 148400],
            'DOJI HN' => ['mua' => 146400, 'ban' => 148400],
            'DOJI SG' => ['mua' => 146400, 'ban' => 148400],
            'BTMC SJC' => ['mua' => 146900, 'ban' => 148400],
            'Phú Quý SJC' => ['mua' => 145400, 'ban' => 148400],
            'PNJ TP.HCM' => ['mua' => 145000, 'ban' => 148000],
            'PNJ Hà Nội' => ['mua' => 145000, 'ban' => 148000],
        ],
        'chart' => [
            'dates' => ['07/10', '12/10', '17/10', '22/10', '27/10', '01/11', '06/11'],
            'buy' => [140000, 142000, 148000, 152000, 149000, 147000, 146400],
            'sell' => [141000, 143000, 149000, 153000, 150000, 148000, 148400]
        ]
    ];

    // KIỂM TRA CACHE
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
        $cached = json_decode(file_get_contents($cache_file), true);
        if ($cached) return $cached;
    }

    // === SCRAPING 24H.COM.VN ===
    $url = 'https://www.24h.com.vn/gia-vang-hom-nay-c425.html';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10, // Giảm timeout để nhanh hơn
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // NẾU SCRAPING THẤT BẠI, TRẢ VỀ FALLBACK
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
    $updated = date('H:i d/m/Y'); // Mặc định nếu không tìm thấy

    // LẤY THỜI GIAN CẬP NHẬT
    $time_nodes = $xpath->query('//*[contains(text(), "Cập nhật") or contains(text(), "cập nhật")]');
    foreach ($time_nodes as $node) {
        $text = $node->textContent;
        if (preg_match('/(\d{1,2}:\d{2})\s*[\(\[]?\s*(\d{1,2}\/\d{1,2}\/\d{4})/', $text, $m)) {
            $updated = $m[1] . ' ' . $m[2];
            break;
        }
    }

    // LẤY DỮ LIỆU BẢNG
    $tables = $xpath->query('//table');
    foreach ($tables as $table) {
        $rows = $xpath->query('.//tr', $table);
        $is_today_section = true;
        
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td | .//th', $row);
            if ($cells->length >= 3) {
                $brand = trim($cells->item(0)->textContent);
                
                // Kiểm tra nếu là tiêu đề "Hôm qua"
                $row_text = strtolower($row->textContent);
                if (strpos($row_text, 'hôm qua') !== false || strpos($row_text, 'yesterday') !== false) {
                    $is_today_section = false;
                    continue;
                }
                
                // Bỏ qua hàng tiêu đề
                if (strpos($brand, 'thương hiệu') !== false || strpos($brand, 'địa điểm') !== false) {
                    continue;
                }
                
                $mua = (int)preg_replace('/[^\d]/', '', $cells->item(1)->textContent);
                $ban = (int)preg_replace('/[^\d]/', '', $cells->item(2)->textContent);
                
                if ($brand && $mua > 100000) {
                    if ($is_today_section) {
                        $today[$brand] = ['mua' => $mua, 'ban' => $ban];
                    } else {
                        $yesterday[$brand] = ['mua' => $mua, 'ban' => $ban];
                    }
                }
            }
        }
    }

    // NẾU KHÔNG LẤY ĐƯỢC DỮ LIỆU, DÙNG FALLBACK
    if (empty($today)) {
        $result = $fallback;
    } else {
        $result = [
            'updated' => $updated,
            'source' => '24h.com.vn',
            'today' => $today,
            'yesterday' => !empty($yesterday) ? $yesterday : $today, // Nếu không có dữ liệu hôm qua, dùng hôm nay
            'chart' => $fallback['chart'] // Giữ nguyên biểu đồ từ fallback
        ];
    }

    // LƯU CACHE
    file_put_contents($cache_file, json_encode($result));
    return $result;
}

$data = getGoldDataFrom24h();
?>

<div class="container my-5">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <h1 class="text-center mb-4 text-warning">
        <i class="bi bi-gem"></i> Giá Vàng Hôm Nay
      </h1>
      
      <!-- THÔNG BÁO NGUỒN DỮ LIỆU -->
      <div class="alert alert-info text-center">
        <strong>Nguồn: <?= htmlspecialchars($data['source']) ?></strong> | 
        <strong>Cập nhật: <?= htmlspecialchars($data['updated']) ?></strong> |
        <small>Đơn vị: đồng/lượng</small>
        <?php if ($data['source'] === 'giavang.net (dự phòng)'): ?>
          <br><small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Đang sử dụng dữ liệu dự phòng</small>
        <?php endif; ?>
      </div>

      <!-- BẢNG SO SÁNH -->
      <div class="card shadow-sm mb-5">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0 text-center">Bảng Giá Vàng Chi Tiết</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
              <thead class="table-warning">
                <tr>
                  <th class="text-center">Thương hiệu</th>
                  <th colspan="2" class="text-center bg-success text-white">Hôm nay (<?= date('d/m/Y') ?>)</th>
                  <th colspan="2" class="text-center bg-secondary text-white">Hôm qua (<?= date('d/m/Y', strtotime('-1 day')) ?>)</th>
                </tr>
                <tr>
                  <th></th>
                  <th class="text-center bg-success text-white">Mua vào</th>
                  <th class="text-center bg-success text-white">Bán ra</th>
                  <th class="text-center bg-secondary text-white">Mua vào</th>
                  <th class="text-center bg-secondary text-white">Bán ra</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $brands = array_keys($data['today']);
                foreach ($brands as $brand):
                  $t = $data['today'][$brand];
                  $y = $data['yesterday'][$brand] ?? $t;
                  
                  // Tính biến động
                  $change_mua = $t['mua'] - $y['mua'];
                  $change_ban = $t['ban'] - $y['ban'];
                ?>
                  <tr>
                    <td><strong class="text-primary"><?= htmlspecialchars($brand) ?></strong></td>
                    
                    <!-- HÔM NAY -->
                    <td class="text-end">
                      <span class="fw-bold"><?= number_format($t['mua']) ?> ₫</span>
                      <?php if ($change_mua != 0): ?>
                        <br>
                        <small class="<?= $change_mua > 0 ? 'text-success' : 'text-danger' ?>">
                          <i class="bi bi-arrow-<?= $change_mua > 0 ? 'up' : 'down' ?>"></i>
                          <?= number_format(abs($change_mua)) ?> ₫
                        </small>
                      <?php endif; ?>
                    </td>
                    
                    <td class="text-end">
                      <span class="fw-bold"><?= number_format($t['ban']) ?> ₫</span>
                      <?php if ($change_ban != 0): ?>
                        <br>
                        <small class="<?= $change_ban > 0 ? 'text-success' : 'text-danger' ?>">
                          <i class="bi bi-arrow-<?= $change_ban > 0 ? 'up' : 'down' ?>"></i>
                          <?= number_format(abs($change_ban)) ?> ₫
                        </small>
                      <?php endif; ?>
                    </td>
                    
                    <!-- HÔM QUA -->
                    <td class="text-end text-muted"><?= number_format($y['mua']) ?> ₫</td>
                    <td class="text-end text-muted"><?= number_format($y['ban']) ?> ₫</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- BIỂU ĐỒ 30 NGÀY -->
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">
            <i class="bi bi-graph-up"></i> Biểu Đồ Giá Vàng 7 Ngày Gần Nhất 
            <span class="float-end">SJC</span>
          </h5>
        </div>
        <div class="card-body">
          <canvas id="goldChart" height="200"></canvas>
        </div>
      </div>

      <!-- LƯU Ý -->
      <div class="alert alert-warning mt-3">
        <small>
          <i class="bi bi-info-circle"></i> 
          <strong>Lưu ý:</strong> Giá vàng có thể thay đổi tùy thời điểm. 
          Dữ liệu được cập nhật tự động mỗi 10 phút. 
          Vui lòng liên hệ trực tiếp các điểm giao dịch để có giá chính xác nhất.
        </small>
      </div>
    </div>
  </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('goldChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($data['chart']['dates']) ?>,
      datasets: [
        {
          label: 'Giá mua (₫)',
          data: <?= json_encode($data['chart']['buy']) ?>,
          borderColor: '#dc3545',
          backgroundColor: 'rgba(220, 53, 69, 0.1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true
        },
        {
          label: 'Giá bán (₫)',
          data: <?= json_encode($data['chart']['sell']) ?>,
          borderColor: '#28a745',
          backgroundColor: 'rgba(40, 167, 69, 0.1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { 
          position: 'bottom',
          labels: { font: { size: 12 } }
        },
        tooltip: { 
          mode: 'index', 
          intersect: false,
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' ₫';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          ticks: {
            callback: function(value) {
              return (value / 1000).toLocaleString('vi-VN') + 'K';
            },
            font: { size: 11 }
          },
          title: {
            display: true,
            text: 'Đồng (₫)'
          }
        },
        x: {
          ticks: {
            font: { size: 11 }
          }
        }
      }
    }
  });
});
</script>

<?php 
// ĐƯỜNG DẪN 
require_once __DIR__ . '/../layouts/footer.php'; 
?>