<section class="category-section py-5">
  <div class="container text-center">
    <h3 class="fw-bold mb-4" style="color:#003366;">Bạn đang tìm gì hôm nay?</h3>

    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4">

      <?php foreach ($categories as $cate): ?>
        <div class="col">
          <a href="<?= BASE_URL ?>category/index/<?= $cate['category_id'] ?>" style="text-decoration:none;">
            <div class="category-card">
              <img src="<?= $cate['image'] ?>" alt="<?= $cate['name'] ?>">
              <p><?= $cate['name'] ?></p>
            </div>
          </a>
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
