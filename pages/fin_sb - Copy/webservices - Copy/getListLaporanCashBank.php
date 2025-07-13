<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['from'],$_POST['to'],$_POST['idcashbank']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($from,$to,$id){
		$explode = explode(" ",$from);
		$from = $explode[2]."-".date('m', strtotime($explode[1]))."-".$explode[0];

		$explodes = explode(" ",$to); 
		$to = $explodes[2]."-".date('m', strtotime($explodes[1]))."-".$explodes[0];
		//$to = date("Y-m-d", strtotime($to));	
		//$explode = explode("/",$from);
		//$from = $explode[1]."-".$explode[0]."-01";
		//$explode = explode("/",$to);
		//$to = $explode[1]."-".$explode[0]."-31";		
//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$andwhere = "AND (id_coa >= '10100' AND id_coa <= '11012')";
		if($id != ""){
			$segment = substr($id,1,1);
			if($segment == '0'){
				$andwhere = "AND id_coa < '11000'";
			}
			else if($segment == '1'){
				$andwhere = "AND id_coa >= '11000' AND id_coa <='11012'";
			}
			
		}
		$q = "SELECT id_coa,nm_coa,post_to FROM mastercoa WHERE post_to IS NULL OR post_to ='' $andwhere;";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$myIdCoa = $row['id_coa'];
		
/*
		$td .="<tr id='Group$row[id_coa]'>"; 
		$td .="<td align='center'><i class='fa fa-plus' style='cursor:pointer' id='$row[id_coa]' onclick='getChild(this.id)'> </i></td>";	
		$td .="<td align='center'>$row[id_coa]</td>";
		$td .="<td >$row[nm_coa]</td>";
		$td .="<td align='center'></td>";		
		$td .="<td align='center'></td>"; 
		$td .="<td align='center'></td>";
		$td .="<td align='center'></td>";
		$td .="<td align='center'></td>";
		$td .="<td align='center'></td>";

		$td .= "</tr>";	
		*/
		if($segment == '0'){
		  $td .="<tr id='Group$row[id_coa]' style='display:none'>				"; 
         $td .="<td align='center'><i class='fa fa-plus' style='cursor:pointer' id='$row[id_coa]' onclick='getChild(this.id)'> </i></td>";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td>";
		  $td .="<td >										</td>";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>					    </td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
          $td .=" </tr>										";
		}
		else if($segment == '1'){
		  $td .="<tr id='Group$row[id_coa]' style='display:none'>				"; 
          $td .="<td align='center'><i class='fa fa-plus' style='cursor:pointer' id='$row[id_coa]' onclick='getChild(this.id)'> </i></td>";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td>";
		  $td .="<td >										</td>";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>					    </td> ";
		  $td .="<td align='center'>					    </td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
          $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
		  $td .="<td align='center'>						</td> ";
          $td .=" </tr>										";			
			
		}
		
		}
		$records[] 				= array();
			$result = '{ "status":"ok", "message":"1", "records":['.$myIdCoa.']    } <-|->'.$td;
		return $result;
	}
}




?>




