<h2>Danh sách sản phẩm</h2>
<table border="1" cellpadding="8">
  <tr>
    <th>ID</th>
    <th>Tên sản phẩm</th>
    <th>Danh mục</th>
    <th>Giá</th>
  </tr>

  <?php foreach ($products as $p): ?>
  <tr>
    <td><?= $p['product_id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= htmlspecialchars($p['category_name'] ?? 'Không có') ?></td>
    <td><?= number_format($p['price'], 0, ',', '.') ?> ₫</td>
  </tr>
  <?php endforeach; ?>
</table>
