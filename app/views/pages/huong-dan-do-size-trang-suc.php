<?php 
if (!defined('ROOT_PATH')) {
    // Đảm bảo ROOT_PATH được định nghĩa khi chạy độc lập
    define('ROOT_PATH', dirname(__DIR__, 3)); 
}
//...
$page_title = "Hướng Dẫn Đo Size Trang Sức"; 
include __DIR__ . '/../layouts/header.php'; 

?>

<div class="container py-4">
    <h1 class="text-3xl font-bold mb-5 text-center text-primary">HƯỚNG DẪN ĐO SIZE TRANG SỨC CHÍNH XÁC</h1>

    
    
    <div class="method-section mb-4">
        <h3 class="text-xl font-medium mt-3 text-rose-gold">Cách 1: Đo bằng đường kính (Rất chính xác)</h3>
        <p class="text-gray-700">
            Bạn tìm một chiếc nhẫn đeo vừa tay, tháo ra lấy thước học sinh đo **đường kính trong lòng nhẫn** và báo lại cho nhân viên bán hàng.
        </p>
        

        <h3 class="text-xl font-medium mt-4 text-rose-gold">Cách 2: Đo bằng chu vi ngón tay (Cũng rất chính xác)</h3>
        <p class="text-gray-700">
            Bạn lấy kéo, cắt tờ giấy bản ngang 5mm và quấn quanh ngón tay. Lấy bút đánh dấu chỗ tiếp giáp nhau, dùng kéo cắt lấy phần đánh dấu được. Sau đó dùng thước đo **chiều dài mẫu giấy**. Đem **chia cho 3,14** thì bạn đã tính ra được đường kính chiếc nhẫn của bạn và báo lại cho nhân viên bán hàng chiều dài đo được.
        </p>
        <p class="note bg-yellow-100 p-2 border-l-4 border-yellow-500 italic text-sm mt-3">
            **Chú ý quan trọng:** Bạn nên đo đi đo lại 2 đến 3 lần. Khi thời tiết lạnh, ngón tay có thể nhỏ hơn, bạn nên **cộng thêm 1 mm** vào chu vi. Khi thời tiết nóng, trừ đi 1 mm. Trường hợp xương khớp ngón tay to, bạn nên đo chu vi ở gần khớp sao cho khi đeo nhẫn dễ vào nhưng không bị tuột mất.
        </p>
        
    </div>

    <h2 class="text-2xl font-semibold mt-6 mb-3 text-secondary border-b pb-2">2. Hướng dẫn đo size vòng/lắc tay</h2>
    

    <div class="method-section mb-4">
        <h3 class="text-xl font-medium mt-3 text-rose-gold">Cách 1: Dùng thước đo (Lắc tay có sẵn)</h3>
        <p class="text-gray-700">
            Bạn dùng thước để đo chiều dài của chiếc lắc tay/vòng đã vừa với cổ tay của bạn. Sau khi có kích thước, bạn đối chiếu số cm của thước với bảng kích thước lắc.
        </p>
        <p class="note italic text-sm mt-2">
            **Lưu ý:** Size lắc phổ biến đối với lắc tay nữ là size **16, 17, 18**.
        </p>

        <h3 class="text-xl font-medium mt-4 text-rose-gold">Cách 2: Đo thủ công (Không có lắc tay mẫu)</h3>
        <p class="text-gray-700">
            Bạn sử dụng một miếng giấy nhỏ hoặc thước dây mềm, quấn **chặt vòng** theo cổ tay/chân bạn, cắt hoặc đánh dấu trên giấy/thước. Sau đó mở giấy ra đo độ dài bằng thước thẳng.
        </p>
        <p class="note italic text-sm mt-2">
            **Lưu ý:** Với cách đo này, bạn nên **cộng thêm 1-2cm** vào kích thước lắc để đảm bảo vừa tay thoải mái nhất.
        </p>
    </div>

    <h2 class="text-2xl font-semibold mt-6 mb-3 text-secondary border-b pb-2">3. Hướng dẫn cách đo size dây chuyền</h2>

    <div class="method-section mb-4">
        <h3 class="text-xl font-medium mt-3 text-rose-gold">Cách 1: Dùng thước đo (Dây chuyền có sẵn)</h3>
        <p class="text-gray-700">
            Bạn dùng thước để đo chiều dài đoạn dây chuyền/dây cổ có độ rộng và kiểu dáng tương tự chiếc bạn định mua.
        </p>
        <p class="note italic text-sm mt-2">
            **Lưu ý:** Size dây phổ biến thường là size **42** hoặc **45** (cm).
        </p>
        


        <h3 class="text-xl font-medium mt-4 text-rose-gold">Cách 2: Đo thủ công (Ước lượng)</h3>
        <p class="text-gray-700">
            Bạn sử dụng một miếng giấy nhỏ, quấn chừng quanh cổ theo vòng dây mà bạn muốn đeo, sau đó đo độ dài bằng thước kẻ. Phương pháp này chỉ là ước lượng.
        </p>
    </div>

    <p class="text-center mt-5 p-3 text-lg font-bold text-rose-gold-dark border-t-2 border-rose-gold-dark">
        JSHOP CHÚC BẠN LỰA CHỌN ĐƯỢC MÓN TRANG SỨC HOÀN HẢO NHẤT NHÉ!
    </p>

</div>

<?php 
// NHÚNG FOOTER: SỬA LỖI: SỬ DỤNG ĐƯỜNG DẪN TƯƠNG ĐỐI CHUẨN XÁC
include __DIR__ . '/../layouts/footer.php'; 
?>