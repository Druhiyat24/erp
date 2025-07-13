<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$logo_company=$rscomp["logo_company"];
	$jenis_company=$rscomp["jenis_company"];
	$gr_po_need_app=$rscomp["gr_po_need_app"];
$modenya = $_GET['modeajax'];

if ($modenya=="cari_list_kpno")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		if($nm_company=="PT. Tun Hong")
		{	$sql="select a.id_item isi,concat(a.kpno,'|',a.styleno,'|',a.buyerno,'|',a.deldate,'|',a.id_item) 
			tampil from masterstyle a inner join bom s on a.id_item=s.id_item_fg
			where deldate>='$cridtnya' 
			group by kpno,styleno,buyerno,a.id_item order by kpno,styleno";
		}
		else
		{	if($jenis_company=="VENDOR LG")
			{ $fldtmpl=" concat(a.jo_no,' ',mp.product_group) "; }
			else
			{ $fldtmpl=" concat(ac.styleno,' ',ac.kpno,' ',mb.supplier) "; }
			$sql="select a.id isi,$fldtmpl  
			tampil from jo a inner join jo_det s on a.id=s.id_jo 
			inner join so d on s.id_so=d.id 
			inner join so_det f on d.id=f.id_so
			inner join act_costing ac on d.id_cost=ac.id 
			inner join masterproduct mp on ac.id_product=mp.id 
			inner join mastersupplier mb on ac.id_buyer=mb.id_supplier 
			where deldate_det>='$cridtnya' 
			group by a.id order by a.jo_no";
		}
		IsiCombo($sql,'','Pilih Nomor Order');
	}
}

if ($modenya=="cari_list_kpno2")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$sql="select concat(a.id_item,'~',s.id_item_bb) isi,
			concat(a.kpno,'|',a.styleno,'|',a.buyerno,'|',a.deldate,'|',a.id_item,'|',d.goods_code,'|',d.itemdesc) 
			tampil from masterstyle a inner join bom s on a.id_item=s.id_item_fg
			inner join masteritem d on s.id_item_bb=d.id_item 
			where deldate>='$cridtnya' 
			group by a.id_item,s.id_item_bb order by kpno,styleno";
		IsiCombo($sql,'','Pilih Nomor Order');
	}
}

if ($modenya=="cari_list_bpbno2")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$sql="select concat(a.bpbno,'~',a.id) isi,
			concat(if(a.bpbno_int!='',a.bpbno_int,a.bpbno),'|',right(a.nomor_aju,6),'|',s.goods_code,'|',
			s.itemdesc,'|',a.qty) 
			tampil from bpb a inner join masteritem s on a.id_item=s.id_item
			where bpbdate>='$cridtnya' and 
			(ifnull(id_item_fg,'0')='0' or ifnull(id_item_bb,'0')='0') 
			order by a.id";
		IsiCombo($sql,'','Pilih Nomor BPB');
	}
}

if ($modenya=="cari_list_kpno_item")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql="select a.id_item isi,concat(a.id_item,'|',a.goods_code,'|',a.itemdesc) 
			tampil from masteritem a ";
		IsiCombo($sql,'','Pilih Bahan Baku');
	}
}

if ($modenya=="cari_list_sj")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$sql="select concat('bppb:',bppbno) isi,concat(if(bppbno_int='',bppbno,bppbno_int),'|',supplier) tampil from 
			bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier
			where bppbdate>='$cridtnya' and jenis_dok='INHOUSE' and confirm='N' 
			group by bppbno 
			union all 
			select concat('bpb:',bpbno) isi,concat(if(bpbno_int='',bpbno,bpbno_int),'|',supplier,'|',jenis_dok,'|',bcno) tampil from 
			bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier
			where bpbdate>='$cridtnya' and s.area in ('I','L')  
			and confirm='N' 
			group by bpbno order by isi";
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Nomor Transaksi');
	}
}

if ($modenya=="cari_list_qc")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$sql="select bpbno isi,concat(if(bpbno_int<>'',bpbno_int,bpbno),'|',supplier) tampil from 
			bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier
			where bpbdate>='$cridtnya' group by bpbno order by bpbno";
		IsiCombo($sql,'','Pilih Nomor BPB');
	}
}

