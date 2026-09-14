<?php
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }
include '../../include/conn.php';
include '../forms/fungsi.php';

$from = (isset($_GET['frexc']) && trim($_GET['frexc']) != '') ? date('Y-m-d',strtotime($_GET['frexc'])) : '';
$to = (isset($_GET['toexc']) && trim($_GET['toexc']) != '') ? date('Y-m-d',strtotime($_GET['toexc'])) : '';
$txtsupplier = isset($_GET['txtsupplier']) ? $_GET['txtsupplier'] : '';
$txtbuyer = isset($_GET['txtbuyer']) ? $_GET['txtbuyer'] : '';

$supplier_filter = ($txtsupplier != '') ? "AND a.id_supplier='".$txtsupplier."'" : "";
$buyer_filter = ($txtbuyer != '') ? "AND a.id_buyer='".$txtbuyer."'" : "";
$date_filter = "";
if ($from != '' && $to != '') {
  $date_filter = " AND a.podate BETWEEN '".$from."' AND '".$to."'";
} elseif ($from != '') {
  $date_filter = " AND a.podate >= '".$from."'";
} elseif ($to != '') {
  $date_filter = " AND a.podate <= '".$to."'";
}

$rsComp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rsComp["company"];

$sqldatatable = "select IF(a.app = 'A', 'Approved', 'Waiting') AS status_po,a.id,pono,podate,supplier,
nama_pterms,n_kurs,tmppoit.buyer,tmppoit.t_row,tmppoit.t_rows_cancel,tmppoit.itemdesc,tmppoit.qty,tmppoit.unit,tmppoit.price,tmppoit.curr
from po_header a inner join 
mastersupplier s on a.id_supplier=s.id_supplier inner join 
masterpterms d on a.id_terms=d.id
inner join 
(select poit.id_jo,poit.id_po,group_concat(distinct reqno) buyer,
count(*) t_row,sum(if(cancel='Y',1,0)) t_rows_cancel, j.itemdesc, poit.qty, poit.unit, poit.price, poit.curr from po_item poit 
inner join reqnon_header rnh on poit.id_jo=rnh.id	
INNER JOIN masteritem j ON poit.id_gen = j.id_item	group by poit.id_po) 
tmppoit on tmppoit.id_po=a.id 
where a.jenis='N' $date_filter $supplier_filter $buyer_filter
order by podate desc";

$query = mysql_query($sqldatatable);

header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=Laporan_PO_General_".date('Ymd_His').".xls");
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]>
<xml>
<x:ExcelWorkbook>
<x:ExcelWorksheets>
<x:ExcelWorksheet>
<x:Name>Laporan PO General</x:Name>
<x:WorksheetOptions>
<x:DisplayGridlines/>
</x:WorksheetOptions>
</x:ExcelWorksheet>
</x:ExcelWorksheets>
</x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  body { font-family: Calibri, Arial, sans-serif; }
  .header-title { font-size: 16pt; font-weight: bold; color: #1a237e; }
  .header-sub { font-size: 10pt; color: #555; margin-top: 4px; }
  .header-period { font-size: 11pt; color: #333; margin-top: 8px; font-weight: bold; }
  table.data-table { border-collapse: collapse; width: 100%; margin-top: 15px; }
  table.data-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-color: #667eea;
    color: #ffffff; font-weight: bold; font-size: 10pt;
    padding: 8px 10px; border: 1px solid #4a5abc;
    text-align: center; text-transform: uppercase;
  }
  table.data-table td {
    font-size: 10pt; padding: 6px 10px;
    border: 1px solid #d0d0d0; color: #333;
  }
  table.data-table tr:nth-child(even) td { background-color: #f4f6ff; }
  table.data-table tr:hover td { background-color: #e8ecff; }
  .status-approved { color: #27ae60; font-weight: bold; }
  .status-waiting { color: #e67e22; font-weight: bold; }
  .footer-info { margin-top: 15px; font-size: 9pt; color: #888; }
  .summary-box { margin-top: 10px; padding: 8px 12px; background: #f0f4ff; border-left: 4px solid #667eea; font-size: 10pt; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
<tr>
  <td>
    <div class="header-title"><?php echo $nm_company; ?></div>
    <div class="header-sub">Laporan Purchase Order General</div>
    <div class="header-period">Periode: <?php echo fd_view($from); ?> s/d <?php echo fd_view($to); ?></div>
  </td>
  <td align="right" valign="top">
    <div style="font-size:9pt;color:#999;">Dicetak: <?php echo date('d M Y H:i'); ?></div>
  </td>
</tr>
</table>

<div class="summary-box">
  Total Data: <b><?php echo mysql_num_rows($query); ?></b> record(s)
</div>

<table class="data-table">
<thead>
<tr>
  <th>No</th>
  <th>Supplier</th>
  <th>PO No</th>
  <th>PO Date</th>
  <th>Request No</th>
  <th>Description</th>
  <th>Qty PO</th>
  <th>Unit</th>
  <th>Price</th>
  <th>Curr</th>
  <th>Status</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
while($data = mysql_fetch_array($query)){
  $status_class = ($data['status_po'] == 'Approved') ? 'status-approved' : 'status-waiting';
  echo "<tr>";
  echo "<td align='center'>".$no."</td>";
  echo "<td>".$data['supplier']."</td>";
  echo "<td align='center'>".$data['pono']."</td>";
  echo "<td align='center'>".fd_view($data['podate'])."</td>";
  echo "<td>".$data['buyer']."</td>";
  echo "<td>".$data['itemdesc']."</td>";
  echo "<td align='right'>".number_format($data['qty'],2)."</td>";
  echo "<td align='center'>".$data['unit']."</td>";
  echo "<td align='right'>".number_format($data['price'],2)."</td>";
  echo "<td align='center'>".$data['curr']."</td>";
  echo "<td align='center' class='".$status_class."'>".$data['status_po']."</td>";
  echo "</tr>";
  $no++;
}
?>
</tbody>
</table>

<div class="footer-info">
  Laporan ini digenerate otomatis oleh sistem ERP &copy; <?php echo date('Y'); ?> <?php echo $nm_company; ?>
</div>
</body>
</html>
