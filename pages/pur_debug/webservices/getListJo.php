<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_GET["id_supplier"],$_GET['jenis_item']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_supp,$jenis_item){
		
		
		
		
		include __DIR__ .'/../../../include/conn.php';

  if($jenis_item=="Material")
  {
    $filjen=" and s.jenis!='N' ";
  }
  else
  {
    $filjen=" and s.jenis='N' ";
  }

		
$tmplfld="concat(jo_no,' - ',ms.supplier,' - ',ac.styleno)"; 
  $q = "select tmppr.id id,tmppr.vtampil tampil from  
    (select a.id,$tmplfld vtampil,
    sum(sod.qty*s.cons) qtybom,tmppo.qty_po from 
    jo a inner join bom_jo_item s on a.id=s.id_jo
    inner join jo_det jod on a.id=jod.id_jo
    inner join so on jod.id_so=so.id
    inner join act_costing ac on so.id_cost=ac.id 
    inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
    inner join so_det sod on s.id_so_det=sod.id  
    left join 
	(select a.id_jo,a.id_gen,sum(a.qty) qty_po from po_item a inner join po_header s on a.id_po=s.id 
      where cancel='N' $filjen group by id_jo,id_gen) tmppo 
    on tmppo.id_jo=s.id_jo and tmppo.id_gen=s.id_item
    where (s.id_supplier='$id_supp' or s.id_supplier2='$id_supp') 
    and s.status='$jenis_item' and s.cancel='N' $sql_app group by jo_no,s.id_item) tmppr 
    where qtybom>qty_po or qty_po is null group by tmppr.id";		
		$outp = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"isi":"'. rawurlencode($row["tampil"]). '"}'; 	
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




