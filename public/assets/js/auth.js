// FILE: assets/js/auth.js
console.log("⚠️ File auth.js đã được load!");

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Auth System đang khởi tạo...');
    
    // Chờ 100ms để đảm bảo DOM đã load xong
    setTimeout(initAuthSystem, 100);
});

function initAuthSystem() {
    const BASE_URL = '/Jshop/app/controllers/AuthController.php';
    let isProcessing = false;
    
    console.log('🔍 Kiểm tra modal...');
    console.log('Login Modal:', document.getElementById('loginModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    console.log('Register Modal:', document.getElementById('registerModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    console.log('OTP Modal:', document.getElementById('otpModal') ? '✅ Tìm thấy' : '❌ Không tìm thấy');
    
    // ================== TIỆN ÍCH ==================
    const AuthUtils = {
        showMessage(elementId, type, message, duration = 5000) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.warn(`Element #${elementId} not found`);
                return;
            }
            
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
            
            if (type !== 'info' && duration > 0) {
                setTimeout(() => {
                    if (element.innerHTML.includes(alertClass)) {
                        element.innerHTML = '';
                    }
                }, duration);
            }
        },
        
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
        
        async callAPI(action, data) {
            if (isProcessing) {
                return { status: 'error', message: 'Đang xử lý yêu cầu trước' };
            }
            
            isProcessing = true;
            
            try {
                const formData = new FormData();
                for (const key in data) {
                    if (data[key] !== undefined && data[key] !== null) {
                        formData.append(key, data[key]);
                    }
                }
                
                console.log(`📤 API: ${action}`, Object.fromEntries(formData));
                
                const response = await fetch(`${BASE_URL}?action=${action}`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                console.log(`📥 Response (${action}):`, result);
                
                return result;
            } catch (error) {
                console.error(`❌ API Error (${action}):`, error);
                return { status: 'error', message: 'Lỗi kết nối máy chủ' };
            } finally {
                isProcessing = false;
            }
        },
        
        closeModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) return;
            
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        },
        
        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    };
    
    // ================== ĐĂNG KÝ ==================
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        console.log('✅ Đăng ký form - Đã kết nối');
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const formData = {
                name: this.querySelector('[name="name"]').value.trim(),
                email: this.querySelector('[name="email"]').value.trim(),
                phone: this.querySelector('[name="phone"]').value.trim(),
                password: document.getElementById('registerPassword').value,
                confirm: document.getElementById('registerConfirm').value
            };
            
            // Validate
            if (!formData.name || !formData.email || !formData.password || !formData.confirm) {
                AuthUtils.showMessage('registerMessage', 'error', 'Vui lòng điền đầy đủ thông tin');
                return;
            }
            
            if (!AuthUtils.validateEmail(formData.email)) {
                AuthUtils.showMessage('registerMessage', 'error', 'Email không hợp lệ');
                return;
            }
            
            if (formData.password.length < 6) {
                AuthUtils.showMessage('registerMessage', 'error', 'Mật khẩu ít nhất 6 ký tự');
                return;
            }
            
            if (formData.password !== formData.confirm) {
                AuthUtils.showMessage('registerMessage', 'error', 'Mật khẩu không khớp');
                return;
            }
            
            AuthUtils.showMessage('registerMessage', 'info', 'Đang đăng ký...');
            AuthUtils.setLoading(submitBtn, true);
            
            try {
                const result = await AuthUtils.callAPI('register', formData);
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('registerMessage', 'success', result.message);
                    
                    // Đóng modal đăng ký
                    setTimeout(() => {
                        AuthUtils.closeModal('registerModal');
                    }, 1000);
                    
                    // Mở modal OTP
                    setTimeout(() => {
                        if (window.showOTPModal && typeof window.showOTPModal === 'function') {
                            window.showOTPModal(formData.email);
                        } else {
                            console.log('Mở OTP modal thủ công');
                            // Fallback: mở modal OTP trực tiếp
                            const otpModal = document.getElementById('otpModal');
                            if (otpModal) {
                                const emailInput = document.getElementById('otpEmail');
                                if (emailInput) emailInput.value = formData.email;
                                
                                const emailDisplay = document.getElementById('otpEmailDisplay');
                                if (emailDisplay) emailDisplay.textContent = `OTP đã gửi đến: ${formData.email}`;
                                
                                const modal = new bootstrap.Modal(otpModal);
                                modal.show();
                            } else {
                                alert(`Vui lòng nhập OTP đã gửi đến: ${formData.email}`);
                            }
                        }
                    }, 500);
                    
                } else {
                    AuthUtils.showMessage('registerMessage', 'error', result.message);
                }
            } catch (error) {
                AuthUtils.showMessage('registerMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(submitBtn, false);
            }
        });
    } else {
        console.log('⚠️ Đăng ký form - Không tìm thấy');
    }
    
    // ================== ĐĂNG NHẬP ==================
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        console.log('✅ Đăng nhập form - Đã kết nối');
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = this.querySelector('[name="email"]').value.trim();
            const password = this.querySelector('[name="password"]').value;
            
            if (!email || !password) {
                AuthUtils.showMessage('loginMessage', 'error', 'Vui lòng nhập email và mật khẩu');
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
                    AuthUtils.showMessage('loginMessage', 'success', result.message);
                    
                    setTimeout(() => {
                        AuthUtils.closeModal('loginModal');
                        window.location.reload();
                    }, 1000);
                    
                } else {
                    AuthUtils.showMessage('loginMessage', 'error', result.message);
                }
            } catch (error) {
                AuthUtils.showMessage('loginMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(submitBtn, false);
            }
        });
    } else {
        console.log('⚠️ Đăng nhập form - Không tìm thấy');
    }
    
    // ================== XỬ LÝ OTP ==================
    const otpForm = document.getElementById('otpForm');
    if (otpForm) {
        console.log('✅ OTP form - Đã kết nối');
        otpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.otp-submit-btn');
            const email = document.getElementById('otpEmail')?.value;
            const otp = document.getElementById('fullOtp')?.value;
            
            if (!email || !otp || otp.length !== 6) {
                AuthUtils.showMessage('otpMessage', 'error', 'Vui lòng nhập đủ 6 số OTP');
                return;
            }
            
            AuthUtils.showMessage('otpMessage', 'info', 'Đang xác thực OTP...');
            AuthUtils.setLoading(submitBtn, true);
            
            try {
                const result = await AuthUtils.callAPI('verifyOTP', { email, otp });
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('otpMessage', 'success', result.message);
                    
                    setTimeout(() => {
                        AuthUtils.closeModal('otpModal');
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                    
                } else {
                    AuthUtils.showMessage('otpMessage', 'error', result.message);
                }
            } catch (error) {
                AuthUtils.showMessage('otpMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(submitBtn, false);
            }
        });
    } else {
        console.log('⚠️ OTP form - Không tìm thấy');
    }
    
    // ================== GỬI LẠI OTP ==================
    const resendBtn = document.getElementById('resendOTPBtn');
    if (resendBtn) {
        console.log('✅ Nút gửi lại OTP - Đã kết nối');
        resendBtn.addEventListener('click', async function() {
            const email = document.getElementById('otpEmail')?.value;
            if (!email) {
                AuthUtils.showMessage('otpMessage', 'error', 'Không tìm thấy email');
                return;
            }
            
            AuthUtils.showMessage('otpMessage', 'info', 'Đang gửi lại OTP...');
            AuthUtils.setLoading(this, true);
            
            try {
                const result = await AuthUtils.callAPI('resendOTP', { email });
                
                if (result.status === 'success') {
                    AuthUtils.showMessage('otpMessage', 'success', result.message);
                } else {
                    AuthUtils.showMessage('otpMessage', 'error', result.message);
                }
            } catch (error) {
                AuthUtils.showMessage('otpMessage', 'error', 'Lỗi hệ thống');
            } finally {
                AuthUtils.setLoading(this, false);
            }
        });
    }
    
    // ================== CHUYỂN MODAL ==================
    // Login -> Register
    document.querySelectorAll('.switch-to-register').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                const modalInstance = bootstrap.Modal.getInstance(loginModal);
                if (modalInstance) modalInstance.hide();
            }
            
            setTimeout(() => {
                const registerModal = document.getElementById('registerModal');
                if (registerModal) {
                    const modal = new bootstrap.Modal(registerModal);
                    modal.show();
                }
            }, 200);
        });
    });
    
    // Register -> Login
    document.querySelectorAll('.switch-to-login').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const registerModal = document.getElementById('registerModal');
            if (registerModal) {
                const modalInstance = bootstrap.Modal.getInstance(registerModal);
                if (modalInstance) modalInstance.hide();
            }
            
            setTimeout(() => {
                const loginModal = document.getElementById('loginModal');
                if (loginModal) {
                    const modal = new bootstrap.Modal(loginModal);
                    modal.show();
                }
            }, 200);
        });
    });
    
    // ================== TOGGLE PASSWORD ==================
    function initPasswordToggles() {
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
    }
    
    initPasswordToggles();
    
    // ================== OTP INPUT HANDLER ==================
    function initOTPInputs() {
        const otpInputs = document.querySelectorAll('.otp-input');
        
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const value = this.value;
                
                // Chỉ cho phép số
                if (!/^\d*$/.test(value)) {
                    this.value = '';
                    return;
                }
                
                // Giới hạn 1 ký tự
                if (value.length > 1) {
                    this.value = value.charAt(0);
                }
                
                // Chuyển sang ô tiếp theo
                if (value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Cập nhật OTP đầy đủ
                updateFullOTP();
            });
            
            // Xử lý backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    e.preventDefault();
                    otpInputs[index - 1].focus();
                }
            });
            
            // Paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasted = e.clipboardData.getData('text').trim();
                
                if (/^\d{6}$/.test(pasted)) {
                    for (let i = 0; i < 6; i++) {
                        if (otpInputs[i]) {
                            otpInputs[i].value = pasted[i];
                        }
                    }
                    updateFullOTP();
                    if (otpInputs[5]) otpInputs[5].focus();
                }
            });
        });
        
        function updateFullOTP() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let fullOtp = '';
            
            otpInputs.forEach(input => {
                fullOtp += input.value;
            });
            
            const fullOtpInput = document.getElementById('fullOtp');
            if (fullOtpInput) {
                fullOtpInput.value = fullOtp;
            }
            
            const submitBtn = document.querySelector('.otp-submit-btn');
            if (submitBtn) {
                submitBtn.disabled = fullOtp.length !== 6;
            }
        }
    }
    
    initOTPInputs();
    
    // ================== GLOBAL FUNCTIONS ==================
    window.showOTPModal = function(email) {
        console.log('📧 Mở OTP modal cho:', email);
        
        const otpModal = document.getElementById('otpModal');
        if (!otpModal) {
            console.error('Không tìm thấy OTP modal');
            return false;
        }
        
        const emailInput = document.getElementById('otpEmail');
        const emailDisplay = document.getElementById('otpEmailDisplay');
        
        if (emailInput) emailInput.value = email;
        if (emailDisplay) emailDisplay.textContent = `OTP đã gửi đến: ${email}`;
        
        // Reset OTP inputs
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
        });
        document.getElementById('fullOtp').value = '';
        
        // Mở modal
        const modal = new bootstrap.Modal(otpModal, { backdrop: 'static' });
        modal.show();
        
        // Focus vào ô đầu tiên
        setTimeout(() => {
            const firstInput = document.querySelector('.otp-input');
            if (firstInput) firstInput.focus();
        }, 300);
        
        return true;
    };
    
    console.log('✅ Auth System đã sẵn sàng');
}