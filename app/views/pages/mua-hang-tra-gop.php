<?php 
// 1. Đặt page_title cho Header
$page_title = "Mua hàng trả góp"; 
include __DIR__ . '/../../layouts/header.php'; // THÊM HEADER
?>

<div class="container py-5">
    <h1><?= $page_title ?></h1>
    <p>
    – Khách hàng có thể mua trả góp qua các đối tác tài chính được công nhận.<br>
    – Thời hạn trả góp: 3, 6, 9, 12 tháng (tuỳ chương trình).<br>
    – Yêu cầu: CMND/CCCD hoặc Hộ chiếu, hợp đồng và giấy tờ liên quan.<br>
    – Lãi suất và phí xử lý được thể hiện rõ trong hợp đồng trả góp.<br>
    – Liên hệ bộ phận chăm sóc khách hàng để biết chi tiết và hỗ trợ hồ sơ.<br>
    </p>
</div>

<?php 
include __DIR__ . '/../../layouts/footer.php'; // THÊM FOOTER
?>