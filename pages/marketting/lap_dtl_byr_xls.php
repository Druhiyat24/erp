<?php
include("koneksi.php");

echo "<table border='1'>";
echo "<tr>
<th>No</th>
<th>Buyer</th>
<th>No WS</th>
<th>Style</th>
<th>Jenis SO</th>
<th>Destination</th>
</tr>";

$sql = "SELECT 
          ms.Supplier AS buyer,
          ac.kpno,
          ac.styleno,
          so.jns_so,
          ac.main_dest
        FROM act_costing ac
        INNER JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
        INNER JOIN so ON ac.id = so.id_cost
        WHERE ac.aktif = 'Y' AND so.cancel_h = 'N'
        ORDER BY buyer ASC";

$query = mysql_query($sql);
$no = 1;
while ($r = mysql_fetch_assoc($query)) {
  echo "<tr>";
  echo "<td>" . $no . "</td>";
  echo "<td>" . $r['buyer'] . "</td>";
  echo "<td>" . $r['kpno'] . "</td>";
  echo "<td>" . $r['styleno'] . "</td>";
  echo "<td>" . $r['jns_so'] . "</td>";
  echo "<td>" . $r['main_dest'] . "</td>";
  echo "</tr>";
  $no++;
}
echo "</table>";
?>
