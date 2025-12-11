// Jshop/public/assets/js/admin-collection.js
const modalElement = document.getElementById('collectionModal');
const collectionModal = new bootstrap.Modal(modalElement);
const form = document.getElementById('collectionForm');

// Sử dụng biến global ADMIN_CONTROLLER_URL được định nghĩa trong view
const apiUrl = ADMIN_CONTROLLER_URL; 

function openCollectionModal() {
    form.reset(); 
    document.getElementById('collectionId').value = ''; 
    document.getElementById('modalTitle').innerText = 'Thêm Bộ sưu tập mới';
    collectionModal.show();
}

window.editCollection = function(id) {
    fetch(`${apiUrl}?action=get_collection_detail&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                const data = res.data;
                document.getElementById('collectionId').value = data.collection_id;
                document.getElementById('name').value = data.name;
                document.getElementById('slug').value = data.slug || ''; 
                document.getElementById('image').value = data.image || ''; 
                document.getElementById('description').value = data.description || '';
                document.getElementById('modalTitle').innerText = 'Cập nhật Bộ sưu tập';
                collectionModal.show();
            } else {
                alert('Không tìm thấy thông tin');
            }
        })
        .catch(err => alert('Lỗi hệ thống'));
}

form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`${apiUrl}?action=save_collection_ajax`, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            alert(res.message);
            collectionModal.hide();
            location.reload(); 
        } else {
            alert(res.message);
        }
    })
    .catch(err => alert('Lỗi hệ thống'));
});

window.deleteCollection = function(id) {
    if(!confirm('Bạn có chắc chắn muốn xóa bộ sưu tập này? Các sản phẩm liên quan sẽ bị mất liên kết!')) return;
    fetch(`${apiUrl}?action=delete_collection_ajax&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                document.getElementById(`row-${id}`).remove();
                alert('Đã xóa thành công!');
            } else {
                alert(res.message);
            }
        })
        .catch(err => alert('Lỗi hệ thống'));
}