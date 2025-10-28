<?php
$kimcuong = [
  ['id'=>1, 'name'=>'Nhẫn Kim Cương Tự Nhiên', 'price'=>26500000, 'img'=>'https://i.pinimg.com/1200x/0c/dc/37/0cdc37daccd6d99c494d6a4d65dd8365.jpg'],
  ['id'=>2, 'name'=>'Bông Tai Kim Cương ECZ', 'price'=>18900000, 'img'=>'https://i.pinimg.com/736x/15/c2/9d/15c29d606a706aa0f83d05082894bf6e.jpg'],
  ['id'=>3, 'name'=>'Vòng Tay Kim Cương Trắng', 'price'=>31200000, 'img'=>'https://i.pinimg.com/1200x/21/11/16/211116e6dfde6ee4dcfab64c1abce9ad.jpg'],
  ['id'=>4, 'name'=>'Dây Chuyền Kim Cương Hồng', 'price'=>28800000, 'img'=>'https://i.pinimg.com/1200x/d2/95/85/d2958516b3492bf5c90afd2cd6696deb.jpg'],
  ['id'=>5, 'name'=>'Nhẫn Đôi Kim Cương Vàng', 'price'=>35600000, 'img'=>'https://i.pinimg.com/736x/70/70/c9/7070c928554920bb6e23d48225b74c6a.jpg'],
  ['id'=>6, 'name'=>'Mặt Dây Chuyền Kim Cương Đen', 'price'=>21000000, 'img'=>'https://i.pinimg.com/1200x/99/b9/4b/99b94b812161b54dc18009425bb7dbe5.jpg'],
];
?>


<section class="kimcuong-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Kim cương</h3>
    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>
      <div class="product-track">
        <?php foreach ($kimcuong as $product): ?>
          <div class="product-card">
            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="product-image">
              <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <div class="product-info">
              <h4 class="brand">DIAMOND</h4>
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
.kimcuong-section {
  padding: 40px 0; /* 40px trên + dưới */
  position: relative;
  overflow: hidden;
  margin-top: 0; /* giữ 0 nếu muốn sát header */
}
.kimcuong-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.kimcuong-section .site-width { max-width: 1200px; margin: 0 auto; }
.kimcuong-section .product-slider { position: relative; overflow: hidden; }
.kimcuong-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.kimcuong-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.kimcuong-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.kimcuong-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.kimcuong-section .product-info { padding: 18px 20px 22px; text-align: left; }
.kimcuong-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.kimcuong-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.kimcuong-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.kimcuong-section .product-prev, .kimcuong-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.kimcuong-section .product-slider:hover .product-prev, .kimcuong-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.kimcuong-section .product-prev:hover, .kimcuong-section .product-next:hover { color: rgba(0,0,0,0.8); }
.kimcuong-section .product-prev { left: 5px; }
.kimcuong-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.kimcuong-section .product-track');
  const slides = Array.from(document.querySelectorAll('.kimcuong-section .product-card'));
  const prevBtn = document.querySelector('.kimcuong-section .product-prev');
  const nextBtn = document.querySelector('.kimcuong-section .product-next');
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
