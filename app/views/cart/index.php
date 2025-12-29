<?php
$page_title = "Giỏ Hàng - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';

$cart_items = $data['items'] ?? [];
?>

<style>
    .cart-page {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        background-color: #fff;
        color: #1a1a1a;
        padding-bottom: 80px;
    }
    .page-header {
        text-align: center;
        padding: 50px 0;
        border-bottom: 1px solid #eee;
        margin-bottom: 40px;
        background: #f9f9f9;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table thead th {
        text-align: left;
        padding: 15px;
        border-bottom: 2px solid #000;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .cart-table tbody td {
        padding: 25px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    .product-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .product-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border: 1px solid #eee;
    }
    .product-name {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #000;
        text-decoration: none;
        display: block;
        margin-bottom: 5px;
    }
    .product-sku {
        font-size: 0.8rem;
        color: #999;
    }
    .qty-control {
        display: inline-flex;
        border: 1px solid #ddd;
        height: 40px;
    }
    .qty-btn {
        width: 35px;
        border: none;
        background: #fff;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #000;
    }
    .qty-input {
        width: 40px;
        border: none;
        text-align: center;
        font-weight: 600;
        outline: none;
        background: #f9f9f9;
    }
    .btn-remove {
        color: #ccc;
        font-size: 1.2rem;
        transition: 0.2s;
    }
    .btn-remove:hover { color: #dc3545; }
    .cart-summary {
        background: #fcfcfc;
        border: 1px solid #eee;
        padding: 30px;
        position: sticky;
        top: 20px;
    }
    .summary-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.95rem;
    }
    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #000;
        font-size: 1.2rem;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
    }
    .text-gold { color: #d4af37; }
    .btn-checkout {
        display: block;
        width: 100%;
        background: #000;
        color: #d4af37;
        text-align: center;
        padding: 15px;
        text-transform: uppercase;
        font-weight: 600;
        text-decoration: none;
        margin-top: 25px;
        border: none;
        transition: 0.3s;
    }
    .btn-checkout:hover { background: #d4af37; color: #000; }
</style>

<div class="cart-page">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Giỏ Hàng Của Bạn</h1>
        </div>
    </div>

    <div class="container">
        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5">
                <i class="fas fa-cart-arrow-down" style="font-size: 4rem; color: #eee;"></i>
                <h3 class="mt-3">Giỏ hàng trống</h3>
                <p>Quý khách chưa chọn được sản phẩm ưng ý?</p>
                <a href="<?= BASE_URL ?>product" class="btn btn-dark mt-3">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th style="width: 45%;">Sản phẩm</th>
                                    <th style="width: 20%;">Đơn giá</th>
                                    <th style="width: 20%;">Số lượng</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($cart_items as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <img src="<?= $item['image'] ?>" class="product-img" alt="<?= htmlspecialchars($item['name']) ?>">
                                            <div>
                                                <a href="<?= BASE_URL ?>product/detail/<?= $item['product_id'] ?>" class="product-name">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </a>
                                                <span class="product-sku">Mã: #<?= $item['product_id'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td style="font-weight: 600;">
                                        <?= number_format($item['price'], 0, ',', '.') ?>đ
                                    </td>
                                    
                                    <td>
                                        <div class="qty-control">
                                            <a href="<?= BASE_URL ?>cart/update?id=<?= $item['product_id'] ?>&qty=<?= $item['quantity']-1 ?>" class="qty-btn">-</a>
                                            <input type="number" value="<?= $item['quantity'] ?>" class="qty-input" readonly>
                                            <a href="<?= BASE_URL ?>cart/update?id=<?= $item['product_id'] ?>&qty=<?= $item['quantity']+1 ?>" class="qty-btn">+</a>
                                        </div>
                                    </td>
                                    
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>cart/removeItem?id=<?= $item['product_id'] ?>" class="btn-remove" title="Xóa khỏi giỏ">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h3 class="summary-title">Đơn Hàng</h3>
                        
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span style="font-weight: 600;"><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Tổng cộng:</span>
                            <span class="text-gold"><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>

                        <a href="<?= BASE_URL ?>checkout" class="btn-checkout">Tiến Hành Thanh Toán</a>
                        
                        <a href="<?= BASE_URL ?>product" class="btn-continue" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none; font-size: 0.9rem;">
                            ← Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>