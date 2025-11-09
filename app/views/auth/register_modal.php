<!-- MODAL ĐĂNG KÝ -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
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
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                </form>
                <div id="registerMessage" class="mt-3"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <p class="text-muted mb-0">Đã có tài khoản? 
                    <a href="#" class="switch-to-login" data-bs-dismiss="modal">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>