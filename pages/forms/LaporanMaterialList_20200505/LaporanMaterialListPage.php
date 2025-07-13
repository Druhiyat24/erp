<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(900);
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }



$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];
$sekarang = date("Y-m-d");

$froms = date("Y-m-d");
$tos = date('Y-m-d', strtotime('+7 days', strtotime($froms)));
$where = "";
if((ISSET($_GET['froms']) && (ISSET($_GET['tos'])) )){

	//print_r($_GET);


$explode = explode("/",$_GET['froms']);
$froms = $explode[2]."-".$explode[1]."-".$explode[0];
$explode = explode("/",$_GET['tos']);
$tos = $explode[2]."-".$explode[1]."-".$explode[0];
	
}
$where = "WHERE FROMPO.deldate >= '$froms' AND FROMPO.deldate <= '$tos'";
//echo $where; 



# END CEK HAK AKSES KEMBALI







?>

<div class="box">
<div class="box-body">
<div class="col-md-3">  
<!-- FILTER TABLE -->

	<label>Deliv Date From </label>
	<input type="text" class="form-control" autocomplete="off" id="froms" placeholder="Deliv Date From">
	<!-- FILTER TABLE -->
	</div>
	<div class="col-md-3">
	<!-- FILTER TABLE -->
	<label>Deliv Date To </label> 
	<input class="form-control" id="tos" autocomplete="off" placeholder="Deliv Date To">
	
	<!-- FILTER TABLE -->
	</div>
	<div class="col-md-3">
	<!-- FILTER TABLE -->
	<label>&nbsp;&nbsp;&nbsp;</label>
	<br/>
	<a href="#" class="btn btn-primary" onclick=searchDate() />Cari</a>
	
	<!-- FILTER TABLE -->
	</div>	 
	</div>
	</div>

<div class="box" >

	<div class="box-header">

		<h4>Material Status List</h4>

	</div>




	<div class="box-body" style="overflow:scroll;height:500px">


			<table id="example1"  class="display responsive" style="font-size:12px" >

				<thead>

					<tr >

						<tr>

							<th rowspan="2">No.</th>
							<th rowspan="2"># WS</th>
							<th rowspan="2">1st Garment Delivery</th>
							<th rowspan="2">ETA Fabric</th>
							<th colspan="3" class="text-center">Fabric</th>
							<th rowspan="2">ETA Acc. Sewing</th>
							<th colspan="3" class="text-center">Acc. Sewing</th>
							<th rowspan="2">ETA Acc. Packing</th>
							<th colspan="3" class="text-center">Acc. Packing</th>
							<th rowspan="2">RFPD</th>
						</tr>
						<tr>
							<th>Items Completed/Total Items</th>
							<th>Qty Completed/Qty Total</th>
							<th>(%)</th>
							<th>Items Completed/Total Items</th>
							<th>Qty Completed/Qty Total</th>
							<th>(%)</th>
							<th>Items Completed/Total Items</th>
							<th>Qty Completed/Qty Total</th>
							<th>(%)</th>	

						</tr>

					</thead>

					<tbody id="bodyexamle1">
  
						<?php

						error_reporting(E_ERROR | E_PARSE);

			
$sql ="


