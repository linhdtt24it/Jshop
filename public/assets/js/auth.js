// public/assets/js/auth.js - SỬA HOÀN TOÀN
document.addEventListener('DOMContentLoaded', function() {
    let currentModal = null;

    // Xử lý chuyển đổi modal
    function switchModal(fromModalId, toModalId) {
        if (currentModal) {
            currentModal.hide();
        }
        
        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.remove();
            });
            
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            const newModal = new bootstrap.Modal(document.getElementById(toModalId));
            newModal.show();
            currentModal = newModal;
        }, 200);
    }

    document.querySelectorAll('.switch-to-login').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            switchModal('registerModal', 'loginModal');
        });
    });

    document.querySelectorAll('.switch-to-register').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            switchModal('loginModal', 'registerModal');
        });
    });

    // Xử lý đăng nhập - DEBUG MODE
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('loginMessage');
        
        messageDiv.innerHTML = '<div class="alert alert-info">Đang xử lý...</div>';

        console.log('🔄 Sending login request...');

        fetch('auth/login', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('📨 Response status:', response.status);
            console.log('📨 Response URL:', response.url);
            
            // XEM RESPONSE THỰC TẾ
            return response.text().then(html => {
                console.log('🔍 RAW RESPONSE (first 500 chars):', html.substring(0, 500));
                
                // Thử parse JSON
                try {
                    const data = JSON.parse(html);
                    console.log('✅ Valid JSON:', data);
                    return data;
                } catch (jsonError) {
                    console.error('❌ JSON Parse Error:', jsonError);
                    console.log('📄 Full response saved to console as htmlResponse');
                    
                    // Lưu toàn bộ response để debug
                    window.htmlResponse = html;
                    
                    throw new Error('Server returned HTML instead of JSON. Check console for details.');
                }
            });
        })
        .then(data => {
            console.log('✅ Final data:', data);
            if (data.success) {
                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => location.reload(), 1000);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('❌ Final Error:', error);
            messageDiv.innerHTML = `<div class="alert alert-danger">Lỗi: ${error.message}. Xem console để biết chi tiết.</div>`;
        });
    });

    // Xử lý đăng ký - DEBUG MODE
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('registerMessage');
        
        messageDiv.innerHTML = '<div class="alert alert-info">Đang xử lý...</div>';

        console.log('🔄 Sending register request...');

        fetch('auth/register', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('📨 Response status:', response.status);
            
            return response.text().then(html => {
                console.log('🔍 RAW RESPONSE (first 500 chars):', html.substring(0, 500));
                
                try {
                    const data = JSON.parse(html);
                    console.log('✅ Valid JSON:', data);
                    return data;
                } catch (jsonError) {
                    console.error('❌ JSON Parse Error:', jsonError);
                    window.htmlResponse = html;
                    throw new Error('Server returned HTML instead of JSON. Check console.');
                }
            });
        })
        .then(data => {
            console.log('✅ Final data:', data);
            if (data.success) {
                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => {
                    switchModal('registerModal', 'loginModal');
                    document.getElementById('registerForm').reset();
                }, 2000);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('❌ Final Error:', error);
            messageDiv.innerHTML = `<div class="alert alert-danger">Lỗi: ${error.message}</div>`;
        });
    });

    // Theo dõi sự kiện đóng modal
    document.getElementById('loginModal')?.addEventListener('hidden.bs.modal', function() {
        currentModal = null;
    });
    
    document.getElementById('registerModal')?.addEventListener('hidden.bs.modal', function() {
        currentModal = null;
    });
});