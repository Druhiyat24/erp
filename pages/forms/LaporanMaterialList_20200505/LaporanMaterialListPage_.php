<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }



$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];
$sekarang = date("Y-m-d");

$froms = date("Y-m-d");
$tos = date('Y-m-d', strtotime('+90 days', strtotime($froms)));
$where = "";
if((ISSET($_GET['froms']) && (ISSET($_GET['tos'])) )){

	//print_r($_GET);


$explode = explode("/",$_GET['froms']);
$froms = $explode[2]."-".$explode[1]."-".$explode[0];
$explode = explode("/",$_GET['tos']);
$tos = $explode[2]."-".$explode[1]."-".$explode[0];
	
}
$where = "WHERE FINAL.deldate >= '$froms' AND FINAL.deldate <= '$tos'";
//echo $where; 
# CEK HAK AKSES KEMBALI  
   
$akses = flookup("act_costing","userpassword","username='$user'");

if ($akses=="0") 

	{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

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

		<h4>Material List</h4>

	</div>




	<div class="box-body" >

		<div class="container" style="overflow:scroll">

			<table id="example1"  class="display responsive" style="width:100%;font-size:15px;" border="1">

				<thead style="background-color: #e6e6e6">

					<tr >

						<tr>

							<th>No.</th>
							<th># WS</th>
							<th>Costing Del. Date</th>
							<th>ETA</th>
							<th colspan="3">Fabric</th>
							<th colspan="3">Acc Sewing</th>
							<th colspan="3">Acc Packing</th>
							<th>Status Material</th>
						</tr>

					</thead>

					<tbody id="bodyexamle1">

						<?php

						error_reporting(E_ERROR | E_PARSE);

			

						$sql="SELECT 
		FINAL.kpno
		,FINAL.deldate
		,FINAL.etd
		,FINAL.eta			
		,FINAL.bpbno_int
        ,FINAL.pono
        ,FINAL.id_item
        ,FINAL.qty
        ,FINAL.itemdesc		
        ,FINAL.po_desc
        ,SUM(FINAL.fabric_qty) fabric_qty
        ,SUM(FINAL.sewing_qty) sewing_qty
        ,SUM(FINAL.packing_qty) packing_qty
        ,FINAL.type 
        ,FINAL.idjo 
        ,SUM(FINAL.po_fabric_qty) po_fabric_qty
        ,SUM(FINAL.po_sewing_qty) po_sewing_qty
        ,SUM(FINAL.po_packing_qty)  po_packing_qty
		,FINAL.desc_fabric
		,FINAL.desc_sewing
		,FINAL.desc_packing		
		,DATEDIFF(FINAL.deldate,FINAL.eta) AS status_material
		
		FROM (
	SELECT   LIKES.kpno
			,LIKES.deldate
			,LIKES.etd
			,LIKES.eta			
			,LIKES.bpbno_int
			,LIKES.pono
			,LIKES.id_item
			,LIKES.qty
			,LIKES.itemdesc		
			,LIKES.item po_desc
			,IF(LIKES.bpbno_int IS NULL OR LIKES.bpbno_int = '',0,LIKES.fabric_qty)fabric_qty
			,IF(LIKES.bpbno_int IS NULL OR LIKES.bpbno_int = '',0,LIKES.sewing_qty )sewing_qty 
			,IF(LIKES.bpbno_int IS NULL OR LIKES.bpbno_int = '',0,LIKES.packing_qty  )packing_qty 
			,LIKES.type 
			,LIKES.idjo 
			,LIKES.po_fabric_qty
			,LIKES.po_sewing_qty
			,LIKES.po_packing_qty
			,LIKES.desc_fabric
			,LIKES.desc_sewing
			,LIKES.desc_packing			
		
		FROM (
		SELECT 
			ACT.kpno
			,ACT.deldate
			,POH.etd
			,POH.eta
			,BPB.bpbno_int
			,BPB.pono
			,BPB.id_item
			,BPB.qty
			,MI.itemdesc
			,IF(MI.itemdesc LIKE '%FABRIC%','1','0') fabric
			,IF(MI.itemdesc LIKE '%SEWING%','1','0') sewing
			,IF(MI.itemdesc LIKE '%PACKING%','1','0') packing
			,IF(MI.itemdesc LIKE '%FABRIC%',BPB.qty,'0') fabric_qty
			,IF(MI.itemdesc LIKE '%SEWING%',BPB.qty,'0') sewing_qty
			,IF(MI.itemdesc LIKE '%PACKING%',BPB.qty,'0') packing_qty	
			,IF(MI.itemdesc LIKE '%FABRIC%',MI.itemdesc,'0')desc_fabric
			,IF(MI.itemdesc LIKE '%SEWING%',MI.itemdesc,'0')desc_sewing
			,IF(MI.itemdesc LIKE '%PACKING%',MI.itemdesc,'0') desc_packing			
			,IF(MI.itemdesc LIKE '%FABRIC%','F',
				IF(MI.itemdesc LIKE '%SEWING%','S',
				'P')
			) type			
			,JO.id idjo
			,POI.po_fabric_qty
			,POI.po_sewing_qty
			,POI.po_packing_qty
			,POI.item
			FROM bpb BPB
			LEFT JOIN(
				SELECT id_item,itemdesc FROM masteritem
			)MI ON BPB.id_item = MI.id_item
			LEFT JOIN(
				SELECT pono,id,etd,eta FROM po_header 
			)POH ON BPB.pono = POH.pono
			LEFT JOIN(
			
SELECT my_poi.id
		,my_poi.jo_no
		,my_poi.id_po
		,my_poi.item
		,my_poi.unit
		,my_poi.curr
		,my_poi.price
		,my_poi.cancel
		,my_poi.id_jo
		,my_poi.id id_mat_cs 
		,my_poi.po_fabric
		,my_poi.po_sewing
		,my_poi.po_packing
		,my_poi.po_fabric_qty
		,my_poi.po_sewing_qty
		,my_poi.po_packing_qty
FROM(		
select l.id
		,jo_no
		,l.id_po
		,concat(a.nama_group,' ',s.nama_sub_group,' ',
              d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
              g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,
              l.qty
		,l.unit
		,l.curr
		,l.price
		,l.cancel
		,l.id_jo
		,e.id id_mat_cs 
		,if(a.kode_group='FK' OR a.kode_group='FL' OR a.kode_group='FL' OR a.kode_group='FW' OR a.kode_group='FC','1','0') po_fabric
		,if(a.kode_group='AS' OR a.kode_group='AP','1','0') po_sewing
		,if(a.kode_group='AP' OR a.kode_group='AP','1',0) po_packing
		,if(a.kode_group='FK' OR a.kode_group='FL' OR a.kode_group='FL' OR a.kode_group='FW' OR a.kode_group='FC', l.qty,'0') po_fabric_qty
		,if(a.kode_group='AS' OR a.kode_group='AS', l.qty,'0') po_sewing_qty
		,if(a.kode_group='AP' OR a.kode_group='AP', l.qty,'0') po_packing_qty		
		
		
              from po_item l inner join jo m on l.id_jo=m.id 
              inner join mastergroup a 
			  inner join mastersubgroup s on a.id=s.id_group
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              inner join masterwidth f on e.id=f.id_contents 
              inner join masterlength g on f.id=g.id_width
              inner join masterweight h on g.id=h.id_length
              inner join mastercolor i on h.id=i.id_weight
              inner join masterdesc j on i.id=j.id_color 
              and l.id_gen=j.id 
			  )my_poi 
			
			)POI ON POH.id = POI.id_po
			LEFT JOIN(
				SELECT id,jo_no FROM jo 
			)JO ON POI.id_jo = JO.id
			LEFT JOIN(
				SELECT id,id_so,id_jo FROM jo_det
			)JOD ON JOD.id_jo =  JO.id	
			LEFT JOIN (
				SELECT id,id_cost FROM so
			)SO ON JOD.id_so = SO.id
			LEFT JOIN(
				SELECT id,kpno,deldate FROM act_costing
			)ACT ON SO.id_cost = ACT.id
			WHERE  (MI.itemdesc LIKE '%FABRIC%' OR MI.itemdesc LIKE '%SEWING%' OR MI.itemdesc LIKE '%PACKING%' )  ) LIKES WHERE LIKES.kpno IS NOT NULL )FINAL $where GROUP BY FINAL.kpno

						

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

echo "<td>".$data['fabric_qty']."/".$data['po_fabric_qty']."</td>"; 

echo "<td>

		<div style='cursor:pointer;color:#3386FF' 

			data-toggle='modal' 

			data-target='.detmatstat' 

			onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"F"'.")'>

			".$data['fabric_qty']."

		</div>

	</td>";

echo "<td>".round(($data['fabric_qty']/$data['po_fabric_qty'])*100,0)."%</td>"; 	

echo "<td>".($data['sewing_qty'])."/".$data['po_sewing_qty']."</td>"; 	
 
echo "<td>

		<div style='cursor:pointer; color:#3386FF' 

		data-toggle='modal' 

		data-target='.detmatstat' 

		onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"AS"'.")'>".$data['sewing_qty']."

		</div>

	</td>";

echo "<td>".round(($data['sewing_qty']/$data['po_sewing_qty'])*100,0)."%</td>"; 	

echo "<td>".($data['packing_qty'])."/".$data['po_packing_qty']."</td>"; 	

echo "<td><div style='cursor:pointer; color:#3386FF' data-toggle='modal' data-target='.detmatstat' onclick='getMasterLaporanMaterial(".'"'.$data[kpno].'"'.",".'"AP"'.")'>".$data['packing_qty']."</div></td>";

echo "<td>".round(($data['packing_qty']/$data['po_packing_qty'])*100,0)."%</td>"; 

echo "<td>$data[status_material] Days</td>";

echo "</tr>";

							          $no++; // menambah nilai nomor urut

								

							 



							      }

							      ?>			  

							  </tbody>



							</table>





						</div>

					</div>

				</div>



				<!-- Modal Detail Material -- FABRIC, ACC SEWING, ACC PACKING -->

<div class="modal fade detmatstat" role="dialog"> 

	<div class="modal-dialog modal-lg">

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title">Detail Material</h4>

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
