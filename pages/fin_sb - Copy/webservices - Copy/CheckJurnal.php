<?php 

session_start();

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$data = $_POST;

$data = (object)$_POST;

// print_r($data);

if($data != ''){

	$getListData = new getListData();


$List = $getListData->get($data->iddatefrom,$data->iddateto,$data->tipejurnal,$data->stts);

print_r($List);



}

else{

	exit;

}

class getListData {

	public function bulan_periode($from){

		$bulan_periode = explode("/",$from."/01");

		$bulan_periode = $bulan_periode[1]."-".$bulan_periode[0]."-".$bulan_periode[2];

		$bulan_periode = date("Y-m-d", strtotime("-1 days",strtotime($bulan_periode)));	

		return $bulan_periode;

	}	

	public function bulan_periode1($to){

		$bulan_periode1 = explode("/",$to."/01");

		$bulan_periode1 = $bulan_periode1[1]."-".$bulan_periode1[0]."-".$bulan_periode1[2];

		$bulan_periode1 = date("Y-m-d", strtotime("-1 days",strtotime($bulan_periode1)));	

		return $bulan_periode1;

	}	

	public function tipejurnal($jurnal_type){

		$tj = $jurnal_type;

		return $tj;

	}

	public function status_jurnal($status){

		$stat = $status;

		return $stat;
	}

	

	public function get($from,$to,$jurnal_type,$status){

		include __DIR__ .'/../../../include/conn.php';

		$bulan_periode_from = $this->bulan_periode($from);
		$bulan_periode_to = $this->bulan_periode1($to);
		$jurnal_tipe = $this->tipejurnal($jurnal_type);
		$stats = $this->status_jurnal($status);

		// print_r($bulan_periode_from);

		// print_r($bulan_periode_to);

		// print_r($jurnal_tipe);

		// print_r($stats);


		$q = "SELECT fjh.type_journal
		, fjh.period 
		, fjh.id_journal
		, fjh.fg_post
		, fjh.date_journal
		, fjd.id_coa
		, fjd.nm_coa
		, fjd.debit AS debit
		, fjd.credit AS credit
		, fjh.remark
		, b.id_costcenter
		, b.nm_costcenter

		FROM fin_journal_h fjh 
		LEFT JOIN fin_journal_d fjd ON fjh.id_journal=fjd.id_journal
		LEFT JOIN fin_journalheaderdetail fj ON fj.v_idjournal=fjh.id_journal
		LEFT JOIN mastercostcategory a ON a.id_cost_category=fjd.id_cost_category
		LEFT JOIN mastercostcenter b ON b.id_cost_category=a.id_cost_category
		LEFT JOIN mastercostdept c ON c.id_cost_dept=b.id_cost_dept
		LEFT JOIN mastercostsubdept d ON d.id_cost_sub_dept=b.id_cost_sub_dept
		WHERE fjh.fg_post='$stats' 
		AND fjh.type_journal='$jurnal_tipe' 
		AND fjh.date_journal >= CAST('$bulan_periode_from' AS DATE) AND fjh.date_journal <= CAST('$bulan_periode_to' AS DATE)
		ORDER BY fjh.date_journal desc
			";	

			echo $q; //querynya

			$stmt = mysqli_query($conn_li,$q);

		if(mysqli_error($conn_li)){

			$result = '{ "status":"no", "message":"'.mysqli_error($conn_li).'" }';

			return $result;

		}		

		$outp = '';

		if(mysqli_num_rows($stmt) > 0 ){

			while($row = mysqli_fetch_array($stmt)){

					$status = 'OK';

				if ($outp != "") 
					{
						$outp .= ",";
					}

				$outp .= '{"status":"'.$status.'"}'; 

			}

		}else{

			$outp .= '{"status":"'.'NO'.'"}';

		}

		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';

		$result = '{ "status":"ok", "message":"1","records":['.$outp.'] }';

		return $result;

	}

}

?>











