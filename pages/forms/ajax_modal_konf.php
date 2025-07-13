<?php
include "../../include/conn.php";
include "fungsi.php";

$mode = $_REQUEST['mode'];

if ($mode == 'bpb') {

	$bpbno_int = $_REQUEST['bpbno_int'];
	$bpbno = $_REQUEST['bpbno'];
	$tgl_bpb = $_REQUEST['tgl_bpb'];
	$no_po = $_REQUEST['no_po'];
	$supplier = $_REQUEST['supplier'];
	$no_sj = $_REQUEST['no_sj'];
	$tot_qty = $_REQUEST['tot_qty'];
	$totqty = $tot_qty;
	$tipe_konf = $_REQUEST['tipe_konf'];
	$tipe_mat_1 = $_REQUEST['tipe_mat'];


	if ($tipe_mat_1 == 'F') {
		$typemat = 'FABRIC';
	} elseif ($tipe_mat_1 == 'A') {
		$typemat = 'ACCESSORIES';
	} elseif ($tipe_mat_1 == 'N') {
		$typemat = 'GENERAL';
	} elseif ($tipe_mat_1 == 'C') {
		$typemat = 'BARANG DALAM PROSES';
	} elseif ($tipe_mat_1 == 'S') {
		$typemat = 'MAJUN';
	} else {
		$typemat = '';
	}

	if ($tipe_konf == 'PENERIMAAN') {
		$jenis_dok = 'BPB';
	} else if ($tipe_konf == 'PENGELUARAN') {
		$jenis_dok = 'BPPB';
	}
	echo "
	<table width = '100%'>
	<tr>
			<th id='txtnobpb' width = '50%'>Nomor $mode  : $bpbno_int</th>
			<th width = '50%'>Tipe Material : $typemat</th>
	</tr>
	<tr>
			<th width = '50%'>Tanggal $mode  : $tgl_bpb</th>
			<th width = '50%'>Nomor PO : $no_po</th>
	</tr>
	<tr>
			<th width = '50%'>Supplier : $supplier</th>
			<th width = '50%'>Nomor SJ : $no_sj</th>
	</tr>
	</table>

	<br>
	<div class='box'>
    <div class='box-body'>
	<form method='post' name='form1'>
   <table id='examplefix1' class='table table-striped table-bordered' cellspacing='0' width='100%' style='font-size: 12px;text-align:center;'>";
	echo "
			<thead>";
	echo "
				<tr>
					<th style = 'text-align:center;'>No WS</th>
					<th style = 'text-align:center;'>ID Item</th>
					<th style = 'text-align:center;'>Kode Barang</th>
					<th style = 'text-align:center;'>Nama Barang</th>
					<th style = 'text-align:center;'>Qty BPB</th>
					<th style = 'text-align:center;'>Qty Rak</th>
					<th style = 'text-align:center;'>Rak</th>
					<th style = 'text-align:center;'>Unit</th>
					<th style = 'text-align:center;'>Stat Lokasi</th>
					<th style = 'text-align:center;'>Stat Dok</th>
				</tr>
			</thead>";

	echo "<tbody>";

	$sql = "select a.bpbno, a.bpbno_int, ac.kpno,mi.id_item,mi.goods_code, mi.itemdesc,sum(a.qty)qty_bpb,coalesce(bd.roll_qty,0) roll_qty, a.unit , if(sum(a.qty) = bd.roll_qty,'Ok','N/A') stat_lokasi,if (update_dok_pab = 'Y','Ok','N/A') stat_dok, rak
			from bpb a 
			left join 
			(select ab.bpbno,ab.bpbno_int,ab.id_item,ab.id_jo,sum(ab.roll_qty) roll_qty,group_concat(' ', rak, ' ') rak from
			(
			select bd.bpbno,bd.bpbno_int,id_item,id_jo, sum(bd.roll_qty) roll_qty, 
						concat(kode_rak,' (', sum(bd.roll_qty), ')') rak from bpb_det bd 
						inner join master_rak mr on bd.id_rak_loc = mr.id
						where bd.bpbno = '$bpbno' group by id_item,id_jo, id_rak_loc
						order by kode_rak asc
			) ab
			group by id_item, id_jo	) bd on a.bpbno = bd.bpbno and a.id_item = bd.id_item and a.id_jo = bd.id_jo
			inner join masteritem mi on a.id_item = mi.id_item
			inner join jo_det jd on a.id_jo = jd.id_jo
			inner join so on jd.id_so = so.id
			inner join act_costing ac on so.id_cost = ac.id 
			where a.bpbno = '$bpbno'
			group by a.id_item, a.id_jo";

	#echo $sql; 

	$i = 1;
	$query = mysql_query($sql);
	while ($data = mysql_fetch_array($query)) {
		echo "
					<tr>
						<td>$data[kpno]</td>
						<td>$data[id_item]</td>
						<td>$data[goods_code]</td>
						<td style = 'text-align:left;'>$data[itemdesc]</td>
						<td>$data[qty_bpb]</td>
						<td>$data[roll_qty]</td>
						<td>$data[rak]</td>
						<td>$data[unit]</td>
						<td>$data[stat_lokasi]</td>
						<td>$data[stat_dok]</td>
					</tr>";

		$i++;
	};
	echo "
					<tr>
						<td colspan = '4'>Total </td>
						<td>$totqty</td>
						<td colspan = '3'></td>
					</tr>";
	echo "</tbody>
	</table>
	</form>
	</div>
	</div>";
} else if ($mode == 'bppb') {

	$bppbno_int = $_REQUEST['bppbno_int'];
	$bppbno = $_REQUEST['bppbno'];
	$tgl_bppb = $_REQUEST['tgl_bppb'];
	$tipe_konf = $_REQUEST['tipe_konf'];
	$tipe_mat_1 = $_REQUEST['tipe_mat'];
	$supplier = $_REQUEST['supplier'];
	$remark = $_REQUEST['remark'];
	$po_awal = $_REQUEST['po_awal'];
	$bpb_awal = $_REQUEST['bpb_awal'];
	$cek_data = $_REQUEST['cek_data'];
	$tot_qty = $_REQUEST['tot_qty'];
	$totqty = $tot_qty;

	if ($tipe_mat_1 == 'F') {
		$typemat = 'FABRIC';
	} elseif ($tipe_mat_1 == 'A') {
		$typemat = 'ACCESSORIES';
	} elseif ($tipe_mat_1 == 'N') {
		$typemat = 'GENERAL';
	} elseif ($tipe_mat_1 == 'C') {
		$typemat = 'BARANG DALAM PROSES';
	} elseif ($tipe_mat_1 == 'S') {
		$typemat = 'MAJUN';
	} else {
		$typemat = '';
	}

	echo "
	<table width = '100%'>
	<tr>
			<th id='txtnobpb' width = '50%'>Nomor BPPB  : $bppbno_int</th>
			<th width = '50%'>Tipe Material : $typemat</th>
	</tr>
	<tr>
			<th width = '50%'>Tanggal BPPB  : $tgl_bppb</th>
			<th width = '50%'>Nomor PO : $po_awal</th>
	</tr>
	<tr>
			<th width = '50%'>Supplier : $supplier</th>
			<th width = '50%'>Nomor SJ : $no_sj</th>
	</tr>
	<tr>
			<th width = '50%'>Ket : $remark</th>
			<th width = '50%'>BPB Awal : $bpb_awal</th>
	</tr>	
	</table>

	<br>
	<div class='box'>
    <div class='box-body'>
	<form method='post' name='form1'>
   <table id='examplefix1' class='table table-striped table-bordered' cellspacing='0' width='100%' style='font-size: 12px;text-align:center;'>";
	echo "
			<thead>";
	echo "
				<tr>
					<th style = 'text-align:center;'>No WS</th>
					<th style = 'text-align:center;'>ID Item</th>
					<th style = 'text-align:center;'>Kode Barang</th>
					<th style = 'text-align:center;'>Nama Barang</th>
					<th style = 'text-align:center;'>Qty BPB</th>
					<th style = 'text-align:center;'>Qty Rak</th>
					<th style = 'text-align:center;'>Rak</th>
					<th style = 'text-align:center;'>Unit</th>";
	if ($cek_data == '-R') {
		echo "<th style = 'text-align:center;'>Stat Lokasi</th>
					<th style = 'text-align:center;'>Stat Dok</th>";
	}
	echo "		</tr>
			</thead>";

	echo "<tbody>";

	$sql = "select a.bppbno,
	a.bppbno_int,
	a.bppbno_req,
	a.bppbdate,
	a.id_item,
	sum(a.qty) qty_bppb,
	coalesce(bd.roll_qty,0) roll_qty,
	a.remark,
	a.unit,
	ac.kpno,
	mi.itemdesc,
	mi.goods_code,
	case substring(a.bppbno,-2)
	when '-R' then if(bcno = '','N/A','Ok')
	else
	'Ok' 
	end stat_dok,
	'Ok' stat_lokasi,
	rak
	from bppb a
	left join
	(select ab.bppbno,ab.bppbno_int,ab.id_item,ab.id_jo,sum(ab.roll_qty) roll_qty,group_concat(' ', rak, ' ') rak from
		(
			select bd.bppbno,bd.bppbno_int,id_item,id_jo, sum(bd.roll_qty) roll_qty, 
							concat(kode_rak,' (', sum(bd.roll_qty), ')') rak from bppb_det bd 
							inner join master_rak mr on bd.id_rak_loc = mr.id
							where bd.bppbno = '$bppbno' group by id_item,id_jo, id_rak_loc
							order by kode_rak asc
		) ab
		group by id_item, id_jo	)  bd on a.bppbno = bd.bppbno and a.id_item = bd.id_item and a.id_jo = bd.id_jo
		inner join masteritem mi on a.id_item = mi.id_item
		inner join jo_det jd on a.id_jo = jd.id_jo
		inner join so on jd.id_so = so.id
		inner join act_costing ac on so.id_cost = ac.id 
		where a.bppbno = '$bppbno'
		group by a.id_item, a.id_jo	";

	#echo $sql;

	$i = 1;
	$query = mysql_query($sql);
	while ($data = mysql_fetch_array($query)) {
		echo "
					<tr>
						<td>$data[kpno]</td>
						<td>$data[id_item]</td>
						<td>$data[goods_code]</td>
						<td style = 'text-align:left;'>$data[itemdesc]</td>
						<td>$data[qty_bppb]</td>
						<td>$data[roll_qty]</td>
						<td>$data[rak]</td>
						<td>$data[unit]</td>";
		if ($cek_data == '-R') {
			echo "<td>$data[stat_lokasi]</td>
						<td>$data[stat_dok]</td>";
		}
		echo "	</tr>";

		$i++;
	};
	echo "
					<tr>
						<td colspan = '4'>Total </td>
						<td>$totqty</td>
						<td colspan = '3'></td>
					</tr>";
	echo "</tbody>
	</table>
	</form>
	</div>
	</div>";
}
