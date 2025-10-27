<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giá vàng hôm nay - JSHOP</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include __DIR__.'/../header.php'; ?>

<div class="gold-page" style="padding:20px;">
    <?php
    $giaVang = [
        ['Loại', 'Mua vào', 'Bán ra'],
        ['SJC 1 lượng', '68.000.000 VNĐ', '68.500.000 VNĐ'],
        ['SJC 2 lượng', '136.000.000 VNĐ', '137.000.000 VNĐ'],
        ['Vàng nhẫn 24K', '65.000.000 VNĐ', '65.500.000 VNĐ'],
        ['Vàng nhẫn 18K', '48.000.000 VNĐ', '48.500.000 VNĐ'],
    ];
    $thoiGianCapNhat = date('H:i:s d/m/Y');
    ?>

    <h2>Giá vàng hôm nay</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <?php foreach($giaVang as $i => $row): ?>
            <tr>
                <?php foreach($row as $cell): ?>
                    <?php if($i === 0): ?>
                        <th><?php echo $cell; ?></th>
                    <?php else: ?>
                        <td><?php echo $cell; ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>Cập nhật: <?php echo $thoiGianCapNhat; ?></p>
</div>

<?php include __DIR__.'/../footer.php'; ?>
</body>
</html>
