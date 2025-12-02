// FILE: otp_handlers.js - Xử lý logic nhập OTP và kết nối với auth

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 OTP Handler đang khởi tạo...');
    
    // ================== KHỞI TẠO OTP INPUT ==================
    initOTPInputs();
    initOTPSubmit();
    initOTPModalEvents();
    
    console.log('✅ OTP Handler đã sẵn sàng');
});

// ================== KHỞI TẠO 6 Ô OTP ==================
function initOTPInputs() {
    const otpInputs = document.querySelectorAll('.otp-input');
    
    otpInputs.forEach(input => {
        // Xử lý nhập số
        input.addEventListener('input', function(e) {
            const value = this.value;
            const index = parseInt(this.dataset.index);
            
            // Chỉ cho phép nhập số
            if (!/^\d*$/.test(value)) {
                this.value = '';
                return;
            }
            
            // Giới hạn 1 ký tự
            if (value.length > 1) {
                this.value = value.charAt(0);
            }
            
            // Cập nhật trạng thái
            updateOTPInputState(this, value);
            
            // Chuyển sang ô tiếp theo nếu có giá trị
            if (value.length === 1 && index < 6) {
                const nextInput = document.querySelector(`.otp-input[data-index="${index + 1}"]`);
                if (nextInput) nextInput.focus();
            }
            
            // Cập nhật OTP đầy đủ
            updateFullOTP();
        });
        
        // Xử lý xóa bằng Backspace
        input.addEventListener('keydown', function(e) {
            const index = parseInt(this.dataset.index);
            
            if (e.key === 'Backspace' && this.value === '' && index > 1) {
                e.preventDefault();
                const prevInput = document.querySelector(`.otp-input[data-index="${index - 1}"]`);
                if (prevInput) {
                    prevInput.value = '';
                    prevInput.focus();
                    updateOTPInputState(prevInput, '');
                    updateFullOTP();
                }
            }
            
            // Xử lý mũi tên trái/phải
            if (e.key === 'ArrowLeft' && index > 1) {
                e.preventDefault();
                const prevInput = document.querySelector(`.otp-input[data-index="${index - 1}"]`);
                if (prevInput) prevInput.focus();
            }
            
            if (e.key === 'ArrowRight' && index < 6) {
                e.preventDefault();
                const nextInput = document.querySelector(`.otp-input[data-index="${index + 1}"]`);
                if (nextInput) nextInput.focus();
            }
        });
        
        // Xử lý paste OTP
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pastedData)) {
                // Gán từng số vào các ô
                for (let i = 0; i < 6; i++) {
                    const input = document.querySelector(`.otp-input[data-index="${i + 1}"]`);
                    if (input) {
                        input.value = pastedData.charAt(i);
                        updateOTPInputState(input, pastedData.charAt(i));
                    }
                }
                updateFullOTP();
                
                // Focus vào ô cuối
                const lastInput = document.querySelector('.otp-input[data-index="6"]');
                if (lastInput) lastInput.focus();
            }
        });
        
        // Focus: chọn toàn bộ text
        input.addEventListener('focus', function() {
            setTimeout(() => {
                this.select();
            }, 0);
        });
        
        // Click: chọn toàn bộ text
        input.addEventListener('click', function() {
            this.select();
        });
    });
}

// ================== CẬP NHẬT TRẠNG THÁI Ô OTP ==================
function updateOTPInputState(input, value) {
    if (value) {
        input.classList.add('filled');
        input.classList.remove('empty');
    } else {
        input.classList.remove('filled');
        input.classList.add('empty');
    }
}

// ================== CẬP NHẬT OTP ĐẦY ĐỦ ==================
function updateFullOTP() {
    const otpInputs = document.querySelectorAll('.otp-input');
    let fullOtp = '';
    
    otpInputs.forEach(input => {
        fullOtp += input.value;
    });
    
    // Cập nhật input ẩn
    const fullOtpInput = document.getElementById('fullOtp');
    if (fullOtpInput) {
        fullOtpInput.value = fullOtp;
    }
    
    // Cập nhật trạng thái nút submit
    const submitBtn = document.querySelector('.otp-submit-btn');
    if (submitBtn) {
        const isComplete = fullOtp.length === 6;
        submitBtn.disabled = !isComplete;
        
        // Thêm animation khi hoàn thành
        if (isComplete) {
            submitBtn.classList.add('pulse-animation');
            setTimeout(() => {
                submitBtn.classList.remove('pulse-animation');
            }, 1000);
        }
    }
    
    return fullOtp;
}

