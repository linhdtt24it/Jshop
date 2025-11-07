<?php
// app/views/collection/index.php
$page_title = "Bộ Sưu Tập - JSHOP";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- HERO SECTION -->
<section class="collection-hero py-5 text-white" style="background: linear-gradient(135deg, #966d45 0%, #b08d5f 100%);">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-4 fw-bold mb-3">Bộ Sưu Tập JSHOP</h1>
        <p class="lead mb-0">Khám phá những bộ sưu tập trang sức độc đáo và tinh tế</p>
      </div>
    </div>
  </div>
</section>

<!-- COLLECTIONS GRID -->
<div class="container my-5">
  <div class="row g-4">
    
    <!-- BỘ SƯU TẬP NỔI BẬT -->
    <div class="col-12">
      <h2 class="text-center mb-4 text-warning">Bộ Sưu Tập Nổi Bật</h2>
    </div>

    <!-- COLLECTION 1 -->
    <div class="col-lg-4 col-md-6">
      <div class="card collection-card shadow-lg border-0 h-100">
        <div class="position-relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.0&auto=format&fit=crop&w=500&q=80" 
               class="card-img-top" alt="Bộ sưu tập Vàng 24K" style="height: 300px; object-fit: cover;">
          <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-warning text-dark">Bán chạy</span>
          </div>
        </div>
        <div class="card-body text-center">
          <h4 class="card-title text-gold">Vàng 24K Tinh Khiết</h4>
          <p class="card-text text-muted">Bộ sưu tập vàng nguyên chất với thiết kế tinh xảo</p>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <span class="badge bg-light text-dark">Nhẫn</span>
            <span class="badge bg-light text-dark">Dây chuyền</span>
            <span class="badge bg-light text-dark">Lắc tay</span>
          </div>
          <a href="<?= BASE_URL ?>collection/vang-24k" class="btn btn-warning btn-lg w-100">
            <i class="bi bi-gem"></i> Khám phá ngay
          </a>
        </div>
      </div>
    </div>

    <!-- COLLECTION 2 -->
    <div class="col-lg-4 col-md-6">
      <div class="card collection-card shadow-lg border-0 h-100">
        <div class="position-relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?ixlib=rb-4.0.0&auto=format&fit=crop&w=500&q=80" 
               class="card-img-top" alt="Bộ sưu tập Kim Cương" style="height: 300px; object-fit: cover;">
          <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-danger">Mới</span>
          </div>
        </div>
        <div class="card-body text-center">
          <h4 class="card-title text-primary">Kim Cương Vĩnh Cửu</h4>
          <p class="card-text text-muted">Vẻ đẹp vĩnh cửu với những viên kim cương tự nhiên</p>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <span class="badge bg-light text-dark">Nhẫn cưới</span>
            <span class="badge bg-light text-dark">Bông tai</span>
            <span class="badge bg-light text-dark">Mặt dây</span>
          </div>
          <a href="<?= BASE_URL ?>collection/kim-cuong" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-diamond"></i> Khám phá ngay
          </a>
        </div>
      </div>
    </div>

    <!-- COLLECTION 3 -->
    <div class="col-lg-4 col-md-6">
      <div class="card collection-card shadow-lg border-0 h-100">
        <div class="position-relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1602173574767-37ac01994b2a?ixlib=rb-4.0.0&auto=format&fit=crop&w=500&q=80" 
               class="card-img-top" alt="Bộ sưu tập Bạc" style="height: 300px; object-fit: cover;">
        </div>
        <div class="card-body text-center">
          <h4 class="card-title text-secondary">Bạc Sterling</h4>
          <p class="card-text text-muted">Phong cách trẻ trung với bạc nguyên chất 92.5%</p>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <span class="badge bg-light text-dark">Vòng tay</span>
            <span class="badge bg-light text-dark">Nhẫn nam</span>
            <span class="badge bg-light text-dark">Dây chuyền</span>
          </div>
          <a href="<?= BASE_URL ?>collection/bac-sterling" class="btn btn-secondary btn-lg w-100">
            <i class="bi bi-moon-stars"></i> Khám phá ngay
          </a>
        </div>
      </div>
    </div>

    <!-- CÁC BỘ SƯU TẬP KHÁC -->
    <div class="col-12 mt-5">
      <h3 class="text-center mb-4">Bộ Sưu Tập Khác</h3>
      <div class="row g-3">
        <?php 
        $collections = [
          ['name' => 'Ngọc Trai Biển', 'slug' => 'ngoc-trai', 'color' => 'info'],
          ['name' => 'Đá Quý Phong Thủy', 'slug' => 'da-quy-phong-thuy', 'color' => 'success'],
          ['name' => 'Trang Sức Cưới', 'slug' => 'trang-suc-cuoi', 'color' => 'danger'],
          ['name' => 'Trang Sức Nam', 'slug' => 'trang-suc-nam', 'color' => 'dark'],
          ['name' => 'Trang Sức Trẻ Em', 'slug' => 'trang-suc-tre-em', 'color' => 'warning'],
          ['name' => 'Quà Tặng Đặc Biệt', 'slug' => 'qua-tang-dac-biet', 'color' => 'primary']
        ];
        foreach($collections as $collection): 
        ?>
        <div class="col-lg-4 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="mb-3">
                <i class="bi bi-gem display-6 text-<?= $collection['color'] ?>"></i>
              </div>
              <h5 class="card-title"><?= $collection['name'] ?></h5>
              <a href="<?= BASE_URL ?>collection/<?= $collection['slug'] ?>" class="btn btn-outline-<?= $collection['color'] ?>">
                Xem bộ sưu tập
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- STATS SECTION -->
<section class="bg-light py-5 mt-5">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3">
        <h3 class="text-warning fw-bold">50+</h3>
        <p class="text-muted">Bộ Sưu Tập</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-warning fw-bold">1000+</h3>
        <p class="text-muted">Sản Phẩm</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-warning fw-bold">5★</h3>
        <p class="text-muted">Đánh Giá</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-warning fw-bold">10K+</h3>
        <p class="text-muted">Khách Hàng</p>
      </div>
    </div>
  </div>
</section>



<?php require_once __DIR__ . '/../layouts/footer.php'; ?>