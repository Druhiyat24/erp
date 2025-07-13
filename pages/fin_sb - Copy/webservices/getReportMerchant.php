<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];

if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['from'],$_POST['to']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($from,$to){
		//echo $from;
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-31";		
//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$q = "
SELECT id_coa,nm_coa,post_to FROM mastercoa WHERE post_to IS NULL OR post_to =''  AND (id_coa >= 10100 AND id_coa <= 11012)";
//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
	
		

		$td .="<tr>"; 
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
		}
echo "TD:".$td;
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;
		return $result;




	}
?>




