<?php
include "../../include/conn.php";
include "fungsi.php";
include "ro_acc_sumber.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }
$modenya = $_GET['modeajax'];

if ($modenya == "view_sumber")
{
	$bpbno = $_REQUEST['bpbno'];
	$h = function($s) { return htmlspecialchars($s); };
	$rows = array();
	foreach (ro_acc_sumber($con_new, $bpbno) as $r) { if ($r['sisa'] > 0) { $rows[] = $r; } }
	?>
	<div class="mutacc-table-wrap">
	<table id="tbl_ro_acc" class="display" style="width:100%;font-size:11px;">
		<thead>
			<tr>
				<th>No</th>
				<th>Asal Stok</th>
				<th>Rak</th>
				<th>ID Item</th>
				<th>Kode</th>
				<th>Deskripsi</th>
				<th>WS</th>
				<th>Sisa di Rak</th>
				<th>Satuan</th>
				<th>Qty RO</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		foreach ($rows as $r)
		{
			$id = (int) $r['id'];
			if ($r['asal'] == 'RI') { $asal = "<span class='roacc-badge ri'>RI</span> ".$h($r['bpbno_int']); }
			else if ($r['asal'] == 'MUTASI') { $asal = "<span class='roacc-badge mut'>Mutasi</span> ".$h($r['no_mut']); }
			else { $asal = "<span class='roacc-badge bpb'>BPB</span> ".$h($r['bpbno_int']); }
			$sisa = (float) $r['sisa'];
			echo "
			<tr>
				<td>$no</td>
				<td>$asal</td>
				<td>".$h($r['rak'])."</td>
				<td>".$h($r['id_item'])."</td>
				<td>".$h($r['goods_code'])."</td>
				<td>".$h($r['itemdesc'])."</td>
				<td>".$h($r['kpno'])."</td>
				<td class='roacc-num'>".number_format($sisa, 2)."</td>
				<td>".$h($r['unit'])."</td>
				<td><input class='form-control roacc-qty' type='number' step='any' min='0' max='$sisa' data-sisa='$sisa' name='qty_ro[$id]' value=''></td>
			</tr>";
			$no++;
		}
		?>
		</tbody>
	</table>
	</div>
	<?php
}
?>