SELECT 
		FROMPO.id
        ,FROMPO.kpno
		,FROMBPB.bpbno
		,FROMPO.deldate
		,FROMPO.eta
		,DATEDIFF(FROMPO.deldate,FROMPO.eta) AS status_material
        ,FROMPO.po_fabric_qty
        ,FROMPO.po_sewing_qty
        ,FROMPO.po_packing_qty
        ,FROMPO.po_fabric_count
        ,FROMPO.po_sewing_count
        ,FROMPO.po_packing_count		
        ,ifnull(FROMBPB.bpb_fabric_qty,0 )bpb_fabric_qtyY
        ,ifnull(FROMBPB.bpb_sewing_qty,0 )bpb_sewing_qtyY
        ,ifnull(FROMBPB.bpb_packing_qty,0)bpb_packing_qtyY	


        ,ifnull(FROMBPB.bpb_fabric_count,0 )bpb_fabric_countY
        ,ifnull(FROMBPB.bpb_sewing_count,0 )bpb_sewing_countY
        ,ifnull(FROMBPB.bpb_packing_count,0)bpb_packing_countY	
		
        ,ifnull(RUNNING.running_fabric_count,0 )running_fabric_count
        ,ifnull(RUNNING.running_sewing_count,0 )running_sewing_count
        ,ifnull(RUNNING.running_packing_count,0)running_packing_count	

		,(ifnull(FROMBPB.bpb_fabric_qty,0 ) + ifnull(RUNNING.running_fabric_qty,0 ) )bpb_fabric_qty
		,(ifnull(FROMBPB.bpb_sewing_qty,0 ) + ifnull(RUNNING.running_sewing_qty,0 ) )bpb_sewing_qty
		,(ifnull(FROMBPB.bpb_packing_qty,0 ) + ifnull(RUNNING.running_packing_qty,0 ) )bpb_packing_qty
		,(ifnull(FROMBPB.bpb_fabric_count,0 ) + ifnull(RUNNING.running_fabric_count,0 ) )bpb_fabric_count
		,(ifnull(FROMBPB.bpb_sewing_count,0 ) + ifnull(RUNNING.running_sewing_count,0 ) )bpb_sewing_count
		,(ifnull(FROMBPB.bpb_packing_count,0 ) + ifnull(RUNNING.running_packing_count,0 ) )bpb_packing_count		
		
        ,FROMPO.item
        ,FROMPO.pono
        ,FROMPO.qty
FROM
	(
		SELECT
				JOINS.id
				,JOINS.kpno
				,JOINS.deldate
				,JOINS.eta
				,ifnull(sum(JOINS.po_fabric_qty),0)po_fabric_qty
				,ifnull(sum(JOINS.po_sewing_qty),0)po_sewing_qty
				,ifnull(sum(JOINS.po_packing_qty),0)po_packing_qty
				,ifnull(sum(JOINS.po_fabric_count),0)po_fabric_count
				,ifnull(sum(JOINS.po_sewing_count),0)po_sewing_count
				,ifnull(sum(JOINS.po_packing_count),0)po_packing_count				
				,JOINS.item
				,JOINS.pono
				,JOINS.qty
				FROM(
		SELECT  ACT.id,ACT.kpno,ACT.deldate,POH.eta
		,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(POI.qty,0),0)po_fabric_qty
				,if(MYITEM.kode_group = 'AS',ifnull(POI.qty,0),0)po_sewing_qty
				,if(MYITEM.kode_group = 'AP',ifnull(POI.qty,0),0)po_packing_qty

,if(MYITEM.kode_group = 'F' 
			,ifnull(1,0),0)po_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)po_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)po_packing_count

				
				
				,MYITEM.itemdesc item
				,POH.pono
				,POI.qty
		FROM act_costing ACT
			LEFT JOIN(
				SELECT id_cost,id FROM so
			)SO ON ACT.id = SO.id_cost
			LEFT JOIN(
				SELECT id_so,id_jo id_jos FROM jo_det 	)JOD ON SO.id = JOD.id_so
			LEFT JOIN(
				SELECT id,jo_no FROM jo 
			)JO ON JOD.id_jos = JO.id
			LEFT JOIN(
				SELECT id_po,id_jo,id_gen,qty FROM po_item
			)POI ON JO.id = POI.id_jo
			LEFT JOIN(
				SELECT pono,id,eta,app FROM po_header WHERE app = 'A'
			)POH ON POH.id = POI.id_po
			
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc,MS_ITEM.id_gen FROM mastergroup MS_GROUP
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
	INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON POI.id_gen = MYITEM.id_gen