if ($modenya=="cari_list_po" or $modenya=="cari_list_po_gen" or $modenya=="cari_list_po_wip")
{	$nama_sup = $_REQUEST['nama_sup'];
	if ($nama_sup!='')
	{	
		if($modenya=="cari_list_po_gen") 
		{ 
			$isi ="a.pono";
			$cri=" and a.jenis='N' and a.app='A' ";; 
			$sql_join="";
			$sql_fld="pono ";	
		} 
		else if($modenya=="cari_list_po_wip") 
		{ 
/* 			print_r($_REQUEST);
			die(); */
	
			$isi = (ISSET($_REQUEST["mode_"]) && $_REQUEST["mode_"] == "37_bppb_po" ? "a.id" : "a.pono"  );
			if($gr_po_need_app=="Y")
			{	$cri=" and a.jenis='P' and a.app='A' "; }
			else
			{	$cri=" and a.jenis='P' "; }
			$sql_join="inner join jo_det jod on d.id_jo=jod.id_jo 
				inner join so on so.id=jod.id_so 
				inner join act_costing ac on ac.id=so.id_cost ";
			$sql_fld="concat(pono,'|',ifnull(pono_int,''),'|',if(count(distinct d.id_jo)>1,' (Combine)',ac.kpno)) ";
		}
		else 
		{ 
			$isi ="a.pono";
			if($gr_po_need_app=="Y")
			{	$cri=" and a.jenis='M' and a.app='A' "; }
			else
			{	$cri=" and a.jenis='M' "; }
			$sql_join="inner join jo_det jod on d.id_jo=jod.id_jo 
				inner join so on so.id=jod.id_so 
				inner join act_costing ac on ac.id=so.id_cost ";
			$sql_fld="concat(pono,'|',ifnull(pono_int,''),'|',if(count(distinct d.id_jo)>1,' (Combine)',ac.kpno)) ";
		}
		$sql="select $isi isi,$sql_fld tampil from 
			po_header a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			inner join po_item d on a.id=d.id_po 
			$sql_join
			left join 
			(select s.id,sum(d.qty) qtybpb from po_header a inner join po_item s on a.id=s.id_po 
				inner join bpb d on s.id=d.id_po_item where a.id_supplier='$nama_sup'   
				group by s.id)	t_bpb 
			on d.id=t_bpb.id  
			where a.id_supplier='$nama_sup' $cri 
			and ifnull(t_bpb.qtybpb,0)<d.qty group by pono order by pono";
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Nomor PO');
	}
}
if ($modenya=="cari_list_sec")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$id_supplier=flookup("id_supplier","po_header","pono='$crinya'");
		$sql = "select a.id isi,concat(nomor_sj,'|',supplier,'|',a.pono,'|',jenis_barang) tampil 
      from list_in_out a inner join mastersupplier s on a.id_supplier=s.id_supplier
      left join bpb d on a.id=d.id_sec where 
      a.id_supplier='$id_supplier' and a.dihide='N' ";
    IsiCombo($sql,'',$cpil.' Id Security');
	}
}

if ($modenya=="cari_list_sj_ri" or $modenya=="cari_list_sj_ri_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		if($modenya=="cari_list_sj_ri_fg")
		{	$crimat="a.bppbno like 'SJ-FG%' "; }
		else
		{	$crimat="a.bppbno not like 'SJ-FG%' "; }
		$sql="select bppbno isi,concat(bppbno,'|',supplier,'|',jenis_dok,'|',bcno,'|',ifnull(jo_no,'')) tampil from 
			bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			left join so_det sod on a.id_so_det=sod.id 
			left join jo_det jod on sod.id_so=jod.id_so 
			left join jo on jo.id=jod.id_jo  
			where bppbdate>='$cridtnya' and $crimat   
			group by bppbno order by bppbno";
		IsiCombo($sql,'','Pilih Nomor SJ');
	}
}

if ($modenya=="cari_list_sj_ro")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$modenya=$_REQUEST['mode'];
		if($modenya=="Bahan_Baku")
		{
			$fld_cri = " and mi.mattype not in ('N','M')";
		}
		else
		{
			$fld_cri = " and mi.mattype in ('N','M') and a.bpbno not like 'FG%' ";
		}
		$sql="select bpbno isi,concat(if(bpbno_int!='',bpbno_int,bpbno),'|',supplier,'|',invno) tampil from 
			bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			inner join masteritem mi on a.id_item=mi.id_item 
			where bpbdate>='$cridtnya' 
			$fld_cri group by bpbno order by bpbno";
		IsiCombo($sql,'','Pilih Nomor BPB');
	}
}

