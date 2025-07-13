<?php 
include "../../include/conn.php";
include "fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];
$ip=$user.'-'.$sesi;

$id_nya = $_POST['id'];
echo "<table class='table table-bordered'>";
	echo "<thead>";
	echo "<tr>";
		echo "<th>Nama Barang</th>";
		echo "<th>Jumlah</th>";
		echo "<th>Satuan</th>";
		echo "<th>Curr</th>";
		echo "<th>Harga</th>";
	echo "</tr>";
	echo "</thead>";
	# QUERY TABLE
	$sql="delete from tmp_report where ip='$ip'";
    insert_log($sql,$user);
    $sql="insert into tmp_report (ip,matcontents,date1,sj1,pono,qtyorder,vendorcode,remark,qty1,qty2)
    	select '$ip',id_item,bpbdate,bpbno,pono,0,s.supplier,remark,qty,0 from bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier
    	where id_item='$id_nya'";
    insert_log($sql,$user);
    $sql="insert into tmp_report (ip,matcontents,date1,sj1,pono,qtyorder,vendorcode,remark,qty1,qty2)
    	select 'SC',id_item,bppbdate,bppbno,concat('PK # ',kpno,' ST # ',styleno),0,'',sent_to,0,qty from bppb
    	where id_item='$id_nya' ";
    insert_log($sql,$user);
    $sql="insert into tmp_report (ip,matcontents,date1,sj1,pono,qtyorder,vendorcode,remark,qty1,qty2) 
    	select 'SC',id_item,returndate,returnno,'',0,'','',0,qty from hreturn 
    	where id_item='$id_nya' and returnno like 'RO%' ";
    insert_log($sql,$user);
    $sql="insert into tmp_report (ip,matcontents,date1,sj1,pono,qtyorder,vendorcode,remark,qty1,qty2) 
    	select 'SC',id_item,returndate,returnno,'',0,'','',qty,0 from hreturn  
    	where id_item='$id_nya' and returnno like 'RI%'";
    insert_log($sql,$user);
    
	$query = mysql_query("SELECT a.curr,a.price,a.id_item,s.goods_code,$flddesc itemdesc,a.qty,a.unit 
         FROM $tbltrans a inner join $tblmst s on a.id_item=s.id_item 
         where $fldtrans='$trans_no' ORDER BY a.id_item ASC");
	while($data = mysql_fetch_array($query))
  	{ echo "<tr>";
		  echo "<td>$data[goods_code]</td>"; 
		  if (substr($trans_no,0,2)=="FG" OR substr($trans_no,0,5)=="SJ-FG")
		  { echo "<td>$data[buyerno]</td>"; }
		  echo "<td>$data[itemdesc]</td>"; 
		  echo "<td>$data[qty]</td>";
		  echo "<td>$data[unit]</td>"; 
		  echo "<td>$data[curr]</td>"; 
		  echo "<td>$data[price]</td>";
	  echo "</tr>";
	}
echo "</table>";

?>