<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="/Jshop/public/assets/css/admin.css">
</head>

<body>
<h2>➕ Thêm sản phẩm mới</h2>

<form action="/Jshop/public/admin/storeProduct" method="POST" enctype="multipart/form-data">

    <label>Tên sản phẩm</label>
    <input type="text" name="name" required>

    <label>Giá</label>
    <input type="number" name="price" required>

    <label>Danh mục</label>
    <select name="category" required>
        <option value="1">01</option>
        <option value="2">02</option>
        <option value="3">file addProduct nha</option>
    </select>

    <label>Hình ảnh</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Lưu sản phẩm</button>
</form>

</body>
</html>
