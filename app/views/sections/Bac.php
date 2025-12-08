<?php
// app/views/sections/Bac.php
// Đã sử dụng biến $productsBac đã được truyền từ HomeController
$products = $productsBac; 
?>
<section class="bac-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Bạc</h3>
    
    <div class="product-slider-container"> 
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>

      <div class="product-track">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <a href="<?= BASE_URL ?>product/detail/<?= $product['product_id'] ?>" class="product-image">
              <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </a>
            <div class="product-info">
              <h4 class="brand">SILVER</h4>
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
.bac-section { position: relative; overflow: hidden; }
.bac-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.bac-section .site-width { max-width: 1200px; margin: 0 auto; }
.product-slider-container { position: relative; overflow: hidden; } /* Đổi tên class */
.product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.product-info { padding: 18px 20px 22px; text-align: left; }
.product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.product-prev, .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0.4); font-size: 32px; border: none; cursor: pointer; z-index: 5; transition: color 0.3s ease; }
.product-prev:hover, .product-next:hover { color: rgba(0,0,0,0.8); }
.product-prev { left: 5px; }
.product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.querySelector('.bac-section .product-slider-container');
  if (!container) return; // Kiểm tra nếu container tồn tại

  const track = container.querySelector('.product-track');
  const slides = Array.from(container.querySelectorAll('.product-card'));
  const prevBtn = container.querySelector('.product-prev');
  const nextBtn = container.querySelector('.product-next');
  
  const gap = 20; // Khoảng cách giữa các slide (từ CSS)
  const cloneCount = 3; // Số lượng slide nhân bản
  let index = cloneCount;
  let isAnimating = false;

  // 1. Clone Slides cho hiệu ứng loop vô tận
  slides.forEach((slide, i) => {
    track.appendChild(slide.cloneNode(true)); // Clone đầu vào cuối
    track.insertBefore(slide.cloneNode(true), track.firstChild); // Clone cuối vào đầu
  });

  // Tính toán chiều rộng slide sau khi clone và gán lại slides
  const allSlides = Array.from(track.querySelectorAll('.product-card'));
  const slideWidth = allSlides[cloneCount].offsetWidth + gap;

  // Thiết lập vị trí ban đầu (tại slide clone cuối)
  track.style.transform = `translateX(${-index * slideWidth}px)`;

  function updateSlider(animate = true) {
    if (isAnimating && animate) return;
    isAnimating = true;
    track.style.transition = animate ? 'transform 0.5s ease' : 'none';
    track.style.transform = `translateX(${-index * slideWidth}px)`;
  }

  function handleTransitionEnd() {
    isAnimating = false;
    // Quay về vị trí thật sau khi transition kết thúc
    if (index >= slides.length + cloneCount) { 
      index = cloneCount; 
      updateSlider(false); 
    } else if (index < cloneCount) { 
      index = slides.length + cloneCount - 1; 
      updateSlider(false); 
    }
  }

  nextBtn.addEventListener('click', () => { index++; updateSlider(); });
  prevBtn.addEventListener('click', () => { index--; updateSlider(); });

  track.addEventListener('transitionend', handleTransitionEnd);

  // Auto-slide
  setInterval(() => { if (!isAnimating) { index++; updateSlider(); } }, 4000);
});
</script>