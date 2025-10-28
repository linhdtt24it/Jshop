<?php
$tsNu = [
  ['id'=>1, 'name'=>'Nhẫn Kim Cương Trắng', 'price'=>9800000, 'img'=>'https://locphuc.com.vn/Content/Images/022023/DSR0918BRW.WG01A/nhan-kim-cuong-DSR0918BRW-WG01A-g1.jpg'],
  ['id'=>2, 'name'=>'Bông Tai Ngọc Trai Tự Nhiên', 'price'=>4600000, 'img'=>'https://quatangngoctrai.com/wp-content/uploads/2020/11/Bong-Tai-Ngoc-Trai-T20.027-1.jpg'],
  ['id'=>3, 'name'=>'Dây Chuyền Trái Tim Vàng Hồng', 'price'=>5200000, 'img'=>'https://bizweb.dktcdn.net/100/461/213/products/vyn62-h-1696827181292.png?v=1751356900743'],
  ['id'=>4, 'name'=>'Vòng Tay Bạc Nữ Tinh Tế', 'price'=>2300000, 'img'=>'https://tnj.vn/40969-large_default/lac-tay-bac-nu-ban-to-khac-ten-ltn0212.jpg'],
  ['id'=>5, 'name'=>'Nhẫn Đính Đá Ruby Đỏ', 'price'=>7900000, 'img'=>'https://i.pinimg.com/1200x/d5/9c/07/d59c07a8dc10beee4787561a0ebaba1d.jpg'],
  ['id'=>6, 'name'=>'Bông Tai Vàng 18K ECZ', 'price'=>6700000, 'img'=>'https://apj.vn/wp-content/uploads/2020/10/BTP77-bong-tai-vang-vang-18k.jpg'],
];
?>

<section class="trangsucnu-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Trang sức nữ</h3>
    <div class="product-slider">
      <button class="product-prev">&#10094;</button>
      <button class="product-next">&#10095;</button>
      <div class="product-track">
        <?php foreach ($tsNu as $product): ?>
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
.trangsucnu-section {  margin-top : 0; position: relative; overflow: hidden; }
.trangsucnu-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 40px; letter-spacing: 1px; }
.trangsucnu-section .site-width { max-width: 1200px; margin: 0 auto; }
.trangsucnu-section .product-slider { position: relative; overflow: hidden; }
.trangsucnu-section .product-track { display: flex; gap: 20px; transition: transform 0.5s ease; }
.trangsucnu-section .product-card { min-width: 260px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.trangsucnu-section .product-card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.trangsucnu-section .product-image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.trangsucnu-section .product-info { padding: 18px 20px 22px; text-align: left; }
.trangsucnu-section .product-info .brand { font-size: 13px; font-weight: 600; color: #777; margin-bottom: 4px; text-transform: uppercase; }
.trangsucnu-section .product-info .name { font-size: 15px; color: #111; font-weight: 500; margin-bottom: 8px; }
.trangsucnu-section .product-info .price { font-size: 16px; font-weight: 700; color: #000; }
.trangsucnu-section .product-prev, .trangsucnu-section .product-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; color: rgba(0,0,0,0); font-size: 32px; border: none; cursor: pointer; z-index: 5; pointer-events: none; transition: color 0.3s ease; }
.trangsucnu-section .product-slider:hover .product-prev, .trangsucnu-section .product-slider:hover .product-next { color: rgba(0,0,0,0.4); pointer-events: auto; }
.trangsucnu-section .product-prev:hover, .trangsucnu-section .product-next:hover { color: rgba(0,0,0,0.8); }
.trangsucnu-section .product-prev { left: 5px; }
.trangsucnu-section .product-next { right: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.trangsucnu-section .product-track');
  const slides = Array.from(document.querySelectorAll('.trangsucnu-section .product-card'));
  const prevBtn = document.querySelector('.trangsucnu-section .product-prev');
  const nextBtn = document.querySelector('.trangsucnu-section .product-next');
  const slideWidth = slides[0].offsetWidth + 20;
  const cloneCount = 3;

  // Clone đầu/cuối để chạy vô hạn
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
