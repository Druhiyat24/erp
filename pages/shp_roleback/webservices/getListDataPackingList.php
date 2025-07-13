<?php 
//SHO_PRO_UNB_INV
session_start();

include "../../forms/fungsi.php";

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


  function flookup_new2($fld,$tbl,$criteria)
{	
include __DIR__ .'/../../../include/conn.php';
if ($fld!="" AND $tbl!="" AND $criteria!="")
	{	$quenya = "Select $fld as namafld from $tbl Where $criteria ";
		//$strsql = mysqli_query($quenya);
		$strsql = mysqli_query($conn_li,$quenya);
		if (!$strsql) { die($quenya. mysqli_error()); }
		$rs = mysqli_fetch_array($strsql);
		if (mysqli_num_rows($strsql)=='0')
		{$hasil="";}
		else
		{$hasil=$rs['namafld'];}
		return $hasil;
	}
};
  

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup_new2("SHO_PRO_UNB_INV","userpassword","username='$user'");
//echo $akses;
//die();
		$data = $_GET;

		//print_r($data);

//$data = (object)$_POST['data'];

$getListData = new getListData();

//if($data['code'] == '1' ){

$List = $getListData->get($_GET['type_invoice'],$akses);

print_r($List);

//}

//else{

//	exit;

//}

class getListData {

	function CompileQuery($query,$mode){

		include __DIR__ .'/../../../include/conn.php';

		$stmt = mysqli_query($conn_li,$query);	

		if(mysqli_error($conn_li)){

		

			$result = mysqli_error($conn_li)."__ERRTRUE";

			return $result;

		}	

		else{

			if($mode == "CRUD"){

				print_r($query);

				$result = '{ "status":"ok", "message":"1"}';

				return $result;

			}

			else{

				

				if(mysqli_num_rows($stmt) == '0' ){

					$result = '{ "status":"ok", "message":"2"}';

					return '0';

				}

				else{

					return $stmt;

				}

			}

		} 

	}	

	public function get($type_invoice,$akses){

		$q = "SELECT a.*

		,supplier

		,IF(a.n_typeinvoice='1', 'LOCAL', 'EXPORT')description

		,W.n_id id_ic 

		,ID.id_so_det

		,ACT.kpno
		,SINV.description des_status

		FROM invoice_header a inner join 

          mastersupplier ms on a.id_buyer=ms.id_supplier

		  LEFT JOIN invoice_commercial W ON W.n_idinvoiceheader = a.id

		LEFT JOIN invoice_detail ID ON ID.id_inv = a.id

		LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det

		LEFT JOIN so SO ON SOD.id_so = SO.id

		LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost	
		LEFT JOIN shp_ms_status_invoice SINV ON SINV.id = a.n_post

		WHERE

		a.n_typeinvoice = '$type_invoice'

		GROUP BY a.id;

		";

		//echo $q;

		$MyList = $this->CompileQuery($q,'SELECT');

		if($MyList == '0'){

			$result = '{ "status":"ok", "message":"2", "records":"0"}';

		}

		else{

			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);
					if($EXP[1]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}

			else{	

		$outp = '';

		$my_id = 1; 

		//echo $type_invoice;

 		while($row = mysqli_fetch_array($MyList)){

		$invno = ($row['n_post'] > 0 AND $row['n_post'] <=2 ? $row['invno'] : '');

		$invdate = ($row['n_post'] > 0 AND $row['n_post'] <=2 ? date('d M Y',strtotime($row['invdate'])) : '');

		$datepackinglist = (ISSET($row['date_paclist']) ?  date('d M Y',strtotime($row['date_paclist'])):$invdate );

		$button = '';

		//echo $type_invoice;

			if($row['n_typeinvoice'] == '1'){

				$mod = 'DeliveryOrderForm';

			}else{

				$mod = 'PackingListForm';

			}

			  if($row['n_typeinvoice'] == '1' ){



				  $urlpdf = "PdfInvoice.php";



				  $type_invoice = "LOCAL";



			  }else if($row['n_typeinvoice'] == '2' ){



				  $urlpdf = "PdfInvoice.php"; 



				  $type_invoice = "EXPORT";



			  }				

			

			

			if($row['n_post'] == '0' OR  $row['n_post'] == '11'){

			 $button .= " <a class='btn btn-success btn-s' href='../shp/?mod=$mod&noid=$row[id]&type_invoice=$row[n_typeinvoice]'

              data-toggle='tooltip' title='Edit'><i class='fa fa-pencil'></i></a>"; 	

			// $button .= " <a class='btn btn-info btn-s' href='webservices/postPackInv.php?id=$row[id]&part=PL&type_invoice=$row[n_typeinvoice]'

              //data-toggle='tooltip' title='Send'><i class='fa fa-send'></i></a>";   

            $button .= " <a class='btn btn-warning btn-s' href='$urlpdf?id=$row[id]&type=$type_invoice' 

                data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i></a></td>"; 			  

			}		
			if($row['n_post'] == '11'){
				if($akses == '1'){
				
		$button .="<a href='webservices/postPackInv.php?id=$row[id]&part=PL&type_invoice=$type_invoice&action=UNBLOCK' class='btn btn-primary'
                  data-toggle='tooltip' title='UnBlock'><i class='fa fa-unlock'></i>
                </a>";					
				}			  			
				
			}			

			if($row['n_post'] == '1' OR $row['n_post'] == '2'){

            $button .= " <a class='btn btn-warning btn-s' href='$urlpdf?id=$row[id]&type=$type_invoice' 

                data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i></a></td>"; 				

			}

			if ($outp != "") {$outp .= ",";}

			$outp .= '{"desc":"'.$row["description"].'",'; 

			$outp .= '"date":"'.$datepackinglist.'",'; 

			$outp .= '"nopacking":"'.$row["v_codepaclist"].'",'; 

			$outp .= '"invoice":"'.$invno.'",'; 

			$outp .= '"invoicedate":"'.$invdate.'",';

			$outp .= '"ws":"'.$row['kpno'].'",'; 

			$outp .= '"status_pl":"'.$row['des_status'].'",'; 

			$outp .= '"customer":"'.$row['supplier'].'",'; //des_status

			$outp .= '"userpost":"'.$row['v_userpost']." (".$row['d_insert'].")".'",'; 

			$outp .= '"action":"'.rawurlencode($button).'"}'; 

			$my_id++;

		} 		

			$result = '{"data":['.$outp.']}';	

			}		

		}

		return $result;

	}

}









?>









