<?php
  session_start();
  include '../../include/conn.php';
  if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }

  $mode = isset($_GET['mode']) ? $_GET['mode'] : '';

  if ($mode=="Mesin") { $filternya="a.mattype in ('M')"; $judul="Data Mesin"; }
  else if ($mode=="Sparepart") { $filternya="a.mattype in ('P')"; $judul="Data Sparepart"; }
  else if ($mode=="Alat") { $filternya="a.mattype in ('T')"; $judul="Data Alat"; }
  else { $filternya="1=1"; $judul="Data Item"; }

  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=".strtolower(str_replace(' ','_',$judul)).".xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  $query = mysql_query("SELECT a.*,s.goods_code kode_lama,s.itemdesc desc_lama FROM masteritem
    a left join masteritem_odo s on a.id_item_odo=s.id_item_odo
    where $filternya ORDER BY a.id_item DESC");

  echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
  echo "<head><style>";
  echo "table { border-collapse: collapse; width: 100%; font-family: Calibri, sans-serif; font-size: 11pt; }";
  echo "th { background-color: #4472C4; color: #FFFFFF; font-weight: bold; padding: 8px 5px; border: 1px solid #2F5496; text-align: center; }";
  echo "td { padding: 5px; border: 1px solid #B4C6E7; }";
  echo "tr:nth-child(even) { background-color: #D9E2F3; }";
  echo "tr:nth-child(odd) { background-color: #FFFFFF; }";
  echo ".title { font-size: 16pt; font-weight: bold; color: #2F5496; margin-bottom: 10px; }";
  echo ".date { font-size: 10pt; color: #808080; margin-bottom: 15px; }";
  echo "</style></head><body>";

  echo "<div class='title'>$judul</div>";
  echo "<div class='date'>Tanggal Export: ".date('d F Y H:i:s')."</div>";

  echo "<table>";
  echo "<tr>";
  echo "<th>No</th>";
  echo "<th>Kode</th>";
  echo "<th>Klasifikasi</th>";
  echo "<th>Nama</th>";
  echo "<th>SN</th>";
  echo "<th>Brand</th>";
  echo "<th>Thn. Beli</th>";
  echo "<th>Kode Lama</th>";
  echo "<th>Desc Lama</th>";
  echo "</tr>";

  $no = 1;
  while($data = mysql_fetch_array($query))
  {
    echo "<tr>";
    echo "<td align='center'>$no</td>";
    echo "<td>$data[goods_code]</td>";
    echo "<td align='center'>$data[matclass]</td>";
    echo "<td>$data[itemdesc]</td>";
    echo "<td>$data[sn]</td>";
    echo "<td>$data[brand]</td>";
    echo "<td align='center'>$data[thn_beli]</td>";
    echo "<td>$data[kode_lama]</td>";
    echo "<td>$data[desc_lama]</td>";
    echo "</tr>";
    $no++;
  }
  echo "</table>";
  echo "</body></html>";
?>
