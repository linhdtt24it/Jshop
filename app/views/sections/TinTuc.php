<section class="news-section">
  <div class="site-width">
    <h3 class="section-title text-uppercase">Tin tức & Sự kiện</h3>
    
    <div class="news-slider-container"> 
      <button class="news-prev">&#10094;</button>
      <button class="news-next">&#10095;</button>
      
      <div class="news-track">
        <?php foreach ($newsList ?? [] as $news): // Sử dụng biến đã truyền ?>
          <div class="news-card">
            <div class="news-image">
              <img src="<?= $news['image_url'] ?? 'default.jpg' ?>" alt="<?= htmlspecialchars($news['title']) ?>">
            </div>
            <div class="news-content">
              <h4 class="news-title"><?= htmlspecialchars($news['title']) ?></h4>
              <p class="news-desc"><?= htmlspecialchars($news['content'] ?? '') ?></p>
              <a href="<?= BASE_URL ?>news/detail/<?= $news['news_id'] ?>" class="news-link">Đọc thêm →</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<style>
.news-section { margin-top: 0; margin-bottom: 20px; position: relative; overflow: hidden; }
.news-section .section-title { text-align: center; font-size: 32px; font-weight: 800; color: #c2185b; margin-bottom: 50px; letter-spacing: 1px; }
.site-width { max-width: 1200px; padding: 0 20px; margin: 0 auto; }

.news-slider-container { position: relative; overflow: hidden; } /* Class mới */
.news-track { display: flex; gap: 20px; transition: transform 0.5s ease; }

.news-card {
  flex: 0 0 calc((100% - 40px)/3); 
  display: flex; flex-direction: column; 
  background: #fafafa; border-radius: 16px; 
  overflow: hidden; box-shadow: 0 3px 12px rgba(0,0,0,0.1); 
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.news-card:hover { transform: translateY(-8px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

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
  const container = document.querySelector('.news-section .news-slider-container');
  if (!container) return; 
  
  const track = container.querySelector('.news-track');
  const slides = Array.from(container.querySelectorAll('.news-card'));
  
  if (slides.length <= 3) return; 

  const prevBtn = container.querySelector('.news-prev');
  const nextBtn = container.querySelector('.news-next');
  
  const gap = 20;
  let cloneCount = slides.length; 
  let index = cloneCount;
  let isAnimating = false;

  slides.forEach((slide, i) => {
    track.appendChild(slide.cloneNode(true)); 
    track.insertBefore(slide.cloneNode(true), track.firstChild); 
  });
  
  const allSlides = Array.from(track.querySelectorAll('.news-card'));
  const slideWidth = allSlides[cloneCount].offsetWidth + gap;

  // Thiết lập vị trí ban đầu
  track.style.transform = `translateX(${-index * slideWidth}px)`;

  function updateSlider(animate = true) {
    if (isAnimating && animate) return;
    isAnimating = true;
    track.style.transition = animate ? 'transform 0.5s ease' : 'none';
    track.style.transform = `translateX(${-index * slideWidth}px)`;
  }

  function handleTransitionEnd() {
    isAnimating = false;
    if (index >= slides.length + cloneCount) { 
      index = cloneCount; 
      updateSlider(false); 
    } else if (index < cloneCount) { 
      index = slides.length + cloneCount - 1; 
      updateSlider(false); 
    }
  }

  function nextSlide() { if (isAnimating) return; index++; updateSlider(); }
  function prevSlide() { if (isAnimating) return; index--; updateSlider(); }

  nextBtn.addEventListener('click', nextSlide);
  prevBtn.addEventListener('click', prevSlide);

  track.addEventListener('transitionend', handleTransitionEnd);

  // Auto-slide
  setInterval(() => { if (!isAnimating) nextSlide(); }, 5000);

  // Xử lý Resize
  window.addEventListener('resize', () => { 
    const firstRealSlide = allSlides[cloneCount];
    if (firstRealSlide) {
        const newSlideWidth = firstRealSlide.offsetWidth + gap;
        if (slideWidth !== newSlideWidth) {
            slideWidth = newSlideWidth;
            updateSlider(false); 
        }
    }
  });
});
</script>