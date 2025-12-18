<?php
/**
 * File: Jshop/app/views/checkout/mock_redirect.php
 * Chức năng: Giả lập cổng thanh toán MoMo/ZaloPay, tự động kiểm tra trạng thái và khôi phục giỏ hàng khi hủy.
 */
$page_title = $data['page_title'] ?? 'Cổng Thanh Toán';
include __DIR__ . "/../layouts/header.php";

$order_id = $data['order_id'] ?? 0;
$method = $data['method'] ?? 'Ví điện tử';

// Cấu hình logo và màu sắc theo ví
if ($method === 'MOMO') {
    $logo = 'https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png';
    $main_color = '#ae136e'; // Màu hồng MoMo
} else {
    // Logo ZaloPay chuẩn
    $logo = 'https://img.mservice.io/momo-payment/2020/03/9216790b-6029-4d69-82f5-f5b9d368e7d2.png'; 
    $main_color = '#008fe5'; // Màu xanh ZaloPay
}
?>

<div class="container my-5 py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 p-4" style="border-radius: 20px;">
                <div class="card-body">
                    <img src="<?= $logo ?>" alt="<?= $method ?>" class="mb-4 rounded shadow-sm" style="width: 80px; height: 80px; object-fit: contain;">
                    
                    <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Thanh toán qua <?= $method ?></h2>
                    <p class="text-muted mb-4">Vui lòng mở ứng dụng <strong><?= $method ?></strong> để quét mã QR thanh toán cho đơn hàng <span class="text-dark fw-bold">#<?= $order_id ?></span></p>

                    <div class="qr-container p-3 border rounded-4 bg-white d-inline-block mb-4 shadow-sm" style="border: 2px dashed <?= $main_color ?> !important;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=JSHOP_PAYMENT_<?= $order_id ?>" alt="QR Code" class="img-fluid" style="width: 200px;">
                    </div>

                    <div class="alert border-0 small mb-0" style="background-color: #f8f9fa;">
                        <div class="spinner-border spinner-border-sm me-2" role="status" style="color: <?= $main_color ?>;"></div>
                        Hệ thống đang chờ xác nhận thanh toán... <br>
                        <small class="text-muted">Đừng đóng trình duyệt, trang sẽ <b>tự động hoàn tất</b> khi tiền về.</small>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="<?= BASE_URL ?>checkout/cancelOrder/<?= $order_id ?>" 
                   class="btn btn-link text-muted text-decoration-none small" 
                   onclick="return confirm('Bạn có chắc chắn muốn hủy thanh toán và quay lại giỏ hàng?')">
                    <i class="bi bi-arrow-left me-1"></i> Hủy giao dịch & Quay lại giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const orderId = <?= $order_id ?>;
    
    // Cứ 2 giây gửi yêu cầu hỏi Server xem trạng thái đơn hàng đã là 'paid' chưa
    const checkPaymentStatus = setInterval(function() {
        fetch('<?= BASE_URL ?>checkout/checkStatus/' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    // Nếu Server phản hồi đã thanh toán
                    clearInterval(checkPaymentStatus); 
                    window.location.href = '<?= BASE_URL ?>checkout/success'; 
                }
            })
            .catch(err => console.error("Đang kết nối lại với hệ thống..."));
    }, 2000);
</script>

<style>
    body { background-color: #f8f9fa; }
    .qr-container img { transition: transform 0.3s ease; }
    .qr-container:hover img { transform: scale(1.05); }
    .card { border-radius: 20px; transition: 0.3s; }
    .card:hover { transform: translateY(-5px); }
</style>

<?php include __DIR__ . "/../layouts/footer.php"; ?>