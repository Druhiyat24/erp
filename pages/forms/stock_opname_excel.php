<?php
// Ekspor Excel stock opname: dokumen HTML bersih berformat Excel (tanpa layout index.php).
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }

include __DIR__ . '/../../include/conn.php';

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';

$rsCo = mysqli_query($conn_li, "select company from mastercompany limit 1");
$rowCo = $rsCo ? mysqli_fetch_assoc($rsCo) : array();
$nm_company = isset($rowCo['company']) ? $rowCo['company'] : '';
$isSandrafine = ($nm_company == "PT. Sandrafine Garment");

if ($mode == "FG") {
  $judul = "Lap. Stock FG";
  $qry = "select styleno,kpno,goods_code,color,size,stock,a.id_item
    from stock a inner join masterstyle s on a.mattype='FG' and a.id_item=s.id_item
    order by a.id_item";
} else {
  if ($mode == "General") { $crit = "s.mattype='N'"; $judul = "Lap. Stock General"; }
  else if ($mode == "Bahan_Baku") { $crit = "s.mattype in ('A','F')"; $judul = "Lap. Stock Bahan Baku"; }
  else { $crit = "s.mattype not in ('N','A','F')"; $judul = "Lap. Stock Lainnya"; }
  $qry = "select s.matclass,a.id_item,if(s.goods_code='-' or s.goods_code='',concat(s.mattype,' ',s.id_item),s.goods_code) goods_code,
    s.itemdesc,s.color,s.size,odo.goods_code kode_odo,odo.itemdesc item_odo,round(a.stock,2) stock
    from stock a inner join masteritem s on a.mattype=s.mattype and a.id_item=s.id_item
    left join masteritem_odo odo on s.id_item_odo=odo.id_item_odo
    where $crit order by a.id_item";
}

$query = mysqli_query($conn_li, $qry);
if (!$query) { die("Query gagal: " . htmlspecialchars(mysqli_error($conn_li))); }

$rows = array();
$total = 0;
while ($d = mysqli_fetch_assoc($query)) { $rows[] = $d; $total += (float)$d['stock']; }

$fileName = "stock_opname_" . preg_replace('/[^A-Za-z0-9_]/', '_', $mode) . "_" . date('Ymd_His') . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$fileName");
header("Pragma: no-cache");
header("Expires: 0");

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// jumlah kolom untuk judul & total
$colCount = ($mode == "FG") ? 9 : (($isSandrafine ? 11 : 10));
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<!--[if gte mso 9]><xml>
<x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>
<x:Name>Stock Opname</x:Name>
<x:WorksheetOptions><x:FreezePanes/><x:FrozenNoSplit/><x:SplitHorizontal>5</x:SplitHorizontal><x:TopRowBottomPane>5</x:TopRowBottomPane><x:DoNotDisplayGridlines/></x:WorksheetOptions>
</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook>
</xml><![endif]-->
<style>
  table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 10pt; }
  .title { font-size: 14pt; font-weight: bold; border: none; }
  .sub   { font-size: 10pt; color: #555555; border: none; }
  th { background: #1f6b3a; color: #ffffff; font-weight: bold; text-align: center;
       border: 0.5pt solid #000000; padding: 4px; mso-pattern: #1f6b3a none; }
  td { border: 0.5pt solid #000000; padding: 3px; vertical-align: middle; }
  td.num { text-align: right; mso-number-format: "\#\,\#\#0\.00"; }
  td.center { text-align: center; }
  tr.total th { background: #e2efda; color: #000000; text-align: right; }
  tr.total td { font-weight: bold; background: #e2efda; }
</style>
</head>
<body>
<table>
  <col style="width:40px">
  <?php if ($mode == "FG") { ?>
  <col style="width:120px"><col style="width:120px"><col style="width:90px"><col style="width:90px">
  <col style="width:150px"><col style="width:120px"><col style="width:80px"><col style="width:110px">
  <?php } else { ?>
  <col style="width:90px"><col style="width:90px"><col style="width:150px"><col style="width:200px">
  <col style="width:120px"><col style="width:80px">
  <?php if ($isSandrafine) { ?><col style="width:90px"><?php } ?>
  <col style="width:120px"><col style="width:200px"><col style="width:110px">
  <?php } ?>

  <tr><td class="title" colspan="<?php echo $colCount; ?>"><?php echo e($nm_company); ?></td></tr>
  <tr><td class="title" colspan="<?php echo $colCount; ?>"><?php echo e($judul); ?></td></tr>
  <tr><td class="sub" colspan="<?php echo $colCount; ?>">Dicetak: <?php echo date('d-m-Y H:i'); ?></td></tr>
  <tr><td colspan="<?php echo $colCount; ?>" style="border:none">&nbsp;</td></tr>

  <tr>
    <th>No</th>
    <?php if ($mode == "FG") { ?>
    <th>Style / Model</th>
    <th>No. Order</th>
    <th>Kategori</th>
    <th>ID Item</th>
    <th>Kode Barang</th>
    <th>Warna</th>
    <th>Ukuran</th>
    <th>Stock</th>
    <?php } else { ?>
    <th>Kategori</th>
    <th>ID Item</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Warna</th>
    <th>Ukuran</th>
    <?php if ($isSandrafine) { ?><th>Stock Card</th><?php } ?>
    <th>Kode Odo</th>
    <th>Item Odo</th>
    <th>Stock</th>
    <?php } ?>
  </tr>

  <?php $no = 1; foreach ($rows as $d) { ?>
  <tr>
    <td class="center"><?php echo $no++; ?></td>
    <?php if ($mode == "FG") { ?>
    <td><?php echo e($d['styleno']); ?></td>
    <td><?php echo e($d['kpno']); ?></td>
    <td class="center">FG</td>
    <td class="center"><?php echo e($d['id_item']); ?></td>
    <td><?php echo e($d['goods_code']); ?></td>
    <td><?php echo e($d['color']); ?></td>
    <td class="center"><?php echo e($d['size']); ?></td>
    <td class="num"><?php echo e($d['stock']); ?></td>
    <?php } else { ?>
    <td><?php echo e($d['matclass']); ?></td>
    <td class="center"><?php echo e($d['id_item']); ?></td>
    <td><?php echo e($d['goods_code']); ?></td>
    <td><?php echo e($d['itemdesc']); ?></td>
    <td><?php echo e($d['color']); ?></td>
    <td class="center"><?php echo e($d['size']); ?></td>
    <?php if ($isSandrafine) { ?><td></td><?php } ?>
    <td><?php echo e($d['kode_odo']); ?></td>
    <td><?php echo e($d['item_odo']); ?></td>
    <td class="num"><?php echo e($d['stock']); ?></td>
    <?php } ?>
  </tr>
  <?php } ?>

  <tr class="total">
    <th colspan="<?php echo $colCount - 1; ?>">Total:</th>
    <td class="num"><?php echo number_format($total, 2, '.', ''); ?></td>
  </tr>
</table>
</body>
</html>
