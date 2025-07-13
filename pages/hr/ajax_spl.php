<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="view_list_nik" or $modajax=="view_list_nik2" 
	or $modajax=="view_list_nik2spl" or $modajax=="view_list_nik3")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}
if ($modajax=="calc_jam_mulai_selesai")
{	$tglnya = $_REQUEST['tglnya'];
	$jamnya = $_REQUEST['jamnya'];
	
	$mul = flookup("mulai_spl","hr_masterwaktu","hari='".date('l',strtotime($tglnya))."'");
	$timestamp = strtotime($mul) + $jamnya*(60*60);
	$time = date('H:i', $timestamp);
	$sel = $time;
	#echo "<script>alert($sel)</script>";
	echo json_encode(array(ft($mul),ft($sel)));
}
if ($modajax=="get_list_line")
{	$crinya=$_REQUEST['cri_item'];
	$cridept=$_REQUEST['dept'];
	if ($crinya!="")
	{	$sql="select line isi,line tampil from hr_masteremployee where 
			(selesai_kerja='0000-00-00' or selesai_kerja is null) and   
			bagian='$crinya' and department='$cridept' group by line";
		IsiCombo($sql,'-','Pilih Line');
	}
}
if ($modajax=="get_list_bagian")
{	$crinya=$_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql="select bagian isi,bagian tampil from hr_masteremployee where 
			(selesai_kerja='0000-00-00' or selesai_kerja is null) and 
			department='$crinya' group by bagian";
		IsiCombo($sql,'-','Pilih Bagian');
	}
}
if ($modajax=="view_list_nik2spl")
{	$tglnya = $_REQUEST['tglnya'];	
	?>
	<table id="example1" class="table table-bordered table-striped">
		<thead>
      <tr>
	      <th>No</th>
        <th>Nik</th>
        <th>Nama</th>
        <th>Tanggal</th>
        <th>Jam Mulai</th>
        <th>Jam Selesai</th>
        <th>Keterangan</th>
        <th>Action</th>
      </tr>
   	</thead>
  	<tbody>
    	<?php
      # QUERY TABLE
      $sql="SELECT a.*,s.nama 
      	FROM hr_spl a inner join hr_masteremployee s 
        on a.nik=s.nik where tanggal='".fd($tglnya)."' 
        order by a.tanggal desc";
      $query = mysql_query($sql);
		  $no = 1; 
			while($data = mysql_fetch_array($query))
		  { echo "<tr>";
				  echo "<td>$no</td>"; 
				  echo "<td>$data[nik]</td>"; 
				  echo "<td>$data[nama]</td>"; 
				  echo "<td>$data[tanggal]</td>";
          echo "<td>$data[mulai]</td>"; 
				  echo "<td>$data[selesai]</td>"; 
          echo "<td>$data[keterangan]</td>";
          $pared=$data['nik'].":".$data['tanggal'];
          echo "<td><a href='../hr/?mod=18e&id=$pared'>Ubah</a> | 
          <a href='del_data.php?id=$pared&pro=SPL'";?> 
          onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
			  echo "</tr>";
			  $no++; // menambah nilai nomor urut
			}
		  ?>
      </tbody>
	</table>
  <?php
}
if ($modajax=="view_list_nik" or $modajax=="view_list_nik2" or $modajax=="view_list_nik3")
{	$tglnya = $_REQUEST['tglnya'];	
	if ($modajax=="view_list_nik2")
	{	$bagian = $_REQUEST['bagian'];
		$where = "bagian='$bagian'";
	}
	else if ($modajax=="view_list_nik3")
	{	$dept = $_REQUEST['dept'];
		$where = "department='$dept'";
	}
	else
	{	$line = $_REQUEST['line'];	
		$where = "line='$line'";
	}
	#if ($line!="")
	#{	
	$sql="select nik,nama,timein,timeout from hr_masteremployee a left join hr_timecard s 
		on a.nik=s.empno and '".fd($tglnya)."'=s.workdate where $where 
		and (selesai_kerja='0000-00-00' or selesai_kerja is null)";
	#echo $sql;
	$rs=mysql_query($sql);
	$crinya=mysql_num_rows($rs);
	echo "<table id='examplefixnopage' style='width: 100%;'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th width='2%'>...</th>";
				echo "<th width='10%'>NIK</th>";
				echo "<th width='30%'>Nama</th>";
				echo "<th width='10%'>Masuk Actual</th>";
				echo "<th width='10%'>Pulang Actual</th>";
			echo "</tr>";
		echo "</thead>";
		while($data = mysql_fetch_array($rs))
		{	$status_checked="";
			echo "
			<tr>
				<td>
      		<input type ='hidden' name='chkhide[$data[nik]]' value='$data[nik]'>
        	<input type ='checkbox' $status_checked name='itemchk[$data[nik]]' class='chkclass'>
      	</td>
      	<td>$data[nik]</td>
				<td>$data[nama]</td>
				<td>$data[timein]</td>
				<td>$data[timeout]</td>
			</tr>";
		}
	echo "</table>";
	#}
}
?>
<?php 
if ($modajax=="view_list_nik" or $modajax=="view_list_nik2" 
	or $modajax=="view_list_nik2spl" or $modajax=="view_list_nik3")
{?>
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
	<script>
  //Datatable fix header
  $(document).ready(function() {
    var table = $('#examplefixnopage').DataTable
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
<?php
}
?>