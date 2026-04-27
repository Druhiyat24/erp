<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=general_request.xls");

include "../../include/conn.php";

$status = $_GET['status'];
// USER FILTERING
session_start();
$user = $_SESSION['username']; // pastikan user login

function flookup($field, $table, $where) {
    $sql = "SELECT $field FROM $table WHERE $where LIMIT 1";
    $q = mysql_query($sql);
    if ($q && mysql_num_rows($q) > 0) {
        $d = mysql_fetch_array($q);
        return $d[$field];
    }
    return "";
}

$all_dept = flookup("username", "userpassword", "username='$user' AND all_dept='1'");

if ($all_dept == "") {
    $user_group = flookup("kode_mkt", "userpassword", "username='$user'");
    $filter_user = "WHERE up.kode_mkt='$user_group'";
    $filter_date = "";
} else {
    $filter_user = "";
    $filter_date = "";
}

$where = trim("$filter_user $filter_date");

if($status != ""){
  $where = "WHERE status = '$status'";
}

$query = mysql_query("
select a.reqno reqno,goods_code,itemdesc,s.color,s.size,ms.supplier,reqitem.qty,reqitem.unit,
        reqitem.curr,reqitem.price,round(reqitem.qty*reqitem.price) amt,a.notes remark
        ,tmppo.username userpo,tmppo.podate,a.username username,a.reqdate reqdate,
        a.app,a.app_by,a.app_date,a.app_notes,a.app2,a.app_by2,a.app_date2,a.app_notes2 
        from reqnon_header a inner join reqnon_item reqitem on a.id=reqitem.id_reqno
        inner join masteritem s on reqitem.id_item=s.id_item
		INNER JOIN userpassword up ON a.username = up.username		
        left join mastersupplier ms on reqitem.id_supplier=ms.id_supplier 
        left join (select s.id_jo,a.username,a.podate from po_header a inner join po_item s on a.id=s.id_po 
            where jenis='N' group by s.id_jo) tmppo on tmppo.id_jo=a.id
$where
ORDER BY a.dateinput DESC
");

// Tampilkan header HTML Excel
echo "<div class='box'>";
echo "<div class='box-body'>";
echo "<h1>LIST GENERAL REQUEST</h1>";
echo "<h3>Departemen: $user_group</h3>";
echo "</div>";
echo "</div>";
echo "<table border='1'>";
echo "<thead>
					<tr>
						<th>Req #</th>
						<th>Item Code</th>
						<th>Description</th>
						<th>Color</th>
						<th>Size</th>
						<th>Supplier</th>
						<th>Qty</th>
						<th>Unit</th>
						<th>Currency</th>
						<th>Price</th>
						<th>Amount</th>
						<th>Remark</th>
						<th>User PO</th>
						<th>Date PO</th>
						<th>Created By</th>
						<th>Request Date</th>
						<th>Approve</th>
						<th>Approve By</th>
						<th>Date Approve</th>
						<th>Approve Notes</th>
						<th>Approve 2</th>
						<th>Approve By 2</th>
						<th>Date Aprrove 2</th>
						<th>Approve Notes 2</th>
					</tr>
</thead><tbody>";

while($row = mysql_fetch_array($query)){
$cancel_text = ($row['cancel_h'] == 'Y') ? 'Cancelled' : '';	
$req_date = date('d-m-Y', strtotime($row['reqdate']));
$created_date = date('d-m-Y H:i', strtotime($row['dateinput']));
$po_date = $row['podate'] ? date('d-m-Y', strtotime($row['podate'])) : '';	
  echo "<tr>
          <td>$row[reqno]</td>
		  <td>$row[goods_code]</td>
          <td>$row[itemdesc]</td>
          <td>$row[color]</td>
          <td>$row[size]</td>
          <td>$row[supplier]</td>
          <td>$row[qty]</td>
          <td>$row[unit]</td>
          <td>$row[curr]</td>
          <td>$row[price]</td>
          <td>$row[amt]</td>
          <td>$row[remark]</td>
          <td>$row[userpo]</td>
		  <td>$row[podate]</td>
		  <td>$row[username]</td>
		  <td>$row[reqdate]</td>
		  <td>$row[app]</td>
		  <td>$row[app_by]</td>
		  <td>$row[app_date]</td>
		  <td>$row[app_notes]</td>
		  <td>$row[app2]</td>
		  <td>$row[app_by2]</td>
		  <td>$row[app_date2]</td>
		  <td>$row[app_notes2]</td>
        </tr>";
}

echo "</tbody></table>";
?>