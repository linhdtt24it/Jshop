<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>

<body>

<h2>📦 Danh sách sản phẩm</h2>

<a href="/Jshop/public/admin/addProduct">➕ Thêm sản phẩm</a>

<table border="1" cellpadding="10" style="margin-top:20px;">
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Ảnh</th>
        <th>Danh mục</th>
    </tr>

    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['product_id'] ?></td>
        <td><?= $p['name'] ?></td>
        <td><?= number_format($p['price']) ?>đ</td>

        <td>
            <?php if ($p['image']): ?>
                <img src="/Jshop/public/uploads/products/<?= $p['image'] ?>" width="80">
            <?php else: ?>
                Không có ảnh
            <?php endif; ?>
        </td>

        <td><?= $p['category_name'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
