<?php
require_once __DIR__ . '/../../models/Product.php';
$productModel = new Product();

// lấy đúng nhóm đồng hồ theo id hoặc category tuỳ bạn
$products = $productModel->getProductsByIdRange(54, 60);
?>

<section class="dongho-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Đồng hồ</h3>

    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>

      <div class="product-track">
        <?php foreach ($products as $product): ?>
          <div class="product-card">

            <a href="<?= BASE_URL . 'product/detail/' . $product['product_id'] ?>" class="product-image">
              <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </a>

            <div class="product-info">
              <h4 class="brand">JEWELRY</h4>

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
.dongho-section {  margin-top : 0; position: relative; overflow: hidden; }
.dongho-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.dongho-section .site-width { max-width: 1200px; margin: 0 auto; }
.dongho-section .product-slider { position: relative; overflow: hidden; }
.dongho-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.dongho-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.dongho-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.dongho-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.dongho-section .product-info { padding: 18px 20px 22px; text-align: left; }
.dongho-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.dongho-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.dongho-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.dongho-section .product-prev, .dongho-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.dongho-section .product-slider:hover .product-prev, .dongho-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.dongho-section .product-prev:hover, .dongho-section .product-next:hover { color: rgba(0,0,0,0.8); }
.dongho-section .product-prev { left: 5px; }
.dongho-section .product-next { right: 5px; }
@media (max-width: 768px) { .dongho-section .product-card { min-width: 200px; } }
@media (max-width: 480px) { .dongho-section .product-card { min-width: 150px; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.dongho-section .product-track');
  let slides = Array.from(document.querySelectorAll('.dongho-section .product-card'));
  const prevBtn = document.querySelector('.dongho-section .product-prev');
  const nextBtn = document.querySelector('.dongho-section .product-next');
  let slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;
  let index = cloneCount;
  let isTransitioning = false;

  // Clone đầu/cuối
  function cloneSlides() {
    slides = Array.from(document.querySelectorAll('.dongho-section .product-card'));
    for (let i = 0; i < cloneCount; i++) {
      const firstClone = slides[i].cloneNode(true);
      const lastClone = slides[slides.length - 1 - i].cloneNode(true);
      track.appendChild(firstClone);
      track.insertBefore(lastClone, track.firstChild);
    }
    slides = Array.from(document.querySelectorAll('.dongho-section .product-card'));
  }
  cloneSlides();

  track.style.transform = `translateX(${-index * slideWidth}px)`;

  function updateSlider(animate = true) {
    isTransitioning = true;
    track.style.transition = animate ? 'transform 0.5s ease' : 'none';
    track.style.transform = `translateX(${-index * slideWidth}px)`;
  }

  function nextSlide() { if (isTransitioning) return; index++; updateSlider(); }
  function prevSlide() { if (isTransitioning) return; index--; updateSlider(); }

  nextBtn.addEventListener('click', nextSlide);
  prevBtn.addEventListener('click', prevSlide);

  track.addEventListener('transitionend', () => {
    if (index >= slides.length - cloneCount) index = cloneCount;
    else if (index < cloneCount) index = slides.length - cloneCount;
    updateSlider(false);
    isTransitioning = false;
  });

  // Auto-slide
  setInterval(() => { if (!isTransitioning) nextSlide(); }, 4000);

  // Touch swipe
  let startX = 0, currentX = 0, isDragging = false;
  track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; isDragging = true; track.style.transition = 'none'; });
  track.addEventListener('touchmove', e => { if (!isDragging) return; currentX = e.touches[0].clientX; const moveX = currentX - startX; track.style.transform = `translateX(${-index * slideWidth + moveX}px)`; });
  track.addEventListener('touchend', e => { isDragging = false; const diff = currentX - startX; if (diff > 50) prevSlide(); else if (diff < -50) nextSlide(); else updateSlider(); });

  // Update slideWidth on resize
  window.addEventListener('resize', () => { slideWidth = slides[0].offsetWidth + 20; updateSlider(false); });
});
</script>
