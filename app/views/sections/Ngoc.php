<?php
$ngoc = [
  ['id'=>1, 'name'=>'Dây Chuyền Ngọc Trai Tự Nhiên', 'price'=>5900000, 'img'=>'https://i.pinimg.com/736x/6b/65/87/6b658756a5d0d507a4bf95903a0458cf.jpg'],
  ['id'=>2, 'name'=>'Bông Tai Ngọc Bích', 'price'=>6200000, 'img'=>'https://i.pinimg.com/1200x/0a/8c/3e/0a8c3ea16b900e352177db8658ed944d.jpg'],
  ['id'=>3, 'name'=>'Vòng Tay Ngọc Jade', 'price'=>7200000, 'img'=>'https://i.pinimg.com/1200x/05/0d/3a/050d3a6f049dceab7d67cc5d3867967d.jpg'],
  ['id'=>4, 'name'=>'Nhẫn Ngọc Lục Bảo', 'price'=>8800000, 'img'=>'https://i.pinimg.com/1200x/20/32/07/203207c926fb2907d10e0c928e6f0487.jpg'],
  ['id'=>5, 'name'=>'Chuỗi Ngọc Trai Hồng', 'price'=>9200000, 'img'=>'https://i.pinimg.com/1200x/56/ce/bb/56cebb73ab3a971991e6e235a45e15e8.jpg'],
  ['id'=>6, 'name'=>'Dây Chuyền Ngọc Lam', 'price'=>6800000, 'img'=>'https://i.pinimg.com/1200x/51/7c/ae/517cae45d592f9b8a8cdd78d2226c2a1.jpg'],
];
?>

<section class="ngoc-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Ngọc</h3>
    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>
      <div class="product-track">
        <?php foreach ($ngoc as $product): ?>
          <div class="product-card">
            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="product-image">
              <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <div class="product-info">
              <h4 class="brand">PEARL</h4>
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
.ngoc-section {
  padding: 40px 0; /* 40px trên + dưới */
  position: relative;
  overflow: hidden;
  margin-top: 0; /* giữ 0 nếu muốn sát header */
}
.ngoc-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.ngoc-section .site-width { max-width: 1200px; margin: 0 auto; }
.ngoc-section .product-slider { position: relative; overflow: hidden; }
.ngoc-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.ngoc-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.ngoc-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.ngoc-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.ngoc-section .product-info { padding: 18px 20px 22px; text-align: left; }
.ngoc-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.ngoc-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.ngoc-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.ngoc-section .product-prev, .ngoc-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.ngoc-section .product-slider:hover .product-prev, .ngoc-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.ngoc-section .product-prev:hover, .ngoc-section .product-next:hover { color: rgba(0,0,0,0.8); }
.ngoc-section .product-prev { left: 5px; }
.ngoc-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.ngoc-section .product-track');
  const slides = Array.from(document.querySelectorAll('.ngoc-section .product-card'));
  const prevBtn = document.querySelector('.ngoc-section .product-prev');
  const nextBtn = document.querySelector('.ngoc-section .product-next');
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