GROUP BY ACT.kpno,MYITEM.id_item,POH.pono

			)JOINS GROUP BY JOINS.kpno
			
			)FROMPO
	
LEFT JOIN(

SELECT
		JOINS.id
		,JOINS.kpno
		,JOINS.bpbno
		,JOINS.pono
		,JOINS.bpbno_int	
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',1,0)ABC
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_fabric_qty),0))bpb_fabric_qty
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_sewing_qty),0))bpb_sewing_qty
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_packing_qty),0))bpb_packing_qty

		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_fabric_count),0))bpb_fabric_count
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_sewing_count),0))bpb_sewing_count
		,if(JOINS.bpbno_int IS NULL OR JOINS.bpbno_int = '',0,ifnull(sum(JOINS.bpb_packing_count),0))bpb_packing_count


		
		,JOINS.item
		,JOINS.qty
		FROM(
SELECT  ACT.id,ACT.kpno 
		,ifnull(BPB.bpbno_int,BPB.bpbno)bpbno
		,POH.pono
		,BPB.bpbno_int
		,if(MYITEM.kode_group = 'FK' 
		OR MYITEM.kode_group = 'F'
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(BPB.qty,0),0)bpb_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)bpb_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)bpb_packing_qty
		
,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(1,0),0)bpb_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)bpb_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)bpb_packing_count		
		
		,MYITEM.itemdesc item
		,BPB.qty
FROM act_costing ACT
	LEFT JOIN(
		SELECT id_cost,id FROM so
	)SO ON ACT.id = SO.id_cost
	LEFT JOIN(
		SELECT id_so,id_jo id_jos FROM jo_det 	)JOD ON SO.id = JOD.id_so
	LEFT JOIN(
		SELECT id,jo_no FROM jo 
	)JO ON JOD.id_jos = JO.id
	LEFT JOIN(
		SELECT id_po,id_jo,id_gen,qty FROM po_item
	)POI ON JO.id = POI.id_jo
	LEFT JOIN(
		SELECT pono,id FROM po_header
	)POH ON POH.id = POI.id_po
	LEFT JOIN(
		SELECT pono,bpbno_int,qty,id_item,bpbno FROM bpb 
	)BPB ON BPB.pono = POH.pono
	
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON BPB.id_item = MYITEM.id_item GROUP BY ifnull(BPB.bpbno_int,BPB.bpbno)
	)JOINS WHERE JOINS.bpbno_int IS NOT NULL OR JOINS.bpbno_int != '' GROUP BY JOINS.kpno
)FROMBPB ON FROMPO.kpno = FROMBPB.kpno

