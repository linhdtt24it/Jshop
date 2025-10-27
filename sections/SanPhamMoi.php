<?php
// chucnang/SanPhamMoi.php

if (!isset($newProducts)) {
    $newProducts = [
        'bongtai' => [
            ['id'=>101,'name'=>'BÔNG TAI CZ 22B122','price'=>7669350,'img'=>'assets/images/products/bongtai1.jpg','desc'=>'Vàng 14K, đá CZ'],
            ['id'=>102,'name'=>'BÔNG TAI ĐÁ GARNET 21B376','price'=>6179750,'img'=>'assets/images/products/bongtai2.jpg','desc'=>'Vàng 14K, đá Garnet'],
        ],
        'nhan' => [
            ['id'=>201,'name'=>'NHẪN KIM CƯƠNG','price'=>22750000,'img'=>'assets/images/products/nhan1.jpg','desc'=>'Vàng 18K, đá Diamond'],
            ['id'=>202,'name'=>'NHẪN CZ CLASSIC','price'=>7990000,'img'=>'assets/images/products/nhan2.jpg','desc'=>'Vàng 10K, đá CZ'],
        ],
        'daymat' => [],
        'matday' => [],
        'vongtay' => [],
        'cuoi' => [],
    ];
}
?>

<section class="new-products-section">
    <div class="section-header">
        <h2>SẢN PHẨM MỚI</h2>
        <ul class="product-tabs">
            <li class="active" data-tab="bongtai">BÔNG TAI</li>
            <li data-tab="nhan">NHẪN</li>
            <li data-tab="daymat">DÂY LIỀN MẶT</li>
            <li data-tab="matday">MẶT DÂY CHUYỀN</li>
            <li data-tab="vongtay">LẮC & VÒNG TAY</li>
            <li data-tab="cuoi">TRANG SỨC CƯỚI</li>
        </ul>
    </div>

    <?php foreach($newProducts as $tab => $products): ?>
    <div class="product-slider tab-content <?php echo $tab=='bongtai'?'active':''; ?>" id="<?php echo $tab; ?>">
        <button class="product-prev">&#10094;</button>
        <button class="product-next">&#10095;</button>
        <div class="product-track">
            <?php foreach($products as $p): ?>
            <div class="product-card">
                <a href="product_detail.php?id=<?php echo $p['id']; ?>" class="product-image">
                    <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['name']; ?>">
                </a>
                <div class="product-info">
                    <h4 class="brand">JEWELRY</h4>
                    <p class="name"><?php echo $p['name']; ?></p>
                    <p class="desc"><?php echo $p['desc']; ?></p>
                    <div class="price"><?php echo number_format($p['price'],0,',','.'); ?> VNĐ</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<style>
.new-products-section { max-width:1200px; margin:60px auto; }
.section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
.section-header h2 { font-size:26px; font-weight:800; color:#16202c; letter-spacing:1px; }
.product-tabs { list-style:none; display:flex; gap:25px; margin:0; padding:0; }
.product-tabs li { cursor:pointer; position:relative; font-size:15px; font-weight:500; color:#111; transition:all 0.3s; }
.product-tabs li.active, .product-tabs li:hover { color:#d6a04f; }
.product-tabs li.active::after, .product-tabs li:hover::after { content:''; position:absolute; bottom:-6px; left:0; width:100%; height:2px; background:#d6a04f; }

.product-slider { position:relative; overflow:hidden; margin-top:20px; }
.product-track { display:flex; gap:20px; transition: transform 0.5s ease; }
.product-card { min-width:260px; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08); transition:transform 0.3s, box-shadow 0.3s; }
.product-card:hover { transform:translateY(-6px); box-shadow:0 8px 20px rgba(0,0,0,0.12); }
.product-image img { width:100%; height:250px; object-fit:cover; display:block; }
.product-info { padding:18px 20px 22px; text-align:left; }
.product-info .brand { font-size:13px; font-weight:600; color:#777; margin-bottom:4px; }
.product-info .name { font-size:15px; font-weight:500; color:#111; margin-bottom:4px; }
.product-info .desc { font-size:14px; color:#555; margin-bottom:8px; }
.product-info .price { font-size:16px; font-weight:700; color:#000; }

.product-prev, .product-next {
    position:absolute; top:50%; transform:translateY(-50%);
    background:none; color:rgba(0,0,0,0); font-size:28px; border:none; cursor:pointer; z-index:5; pointer-events:none; transition: color 0.3s ease;
}
.product-slider:hover .product-prev, .product-slider:hover .product-next { color:rgba(0,0,0,0.4); pointer-events:auto; }
.product-prev:hover, .product-next:hover { color:rgba(0,0,0,0.8); }
.product-prev { left:5px; } .product-next { right:5px; }

.tab-content { display:none; }
.tab-content.active { display:block; }

@media (max-width:768px) {
    .product-card { min-width:200px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // TAB SWITCH
    document.querySelectorAll('.product-tabs li').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.product-tabs li').forEach(t=>t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });

    // SLIDER FUNCTION
    document.querySelectorAll('.product-slider').forEach(slider => {
        const track = slider.querySelector('.product-track');
        const cards = slider.querySelectorAll('.product-card');
        const prevBtn = slider.querySelector('.product-prev');
        const nextBtn = slider.querySelector('.product-next');
        const cardWidth = cards[0]?.offsetWidth + 20;
        let index = 0;

        function update() {
            track.style.transform = `translateX(${-index*cardWidth}px)`;
        }

        nextBtn.addEventListener('click', ()=>{
            if(index < cards.length - Math.floor(slider.offsetWidth / cardWidth)) index++;
            update();
        });
        prevBtn.addEventListener('click', ()=>{
            if(index>0) index--;
            update();
        });
    });
});
</script>
