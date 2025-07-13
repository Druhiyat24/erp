<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$logo_company=$rscomp["logo_company"];
	$jenis_company=$rscomp["jenis_company"];
	$gr_po_need_app=$rscomp["gr_po_need_app"];
$modenya = $_GET['modeajax'];

if ($modenya=="cari_list_sj_ri" or $modenya=="cari_list_sj_ri_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		if($modenya=="cari_list_sj_ri_fg")
		{	$crimat="a.bppbno like 'SJ-FG%' "; }
		else
		{	$crimat="a.bppbno not like 'SJ-FG%' "; }
		$sql="select bppbno isi,concat(if(bppbno_int!='',bppbno_int,bppbno),'|',supplier,'|',jenis_dok,'|',bcno,'|',ifnull(jo_no,'')) tampil from 
			bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
			left join so_det sod on a.id_so_det=sod.id 
			left join jo_det jod on sod.id_so=jod.id_so 
			left join jo on jo.id=jod.id_jo  
			where bppbdate>='$cridtnya' and $crimat   
			group by bppbno order by bppbno";
		IsiCombo($sql,'','Pilih Nomor SJ');
	}
}

if ($modenya=="view_list_sj_ri" or $modenya=="view_list_sj_ri_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bppbno=$crinya;
		if($modenya=="view_list_sj_ri_fg")
		{ $tblmst="masterstyle";	
			$flddesc="concat(s.itemname,' ',s.color,' ',s.size)";
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
?>