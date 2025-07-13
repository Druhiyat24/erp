<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
include "../forms/fungsi2.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_rule")
{	$sql="select nama_pilihan isi,nama_pilihan tampil 
    from masterpilihan where kode_pilihan='Rule_BOM'";
  IsiCombo($sql,'','Pilih Rule BOM');
}

if ($modenya=="view_cons")
{	$id_jo=$_REQUEST['id_jo'];
	$id_item=$_REQUEST['cri_item'];
	$cons=flookup("ac.cons","jo_det_dev a inner join so_dev s on a.id_so=s.id inner join 
		act_development_mat ac on s.id_cost=ac.id_act_cost","a.id_jo='$id_jo' and ac.id_item='$id_item'");
	echo json_encode(array($cons));
}

if ($modenya=="view_list_cost")
{	$cri = $_REQUEST['cri_item'];
	$id_item = $_REQUEST['id_jo'];
	if ($cri=="M")
	{	$sql = "SELECT h.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) tampil
      from act_development a inner join act_development_mat s on 
      a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
      d.id=f.id_group 
      inner join mastertype2 g on f.id=g.id_sub_group
      inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
      inner join so_dev j on a.id=j.id_cost
      inner join jo_det_dev k on k.id_so=j.id
      where id_jo='$id_item' group by h.id";
    IsiCombo($sql,'','Pilih Contents');
  }
	else
	{	$sql = "SELECT mo.id isi,concat(cfdesc) tampil
      from act_development a inner join act_development_mfg s on 
      a.id=s.id_act_cost inner join mastercf mo on s.id_item=mo.id
      inner join so_dev j on a.id=j.id_cost
      inner join jo_det_dev k on k.id_so=j.id 
      where id_jo='$id_item'";
    IsiCombo($sql,'','Pilih Manufacturing');
  }
}

if ($modenya=="view_list_size")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/select2/select2.min.css'>";
	echo "</head>";
}

if ($modenya=="view_list_size")
{	//print_r($_REQUEST);

	$j_item = $_REQUEST['j_item'];
	$id_contents = $_REQUEST['id_contents'];
	$id_jo = $_REQUEST['id_jo'];
	$rulebom = $_REQUEST['rulebom'];
	if ($rulebom!="")
	{	if ($rulebom=="All Color All Size")
		{	$sql="select 'All Color All Size' tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' limit 1";
		//echo $sql;
		}
		else if ($rulebom=="All Color Range Size")
		{	$sql="select concat('All Color | ',size) tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by size";
		}
		else if ($rulebom=="Per Color All Size")
		{	$sql="select concat(color,' | All Size') tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color";
		}
		else
		{	$sql="select concat(color,' | ',size) tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color,size";
		}
		$rs=mysql_query($sql);
		$crinya=mysql_num_rows($rs);
		echo "<table style='width: 100%;'>";
			echo "<thead>";
				echo "<tr>";
					$cnya="Color | Size"; $ket="CSBD_BOM|".$id_jo."|".$id_contents."|".$rulebom."|".$j_item; 
					echo "<th width='33%'>$cnya &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
					echo "<th width='33%'>$cnya &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
					echo "<th width='33%'>$cnya &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tr>"; show_roll_dev(1,3,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(4,6,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(7,9,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(10,12,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(13,15,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(16,18,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(19,21,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(22,24,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(25,27,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(28,30,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll_dev(31,33,$crinya,$ket); echo "</tr>";
		echo "</table>";
	}
}
?>
<?php 
if ($modenya=="view_list_size")
{?>
	
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
	<script src="../../plugins/select2/select2.full.min.js"></script>
  <script>
  $(".select2").select2();
  $("#example1").DataTable();
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
  //Datatable fix header no pagination and searching
  $(document).ready(function() {
    var table = $('#examplefix2').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        searching: false,
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