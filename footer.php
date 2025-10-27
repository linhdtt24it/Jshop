<div class="footer">
    <p>&copy; 2025 JShop. All rights reserved.</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-gia-vang');
    if(btn) {
        btn.addEventListener('click', function(e){
            e.preventDefault(); // ngăn reload trang
            fetch('gold_price.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('gold-price-container').innerHTML = html;
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('gold-price-container').innerHTML = "<p>Không thể tải dữ liệu.</p>";
                });
        });
    }
});
</script>

<?php wp_footer(); ?>
