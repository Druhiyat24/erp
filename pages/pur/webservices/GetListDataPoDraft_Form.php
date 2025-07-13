<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['id']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 	 A.id
		,A.draftno
		,E.kpno ws
		,E.styleno
		,B.curr
		,F.Supplier nama_buyer
		,F_F.Supplier nama_supplier
		,IF(A.app ='W','WAITING',IF(A.app='A','APPROVED','CANCELED/REJECT'))status
		,G.kode_pterms
		,A.draftdate
		,A.id_supplier
		,A.id_terms
		,A.n_kurs
		,A.etd
		,A.eta
		,A.expected_date
		,A.notes
		,A.tax
		,A.jenis
		,A.ppn
		,A.pph
		,A.app
		,A.app_by
		,A.app_date
		,A.revise
		,A.username
		,A.discount
		,A.jml_pterms
		,A.id_dayterms
		,A.fg_pkp
		,A.po_over
		,A.po_close
			FROM po_header_draft A
			LEFT JOIN(SELECT id,id_po_draft,MAX(id_jo)id_jo,id_gen,curr FROM po_item_draft WHERE cancel = 'N' GROUP BY id_po_draft)B ON A.id = B.id_po_draft
			LEFT JOIN(SELECT * FROM jo_det WHERE cancel = 'N')C ON B.id_jo = C.id_jo
			LEFT JOIN(SELECT * FROM so )D ON C.id_so = D.id
			LEFT JOIN(SELECT * FROM act_costing)E ON E.id = D.id_cost
			LEFT JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'C')F ON E.id_buyer = F.Id_Supplier
			LEFT JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'S')F_F ON A.id_supplier = F_F.Id_Supplier
			LEFT JOIN(SELECT * FROM masterpterms)G ON G.id = A.id_terms
			WHERE A.jenis IN ('M','P') AND A.id= '{$id}'";
			//echo $q;
		$outp = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"draftno":"'.rawurlencode($row['draftno']).'",';
			$outp .= '"draftdate":"'. rawurlencode(date('d M Y', strtotime($row["draftdate"]))). '",'; 
			$outp .= '"jenis_item":"'. rawurlencode($row["jenis"]). '",';
			$outp .= '"supplier":"'. rawurlencode($row["id_supplier"]). '",';
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",';
			$outp .= '"id_terms":"'. rawurlencode($row["id_terms"]). '",';
			$outp .= '"jml_pterms":"'. rawurlencode($row["jml_pterms"]). '",';
			$outp .= '"id_dayterms":"'. rawurlencode($row["id_dayterms"]). '",';
			$outp .= '"etd":"'. rawurlencode(date('d M Y', strtotime($row["etd"]))). '",';
			$outp .= '"eta":"'. rawurlencode(date('d M Y', strtotime($row["eta"]))). '",';
			$outp .= '"expected_date":"'. rawurlencode(date('d M Y', strtotime($row["expected_date"]))). '",';
			$outp .= '"discount":"'. rawurlencode($row["discount"]). '",';
			$outp .= '"tax":"'. rawurlencode($row["tax"]). '",';
			$outp .= '"notes":"'. rawurlencode($row["notes"]). '",';
			$outp .= '"fg_pkp":"'. rawurlencode($row["fg_pkp"]). '",';
			$outp .= '"kurs":"'. rawurlencode($row["n_kurs"]). '"}'; 
		}	
		//POPULASI ID JO
		
		$q = "SELECT * FROM po_item_draft WHERE id_po_draft = '{$id}'";
			//echo $q;
		$populasi_jo = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($populasi_jo != "") {$populasi_jo .= ",";}
			$populasi_jo .= '{"id_jo":"'.rawurlencode($row['id_jo']).'"}'; 
		}			
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.'], "id_jo" : ['.$populasi_jo.'] }';
		return $result;
	}
}
?>




