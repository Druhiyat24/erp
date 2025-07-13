<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
?>
<head>
	<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>
	<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>
</head>
<table id="examplefix" class="display responsive" style="width:100%">
<thead>
	<tr>
		<th width="2%">Cek List</th>
		<th>Jenis Setting Payroll</th>
	</tr>
</thead>
<tbody>
<?php
$nik=$_REQUEST['nik'];
# QUERY TABLE
$sql="select * from masterpilihan where kode_pilihan='SET_PAY'";
$result=mysql_query($sql);
$sql="select * from hr_masteremployee where nik='$nik'";
$rs=mysql_fetch_array(mysql_query($sql));
while($data = mysql_fetch_array($result))
{ echo "<tr>";
		if ($rs[$data[1]]=="1")  
		{	$status_checked="checked";	}
		else
		{	$status_checked="";	}
		echo "
		<td>
			<input type ='hidden' name='chkhide[$data[1]]' value='$data[1]'>
			<input type ='checkbox' $status_checked name='itemchk[$data[1]]' class='chkclass'>
		</td>";
		if ($data[1]=="set_bpjstk")
		{	$cap_ori="BPJS Tenaga Kerja";	}
		else if ($data[1]=="set_bpjskes")
		{	$cap_ori="BPJS Kesehatan";	}
		echo "<td>$cap_ori</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
  var table = $('#examplefix').DataTable
  ({  scrollY: "300px",
      scrollCollapse: true,
      paging: false,
      pageLength: 50,
      fixedColumns:   
      { leftColumns: 1,
        rightColumns: 1
      }
  });
});
</script>