// ================== RESET FORM OTP ==================
function resetOTPForm() {
    // Reset 6 ô OTP
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach(input => {
        input.value = '';
        updateOTPInputState(input, '');
    });
    
    // Reset input ẩn
    const fullOtpInput = document.getElementById('fullOtp');
    if (fullOtpInput) fullOtpInput.value = '';
    
    // Reset nút submit
    const submitBtn = document.querySelector('.otp-submit-btn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('pulse-animation');
    }
    
    // Reset thông báo
    const messageDiv = document.getElementById('otpMessage');
    if (messageDiv) messageDiv.innerHTML = '';
    
    // Reset nút gửi lại
    const resendBtn = document.getElementById('resendOTPBtn');
    if (resendBtn) {
        resendBtn.disabled = false;
        resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP';
        resendBtn.classList.remove('btn-secondary');
        resendBtn.classList.add('btn-outline-dark');
    }
    
    // Xóa thông tin số lần gửi
    const attemptsInfo = document.getElementById('otpAttemptsInfo');
    if (attemptsInfo) attemptsInfo.innerHTML = '';
}

// ================== XỬ LÝ SUBMIT OTP ==================
function initOTPSubmit() {
    const otpForm = document.getElementById('otpForm');
    if (!otpForm) return;
    
    // Thêm sự kiện click cho nút submit
    const submitBtn = otpForm.querySelector('.otp-submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Kiểm tra OTP đủ 6 số
            const fullOtp = updateFullOTP();
            if (fullOtp.length !== 6) {
                showOTPMessage('error', 'Vui lòng nhập đủ 6 số OTP');
                
                // Focus vào ô đầu tiên trống
                const otpInputs = document.querySelectorAll('.otp-input');
                for (let input of otpInputs) {
                    if (!input.value) {
                        input.focus();
                        break;
                    }
                }
                return;
            }
            
            // Kiểm tra email
            const email = document.getElementById('otpEmail')?.value;
            if (!email) {
                showOTPMessage('error', 'Không tìm thấy thông tin email. Vui lòng đăng ký lại.');
                return;
            }
            
            // Gửi form OTP
            console.log('📤 Submitting OTP for:', email);
            otpForm.dispatchEvent(new Event('submit', { bubbles: true }));
        });
    }
}

// ================== HIỂN THỊ THÔNG BÁO OTP ==================
function showOTPMessage(type, message, duration = 5000) {
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
    
    // Tự động ẩn sau thời gian chỉ định
    if (type !== 'info') {
        setTimeout(() => {
            if (messageDiv.innerHTML.includes(alertClass)) {
                messageDiv.innerHTML = '';
            }
        }, duration);
    }
}

// ================== HÀM HIỂN THỊ MODAL OTP ==================
window.showOTPModal = function(email) {
    console.log('📩 Hiển thị OTP Modal cho:', email);
    
    // Lấy modal element
    const otpModalElement = document.getElementById('otpModal');
    if (!otpModalElement) {
        console.error('❌ Không tìm thấy OTP modal');
        alert(`Vui lòng nhập OTP đã gửi đến: ${email}`);
        return false;
    }
    
    // Cập nhật email
    const emailInput = document.getElementById('otpEmail');
    const emailDisplay = document.getElementById('otpEmailDisplay');
    
    if (emailInput) emailInput.value = email;
    if (emailDisplay) emailDisplay.textContent = email;
    
    // Reset form OTP
    resetOTPForm();
    
    // Hiển thị thông báo
    showOTPMessage('info', '<i class="bi bi-envelope-check me-2"></i>Mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư đến và thư mục spam.');
    
    // Hiển thị modal
    try {
        const otpModal = new bootstrap.Modal(otpModalElement, {
            backdrop: 'static',
            keyboard: false
        });
        otpModal.show();
        
        // Focus vào ô đầu tiên sau khi modal hiển thị
        setTimeout(() => {
            const firstInput = document.querySelector('.otp-input[data-index="1"]');
            if (firstInput) {
                firstInput.focus();
                firstInput.select();
            }
        }, 300);
        
        console.log('✅ OTP modal đã mở');
        return true;
        
    } catch (error) {
        console.error('❌ Lỗi mở OTP modal:', error);
        return false;
    }
};

