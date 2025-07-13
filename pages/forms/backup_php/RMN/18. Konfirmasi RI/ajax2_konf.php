<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$logo_company=$rscomp["logo_company"];
	$jenis_company=$rscomp["jenis_company"];
	$gr_po_need_app=$rscomp["gr_po_need_app"];
	$modenya = $_GET['modeajax'];

if ($modenya=="cari_list_sj")
{	$crinya = $_REQUEST['cri_item'];
	$modnya = $_REQUEST['modnya'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		if($modnya=="17O")
		{
			$sql="select concat('bppb:',bppbno) isi,concat(if(bppbno_int!='',bppbno_int,bppbno),'|',supplier) tampil from 
				bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier
				where bppbdate>='$cridtnya' and s.area in ('I','L','F','LINE') 
				and confirm='N' and a.cancel='N'  
				group by bppbno";
		}
		else
		{
			$sql="select concat('bpb:',bpbno) isi,concat(if(ifnull(bpbno_int,'')='',bpbno,bpbno_int),'|',supplier,'|',jenis_dok,'|',bcno) tampil from 
				bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier
				where bpbdate>='$cridtnya' and s.area in ('I','L')  
				and confirm='N' 
				and a.cancel='N' 
				group by bpbno order by isi";
		}
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Nomor Transaksi');
	}
}

if ($modenya=="view_list_sj")
{	$crinya = $_REQUEST['cri_item2'];
	if ($crinya!="")
	{	$arrnya=explode(":",$crinya);
		$nm_tbl=$arrnya[0];
		$bppbno=$arrnya[1];
		if ($nm_tbl=="bpb") {$nm_fld="bpbno"; $add_cri=",update_dok_pab"; $nomorinput="bpbno_int"; $tgldok="bpbdate"; } else {$nm_fld="bppbno"; $add_cri=""; $nomorinput="bppbno_int"; $tgldok="bppbdate"; }
		if (substr($bppbno,3,2)=="FG" or substr($bppbno,0,2)=="FG") 
		{ $nm_mst="masterstyle";
			$fld_mat="'FG' mattype"; 
			$fld_desc="s.itemname itemdesc";
		}
		else
		{ $nm_mst="masteritem"; 
			$fld_mat="s.mattype";
			$fld_desc="s.itemdesc";
		}
		$sql="select $nm_fld trans_no,$nomorinput no_input,$tgldok tgl_dok,a.id_jo,a.id_item,$fld_mat,s.goods_code,$fld_desc,s.color,a.qty,
			a.unit,a.confirm $add_cri, invno from $nm_tbl a inner join $nm_mst s 
			on a.id_item=s.id_item where $nm_fld='$bppbno' ";
		$sql1="select sum(a.qty) as total,  $nm_fld trans_no,$nomorinput no_input,$tgldok tgl_dok,a.id_jo,a.id_item,$fld_mat,s.goods_code,$fld_desc,s.color,a.qty,
			a.unit,a.confirm $add_cri, invno from $nm_tbl a inner join $nm_mst s 
			on a.id_item=s.id_item where $nm_fld='$bppbno' ";			
		#echo $sql;
		echo "<table style='width: 100%;' id='examplefix2'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>No</th>";
					echo "<th>Nomor Input</th>";
					echo "<th>Tgl $nm_tbl</th>";
					echo "<th>Kode</th>";
					echo "<th>Deskripsi</th>";
					echo "<th>Qty SJ</th>";
					echo "<th>Satuan</th>";
					echo "<th>No SJ</th>";
					if($nm_tbl=="bpb")
					{	
						echo "<th>Detail Roll</th>";	
						echo "<th>Update Dok</th>";	
						echo "<th>Konfirmasi</th>";							
					}
					if($nm_tbl=="bppb")
					{
					echo "<th>Konfirmasi</th>";	
					}					
				echo "</tr>";
			echo "</thead>";
///// RMN			
			$query1=mysql_query($sql1);
			while($data1=mysql_fetch_array($query1))
			{
			// echo "<tbody>"; echo "</tbody>";
			echo "<tfoot>";
			echo "<tr>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th>Total</th>";
			echo "<th>$data1[total]</th>";
			echo "<th></th>";
			if($nm_tbl=="bpb")
			{
				echo "<th></th>";
				echo "<th></th>";			
			}
			echo "<th></th>";	
			echo "</tr>";
			echo "</tfoot>";
		}
// $('#select_all').change(function() {
//   var checkboxes = $(this).closest('form').find(':checkbox');
//   checkboxes.prop('checked', $(this).is(':checked'));
// });
// <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
////// RMN		
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id = $data['id_item'];
					$tgl = date('d-M-Y',strtotime($data['tgl_dok']));
					$no_trans = $data['trans_no'];
					echo "<tr>";
						echo "<td>$i</td>";
						echo "<td>$data[no_input]</td>";
						echo "<td>$tgl</td>";
						echo "<td>$data[goods_code]</td>";
						echo "<td>$data[itemdesc]</td>";
						echo "<td>$data[qty]</td>";
						echo "<td>$data[unit]</td>";
						echo "<td>$data[invno]</td>";
						if($nm_tbl=="bpb" and $logo_company=="S")
							{	
								$cekroll=flookup("bpbno","bpb_roll_h","bpbno='$data[trans_no]' and id_jo='$data[id_jo]' 
									and id_item='$data[id_item]'");
								if($cekroll=="") { $cekroll="N/A"; } else { $cekroll="Ok"; }
								if($data['update_dok_pab']=="Y") { $upddok="Ok"; } else { $upddok="N/A"; }
								if($cekroll =="Ok")
								{
									$status = "checked";
									$status_konf = "";
								}
								else
								{
									$status = "";
									$status_konf = "disabled";
								}
								echo "<td>$cekroll</td>";
								echo "<td>$upddok</td>";
								if ($data['confirm']=="Y")
								{
									echo "<td>Confirmed</td>";
								}
								else
								{
									if(strpos($no_trans, 'C') !== false or strpos($no_trans, 'N') !== false or strpos($no_trans, 'M') !== false )
									{
										$status_konf = "";
									echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>";
									}
									else
									{
										echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' $status></td>";
									}										
								}
							}
							if($nm_tbl=="bppb" and $logo_company=="S")
							{
							echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>";							
							}	
							// if ($data['confirm']=="Y")
						// { echo "<td>Confirmed</td>"; }
						// else
						// {echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>"; }
; 

					echo "</tr>";
					$i++;
				};
		echo "</table>";
		echo "<button type='submit' name='submit' $status_konf class='btn btn-primary'>Konfirmasi</button>";
	}
}
?>