<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

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
	$cons=flookup("ac.cons","jo_det a inner join so s on a.id_so=s.id inner join 
		act_costing_mat ac on s.id_cost=ac.id_act_cost","a.id_jo='$id_jo' and ac.id_item='$id_item'");
	$unit=flookup("ac.unit","jo_det a inner join so s on a.id_so=s.id inner join 
		act_costing_mat ac on s.id_cost=ac.id_act_cost","a.id_jo='$id_jo' and ac.id_item='$id_item'");
	$nm_group = flookup("nama_group","mastercontents mct inner join mastertype2 mty on mct.id_type=mty.id 
		inner join mastersubgroup msu on mty.id_sub_group=msu.id inner join mastergroup mgr on msu.id_group=mgr.id", 
		"mct.id='$id_item'");
	echo json_encode(array($cons,$nm_group,$unit));
}

if ($modenya=="view_list_cost")
{	$cri = $_REQUEST['cri_item'];
	$id_item = $_REQUEST['id_jo'];
	if ($cri=="M")
	{	$sql = "SELECT h.id isi,concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) tampil
      from act_costing a inner join act_costing_mat s on 
      a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
      d.id=f.id_group 
      inner join mastertype2 g on f.id=g.id_sub_group
      inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
      inner join so j on a.id=j.id_cost
      inner join jo_det k on k.id_so=j.id
      where id_jo='$id_item' group by h.id";
    IsiCombo($sql,'','Pilih Contents');
  }
	else
	{	$sql = "SELECT mo.id isi,concat(cfdesc) tampil
      from act_costing a inner join act_costing_mfg s on 
      a.id=s.id_act_cost inner join mastercf mo on s.id_item=mo.id
      inner join so j on a.id=j.id_cost
      inner join jo_det k on k.id_so=j.id 
      where id_jo='$id_item'";
    IsiCombo($sql,'','Pilih Manufacturing');
  }
}

if ($modenya=="view_list_size")
{	$j_item = $_REQUEST['j_item'];
	$id_contents = $_REQUEST['id_contents'];
	$id_jo = $_REQUEST['id_jo'];
	$rulebom = $_REQUEST['rulebom'];
	if ($rulebom!="")
	{	if ($rulebom=="All Color All Size")
		{	$sql="select 'All Color All Size' tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' limit 1";
		}
		else if ($rulebom=="All Color Range Size")
		{	$sql="select concat('All Color | ',size) tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by size";
		}
		else if ($rulebom=="Per Color All Size")
		{	$sql="select concat(color,' | All Size') tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color";
		}
		else
		{	$sql="select concat(color,' | ',size) tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color,size";
		}
		$rs=mysql_query($sql);
		$crinya=mysql_num_rows($rs);
		echo "<table style='width: 100%;'>";
			echo "<thead>";
				echo "<tr>";
					$cnya="Color | Size"; $ket="CSBD_BOM|".$id_jo."|".$id_contents."|".$rulebom."|".$j_item; 
					echo "<th width='16%'>$cnya</th>";
					echo "<th width='16%'>Item</th>";
					echo "<th width='16%'>$cnya</th>";
					echo "<th width='16%'>Item</th>";
					echo "<th width='16%'>$cnya</th>";
					echo "<th width='16%'>Item</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tr>"; show_roll(1,3,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(4,6,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(7,9,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(10,12,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(13,15,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(16,18,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(19,21,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(22,24,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(25,27,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(28,30,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(31,33,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(34,36,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(37,39,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(40,42,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(43,45,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(46,48,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(49,51,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(52,54,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(55,57,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(58,60,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(61,63,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(64,66,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(67,69,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(70,72,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(73,75,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(76,78,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(79,81,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(82,84,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(85,87,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(88,90,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(91,93,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(94,96,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(97,99,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(100,102,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(103,105,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(106,108,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(109,111,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(112,114,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(115,117,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(118,120,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(121,123,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(124,126,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(127,129,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(130,132,$crinya,$ket); echo "</tr>";
			echo "<tr>"; show_roll(133,135,$crinya,$ket); echo "</tr>";
		echo "</table>";
	}
}
?>