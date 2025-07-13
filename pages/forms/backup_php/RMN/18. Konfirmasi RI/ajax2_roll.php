<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$logo_company=$rscomp["logo_company"];
	$jenis_company=$rscomp["jenis_company"];
	$gr_po_need_app=$rscomp["gr_po_need_app"];
$modenya = $_GET['modeajax'];

if ($modenya=="cari_qty_bpb")
{	$crinya = $_REQUEST['cri_item'];
	$cek_arr=explode("|",$crinya);
	$bpbno=$cek_arr[0];
	$id_item=$cek_arr[1];
	$sqlbpb = "select group_concat(distinct id_po_item) list_id_po_item,sum(qty_over) tot_over,ifnull(poh.po_over,'') st_over 
		from bpb left join po_header poh on bpb.pono=poh.pono where bpb.bpbno='$bpbno' and bpb.id_item='$id_item' 
		group by bpb.bpbno,bpb.id_item";
	$rsbpb = mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));
	$id_po_item=$rsbpb['list_id_po_item'];
	$qty_over = $rsbpb['tot_over'];
	$cek_over = $rsbpb['st_over'];
	if($qty_over>0 and $cek_over!="C")
	{
		echo json_encode
		(array
			(	0,
				'',
				'',
				'',
				0,
				'',
				'',
				'',
				'Item Over Tollerance',
				'',
				''
			)
		);
	}
	else
	{
		$sql="select concat(itemdesc,' ',ifnull(color,''),' ',ifnull(size,''),' ',ifnull(add_info,'')) item,
			nomor_rak,sum(bpb.qty) qtybpb,bpb.unit unitbpb,konv.unit2 unitkonv,konv.qty1,konv.
			qty2,pono,supplier,ac.kpno    
			from bpb inner join mastersupplier msu on bpb.id_supplier=msu.id_supplier 
			inner join masteritem mi on bpb.id_item=mi.id_item 
			inner join (select * from jo_det group by id_jo) jod on bpb.id_jo=jod.id_jo 
			inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id 
			left join konversi_satuan konv on bpb.id_item=konv.id_item  
			where bpbno='$bpbno' and bpb.id_item='$id_item' 
			group by bpbno ";
		$rs=mysql_fetch_array(mysql_query($sql));
		$unitkonv=$rs['unitkonv'];
		if($rs['qty1']==1)
		{	$qtykonv=$rs['qtybpb']*$rs['qty2']; 
			$konvnya="Kali|".$rs['qty2'];
		}
		else if($rs['qty2']==1)
		{ $qtykonv=$rs['qtybpb']/$rs['qty1']; 
			$konvnya="Bagi|".$rs['qty1'];
		}
		else
		{ $qtykonv=$rs['qtybpb']; 
			$unitkonv=$rs['unitbpb'];
			$konvnya="Kali|1";
		}
		echo json_encode
		(array
			(	$rs['qtybpb'],
				$rs['nomor_rak'],
				$rs['unitbpb'],
				$unitkonv,
				round($qtykonv,2),
				$konvnya,
				$rs['pono'],
				$rs['supplier'],
				$rs['item'],
				$rs['kpno']
			)
		);	
	}
}

