<?php
// Jshop/app/views/checkout/success.php
$page_title = "Đặt Hàng Thành Công - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';

// Lấy thông tin đơn hàng từ Controller gửi qua
$order = $data['order'] ?? null;
?>

<style>
    .success-page {
        padding: 100px 0;
        text-align: center;
        background: #fff;
        font-family: 'Be Vietnam Pro', sans-serif;
    }
    .success-icon {
        font-size: 5rem;
        color: #d4af37; /* Màu vàng Gold đặc trưng */
        margin-bottom: 30px;
    }
    .success-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 15px;
        text-transform: uppercase;
    }
    .success-msg {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 40px;
    }
    .order-details {
        max-width: 500px;
        margin: 0 auto 40px;
        padding: 30px;
        border: 1px solid #eee;
        background: #f9f9f9;
        text-align: left;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        border-bottom: 1px dashed #ddd;
        padding-bottom: 10px;
    }
    .btn-home {
        display: inline-block;
        background: #000;
        color: #d4af37;
        padding: 15px 40px;
        text-transform: uppercase;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-home:hover {
        background: #d4af37;
        color: #000;
    }
</style>

<div class="success-page">
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">Đặt hàng thành công</h1>
        <p class="success-msg">Cảm ơn quý khách đã tin tưởng và lựa chọn JSHOP Luxury. Đơn hàng của quý khách đang được xử lý.</p>

        <?php if ($order): ?>
            <div class="order-details">
                <div class="detail-row">
                    <span>Mã đơn hàng:</span>
                    <strong>#<?= $order['order_id'] ?></strong>
                </div>
                <div class="detail-row">
                    <span>Tổng thanh toán:</span>
                    <strong class="text-gold"><?= number_format($order['total'], 0, ',', '.') ?>đ</strong>
                </div>
                <div class="detail-row">
                    <span>Trạng thái:</span>
                    <span class="badge bg-secondary">Chờ xác nhận</span>
                </div>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="<?= BASE_URL ?>" class="btn-home">Quay về trang chủ</a>
            <p style="margin-top: 20px;">
                <small>Bộ phận CSKH sẽ liên hệ với quý khách trong vòng 15 phút để xác nhận đơn hàng.</small>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>