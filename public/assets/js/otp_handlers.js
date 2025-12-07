// FILE: otp_handlers.js - SỬA LẠI

// ================== FIX TOGGLE PASSWORD TRƯỚC ==================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Fixing password toggles...');
    
    // SIMPLE FIX: Thêm sự kiện cho các nút toggle
    const toggles = [
        { input: 'loginPassword', toggle: 'toggleLoginPassword' },
        { input: 'registerPassword', toggle: 'toggleRegisterPassword' },
        { input: 'registerConfirm', toggle: 'toggleRegisterConfirm' }
    ];
    
    toggles.forEach(({ input, toggle }) => {
        const inputElement = document.getElementById(input);
        const toggleElement = document.getElementById(toggle);
        
        if (inputElement && toggleElement) {
            // Đảm bảo có padding-right cho input
            inputElement.style.paddingRight = '40px';
            
            // Đảm bảo toggle ở đúng vị trí
            toggleElement.style.cssText = `
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                background: transparent;
                border: none;
                color: #6c757d;
                cursor: pointer;
                padding: 5px;
            `;
            
            // Thêm sự kiện click
            toggleElement.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                if (inputElement.type === 'password') {
                    inputElement.type = 'text';
                    icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                } else {
                    inputElement.type = 'password';
                    icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                }
            });
            
            console.log(`✅ Fixed toggle for ${input}`);
        }
    });
    
    // Tiếp tục với OTP handlers
    initOTPHandlers();
});

function initOTPHandlers() {
    console.log('🔧 OTP Handler đang khởi tạo...');
    
    const BASE_URL = '/Jshop/app/controllers/AuthController.php';
    let isVerifying = false;
    
    // ================== KHỞI TẠO OTP INPUT ==================
    function initOTPInputs() {
        const otpInputs = document.querySelectorAll('.otp-input');
        
        otpInputs.forEach((input, index) => {
            // Data index
            input.dataset.index = index + 1;
            
            // Input
            input.addEventListener('input', function(e) {
                const value = this.value.replace(/\D/g, '').charAt(0);
                this.value = value;
                
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                updateFullOTP();
            });
            
            // Keydown
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // Focus
            input.addEventListener('focus', function() {
                this.select();
            });
        });
        
        function updateFullOTP() {
            let fullOtp = '';
            otpInputs.forEach(input => {
                fullOtp += input.value;
            });
            
            const fullOtpInput = document.getElementById('fullOtp');
            if (fullOtpInput) {
                fullOtpInput.value = fullOtp;
            }
            
            const submitBtn = document.getElementById('verifyOtpBtn');
            if (submitBtn) {
                submitBtn.disabled = fullOtp.length !== 6;
            }
            
            return fullOtp;
        }
    }
    
    // ================== HIỂN THỊ THÔNG BÁO ==================
    function showOTPMessage(type, message) {
        const messageDiv = document.getElementById('otpMessage');
        if (!messageDiv) return;
        
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';
        
        messageDiv.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }
    
    // ================== XỬ LÝ SUBMIT OTP ==================
    function initOTPSubmit() {
        const verifyBtn = document.getElementById('verifyOtpBtn');
        if (!verifyBtn) return;
        
        verifyBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (isVerifying) return;
            
            const email = document.getElementById('otpEmail')?.value;
            const otpInputs = document.querySelectorAll('.otp-input');
            let fullOtp = '';
            
            otpInputs.forEach(input => {
                fullOtp += input.value;
            });
            
            if (!email) {
                showOTPMessage('error', 'Không tìm thấy email');
                return;
            }
            
            if (fullOtp.length !== 6) {
                showOTPMessage('error', 'Vui lòng nhập đủ 6 số OTP');
                return;
            }
            
            isVerifying = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xác thực...';
            this.disabled = true;
            
            showOTPMessage('info', 'Đang xác thực OTP...');
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('otp', fullOtp);
                
                const response = await fetch(`${BASE_URL}?action=verifyOTP`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                console.log('OTP Result:', result);
                
                if (result.status === 'success') {
                    showOTPMessage('success', result.message);
                    
                    setTimeout(() => {
                        const otpModal = document.getElementById('otpModal');
                        if (otpModal) {
                            bootstrap.Modal.getInstance(otpModal).hide();
                        }
                        
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                    
                } else {
                    showOTPMessage('error', result.message);
                    this.innerHTML = originalText;
                    this.disabled = false;
                    isVerifying = false;
                }
                
            } catch (error) {
                showOTPMessage('error', 'Lỗi kết nối');
                this.innerHTML = originalText;
                this.disabled = false;
                isVerifying = false;
            }
        });
    }
    
    // ================== GỬI LẠI OTP ==================
    function initResendOTP() {
        const resendBtn = document.getElementById('resendOTPBtn');
        if (!resendBtn) return;
        
        resendBtn.addEventListener('click', async function() {
            const email = document.getElementById('otpEmail')?.value;
            if (!email) {
                showOTPMessage('error', 'Không tìm thấy email');
                return;
            }
            
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
            this.disabled = true;
            
            showOTPMessage('info', 'Đang gửi lại OTP...');
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                
                const response = await fetch(`${BASE_URL}?action=resendOTP`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showOTPMessage('success', result.message);
                    
                    // Reset OTP inputs
                    document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                    document.getElementById('fullOtp').value = '';
                    
                    // Focus ô đầu
                    setTimeout(() => {
                        const firstInput = document.querySelector('.otp-input[data-index="1"]');
                        if (firstInput) firstInput.focus();
                    }, 100);
                    
                } else {
                    showOTPMessage('error', result.message);
                }
                
            } catch (error) {
                showOTPMessage('error', 'Lỗi kết nối');
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    }
    
    // ================== HỦY ĐĂNG KÝ ==================
    async function cancelRegistration(email) {
        if (!email) return;
        
        try {
            const formData = new FormData();
            formData.append('email', email);
            
            await fetch(`${BASE_URL}?action=cancelRegistration`, {
                method: 'POST',
                body: formData
            });
            
            console.log('Đã hủy đăng ký:', email);
        } catch (error) {
            console.error('Lỗi hủy đăng ký:', error);
        }
    }
    
    // ================== HÀM HIỂN THỊ MODAL ==================
    window.showOTPModal = function(email) {
        console.log('Mở OTP modal cho:', email);
        
        const otpModal = document.getElementById('otpModal');
        if (!otpModal) {
            alert(`Vui lòng nhập OTP đã gửi đến: ${email}`);
            return false;
        }
        
        // Cập nhật email
        const emailInput = document.getElementById('otpEmail');
        const emailDisplay = document.getElementById('otpEmailDisplay');
        
        if (emailInput) emailInput.value = email;
        if (emailDisplay) emailDisplay.textContent = email;
        
        // Reset OTP
        document.querySelectorAll('.otp-input').forEach(input => input.value = '');
        document.getElementById('fullOtp').value = '';
        
        // Mở modal
        const modal = new bootstrap.Modal(otpModal, { 
            backdrop: 'static',
            keyboard: false 
        });
        modal.show();
        
        // Focus ô đầu
        setTimeout(() => {
            const firstInput = document.querySelector('.otp-input[data-index="1"]');
            if (firstInput) firstInput.focus();
        }, 300);
        
        // Khi đóng modal: hủy đăng ký
        otpModal.addEventListener('hidden.bs.modal', function() {
            cancelRegistration(email);
        });
        
        return true;
    };
    
    // ================== KHỞI TẠO ==================
    initOTPInputs();
    initOTPSubmit();
    initResendOTP();
    
    console.log('✅ OTP Handler đã sẵn sàng');
}