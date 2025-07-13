<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
	

$List = $getListData->get($_GET['id_so'],$_GET['color'],$_GET['id_number']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id,$color,$id_number){
	//print_r($id);
	//die();		
		include __DIR__ .'/../../../include/conn.php';
		$q = " SELECT A.id_jo,A.id_item,B.id_so, FROM bppb A
					INNER JOIN masteritem C ON A.id_item = B.id_item
				WHERE A.bppbno_req ='{$id_bppb}'
"; 
/*  echo $q;
die();    */
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$key_view = "0";  
		while($row = mysql_fetch_array($stmt)){
/* 			$q_view = $this->query_detail($row['id_jo'],$row['id_number'],$row['color'],$row['nama']);
			echo $q_view;
			die();
			$stmt_q = mysql_query($q_view);
				while($row_view = mysql_fetch_array($stmt_q)){
					$key_view = $row_view['jumlah'];
				}
				if(ISSET($key_view)){
					if($key_view > 0){
						$is_v =1;
					}else{
						$is_v =0;
					}
				}else{
					$is_v =0;
				}
 */
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"id":"'.rawurlencode($row['id']).'",';
				$outp .= '"isi":"'. rawurlencode($row["nama"]). '"}';				
			
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
	
	
	
	public function query_detail($id_jo,$id_number,$color,$bppbno_req){
		$where_query = "AND REQ.id_roll_det NOT IN(SELECT id_roll_det FROM prod_spread_report_detail WHERE id_number='{$id_number}')";	
		$det = "	
			SELECT IFNULL(count(*),0)jumlah FROM(
		
		SELECT 
			 REQ.loc_qty
			,REQ.id_roll_det
			,REQ.bppbno
			,REQ.qty_request
			,REQ.out_qty AS qty_bkb
			,BOM.*
			,REQ.lot_no AS lot
			,REQ.itemdesc
			,REQ.roll_no
			,SR.lembar_gelaran lembar_gelar
			,SR.sisa_gelar
			,SR.sambung_duluan_bisa
			,SR.sisa_tidak_bisa
			,SR.qty_reject_yds
			,SR.total_yds
			,SR.short_roll
			,SR.percent
			,SR.remark
		FROM (
			SELECT * FROM view_portal_bom WHERE id_jo ='{$id_jo}'
		)BOM
				INNER JOIN (
			SELECT 
				b.username
				,tbllok.id_roll_det
				,b.bppbno
				,b.id_item
				,b.id_jo
				,b.bppbdate
				,ac.kpno
				,ac.styleno
				,b.tanggal_aju
				,supplier tujuan
				,so.mindeldate as del_date
				,concat(mi.goods_code,' ',mi.itemdesc) itemdesc
				,mi.color
				,no_rak as location
				,qtyloc as loc_qty
				,unitloc as loc_unit
				,b.qty as qty_request
				,qtysdhout as out_qty
				,unitsdhout as out_unit
				,'' as check_picker
				,'' as check_loader
				,'' as check_penerima
				,b.remark
				,mi.id_gen
				,tbllok.lot_no   
				,tbllok.roll_no
			FROM bppb_req b
			INNER JOIN masteritem mi on b.id_item = mi.id_item 
			INNER JOIN mastersupplier msup on b.id_supplier=msup.id_supplier 
			INNER JOIN (
				SELECT 
					id_so,
					id_jo 
				FROM jo_det GROUP BY id_jo
			) jod ON b.id_jo=jod.id_jo 
			INNER JOIN (
				SELECT 
					so.id,
					id_cost,
					min(sod.deldate_det) mindeldate 
				FROM so 
				INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY so.id
			) so on jod.id_so=so.id 
			INNER JOIN act_costing ac ON so.id_cost=ac.id 
			LEFT JOIN (
				SELECT 
					tmplok.id_roll_det,
					id_item,
					id_jo,
					concat(kode_rak,' ',qtyloc,' ',unitloc) no_rak,
					(tmplok.qtyloc)qtyloc,
					tmplok.roll_no,
					'' unitloc,
					lot_no 
				FROM (
					SELECT 
						r.id id_roll_det,
						a.id_item,
						a.id_jo,
						d.kode_rak,
						r.roll_no,
						if(r.lot_no = '' OR r.lot_no IS NULL OR r.lot_no = '-', 'N/A', r.lot_no) lot_no,
						#round(sum(roll_qty),2) qtyloc,
						round((roll_qty),2) qtyloc,
						unit unitloc 
					FROM bpb_roll_h a 
					INNER JOIN bpb_roll r ON a.id=r.id_h 
					INNER JOIN master_rak d ON r.id_rak_loc=d.id  
					#GROUP BY id_item,id_jo,d.kode_rak
				) tmplok 
				#GROUP BY id_item,id_jo
			) tbllok ON b.id_item=tbllok.id_item AND b.id_jo=tbllok.id_jo 
			LEFT JOIN (
				SELECT 
					bppbno_req,
					id_item,
					id_jo,
					SUM(ifnull(qty,0)) qtysdhout,
					unit unitsdhout 
				FROM bppb WHERE bppbno_req = '{$bppbno_req}'
				AND id_supplier ='432'
				GROUP BY id_item,id_jo
			) tblsdhout ON tbllok.id_item = tblsdhout.id_item AND tbllok.id_jo = tblsdhout.id_jo AND b.bppbno=tblsdhout.bppbno_req
			WHERE 1=1 
		)REQ ON BOM.id_jo = REQ.id_jo AND REQ.id_gen = BOM.id_item 
		LEFT JOIN(
			SELECT id
					,id_roll_det
					,id_number
					,bppbno_req
					,id_jo
					,lembar_gelaran
					,sisa_gelar
					,sambung_duluan_bisa
					,sisa_tidak_bisa
					,qty_reject_yds
					,total_yds
					,short_roll
					,percent
					,remark
					FROM prod_spread_report_detail
					WHERE 
			 id_jo ='{$id_jo}' AND id_number='{$id_number}'
		)SR ON BOM.id_jo = SR.id_jo AND REQ.id_roll_det = SR.id_roll_det
		WHERE REQ.out_qty IS NOT NULL AND BOM.color ='{$color}'
		$where_query )X GROUP BY X.bppbno
";
	return $det;
	}
}




?>




