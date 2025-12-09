<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhân viên mới • JSHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/admin-dashboard.css">
    
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 20px auto; /* Căn giữa */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-control:focus { border-color: #d4af37; outline: none; }
        .btn-submit {
            background: #d4af37; color: white; border: none;
            padding: 12px 30px; border-radius: 6px; cursor: pointer;
            font-weight: bold; width: 100%; transition: 0.3s;
        }
        .btn-submit:hover { background: #b5922f; }
        .btn-back { color: #666; text-decoration: none; display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand">
            <i class="fas fa-gem"></i>
            <div class="brand-text"><h2>JSHOP</h2><span>ADMINISTRATOR</span></div>
        </div>
        <ul class="menu">
            <li><a href="/Jshop/app/controllers/AdminController.php?action=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Jshop/app/controllers/AdminController.php?action=staff_list" class="active"><i class="fas fa-users-cog"></i> Quản lý Nhân viên</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Thêm nhân sự mới</h1>
            </div>
        </header>

        <div class="form-container">
            <a href="/Jshop/app/controllers/AdminController.php?action=staff_list" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>

            <form action="/Jshop/app/controllers/AdminController.php?action=store_employee" method="POST">
                
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="full_name" class="form-control" required placeholder="Ví dụ: Nguyễn Văn A">
                </div>

                <div class="form-group">
                    <label>Email đăng nhập</label>
                    <input type="email" name="email" class="form-control" required placeholder="nhanvien@jshop.com">
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu...">
                </div>

                <div class="form-group">
                    <label>Vai trò (Role)</label>
                    <select name="role" class="form-control">
                        <option value="staff">Nhân viên (Staff)</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Tạo tài khoản ngay</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>