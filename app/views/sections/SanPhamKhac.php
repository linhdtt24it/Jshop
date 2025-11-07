<?php
$spkhac = [
  ['id'=>1, 'name'=>'Hộp Đựng Trang Sức Cao Cấp', 'price'=>650000, 'img'=>'https://i.pinimg.com/1200x/53/81/da/5381da261d5d0a21e66d9c2a16d24181.jpg'],
  ['id'=>2, 'name'=>'Khăn Lau Trang Sức Chuyên Dụng', 'price'=>12000, 'img'=>'https://salt.tikicdn.com/cache/750x750/ts/product/0a/87/5a/e3fd6931b261683d3fe92dbb86da3865.jpg'],
  ['id'=>3, 'name'=>'Dụng Cụ Vệ Sinh Trang Sức', 'price'=>180000, 'img'=>'https://i.pinimg.com/736x/7b/ee/cd/7beecd9db2fe247eae188fcdce96603b.jpg'],
  ['id'=>4, 'name'=>'Găng Tay Nữ Trang', 'price'=>95000, 'img'=>'https://down-vn.img.susercontent.com/file/vn-11134202-7ras8-m0er46ug0i3309'],
];
?>

<section class="spkhac-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Sản phẩm khác</h3>
    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>
      <div class="product-track">
        <?php foreach ($spkhac as $product): ?>
          <div class="product-card">
            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="product-image">
              <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <div class="product-info">
              <h4 class="brand">ACCESSORY</h4>
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
.spkhac-section { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.spkhac-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.spkhac-section .site-width { max-width: 1200px; margin: 0 auto; }
.spkhac-section .product-slider { position: relative; overflow: hidden; }
.spkhac-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.spkhac-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.spkhac-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.spkhac-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.spkhac-section .product-info { padding: 18px 20px 22px; text-align: left; }
.spkhac-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.spkhac-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.spkhac-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.spkhac-section .product-prev, .spkhac-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.spkhac-section .product-slider:hover .product-prev, .spkhac-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.spkhac-section .product-prev:hover, .spkhac-section .product-next:hover { color: rgba(0,0,0,0.8); }
.spkhac-section .product-prev { left: 5px; }
.spkhac-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.spkhac-section .product-track');
  const slides = Array.from(document.querySelectorAll('.spkhac-section .product-card'));
  const prevBtn = document.querySelector('.spkhac-section .product-prev');
  const nextBtn = document.querySelector('.spkhac-section .product-next');
  const slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 2;

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
