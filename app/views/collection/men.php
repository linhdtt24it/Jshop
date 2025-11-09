<?php include "app/views/layouts/header.php"; ?>
<link rel="stylesheet" href="/public/assets/css/collection.css">

<div class="product-page">
    <aside class="filter-box">
    <h3>Bộ lọc</h3>

    <form method="GET" action="">

        <div class="filter-group">
            <label>Chất liệu</label>
            <select name="material" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <?php foreach($data['materials'] as $m): ?>
                    <option value="<?= $m['material_id']; ?>" 
                        <?= ($_GET['material'] ?? '') == $m['material_id'] ? 'selected' : '' ?>>
                        <?= $m['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Mục đích</label>
            <select name="purpose" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <?php foreach($data['purposes'] as $p): ?>
                    <option value="<?= $p['purpose_id']; ?>"
                        <?= ($_GET['purpose'] ?? '') == $p['purpose_id'] ? 'selected' : '' ?>>
                        <?= $p['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </form>
    </aside>


    <section class="product-list">
        <?php foreach($data['products'] as $item): ?>
        <div class="product-card">
            <img src="/public/images/<?= $item['image'] ?? 'no-image.jpg' ?>" alt="">
            <h4><?= $item['name'] ?></h4>
            <p class="price"><?= number_format($item['price']) ?>₫</p>
            <p class="desc"><?= $item['description'] ?></p>
        </div>
        <?php endforeach; ?>
    </section>
</div>

<?php include "app/views/layouts/footer.php"; ?>
