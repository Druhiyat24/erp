<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


// if (isset($_GET['from'])) {
//   $from = date('d M Y', strtotime($_GET['from']));
// } else {
//   $from = "";
// }
// if (isset($_GET['to'])) {
//   $to = date('d M Y', strtotime($_GET['to']));
// } else {
//   $to = "";
// }

$jenis_dok = $_GET['jenis_dok'];
$no_daftar = $_GET['no_daftar'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN DATA.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST DATA</h3>
  </div>
  <div>
    Jenis Dokumen : <?php echo $jenis_dok; ?>
  </div>
  <div>
    No Pabean : <?php echo $no_daftar; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No.</th>
          <th>No WS</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>No Dokumen</th>
          <th>Tanggal Dokumen</th>
          <th>Jenis Barang</th>
          <th>Jenis Dokumen</th>
          <th>No Daftar</th>
          <th>Tanggal Daftar</th>
          <th>No Aju</th>
          <th>Tanggal Aju</th>
          <th>Qty In</th>
          <th>Qty Out</th>
          <th>Sisa</th>
        </tr>
      </thead>
      <tbody>
        <?php
          # QUERY TABLE
       if($jenis_dok == 'ALL' ){
   $where_1 = "";
}else{
   $where_1 = " and type_bc = '$jenis_dok' ";     
}

if($no_daftar == 'ALL' || $no_daftar == null){
   $where_2 = "";
}else{ 
   $where_2 = " and no_daftar = '$no_daftar' "; 
}



$sql = "SELECT
kpno,
styleno,
id_jo,
id_item,
itemdesc,
no_dok,
tgl_dok,
ROUND(qty_in, 2) AS qty_in,
ROUND(qty_out, 2) AS qty_out,
ROUND(
  SUM(qty_in) OVER (
   PARTITION BY type_bc, no_daftar
   ORDER BY no_daftar,
qty_out 
   ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
   ) - 
  SUM(qty_out) OVER (
   PARTITION BY type_bc, no_daftar
   ORDER BY no_daftar,
qty_out
   ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
   ),
  2
  ) AS sisa_saldo, 
type_bc,
no_aju,
tgl_aju,
no_daftar,
tgl_daftar,
type_material
FROM (
 SELECT
 kpno,
 styleno,
 a.id_jo,
 a.id_item,
 mi.itemdesc,
 a.no_dok,
 a.tgl_dok,
 qty_good AS qty_in,
 0 AS qty_out,
 type_bc,
 no_aju,
 tgl_aju,
 no_daftar,
 tgl_daftar,
 type_material
 FROM
 whs_inmaterial_fabric_det a
 INNER JOIN (
  SELECT
  id_jo,
  kpno,
  styleno
  FROM
  act_costing ac
  INNER JOIN so ON ac.id = so.id_cost
  INNER JOIN jo_det jod ON so.id = jod.id_so
  GROUP BY id_jo
  ) b ON b.id_jo = a.id_jo
 INNER JOIN whs_inmaterial_fabric c ON c.no_dok = a.no_dok
 INNER JOIN masteritem mi ON mi.id_item = a.id_item
 WHERE
 a.STATUS = 'Y'
 AND c.STATUS != 'Cancel'
 AND type_bc != 'INHOUSE'
 AND LENGTH(no_daftar) >= 6
 UNION ALL

 SELECT
 kpno,
 styleno,
 a.id_jo,
 a.id_item,
 mi.itemdesc,
 a.no_bppb AS no_dok,
 c.tgl_bppb AS tgl_dok,
 0 AS qty_in,
 SUM(qty_out) AS qty_out,
 bc_in AS type_bc,
 no_aju_in AS no_aju,
 tgl_aju_in AS tgl_aju,
 no_daftar_in AS no_daftar,
 tgl_daftar_in AS tgl_daftar,
 'Fabric' AS type_material
 FROM
 (select * from whs_bppb_det GROUP BY id_roll) a
 INNER JOIN (
  SELECT
  id_jo,
  kpno,
  styleno
  FROM
  act_costing ac
  INNER JOIN so ON ac.id = so.id_cost
  INNER JOIN jo_det jod ON so.id = jod.id_so
  GROUP BY id_jo
  ) b ON b.id_jo = a.id_jo
 INNER JOIN whs_bppb_h c ON c.no_bppb = a.no_bppb
 INNER JOIN masteritem mi ON mi.id_item = a.id_item
 WHERE
 a.bc_in IS NOT NULL
 AND a.STATUS = 'Y'
 AND c.STATUS != 'Cancel'
 AND LENGTH(no_daftar_in) >= 6
 AND bc_in != 'INHOUSE'
 GROUP BY
 a.no_bppb,
 a.id_jo,
 a.id_item,
 bc_in,
 no_aju_in,
 no_daftar_in
) a
where no_daftar != ''
$where_1
$where_2
ORDER BY
no_daftar,
tgl_dok,
qty_out asc";

        $query = mysql_query($sql);
        $no = 1;
        $no_urt = 0;
        $no_urt_fix = 0;
        while ($data = mysql_fetch_array($query)) {
         $tgl_dok = date('d M Y', strtotime($data[tgl_dok]));
            $tgl_aju = date('d M Y', strtotime($data[tgl_aju]));
            $tgl_daftar = date('d M Y', strtotime($data[tgl_daftar]));

            $cri_next = $data['no_daftar'];
            if ($cri_next != $cri_prev) {
              $no_urt++;
              $no_urt_fix++;
            }
            echo "<tr>";
            if ($no_urt != '' && $no_urt_fix != '') {
              echo "<td> $no_urt</td>";
            } else {
              echo "<td></td>";
            }
            
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[id_item]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[no_dok]</td>";
            echo "<td>$tgl_dok</td>";
            echo "<td>$data[type_material]</td>";
            echo "<td>$data[type_bc]</td>";
            echo "<td>$data[no_daftar]</td>";
            echo "<td>$tgl_daftar</td>";
            echo "<td>$data[no_aju]</td>";
            echo "<td>$tgl_aju</td>";
            echo "<td>$data[qty_in]</td>";
            echo "<td>$data[qty_out]</td>";
            echo "<td>$data[sisa_saldo]</td>";
            echo "</tr>";
            $no++;
            $cri_prev = $data['no_daftar'];
            if ($cri_prev == $cri_next) {
              $no_urt_fix = '';
            }
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>