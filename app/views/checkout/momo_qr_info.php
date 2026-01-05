<?php
$page_title = $data['page_title'] ?? 'Thanh toán Vietinbank QR';
include __DIR__ . "/../layouts/header.php";

$order = $data['order'] ?? [];
$user = $data['user'] ?? [];
$order_id = $data['order_id'] ?? 0;
?>

<div class="container my-5 py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 p-4" style="border-radius: 20px;">
                <div class="card-body">
                    <img src="https://www.vietinbank.vn/web/portal/assets/images/logo-vi.png" alt="Vietinbank" class="mb-4 rounded shadow-sm" style="height: 80px; object-fit: contain;">
                    
                    <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Thanh toán qua Vietinbank QR</h2>
                    <p class="text-muted mb-4">Vui lòng mở ứng dụng <strong>Ngân hàng</strong> của bạn để quét mã QR thanh toán cho đơn hàng <span class="text-dark fw-bold">#<?= $order_id ?></span></p>

                    <div class="qr-container p-3 border rounded-4 bg-white d-inline-block mb-4 shadow-sm" style="border: 2px dashed #0056a6 !important;">
                        <?php
                            $bank_bin = '970415'; // Vietinbank BIN
                            $account_no = '107881666265';
                            $account_name = 'PHẠM THÁI BẢO';
                            $amount = $order['total_amount'];
                            $info = "Thanh toan don hang JSHOP " . $order_id;
                            $qr_url = "https://img.vietqr.io/image/$bank_bin-$account_no-compact2.png?amount=$amount&addInfo=" . urlencode($info) . "&accountName=" . urlencode($account_name);
                        ?>
                        <img src="<?= $qr_url ?>" alt="QR Code" class="img-fluid" style="width: 200px;">
                    </div>

                    <div class="alert alert-info border-0 small mb-0">
                        <strong>Lưu ý:</strong> Đơn hàng của bạn sẽ được xử lý sau khi chúng tôi xác nhận đã nhận được thanh toán. Vui lòng ghi rõ mã đơn hàng <strong>#<?= $order_id ?></strong> trong nội dung chuyển khoản.
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="<?= BASE_URL ?>" class="btn btn-dark">
                    <i class="bi bi-arrow-left me-1"></i> Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .qr-container img { transition: transform 0.3s ease; }
    .qr-container:hover img { transform: scale(1.05); }
    .card { border-radius: 20px; transition: 0.3s; }
    .card:hover { transform: translateY(-5px); }
</style>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
