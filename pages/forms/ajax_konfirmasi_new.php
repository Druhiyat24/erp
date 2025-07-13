<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rscomp["company"];
$st_company = $rscomp["status_company"];
$status_company = $rscomp["jenis_company"];
$jenis_company = $rscomp["jenis_company"];
$logo_company = $rscomp["logo_company"];
$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya == "view_list_tipe") {
	$tipe_konf 	= $_REQUEST['tipe_konf'];

	if ($tipe_konf == 'PENERIMAAN') {
		$sql = "select isi,tampil from (
			select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
						  when 'A' then 'ACCESSORIES'
						  when 'N' then 'GENERAL'
						  when 'C' then 'BARANG DALAM PROSES'
						  when 'S' then 'MAJUN'
						  end tampil
						  from masteritem where mattype in ('F','A','N','S','C')							
			)a
			union
			select 'FG' isi, 'BARANG JADI' tampil
			order by 
						  case isi when 'F' then '1'
						  when 'A' then '2'
						  when 'C' then '3'
						  when 'N' then '4'
						  when 'FG' then '5'
						  else '6'
						  end";
	} else if ($tipe_konf == 'PENGELUARAN') {
		$sql = "select isi,tampil from (
			select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
						  when 'A' then 'ACCESSORIES'
						  when 'N' then 'GENERAL'
						  when 'C' then 'BARANG DALAM PROSES'
						  when 'S' then 'MAJUN'
						  end tampil
						  from masteritem where mattype in ('F','A','N','S','C')							
			)a
			union
			select 'FG' isi, 'BARANG JADI' tampil
			order by 
						  case isi when 'F' then '1'
						  when 'A' then '2'
						  when 'C' then '3'
						  when 'N' then '4'
						  when 'FG' then '5'
						  else '6'
						  end";
	} else {
		$sql = "";
	}
	IsiCombo($sql, '', 'Pilih Tipe#');
}

