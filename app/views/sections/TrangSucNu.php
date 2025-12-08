<?php
// app/views/sections/TrangSucNu.php
// Đã sử dụng biến $tsNu đã được truyền từ HomeController
$products = $tsNu;
?>

<section class="trangsucnu-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Trang sức nữ</h3>
    
    <div class="product-slider-container">
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
/* CSS RÚT GỌN (Dùng chung cho Slider) */
.trangsucnu-section { position: relative; overflow: hidden; }
.trangsucnu-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.trangsucnu-section .site-width { max-width: 1200px; margin: 0 auto; }
.product-slider-container { position: relative; overflow: hidden; } 
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
  const container = document.querySelector('.trangsucnu-section .product-slider-container');
  if (!container) return; 

  const track = container.querySelector('.product-track');
  const slides = Array.from(container.querySelectorAll('.product-card'));
  const prevBtn = container.querySelector('.product-prev');
  const nextBtn = container.querySelector('.product-next');
  
  const gap = 20; 
  const cloneCount = 3; 
  let index = cloneCount;
  let isAnimating = false;

  // 1. Clone Slides cho hiệu ứng loop vô tận
  slides.forEach((slide, i) => {
    track.appendChild(slide.cloneNode(true)); 
    track.insertBefore(slide.cloneNode(true), track.firstChild); 
  });

  // Tính toán chiều rộng slide
  const allSlides = Array.from(track.querySelectorAll('.product-card'));
  const slideWidth = allSlides[cloneCount].offsetWidth + gap;

  // Thiết lập vị trí ban đầu
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

  function nextSlide() { if (isAnimating) return; index++; updateSlider(); }
  function prevSlide() { if (isAnimating) return; index--; updateSlider(); }

  nextBtn.addEventListener('click', nextSlide);
  prevBtn.addEventListener('click', prevSlide);

  track.addEventListener('transitionend', handleTransitionEnd);

  // Auto-slide
  setInterval(() => { if (!isAnimating) nextSlide(); }, 4000);

  // Touch swipe
  let startX = 0, currentX = 0, isDragging = false;
  track.addEventListener('touchstart', e => { 
    startX = e.touches[0].clientX; 
    isDragging = true; 
    track.style.transition = 'none'; 
  });
  
  track.addEventListener('touchmove', e => { 
    if (!isDragging) return; 
    currentX = e.touches[0].clientX; 
    const moveX = currentX - startX; 
    track.style.transform = `translateX(${-index * slideWidth + moveX}px)`; 
  });
  
  track.addEventListener('touchend', e => { 
    isDragging = false; 
    const diff = currentX - startX; 
    if (diff > 50) prevSlide(); 
    else if (diff < -50) nextSlide(); 
    else updateSlider(); 
  });

  // Update slideWidth on resize
  window.addEventListener('resize', () => { 
    const allSlides = Array.from(track.querySelectorAll('.product-card'));
    const firstRealSlide = allSlides[cloneCount];
    if (firstRealSlide) {
        const gap = 20; 
        const newSlideWidth = firstRealSlide.offsetWidth + gap;
        // Cập nhật lại slideWidth và vị trí
        if (slideWidth !== newSlideWidth) {
            slideWidth = newSlideWidth;
            updateSlider(false); 
        }
    }
  });
});
</script>