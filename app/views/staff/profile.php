<?php

$user_name = $user_data['full_name'] ?? $_SESSION['user_name'] ?? 'Nhân viên';
$user_id = $user_data['user_id'] ?? $_SESSION['user_id'] ?? 0;

$user_info = [
    'email' => $user_data['email'] ?? $_SESSION['email'] ?? 'Chưa cập nhật',
    'phone' => $user_data['phone_number'] ?? $_SESSION['phone_number'] ?? 'Chưa cập nhật', 
    'age' => $user_data['age'] ?? 'Chưa cập nhật',
    'hometown' => $user_data['hometown'] ?? 'Chưa cập nhật',
    'health_status' => $user_data['health_status'] ?? 'Chưa cập nhật',
];

$user = ['full_name' => $user_name, 'avatar' => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)];


$ROOT_URL = str_replace('public/', '', BASE_URL);
$new_messages_count = count(array_filter($messages ?? [], fn($m) => $m['status'] === 'new'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ Cá nhân Staff • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
   <link rel="stylesheet" href="/Jshop/public/assets/css/staff-dashboard.css">
   
   <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        .close-btn:hover {
            color: #333;
        }
        .modal-content h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .modal-content label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .modal-content input[type="text"],
        .modal-content input[type="email"],
        .modal-content input[type="number"],
        .modal-content input[type="password"],
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .profile-card-header {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            border: 3px solid #3b82f6;
        }
        .profile-info {
            flex-grow: 1;
        }
        .profile-info h1 {
            margin-bottom: 5px;
            font-size: 1.75rem;
            color: #1a202c;
        }
        .profile-info p {
            font-size: 1rem;
            color: #4a5568;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #edf2f7;
        }
        .detail-label {
            font-weight: 600;
            color: #2d3748;
        }
        .detail-value {
            color: #4a5568;
        }
        .flash-message {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .flash-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .flash-error {
            background-color: #f8d7da;
            color: #842029;
        }
   </style>
</head>
<body>

<div class="wrapper">
    
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-gem brand-icon"></i>
            <div>
                <h2>JSHOP</h2>
                <span>STAFF PORTAL</span>
            </div>
        </div>

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
            <li><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=messages"><i class="fas fa-comments"></i> <span>Tin nhắn khách</span> <span class="badge"><?= $new_messages_count ?></span></a></li>
            <li><a href="#"><i class="fas fa-star"></i> <span>Đánh giá & KPI</span></a></li>
            
            <li class="menu-header">CÁ NHÂN</li>
            <li class="active"><a href="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=profile" class="active"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> <span>Lịch làm việc</span></a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?= $ROOT_URL ?>app/controllers/AuthController.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </aside>

    <div class="main-panel">
        
        <header class="top-bar">
            <h4>Hồ sơ Cá nhân</h4>
            <div class="user-profile">
                <img src="<?= $user['avatar'] ?>" alt="Staff">
            </div>
        </header>

        <main class="content">
            <div class="card p-4">
                
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="flash-message flash-success">
                        <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="flash-message flash-error">
                        <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                <div class="profile-card-header">
                    <img src="<?= $user['avatar'] ?>" alt="<?= $user['full_name'] ?>" class="profile-avatar">
                    <div class="profile-info">
                        <h1><?= $user['full_name'] ?></h1> 
                    </div>
                    <div>
                         <button id="editProfileBtn" class="btn btn-sm btn-primary"><i class="fas fa-edit me-2"></i>Chỉnh sửa</button>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-secondary">Thông tin cá nhân</h5>
                <div class="info-details">
                    
                    <div class="detail-row">
                        <span class="detail-label">Tên đầy đủ:</span>
                        <span class="detail-value" id="profileFullName"><?= $user['full_name'] ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Tuổi:</span>
                        <span class="detail-value" id="profileAge"><?= $user_info['age'] ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Quê quán:</span>
                        <span class="detail-value" id="profileHometown"><?= $user_info['hometown'] ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Tình trạng sức khỏe:</span>
                        <span class="detail-value" id="profileHealth"><?= $user_info['health_status'] ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value" id="profileEmail"><?= $user_info['email'] ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Số điện thoại:</span>
                        <span class="detail-value" id="profilePhone"><?= $user_info['phone'] ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Mật khẩu:</span>
                        <span class="detail-value">*********** <a href="javascript:void(0)" id="changePasswordLink" class="text-primary ms-3">Đổi mật khẩu</a>
                        </span>
                    </div>
                    
                </div>
            </div>
        </main>
    </div>
</div>

<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Chỉnh sửa Thông tin Cá nhân</h2>
        
        <form action="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=update_profile" method="POST">
            
            <input type="hidden" name="user_id" value="<?= $user_id ?>">

            <label for="fullName">Tên đầy đủ:</label>
            <input type="text" id="fullName" name="full_name" required value="<?= $user['full_name'] ?>">

            <label for="age">Tuổi:</label>
            <input type="number" id="age" name="age" required value="<?= $user_info['age'] !== 'Chưa cập nhật' ? $user_info['age'] : '' ?>">

            <label for="hometown">Quê quán:</label>
            <input type="text" id="hometown" name="hometown" value="<?= $user_info['hometown'] !== 'Chưa cập nhật' ? $user_info['hometown'] : '' ?>">

            <label for="healthStatus">Tình trạng sức khỏe:</label>
            <input type="text" id="healthStatus" name="health_status" value="<?= $user_info['health_status'] !== 'Chưa cập nhật' ? $user_info['health_status'] : '' ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?= $user_info['email'] ?>">

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone_number" value="<?= $user_info['phone'] ?>">

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Lưu Thay đổi</button>
        </form>
    </div>
</div>

<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Đổi Mật khẩu</h2>
        
        <form action="<?= $ROOT_URL ?>app/controllers/StaffController.php?action=change_password" method="POST" id="changePasswordForm">
            
            <input type="hidden" name="user_id" value="<?= $user_id ?>">

            <label for="oldPassword">Mật khẩu cũ:</label>
            <input type="password" id="oldPassword" name="old_password" required>

            <label for="newPassword">Mật khẩu mới:</label>
            <input type="password" id="newPassword" name="new_password" required>

            <label for="confirmPassword">Xác nhận Mật khẩu mới:</label>
            <input type="password" id="confirmPassword" name="confirm_password" required>
            
            <div id="passwordMismatchError" style="color: red; margin-bottom: 15px; display: none;">Mật khẩu mới không khớp!</div>

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Đổi Mật khẩu</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editProfileModal');
        const editBtn = document.getElementById('editProfileBtn');
        const closeEditSpan = editModal.querySelector('.close-btn');

        const passModal = document.getElementById('changePasswordModal');
        const passLink = document.getElementById('changePasswordLink');
        const closePassSpan = passModal.querySelector('.close-btn');
        const passForm = document.getElementById('changePasswordForm');
        const newPass = document.getElementById('newPassword');
        const confirmPass = document.getElementById('confirmPassword');
        const errorDiv = document.getElementById('passwordMismatchError');

        function fillEditForm() {
            const ageText = document.getElementById('profileAge').textContent.trim();
            const hometownText = document.getElementById('profileHometown').textContent.trim();
            const healthText = document.getElementById('profileHealth').textContent.trim();
            
            document.getElementById('fullName').value = document.getElementById('profileFullName').textContent.trim();
            document.getElementById('age').value = ageText !== 'Chưa cập nhật' ? ageText : '';
            document.getElementById('hometown').value = hometownText !== 'Chưa cập nhật' ? hometownText : '';
            document.getElementById('healthStatus').value = healthText !== 'Chưa cập nhật' ? healthText : '';
            document.getElementById('email').value = document.getElementById('profileEmail').textContent.trim();
            document.getElementById('phone').value = document.getElementById('profilePhone').textContent.trim();
        }

        editBtn.onclick = function() {
            fillEditForm();
            editModal.style.display = 'flex';
        }
        closeEditSpan.onclick = function() {
            editModal.style.display = 'none';
        }
        
        passLink.onclick = function() {
            passForm.reset();
            errorDiv.style.display = 'none';
            passModal.style.display = 'flex';
        }
        closePassSpan.onclick = function() {
            passModal.style.display = 'none';
        }
        
        passForm.onsubmit = function(event) {
            if (newPass.value !== confirmPass.value) {
                event.preventDefault();
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target == editModal || event.target == passModal) {
                editModal.style.display = 'none';
                passModal.style.display = 'none';
            }
        }
    });
</script>

</body>
</html>