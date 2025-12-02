document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Auth JS loaded');
    
    const BASE_URL = '/Jshop/app/controllers/AuthController.php';
    
    // ================== DEBUG CHECK ==================
    console.log('🔍 OTP Modal:', document.getElementById('otpModal') ? '✅ Found' : '❌ Not found');
    console.log('🔍 Login Modal:', document.getElementById('loginModal') ? '✅ Found' : '❌ Not found');
    console.log('🔍 Register Modal:', document.getElementById('registerModal') ? '✅ Found' : '❌ Not found');
    console.log('🔍 Bootstrap:', typeof bootstrap !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
    
    // ================== HÀM GỌI API ==================
    function callAuthAPI(action, data) {
        const formData = new FormData();
        for (const key in data) {
            formData.append(key, data[key]);
        }
        
        return fetch(`${BASE_URL}?action=${action}`, {
            method: 'POST',
            body: formData
        }).then(res => res.json());
    }
    
    // ================== HÀM ĐÓNG/MỞ MODAL ==================
    function closeAllModals() {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
        
        // Xóa backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    function openModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if(modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            return modal;
        }
        return null;
    }
    
    // ================== ĐĂNG NHẬP ==================
    const loginForm = document.getElementById('loginForm');
    if(loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('loginMessage');
            
            const formData = {
                email: this.querySelector('[name="email"]').value,
                password: this.querySelector('[name="password"]').value
            };
            
            // Hiển thị loading
            messageDiv.innerHTML = '<div class="alert alert-info">Đang đăng nhập...</div>';
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
            
            try {
                const result = await callAuthAPI('login', formData);
                console.log('Login result:', result);
                
                if(result.status === 'success') {
                    messageDiv.innerHTML = '<div class="alert alert-success">Đăng nhập thành công!</div>';
                    
                    // Đóng modal đăng nhập
                    const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                    if(loginModal) {
                        loginModal.hide();
                    }
                    
                    setTimeout(() => location.reload(), 1000);
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            } catch(error) {
                console.error('Login error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">Lỗi kết nối</div>';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
    
    // ================== ĐĂNG KÝ ==================
    const registerForm = document.getElementById('registerForm');
    if(registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('registerMessage');
            
            // Kiểm tra mật khẩu
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
            
            // Lấy dữ liệu form
            const formData = {
                name: this.querySelector('[name="name"]').value,
                email: this.querySelector('[name="email"]').value,
                phone: this.querySelector('[name="phone"]').value,
                address: this.querySelector('[name="address"]').value,
                password: password,
                confirm: confirm
            };
            
            // Hiển thị loading
            messageDiv.innerHTML = '<div class="alert alert-info">Đang đăng ký...</div>';
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
            
            try {
                const result = await callAuthAPI('register', formData);
                console.log('Register result:', result);
                
                if(result.status === 'success' || result.status === 'warning') {
                    // Ẩn thông báo
                    messageDiv.innerHTML = '';
                    
                    // Đóng modal đăng ký
                    const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                    if(registerModal) {
                        registerModal.hide();
                    }
                    
                    // Reset form
                    this.reset();
                    
                    // MỞ MODAL OTP
                    setTimeout(() => {
                        const emailToShow = result.email || formData.email;
                        const otpCode = result.otp || null;
                        
                        console.log('Opening OTP modal for:', emailToShow);
                        
                        // Gọi hàm từ modal_otp.php
                        if(typeof window.showOTPModal === 'function') {
                            window.showOTPModal(emailToShow, otpCode);
                        } else {
                            // Fallback
                            const otpModalElement = document.getElementById('otpModal');
                            if(otpModalElement) {
                                const otpEmailInput = document.getElementById('otpEmail');
                                if(otpEmailInput) otpEmailInput.value = emailToShow;
                                
                                const otpModal = new bootstrap.Modal(otpModalElement);
                                otpModal.show();
                            } else {
                                alert(`Vui lòng nhập mã OTP đã gửi đến: ${emailToShow}`);
                            }
                        }
                    }, 500);
                    
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            } catch(error) {
                console.error('Register error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">Lỗi đăng ký</div>';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
    
    // ================== XÁC THỰC OTP ==================
    const otpForm = document.getElementById('otpForm');
    if(otpForm) {
        otpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('otpMessage');
            
            const formData = {
                email: document.getElementById('otpEmail').value,
                otp: this.querySelector('[name="otp"]').value
            };
            
            // Hiển thị loading
            messageDiv.innerHTML = '<div class="alert alert-info">Đang xác thực...</div>';
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
            
            try {
                const result = await callAuthAPI('verifyOTP', formData);
                console.log('Verify OTP result:', result);
                
                if(result.status === 'success') {
                    messageDiv.innerHTML = '<div class="alert alert-success">Xác thực thành công!</div>';
                    
                    // Đóng modal OTP
                    const otpModal = bootstrap.Modal.getInstance(document.getElementById('otpModal'));
                    if(otpModal) {
                        otpModal.hide();
                    }
                    
                    // Chuyển hướng hoặc reload
                    setTimeout(() => {
                        if(result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            location.reload();
                        }
                    }, 1000);
                    
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                }
            } catch(error) {
                console.error('Verify OTP error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">Lỗi xác thực</div>';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
    
    // ================== GỬI LẠI OTP ==================
    window.resendOTP = async function() {
        const email = document.getElementById('otpEmail').value;
        const messageDiv = document.getElementById('otpMessage');
        const resendBtn = document.getElementById('resendOTPBtn');
        
        if(!email) {
            alert('Vui lòng nhập email');
            return;
        }
        
        // Disable nút gửi lại
        if(resendBtn) {
            resendBtn.disabled = true;
            const originalText = resendBtn.innerHTML;
            resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang gửi...';
        }
        
        messageDiv.innerHTML = '<div class="alert alert-info">Đang gửi lại OTP...</div>';
        
        try {
            const formData = new FormData();
            formData.append('email', email);
            
            const response = await fetch(`${BASE_URL}?action=resendOTP`, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if(result.status === 'success' || result.status === 'warning') {
                messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                
                if(result.otp) {
                    messageDiv.innerHTML += `<div class="alert alert-warning mt-2">OTP mới: <strong>${result.otp}</strong></div>`;
                }
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
            }
        } catch(error) {
            console.error('Resend OTP error:', error);
            messageDiv.innerHTML = '<div class="alert alert-danger">Lỗi gửi lại OTP</div>';
        } finally {
            if(resendBtn) {
                setTimeout(() => {
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP';
                }, 30000);
            }
        }
    };
    
    // ================== CHUYỂN ĐỔI GIỮA LOGIN/REGISTER ==================
    function setupModalSwitcher() {
        // Chuyển từ Register sang Login
        document.querySelectorAll('.switch-to-login').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🔄 Switching to Login');
                
                // Đóng modal Register
                const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                if(registerModal) {
                    registerModal.hide();
                }
                
                // Mở modal Login sau 400ms
                setTimeout(() => {
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                }, 400);
            });
        });
        
        // Chuyển từ Login sang Register
        document.querySelectorAll('.switch-to-register').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🔄 Switching to Register');
                
                // Đóng modal Login
                const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                if(loginModal) {
                    loginModal.hide();
                }
                
                // Mở modal Register sau 400ms
                setTimeout(() => {
                    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                    registerModal.show();
                }, 400);
            });
        });
    }
    
    // Gọi hàm setup modal switcher
    setupModalSwitcher();
    
    // ================== TOGGLE PASSWORD ==================
    function setupPasswordToggle(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        
        if(input && toggle) {
            toggle.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if(input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                }
            });
        }
    }
    
    setupPasswordToggle('loginPassword', 'toggleLoginPassword');
    setupPasswordToggle('registerPassword', 'toggleRegisterPassword');
    setupPasswordToggle('registerConfirm', 'toggleRegisterConfirm');
    
    console.log('✅ Auth system ready - Modal switchers initialized');
});