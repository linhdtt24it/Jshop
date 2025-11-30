<?php
require_once __DIR__ . '/../../models/Product.php';
$productModel = new Product();

// Lấy sản phẩm đá quý (material_id = 4)
$products = $productModel->getProductsByMaterial(4);
?>
<section class="daquy-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Đá quý</h3>

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
              <h4 class="brand">GEMSTONE</h4>

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
.daquy-section {  margin-top : 0; position: relative; overflow: hidden; }
.daquy-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.daquy-section .site-width { max-width: 1200px; margin: 0 auto; }
.daquy-section .product-slider { position: relative; overflow: hidden; }
.daquy-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.daquy-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.daquy-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.daquy-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.daquy-section .product-info { padding: 18px 20px 22px; text-align: left; }
.daquy-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.daquy-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.daquy-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.daquy-section .product-prev, .daquy-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.daquy-section .product-slider:hover .product-prev, .daquy-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.daquy-section .product-prev:hover, .daquy-section .product-next:hover { color: rgba(0,0,0,0.8); }
.daquy-section .product-prev { left: 5px; }
.daquy-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.daquy-section .product-track');
  const slides = Array.from(document.querySelectorAll('.daquy-section .product-card'));
  const prevBtn = document.querySelector('.daquy-section .product-prev');
  const nextBtn = document.querySelector('.daquy-section .product-next');
  const slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;

  // clone đầu/cuối
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
