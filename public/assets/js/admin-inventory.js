// Jshop/public/assets/js/admin-inventory.js

const modalElement = document.getElementById('inventoryModal');
// Đảm bảo Bootstrap Modal object đã được khởi tạo
const inventoryModal = new bootstrap.Modal(modalElement); 
const form = document.getElementById('inventoryForm');
const apiUrl = ADMIN_CONTROLLER_URL;

window.openStockModal = function(id) {
    // 1. Lấy chi tiết sản phẩm hiện tại để điền vào modal
    fetch(`${apiUrl}?action=get_product_detail&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                const data = res.data;
                document.getElementById('productId').value = data.product_id;
                document.getElementById('productName').innerText = data.name;
                document.getElementById('currentStock').innerText = data.stock;
                document.getElementById('newStock').value = data.stock;
                inventoryModal.show();
            } else {
                alert('Không tìm thấy thông tin sản phẩm');
            }
        })
        .catch(err => alert('Lỗi hệ thống khi lấy chi tiết sản phẩm.'));
}

form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(); 
    
    // Lấy ID và số lượng mới từ form
    formData.append('product_id', document.getElementById('productId').value);
    formData.append('stock', document.getElementById('newStock').value);
    
    // 2. Gửi yêu cầu cập nhật tồn kho
    fetch(`${apiUrl}?action=update_stock_ajax`, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(res => {
        alert(res.message);
        if(res.status === 'success') {
            inventoryModal.hide();
            // Tải lại trang để cập nhật bảng
            location.reload(); 
        }
    })
    .catch(err => alert('Lỗi hệ thống: Không thể cập nhật tồn kho.'));
});