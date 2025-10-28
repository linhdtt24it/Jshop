<section class="giavang-section">
  <div class="site-width text-center">
    <h3 class="section-title text-uppercase">Giá vàng hôm nay</h3>

    <div class="gia-vang-box">
      <table class="gia-vang-table">
        <thead>
          <tr>
            <th>Loại vàng</th>
            <th>Mua vào (VNĐ/chỉ)</th>
            <th>Bán ra (VNĐ/chỉ)</th>
          </tr>
        </thead>
        <tbody id="goldTable">
          <tr><td>Vàng SJC</td><td>7.200.000</td><td>7.300.000</td></tr>
          <tr><td>Vàng 9999</td><td>7.150.000</td><td>7.250.000</td></tr>
          <tr><td>Vàng 24K</td><td>7.100.000</td><td>7.200.000</td></tr>
          <tr><td>Vàng 18K</td><td>5.300.000</td><td>5.450.000</td></tr>
          <tr><td>Vàng 14K</td><td>4.200.000</td><td>4.350.000</td></tr>
          <tr><td>Vàng 10K</td><td>3.500.000</td><td>3.620.000</td></tr>
        </tbody>
      </table>
      <p class="update-time" id="updateTime"></p>
    </div>
  </div>
</section>

<style>
  .giavang-section {
  margin-top: 50px;     /* cách section phía trên */
  margin-bottom: 50px;  /* cách section phía dưới */
}

.gia-vang-box {
  max-width: 800px;
  margin: 0 auto;
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.gia-vang-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 16px;
}
.gia-vang-table th, .gia-vang-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
}
.gia-vang-table th {
  background: #c2185b;
  color: #fff;
  font-weight: 700;
}
.gia-vang-table tr:hover {
  background: #fafafa;
}
.update-time {
  font-size: 14px;
  color: #777;
  margin-top: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const goldRows = document.querySelectorAll('#goldTable tr');

  goldRows.forEach(row => {
    const buyCell = row.children[1];
    const sellCell = row.children[2];

    // random ±0.3%
    const randomBuy = (parseInt(buyCell.textContent.replace(/\D/g,'')) * (1 + (Math.random() - 0.5) / 150)).toFixed(0);
    const randomSell = (parseInt(sellCell.textContent.replace(/\D/g,'')) * (1 + (Math.random() - 0.5) / 150)).toFixed(0);

    buyCell.textContent = Number(randomBuy).toLocaleString('vi-VN');
    sellCell.textContent = Number(randomSell).toLocaleString('vi-VN');
  });

  // Cập nhật thời gian
  const time = new Date();
  document.getElementById('updateTime').textContent =
    "Cập nhật lúc: " + time.toLocaleTimeString('vi-VN') + " - " +
    time.toLocaleDateString('vi-VN');
});
</script>
