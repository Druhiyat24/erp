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
		,B.curr
		,C.reqno
				,B_CANCEL.jlh_app
		,F_F.Supplier nama_supplier
		,IF(B_CANCEL.jlh_app IS NULL
			,'CANCELLED',
			IF(A.app ='W','WAITING',
				IF(A.app='A','APPROVED','CANCELED/REJECT')))status
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
		,A.d_start_installment
		,A.n_installment
		,A.fg_installment
			FROM po_header_draft A
			INNER JOIN(SELECT * FROM po_item_draft GROUP BY id_jo,id)B ON A.id = B.id_po_draft
			INNER JOIN(
SELECT A.id,B.jlh_app FROM po_header_draft A LEFT JOIN(
SELECT COUNT(*)jlh_app,id_po_draft FROM po_item_draft WHERE cancel = 'N' GROUP BY id_po_draft)B
ON A.id = B.id_po_draft)B_CANCEL ON B_CANCEL.id = A.id
			INNER JOIN(SELECT * FROM reqnon_header WHERE cancel_h = 'N')C ON B.id_jo = C.id
			INNER JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'S')F_F ON A.id_supplier = F_F.Id_Supplier
			INNER JOIN(SELECT * FROM masterpterms)G ON G.id = A.id_terms
			WHERE A.jenis IN ('N') AND A.id= '{$id}' GROUP BY A.id";
			//echo $q;
		$outp = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"draftno":"'.rawurlencode($row['draftno']).'",';
			$outp .= '"draftdate":"'. rawurlencode(date('d M Y', strtotime($row["draftdate"]))). '",'; 
			$outp .= '"reqno":"'. rawurlencode($row["reqno"]). '",';
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
			$outp .= '"d_installment":"'.(($row["d_start_installment"] == null || $row["d_start_installment"] <'2000-0101' ) ? '' :rawurlencode(date('d M Y', strtotime($row["d_start_installment"])))). '",';
			$outp .= '"n_installment":"'. rawurlencode($row["n_installment"]). '",';
			$outp .= '"fg_installment":"'. rawurlencode($row["fg_installment"]). '",';
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
		//POPULASI ID KODE DEPT
		$q = "SELECT A.*,C.kode_mkt FROM po_item_draft A 
	INNER JOIN reqnon_header B ON A.id_jo = B.id
	INNER JOIN userpassword C ON B.username = C.username
WHERE A.id_po_draft = '{$id}' GROUP BY C.kode_mkt";
			//echo $q;
		$populasi_kode_dept = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($populasi_kode_dept != "") {$populasi_kode_dept .= ",";}
			$populasi_kode_dept .= '{"kode_dept":"'.rawurlencode($row['kode_mkt']).'"}'; 
		}			
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.'], "id_jo" : ['.$populasi_jo.'], "kode_dept" : ['.$populasi_kode_dept.'] }';
		return $result;
	}
}
?>




