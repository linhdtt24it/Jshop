<?php
// app/views/contact/index.php
$page_title = "Liên hệ - JSHOP";
require_once __DIR__ . '/../layouts/header.php';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
  

  
    .contact-page {
        /* Be Vietnam Pro: Font quốc dân cho web Việt, bao đẹp, bao chuẩn dấu */
        font-family: 'Be Vietnam Pro', sans-serif !important; 
        color: #1a1a1a;
    }
    
    /* Tiêu đề dùng Playfair Display cho sang */
    h1, h2, h3, h4, h5, .card-header, .btn-dark-luxury {
        font-family: 'Playfair Display', serif !important;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* --- CÁC STYLE KHÁC GIỮ NGUYÊN --- */
    .contact-hero {
        background-color: #f9f9f9;
        padding: 80px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .contact-card {
        border: 1px solid #eeeeee;
        border-radius: 0;
        box-shadow: none;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .contact-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #dddddd;
    }

    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #eeeeee;
        padding: 20px 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        border-radius: 0;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 0.95rem;
        background-color: #fafafa;
        font-family: 'Be Vietnam Pro', sans-serif !important; 
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #000;
        box-shadow: none;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #333;
    }

    .btn-dark-luxury {
        background-color: #000;
        color: #fff;
        border: 1px solid #000;
        border-radius: 0;
        padding: 12px 30px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-dark-luxury:hover {
        background-color: #fff;
        color: #000;
    }

    .contact-icon {
        font-size: 1.2rem;
        color: #000;
        width: 24px;
        text-align: center;
    }

    .social-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
        color: #000;
        border-radius: 0 !important;
        transition: all 0.3s ease;
    }

    .social-btn:hover {
        background-color: #000;
        color: #fff;
        border-color: #000;
    }
</style>

<div class="contact-page">
    <section class="contact-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-3">Liên Hệ JSHOP</h1>
                    <p class="lead mb-0 text-muted" style="font-family: 'Playfair Display', serif; font-style: italic;">
                        Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row g-5">
            
            <div class="col-lg-7">
                <div class="card contact-card h-100">
                    <div class="card-header bg-white text-dark">
                        <h5 class="mb-0"><i class="bi bi-chat-square-text me-2"></i> Gửi tin nhắn</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Nhập họ tên..." required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="email@vidu.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Điện thoại</label>
                                <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control" placeholder="Bạn cần hỗ trợ gì?" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-dark-luxury w-100">
                                Gửi Tin Nhắn
                            </button>
                        </form>
                        <div id="contactMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card contact-card h-100">
                    <div class="card-header bg-white text-dark">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Thông tin kết nối</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="contact-info">
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-start mb-4">
                                    <i class="bi bi-geo-alt contact-icon me-3 mt-1"></i>
                                    <div>
                                        <strong class="text-uppercase" style="font-size: 0.9rem;">Địa chỉ</strong><br>
                                        <span class="text-muted">180 Cao Lỗ, Phường 4, Quận 8, TP.HCM</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start mb-4">
                                    <i class="bi bi-telephone contact-icon me-3 mt-1"></i>
                                    <div>
                                        <strong class="text-uppercase" style="font-size: 0.9rem;">Hotline</strong><br>
                                        <span class="text-muted">1900 1234</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start mb-4">
                                    <i class="bi bi-envelope contact-icon me-3 mt-1"></i>
                                    <div>
                                        <strong class="text-uppercase" style="font-size: 0.9rem;">Email</strong><br>
                                        <span class="text-muted">support@jshop.vn</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="bi bi-clock contact-icon me-3 mt-1"></i>
                                    <div>
                                        <strong class="text-uppercase" style="font-size: 0.9rem;">Giờ làm việc</strong><br>
                                        <span class="text-muted">
                                            Thứ 2 - Thứ 7: 8:00 - 21:00<br>
                                            Chủ nhật: 9:00 - 18:00
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <div class="text-center">
                            <h6 class="mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Theo dõi JSHOP</h6>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn social-btn"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="btn social-btn"><i class="bi bi-youtube"></i></a>
                                <a href="#" class="btn social-btn"><i class="bi bi-tiktok"></i></a>
                                <a href="#" class="btn social-btn"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card contact-card">
                    <div class="card-header bg-white text-dark">
                        <h5 class="mb-0"><i class="bi bi-map me-2"></i> Bản đồ</h5>
                    </div>
                    <div class="card-body p-0">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.518987816833!2d106.62927431533437!3d10.772906992323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ea144839ef1%3A0x798819bdcd295205!2zMTgwIENhbyBMw7IsIFBoxrDhu51uZyA0LCBRdeG6rW4gOCwgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1647856789015!5m2!1svi!2s"
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const msg = document.getElementById('contactMessage');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Hiển thị loading
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'ĐANG GỬI...';
    submitBtn.style.opacity = '0.7';
    submitBtn.disabled = true;

    fetch('<?= BASE_URL ?>contact/send', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msg.innerHTML = `<div class="alert alert-dark rounded-0 fade show" role="alert">
                <i class="bi bi-check-circle"></i> ${data.message}
            </div>`;
            this.reset();
        } else {
            msg.innerHTML = `<div class="alert alert-secondary rounded-0 fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> ${data.message}
            </div>`;
        }
    })
    .catch(() => {
        msg.innerHTML = `<div class="alert alert-dark rounded-0">Lỗi kết nối máy chủ!</div>`;
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.style.opacity = '1';
        submitBtn.disabled = false;
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>