if ($modenya=="view_list_roll" OR $modenya=="view_list_detail" OR $modenya=="view_list_size")
{	$crinya = $_REQUEST['cri_item'];
	if (isset($_REQUEST['sat_nya'])) { $defsat=$_REQUEST['sat_nya']; } else { $defsat=""; }
	if ($crinya!="")
	{	echo "<table style='width: 100%;'>";
			echo "<thead>";
				echo "<tr>";
					if ($modenya=="view_list_size") 
					{	
						$id_so=$_REQUEST['id_so'];
						$cnya="Size"; $ket="S:".$id_so;
						echo "
						<th width='3%'>$cnya</th>
						<th width='3%'>Qty</th>
						<th width='3%'>Add</th>
						<th width='3%'>Barcode</th>
						<th width='3%'>Price</th>

						<th width='3%'>$cnya</th>
						<th width='3%'>Qty</th>
						<th width='3%'>Add</th>
						<th width='3%'>Barcode</th>
						<th width='3%'>Price</th>

						<th width='3%'>$cnya</th>
						<th width='3%'>Qty</th>
						<th width='3%'>Add</th>
						<th width='3%'>Barcode</th>
						<th width='3%'>Price</th>

						<th width='3%'>$cnya</th>
						<th width='3%'>Qty</th>
						<th width='3%'>Add</th>
						<th width='3%'>Barcode</th>
						<th width='3%'>Price</th>

						<th width='3%'>$cnya</th>
						<th width='3%'>Qty</th>
						<th width='3%'>Add</th>
						<th width='3%'>Barcode</th>
						<th width='3%'>Price</th>";
						// echo "<th width='15%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Qty &nbsp &nbsp &nbsp Add &nbsp &nbsp &nbsp Barcode &nbsp &nbsp &nbsp Price</th>";
						// echo "<th width='15%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Qty &nbsp &nbsp &nbsp Add &nbsp &nbsp &nbsp Barcode &nbsp &nbsp &nbsp Price</th>";
						// echo "<th width='15%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Qty &nbsp &nbsp &nbsp Add &nbsp &nbsp &nbsp Barcode &nbsp &nbsp &nbsp Price</th>";
						// echo "<th width='15%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Qty &nbsp &nbsp &nbsp Add &nbsp &nbsp &nbsp Barcode &nbsp &nbsp &nbsp Price</th>";
						// echo "<th width='15%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Qty &nbsp &nbsp &nbsp Add &nbsp &nbsp &nbsp Barcode &nbsp &nbsp &nbsp Price</th>";
					} 
					else if ($modenya=="view_list_detail") 
					{	
						$cnya="Qty"; $ket="D";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
					} 
					else 
					{	
						$cnya=$defsat; $ket="R";
						echo "
						<th width='4%'>No.</th>
						<th width='4%'>Lot #</th>
						<th width='4%'>SJ</th>
						<th width='4%'>Actual</th>
						<th width='4%'>Konv</th>
						<th width='4%'>FOC</th>
						<th width='8%'>Barcode</th>
						<th width='8%'>No. Rak</th>
						
						<th width='4%'>No.</th>
						<th width='4%'>Lot #</th>
						<th width='4%'>SJ</th>
						<th width='4%'>Actual</th>
						<th width='4%'>Konv</th>
						<th width='4%'>FOC</th>
						<th width='8%'>Barcode</th>
						<th width='8%'>No. Rak</th>";
					}
				echo "</tr>";
			echo "</thead>";
			if ($modenya=="view_list_size")
			{	
				echo "<tr>"; show_roll_size(1,5,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(6,10,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(11,15,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(16,20,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(21,25,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(26,30,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(31,35,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(36,40,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(41,45,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(46,50,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(51,55,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(56,60,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(61,65,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(66,70,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(71,75,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(76,80,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(81,85,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(86,90,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(91,95,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll_size(96,100,$crinya,$ket); echo "</tr>";
			}
			else if ($modenya=="view_list_detail")
			{	
				echo "<tr>"; show_roll(1,5,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(6,10,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(11,15,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(16,20,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(21,25,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(26,30,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(31,35,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(36,40,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(41,45,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(46,50,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(51,55,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(56,60,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(61,65,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(66,70,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(71,75,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(76,80,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(81,85,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(86,90,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(91,95,$crinya,$ket); echo "</tr>";
				echo "<tr>"; show_roll(96,100,$crinya,$ket); echo "</tr>";
			}
			else
			{	for ($x = 1; $x <= 800; $x++) 
      	{ echo "<tr>"; show_roll_bpb($x,$x+1,$crinya,$ket); echo "</tr>"; 
      		$x = $x + 1;
      	}
    	}
		echo "</table>";
	}
}

if ($modenya=="cari_list_bpb" or $modenya=="cari_list_bpb_loc" or $modenya=="cari_list_bpb_det")
{	$crinya = $_REQUEST['cri_item'];
	if (isset($_REQUEST['mat_nya'])) {$crimat = $_REQUEST['mat_nya'];} else {$crimat = "";}
	if (($modenya=="cari_list_bpb" and $crinya!="" and $crimat!="") or
			($modenya=="cari_list_bpb_loc" and $crinya!="" and $crimat!="") or  
			($modenya!="cari_list_bpb" and $modenya!="cari_list_bpb_loc" and $crinya!=""))
	{	$cridtnya=fd($crinya);
		if ($modenya=="cari_list_bpb") 
		{$sql_mat=" and matclass='$crimat' and rh.bpbno is null ";} 
		else if ($modenya=="cari_list_bpb_loc") 
		{$sql_mat=" and matclass='$crimat' and location is null ";} 
		else 
		{$sql_mat=" and sudah_detail='N' ";}
		if ($modenya=="cari_list_bpb_loc")
		{$sql_join=" inner join bpb_roll_h rh on a.bpbno=rh.bpbno and a.id_item=rh.id_item ";}
		else if ($modenya=="cari_list_bpb")
		{	$sql_join=" left join bpb_roll_h rh on a.bpbno=rh.bpbno and a.id_item=rh.id_item 
				and a.id_jo=rh.id_jo";
		}
		else 
		{$sql_join=" ";}
		$sql="select concat(a.bpbno,'|',a.id_item,'|',a.id_jo) isi,
			concat(if(a.bpbno_int!='',a.bpbno_int,a.bpbno),'|',itemdesc,' ',color,' ',size,' ',add_info,'|',ac.kpno) tampil 
			from bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			inner join masteritem d on a.id_item=d.id_item $sql_join 
			left join jo_det jod on a.id_jo=jod.id_jo 
			inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id    
			where bpbdate>='$cridtnya' $sql_mat
			and a.cancel = 'N' 
			group by a.bpbno,a.id_item,a.id_jo order by a.bpbno";
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Nomor BPB');
	}
}

?>