<?php
$user_name = $_SESSION['user_name'] ?? 'Nhân viên';
$user = ['full_name' => $user_name, 'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)];

// KHẮC PHỤC LỖI BASE_URL TRỎ ĐẾN CONTROLLER BỊ SAI
// BASE_URL được định nghĩa trong Controller trước khi load view.
$ROOT_URL = str_replace('public/', '', BASE_URL);

// LẤY SỐ LƯỢNG TIN NHẮN MỚI TỪ BIẾN $messages ĐƯỢC TRUYỀN VÀO TỪ CONTROLLER
$new_messages_count = count(array_filter($messages ?? [], fn($m) => $m['status'] === 'new'));

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Tin nhắn #<?= $msg['message_id'] ?> • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css">
    
    <style>
        .chat-area {
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f7fafc;
        }

        /* Bubble cho Khách hàng (Trái) */
        .chat-area .bubble-customer {
            background-color: #fff;
            border: 1px solid #cbd5e0;
            color: #1a202c;
            border-radius: 18px 18px 18px 4px;
            padding: 10px 15px;
            max-width: 70%;
            margin-right: auto;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        /* Bubble cho Staff (Phải) */
        .chat-area .bubble-staff {
            background-color: #2563eb;
            color: #fff;
            border-radius: 18px 18px 4px 18px;
            padding: 10px 15px;
            max-width: 70%;
            margin-left: auto;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .chat-area .small.fw-bold {
            margin-bottom: 5px;
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .chat-area .text-time {
            font-size: 0.75rem;
            margin-top: 5px;
        }
        
        .chat-area .text-staff-time {
            color: rgba(255, 255, 255, 0.75);
        }

        /* Nút hành động trong form */
        .action-buttons a {
            margin-left: 10px;
        }
    </style>
    </head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-gem brand-icon"></i><div><h2>JSHOP</h2><span>STAFF PORTAL</span></div></div>
        <ul class="sidebar-menu">
            <li class="menu-header">CÔNG VIỆC CỦA BẠN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=dashboard"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
            <li>
                <a href="#">
                    <i class="fas fa-clipboard-list"></i> 
                    <span>Đơn hàng chờ</span> 
                    <span class="badge">5</span>
                </a>
            </li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages" class="active"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= $new_messages_count ?></span></a></li>
            <li><a href="#"><i class="fas fa-star"></i> <span>Đánh giá & KPI</span></a></li>

            <li class="menu-header">CÁ NHÂN</li>
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Lịch làm việc</span></a></li>
        </ul>
        <div class="sidebar-footer"><a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></div>
    </aside>

    <div class="main-panel">
        <header class="top-bar">
            <h4>Chi tiết Tin nhắn #<?= $msg['message_id'] ?></h4>
            <div class="user-profile"><img src="<?= $user['avatar'] ?>" alt="Staff"></div>
        </header>

        <main class="content">
            <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages" class="btn btn-sm btn-secondary mb-3">← Quay lại danh sách</a>
            
            <div class="card p-4 mb-4">
                
                <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            <i class="fas fa-user-circle me-2"></i> <?= htmlspecialchars($msg['full_name']) ?>
                        </h5>
                        <p class="small text-muted mb-1">
                            <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($msg['email']) ?> 
                            <?php if ($msg['phone']): ?>
                            <span class="mx-2">|</span> <i class="fas fa-phone me-1"></i> <?= htmlspecialchars($msg['phone']) ?>
                            <?php endif; ?>
                        </p>
                        <p class="small text-muted mb-0"><i class="fas fa-clock me-1"></i> Gửi lúc: <?= date('H:i:s d/m/Y', strtotime($msg['created_at'])) ?></p>
                    </div>
                    <div>
                        <?php 
                            $badge = ['new' => 'danger', 'in_progress' => 'primary', 'closed' => 'success'][$msg['status']];
                            $text = ['new' => 'Mới', 'in_progress' => 'Đang xử lý', 'closed' => 'Đã đóng'][$msg['status']];
                        ?>
                        <span class="badge bg-<?= $badge ?> fs-6 py-2 px-3"><?= $text ?></span>
                    </div>
                </div>

                <h6 class="text-secondary fw-bold mb-3">Nội dung Yêu cầu:</h6>
                <div class="p-3 border rounded bg-light mb-4">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                </div>

                <h6 class="text-secondary fw-bold mb-3">Lịch sử Phản hồi:</h6>
                <div class="chat-area">
                    <?php if (empty($msg['replies'])): ?>
                        <p class="text-info text-center py-3 mb-0">Chưa có phản hồi nào. Hãy là người đầu tiên trả lời!</p>
                    <?php else: ?>
                        <?php foreach ($msg['replies'] as $reply): ?>
                            <?php 
                                $is_staff = $reply['sender_type'] === 'staff';
                                $sender_name = $is_staff ? $user['full_name'] : htmlspecialchars($msg['full_name']);
                            ?>
                            <div class="d-flex mb-3 <?= $is_staff ? 'justify-content-end' : 'justify-content-start' ?>">
                                <div class="<?= $is_staff ? 'bubble-staff' : 'bubble-customer' ?>">
                                    <p class="small fw-bold mb-1"><?= $sender_name ?></p>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
                                    <small class="text-end d-block text-time <?= $is_staff ? 'text-staff-time' : 'text-muted' ?>"><?= date('H:i d/m', strtotime($reply['created_at'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if ($msg['status'] !== 'closed'): ?>
                <hr class="mt-4">
                <h5 class="mt-4 mb-3 text-success"><i class="fas fa-reply-all me-2"></i> Gửi Phản hồi và Đóng Tin nhắn</h5>
                <form action="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=reply" method="POST">
                    <input type="hidden" name="message_id" value="<?= $msg['message_id'] ?>">
                    <textarea name="reply_content" rows="4" class="form-control mb-3" placeholder="Nhập nội dung trả lời khách hàng (sẽ được gửi qua Email)..." required></textarea>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Gửi Phản hồi</button>
                        <a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=close_message&id=<?= $msg['message_id'] ?>" class="btn btn-secondary" onclick="return confirm('Bạn có chắc chắn muốn đóng tin nhắn này mà KHÔNG gửi phản hồi không? Trạng thái sẽ chuyển thành Đã đóng.');">
                            <i class="fas fa-times-circle"></i> Đóng (Không trả lời)
                        </a>
                    </div>
                </form>
                <?php else: ?>
                    <p class="text-success mt-4 fs-5"><i class="fas fa-check-circle me-2"></i> Tin nhắn này đã được đóng và xử lý xong.</p>
                <?php endif; ?>

            </div>
        </main>
    </div>
</div>