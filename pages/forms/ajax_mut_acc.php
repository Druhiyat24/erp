<?php
include "../../include/conn.php";
include "fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$modenya = $_GET['modeajax'];
	$user=$_SESSION['username'];
#echo $modenya;


	if ($modenya=="view_item")
	{ 
		$id_rak_loc = $_REQUEST['id_rak_loc'];	
		// $tgl_bpb = date("Y-m-d",strtotime($_REQUEST['tgl_bpb']));;	
		// join bpb/jo_det dibuang karena tidak dipakai untuk daftar item; CAST karena id_bpb_det varchar vs id bigint (biar key bisa dipakai)
		$sql_bpb = "select '' isi, 'Select Item' tampil union
          select x.id_item isi, CONCAT(x.id_item,' - ',x.itemdesc) tampil from (
            select bd.id_item, mi.itemdesc,
              SUM(COALESCE(bd.roll_qty,0) - COALESCE(b.qty_out,0) - COALESCE(bd.qty_mutasi,0)) qty
            from bpb_det bd
            inner join masteritem mi on bd.id_item = mi.id_item
            left join (select id_bpb_det, SUM(roll_qty) qty_out from bppb_det
                       where cancel = 'N' and id_rak_loc = '$id_rak_loc' group by id_bpb_det) b
              on b.id_bpb_det = CAST(bd.id AS CHAR)
            where bd.id_rak_loc = '$id_rak_loc'
            group by bd.id_item
          ) x where x.qty > 0";
		IsiCombo($sql_bpb,'','');
	
	}


	if ($modenya=="viewdetail" && $_REQUEST['id_item']=="")
	{
		// tanpa filter item query-nya bisa sampai menit-an di rak besar, jadi tabel baru dimuat setelah item dipilih
		echo "<div class='mutacc-empty'>Pilih Item terlebih dahulu untuk menampilkan data.</div>";
	}
	else if ($modenya=="viewdetail")
	{
		?>
		<div class="mutacc-table-wrap">
		<table id="data_mut" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
        <tr>
          <th style="width: 4%"><input type="checkbox" onchange="checkAll(this)" name="chk[]" ></th>
          <th style="width: 6%">ID</th>
          <th style="width: 6%">ID Item</th>
          <th style="width: 7%">Kode Item</th>
          <th style="width: 9%">Nama Item</th>
          <th style="width: 8%">Supplier</th>
          <th style="width: 8%">WS</th>
          <th hidden>Style</th>
          <th hidden>No JO</th>
          <th hidden>No PO</th>
          <th style="width: 8%">No BPB</th>
          <th style="width: 7%">Tgl BPB</th>
          <th style="width: 7%">Qty</th>
          <th style="width: 7%">Qty Mutasi</th>
          <th style="width: 7%">Unit</th>
          <th style="width: 8%">Rak </th>
          <th style="width: 8%">Rak Tujuan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $id_item = $_REQUEST['id_item'];
		$id_rak_loc = $_REQUEST['id_rak_loc'];
        $sql="select * from (select * from (select a.*,b.*,(COALESCE(a.roll_qty,0) - COALESCE(b.qty_out,0) - COALESCE(a.qty_mutasi,0)) qty_sisa from (select bd.*,
          bpb.bpbdate tgl_bpb, 
          jd.kpno, 
          mi.itemdesc, 
          mi.goods_code,
          mr.kode_rak, 
          concat(mr.kode_rak, ' ', mr.nama_rak)rak, 
          supplier,
          pono,
          invno,
          buyer,
          styleno
          from bpb_det bd
         left join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb 
         group by bpbno) bpb on bd.bpbno = bpb.bpbno
         inner join masteritem mi on bd.id_item = mi.id_item
				 left join (select ac.id_buyer, supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
				 inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc) jd on bd.id_jo = jd.id_jo
         inner join master_rak mr on bd.id_rak_loc = mr.id
         left join mastersupplier ms on bpb.id_supplier = ms.id_supplier 
         where bd.id_rak_loc = '$id_rak_loc' and bd.id_item = '$id_item'
         order by bpb.bpbdate asc) a left join
				 (select id_bpb_det,SUM(roll_qty) qty_out from bppb_det where cancel = 'N' GROUP BY id_bpb_det) b on b.id_bpb_det = a.id) a ) a where qty_sisa > 0 and id_item = '$id_item' ";
        // echo $sql;
        $opsi_rak = "";
        $sql_rak = mysql_query("select id, CONCAT(kode_rak,' ',nama_rak) nama_rak from master_rak where nama_rak like '%ACCESSORIES%' and aktif = 'Y'");
        while ($row = mysql_fetch_array($sql_rak)) {
          $opsi_rak .= '<option value="'.$row['id'].'">'.$row['nama_rak'].'</option>';
        }
        $i=1;
        $query=mysqli_query($con_new,$sql);
        while($data=mysqli_fetch_array($query))
        { 
          $id=$data['id'];
          // $sqlbpb="select supplier,unit from bpb a inner join mastersupplier s 
          //   on a.id_supplier=s.id_supplier where bpbno='$data[bpbno]' 
          //   and a.id_item='$data[id_item]' and a.id_jo='$data[id_jo]'";
          // $rsbpb=mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));
        //  <td><input type = 'number' style='width:50px;' max = '$data[sisa]' name ='itemqty[$id]' value = '$data[sisa]'></td>
          echo "
          <tr>
            <td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass'></td>
            <td>$data[id]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[supplier]</td>
            <td>$data[kpno]</td>
            <td hidden>$data[styleno]</td>
            <td hidden>$data[no_jo]</td>
            <td hidden>$data[pono]</td>
            <td>$data[bpbno_int]</td>
            <td>$data[tgl_bpb]</td>
            <td>$data[qty_sisa]</td>
            <td><input  class='form-control' type='number' step='any' min='0.01' max = '$data[qty_sisa]' name ='qty_mutasi[$id]' value = '$data[qty_sisa]' required></td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td> <select class='form-control select2' name='namarak[$id]' data-dropup-auto='false' data-live-search='true'>
                <option value='' disabled selected='true'>Select Rak</option>".$opsi_rak."</select></td>
          </tr>";
          $i++;
        };
        ?>
      </tbody>
    </table>
    </div>
    <?php

	}

	if ($modenya=="detail_hist")
	{
		$id = (int) $_REQUEST['id'];
		$h = function($s) { return ($s === null || trim($s) === '') ? '-' : htmlspecialchars($s); };
		$angka = function($n) { return number_format((float) $n, 2); };

		$mut = mysqli_fetch_assoc(mysqli_query($con_new, "select bd.*, mi.goods_code, mi.itemdesc
			from bpb_det bd left join masteritem mi on mi.id_item = bd.id_item
			where bd.id = '$id' and bd.no_mut like 'MUT/GACC/%'"));

		if (!$mut)
		{ echo "<div class='mutacc-empty'>Data mutasi tidak ditemukan.</div>"; }
		else
		{
			$id_src = (int) $mut['id_bpbdet'];
			$src = mysqli_fetch_assoc(mysqli_query($con_new, "select * from bpb_det where id = '$id_src'"));
			if (!$src) { $src = array('id_rak_loc' => 0, 'roll_qty' => 0, 'qty_mutasi' => 0); }

			$rak = array();
			$q = mysqli_query($con_new, "select id, concat(kode_rak,' ',nama_rak) rak from master_rak where id in ('".(int)$mut['id_rak_loc']."','".(int)$src['id_rak_loc']."')");
			while ($r = mysqli_fetch_assoc($q)) { $rak[$r['id']] = $r['rak']; }
			$rak_asal = isset($rak[$src['id_rak_loc']]) ? $rak[$src['id_rak_loc']] : '-';
			$rak_tujuan = isset($rak[$mut['id_rak_loc']]) ? $rak[$mut['id_rak_loc']] : '-';

			// id_bpb_det bertipe varchar, dibandingkan sebagai string supaya index-nya terpakai
			$out = array();
			$q = mysqli_query($con_new, "select id_bpb_det, sum(roll_qty) qty from bppb_det where id_bpb_det in ('$id','$id_src') and cancel = 'N' group by id_bpb_det");
			while ($r = mysqli_fetch_assoc($q)) { $out[$r['id_bpb_det']] = (float) $r['qty']; }
			$out_tujuan = isset($out[$id]) ? $out[$id] : 0;
			$out_asal = isset($out[$id_src]) ? $out[$id_src] : 0;

			$bpbno = mysqli_real_escape_string($con_new, $mut['bpbno']);
			$bpb = mysqli_fetch_assoc(mysqli_query($con_new, "select b.bpbdate, b.pono, b.invno, ms.supplier
				from bpb b left join mastersupplier ms on ms.id_supplier = b.id_supplier
				where b.bpbno = '$bpbno' limit 1"));

			$id_jo = mysqli_real_escape_string($con_new, $mut['id_jo']);
			$ws = mysqli_fetch_assoc(mysqli_query($con_new, "select ac.kpno, ac.styleno, mb.supplier buyer
				from jo_det jd inner join so on jd.id_so = so.id
				inner join act_costing ac on so.id_cost = ac.id
				left join mastersupplier mb on ac.id_buyer = mb.id_supplier
				where jd.id_jo = '$id_jo' and jd.cancel = 'N' limit 1"));

			$tgl_mut = ($mut['tgl_mut'] != "" && $mut['tgl_mut'] != "0000-00-00") ? date('d M Y', strtotime($mut['tgl_mut'])) : '-';
			$tgl_bpb = ($bpb && $bpb['bpbdate'] != "") ? date('d M Y', strtotime($bpb['bpbdate'])) : '-';
			$sisa_asal = (float) $src['roll_qty'] - $out_asal - (float) $src['qty_mutasi'];
			$sisa_tujuan = (float) $mut['roll_qty'] - $out_tujuan - (float) $mut['qty_mutasi'];
			?>
			<div class="mutacc-det-flow">
				<div class="mutacc-det-rak">
					<small>Rak Asal</small>
					<b><?= $h($rak_asal) ?></b>
				</div>
				<div class="mutacc-det-arrow">
					<i class="fa fa-long-arrow-right"></i>
					<span><?= $angka($mut['roll_qty']) ?> <?= $h($mut['unit']) ?></span>
				</div>
				<div class="mutacc-det-rak tujuan">
					<small>Rak Tujuan</small>
					<b><?= $h($rak_tujuan) ?></b>
				</div>
			</div>

			<div class="mutacc-det-section">
				<div class="mutacc-det-title">Info Mutasi</div>
				<div class="mutacc-det-grid">
					<div><span>No Mutasi</span><b><?= $h($mut['no_mut']) ?></b></div>
					<div><span>Tgl Mutasi</span><b><?= $tgl_mut ?></b></div>
					<div><span>User</span><b><?= $h($mut['user']) ?></b></div>
				</div>
			</div>

			<div class="mutacc-det-section">
				<div class="mutacc-det-title">Item</div>
				<div class="mutacc-det-grid">
					<div><span>ID Item</span><b><?= $h($mut['id_item']) ?></b></div>
					<div><span>Kode Item</span><b><?= $h($mut['goods_code']) ?></b></div>
					<div><span>Unit</span><b><?= $h($mut['unit']) ?></b></div>
					<div class="full"><span>Nama Item</span><b><?= $h($mut['itemdesc']) ?></b></div>
				</div>
			</div>

			<div class="mutacc-det-section">
				<div class="mutacc-det-title">Dokumen Penerimaan (BPB)</div>
				<div class="mutacc-det-grid">
					<div><span>No BPB</span><b><?= $h($mut['bpbno_int']) ?></b></div>
					<div><span>Tgl BPB</span><b><?= $tgl_bpb ?></b></div>
					<div><span>Supplier</span><b><?= $h($bpb ? $bpb['supplier'] : '-') ?></b></div>
					<div><span>No PO</span><b><?= $h($bpb ? $bpb['pono'] : '-') ?></b></div>
					<div><span>No Invoice / SJ</span><b><?= $h($bpb ? $bpb['invno'] : '-') ?></b></div>
					<div><span>WS</span><b><?= $h($ws ? $ws['kpno'] : '-') ?></b></div>
					<div><span>Style</span><b><?= $h($ws ? $ws['styleno'] : '-') ?></b></div>
					<div><span>Buyer</span><b><?= $h($ws ? $ws['buyer'] : '-') ?></b></div>
				</div>
			</div>

			<div class="mutacc-det-section">
				<div class="mutacc-det-title">Posisi Stok Saat Ini</div>
				<table class="mutacc-det-stok">
					<thead>
						<tr><th>Lokasi</th><th>Qty Masuk</th><th>Keluar</th><th>Dimutasi</th><th>Sisa</th></tr>
					</thead>
					<tbody>
						<tr>
							<td>Rak Asal <small><?= $h($rak_asal) ?></small></td>
							<td><?= $angka($src['roll_qty']) ?></td>
							<td><?= $angka($out_asal) ?></td>
							<td><?= $angka($src['qty_mutasi']) ?></td>
							<td><b><?= $angka($sisa_asal) ?></b></td>
						</tr>
						<tr>
							<td>Rak Tujuan <small><?= $h($rak_tujuan) ?></small></td>
							<td><?= $angka($mut['roll_qty']) ?></td>
							<td><?= $angka($out_tujuan) ?></td>
							<td><?= $angka($mut['qty_mutasi']) ?></td>
							<td><b><?= $angka($sisa_tujuan) ?></b></td>
						</tr>
					</tbody>
				</table>
				<p class="mutacc-det-note">Kolom "Dimutasi" di rak asal adalah total semua mutasi dari baris penerimaan ini, tidak hanya mutasi ini.</p>
			</div>
			<?php
		}
	}


?>