LEFT JOIN( 
		SELECT
				JOINS.id
				,JOINS.kpno
				,ifnull(sum(JOINS.running_fabric_qty),0)running_fabric_qty
				,ifnull(sum(JOINS.running_sewing_qty),0)running_sewing_qty
				,ifnull(sum(JOINS.running_packing_qty),0)running_packing_qty
				
				,sum(JOINS.running_fabric_count)running_fabric_count
				,sum(JOINS.running_sewing_count)running_sewing_count
				,sum(JOINS.running_packing_count)running_packing_count				
				FROM(

SELECT  ACT.id,ACT.kpno 
		,BPB.bpbno_int
		,BPB.bpbno
		,BPB.id_item
		,MYITEM.kode_group
		,if(MYITEM.kode_group = 'FK' 
		OR MYITEM.kode_group = 'F'
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
		,ifnull(BPB.qty,0),0)running_fabric_qty
		,if(MYITEM.kode_group = 'AS',ifnull(BPB.qty,0),0)running_sewing_qty
		,if(MYITEM.kode_group = 'AP',ifnull(BPB.qty,0),0)running_packing_qty
		
		
,if(MYITEM.kode_group = 'FK' 
			OR MYITEM.kode_group = 'F' 
			OR MYITEM.kode_group = 'FL' 
			OR MYITEM.kode_group = 'FP' 
			OR MYITEM.kode_group = 'FW'
			OR MYITEM.kode_group = 'FP'
			,ifnull(1,0),0)running_fabric_count
				,if(MYITEM.kode_group = 'AS',ifnull(1,0),0)running_sewing_count
				,if(MYITEM.kode_group = 'AP',ifnull(1,0),0)running_packing_count		
		,MYITEM.itemdesc item
		,BPB.qty
FROM act_costing ACT
	LEFT JOIN(
		SELECT id_cost,id FROM so
	)SO ON ACT.id = SO.id_cost
	LEFT JOIN(
		SELECT id_so,id_jo id_jos FROM jo_det 	)JOD ON SO.id = JOD.id_so
	LEFT JOIN(
		SELECT id,jo_no FROM jo 
	)JO ON JOD.id_jos = JO.id
	LEFT JOIN(
		SELECT pono,bpbno_int,qty,id_item,bpbno,id_po_item,id_jo FROM bpb 
	)BPB ON BPB.id_jo = JO.id
			LEFT JOIN(
SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc FROM mastergroup MS_GROUP
	INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
	INNER JOIN mastertype2 MS_TYPE 			ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
	INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
	INNER JOIN masterwidth MS_WIDTH 		ON MS_CONTENTS.id=MS_WIDTH.id_contents 
	INNER JOIN masterlength  MS_LENGTH		ON MS_WIDTH.id=MS_LENGTH.id_width
	INNER JOIN masterweight  MS_WEIGHT		ON MS_LENGTH.id=MS_WEIGHT.id_length
	INNER JOIN mastercolor  MS_COLOR		ON MS_WEIGHT.id=MS_COLOR.id_weight
	INNER JOIN masterdesc   MS_DESC			ON MS_COLOR.id=MS_DESC.id_color
		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id		
) MYITEM ON BPB.id_item = MYITEM.id_item
	WHERE left(BPB.bpbno,1) in ('A','F','B') and (BPB.id_po_item='' or BPB.id_po_item is null) and BPB.id_jo!='' 
	)JOINS  GROUP BY JOINS.kpno
)RUNNING ON FROMBPB.kpno = RUNNING.kpno
	$where 
						";
        					# QUERY TABLE
							//echo "$sql";
						$query = mysql_query($sql); 

						$no = 1;

						while($data = mysql_fetch_array($query)) {

							

					



echo "<tr>"; 

echo "<td>$no</td>"; 

echo "<td>$data[kpno]</td>";

echo "<td>".fd_view($data['deldate'])."</td>";

echo "<td>".fd_view($data['eta'])."</td>";

echo "<td>
		<div style='cursor:pointer;color:#3386FF' 

			data-toggle='modal' 

			data-target='.detmatstatcount' 
			data-backdrop='static' data-keyboard='false'
			onclick='getMasterLaporanMaterialCount(".'"'.$data[kpno].'"'.",".'"F"'.")'>
			".round($data['bpb_fabric_count'],0)."/".round($data['po_fabric_count'],0)."


		</div>
		</td>"; 

echo "<td>

		<div style='cursor:pointer;color:#3386FF' 

			data-toggle='modal' 
			
			data-target='.detmatstat' 
			data-backdrop='static' data-keyboard='false'
			onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"F"'.")'>
			".round($data['bpb_fabric_qty'],0)."/".round($data['po_fabric_qty'],0)." ";"


		</div>
   
	</td>";
echo "<td>".round(($data['bpb_fabric_qty']/$data['po_fabric_qty'])*100,2)."%</td>"; 		
	//eta fabric
echo "<td>".fd_view($data['eta'])."</td>";

echo "<td>
		<div style='cursor:pointer;color:#3386FF' 

			data-toggle='modal' 

			data-target='.detmatstatcount' 
			data-backdrop='static' data-keyboard='false'
			onclick='getMasterLaporanMaterialCount(".'"'.$data[kpno].'"'.",".'"AS"'.")'>
			".(round($data['bpb_sewing_count'],0))."/".round($data['po_sewing_count'],0)."
		</div>
		</td>"; 	
 
echo "<td>

		<div style='cursor:pointer; color:#3386FF' 

		data-toggle='modal' 

		data-target='.detmatstat' 
		data-backdrop='static' data-keyboard='false'
		onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"AS"'.")'>".round($data['bpb_sewing_qty'],0)."/".round($data['po_sewing_qty'],0)."

		</div>

	</td>";

echo "<td>".round(($data['bpb_sewing_qty']/$data['po_sewing_qty'])*100,2)."%</td>"; 	
	//eta sewing
echo "<td>".fd_view($data['eta'])."</td>";

echo "<td>
		<div style='cursor:pointer;color:#3386FF' 

			data-toggle='modal' 

			data-target='.detmatstatcount' 
			data-backdrop='static' data-keyboard='false'
			onclick='getMasterLaporanMaterialCount(".'"'.$data[kpno].'"'.",".'"AP"'.")'>
			".(round($data['bpb_packing_count'],0))."/".round($data['po_packing_count'],0)."
		</div>
		</td>"; 


echo "<td>
	<div style='cursor:pointer; color:#3386FF' data-toggle='modal' data-backdrop='static' data-keyboard='false' data-target='.detmatstat' onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"AP"'.")'>".round($data['bpb_packing_qty'],0)."/".round($data['po_packing_qty'],0)."
	</div></td>";
	//eta packing
echo "<td>".round(($data['bpb_packing_qty']/$data['po_packing_qty'])*100,2)."%</td>"; 

echo "<td>$data[status_material] Days</td>";

echo "</tr>";

							          $no++; // menambah nilai nomor urut

								

							 



							      }

							      ?>			  

							  </tbody>



							</table>





			

					</div>

				</div>



				<!-- Modal Detail Material -- FABRIC, ACC SEWING, ACC PACKING -->

