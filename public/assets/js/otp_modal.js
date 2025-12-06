// FILE: otp_modal.js - Quản lý modal OTP và input 6 ô

// ================== BIẾN TOÀN CỤC ==================
let otpTimer = null;
let otpExpiryTime = null;

// ================== QUẢN LÝ 6 Ô OTP ==================
function setupOTPInputs() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const fullOtpInput = document.getElementById('fullOtp');
    
    // Hàm cập nhật OTP đầy đủ
    function updateFullOTP() {
        let fullOtp = '';
        otpInputs.forEach(input => {
            fullOtp += input.value;
        });
        
        if (fullOtpInput) {
            fullOtpInput.value = fullOtp;
        }
        
        return fullOtp;
    }
    
    // Xử lý cho từng ô OTP
    otpInputs.forEach((input, index) => {
        // Focus: chọn toàn bộ nội dung
        input.addEventListener('focus', function() {
            this.select();
        });
        
        // Nhập số
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
            
            // Nếu có giá trị, chuyển sang ô tiếp theo
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
                otpInputs[index + 1].select();
            }
            
            // Cập nhật OTP đầy đủ
            updateFullOTP();
            
            // Thêm class filled
            this.classList.toggle('filled', this.value.length > 0);
        });
        
        // Xử lý phím Backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].select();
            }
        });
        
        // Xử lý paste (cho phép paste 6 số cùng lúc)
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pastedText)) {
                // Nhập 6 số vào 6 ô
                for (let i = 0; i < 6; i++) {
                    if (otpInputs[i]) {
                        otpInputs[i].value = pastedText[i] || '';
                        otpInputs[i].classList.add('filled');
                    }
                }
                
                updateFullOTP();
                
                // Focus vào ô cuối
                if (otpInputs[5]) {
                    otpInputs[5].focus();
                    otpInputs[5].select();
                }
            }
        });
    });
    
    // Hàm reset OTP inputs
    window.resetOTPInputs = function() {
        otpInputs.forEach(input => {
            input.value = '';
            input.classList.remove('filled');
        });
        
        if (fullOtpInput) {
            fullOtpInput.value = '';
        }
    };
    
    // Focus vào ô đầu tiên
    window.focusFirstOTPInput = function() {
        if (otpInputs[0]) {
            otpInputs[0].focus();
            otpInputs[0].select();
        }
    };
    
    return {
        updateFullOTP,
        resetOTPInputs: window.resetOTPInputs,
        focusFirstOTPInput: window.focusFirstOTPInput
    };
}

// ================== QUẢN LÝ THỜI GIAN OTP ==================
function startOTPTimer() {
    stopOTPTimer();
    
    // Mặc định 60 phút (1 giờ)
    otpExpiryTime = new Date();
    otpExpiryTime.setMinutes(otpExpiryTime.getMinutes() + 60);
    
    updateOTPTimerDisplay();
    
    // Cập nhật mỗi giây
    otpTimer = setInterval(updateOTPTimerDisplay, 1000);
}

function stopOTPTimer() {
    if (otpTimer) {
        clearInterval(otpTimer);
        otpTimer = null;
    }
}

function updateOTPTimerDisplay() {
    if (!otpExpiryTime) return;
    
    const now = new Date();
    const timeLeft = otpExpiryTime - now;
    
    const timerElement = document.getElementById('otpTimer');
    if (!timerElement) return;
    
    if (timeLeft <= 0) {
        stopOTPTimer();
        timerElement.innerHTML = '<span class="text-danger"><i class="bi bi-clock"></i> OTP đã hết hạn</span>';
        return;
    }
    
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    
    timerElement.innerHTML = `<i class="bi bi-clock"></i> Còn lại: <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong>`;
    
    // Đổi màu theo thời gian còn lại
    if (minutes < 5) {
        timerElement.className = 'text-danger fw-bold';
    } else if (minutes < 10) {
        timerElement.className = 'text-warning fw-bold';
    } else {
        timerElement.className = 'text-success';
    }
}

