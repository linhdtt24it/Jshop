<?php
require_once __DIR__ . '/../../models/Product.php';
$productModel = new Product();

// lấy sản phẩm theo material_id = 1
$products = $productModel->getProductsByMaterial(1);
?>
<section class="vang-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Vàng</h3>

    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>

      <div class="product-track">
        <?php foreach ($products as $product): ?>
          <div class="product-card">

            <a href="<?= BASE_URL ?>product/detail/<?= $product['product_id'] ?>" class="product-image">
              <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </a>

            <div class="product-info">
              <h4 class="brand">GOLD</h4>

              <p class="name"><?= htmlspecialchars($product['name']) ?></p>

              <div class="price">
                <?= number_format($product['price'], 0, ',', '.') ?> VNĐ
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<style>
.vang-section {
  padding: 40px 0; /* 40px trên + dưới */
  position: relative;
  overflow: hidden;
  margin-top: 0; /* giữ 0 nếu muốn sát header */
}
.vang-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.vang-section .site-width { max-width: 1200px; margin: 0 auto; }
.vang-section .product-slider { position: relative; overflow: hidden; }
.vang-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.vang-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.vang-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.vang-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.vang-section .product-info { padding: 18px 20px 22px; text-align: left; }
.vang-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.vang-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.vang-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.vang-section .product-prev, .vang-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.vang-section .product-slider:hover .product-prev, .vang-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.vang-section .product-prev:hover, .vang-section .product-next:hover { color: rgba(0,0,0,0.8); }
.vang-section .product-prev { left: 5px; }
.vang-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.vang-section .product-track');
  const slides = Array.from(document.querySelectorAll('.vang-section .product-card'));
  const prevBtn = document.querySelector('.vang-section .product-prev');
  const nextBtn = document.querySelector('.vang-section .product-next');
  const slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;

  // Clone đầu/cuối
  for (let i = 0; i < cloneCount; i++) {
    const firstClone = slides[i].cloneNode(true);
    const lastClone = slides[slides.length - 1 - i].cloneNode(true);
    track.appendChild(firstClone);
    track.insertBefore(lastClone, track.firstChild);
  }

  let index = cloneCount;
  track.style.transform = `translateX(${-index * slideWidth}px)`;

  function updateSlider(animate = true) {
    track.style.transition = animate ? 'transform 0.5s ease' : 'none';
    track.style.transform = `translateX(${-index * slideWidth}px)`;
  }

  nextBtn.addEventListener('click', () => { index++; updateSlider(); });
  prevBtn.addEventListener('click', () => { index--; updateSlider(); });

  track.addEventListener('transitionend', () => {
    if (index >= slides.length + cloneCount) { index = cloneCount; updateSlider(false); }
    else if (index < cloneCount) { index = slides.length + cloneCount - 1; updateSlider(false); }
  });

  setInterval(() => { index++; updateSlider(); }, 4000);
});
</script>
