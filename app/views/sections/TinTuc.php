<section class="news-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Tin tức & Sự kiện</h3>
    <div class="news-slider">
      <button class="news-prev">&#10094;</button>
      <button class="news-next">&#10095;</button>
      <div class="news-track">
        <!-- Card 1 -->
        <div class="news-card">
          <div class="news-image">
            <img src="https://i.pinimg.com/736x/29/41/56/2941564481866668af66d5923d74de00.jpg" alt="Khai trương cửa hàng ">
          </div>
          <div class="news-content">
            <h4 class="news-title">Khai trương cửa hàng JSHOP tại ĐÀ NẴNG</h4>
            <p class="news-desc">JSHOP ra mắt cửa hàng mới với ưu đãi hấp dẫn dành cho khách hàng yêu trang sức.</p>
            <a href="#" class="news-link">Đọc thêm →</a>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="news-card">
          <div class="news-image">
            <img src="https://cdn-i.vtcnews.vn/files/phuong.ly/2018/04/19/30849330_2028411657420994_250692551_o-2155565.jpg" alt="Bộ sưu tập kim cương mới">
          </div>
          <div class="news-content">
            <h4 class="news-title">Bộ sưu tập Kim Cương "Diamond Shine"</h4>
            <p class="news-desc">Khám phá vẻ đẹp lấp lánh từ bộ sưu tập Kim Cương 2025 của JSHOP.</p>
            <a href="#" class="news-link">Đọc thêm →</a>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="news-card">
          <div class="news-image">
            <img src="https://i.pinimg.com/736x/a0/5b/72/a05b72d94ec78deb7c7cf01567530226.jpg" alt="Ưu đãi tháng 10">
          </div>
          <div class="news-content">
            <h4 class="news-title">Ưu đãi “Tháng Vàng Yêu Thương”</h4>
            <p class="news-desc">Cơ hội sở hữu trang sức cao cấp với mức giá ưu đãi đặc biệt trong tháng này.</p>
            <a href="#" class="news-link">Đọc thêm →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.news-section {
  margin-top: 0;           /* không cách trên */
  margin-bottom: 20px;     /* khoảng cách với footer */
  position: relative;
  overflow: hidden;
}

.news-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 50px; letter-spacing: 1px; }

.site-width { max-width: 1200px; padding: 0 20px; margin: 0 auto; }

.news-slider { position: relative; overflow: hidden; }
.news-track { display: flex; gap: 20px; transition: transform 0.5s ease; }

.news-card {
  flex: 0 0 calc((100% - 40px)/3); /* 3 card hiển thị + gap 20px */
  display: flex;
  flex-direction: column;
  background: #fafafa;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.news-card:hover { transform: translateY(-8px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

.news-image { flex: none; }
.news-image img { width: 100%; height: 200px; object-fit: cover; display: block; transition: transform 0.3s ease; }
.news-card:hover .news-image img { transform: scale(1.05); }

.news-content { flex: 1; display: flex; flex-direction: column; padding: 16px; }
.news-title { font-size: 18px; font-weight: 700; color: #222; margin-bottom: 10px; }
.news-desc { font-size: 15px; color: #555; line-height: 1.6; margin-bottom: auto; }
.news-link { text-decoration: none; font-weight: 600; color: #c2185b; margin-top: 10px; }
.news-link:hover { color: #a3154c; }

/* nút prev/next */
.news-prev, .news-next { position: absolute; top: 50%; transform: translateY(-50%); background: none; font-size: 32px; border: none; cursor: pointer; z-index: 5; color: rgba(0,0,0,0.3); transition: color 0.3s ease; }
.news-prev:hover, .news-next:hover { color: rgba(0,0,0,0.7); }
.news-prev { left: 0; }
.news-next { right: 0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.news-track');
  const slides = Array.from(document.querySelectorAll('.news-card'));
  const prevBtn = document.querySelector('.news-prev');
  const nextBtn = document.querySelector('.news-next');
  const gap = 20;
  const slideWidth = slides[0].offsetWidth + gap;
  const cloneCount = slides.length; // clone đủ để loop

  // clone đầu/cuối
  slides.forEach(slide => track.appendChild(slide.cloneNode(true)));
  slides.forEach(slide => track.insertBefore(slide.cloneNode(true), track.firstChild));

  let index = cloneCount;
  track.style.transform = `translateX(${-index * slideWidth}px)`;

  function updateSlider(animate = true) {
    track.style.transition = animate ? 'transform 0.5s ease' : 'none';
    track.style.transform = `translateX(${-index * slideWidth}px)`;
  }

  nextBtn.addEventListener('click', () => { index++; updateSlider(); });
  prevBtn.addEventListener('click', () => { index--; updateSlider(); });

  track.addEventListener('transitionend', () => {
    if (index >= slides.length + cloneCount) index = cloneCount;
    if (index < cloneCount) index = slides.length + cloneCount - 1;
    updateSlider(false);
  });

  setInterval(() => { index++; updateSlider(); }, 5000);
});
</script>
