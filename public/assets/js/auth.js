document.addEventListener('DOMContentLoaded', function() {
    let currentModal = null;

    // Chuyển modal
    function switchModal(fromModalId, toModalId) {
        if(currentModal) currentModal.hide();

        setTimeout(()=>{
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            const newModal = new bootstrap.Modal(document.getElementById(toModalId));
            newModal.show();
            currentModal = newModal;
        }, 200);
    }

    // Chuyển giữa login/register
    document.querySelectorAll('.switch-to-login').forEach(link=>{
        link.addEventListener('click', e=>{
            e.preventDefault();
            switchModal('registerModal','loginModal');
        });
    });

    document.querySelectorAll('.switch-to-register').forEach(link=>{
        link.addEventListener('click', e=>{
            e.preventDefault();
            switchModal('loginModal','registerModal');
        });
    });

    // AJAX đăng nhập
    document.getElementById('loginForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('loginMessage');
        messageDiv.innerHTML = '<div class="alert alert-info">Đang xử lý...</div>';

        fetch('/app/controllers/AuthController.php?action=login',{
            method:'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status==='success'){
                messageDiv.innerHTML = '<div class="alert alert-success">Đăng nhập thành công!</div>';
                setTimeout(()=>location.reload(),1000);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(err=>{
            messageDiv.innerHTML = `<div class="alert alert-danger">Lỗi server. Xem console.</div>`;
            console.error(err);
        });
    });

    // AJAX đăng ký
    document.getElementById('registerForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('registerMessage');
        messageDiv.innerHTML = '<div class="alert alert-info">Đang xử lý...</div>';

        fetch('/app/controllers/AuthController.php?action=register',{
            method:'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status==='success'){
                messageDiv.innerHTML = '<div class="alert alert-success">Đăng ký thành công! Kiểm tra email để nhận OTP.</div>';
                setTimeout(()=>{
                    switchModal('registerModal','loginModal');
                    document.getElementById('registerForm').reset();
                },1500);
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(err=>{
            messageDiv.innerHTML = `<div class="alert alert-danger">Lỗi server. Xem console.</div>`;
            console.error(err);
        });
    });

    // Reset modal khi đóng
    document.getElementById('loginModal')?.addEventListener('hidden.bs.modal', ()=>currentModal=null);
    document.getElementById('registerModal')?.addEventListener('hidden.bs.modal', ()=>currentModal=null);

    // Toggle password

 function togglePassword(inputId, spanId){
    const input = document.getElementById(inputId);
    const span = document.getElementById(spanId);
    if(!input || !span) return;

    const icon = span.querySelector('i');
    if(!icon) return;

    span.addEventListener('click', ()=>{
        if(input.type==='password'){
            input.type='text';
            icon.classList.replace('bi-eye-fill','bi-eye-slash-fill');
        } else {
            input.type='password';
            icon.classList.replace('bi-eye-slash-fill','bi-eye-fill');
        }
    });
}
togglePassword('loginPassword','toggleLoginPassword');
togglePassword('registerPassword','toggleRegisterPassword');
togglePassword('registerConfirm','toggleRegisterConfirm');


});