if ($modenya=="cari_defect")
{	$crinya = $_REQUEST['cri_item'];
	$cbomat=substr($crinya,0,1);
	if ($crinya!="")
	{	if($logo_company=="Z") { $sql_cri=""; } else { $sql_cri=" where left(mattype,1)='$cbomat' "; }
		$sql="select id_defect isi,nama_defect tampil from 
			master_defect
			$sql_cri ";
		IsiCombo($sql,'','Pilih Jenis Defect');
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
			concat(if(a.bpbno_int!='',a.bpbno_int,a.bpbno),'|',d.itemdesc,'--',d.add_info,'|',ac.kpno) tampil 
			from bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			inner join masteritem d on a.id_item=d.id_item $sql_join 
			inner join jo_det jod on a.id_jo=jod.id_jo 
			inner join so on jod.id_so=so.id 
			inner join act_costing ac on so.id_cost=ac.id    
			where bpbdate>='$cridtnya' $sql_mat 
			group by a.bpbno,a.id_item,a.id_jo order by a.bpbno";
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Nomor BPB');
	}
}

if ($modenya=="cari_list_bppb")
{	$crinya = $_REQUEST['cri_item'];
	if (isset($_REQUEST['mat_nya'])) {$crimat = $_REQUEST['mat_nya'];} else {$crimat = "";}
	if ($crinya!="" and $crimat!="")
	{	$cridtnya=fd($crinya);
		if ($modenya=="cari_list_bppb") 
		{$sql_mat=" and matclass='$crimat' ";} 
		else 
		{$sql_mat=" and sudah_detail='N' ";}
		$sql="select concat(bppbno,'|',a.id_item) isi,concat(bppbno,'|',itemdesc) 
			tampil from bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			inner join masteritem d on a.id_item=d.id_item
			where bppbdate>='$cridtnya' $sql_mat 
			group by bppbno,a.id_item order by bppbno";
		IsiCombo($sql,'','Pilih Nomor SJ');
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
					{	$id_so=$_REQUEST['id_so'];
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
					{	$cnya="Qty"; $ket="D";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
						echo "<th width='10%'>$cnya &nbsp &nbsp &nbsp &nbsp &nbsp Item</th>";
					} 
					else 
					{	$cnya=$defsat; $ket="R";
						echo "
						<th width='4%'>No.</th>
						<th width='4%'>Lot #</th>
						<th width='4%'>Actual</th>
						<th width='4%'>Konv</th>
						<th width='4%'>FOC</th>
						<th width='8%'>Barcode</th>
						<th width='8%'>No. Rak</th>
						
						<th width='4%'>No.</th>
						<th width='4%'>Lot #</th>
						<th width='4%'>Actual</th>
						<th width='4%'>Konv</th>
						<th width='4%'>FOC</th>
						<th width='8%'>Barcode</th>
						<th width='8%'>No. Rak</th>";
					}
				echo "</tr>";
			echo "</thead>";
			if ($modenya=="view_list_size")
			{	echo "<tr>"; show_roll_size(1,5,$crinya,$ket); echo "</tr>";
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
			{	echo "<tr>"; show_roll(1,5,$crinya,$ket); echo "</tr>";
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
			{	for ($x = 1; $x <= 200; $x++) 
      	{ echo "<tr>"; show_roll($x,$x+1,$crinya,$ket); echo "</tr>"; 
      		$x = $x + 1;
      	}
    	}
		echo "</table>";
	}
}

if ($modenya=="view_list_sj")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$arrnya=explode(":",$crinya);
		$nm_tbl=$arrnya[0];
		$bppbno=$arrnya[1];
		if ($nm_tbl=="bpb") {$nm_fld="bpbno"; $add_cri=",update_dok_pab";$bc_no=",bcno"; } else {$nm_fld="bppbno"; $add_cri="";$bc_no=""; }
		$sql="select $nm_fld trans_no,a.id_jo,a.id_item,s.mattype,s.goods_code,s.itemdesc,s.color,a.qty,
			a.unit,a.confirm $add_cri $bc_no from $nm_tbl a inner join masteritem s 
			on a.id_item=s.id_item where $nm_fld='$bppbno' 
			order by s.mattype desc ";
		echo "<table style='width: 100%;' id='examplefix2'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>No</th>";
					echo "<th>Kode</th>";
					echo "<th>Deskripsi</th>";
					echo "<th>Qty SJ</th>";
					echo "<th>Satuan</th>";
					if($logo_company=="Z")
					{	echo "<th>Sesuai/Tidak</th>";	}
					else
					{	if($nm_tbl=="bpb")
						{	echo "<th>Detail Roll</th>";	
							echo "<th>Update Dok</th>";	
							echo "<th>No. Bc.</th>";
						}
						echo "<th>Konfirmasi</th>";	
					}
				echo "</tr>";
			echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id = $data['id_item'];
					echo "<tr>";
						echo "<td>$i</td>";
						echo "<td>$data[goods_code]</td>";
						echo "<td>$data[itemdesc]</td>";
						echo "<td>$data[qty]</td>";
						echo "<td>$data[unit]</td>";
						if ($data['confirm']=="Y")
						{ echo "<td>Confirmed</td>"; }
						else
						{ if($nm_tbl=="bpb" and $logo_company=="S")
							{	$cekroll=flookup("bpbno","bpb_roll_h","bpbno='$data[trans_no]' and id_jo='$data[id_jo]' 
									and id_item='$data[id_item]'");
								if($cekroll=="") { $cekroll="N/A"; } else { $cekroll="Ok"; }
								if($data['update_dok_pab']=="Y" || $data['bcno'] !="") { $upddok="Ok";$bcno_saya=$data['bcno'];  } else { $upddok="N/A";$bcno_saya="-"; }
								echo "<td>$cekroll</td>";
								echo "<td>$upddok</td>";	
								echo "<td>$bcno_saya</td>";
								if(($cekroll=="Ok" and $upddok=="Ok") or $data['mattype']=="N" or $data['mattype']=="C")
								{	$statchk=""; }
								else
								{	$statchk="onclick='return false'"; }
							}
							else
							{	$statchk=""; }
							echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' $statchk></td>"; 
						}
					echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}

if ($modenya=="view_list_qc")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bppbno=$crinya;
		$sql="select a.id_item,s.goods_code,s.itemdesc,s.color,a.qty,
			a.unit,a.dicekqc,kpno,pono from bpb a inner join masteritem s 
			on a.id_item=s.id_item where bpbno='$bppbno' 
			order by s.mattype desc ";
		echo "<table style='width: 100%;' id='examplefix'>";
			echo "
				<thead>
					<tr>
						<th>No</th>
						<th>Nomor WS</th>
						<th>Nomor PO</th>
						<th>Kode</th>
						<th>Deskripsi</th>
						<th>Qty BPB</th>
						<th>Satuan</th>
						<th>Qty QC</th>
						<th>Status</th>
					</tr>
				</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id = $data['id_item'];
					echo "<tr>";
						echo "
							<td>$i</td>
							<td>$data[kpno]</td>
							<td>$data[pono]</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' size='4' name ='itemstock[$id]' value='$data[qty]' id='stockajax$i' class='form-control' readonly></td>
							<td>$data[unit]</td>
							<td><input type ='text' size='4' name ='itemqc[$id]' id='qcajax$i' class='form-control'></td>
							<td>
								<select style='width: 100%;' name='cbostatus[$id]' class='form-control select2 staclass'>
              		<option></option>";
              		if ($data['dicekqc']=="Y")
              		{ echo "<option selected>Pass</option>"; 
              			echo "<option>Reject</option>";
              		} else if ($data['dicekqc']=="R")
              		{ echo "<option>Pass</option>"; 
              			echo "<option selected>Reject</option>";
              		} else
              		{ echo "<option>Pass</option>"; 
              			echo "<option>Reject</option>";
              		}
              	echo "	
              	</select>
							</td>";
					echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}

if ($modenya=="view_list_sj_ri" or $modenya=="view_list_sj_ri_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bppbno=$crinya;
		if($modenya=="view_list_sj_ri_fg")
		{ $tblmst="masterstyle";	
			$flddesc="s.itemname";
		}
		else
		{ $tblmst="masteritem";	
			$flddesc="s.itemdesc";
		}
		$sql="select a.id_so_det,a.id_jo,a.id_item,s.goods_code,$flddesc itemdesc,s.color,a.qty,
			a.unit,a.confirm from bppb a inner join $tblmst s 
			on a.id_item=s.id_item where bppbno='$bppbno' 
			order by a.id_item desc ";
		echo "
		<table style='width: 100%;' id='examplefix2'>
			<thead>
				<tr>
					<th>No</th>
					<th>JO #</th>
					<th>Kode</th>
					<th>Deskripsi</th>
					<th>Qty SJ</th>
					<th>Satuan</th>
					<th>Qty RI</th>
				</tr>
			</thead>";
			$i=1;
			$query=mysql_query($sql);
			while($data=mysql_fetch_array($query))
			{	if($modenya=="view_list_sj_ri")
				{	$jonya = flookup("jo_no","jo","id='$data[id_jo]'"); }
				else
				{	$jonya = flookup("jo_no","so_det a inner join so on a.id_so=so.id 
						inner join jo_det d on so.id=d.id_so inner join jo 
						on d.id_jo=jo.id","a.id='$data[id_so_det]'"); 
				}
				$id = $data['id_item'];
				echo "
				<tr>
					<td>$i</td>
					<td>$jonya</td>
					<td>$data[goods_code]</td>
					<td>$data[itemdesc]</td>
					<td><input type ='text' size='4' name ='itemstock[$id]' value='$data[qty]' id='stockajax$i' 
						class='form-control qtysjclass' readonly></td>
					<td>$data[unit]</td>
					<td>
						<input type ='text' size='4' name ='item[$id]' id='itemajax' class='form-control itemclass'>
						<input type ='hidden' size='4' name ='jo[$id]' id='joajax' class='joclass' value='$data[id_jo]'>
					</td>
				</tr>";
				$i++;
			};
		echo "</table>";
	}
}

/* if ($modenya=="view_list_sj_ro")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bpbno=$crinya;
		$sql="select a.id_jo,a.id_item,s.goods_code,s.itemdesc,s.color,a.qty,
			a.unit from bpb a inner join masteritem s 
			on a.id_item=s.id_item where bpbno='$bpbno' 
			order by s.mattype desc ";
		echo "<table style='width: 100%;' id='examplefix'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>No</th>";
					echo "<th>Kode</th>";
					echo "<th>Deskripsi</th>";
					echo "<th>Qty BPB</th>";
					echo "<th>Satuan</th>";
					echo "<th>Qty RO</th>";
				echo "</tr>";
			echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id = $data['id_item'].":".$data['id_jo'];
					echo "<tr>";
						echo "
							<td>$i</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' size='4' name ='itemstock[$id]' value='$data[qty]' 
								id='stockajax$i' class='qtysjclass' readonly></td>
							<td>$data[unit]</td>
							<td><input type ='text' size='4' name ='item[$id]' id='itemajax' class='itemclass'></td>
							";
					echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}
 */
 
if ($modenya=="view_list_sj_ro")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bpbno=$crinya;
		$sql="SELECT ROUND((a.qty -ifnull(O.qty_nya,0)-ifnull(RO_EXIST.qty,0) ),2)qty , O.qty_nya ,a.id_jo,a.id_item,s.goods_code,s.itemdesc,s.color,a.qty q_in,
			a.unit from bpb a 
			inner join masteritem s 
			on a.id_item=s.id_item
			LEFT JOIN(
			SELECT bppbno_int,SUM(qty)qty_nya, id_item,MAX(id_jo)id_jo,id_supplier FROM bppb WHERE 1=1 
				AND id_supplier ='432'  
				AND bppbno_int NOT LIKE '%WIP%' AND bppbno_int  NOT LIKE '%FG%' AND bppbno_int NOT LIKE '%GEN%'
				GROUP BY id_jo,id_item
			)O ON a.id_jo = O.id_jo AND a.id_item = O.id_item
			LEFT JOIN(
			SELECT SUM(qty)qty,bpbno_ro,id_jo,id_item FROM bppb WHERE bppbno  LIKE '%R%'
GROUP BY bpbno_ro,id_jo,id_item
			
			
			)RO_EXIST ON a.bpbno =RO_EXIST.bpbno_ro AND a.id_jo = RO_EXIST.id_jo AND a.id_item = RO_EXIST.id_item
			where bpbno='$bpbno' ";
			
		echo "<table style='width: 100%;' id='examplefix'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>No</th>";
					echo "<th>Kode</th>";
					echo "<th>Deskripsi</th>";
					echo "<th>Qty BPB</th>";
					echo "<th>Satuan</th>";
					echo "<th>Qty RO</th>";
				echo "</tr>";
			echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id = $data['id_item'].":".$data['id_jo'];
					echo "<tr>";
						echo "
							<td>$i</td>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' size='4' name ='itemstock[$id]' value='$data[qty]' 
								id='stockajax$i' class='qtysjclass' readonly></td>
							<td>$data[unit]</td>
							<td><input type ='text' size='4' name ='item[$id]' id='itemajax' class='itemclass'></td>
							";
					echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}
if ($modenya=="cari_qty_bpb")
{	$crinya = $_REQUEST['cri_item'];
	$cek_arr=explode("|",$crinya);
	$bpbno=$cek_arr[0];
	$id_item=$cek_arr[1];
	$sql="select concat(mi.itemdesc,'--',mi.add_info,' ',ifnull(color,''),' ',ifnull(size,'')) item,
		nomor_rak,sum(bpb.qty) qtybpb,bpb.unit unitbpb,konv.unit2 unitkonv,konv.qty1,konv.
		qty2,pono,supplier,ac.kpno    
		from bpb inner join mastersupplier msu on bpb.id_supplier=msu.id_supplier 
		inner join masteritem mi on bpb.id_item=mi.id_item 
		inner join jo_det jod on bpb.id_jo=jod.id_jo 
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

if ($modenya=="cari_qty_sj")
{	$crinya = $_REQUEST['cri_item'];
	$cek_arr=explode("|",$crinya);
	$bpbno=$cek_arr[0];
	$id_item=$cek_arr[1];
	$sql="select nomor_rak,sum(qty) qtybpb,unit unitbpb,konv.unit2 unitkonv,konv.qty1,konv.qty2   
		from bppb left join konversi_satuan konv on bppb.id_item=konv.id_item 
		where bppbno='$bpbno' and bppb.id_item='$id_item' 
		group by bppbno ";
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
			$konvnya
		)
	);
}

