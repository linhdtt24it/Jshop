<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân viên • JSHOP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Jshop/public/assets/css/admin-dashboard.css">
    
    <style>
       
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #666; font-weight: 700; }
        td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .role-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .role-admin { background: #000; color: #d4af37; }
        .role-staff { background: #e0f2fe; color: #0284c7; }
        .btn-navy { background-color: #0f172a; color: white !important; padding: 10px 20px; border-radius: 5px; font-weight: bold; border: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-navy:hover { background-color: #1e293b; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.4); }

        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 5px; color: white; margin: 0 3px; border: none; cursor: pointer; text-decoration: none; }
        .btn-edit { background: #3b82f6; } 
        .btn-delete { background: #ef4444; } 
        .disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; pointer-events: none; }
        .loading-overlay { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.7); z-index: 9999; justify-content: center; align-items: center; }
    </style>
</head>
<body>

<div class="loading-overlay" id="loadingOverlay"><div class="spinner-border text-primary" role="status"></div></div>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand">
            <i class="fas fa-gem"></i>
            <div class="brand-text"><h2>JSHOP</h2><span>ADMINISTRATOR</span></div>
        </div>
        
        <ul class="menu">
            <li class="label">QUẢN TRỊ</li>
            <li>
                <a href="/Jshop/app/controllers/AdminController.php?action=dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="label">NHÂN SỰ</li>
            <li>
                <a href="#" class="active"> <i class="fas fa-users-cog"></i> Quản lý Nhân viên
                </a>
            </li>
        </ul>
        
        <div class="logout">
             <a href="/Jshop/app/controllers/AuthController.php?action=logout" onclick="return confirm('Sếp muốn đăng xuất hả?');">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="page-title">
                <h1>Danh sách nhân sự</h1>
                <p>Quản lý tài khoản Admin và Staff</p>
            </div>
        </header>

        <div class="content-grid" style="grid-template-columns: 1fr;">
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Tổng: <?= count($staffList) ?> nhân viên</h3>
                    <button class="btn-navy" onclick="openAddModal()"><i class="fas fa-plus"></i> Thêm nhân viên mới</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th style="text-align: center;">Hành động</th> 
                        </tr>
                    </thead>
                    <tbody id="staffTableBody">
                        <?php foreach ($staffList as $staff): ?>
                        <tr id="row-<?= $staff['user_id'] ?>">
                            <td>#<?= $staff['user_id'] ?></td>
                            <td><strong><?= htmlspecialchars($staff['full_name']) ?></strong></td>
                            <td><?= htmlspecialchars($staff['email']) ?></td>
                            <td><?= htmlspecialchars($staff['phone_number'] ?? '---') ?></td>
                            <td>
                                <?php if($staff['role'] == 'admin'): ?> <span class="role-badge role-admin">ADMIN</span>
                                <?php else: ?> <span class="role-badge role-staff">STAFF</span> <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($staff['created_at'])) ?></td>
                            <td style="text-align: center;">
                                <button class="btn-action btn-edit" onclick="editStaff(<?= $staff['user_id'] ?>)"><i class="fas fa-edit"></i></button>
                                <?php if ($staff['user_id'] != $_SESSION['user_id']): ?>
                                    <button class="btn-action btn-delete" onclick="deleteStaff(<?= $staff['user_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
                                <?php else: ?>
                                    <button class="btn-action disabled"><i class="fas fa-trash-alt"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Thêm nhân viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="staffForm">
                    <input type="hidden" name="user_id" id="staffId">
                    <div class="mb-3"><label class="form-label fw-bold">Họ tên</label><input type="text" class="form-control" name="full_name" id="fullName" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Email</label><input type="email" class="form-control" name="email" id="email" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Số điện thoại</label><input type="text" class="form-control" name="phone" id="phone" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Mật khẩu <small class="text-muted fw-normal" id="passHint"></small></label><input type="password" class="form-control" name="password" id="password"></div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Vai trò</label>
                        <select class="form-select" name="role" id="role">
                            <option value="staff">Nhân viên (Staff)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-navy w-100 justify-content-center"><i class="fas fa-save me-2"></i> Lưu thông tin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/Jshop/public/assets/js/admin-staff.js"></script> 
</body>
</html>