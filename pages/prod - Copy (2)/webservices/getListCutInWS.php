<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$url = $_GET['id_url'];
$List = $getListWS->get($url);
print_r($List);
// print_r($id_url);die();

class getListWS {
	public function get($url){
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$q1 = "SELECT 
					ac.id AS id_cost,
					CONCAT(ac.kpno, ' | ',ac.styleno, ' | ', req.bppbno) AS ws,
					req.bppbno AS req_no,
					j.id AS id_jo
				FROM act_costing AS ac 
				INNER JOIN so AS s ON s.id_cost = ac.id
				INNER JOIN so_det AS sd ON sd.id_so = s.id
				INNER JOIN bom_jo_item AS bji ON bji.id_so_det = sd.id
				INNER JOIN jo AS j ON j.id = bji.id_jo
				/*INNER JOIN (
					SELECT 
						bp.id,
						bp.bppbno,
						bp.id_jo,
						bp.id_item
					FROM bpb AS bp
					WHERE bppbno IS NOT NULL
				) AS bp ON bp.id_jo = j.id*/
				INNER JOIN (
					SELECT 
						pp.id,
						pp.id_item,
						pp.id_jo,
						pp.bppbno_req,
						pp.id_supplier,
						fac.factory
					FROM bppb AS pp
					INNER JOIN (
						SELECT 
							fac.Id_Supplier AS id_factory,
							fac.Supplier AS factory,
							fac.tipe_sup
						FROM mastersupplier AS fac
						WHERE fac.area = 'F'
					) AS fac ON fac.id_factory = pp.id_supplier
					WHERE pp.confirm = 'Y' AND pp.jenis_dok = 'INHOUSE'
					AND fac.factory = 'Production - Cutting'
				) AS pp ON #pp.id_item = bp.id_item AND 
				pp.id_jo = j.id
				INNER JOIN masteritem MS_ITEM ON MS_ITEM.id_item = pp.id_item
				INNER JOIN masterdesc MS_DESC ON MS_DESC.id = MS_ITEM.id_gen AND MS_DESC.id = bji.id_item
				INNER JOIN mastercolor MS_COLOR ON MS_COLOR.id = MS_DESC.id_color
				INNER JOIN masterweight MS_WEIGHT ON MS_WEIGHT.id = MS_COLOR.id_weight
				INNER JOIN masterlength MS_LENGTH ON MS_LENGTH.id = MS_WEIGHT.id_length
				INNER JOIN masterwidth MS_WIDTH ON MS_WIDTH.id = MS_LENGTH.id_width
				INNER JOIN mastercontents MS_CONTENT ON MS_CONTENT.id = MS_WIDTH.id_contents
				INNER JOIN mastertype2 MS_TYPE ON MS_TYPE.id = MS_CONTENT.id_type
				INNER JOIN mastersubgroup MS_SUB ON MS_SUB.id = MS_TYPE.id_sub_group
				INNER JOIN mastergroup MS_GROUP ON MS_GROUP.id = MS_SUB.id_group
				INNER JOIN (
					SELECT 
						req.bppbno,
						req.id_jo,
						req.id_item
					FROM bppb_req AS req
				) AS req ON req.bppbno = pp.bppbno_req AND req.id_jo = pp.id_jo AND req.id_item = pp.id_item
				WHERE req.bppbno NOT IN (
					SELECT
						ci.request_no 
					FROM prod_cut_in AS ci WHERE ci.request_no IS NOT NULL 
				)
				AND MS_GROUP.id = '1'
				GROUP BY req.bppbno
				ORDER BY ac.id DESC
			";
			// echo $q;
			$stmt = mysql_query($q1);
		} else {
			$q2 = "SELECT 
					ci.id_cut_in,
					ci.id_act_costing AS id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno, ' | ', ci.request_no) AS ws,
					ci.request_no AS req_no,
					ci.id_jo 
				FROM prod_cut_in AS ci
				INNER JOIN (
					SELECT 
						ac.id,
						ac.styleno,
						ac.kpno
					FROM act_costing AS ac
				) AS ac ON ac.id = ci.id_act_costing
				WHERE ci.id_cut_in = '{$url}'
			";
			$stmt = mysql_query($q2);
		}
		

					// echo $q2;		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_cost":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '",';
			$outp .= '"req_no":"'. rawurlencode($row["req_no"]). '",';
			$outp .= '"id_jo":"'. rawurlencode($row["id_jo"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>