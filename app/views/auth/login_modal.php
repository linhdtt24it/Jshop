<!-- MODAL ĐĂNG NHẬP -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đăng nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
                <div id="loginMessage" class="mt-3"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <p class="text-muted mb-0">Chưa có tài khoản? 
                    <a href="#" class="switch-to-register" data-bs-dismiss="modal">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>