<?php
// app/views/news/index.php
$page_title = "Tin tức - JSHOP";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- HERO SECTION -->
<section class="news-hero bg-light py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-5 fw-bold text-warning mb-3">Tin Tức JSHOP</h1>
        <p class="lead text-muted">Cập nhật xu hướng trang sức mới nhất</p>
      </div>
    </div>
  </div>
</section>

<!-- NEWS CONTENT -->
<div class="container my-5">
  <div class="row g-4">
    
    <!-- BÀI VIẾT NỔI BẬT -->
    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.0&auto=format&fit=crop&w=1000&q=80" 
             class="card-img-top" alt="Bài viết nổi bật" style="height: 400px; object-fit: cover;">
        <div class="card-body">
          <span class="badge bg-warning mb-2">Nổi bật</span>
          <h3 class="card-title">Xu hướng trang sức 2024: Phong cách nào đang thống trị?</h3>
          <p class="card-text text-muted">Khám phá những xu hướng trang sức mới nhất sẽ làm bừng sáng tủ đồ của bạn trong năm 2024...</p>
          <a href="<?= BASE_URL ?>news/detail/1" class="btn btn-outline-warning">Đọc tiếp</a>
        </div>
      </div>

      <!-- DANH SÁCH TIN TỨC -->
      <div class="row g-4">
        <?php for($i = 1; $i <= 6; $i++): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?ixlib=rb-4.0.0&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top" alt="Tin tức <?= $i ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title">Cách bảo quản trang sức vàng đúng cách</h5>
              <p class="card-text text-muted small">Bí quyết giữ cho trang sức luôn sáng bóng như mới...</p>
              <a href="<?= BASE_URL ?>news/detail/<?= $i + 1 ?>" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="col-lg-4">
      <!-- TIN MỚI NHẤT -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
          <h6 class="mb-0"><i class="bi bi-lightning"></i> Tin mới nhất</h6>
        </div>
        <div class="list-group list-group-flush">
          <?php for($i = 1; $i <= 5; $i++): ?>
          <a href="#" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">Cách mix đồ với trang sức bạc</h6>
              <small class="text-muted">3 days ago</small>
            </div>
            <small class="text-muted">Phong cách thời thượng</small>
          </a>
          <?php endfor; ?>
        </div>
      </div>

      <!-- DANH MỤC -->
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0"><i class="bi bi-bookmarks"></i> Danh mục</h6>
        </div>
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Xu hướng
            <span class="badge bg-warning rounded-pill">5</span>
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Bảo quản
            <span class="badge bg-primary rounded-pill">3</span>
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            Phong thủy
            <span class="badge bg-success rounded-pill">7</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>