if ($modenya=="view_list_kpno")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$id_item_fg=$crinya;
		$sql="select a.id_item_bb,s.goods_code,s.itemdesc,a.cons,
			round(a.allowance*100) allow 
			,(d.qty*a.cons)+((d.qty*a.cons)*a.allowance) qtypr 
			,d.qty,a.satuan from bom a inner join masteritem s 
			on a.id_item_bb=s.id_item inner join masterstyle d
			on a.id_item_fg=d.id_item where d.id_item='$id_item_fg' 
			order by s.mattype desc ";
		echo "<table style='width: 100%;' id='example1'>";
			echo "<thead>";
				echo "<tr>";
					echo
						"<th width='5%'>No</th>	
						<th width='10%'>Bahan Baku</th>
						<th width='10%'>Qty Order</th>
						<th width='5%'>Cons</th>
						<th width='5%'>Satuan</th>
						<th width='5%'>Allow</th>
						<th width='5%'>Keb</th>
						<th width='5%'>Masuk</th>
						<th width='5%'>Keluar</th>
						<th width='5%'>Aksi</th>";
				echo "</tr>";
				echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	echo "<tr>";
						echo "<td width='5%'>$i</td>";
						echo "<td width='10%'>$data[goods_code] $data[itemdesc]</td>";
						echo "<td width='10%'>$data[qty]</td>";
						echo "<td width='5%'>$data[cons]</td>";
						echo "<td width='5%'>$data[satuan]</td>";
						echo "<td width='5%'>$data[allow] %</td>";
						echo "<td width='5%'>$data[qtypr]</td>";
						$bpb=flookup("sum(qty)","bpb","id_item_fg='$id_item_fg'
							and id_item='$data[id_item_bb]'");
						$bppb=flookup("sum(qty)","bppb","id_item_fg='$id_item_fg'
							and id_item='$data[id_item_bb]'");
						echo "<td width='5%'>$bpb</td>";
						echo "<td width='5%'>$bppb</td>";
						echo "<td width='5%'> 
							<a href='del_bom.php?idbb=$data[id_item_bb]&idfg=$id_item_fg'
								data-toggle='tooltip' title='Hapus'";?> 
	          		onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "<i class='fa fa-trash-o'></i></a></td>";
				  echo "</tr>";
					$i++;
				};
		echo "</table>";
	}
}
?>