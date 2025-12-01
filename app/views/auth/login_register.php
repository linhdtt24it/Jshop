<!-- MODAL ĐĂNG NHẬP -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark bg-light">
            <div class="modal-header border-0">
                <h5 class="modal-title">Đăng nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                            <span class="input-group-text bg-white cursor-pointer" id="toggleLoginPassword">
                                <i class="bi bi-eye-fill"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Đăng nhập</button>
                </form>
                <div id="loginMessage" class="mt-3"></div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <p class="text-muted mb-0">Chưa có tài khoản? 
                    <a href="#" class="switch-to-register" data-bs-dismiss="modal">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ĐĂNG KÝ -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark bg-light">
            <div class="modal-header border-0">
                <h5 class="modal-title">Đăng ký</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="registerPassword" required>
                            <span class="input-group-text bg-white cursor-pointer" id="toggleRegisterPassword">
                                <i class="bi bi-eye-fill"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <input type="password" name="confirm" class="form-control" id="registerConfirm" required>
                            <span class="input-group-text bg-white cursor-pointer" id="toggleRegisterConfirm">
                                <i class="bi bi-eye-fill"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Đăng ký</button>
                </form>
                <div id="registerMessage" class="mt-3"></div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <p class="text-muted mb-0">Đã có tài khoản? 
                    <a href="#" class="switch-to-login" data-bs-dismiss="modal">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
