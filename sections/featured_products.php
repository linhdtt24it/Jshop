<?php
// sections/featured_products.php

if (!isset($featuredProducts)) {
    $featuredProducts = [
        ['id'=>1, 'name'=>'Trái tim anh đào - Sakura Heart', 'price'=>5000000, 'img'=>'assets/img/product1.jpg'],
        ['id'=>2, 'name'=>'Giọt lệ trong - Tear of Purity', 'price'=>3500000, 'img'=>'assets/img/product2.jpg'],
        ['id'=>3, 'name'=>'Hồng Diễm - Crimson Radiance', 'price'=>2000000, 'img'=>'assets/img/product3.jpg'],
        ['id'=>4, 'name'=>'Bạch Liên – White Lotus', 'price'=>1800000, 'img'=>'assets/img/product4.jpg'],
        ['id'=>5, 'name'=>'Băng Thanh - Pale Ocean ', 'price'=>6000000, 'img'=>'assets/img/product5.jpg'],
        ['id'=>6, 'name'=>'Ngọc Thẳm - Deep Sapphire', 'price'=>4200000, 'img'=>'assets/img/product6.jpg'],
        ['id'=>7, 'name'=>'Hoàng Kim - Golden Radiance', 'price'=>3900000, 'img'=>'assets/img/product7.jpg'],
    ];
}
?>

<section class="featured-products-section">
    
    <div class="site-width">
        <h3 class="section-title">SẢN PHẨM NỔI BẬT</h3>

        <div class="product-slider">
            <button class="product-prev">&#10094;</button>
            <button class="product-next">&#10095;</button>

            <div class="product-track">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card">
                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="product-image">
                            <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
                        </a>
                        <div class="product-info">
                            <h4 class="brand">JEWELRY</h4>
                            <p class="name"><?php echo $product['name']; ?></p>
                            <div class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<style>
.featured-products-section {
    padding: 60px 0;
    background: #fafafa;
    position: relative;
    overflow: hidden;
    margin-top: 38px !important; /* ✅ bắt buộc cách slider ~1cm */
}
.section-title {
    text-align: center;        /* căn giữa */
    font-size: 36px;           /* chữ lớn */
    font-weight: 800;          /* đậm */
    color: #000;               /* màu nổi bật */
    margin-bottom: 40px;       /* cách dưới */
    letter-spacing: 2px;
}

.site-width { max-width: 1200px; margin:0 auto; }

.product-slider { position: relative; overflow: hidden; }
.product-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s ease;
}
.product-card {
    min-width: 260px;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    transition:transform 0.3s, box-shadow 0.3s;
}
.product-card:hover { transform: translateY(-6px); box-shadow:0 8px 20px rgba(0,0,0,0.12); }
.product-image img { width:100%; height:250px; object-fit:cover; display:block; }
.product-info { padding:18px 20px 22px; text-align:left; }
.product-info .brand { font-size:13px; font-weight:600; color:#777; margin-bottom:4px; }
.product-info .name { font-size:15px; color:#111; font-weight:500; margin-bottom:8px; }
.product-info .price { font-size:16px; font-weight:700; color:#000; }

/* ===== BUTTONS ===== */
.product-prev, .product-next {
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:none;
    color:rgba(0,0,0,0);
    font-size:28px;
    border:none;
    cursor:pointer;
    z-index:5;
    pointer-events:none;
    transition: color 0.3s ease;
}
.product-slider:hover .product-prev,
.product-slider:hover .product-next {
    color:rgba(0,0,0,0.4);
    pointer-events:auto;
}
.product-prev:hover,
.product-next:hover { color:rgba(0,0,0,0.8); }
.product-prev { left:5px; }
.product-next { right:5px; }
</style>


<script>
// ===== PRODUCT SLIDER JS =====
document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.product-track');
    const slides = document.querySelectorAll('.product-card');
    const prevBtn = document.querySelector('.product-prev');
    const nextBtn = document.querySelector('.product-next');
    const slideWidth = slides[0].offsetWidth + 20; // card width + gap
    let index = 0;

    function updateSlider() {
        track.style.transform = `translateX(${-index * slideWidth}px)`;
    }

    nextBtn.addEventListener('click', () => {
        if(index < slides.length - Math.floor(track.parentElement.offsetWidth / slideWidth)) index++;
        updateSlider();
    });

    prevBtn.addEventListener('click', () => {
        if(index > 0) index--;
        updateSlider();
    });
});
</script>
