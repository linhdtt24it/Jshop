<?php
$newProducts = [
  ['id'=>1, 'name'=>'Nhẫn Ngọc Trai Tinh Khiết', 'price'=>2800000, 'img'=>'https://trangsucvn.com/images/201410/goods_img/7727_P_1414469171678.jpg'],
  ['id'=>2, 'name'=>'Vòng Tay Kim Cương Mini', 'price'=>4200000, 'img'=>'https://i.pinimg.com/1200x/19/f5/94/19f594022698131694fa3e22785445c8.jpg'],
  ['id'=>3, 'name'=>'Bông Tai Vàng Trắng ECZ', 'price'=>3500000, 'img'=>'https://i.pinimg.com/736x/0b/39/e5/0b39e5c9b0b2cf5922e7412b83f9450b.jpg'],
  ['id'=>4, 'name'=>'Dây Chuyền Ngọc Lục Bảo', 'price'=>3900000, 'img'=>'https://i.pinimg.com/1200x/bd/73/17/bd73176f7429f22ec13182f48bc57f8f.jpg'],
  ['id'=>5, 'name'=>'Dây Chuyền Ruby Hồng', 'price'=>9900000, 'img'=>'https://i.pinimg.com/736x/f0/a0/1b/f0a01b76c3822da238cade1b7a02fc74.jpg'],
  ['id'=>6, 'name'=>'Bông Tai Kim Cương Tự Nhiên', 'price'=>3900000, 'img'=>'https://i.pinimg.com/1200x/2e/7d/1a/2e7d1aec9594cbc101de0cb06f3a008f.jpg'],
];
?>

<section class="new-products-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Sản phẩm mới</h3>

    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>

      <div class="product-track">
        <?php foreach ($newProducts as $product): ?>
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
.new-products-section {
  padding: 40px 0; /* 40px trên + dưới */
  position: relative;
  overflow: hidden;
  margin-top: 0; /* giữ 0 nếu muốn sát header */
}
.new-products-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.new-products-section .site-width { max-width: 1200px; margin: 0 auto; }
.new-products-section .product-slider { position: relative; overflow: hidden; }
.new-products-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.new-products-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.new-products-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.new-products-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.new-products-section .product-info { padding: 18px 20px 22px; text-align: left; }
.new-products-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.new-products-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.new-products-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.new-products-section .product-prev, .new-products-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.new-products-section .product-slider:hover .product-prev, .new-products-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.new-products-section .product-prev:hover, .new-products-section .product-next:hover { color: rgba(0,0,0,0.8); }
.new-products-section .product-prev { left: 5px; }
.new-products-section .product-next { right: 5px; }
@media (max-width: 768px) { .new-products-section .product-card { min-width: 200px; } }
@media (max-width: 480px) { .new-products-section .product-card { min-width: 150px; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.new-products-section .product-track');
  let slides = Array.from(document.querySelectorAll('.new-products-section .product-card'));
  const prevBtn = document.querySelector('.new-products-section .product-prev');
  const nextBtn = document.querySelector('.new-products-section .product-next');
  let slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;
  let index = cloneCount;
  let isTransitioning = false;

  // Clone đầu/cuối
  function cloneSlides() {
    slides = Array.from(document.querySelectorAll('.new-products-section .product-card'));
    for (let i = 0; i < cloneCount; i++) {
      const firstClone = slides[i].cloneNode(true);
      const lastClone = slides[slides.length - 1 - i].cloneNode(true);
      track.appendChild(firstClone);
      track.insertBefore(lastClone, track.firstChild);
    }
    slides = Array.from(document.querySelectorAll('.new-products-section .product-card'));
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
