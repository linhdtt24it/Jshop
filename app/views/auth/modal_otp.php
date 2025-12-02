<!-- MODAL OTP - ĐẶT TRƯỚC </body> -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="otpModalLabel">
                    <i class="bi bi-shield-check me-2"></i>XÁC THỰC OTP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-light p-3 rounded mb-3">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-2">ĐÃ GỬI MÃ OTP</h5>
                        <p class="text-muted mb-0">Mã OTP đã được gửi đến email của bạn</p>
                    </div>
                </div>

                <form id="otpForm">
                    <input type="hidden" id="otpEmail" name="email">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nhập mã OTP 6 số:</label>
                        <input type="text" 
                               name="otp" 
                               id="otpInput"
                               class="form-control form-control-lg text-center fw-bold" 
                               placeholder="123456" 
                               maxlength="6"
                               required
                               style="font-size: 2rem; letter-spacing: 1rem; height: 4rem;">
                        <div class="form-text">Vui lòng nhập đúng mã OTP 6 số từ email</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold">
                            <i class="bi bi-check-circle me-2"></i>XÁC NHẬN OTP
                        </button>
                        
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="resendOTP()"
                                id="resendOTPBtn">
                            <i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP
                        </button>
                        
                        <button type="button" 
                                class="btn btn-link text-muted"
                                onclick="closeOTPModal()">
                            <small>Đóng</small>
                        </button>
                    </div>
                </form>
                
                <div id="otpMessage" class="mt-4"></div>
                
                <div class="alert alert-light border mt-4">
                    <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Hướng dẫn:</h6>
                    <ul class="mb-0 small">
                        <li>Kiểm tra cả <strong>hộp thư đến</strong> và <strong>thư mục spam</strong></li>
                        <li>Mã OTP có hiệu lực trong <strong>15 phút</strong></li>
                        <li>Không chia sẻ mã OTP với bất kỳ ai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ... phần HTML modal hiện tại ... -->

<style>
#otpModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
#otpInput:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>


<script>
// Hàm mở modal OTP
window.showOTPModal = function(email, otpCode = null) {
    console.log('📧 showOTPModal called for:', email);
    
    const otpModalElement = document.getElementById('otpModal');
    if(!otpModalElement) {
        console.error('❌ OTP Modal not found in DOM');
        alert(`Vui lòng nhập OTP đã gửi đến: ${email}`);
        return;
    }
    
    // Đặt email vào input ẩn
    const otpEmailInput = document.getElementById('otpEmail');
    if(otpEmailInput) {
        otpEmailInput.value = email;
    }
    
    // Cập nhật thông báo email
    const emailText = document.querySelector('#otpModal .text-muted.mb-0');
    if(emailText) {
        emailText.textContent = `Mã OTP đã được gửi đến: ${email}`;
    }
    
    // Hiển thị OTP test nếu có
    const otpMessageDiv = document.getElementById('otpMessage');
    if(otpMessageDiv) {
        if(otpCode) {
            otpMessageDiv.innerHTML = `<div class="alert alert-warning">OTP TEST: <strong>${otpCode}</strong></div>`;
        } else {
            otpMessageDiv.innerHTML = '';
        }
    }
    
    // Mở modal bằng Bootstrap
    const otpModal = new bootstrap.Modal(otpModalElement);
    otpModal.show();
    
    // Focus vào input OTP
    setTimeout(() => {
        const otpInput = document.getElementById('otpInput');
        if(otpInput) {
            otpInput.focus();
            otpInput.select();
        }
    }, 500);
    
    // Reset form
    const otpForm = document.getElementById('otpForm');
    if(otpForm && otpForm.reset) {
        otpForm.reset();
        if(otpEmailInput) otpEmailInput.value = email; // Giữ lại email
    }
};

// Hàm đóng modal OTP
window.closeOTPModal = function() {
    const otpModalElement = document.getElementById('otpModal');
    if(otpModalElement) {
        const otpModal = bootstrap.Modal.getInstance(otpModalElement);
        if(otpModal) {
            otpModal.hide();
        }
    }
};
</script>