<div class="modal fade detmatstat" role="dialog"> 

	<div class="modal-dialog modal-lg">

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title">Detail Material Qty</h4>

			</div>

			<div class="modal-body">

			<table id="example1a"  class="display responsive" style="width:100%;font-size:15px;" border="1">

					<thead style="background-color: #e6e6e6">

						<tr >

							<tr>

								<th>No.</th>

								

								<th>Po No.</th>

								<th>Nomor Trans</th>

								<th>Item Name</th>

								<th>Qty PO</th>
								<th>Qty BPB</th>

								<th>Unit PO</th>

							</tr>

						</tr>

					</thead>

					<tbody id="bodyexamle23">

						

							  </tbody>

							</table>
								<center>
				<img src="./images/loading.gif" id="myLoading" class="img-responsive" width="auto">
			</center>
						</div> <!-- end modal body -->

						<div class="modal-footer">

							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

						</div> <!-- end modal footer -->

					</div> <!-- end modal content -->

				</div>

		</div> <!-- end modal -->

		
<div class="modal fade detmatstatcount" role="dialog"> 

	<div class="modal-dialog modal-lg">

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title">Detail Material </h4>

			</div>

			<div class="modal-body">

			<table id="example1b"  class="display responsive" style="width:100%;font-size:15px;" border="1">

					<thead style="background-color: #e6e6e6">

						<tr >

							<tr>
								<th>No.</th>
								<th>Po No.</th>
								<th>Item Name</th>
								<th>PO</th>
								<th>BPB</th>
							</tr>

						</tr>

					</thead>

					<tbody id="bodyexamle234">

						

							  </tbody>

							</table>
								<center>
				<img src="./images/loading.gif" id="myLoading_2" class="img-responsive" width="auto">
			</center>
						</div> <!-- end modal body -->

						<div class="modal-footer">

							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

						</div> <!-- end modal footer -->

					</div> <!-- end modal content -->

				</div>

		</div> <!-- end modal -->		