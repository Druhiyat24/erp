<!DOCTYPE html>
<html>
<head>
	<title>Excel Marker Entry</title>
</head>
<body>
	<style type="text/css">
		body{
			font-family: sans-serif;
		}
		table{
			margin: 20px auto;
			border-collapse: collapse;
		}
		table th,
		table td{
			border: 1px solid #3c3c3c;
			padding: 3px 8px;
		}
		td{
			text-align: center;
			vertical-align: middle;
			font-weight: bold;
		}
		a{
			background: blue;
			color: #fff;
			padding: 8px 10px;
			text-decoration: none;
			border-radius: 2px;
		}
		.mini{
			font-weight: normal;
		}
	</style>

	<?php
	include '../../include/conn.php';

	$id_cost = $_GET['cost'];
	$color = $_GET['color'];
	$id_mark_entry = $_GET['url'];

	$current_date = date("Ymd");

	// header("Content-type: application/vnd-ms-excel");
	header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=Marker_Entry_".$current_date.".xls");


	$query = "SELECT 
			sd.id AS id_so_det,
			sd.id_so,
			s.id_cost,
			sd.size,
			sd.qty AS qty_so,
			sd.color,
			sup.Supplier AS buyer,
			ac.styleno AS style,
			ac.kpno AS ws
		FROM so_det AS sd
		INNER JOIN so AS s ON s.id = sd.id_so
		INNER JOIN act_costing AS ac ON ac.id = s.id_cost
		INNER JOIN mastersupplier AS sup ON sup.Id_Supplier = ac.id_buyer
		WHERE sd.cancel = 'N' AND s.cancel_h = 'N' AND sup.tipe_sup = 'c'
		AND s.id_cost = '{$id_cost}'
		AND sd.color = '{$color}'
	";
	

	$queryCount = "SELECT
			COUNT(sd.size) AS size
		FROM so_det AS sd
		INNER JOIN so AS s ON s.id = sd.id_so
		WHERE sd.cancel = 'N' AND s.cancel_h = 'N'
		AND s.id_cost = '{$id_cost}'
		AND sd.color = '{$color}'
	";
	

	$queryLoop = "SELECT 
			meg.id_group,
			meg.id_mark_entry,
			meg.color,
			med.id_panel,
			mp.nama_panel,
			med.id_item,
			mi.itemdesc
		FROM prod_mark_entry_group AS meg
		INNER JOIN prod_mark_entry_detail AS med ON med.id_mark = meg.id_mark_entry AND med.id_group = meg.id_group
		INNER JOIN masteritem AS mi ON mi.id_item = med.id_item
		INNER JOIN masterpanel AS mp ON mp.id = med.id_panel
		WHERE meg.id_mark_entry = '{$id_mark_entry}' AND meg.color = '{$color}' AND med.id_group_det = '1'
		GROUP BY meg.id_group
		ORDER BY med.id_panel ASC,meg.id_group ASC,med.id_group_det ASC
	";
	$sqlLoop = mysqli_query($conn_li,$queryLoop);
	while($loop = mysqli_fetch_array($sqlLoop)){

	?>

		<table border="1">
			<tr>
			<?php 
				$sqlHead = mysqli_query($conn_li,$queryCount);
				$head = mysqli_fetch_array($sqlHead);
				$headCol = $head['size'] + 13;
				// echo $headCol;die(); 
			?>
				<td colspan="<?php echo $headCol;?>" style="font-size: 22px !important;" ><b>PT NIRWANA ALABARE GARMENT<b></td>
			</tr>
			<tr>
				<td class="mini" colspan="2" style="background-color: #3399ff">SZ</td>
				<?php
				$sqlSize = mysqli_query($conn_li,$query);
				while($size = mysqli_fetch_array($sqlSize)){
				?>
					<td style="color: #0099ff"><?php echo $size['size'];?></td>
				<?php
				}
				?>
				<td colspan="11"><?php echo $loop['nama_panel'];?></td>
			</tr>
			<tr>
				<td class="mini" colspan="2" rowspan="2" style="background-color: #3399ff"><?php echo $color;?></td>
				<?php
				$sql2 = mysqli_query($conn_li,$query);
				while($qty = mysqli_fetch_array($sql2)){
				?>
					<td class="mini"><?php echo $qty['qty_so'];?></td>
				<?php
				}
				$sqlWS = mysqli_query($conn_li,$query);
				$ws = mysqli_fetch_array($sqlWS);
				?>
				<td colspan="11" rowspan="2">WS : <?php echo $ws['ws'];?></td>
			</tr>
			<tr>
				<?php
				$sql3 = mysqli_query($conn_li,$query);
				while($p = mysqli_fetch_array($sql3)){
				?>
					<td class="mini">100%</td>
				<?php
				}
				?>
			</tr>
			<tr>
				<td class="mini">QTY 100%</td>
				<td class="mini">TOTAL</td>
				<?php
				$sqlStyle = mysqli_query($conn_li,$query);
				$style = mysqli_fetch_array($sqlStyle);

				$headQty = 0;
				$sql4 = mysqli_query($conn_li,$query);
				while($qty2 = mysqli_fetch_array($sql4)){
				?>
					<td class="mini"><?php echo $qty2['qty_so'];?></td>
				<?php
					$headQty = $headQty + $qty2['qty_so'];
				}
				?>
				<td class="mini" colspan="2" rowspan="2" style="background-color: #3399ff"><?php echo $headQty;?></td>
				<td colspan="9" style="background-color: #0066ff;"><?php echo $style['style'];?></td>
			</tr>
			<tr>
				<td colspan="2">BUYER</td>
				<?php
				$sqlCount2 = mysqli_query($conn_li,$queryCount);
				$buy = mysqli_fetch_array($sqlCount2);
				$buyCol = $buy['size'];

				$sqlBuyer = mysqli_query($conn_li,$query);
				$buyer = mysqli_fetch_array($sqlBuyer);
				?>
				<td colspan="<?php echo $buyCol; ?>"><?php echo $buyer['buyer'];?></td>
				<td colspan="3">PRODUCTION CAD</td>
				<td colspan="3">CHIEF</td>
				<td colspan="3">MANAGER</td>
			</tr>
			<tr>
				<td colspan="2">PO</td>
				<?php
				$sqlCount = mysqli_query($conn_li,$queryCount);
				$po = mysqli_fetch_array($sqlCount);
				$poCol = $po['size'];
				
				$queryPO = mysqli_query($conn_li,"SELECT 
						poh.id,
						GROUP_CONCAT(DISTINCT poh.pono) AS pono
					FROM po_header AS poh
					INNER JOIN po_item AS poi ON poh.id = poi.id_po
					INNER JOIN prod_mark_entry_detail AS med ON med.id_jo = poi.id_jo
					INNER JOIN masteritem AS mi ON mi.id_item = med.id_item AND mi.id_gen = poi.id_gen
					WHERE med.id_cost = '{$id_cost}'
					GROUP BY med.id_mark"
				);
				$stmtPO = mysqli_fetch_array($queryPO);

				$panelSum = $loop['id_panel'];
				$itemSum = $loop['id_item'];
				$querySum = mysqli_query($conn_li,"SELECT 
						mes.gsm,
						mes.width,
						mes.allowance,
						mes.b_cons_kg
					FROM prod_mark_entry_sum AS mes
					WHERE mes.id_mark_entry = '{$id_mark_entry}'
					AND mes.id_panel = '{$panelSum}'
					AND mes.id_item = '{$itemSum}'
					AND mes.color = '{$color}'
				");
				$stmtSum = mysqli_fetch_array($querySum);
				?>
				<td colspan="<?php echo $poCol; ?>"><?php echo $stmtPO['pono'];?></td>
				<td>GSM</td>
				<td><?php echo $stmtSum['gsm'];?></td>
				<td colspan="3" rowspan="2"></td>
				<td colspan="3" rowspan="2"></td>
				<td colspan="3" rowspan="2"></td>
			</tr>
			<tr>
				<td colspan="2">FABRIC</td>
				<?php
				$sqlCount2 = mysqli_query($conn_li,$queryCount);
				$fb = mysqli_fetch_array($sqlCount2);
				$fbCol = $fb['size'];
				?>
				<td colspan="<?php echo $fbCol; ?>"><?php echo $loop['itemdesc'];;?></td>
				<td>WIDTH</td>
				<td><?php echo $stmtSum['width'];?></td>
			</tr>
			<tr>
				<td colspan="2">SIZE</td>
				<?php 
				$sqlSizeDet = mysqli_query($conn_li,$query);
				while($sizeDet = mysqli_fetch_array($sqlSizeDet)){
				?>
					<td style="color: #0099ff"><?php echo $sizeDet['size'];?></td>
				<?php
				}
				?>
				<td colspan="2" style="color: #0099ff">TOTAL</td>
				<td colspan="3">LENGTH (MARKER)</td>
				<td rowspan="2">LENGTH YARD</td>
				<td rowspan="2">SPREAD</td>
				<td rowspan="2">RESULT</td>
				<td rowspan="2">YDS</td>
				<td rowspan="2">CON'S/YARD</td>
				<td rowspan="2">CON'S/KG</td>
			</tr>
			<tr>
				<td colspan="2">QTY</td>
				<?php
				$totQty = 0;
				$sqlQtyDet = mysqli_query($conn_li,$query);
				while($qtyDet = mysqli_fetch_array($sqlQtyDet)){
				?>
					<td><?php echo $qtyDet['qty_so'];?></td>
				<?php
					$totQty = $totQty + $qtyDet['qty_so'];
				}
				?>
				<td colspan="2" style="color: #0099ff"><?php echo $totQty;?></td>
				<td>YDS</td>
				<td colspan="2">INCH</td>
			</tr>
			
			<?php
			$panel = $loop['id_panel'];
			$item = $loop['id_item'];

			$queryMarkDet = "SELECT 
					med.id_group,
					med.id_group_det,
					meg.spread,
					meg.unit_yds,
					meg.unit_inch
				FROM prod_mark_entry_detail AS med
				INNER JOIN prod_mark_entry_group AS meg ON meg.id_group = med.id_group
				WHERE med.id_mark = '{$id_mark_entry}' AND med.color = '{$color}' AND med.id_panel = '{$panel}' AND med.id_item = '{$item}'
				GROUP BY med.id_group
			";
			$sqlLoopDet = mysqli_query($conn_li,$queryMarkDet);

			$totLengthYard = 0;
			$totSpreadRight = 0;
			$totResult = 0;
			$totYDS = 0;
			$no = 1;
			while($loopDet = mysqli_fetch_array($sqlLoopDet)){

				$groupDet = $loopDet['id_group_det'];
				$sqlQuery = "SELECT 
						med.size,
						med.ratio,
						med.spread,
						med.kurang
					FROM prod_mark_entry_detail AS med
					WHERE med.id_mark = '{$id_mark_entry}' AND med.color = '{$color}' 
					AND med.id_panel = '{$panel}' AND med.id_item = '{$item}' AND med.id_group_det = '{$groupDet}'
					ORDER BY med.id_mark_detail ASC
				";

				$group = $loopDet['id_group'];
				$sqlQuery2 = "SELECT 
						meg.id_group,
						meg.unit_yds,
						meg.unit_inch,
						meg.spread
					FROM prod_mark_entry_group AS meg
					WHERE meg.id_group = '{$group}'
				";
				$groupUnit = mysqli_query($conn_li,$sqlQuery2);
				$groupRow = mysqli_fetch_array($groupUnit);

				$sqlQuery3 = "SELECT 
						SUM(med.spread) AS result
					FROM prod_mark_entry_detail AS med
					WHERE med.id_group = '{$group}'
				";
				$groupResult = mysqli_query($conn_li,$sqlQuery3);
				$result = mysqli_fetch_array($groupResult);
			?>
			<tr>
				<td colspan="2">RATIO <?php echo $no++;?></td>
				<?php
				$totRatio = 0;
				$gDet = mysqli_query($conn_li,$sqlQuery);
				while($qtyGDet = mysqli_fetch_array($gDet)){
				?>
					<td style="background-color: #ffff1a"><?php echo $qtyGDet['ratio'];?></td>
				<?php
					$totRatio = $totRatio + $qtyGDet['ratio'];
				}
				?>
				<td colspan="2" style="background-color: #ffff1a"><?php echo $totRatio;?></td>
				<td rowspan="3"><?php echo $groupRow['unit_yds'];?></td>
				<td colspan="2" rowspan="3"><?php echo $groupRow['unit_inch'];?></td>
				<?php
				$lengthYard = ($groupRow['unit_inch'] + 2) / 36 + $groupRow['unit_yds'];
				$yds = $groupRow['spread'] * $lengthYard;
				$consYds = $yds / $result['result'];

				$consKg = ($yds * 0.9144) * ($stmtSum['width'] * 0.0254) * ($stmtSum['gsm'] / $result['result']) / 1000;
				?>
				<td rowspan="3"><?php echo number_format($lengthYard, 3, ".", ",");?></td>
				<td rowspan="3"><?php echo $groupRow['spread'];?></td>
				<td rowspan="3"><?php echo $result['result'];?></td>
				<td rowspan="3"><?php echo number_format($yds, 1, ".", ",");?></td>
				<td rowspan="3"><?php echo number_format($consYds, 4, ".", ",");?></td>
				<td rowspan="3"><?php echo number_format($consKg, 3, ".", ",");?></td>
			</tr>
			<tr>
				<td>SPREAD</td>
				<td><?php echo $loopDet['spread'];?></td>
				<?php
				$totSpread = 0;
				$gDet2 = mysqli_query($conn_li,$sqlQuery);
				while($qtyGDet2 = mysqli_fetch_array($gDet2)){
				?>
					<td><?php echo $qtyGDet2['spread'];?></td>
				<?php
					$totSpread = $totSpread + $qtyGDet2['spread'];
				}
				?>
				<td colspan="2"><?php echo $totSpread;?></td>
			</tr>
			<tr>
				<td colspan="2">BALANCE</td>
				<?php
				$totBal = 0;
				$gDet3 = mysqli_query($conn_li,$sqlQuery);
				while($qtyGDet3 = mysqli_fetch_array($gDet3)){
				?>
					<td style="color: #ff0000;"><?php echo $qtyGDet3['kurang'];?></td>
				<?php
					$totBal = $totBal + $qtyGDet3['kurang'];
				}
				?>
				<td colspan="2" style="color: #ff0000;"><?php echo $totBal;?></td>
			</tr>
			<?php
				$totLengthYard = $totLengthYard + $lengthYard;
				$totSpreadRight = $totSpreadRight + $groupRow['spread'];
				$totResult = $totResult + $result['result'];
				$totYDS = $totYDS + $yds;
			}
			$totConsYds = $totYDS / $totResult;
			$totConsKg = ($totYDS * 0.9144) * ($stmtSum['width'] * 0.0254) * ($stmtSum['gsm'] / $totResult) / 1000;
			?>

			<tr>
				<td colspan="2" style="background-color: #0066ff;">TOTAL SIZE</td>
				<?php
				$panelEnd = $loop['id_panel'];
				$itemEnd = $loop['id_item'];
				$sqlEnd = "SELECT 
						med.id_mark_detail,
						med.id_mark,
						med.id_cost,
						med.id_item,
						med.id_panel,
						med.color,
						med.size,
						med.qty,
						SUM(med.spread) spread
					FROM prod_mark_entry_detail AS med
					LEFT JOIN mastersize AS msz ON msz.size = med.size
					WHERE med.id_cost='{$id_cost}' AND med.color = '{$color}'
					AND med.id_item = '{$itemEnd}' 
					AND med.id_panel = '{$panelEnd}'
					GROUP BY med.size
					ORDER BY msz.urut ASC,med.id_mark_detail ASC
				";
				$EndDet = mysqli_query($conn_li,$sqlEnd);

				$totEnd = 0;
				while($rowEnd = mysqli_fetch_array($EndDet)){
				?>
					<td style="background-color: #0066ff;"><?php echo $rowEnd['spread'];?></td>
				<?php
					$totEnd = $totEnd + $rowEnd['spread'];
				}
				?>
				<td colspan="2" style="background-color: #0066ff;"><?php echo $totEnd;?></td>
				<td rowspan="2" style="background-color: #0066ff;"></td>
				<td colspan="2" rowspan="2" style="background-color: #0066ff;"></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo number_format($totLengthYard, 3, ".", ",");?></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo $totSpreadRight;?></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo $totResult;?></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo number_format($totYDS, 1, ".", ",");?></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo number_format($totConsYds, 4, ".", ",");?></td>
				<td rowspan="2" style="background-color: #0066ff;"><?php echo number_format($totConsKg, 3, ".", ",");?></td>
			</tr>
			<tr>
				<td colspan="2" style="background-color: #0066ff;">BALANCE SIZE</td>
				<?php
				$EndDet2 = mysqli_query($conn_li,$sqlEnd);

				$totBalEnd = 0;
				while($rowEnd2 = mysqli_fetch_array($EndDet2)){
					$balEnd = $rowEnd2['spread'] - $rowEnd2['qty'];
				?>
					<td style="background-color: #0066ff;"><?php echo $balEnd;?></td>
				<?php
					$totBalEnd = $totBalEnd + $balEnd;
				}
				?>
				<td colspan="2" style="background-color: #0066ff;"><?php echo $totBalEnd;?></td>
			</tr>
			<tr>
				<?php 
				$sqlFoot = mysqli_query($conn_li,$queryCount);
				$foot = mysqli_fetch_array($sqlFoot);
				$footCol = $foot['size'] + 2;

				$totConsYdsEnd = $totConsYds * 1.030;
				$totConsKgEnd = $totConsKg * 1.030;
				?>
				<td colspan="<?php echo $footCol;?>">CAD CON'S BODY/KG</td>
				<td colspan="3">B-CON'S/KG</td>
				<td colspan="3">BALANCE</td>
				<td colspan="2">PERCENTAGE</td>
				<td>CONS <?php echo $stmtSum['allowance'];?> %</td>
				<td rowspan="2" style="background-color: #3399ff;"><?php echo number_format($totConsYdsEnd, 4, ".", ",");?></td>
				<td rowspan="2" style="background-color: #3399ff;"><?php echo number_format($totConsKgEnd, 3, ".", ",");?></td>
			</tr>
			<tr>
				<?php 
				$footEnd = mysqli_query($conn_li,$queryCount);
				$fe = mysqli_fetch_array($footEnd);
				$feCol = $fe['size'] + 2;

				$cadCons = $totConsKg * 1.030;
				$balCons = $stmtSum['b_cons_kg'] - $cadCons;
				$percentCons = $balCons / $stmtSum['b_cons_kg'] * 100;
				?>
				<td colspan="<?php echo $feCol;?>" rowspan="2"><?php echo number_format($cadCons, 3, ".", ",");?></td>
				<td colspan="3" rowspan="2"><?php echo $stmtSum['b_cons_kg'];?></td>
				<td colspan="3" rowspan="2"><?php echo number_format($balCons, 4, ".", ",");?></td>
				<td colspan="2" rowspan="2"><?php echo number_format($percentCons, 2, ".", ",");?> %</td>
				<td>1,030</td>
			</tr>
			<tr>
				<?php 
				$fabricNeeds = $totConsKgEnd * $headQty;
				?>
				<td colspan="3" style="background-color: #0066ff;">FABRIC NEEDS/KG : <?php echo number_format($fabricNeeds, 2, ".", ",");?></td>
			</tr>

		</table>
		<br><br><br><br>
	<?php
	}
	?>
</body>
</html>