if ($modenya == "view_list_data") {
	$critipe = $_REQUEST['id_tipe'];
	$critipe_konf = $_REQUEST['tipe_konf'];

	if ($critipe_konf == 'PENERIMAAN' && $critipe == 'A') {
		echo "<table style='width: 100%;' id='examplefix1'>
			 <thead>";
		echo "<tr>
					<th>No</th>
					<th>Nomor Input</th>
					<th>Tgl</th>
					<th>No PO</th>
					<th>Supplier</th>
					<th>No SJ</th>
					<th>Qty BPB</th>
					<th style='display:none;'>BPBno</th>
					<th>Qty Lok</th>
					<th>Username</th>
					<th>Stat Lokasi</th>
					<th>Stat Dok</th>
					<th>Check</th>					
				</tr>
			</thead>";
		$sql = "select bpb.bpbno,bpbno_int, bpbdate, sum(bpb.qty) qty_bpb,coalesce(sum(roll_qty),0) roll_qty,pono, invno, ms.supplier, bpb.username create_by, confirm, if(sum(bpb.qty) = sum(roll_qty),'Ok','N/A') stat_lokasi, 
			if (update_dok_pab = 'Y','Ok','N/A') stat_dok from bpb 
			inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
			left join (select bpbno,id_item,id_jo,sum(roll_qty)roll_qty from bpb_det group by bpbno,id_item,id_jo) bd on bpb.bpbno = bd.bpbno and bpb.id_item = bd.id_item and bpb.id_jo = bd.id_jo
			where bpb.bpbno like 'A%' and bpbdate >= '2023-02-01' and confirm = 'N'
			group by bpb.bpbno
			order by bpbdate asc";
		$i = 1;
		$query = mysql_query($sql);
		while ($data = mysql_fetch_array($query)) {
			$id = $data['bpbno'];
			$tgl = date('d-M-Y', strtotime($data['bpbdate']));
			$stat_lokasi = $data[stat_lokasi];
			$stat_dok = $data[stat_dok];

			if ($stat_lokasi == 'Ok' && $stat_dok == 'Ok') {
				$status = '';

				$bgcol = "style='color:blue;'";
			} else if ($stat_lokasi == 'N/A' && $stat_dok == 'N/A') {
				$status = 'disabled';

				$bgcol = "style='color:red;'";
			} else if ($stat_lokasi == 'N/A' && $stat_dok == 'Ok') {
				$status = 'disabled';
				$bgcol = "style='color:orange;'";
			} else if ($stat_lokasi == 'Ok' && $stat_dok == 'N/A') {
				$status = '';
				$bgcol = "style='color:green;'";
			}


			echo "<tr $bgcol>
						<td>$i</td>
						<td value = '$data[bpbno_int]'>$data[bpbno_int]</td>
						<td value = '$tgl'>$tgl</td>
						<td value = '$data[pono]'>$data[pono]</td>
						<td value = '$data[supplier]'>$data[supplier]</td>
						<td value = '$data[invno]'>$data[invno]</td>
						<td value = '$data[qty_bpb]'>$data[qty_bpb]</td>
						<td style='display:none;' value = '$data[bpbno]'>$data[bpbno]</td>
						<td>$data[roll_qty]</td>
						<td>$data[create_by]</td>
						<td>$data[stat_lokasi]</td>
						<td>$data[stat_dok]</td>
						<td><input type ='checkbox' name ='itemchk[$id]' id='itemchk$i' value = '$id' $status>
						<input type ='hidden' name ='bpbno[$id]' id='bpbno$i' value ='$id'>&nbsp;&nbsp;&nbsp;&nbsp;
							<i class='fa fa-search' aria-hidden='true' id= 'btndetail'></i>
						</td>											
						";
			echo "</tr>";
			$i++;
		};
		echo "</table>";
	} else if ($critipe_konf == 'PENGELUARAN' && $critipe == 'A') {
		echo "<table style='width: 100%;' id='examplefix1'>
			 <thead>";
		echo "<tr>
					<th>No</th>
					<th>Nomor Input</th>
					<th style='display:none;'>BPPBno</th>
					<th style='width:10%'>Tgl BPPB</th>
					<th style='width:10%'>Tgl Input</th>
					<th>Supplier</th>
					<th>Ket</th>
					<th style='display:none;'>po awal</th>
					<th style='display:none;'>bpb awal</th>
					<th style='display:none;'>cek data</th>
					<th>Qty BPB</th>
					<th>Qty Lok</th>
					<th>Username</th>
					<th>Stat Lokasi</th>
					<th>Stat Dok</th>
					<th>Check</th>					
				</tr>
			</thead>";
		$sql = "select a.bppbno, a.bppbno_int, bpb.pono po_awal,bpb.bpbno_int bpb_awal, a.bppbdate, ms.supplier, round(sum(qty),2) qty_bppb, round((b.roll_qty),2) roll_qty, a.username, a.dateinput, a.remark , a.cancel 
		,substring(a.bppbno,-2) cek_data,
		case substring(a.bppbno,-2)
		when '-R' then if(bcno = '','N/A','Ok')
		else
		'Ok' 
		end stat_dok,
		'Ok' stat_lokasi		
		FROM bppb a
		inner join (select bppbno, sum(roll_qty) roll_qty from bppb_det group by bppbno) b on a.bppbno = b.bppbno
		inner join mastersupplier ms on a.id_supplier = ms.id_supplier
		left join (select bpbno,bpbno_int,bppbno,pono from bpb where bpbdate >= '2022-01-01' group by bpbno) bpb on a.bpbno_ro = bpb.bpbno
		where a.bppbdate >= '2023-02-01' and substring(a.bppbno,4,1) = 'A'	and confirm = 'N'
		group by a.bppbno
		order by a.bppbdate asc";
		$i = 1;
		$query = mysql_query($sql);
		while ($data = mysql_fetch_array($query)) {
			$id = $data['bppbno'];
			$tgl = date('d-M-Y', strtotime($data['bppbdate']));
			$tgl_input = date('d-M-Y', strtotime($data['dateinput']));
			$cancel = $data[cancel];
			$stat_lokasi = $data[stat_lokasi];
			$stat_dok = $data[stat_dok];
			if ($cancel == 'Y') {
				$status = 'disabled';
				$bgcol = "style='color:red;'";
			} else if ($cancel == 'N') {
				$status = '';
				$bgcol = "style='color:blue;'";
			} else if ($stat_dok == 'N/A') {
				$status = '';
				$bgcol = "style='color:orange;'";
			}
			echo "<tr $bgcol>
						<td>$i</td>
						<td value = '$data[bppbno_int]'>$data[bppbno_int]</td>
						<td style='display:none;' value = '$data[bppbno]'>$data[bppbno]</td>
						<td value = '$tgl'>$tgl</td>
						<td value = '$tgl_input'>$tgl_input</td>
						<td value = '$data[supplier]'>$data[supplier]</td>
						<td value = '$data[remark]'>$data[remark]</td>
						<td style='display:none;' value = '$data[po_awal]'>$data[po_awal]</td>
						<td style='display:none;' value = '$data[bpb_awal]'>$data[bpb_awal]</td>
						<td style='display:none;' value = '$data[cek_data]'>$data[cek_data]</td>
						<td value = '$data[qty_bppb]'>$data[qty_bppb]</td>
						<td>$data[roll_qty]</td>
						<td>$data[username]</td>
						<td>$stat_lokasi</td>
						<td>$stat_dok</td>
						<td><input type ='checkbox' name ='itemchk[$id]' id='itemchk$i' value = '$id' $status>
						<input type ='hidden' name ='bppbno[$id]' id='bppbno$i' value ='$id'>&nbsp;&nbsp;&nbsp;&nbsp;
							<i class='fa fa-search' aria-hidden='true' id= 'btndetail_out'></i>
						</td>											
						";
			echo "</tr>";
			$i++;
		};
		echo "</table>";
	} else {
		echo "<table style='width: 100%;' id='examplefix1'>
			 <thead>";
		echo "<tr>
					<th>No</th>
					<th>Nomor Input</th>
					<th style='display:none;'>BPBno</th>
					<th>Tgl</th>
					<th>No PO</th>
					<th>Supplier</th>
					<th>No SJ</th>
					<th>Qty BPB</th>
					<th>Qty Lok</th>
					<th>Username</th>
					<th>Stat Lokasi</th>
					<th>Stat Dok</th>
					<th>Check</th>					
				</tr>
			</thead>";
		echo "</table>";
	}
}

