
<?php
require_once __DIR__ . '/../core/Model.php'; 

class ContactModel extends Model {

    // Lưu tin nhắn mới từ khách hàng
    public function saveMessage($name, $email, $phone, $subject, $message) {
        $sql = "INSERT INTO contact_messages (full_name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, 'new')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $subject, $message]);
    }
    
    // Lấy danh sách tin nhắn cho Staff
    public function getAllMessages() {
        $sql = "SELECT * FROM contact_messages ORDER BY status ASC, created_at DESC";
        return $this->selectAll($sql);
    }
    
    // Lấy chi tiết tin nhắn và các phản hồi
    public function getMessageDetail($message_id) {
        $msg = $this->selectOne("SELECT * FROM contact_messages WHERE message_id = ?", [$message_id]);
        if ($msg) {
            $replies = $this->selectAll("SELECT * FROM message_replies WHERE message_id = ? ORDER BY created_at ASC", [$message_id]);
            $msg['replies'] = $replies;
        }
        return $msg;
    }
    
    // Thêm phản hồi
    public function addReply($message_id, $user_id, $sender_type, $content) {
        $sql = "INSERT INTO message_replies (message_id, user_id, sender_type, content) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$message_id, $user_id, $sender_type, $content]);
    }

    // Cập nhật trạng thái
    public function updateStatus($message_id, $status) {
        $sql = "UPDATE contact_messages SET status = ? WHERE message_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $message_id]);
    }

    // Lấy email khách hàng từ message_id
    public function getCustomerInfoByMessageId($message_id) {
        $row = $this->selectOne("SELECT email, full_name FROM contact_messages WHERE message_id = ?", [$message_id]);
        return $row;
    }
}