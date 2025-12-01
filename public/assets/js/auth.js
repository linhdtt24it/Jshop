document.addEventListener('DOMContentLoaded', function() {
    let currentModal = null;

    // ================== TÌM URL ĐÚNG ==================
    console.log('🔍 Đang tìm URL AuthController đúng...');
    
    const URLS_TO_TEST = [
        '/Jshop/app/controllers/AuthController.php',
        '/jshop/app/controllers/AuthController.php',
        '/app/controllers/AuthController.php',
        'app/controllers/AuthController.php',
        '../controllers/AuthController.php'
    ];
    
    let CORRECT_BASE_URL = '/Jshop/app/controllers/AuthController.php'; // Mặc định
    
    // Test từng URL
    URLS_TO_TEST.forEach(url => {
        fetch(url + '?action=test')
            .then(res => {
                if(res.ok) {
                    return res.json().then(data => {
                        if(data.status === 'success' || data.status === 'debug') {
                            console.log(`✅ Tìm thấy URL đúng: ${url}`);
                            CORRECT_BASE_URL = url;
                        }
                    });
                }
            })
            .catch(() => {});
    });

    // ================== HÀM LẤY URL ==================
    function getAuthURL(action) {
        return CORRECT_BASE_URL + '?action=' + action;
    }

    // ================== CHUYỂN MODAL ==================
    function switchModal(fromModalId, toModalId) {
        if(currentModal) currentModal.hide();
        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            const newModal = new bootstrap.Modal(document.getElementById(toModalId));
            newModal.show();
            currentModal = newModal;
        }, 200);
    }

    // ================== CHUYỂN LOGIN/REGISTER ==================
    document.querySelectorAll('.switch-to-login').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            switchModal('registerModal','loginModal');
        });
    });

    document.querySelectorAll('.switch-to-register').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            switchModal('loginModal','registerModal');
        });
    });

    // ================== ĐĂNG NHẬP ==================
    document.getElementById('loginForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('loginMessage');
        const submitBtn = this.querySelector('button[type="submit"]');
        
        messageDiv.innerHTML = '<div class="alert alert-info">Đang đăng nhập...</div>';
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        
        fetch(getAuthURL('login'), {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log('Login response:', data);
            
            if(data.status === 'success'){
                messageDiv.innerHTML = '<div class="alert alert-success">Đăng nhập thành công!</div>';
                setTimeout(() => location.reload(), 1000);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(err => {
            console.error('Login error:', err);
            messageDiv.innerHTML = '<div class="alert alert-danger">Lỗi kết nối server</div>';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // ================== ĐĂNG KÝ + GỬI OTP ==================
    document.getElementById('registerForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('registerMessage');
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Validate mật khẩu
        const password = document.getElementById('registerPassword').value;
        const confirm = document.getElementById('registerConfirm').value;
        
        if(password !== confirm) {
            messageDiv.innerHTML = '<div class="alert alert-danger">Mật khẩu không khớp!</div>';
            return;
        }
        
        if(password.length < 6) {
            messageDiv.innerHTML = '<div class="alert alert-danger">Mật khẩu phải có ít nhất 6 ký tự!</div>';
            return;
        }
        
        messageDiv.innerHTML = '<div class="alert alert-info">Đang xử lý đăng ký...</div>';
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        
        console.log('Sending to:', getAuthURL('register'));
        console.log('Form data:', Object.fromEntries(formData));
        
        fetch(getAuthURL('register'), {
            method: 'POST',
            body: formData
        })
        .then(res => {
            console.log('Response status:', res.status);
            if(!res.ok) {
                throw new Error(`HTTP ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Register response:', data);
            
            if(data.status === 'success'){
                messageDiv.innerHTML = `<div class="alert alert-success">
                    <strong>✅ Thành công!</strong><br>
                    ${data.message}
                </div>`;
                
                setTimeout(() => {
                    switchModal('registerModal','loginModal');
                    document.getElementById('registerForm').reset();
                }, 2000);
                
            } else if(data.status === 'warning'){
                messageDiv.innerHTML = `<div class="alert alert-warning">
                    <strong>⚠️ Cảnh báo</strong><br>
                    ${data.message}
                </div>`;
                
                if(data.otp) {
                    alert(`📧 MÃ OTP CỦA BẠN: ${data.otp}\n\n(Email gửi thất bại, dùng mã này để test)`);
                }
                
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">
                    <strong>❌ Lỗi</strong><br>
                    ${data.message}
                </div>`;
            }
        })
        .catch(err => {
            console.error('Register error:', err);
            messageDiv.innerHTML = `<div class="alert alert-danger">
                <strong>❌ Lỗi kết nối</strong><br>
                ${err.message}<br>
                <small>Kiểm tra Console (F12) để biết chi tiết</small>
            </div>`;
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // ================== RESET MODAL ==================
    document.getElementById('loginModal')?.addEventListener('hidden.bs.modal', () => currentModal = null);
    document.getElementById('registerModal')?.addEventListener('hidden.bs.modal', () => currentModal = null);

    // ================== TOGGLE PASSWORD ==================
    function togglePassword(inputId, spanId){
        const input = document.getElementById(inputId);
        const span = document.getElementById(spanId);
        if(!input || !span) return;

        const icon = span.querySelector('i');
        if(!icon) return;

        span.addEventListener('click', () => {
            if(input.type === 'password'){
                input.type = 'text';
                icon.classList.replace('bi-eye-fill','bi-eye-slash-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash-fill','bi-eye-fill');
            }
        });
    }

    togglePassword('loginPassword','toggleLoginPassword');
    togglePassword('registerPassword','toggleRegisterPassword');
    togglePassword('registerConfirm','toggleRegisterConfirm');

    // ================== TEST URL SAU 2 GIÂY ==================
    setTimeout(() => {
        console.log('🔍 Kiểm tra lại URL AuthController...');
        fetch(getAuthURL('test'))
            .then(res => {
                if(res.ok) {
                    return res.json().then(data => {
                        console.log('✅ AuthController hoạt động:', data);
                    });
                }
                console.log('❌ AuthController không hoạt động, status:', res.status);
            })
            .catch(err => {
                console.log('❌ Lỗi kết nối AuthController:', err.message);
                alert('⚠️ Lỗi: Không thể kết nối đến AuthController. Kiểm tra Console (F12).');
            });
    }, 2000);
});