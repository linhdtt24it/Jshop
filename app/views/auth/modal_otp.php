
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white border-bottom-0">
                <h5 class="modal-title" id="otpModalLabel">
                    <i class="bi bi-shield-check me-2"></i>XÁC THỰC OTP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white">
                <div class="text-center mb-4">
                    <div class="p-3 rounded mb-3">
                        <i class="bi bi-envelope-check otp-icon"></i>
                        <h5 class="mt-2 text-dark">ĐÃ GỬI MÃ OTP</h5>
                        <p class="text-secondary mb-0" id="otpEmailDisplay">Đang tải email...</p>
                    </div>
                </div>

                <form id="otpForm">
                    <input type="hidden" id="otpEmail" name="email">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-3">Nhập mã OTP 6 số:</label>
                        
                        
                        <div class="otp-container d-flex justify-content-between mb-3">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="1">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="2">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="3">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="4">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="5">
                            <input type="text" 
                                   class="otp-input form-control text-center fw-bold" 
                                   maxlength="1"
                                   data-index="6">
                        </div>
                        
                        <input type="hidden" name="otp" id="fullOtp">
                        
                        <div class="form-text text-secondary">Nhập từng số vào ô tương ứng</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg py-3 fw-bold otp-submit-btn">
                            <i class="bi bi-check-circle me-2"></i>XÁC NHẬN OTP
                        </button>
                        
                        <button type="button" 
                                class="btn btn-outline-dark otp-resend-btn"
                                onclick="resendOTP()"
                                id="resendOTPBtn">
                            <i class="bi bi-arrow-clockwise me-2"></i>GỬI LẠI OTP
                        </button>
                        
                        <button type="button" 
                                class="btn btn-link text-secondary otp-close-btn"
                                data-bs-dismiss="modal">
                            <small>Đóng</small>
                        </button>
                    </div>
                </form>
                
                <div id="otpMessage" class="mt-4"></div>
                
                <div class="otp-guide border border-dark rounded p-3 mt-4 bg-light">
                    <h6 class="text-dark mb-2"><i class="bi bi-info-circle me-2"></i>Hướng dẫn:</h6>
                    <ul class="mb-0 small text-dark">
                        <li>Kiểm tra cả <strong>hộp thư đến</strong> và <strong>thư mục spam</strong></li>
                        <li>Mã OTP có hiệu lực trong <strong>15 phút</strong></li>
                        <li>Không chia sẻ mã OTP với bất kỳ ai</li>
                        <li>Nhập từng số vào từng ô riêng biệt</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>