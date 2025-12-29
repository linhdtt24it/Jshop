
const apiUrl = ADMIN_CONTROLLER_URL;

window.deleteProduct = function(id) {
    if(!confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác!')) return;
    
    fetch(`${apiUrl}?action=delete_product_ajax&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
               
                const row = document.getElementById(`row-product-${id}`);
                if (row) {
                    row.remove();
                }
                alert('Đã xóa sản phẩm thành công!');
            } else {
                alert(res.message);
            }
        })
        .catch(err => alert('Lỗi hệ thống khi xóa sản phẩm.'));
}