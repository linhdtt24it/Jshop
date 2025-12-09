<?php
// app/views/cart/index.php
$page_title = "Giỏ Hàng - JSHOP Luxury";
require_once __DIR__ . '/../layouts/header.php';

// --- DỮ LIỆU GIẢ LẬP (1 SẢN PHẨM ĐỂ TEST) ---
$cart_items = [
    [
        'id' => 101,
        'name' => 'Nhẫn Kim Cương Solitaire Luxury',
        'image' => 'https://images.unsplash.com/photo-1605100804763-eb9708a95a8c?q=80&w=300', // Ảnh demo
        'price' => 25000000,
        'quantity' => 1
    ]
];
?>

<style>
    /* Font chữ đồng bộ */
    .cart-page {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        background-color: #fff;
        color: #1a1a1a;
        padding-bottom: 80px;
    }

    /* Tiêu đề */
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

    /* Table */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table thead th {
        text-align: left;
        padding: 15px;
        border-bottom: 2px solid #000;
        font-family: 'Be Vietnam Pro', sans-serif;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #000;
    }
    .cart-table tbody td {
        padding: 25px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    /* Sản phẩm */
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

    /* Bộ đếm số lượng */
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
        transition: 0.2s;
    }
    .qty-btn:hover { background: #f5f5f5; }
    .qty-input {
        width: 40px;
        border: none;
        text-align: center;
        font-weight: 600;
        outline: none;
    }

    /* Nút xóa */
    .btn-remove {
        color: #ccc;
        font-size: 1.2rem;
        transition: 0.2s;
    }
    .btn-remove:hover { color: #000; }

    /* Cột phải: Tổng tiền */
    .cart-summary {
        background: #fcfcfc;
        border: 1px solid #eee;
        padding: 30px;
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
        color: #555;
    }
    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #000;
        font-size: 1.2rem;
        font-weight: 700;
        color: #000;
        font-family: 'Playfair Display', serif;
    }
    .text-gold { color: #d4af37; }

    /* Nút Thanh toán */
    .btn-checkout {
        display: block;
        width: 100%;
        background: #000;
        color: #d4af37;
        text-align: center;
        padding: 15px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        text-decoration: none;
        margin-top: 25px;
        transition: 0.3s;
    }
    .btn-checkout:hover {
        background: #333;
        color: #fff;
    }
    
    .btn-continue {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        border-bottom: 1px solid transparent;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }
    .btn-continue:hover { border-bottom-color: #666; color: #000; }
</style>

<div class="cart-page">
    
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Giỏ Hàng Của Bạn</h1>
        </div>
    </div>

    <div class="container">
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
                                $total += $item['price'] * $item['quantity'];
                            ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="<?= $item['image'] ?>" class="product-img" alt="SP">
                                        <div>
                                            <a href="#" class="product-name"><?= $item['name'] ?></a>
                                            <span class="product-sku">Mã: #<?= $item['id'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td style="font-weight: 600;">
                                    <?= number_format($item['price']) ?>đ
                                </td>
                                
                                <td>
                                    <div class="qty-control">
                                        <button class="qty-btn" onclick="this.nextElementSibling.stepDown()">-</button>
                                        <input type="number" value="<?= $item['quantity'] ?>" class="qty-input" min="1" readonly>
                                        <button class="qty-btn" onclick="this.previousElementSibling.stepUp()">+</button>
                                    </div>
                                </td>
                                
                                <td class="text-end">
                                    <a href="#" class="btn-remove" title="Xóa khỏi giỏ"><i class="bi bi-trash"></i></a>
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
                        <span style="font-weight: 600;"><?= number_format($total) ?>đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    
                    <div class="summary-total">
                        <span>Tổng cộng:</span>
                        <span class="text-gold"><?= number_format($total) ?>đ</span>
                    </div>

                    <a href="/checkout" class="btn-checkout">Thanh Toán Ngay</a>
                    <a href="/products" class="btn-continue">← Tiếp tục mua sắm</a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>