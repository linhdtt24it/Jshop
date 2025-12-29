<?php
$page_title = $data['page_title'] ?? 'Cổng Thanh Toán';
include __DIR__ . "/../layouts/header.php";

$order_id = $data['order_id'] ?? 0;
$method = $data['method'] ?? 'Ví điện tử';
$qr_image = ($method === 'MOMO') 
    ? 'https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png' 
    : 'https://img.mservice.io/momo-payment/2020/03/9216790b-6029-4d69-82f5-f5b9d368e7d2.png';
?>

<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-lg border-0 p-4">
                <div class="card-body">
                    <img src="<?= $qr_image ?>" alt="<?= $method ?>" class="mb-4 rounded shadow-sm" style="width: 80px; height: 80px; <?= ($method === 'ZALOPAY') ? 'filter: hue-rotate(140deg);' : '' ?>">
                    
                    <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Thanh toán qua <?= $method ?></h2>
                    <p class="text-muted mb-4">Vui lòng sử dụng ứng dụng <strong><?= $method ?></strong> để quét mã QR dưới đây cho đơn hàng <span class="text-dark fw-bold">#<?= $order_id ?></span></p>

                    <div class="qr-container p-3 border rounded bg-white d-inline-block mb-4 shadow-sm" style="border: 2px dashed #d4af37 !important;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=JSHOP_ORDER_<?= $order_id ?>" alt="QR Code" class="img-fluid">
                    </div>

                    <div class="alert alert-warning border-0 small mb-4">
                        <i class="bi bi-info-circle me-2"></i> Đây là môi trường thử nghiệm. Vui lòng nhấn nút bên dưới để xác nhận giả lập thanh toán thành công.
                    </div>

                    <div class="d-grid gap-3">
                        <a href="<?= BASE_URL ?>checkout/success" class="btn btn-dark btn-lg py-3 fw-bold text-uppercase" style="letter-spacing: 1px; color: #d4af37;">
                            XÁC NHẬN ĐÃ THANH TOÁN
                        </a>
                        <a href="<?= BASE_URL ?>checkout/index" class="btn btn-link text-muted text-decoration-none small">
                            Hủy giao dịch và quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .qr-container img {
        transition: transform 0.3s ease;
    }
    .qr-container:hover img {
        transform: scale(1.02);
    }
    body {
        background-color: #f8f9fa;
    }
</style>

<?php include __DIR__ . "/../layouts/footer.php"; ?>