// ================== CẬP NHẬT THÔNG TIN SỐ LẦN GỬI OTP ==================
window.updateOTPAttemptsInfo = function(attemptsLeft, currentAttempt) {
    const attemptsInfo = document.getElementById('otpAttemptsInfo');
    if (attemptsInfo) {
        attemptsInfo.innerHTML = `
            <div class="alert alert-light border small mt-2">
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
        resendBtn.classList.remove('btn-outline-dark');
        resendBtn.classList.add('btn-secondary');
    }
};

// ================== KHỞI TẠO SỰ KIỆN MODAL ==================
function initOTPModalEvents() {
    const otpModal = document.getElementById('otpModal');
    if (!otpModal) return;
    
    // Khi modal ẩn đi
    otpModal.addEventListener('hidden.bs.modal', function() {
        console.log('📭 OTP modal đã đóng');
        resetOTPForm();
    });
    
    // Khi modal hiển thị
    otpModal.addEventListener('shown.bs.modal', function() {
        console.log('📬 OTP modal đã mở');
        const firstInput = document.querySelector('.otp-input[data-index="1"]');
        if (firstInput) {
            setTimeout(() => {
                firstInput.focus();
                firstInput.select();
            }, 100);
        }
    });
}

// ================== THÊM ANIMATION CSS (thêm vào CSS hiện có) ==================
function addOTPAnimations() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse 0.5s ease-in-out;
        }
        
        .otp-input {
            transition: all 0.2s ease-in-out;
        }
        
        .otp-input.filled {
            box-shadow: 0 0 0 3px rgba(0, 255, 0, 0.1);
            background-color: #f8fff8;
        }
        
        .otp-input.empty {
            box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1);
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
}

// Thêm animation khi DOM ready
document.addEventListener('DOMContentLoaded', addOTPAnimations);

// ================== HÀM ĐÓNG MODAL OTP ==================
window.closeOTPModal = function() {
    const otpModalElement = document.getElementById('otpModal');
    if (!otpModalElement) return;
    
    const modal = bootstrap.Modal.getInstance(otpModalElement);
    if (modal) {
        modal.hide();
    }
};

// ================== HÀM GỬI LẠI OTP ==================
window.resendOTP = async function() {
    const email = document.getElementById('otpEmail')?.value;
    const resendBtn = document.getElementById('resendOTPBtn');
    
    if (!email) {
        showOTPMessage('error', 'Không tìm thấy thông tin email');
        return;
    }
    
    // Vô hiệu hóa nút ngay lập tức
    if (resendBtn) {
        const originalText = resendBtn.innerHTML;
        resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
        resendBtn.disabled = true;
    }
    
    console.log('🔄 Yêu cầu gửi lại OTP cho:', email);
    showOTPMessage('info', 'Đang gửi lại mã OTP...');
    
    try {
        // Gọi API resendOTP từ auth.js
        const result = await AuthUtils.callAPI('resendOTP', { email });
        
        if (result.status === 'success') {
            showOTPMessage('success', result.message || 'Đã gửi lại mã OTP thành công!');
            
            // Vô hiệu hóa nút trong 60 giây
            if (resendBtn) {
                AuthUtils.disableButton(resendBtn, 60, 'Gửi lại sau');
            }
            
            // Cập nhật thông tin số lần gửi
            if (window.updateOTPAttemptsInfo && result.attempts_left !== undefined) {
                window.updateOTPAttemptsInfo(result.attempts_left, result.current_attempt || 1);
            }
            
            // Reset và focus lại vào ô OTP
            resetOTPForm();
            const firstInput = document.querySelector('.otp-input[data-index="1"]');
            if (firstInput) {
                setTimeout(() => {
                    firstInput.focus();
                    firstInput.select();
                }, 100);
            }
            
        } else {
            showOTPMessage('error', result.message || 'Không thể gửi lại OTP');
            if (resendBtn) {
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP';
            }
        }
    } catch (error) {
        console.error('❌ Lỗi gửi lại OTP:', error);
        showOTPMessage('error', 'Lỗi kết nối máy chủ');
        if (resendBtn) {
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP';
        }
    }
};

// ================== EXPORT FUNCTIONS ==================
window.OTPHandler = {
    showOTPModal: window.showOTPModal,
    closeOTPModal: window.closeOTPModal,
    resendOTP: window.resendOTP,
    resetOTPForm: resetOTPForm,
    updateOTPAttemptsInfo: window.updateOTPAttemptsInfo
};