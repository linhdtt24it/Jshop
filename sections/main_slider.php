<section class="main-slider-area">
    <div class="slider">
        <div class="slide active" style="background-image:url('assets/img/kimcuong.jpg')"></div>
        <div class="slide" style="background-image:url('assets/img/kimcuong1.jpg')"></div>
        <div class="slide" style="background-image:url('assets/img/tulip.jpg')"></div>

        <div class="slider-text">
            <h2 class="slider-title"> TINH HOA SẮC ĐẸP</h2>
            <p class="slider-subtitle">Tôn vinh vẻ đẹp vĩnh cửu và giá trị bền vững</p>
        </div>

        <button class="slider-btn prev">&#10094;</button>
        <button class="slider-btn next">&#10095;</button>
    </div>
</section>

<style>
/* ===== MAIN SLIDER ===== */
.main-slider-area { position: relative; height: 500px; overflow: hidden; margin-bottom: 20px !important; }
.slider { width: 100%; height: 100%; position: relative; }
.slide {
    width: 100%; height: 100%;
    background-size: cover; background-position: center;
    position: absolute; top: 0; left: 0;
    opacity: 0; transition: opacity 0.8s ease;
}
.slide.active { opacity: 1; }

.slider-text {
    position: absolute;
    bottom: 50px; left: 50px;
    color: #fff; text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
}
.slider-text .slider-title { font-size: 36px; font-weight: 800; margin: 0 0 20px; }
.slider-text .slider-subtitle { font-size: 18px; margin:0; }

/* ===== SLIDER BUTTONS CHỈ MŨI TÊN NHẠT ===== */
.slider-btn {
    position: absolute; top: 50%;
    transform: translateY(-50%);
    background: none; /* không nền */
    color: rgba(255,255,255,0); /* ẩn chữ mặc định */
    font-size: 32px;
    border: none;
    cursor: pointer;
    z-index: 5;
    pointer-events: none; /* không click khi ẩn */
    transition: color 0.3s ease;
}

/* Chỉ hiện mũi tên nhạt khi hover slider */
.slider:hover .slider-btn {
    color: rgba(255,255,255,0.5); /* mũi tên nhạt */
    pointer-events: auto; /* có thể click */
}

.slider:hover .slider-btn:hover {
    color: rgba(255,255,255,0.9); /* mũi tên sáng hơn khi hover */
}

.slider-btn.prev { left: 20px; }
.slider-btn.next { right: 20px; }

/* RESPONSIVE */
@media (max-width: 768px){
    .slider-text { left: 20px; bottom: 30px; }
    .slider-text .slider-title { font-size: 28px; }
    .slider-text .slider-subtitle { font-size: 16px; }
}
</style>

<script>
// ===== MAIN SLIDER JS =====
document.addEventListener('DOMContentLoaded', function() {
    let slides = document.querySelectorAll('.slide');
    let index = 0;
    let autoSlide;

    function showSlide(i) {
        slides.forEach(s => s.classList.remove('active'));
        slides[i].classList.add('active');
    }

    function nextSlide() { index = (index + 1) % slides.length; showSlide(index); }
    function prevSlide() { index = (index - 1 + slides.length) % slides.length; showSlide(index); }

    function startAutoSlide() { autoSlide = setInterval(nextSlide, 3000); }
    function resetAutoSlide() { clearInterval(autoSlide); startAutoSlide(); }

    document.querySelector('.slider .next').addEventListener('click', () => { nextSlide(); resetAutoSlide(); });
    document.querySelector('.slider .prev').addEventListener('click', () => { prevSlide(); resetAutoSlide(); });

    startAutoSlide();
});
</script>
