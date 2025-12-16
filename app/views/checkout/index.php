<?php 
// Jshop/app/views/checkout/index.php
$page_title = $data['page_title'] ?? 'Thanh Toán'; 
include __DIR__ . "/../layouts/header.php"; 

$user_info = $data['user_info'] ?? [];
$cart_items = $data['cart_items'] ?? [];
$total_price = $data['total_price'] ?? 0;
?>

<div class="container my-5">
    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">THANH TOÁN ĐƠN HÀNG</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>checkout/process" method="POST">
        <input type="hidden" name="total_amount" value="<?= $total_price ?>">
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-geo-alt me-2"></i> 1. Thông Tin Nhận Hàng
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="receiver_name" class="form-label">Tên người nhận (*)</label>
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" 
                                value="<?= htmlspecialchars($user_info['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="receiver_phone" class="form-label">Số điện thoại (*)</label>
                            <input type="tel" class="form-control" id="receiver_phone" name="receiver_phone" 
                                value="<?= htmlspecialchars($user_info['phone'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Địa chỉ nhận hàng (*)</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required><?= htmlspecialchars($user_info['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-credit-card me-2"></i> 2. Phương Thức Thanh Toán
                    </div>
                    <div class="card-body">
                        
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="COD" checked>
                            <label class="form-check-label fw-bold" for="payment_cod">
                                Thanh toán khi nhận hàng (COD)
                            </label>
                            <p class="text-muted small mb-0">Thanh toán tiền mặt cho nhân viên giao hàng.</p>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="BANK_TRANSFER">
                            <label class="form-check-label fw-bold" for="payment_bank">
                                Chuyển khoản ngân hàng
                            </label>
                            <p class="text-muted small mb-0">Bạn sẽ nhận được thông tin ngân hàng sau khi đặt hàng.</p>
                        </div>
                        
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_momo" value="MOMO" disabled>
                            <label class="form-check-label fw-bold" for="payment_momo">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" style="height: 20px;"> Thanh toán MoMo (Chưa tích hợp)
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_zalopay" value="ZALOPAY" disabled>
                            <label class="form-check-label fw-bold" for="payment_zalopay">
                                <img src="https://static.taphoammo.net/images/zalo-pay-logo.png" alt="ZaloPay" style="height: 20px;"> Thanh toán ZaloPay (Chưa tích hợp)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-cart me-2"></i> 3. Tổng Quan Đơn Hàng
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính (<?= count($cart_items) ?> sản phẩm):</span>
                            <span class="fw-bold"><?= number_format($total_price) ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top border-2 border-dark">
                            <span class="fs-5 fw-bold text-dark">TỔNG CỘNG:</span>
                            <span class="fs-5 fw-bold" style="color: #d4af37;"><?= number_format($total_price) ?>đ</span>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg btn-dark py-3 fw-bold" style="color: #d4af37;">
                                HOÀN TẤT ĐẶT HÀNG
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>