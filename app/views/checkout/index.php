<?php 
/**
 * File: Jshop/app/views/checkout/index.php
 * Chức năng: Hiển thị trang thanh toán và Modal thông báo thành công.
 */
$page_title = $data['page_title'] ?? 'Thanh Toán'; 
include __DIR__ . "/../layouts/header.php"; 

// Khởi tạo dữ liệu từ Controller
$user_info = $data['user_info'] ?? [];
$cart_items = $data['cart_items'] ?? [];
$total_price = $data['total_price'] ?? 0;
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">THANH TOÁN ĐƠN HÀNG</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>checkout/process" method="POST">
        <input type="hidden" name="total_amount" value="<?= $total_price ?>">
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-dark text-white fw-bold py-3">
                        <i class="bi bi-geo-alt me-2"></i> 1. Thông Tin Nhận Hàng
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="receiver_name" class="form-label fw-semibold">Tên người nhận (*)</label>
                            <input type="text" class="form-control form-control-lg fs-6" id="receiver_name" name="receiver_name" 
                                value="<?= htmlspecialchars($user_info['name'] ?? '') ?>" placeholder="Nhập tên người nhận hàng" required>
                        </div>
                        <div class="mb-3">
                            <label for="receiver_phone" class="form-label fw-semibold">Số điện thoại (*)</label>
                            <input type="tel" class="form-control form-control-lg fs-6" id="receiver_phone" name="receiver_phone" 
                                value="<?= htmlspecialchars($user_info['phone'] ?? '') ?>" placeholder="Số điện thoại liên hệ" required>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-semibold">Địa chỉ nhận hàng (*)</label>
                            <textarea class="form-control form-control-lg fs-6" id="shipping_address" name="shipping_address" rows="4" 
                                placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." required><?= htmlspecialchars($user_info['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold py-3">
                        <i class="bi bi-credit-card me-2"></i> 2. Phương Thức Thanh Toán
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check mb-3 p-3 border rounded shadow-sm hover-border">
                            <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_cod" value="COD" checked>
                            <label class="form-check-label fw-bold d-block" for="payment_cod">
                                Thanh toán khi nhận hàng (COD)
                                <small class="text-muted d-block fw-normal">Quý khách sẽ thanh toán tiền mặt cho shipper khi nhận được hàng.</small>
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded hover-border">
                            <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_bank" value="BANK_TRANSFER">
                            <label class="form-check-label fw-bold d-block" for="payment_bank">
                                <i class="bi bi-bank me-2 text-danger"></i> Chuyển khoản ngân hàng liên kết
                                <small class="text-muted d-block fw-normal">Thông tin số tài khoản sẽ được hiển thị sau khi quý khách ấn đặt hàng.</small>
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded hover-border">
                            <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_momo" value="MOMO">
                            <label class="form-check-label fw-bold d-block" for="payment_momo">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" width="20" height="20" class="me-2 rounded shadow-sm"> Thanh toán qua ví MoMo
                                <small class="text-muted d-block fw-normal">Thanh toán nhanh chóng, an toàn qua ứng dụng MoMo.</small>
                            </label>
                        </div>

                        <div class="form-check mb-0 p-3 border rounded hover-border">
                            <input class="form-check-input mt-2" type="radio" name="payment_method" id="payment_zalopay" value="ZALOPAY">
                            <label class="form-check-label fw-bold d-block" for="payment_zalopay">
                                <img src="https://img.mservice.io/momo-payment/2020/03/9216790b-6029-4d69-82f5-f5b9d368e7d2.png" width="20" height="20" class="me-2 rounded shadow-sm" style="filter: hue-rotate(140deg);"> Thanh toán qua ví ZaloPay
                                <small class="text-muted d-block fw-normal">Thanh toán tiện lợi qua ứng dụng ZaloPay.</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white fw-bold py-3">
                        <i class="bi bi-cart me-2"></i> 3. Tổng Quan Đơn Hàng
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính (<?= count($cart_items) ?> sản phẩm):</span>
                            <span class="fw-bold"><?= number_format($total_price) ?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success fw-bold italic">Miễn phí toàn quốc</span>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top border-2 border-dark mt-2">
                            <span class="fs-5 fw-bold text-dark">TỔNG CỘNG:</span>
                            <span class="fs-4 fw-bold text-danger"><?= number_format($total_price) ?>₫</span>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg btn-dark py-3 fw-bold text-uppercase shadow" style="letter-spacing: 2px;">
                                HOÀN TẤT ĐẶT HÀNG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (isset($_SESSION['checkout_success']) && $_SESSION['checkout_success']): ?>
    <div class="modal fade show" id="successModal" tabindex="-1" role="dialog" style="display: block; background: rgba(0,0,0,0.75); z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-5">
                    <div class="mb-4 text-success animate-bounce">
                        <i class="bi bi-check-circle-fill" style="font-size: 5.5rem;"></i>
                    </div>
                    
                    <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: #1a1a1a;">Đặt hàng thành công!</h2>
                    
                    <p class="text-muted mb-4 fs-5">
                        Cảm ơn bạn đã tin tưởng <strong>JSHOP Luxury</strong>. 
                        Đơn hàng <span class="text-dark fw-bold">#<?= $_SESSION['last_order_id'] ?? 'N/A' ?></span> đã được hệ thống ghi nhận.
                    </p>
                    
                    <hr class="my-4">
                    
                    <div class="d-grid gap-3">
                        <a href="<?= BASE_URL ?>product" class="btn btn-dark btn-lg py-3 fw-bold shadow-sm">TIẾP TỤC MUA SẮM</a>
                    </div>
                    
                    <p class="mt-4 small text-muted italic">Nhấn vào vùng trống phía ngoài để đóng thông báo.</p>
                </div>
            </div>
        </div>
    </div>

    <?php 
        unset($_SESSION['checkout_success']);
        unset($_SESSION['last_order_id']);
    ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('successModal');
            modal.onclick = function(e) {
                if(e.target === this) {
                    this.style.transition = "opacity 0.4s ease";
                    this.style.opacity = "0";
                    setTimeout(() => {
                        this.style.display = 'none';
                    }, 400);
                }
            }
        });
    </script>
<?php endif; ?>

<style>
    .animate-bounce {
        animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); opacity: 1; }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .hover-border:hover {
        border-color: #1a1a1a !important;
        background-color: #f9f9f9;
        cursor: pointer;
    }
</style>

<?php include __DIR__ . "/../layouts/footer.php"; ?>