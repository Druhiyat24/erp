<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];


if ($modenya=="view_rule")
{	
	$cek_data = $_REQUEST['cri_item'];
	if ($cek_data!="")
	{
	$sql="select nama_pilihan isi,nama_pilihan tampil 
    from masterpilihan where kode_pilihan='Rule_BOM'";
  IsiCombo($sql,'','Pilih Rule BOM');
	}
}

if ($modenya=="view_dest")
{	
	$id_jo_dest = $_REQUEST['id_jo'];

	$sql="select dest isi, dest tampil 
	from jo_det a inner join so_det s on a.id_so=s.id_so 
	where a.id_jo='$id_jo_dest' group by dest";
  IsiCombo($sql,'','');

}


if ($modenya=="view_list_size")
{	$j_item = 'M';
	$id_contents = $_REQUEST['id_contents'];
	$id_jo = $_REQUEST['id_jo'];
	$dest  = $_REQUEST['dest'];
	if ($dest != "")
	{
		$txtdest = ", dest ";
	}
	else
	{
		$txtdest = "";
	}

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
				on a.id_so=s.id_so where id_jo='$id_jo' group by size $txtdest";
		}
		else if ($rulebom=="Per Color All Size")
		{	$sql="select concat(color,' | All Size') tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color $txtdest";
		}
		else
		{	$sql="select concat(color,' | ',size) tampil 
				from jo_det a inner join so_det s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color,size $txtdest";
		}
		$rs=mysql_query($sql);
		$crinya=mysql_num_rows($rs);
		echo "<table style='width: 100%;'>";
			echo "<thead>";
				echo "<tr>";
					$cnya="Color | Size"; $ket="CSBD_BOM|".$id_jo."|".$id_contents."|".$rulebom."|".$j_item."|".$dest; 
					echo "<th width='auto'>$cnya</th>";
					echo "<th width='45%'>Item</th>";
					echo "<th width='auto'>Qty</th>";
					echo "<th width='10%'>Unit</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tr width='auto'>"; show_roll_bom_global(1,1,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(2,2,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(3,3,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(4,4,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(5,5,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(6,6,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(7,7,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(8,8,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(9,9,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(10,10,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(11,11,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(12,12,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(13,13,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(14,14,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(15,15,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(16,16,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(17,17,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(18,18,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(19,19,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(20,20,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(21,21,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(22,22,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(23,23,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(24,24,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(25,25,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(26,26,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(27,27,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(28,28,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(29,29,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(30,30,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(31,31,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(32,32,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(33,33,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(34,34,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(35,35,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(36,36,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(37,37,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(38,38,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(39,39,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(40,40,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(41,41,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(42,42,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(43,43,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(44,44,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(45,45,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(46,46,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(47,47,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(48,48,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(49,49,$crinya,$ket); echo "</tr>";
			echo "<tr width='auto'>"; show_roll_bom_global(50,50,$crinya,$ket); echo "</tr>";


		echo "</table>";
	}
}

?>