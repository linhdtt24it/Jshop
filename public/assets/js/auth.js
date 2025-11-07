// public/assets/js/auth.js
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('<?= BASE_URL ?>auth/login', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                const msg = document.getElementById('loginMessage');
                msg.innerHTML = data.success
                    ? '<div class="alert alert-success">Đăng nhập thành công!</div>'
                    : '<div class="alert alert-danger">' + data.message + '</div>';
                if (data.success) setTimeout(() => location.reload(), 1000);
            });
    });
});