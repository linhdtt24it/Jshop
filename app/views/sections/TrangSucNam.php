<?php
$tsNam = [
  ['id'=>1, 'name'=>'Nhẫn Bạc Đen Nam Tính', 'price'=>1800000, 'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJ2GePz2ar0PZLbLppY_kP7YRgUMZ6xvgnHg&s'],
  ['id'=>2, 'name'=>'Vòng Tay Da Đính Bạc', 'price'=>2100000, 'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmIzxf3NxGXrLhTadaBWFL-Ov2bH9ZYY3OIg&s'],
  ['id'=>3, 'name'=>'Dây Chuyền Nam Đính Đá Đen', 'price'=>2800000, 'img'=>'https://luxcreuni.com/wp-content/uploads/2024/10/day-chuyen-nam-den-kim-cuong-5-500x500.jpg'],
  ['id'=>4, 'name'=>'Nhẫn Vàng Trắng Phong Cách', 'price'=>3500000, 'img'=>'https://tnj.vn/59810-large_default/nhan-nam-moissanite-don-gian-nnam0009.jpg'],
  ['id'=>5, 'name'=>'Mặt Dây Chuyền Hổ Phách', 'price'=>4200000, 'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQti3Ti7jMmCTpAS2b3AwxU6MIPYY9m-RFmNg&s'],
  ['id'=>6, 'name'=>'Bông Tai Nam Bạc S925', 'price'=>1600000, 'img'=>'https://bizweb.dktcdn.net/100/487/604/products/z4430060620800-3cf1c06e70af1536012626d195627712.jpg?v=1690991544790'],
];
?>

<section class="trangsucnam-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Trang sức nam</h3>
    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>
      <div class="product-track">
        <?php foreach ($tsNam as $product): ?>
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
.trangsucnam-section {
  padding: 40px 0; /* 40px trên + dưới */
  position: relative;
  overflow: hidden;
  margin-top: 0; /* giữ 0 nếu muốn sát header */
}
.trangsucnam-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.trangsucnam-section .site-width { max-width: 1200px; margin: 0 auto; }
.trangsucnam-section .product-slider { position: relative; overflow: hidden; }
.trangsucnam-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.trangsucnam-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.trangsucnam-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.trangsucnam-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.trangsucnam-section .product-info { padding: 18px 20px 22px; text-align: left; }
.trangsucnam-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.trangsucnam-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.trangsucnam-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.trangsucnam-section .product-prev, .trangsucnam-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.trangsucnam-section .product-slider:hover .product-prev, .trangsucnam-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.trangsucnam-section .product-prev:hover, .trangsucnam-section .product-next:hover { color: rgba(0,0,0,0.8); }
.trangsucnam-section .product-prev { left: 5px; }
.trangsucnam-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.trangsucnam-section .product-track');
  const slides = Array.from(document.querySelectorAll('.trangsucnam-section .product-card'));
  const prevBtn = document.querySelector('.trangsucnam-section .product-prev');
  const nextBtn = document.querySelector('.trangsucnam-section .product-next');
  const slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;

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
