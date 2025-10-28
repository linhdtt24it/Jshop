<?php
class Database {
    private $host = "localhost";   // máy chủ MySQL
    private $db_name = "jshop";    // tên CSDL bạn vừa import
    private $username = "root";    // tài khoản MySQL mặc định của XAMPP
    private $password = "";        // mật khẩu trống (nếu bạn không đặt)
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );

            // Bật chế độ thông báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "❌ Kết nối CSDL thất bại: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
