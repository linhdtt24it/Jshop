<!-- 🔹 DANH MỤC GỢI Ý -->
<section class="category-section py-5">
  <div class="container text-center">
    <h3 class="fw-bold mb-4" style="color:#003366;">Bạn đang tìm gì hôm nay?</h3>

    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4">
      <?php
      $categories = [
        ['img'=>'https://i.pinimg.com/1200x/28/80/0d/28800d47a5cfccfaf0571693aa5db084.jpg','name'=>'Nhẫn Kim cương'],
        ['img'=>'https://i.pinimg.com/1200x/7e/09/66/7e0966b95fbc40f7f8f1c899b0642c42.jpg','name'=>'Nhẫn Cưới'],
        ['img'=>'https://i.pinimg.com/736x/18/d9/d8/18d9d8515f73a45d84500f5ee12d93e1.jpg','name'=>'Nhẫn Cầu hôn'],
        ['img'=>'https://i.pinimg.com/736x/73/c9/d9/73c9d9def2baf8bd04b9dbbd0a67ec91.jpg','name'=>'Bông tai '],
        ['img'=>'https://i.pinimg.com/1200x/a6/c5/ed/a6c5ed237babb1989107ce9371528e4f.jpg','name'=>'Dây chuyền Vàng'],
        ['img'=>'https://i.pinimg.com/736x/7e/73/18/7e7318ba3eeb586d2f5cab3bdba8b0cd.jpg','name'=>'Đồng hồ Kim cương'],
        ['img'=>'https://i.pinimg.com/1200x/e9/29/bb/e929bb497c8866f625fa840e3dd7ae6d.jpg','name'=>'Trang sức mới'],
        ['img'=>'https://i.pinimg.com/1200x/b8/80/65/b88065a851c595d4b872ff4a9ec43355.jpg','name'=>'Trang sức Nam'],
        ['img'=>'https://i.pinimg.com/1200x/ee/cf/23/eecf231b9a7658b3496da7980612aaca.jpg','name'=>'Trang sức Nữ'],
        ['img'=>'https://i.pinimg.com/736x/2f/59/a0/2f59a09cc8db53f68243912b421e607c.jpg','name'=>'Trang sức May mắn'],
        ['img'=>'https://i.pinimg.com/1200x/6d/27/ad/6d27add3fd5e7c8fd5d32c498e3e51f0.jpg','name'=>'Trang sức hợp mệnh'],
        ['img'=>'https://i.pinimg.com/736x/71/7b/8d/717b8db127d6a6ba44e7d7fe5bf90df6.jpg','name'=>'Trang sức theo cung hoàng đạo'],
      ];

      foreach ($categories as $cate): ?>
        <div class="col">
          <div class="category-card">
            <img src="<?php echo $cate['img']; ?>" alt="<?php echo $cate['name']; ?>">
            <p><?php echo $cate['name']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
.category-section {
  background-color: #fff;
  margin-top: 20px;
}
.category-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  text-align: center;
}
.category-card img {
  width: 100%;
  aspect-ratio: 1 / 1;
  object-fit: cover;
  border-radius: 16px;
  transition: transform 0.3s ease;
}
.category-card p {
  font-weight: 600;
  color: #555;
  margin-top: 10px;
  font-size: 15px;
}
.category-card:hover img {
  transform: scale(1.05);
}
.category-card:hover p {
  color: #c2185b;
}
</style>


<?php