// ================== MỞ MODAL OTP ==================
window.showOTPModal = function(email) {
    console.log('📧 Mở OTP modal cho:', email);
    
    const otpModalElement = document.getElementById('otpModal');
    if (!otpModalElement) {
        console.error('❌ Không tìm thấy OTP modal');
        alert(`Vui lòng nhập OTP đã gửi đến: ${email}`);
        return false;
    }
    
    // Đặt email vào input ẩn
    const otpEmailInput = document.getElementById('otpEmail');
    if (otpEmailInput) {
        otpEmailInput.value = email;
    }
    
    // Cập nhật hiển thị email
    const emailDisplay = document.getElementById('otpEmailDisplay');
    if (emailDisplay) {
        emailDisplay.textContent = `OTP đã gửi đến: ${email}`;
    }
    
    // Reset OTP inputs
    if (window.resetOTPInputs) {
        window.resetOTPInputs();
    }
    
    // Hiển thị thông báo
    const otpMessageDiv = document.getElementById('otpMessage');
    if (otpMessageDiv) {
        otpMessageDiv.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-envelope-check me-2"></i>
                Mã OTP đã được gửi đến email của bạn.<br>
                <small class="text-muted">Vui lòng kiểm tra hộp thư đến và thư mục spam</small>
            </div>
        `;
    }
    
    // Hiển thị thông tin số lần gửi OTP (mặc định)
    const attemptsInfo = document.getElementById('otpAttemptsInfo');
    if (attemptsInfo) {
        attemptsInfo.innerHTML = `
            <div class="alert alert-light border small">
                <i class="bi bi-info-circle me-1"></i>
                Bạn có thể gửi lại OTP tối đa 3 lần
            </div>
        `;
    }
    
    // Mở modal bằng Bootstrap
    try {
        const otpModal = new bootstrap.Modal(otpModalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        otpModal.show();
        
        // Bắt đầu đếm thời gian
        startOTPTimer();
        
        // Focus vào ô OTP đầu tiên
        setTimeout(() => {
            if (window.focusFirstOTPInput) {
                window.focusFirstOTPInput();
            }
        }, 300);
        
        console.log('✅ OTP modal đã mở');
        return true;
        
    } catch (error) {
        console.error('❌ Lỗi mở OTP modal:', error);
        return false;
    }
};

// ================== ĐÓNG MODAL OTP ==================
window.closeOTPModal = function() {
    console.log('🔒 Đóng OTP modal');
    
    const otpModalElement = document.getElementById('otpModal');
    if (!otpModalElement) return;
    
    // Dừng timer
    stopOTPTimer();
    
    // Đóng modal
    try {
        const modalInstance = bootstrap.Modal.getInstance(otpModalElement);
        if (modalInstance) {
            modalInstance.hide();
        } else {
            const newModal = new bootstrap.Modal(otpModalElement);
            newModal.hide();
        }
    } catch (error) {
        console.error('❌ Lỗi đóng modal:', error);
    }
};

// ================== DỌN DẸP BACKDROP ==================
function cleanupBackdrops() {
    // Xóa backdrop nếu không còn modal nào mở
    const openModals = document.querySelectorAll('.modal.show');
    if (openModals.length === 0) {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.remove();
        });
        
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
}

// ================== KHỞI TẠO KHI DOM READY ==================
document.addEventListener('DOMContentLoaded', function() {
    const otpModalElement = document.getElementById('otpModal');
    if (otpModalElement) {
        console.log('🔧 Đang khởi tạo OTP modal...');
        
        // Khởi tạo OTP inputs
        setupOTPInputs();
        
        // Xử lý sự kiện khi modal ẩn
        otpModalElement.addEventListener('hidden.bs.modal', function() {
            console.log('OTP modal đã ẩn');
            
            // Dừng timer
            stopOTPTimer();
            
            // Reset OTP inputs
            if (window.resetOTPInputs) {
                window.resetOTPInputs();
            }
            
            // Clear messages
            const otpMessageDiv = document.getElementById('otpMessage');
            if (otpMessageDiv) {
                otpMessageDiv.innerHTML = '';
            }
            
            // Dọn dẹp backdrop
            setTimeout(cleanupBackdrops, 150);
        });
        
        // Xử lý nút đóng
        const closeButtons = otpModalElement.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('Nút đóng được nhấn');
            });
        });
        
        // Tự động dọn dẹp backdrop định kỳ (phòng trường hợp lỗi)
        setInterval(cleanupBackdrops, 5000);
        
        console.log('✅ OTP modal đã sẵn sàng');
    }
});

// ================== EXPORT FUNCTIONS ==================
// Xuất các hàm cần thiết ra global scope
window.OTPManager = {
    showOTPModal: window.showOTPModal,
    closeOTPModal: window.closeOTPModal,
    resendOTP: window.resendOTP,
    resetOTPInputs: window.resetOTPInputs,
    focusFirstOTPInput: window.focusFirstOTPInput
};