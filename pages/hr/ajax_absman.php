<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="view_list_nik2")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}
if ($modajax=="view_list_nik2")
{	$tglnya = $_REQUEST['tglnya'];	
	$dept = $_REQUEST['dept'];
	?>
	<table id="examplefix" class="table table-bordered table-striped">
    <thead>
    <tr>
      <th>No</th>
      <th>NIK</th>
      <th>Nama</th>
      <th>Tanggal</th>
      <th>Jam Masuk</th>
      <th>Jam Pulang</th>
      <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
      <?php
      $sql="SELECT a.*,s.nama,s.nik from hr_timecard a inner join hr_masteremployee s 
        on a.empno=s.nik where workdate='".fd($tglnya)."' and department='$dept'
        order by workdate desc ";
      #echo $sql;
      $query = mysql_query($sql);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "<tr>";
        echo "<td>$no</td>"; 
        echo "<td>$data[nik]</td>"; 
        echo "<td>$data[nama]</td>"; 
        echo "<td>".fd_view($data['WorkDate'])."</td>";
        echo "<td>$data[TimeIn]</td>";
        echo "<td>$data[TimeOut]</td>";
        $cri=$data['nik'].":".$data['WorkDate'];
        echo "<td><a href='../hr/?mod=13&id=$cri'>Ubah</a> ";
        echo "| <a href='del_data.php?id=$cri&pro=ManAbs'";?> 
        onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
  </table>
  <?php
}
?>
<?php 
if ($modajax=="view_list_nik2")
{?>
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
	<script>
  //Datatable fix header
  $(document).ready(function() {
    var table = $('#examplefix').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
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