// FILE: auth.js - Xử lý đăng nhập, đăng ký
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Auth System đang khởi tạo...');
    
    const BASE_URL = '/Jshop/app/controllers/AuthController.php';
    
    // ================== DEBUG CHECK ==================
    console.log('🔍 Login Modal:', document.getElementById('loginModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    console.log('🔍 Register Modal:', document.getElementById('registerModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    console.log('🔍 OTP Modal:', document.getElementById('otpModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    
    // ================== BIẾN QUẢN LÝ TRẠNG THÁI ==================
    let isProcessing = false;
    let registerSubmitLock = false;
    
    // ================== TIỆN ÍCH ==================
    const AuthUtils = {
        // Hiển thị thông báo
        showMessage(elementId, type, message) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            }[type] || 'alert-info';
            
            element.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Tự động ẩn sau 5 giây (trừ info)
            if (type !== 'info') {
                setTimeout(() => {
                    element.innerHTML = '';
                }, 5000);
            }
        },
        
        // Bật/tắt trạng thái loading
        setLoading(button, isLoading) {
            if (!button) return;
            
            if (isLoading) {
                button.dataset.originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
                button.disabled = true;
            } else {
                button.innerHTML = button.dataset.originalText || 'Gửi';
                button.disabled = false;
            }
        },
        
        // Gọi API
        async callAPI(action, data) {
            if (isProcessing) {
                return { status: 'error', message: 'Đang xử lý yêu cầu trước, vui lòng đợi...' };
            }
            
            isProcessing = true;
            
            try {
                const formData = new FormData();
                for (const key in data) {
                    if (data[key] !== undefined && data[key] !== null) {
                        formData.append(key, data[key]);
                    }
                }
                
                console.log(`📤 API Call: ${action}`, Object.fromEntries(formData));
                
                const response = await fetch(`${BASE_URL}?action=${action}`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                console.log(`📥 API Response (${action}):`, result);
                
                return result;
            } catch (error) {
                console.error(`❌ API Error (${action}):`, error);
                return { status: 'error', message: 'Lỗi kết nối máy chủ' };
            } finally {
                isProcessing = false;
            }
        },
        
        // Đóng modal
        closeModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) return;
            
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        },
        
        // Kiểm tra email
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        
        // Disable button trong thời gian
        disableButton(button, seconds, text = 'Đợi') {
            if (!button) return;
            
            const originalText = button.innerHTML;
            let countdown = seconds;
            
            button.disabled = true;
            button.innerHTML = `${text} ${countdown}s...`;
            
            const timer = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timer);
                    button.disabled = false;
                    button.innerHTML = originalText;
                } else {
                    button.innerHTML = `${text} ${countdown}s...`;
                }
            }, 1000);
            
            return timer;
        }
    };
    
    // ================== ĐĂNG NHẬP ==================
    (function initLogin() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;
        
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = this.querySelector('[name="email"]').value.trim();
            const password = this.querySelector('[name="password"]').value.trim();
            
            // Validate
            if (!email || !password) {
                AuthUtils.showMessage('loginMessage', 'error', 'Vui lòng nhập đầy đủ thông tin');
                return;
            }
            
            if (!AuthUtils.validateEmail(email)) {
                AuthUtils.showMessage('loginMessage', 'error', 'Email không hợp lệ');
                return;
            }
            
            AuthUtils.showMessage('loginMessage', 'info', 'Đang đăng nhập...');
            AuthUtils.setLoading(submitBtn, true);
            
            try {
                const result = await AuthUtils.callAPI('login', { email, password });
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('loginMessage', 'success', 'Đăng nhập thành công!');
                    
                    // Đóng modal
                    AuthUtils.closeModal('loginModal');
                    
                    // Reload trang sau 1 giây
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    AuthUtils.showMessage('loginMessage', 'error', result.message || 'Đăng nhập thất bại');
                }
            } catch (error) {
                AuthUtils.showMessage('loginMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(submitBtn, false);
            }
        });
    })();
    
    // ================== ĐĂNG KÝ ==================
    (function initRegister() {
        const registerForm = document.getElementById('registerForm');
        if (!registerForm) return;
        
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Chống double submit
            if (registerSubmitLock) {
                AuthUtils.showMessage('registerMessage', 'warning', 'Đang xử lý, vui lòng đợi...');
                return;
            }
            
            registerSubmitLock = true;
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const formData = {
                name: this.querySelector('[name="name"]').value.trim(),
                email: this.querySelector('[name="email"]').value.trim(),
                phone: this.querySelector('[name="phone"]').value.trim(),
                password: document.getElementById('registerPassword').value.trim(),
                confirm: document.getElementById('registerConfirm').value.trim()
            };
            
            // Validate
            if (!formData.name || !formData.email || !formData.password || !formData.confirm) {
                AuthUtils.showMessage('registerMessage', 'error', 'Vui lòng điền đầy đủ thông tin');
                registerSubmitLock = false;
                return;
            }
            
            if (!AuthUtils.validateEmail(formData.email)) {
                AuthUtils.showMessage('registerMessage', 'error', 'Email không hợp lệ');
                registerSubmitLock = false;
                return;
            }
            
            if (formData.password.length < 6) {
                AuthUtils.showMessage('registerMessage', 'error', 'Mật khẩu ít nhất 6 ký tự');
                registerSubmitLock = false;
                return;
            }
            
            if (formData.password !== formData.confirm) {
                AuthUtils.showMessage('registerMessage', 'error', 'Mật khẩu không khớp');
                registerSubmitLock = false;
                return;
            }
            
            AuthUtils.showMessage('registerMessage', 'info', 'Đang đăng ký và gửi OTP...');
            AuthUtils.setLoading(submitBtn, true);
            
            try {
                const result = await AuthUtils.callAPI('register', formData);
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('registerMessage', 'success', 'Đã gửi OTP đến email!');
                    
                    // Đóng modal đăng ký sau 1 giây
                    setTimeout(() => {
                        AuthUtils.closeModal('registerModal');
                    }, 1000);
                    
                    // Mở modal OTP sau 500ms
                    setTimeout(() => {
                        const emailToShow = result.email || formData.email;
                        console.log('Mở OTP modal cho:', emailToShow);
                        
                        if (typeof window.showOTPModal === 'function') {
                            window.showOTPModal(emailToShow);
                        }
                    }, 500);
                    
                } else {
                    AuthUtils.showMessage('registerMessage', 'error', result.message || 'Đăng ký thất bại');
                }
            } catch (error) {
                AuthUtils.showMessage('registerMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(submitBtn, false);
                registerSubmitLock = false;
            }
        });
        
        // Reset khi đóng modal
        const registerModal = document.getElementById('registerModal');
        if (registerModal) {
            registerModal.addEventListener('hidden.bs.modal', function() {
                registerSubmitLock = false;
                const messageDiv = document.getElementById('registerMessage');
                if (messageDiv) messageDiv.innerHTML = '';
            });
        }
    })();
    
    // ================== XÁC THỰC OTP ==================
    (function initOTPForm() {
        const otpForm = document.getElementById('otpForm');
        if (!otpForm) return;
        
        let isVerifyingOTP = false;
        
        otpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (isVerifyingOTP) {
                AuthUtils.showMessage('otpMessage', 'warning', 'Đang xác thực, vui lòng đợi...');
                return;
            }
            
            isVerifyingOTP = true;
            
            const submitBtn = this.querySelector('button[type="submit"]') || document.getElementById('otpSubmitBtn');
            const email = document.getElementById('otpEmail')?.value;
            
            // Lấy OTP từ input ẩn hoặc 6 ô
            let otpValue = document.getElementById('fullOtp')?.value || '';
            if (!otpValue || otpValue.length !== 6) {
                const otpInputs = document.querySelectorAll('.otp-input');
                otpValue = '';
                otpInputs.forEach(input => {
                    otpValue += input.value;
                });
            }
            
            // Validate
            if (!email || !otpValue) {
                AuthUtils.showMessage('otpMessage', 'error', 'Vui lòng nhập mã OTP');
                isVerifyingOTP = false;
                return;
            }
            
            if (otpValue.length !== 6) {
                AuthUtils.showMessage('otpMessage', 'error', 'Mã OTP phải có đúng 6 số');
                isVerifyingOTP = false;
                return;
            }
            
            AuthUtils.showMessage('otpMessage', 'info', 'Đang xác thực OTP...');
            AuthUtils.setLoading(submitBtn, true);
            
            try {
                const result = await AuthUtils.callAPI('verifyOTP', { email, otp: otpValue });
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('otpMessage', 'success', 'Xác thực thành công! Đang chuyển hướng...');
                    
                    // Đóng modal sau 1 giây
                    setTimeout(() => {
                        AuthUtils.closeModal('otpModal');
                        
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                    
                } else {
                    AuthUtils.showMessage('otpMessage', 'error', result.message || 'Mã OTP không đúng');
                    isVerifyingOTP = false;
                }
            } catch (error) {
                AuthUtils.showMessage('otpMessage', 'error', 'Lỗi xác thực');
                isVerifyingOTP = false;
            } finally {
                AuthUtils.setLoading(submitBtn, false);
            }
        });
    })();
    
    // ================== GỬI LẠI OTP ==================
    window.resendOTP = async function() {
        const email = document.getElementById('otpEmail')?.value;
        const resendBtn = document.getElementById('resendOTPBtn');
        
        if (!email) {
            AuthUtils.showMessage('otpMessage', 'error', 'Không tìm thấy email');
            return;
        }
        
        // Vô hiệu hóa nút ngay lập tức
        AuthUtils.setLoading(resendBtn, true);
        
        console.log('🔄 Yêu cầu gửi lại OTP cho:', email);
        AuthUtils.showMessage('otpMessage', 'info', 'Đang gửi lại OTP...');
        
        try {
            const result = await AuthUtils.callAPI('resendOTP', { email });
            
            if (result.status === 'success') {
                AuthUtils.showMessage('otpMessage', 'success', result.message);
                
                // Vô hiệu hóa nút trong 60 giây
                if (resendBtn) {
                    AuthUtils.disableButton(resendBtn, 60, 'Gửi lại sau');
                }
                
                // Cập nhật thông tin số lần gửi
                if (result.attempts_left !== undefined) {
                    updateOTPAttemptsInfo(result.attempts_left, result.current_attempt || 1);
                }
                
            } else {
                AuthUtils.showMessage('otpMessage', 'error', result.message);
                AuthUtils.setLoading(resendBtn, false);
            }
        } catch (error) {
            AuthUtils.showMessage('otpMessage', 'error', 'Lỗi gửi lại OTP');
            AuthUtils.setLoading(resendBtn, false);
        }
    };
    
    // Hàm cập nhật thông tin số lần gửi OTP
    function updateOTPAttemptsInfo(attemptsLeft, currentAttempt) {
        const attemptsInfo = document.getElementById('otpAttemptsInfo');
        if (attemptsInfo) {
            attemptsInfo.innerHTML = `
                <div class="alert alert-light border small">
                    <i class="bi bi-info-circle me-1"></i>
                    Đã gửi OTP ${currentAttempt}/4 lần
                    ${attemptsLeft > 0 ? `• Còn ${attemptsLeft} lần gửi lại` : '• Đã hết lượt gửi'}
                </div>
            `;
        }
        
        // Ẩn nút gửi lại nếu hết lượt
        const resendBtn = document.getElementById('resendOTPBtn');
        if (attemptsLeft <= 0 && resendBtn) {
            resendBtn.disabled = true;
            resendBtn.innerHTML = '<i class="bi bi-slash-circle me-2"></i>Đã hết lượt gửi';
            resendBtn.classList.remove('btn-outline-primary');
            resendBtn.classList.add('btn-secondary');
        }
    }
    
