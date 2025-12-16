<?php 
// Jshop/app/views/staff/orders/detail.php (KHÔNG CÓ INCLUDE HEADER/FOOTER)
$page_title = $data['page_title'] ?? 'Chi tiết Đơn hàng';
$order = $data['order'] ?? null;
$BASE_URL = BASE_URL ?? '/Jshop/public/';

function displayStatus($status) {
    switch ($status) {
        case 'pending': return '<span class="badge bg-warning text-dark">Chờ thanh toán</span>';
        case 'processing': return '<span class="badge bg-info">Đang xử lý</span>';
        case 'shipped': return '<span class="badge bg-primary">Đang giao hàng</span>';
        case 'completed': return '<span class="badge bg-success">Đã hoàn thành</span>';
        case 'cancelled': return '<span class="badge bg-danger">Đã hủy</span>';
        case 'paid': return '<span class="badge bg-success">Đã thanh toán</span>';
        case 'failed': return '<span class="badge bg-danger">Thất bại</span>';
        default: return $status;
    }
}
?>

<div class="page-header">
    <div>
        <h1>Chi tiết Đơn hàng #<?= $order['order_id'] ?? 'N/A' ?></h1>
        <p style="color: #64748b;">Thông tin chi tiết về đơn hàng và các mặt hàng.</p>
    </div>
    <a href="<?= $BASE_URL ?>order/staffIndex" class="btn-primary"><i class="fas fa-arrow-left"></i> Trở về</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-danger text-center">Không tìm thấy đơn hàng này.</div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold bg-secondary text-white">
                    Trạng thái Đơn hàng
                </div>
                <div class="card-body">
                    <p class="mb-2">Trạng thái hiện tại: 
                        <strong id="current-order-status">
                            <?= displayStatus($order['order_status']) ?>
                        </strong>
                    </p>
                    <p class="mb-4">Thanh toán: 
                        <strong id="current-payment-status">
                            <?= displayStatus($order['payment_status']) ?>
                        </strong>
                    </p>

                    <div class="mt-3 border-top pt-3">
                        <label for="update-status" class="form-label fw-bold">Cập nhật trạng thái:</label>
                        <select id="update-status" class="form-select mb-3">
                            <option value="" disabled selected>Chọn trạng thái mới</option>
                            <option value="processing">Đang xử lý</option>
                            <option value="shipped">Đang giao hàng</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Hủy đơn</option>
                        </select>
                        <button id="btn-update-status" class="btn btn-primary w-100" data-order-id="<?= $order['order_id'] ?>">
                            Cập nhật
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold bg-secondary text-white">
                    Thông tin Chung
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Khách hàng:</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></dd>
                        
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($order['customer_email'] ?? 'N/A') ?></dd>
                        
                        <dt class="col-sm-4">Điện thoại:</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($order['receiver_phone']) ?></dd>
                        
                        <dt class="col-sm-4">Địa chỉ giao hàng:</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($order['shipping_address']) ?></dd>
                        
                        <dt class="col-sm-4">PT Thanh toán:</dt>
                        <dd class="col-sm-8 fw-bold text-primary"><?= htmlspecialchars($order['payment_method']) ?></dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-bold bg-secondary text-white">
                    Sản phẩm (<?= count($order['items'] ?? []) ?>)
                </div>
                <ul class="list-group list-group-flush">
                    <?php 
                    $subtotal = 0;
                    foreach ($order['items'] ?? [] as $item): 
                        $item_total = ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                        $subtotal += $item_total;
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="<?= htmlspecialchars($item['product_image'] ?? $BASE_URL.'images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <small class="text-muted"><?= number_format($item['price']) ?>đ x <?= $item['quantity'] ?></small>
                                </div>
                            </div>
                            <span class="fw-bold text-danger"><?= number_format($item_total) ?>đ</span>
                        </li>
                    <?php endforeach; ?>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <span class="fs-5 fw-bold">TỔNG TIỀN:</span>
                        <span class="fs-5 fw-bold" style="color: var(--color-primary);"><?= number_format($order['total_amount']) ?>đ</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnUpdate = document.getElementById('btn-update-status');
    const selectStatus = document.getElementById('update-status');
    const orderId = btnUpdate ? btnUpdate.getAttribute('data-order-id') : null;
    const BASE_URL = '<?= BASE_URL ?? "/Jshop/public/" ?>';

    if (btnUpdate && orderId) {
        btnUpdate.addEventListener('click', function() {
            const newStatus = selectStatus.value;
            if (!newStatus) {
                alert('Vui lòng chọn trạng thái mới.');
                return;
            }

            fetch(BASE_URL + 'order/updateStatus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `order_id=${orderId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.reload(); 
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi kết nối hoặc xử lý server.');
            });
        });
    }
});
</script>