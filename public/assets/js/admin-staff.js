
const modalElement = document.getElementById('staffModal');
const staffModal = new bootstrap.Modal(modalElement);
const form = document.getElementById('staffForm');
const apiUrl = '/Jshop/app/controllers/AdminController.php';

function openAddModal() {
    form.reset(); 
    document.getElementById('staffId').value = ''; 
    document.getElementById('modalTitle').innerText = 'Thêm nhân viên mới';
    document.getElementById('password').required = true;
    document.getElementById('passHint').innerText = '';
    staffModal.show();
}

function editStaff(id) {
    document.getElementById('loadingOverlay').style.display = 'flex';
    fetch(`${apiUrl}?action=get_staff_detail&id=${id}`)
        .then(res => res.json())
        .then(res => {
            document.getElementById('loadingOverlay').style.display = 'none';
            if(res.status === 'success') {
                const data = res.data;
                document.getElementById('staffId').value = data.user_id;
                document.getElementById('fullName').value = data.full_name;
                document.getElementById('email').value = data.email;
                
                document.getElementById('phone').value = data.phone_number || ''; 

                document.getElementById('role').value = data.role;
                document.getElementById('password').required = false;
                document.getElementById('passHint').innerText = '(Để trống nếu không đổi)';
                document.getElementById('modalTitle').innerText = 'Cập nhật nhân viên';
                staffModal.show();
            } else {
                alert('Không tìm thấy thông tin');
            }
        });
}

form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`${apiUrl}?action=save_staff_ajax`, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            alert(res.message);
            staffModal.hide();
            location.reload(); 
        } else {
            alert(res.message);
        }
    })
    .catch(err => alert('Lỗi hệ thống'));
});

function deleteStaff(id) {
    if(!confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) return;
    fetch(`${apiUrl}?action=delete_staff_ajax&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                document.getElementById(`row-${id}`).remove();
                alert('Đã xóa thành công!');
            } else {
                alert(res.message);
            }
        });
}