// Sửa phần CHUYỂN MODAL trong auth.js của bạn:

// ================== CHUYỂN MODAL ==================
document.querySelectorAll('.switch-to-login').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🔄 Switching to Login modal');
        
        // Đóng modal đăng ký bằng cách remove class
        const registerModal = document.getElementById('registerModal');
        if (registerModal) {
            registerModal.classList.remove('show');
            registerModal.style.display = 'none';
            registerModal.setAttribute('aria-hidden', 'true');
        }
        
        // Xóa backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        
        // Reset body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Mở modal đăng nhập
        setTimeout(() => {
            const loginModalElement = document.getElementById('loginModal');
            if (loginModalElement) {
                const loginModal = new bootstrap.Modal(loginModalElement);
                loginModal.show();
            }
        }, 200);
    });
});

document.querySelectorAll('.switch-to-register').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🔄 Switching to Register modal');
        
        // Đóng modal đăng nhập bằng cách remove class
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.classList.remove('show');
            loginModal.style.display = 'none';
            loginModal.setAttribute('aria-hidden', 'true');
        }
        
        // Xóa backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        
        // Reset body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Mở modal đăng ký
        setTimeout(() => {
            const registerModalElement = document.getElementById('registerModal');
            if (registerModalElement) {
                const registerModal = new bootstrap.Modal(registerModalElement);
                registerModal.show();
            }
        }, 200);
    });
});
    
    // ================== TOGGLE PASSWORD ==================
    (function initPasswordToggles() {
        const toggles = [
            { input: 'loginPassword', toggle: 'toggleLoginPassword' },
            { input: 'registerPassword', toggle: 'toggleRegisterPassword' },
            { input: 'registerConfirm', toggle: 'toggleRegisterConfirm' }
        ];
        
        toggles.forEach(({ input, toggle }) => {
            const inputElement = document.getElementById(input);
            const toggleElement = document.getElementById(toggle);
            
            if (inputElement && toggleElement) {
                toggleElement.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (inputElement.type === 'password') {
                        inputElement.type = 'text';
                        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                    } else {
                        inputElement.type = 'password';
                        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                    }
                });
            }
        });
    })();
    
    console.log('✅ Auth System đã sẵn sàng');
});