echo "
  <div class='modal fade' id='mymodal' data-target='#mymodal' tabindex='-1' role='dialog' aria-labelledby='edit' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><span class='fa fa-times'></span></button>
          <h3 class='modal-title' id='txt_title'>Detail Dokumen</h3>
        </div>
        <div class='container-fluid'>
          <div class='row'>
            <div id='txt_tglbpb' class='col col-6' style='font-size: 12px; padding: 0.5rem;'></div>
            <div id='detail_rak' class='modal-body col-12' style='font-size: 12px; padding: 0.5rem;'></div>
          </div>
        </div>
      </div>

    </div>
  </div>";


?>


<script type='text/javascript'>
	$('table tbody tr').on('click', '#btndetail', function() {
		$('#mymodal').modal('show');
		var bpbno_int = $(this).closest('tr').find('td:eq(1)').attr('value');
		var tgl_bpb = $(this).closest('tr').find('td:eq(2)').attr('value');
		var no_po = $(this).closest('tr').find('td:eq(3)').attr('value');
		var supplier = $(this).closest('tr').find('td:eq(4)').attr('value');
		var no_sj = $(this).closest('tr').find('td:eq(5)').attr('value');
		var tot_qty = $(this).closest('tr').find('td:eq(6)').attr('value');
		var bpbno = $(this).closest('tr').find('td:eq(7)').attr('value');
		var tipe_mat = document.form.cbotipe.value;
		var tipe_konf = document.form.tipe_konf.value;
		var mode = 'bpb';
		// var date = $(this).closest('tr').find('td:eq(2)').text();
		// var supp = $(this).closest('tr').find('td:eq(4)').attr('value');
		// var refdoc = $(this).closest('tr').find('td:eq(3)').attr('value');
		// var akun = $(this).closest('tr').find('td:eq(5)').attr('value');
		// var desk = $(this).closest('tr').find('td:eq(7)').text();

		$.ajax({
			type: 'post',
			url: 'ajax_modal_konf.php',
			data: {
				bpbno_int: bpbno_int,
				tipe_mat: tipe_mat,
				tgl_bpb: tgl_bpb,
				no_po: no_po,
				supplier: supplier,
				no_sj: no_sj,
				tot_qty: tot_qty,
				bpbno: bpbno,
				tipe_konf: tipe_konf,
				mode: mode
			},
			success: function(data) {
				console.log(data);
				$('#detail_rak').html(data); //menampilkan data ke dalam modal
			}
		});
		//make your ajax call populate items or what even you need
		// $('#txt_bpb').html(bpbno);
		// $('#txt_tglbpb').html('Date : ' + tgl_bpb + '');
		// $('#txt_no_po').html('Supplier : ' + bpbno + '');
		// $('#txt_supp').html('Refference : ' + bpbno + '');
		// $('#txt_top').html('Kas Account : ' + bpbno + '');
		// $('#txt_curr').html('Currency : ' + bpbno + '');        
		// $('#txt_confirm').html('Status : ' + bpbno + '');
		// $('#txt_tgl_po').html('Description : ' + bpbno + '');                    
	});

	$('table tbody tr').on('click', '#btndetail_out', function() {
		$('#mymodal').modal('show');
		var bppbno_int = $(this).closest('tr').find('td:eq(1)').attr('value');
		var bppbno = $(this).closest('tr').find('td:eq(2)').attr('value');
		var tgl_bppb = $(this).closest('tr').find('td:eq(3)').attr('value');
		var supplier = $(this).closest('tr').find('td:eq(5)').attr('value');
		var remark = $(this).closest('tr').find('td:eq(6)').attr('value');
		var po_awal = $(this).closest('tr').find('td:eq(7)').attr('value');
		var bpb_awal = $(this).closest('tr').find('td:eq(8)').attr('value');
		var cek_data = $(this).closest('tr').find('td:eq(9)').attr('value');
		var tot_qty = $(this).closest('tr').find('td:eq(10)').attr('value');
		var tipe_mat = document.form.cbotipe.value;
		var tipe_konf = document.form.tipe_konf.value;
		var mode = 'bppb';

		$.ajax({
			type: 'post',
			url: 'ajax_modal_konf.php',
			data: {
				bppbno_int: bppbno_int,
				tipe_mat: tipe_mat,
				tgl_bppb: tgl_bppb,
				bppbno: bppbno,
				supplier: supplier,
				remark: remark,
				tipe_konf: tipe_konf,
				po_awal: po_awal,
				bpb_awal: bpb_awal,
				cek_data: cek_data,
				tot_qty: tot_qty,
				mode: mode
			},
			success: function(data) {
				console.log(data);
				$('#detail_rak').html(data); //menampilkan data ke dalam modal
			}
		});
	});
</script>