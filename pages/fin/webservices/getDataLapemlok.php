<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', '600');
error_reporting(E_ALL);
$data = $_GET;
//print_r($data);
//$data = (object)$_POST['data'];
$getListData = new getListData();
$d_from = $getListData->d_from($data['from']);
$d_to = $getListData->d_to($data['to']);
$List = $getListData->detailLapemlok($d_from,$d_to);
print_r($List);
class getListData {
	public function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	public function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}
	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($result);
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
	public function detailLapemlok($d_from,$d_to){
		$q = "SELECT mi.goods_code
                , mi.itemdesc
                , mi.matclass
                , ac.kpno 
                , mst.Styleno
                , s.so_no
                , b.bpbno_int
				,b.id_bpb
                , b.bpbdate
                , b.pono
                , b.bcno
                , b.bcdate
                , b.no_fp
                , b.tgl_fp
                , ms.Supplier
                , ms.supplier_code
                , ms.short_name
                , j.jo_no
                , j.jo_date
                , j.username
                , poi.qty AS qty_po_item
                , b.qty AS qty_bpb
                , ROUND(poi.qty-b.qty,2)qty_outstanding
                , b.qty
                , b.unit
                , b.price
                , b.curr
                , (b.price*b.qty)dpp
                , poh.ppn
                , poh.podate
                , ((b.price*b.qty)+((poh.ppn/100)*(b.price*b.qty)))after_ppn
                , byr1.Supplier AS buyer
                , byr1.supplier_code AS byr_code


                FROM fin_journal_h fjh INNER JOIN (SELECT bpbno_int, 
                bpbdate, 
                id_supplier, 
                id_item,
                id_jo,
                id_po_item,
                qty,
                price,
                unit,
                curr,
                invno, 
                pono, 
                bcno, 
                bcdate,
                no_fp,
                tgl_fp,
				id id_bpb

                FROM bpb WHERE jenis_dok='BC 4.0' AND id_jo!='')b ON b.bpbno_int=fjh.reff_doc

                INNER JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier

                INNER JOIN masteritem mi ON mi.id_item=b.id_item

                INNER JOIN jo j ON j.id=b.id_jo

                INNER JOIN jo_det jod ON jod.id_jo=j.id

                INNER JOIN so s ON s.id=jod.id_so

                INNER JOIN so_det sod ON sod.id_so=s.id

                INNER JOIN act_costing ac ON ac.id=s.id_cost


                INNER JOIN (SELECT Supplier,
                Id_Supplier,
                tipe_sup,
                supplier_code
                FROM mastersupplier WHERE area='L' AND tipe_sup='C')byr1 ON byr1.Id_Supplier=ac.id_buyer 
                
                INNER JOIN po_item poi ON poi.id=b.id_po_item

                INNER JOIN po_header poh ON poh.id=poi.id_po

                INNER JOIN masterstyle mst ON mst.id_so_det=sod.id

                WHERE 1=1 AND fjh.fg_post='2' AND fjh.type_journal='2' AND ms.area='L' AND (fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to') 

				GROUP BY b.id_bpb

                ORDER BY b.bpbdate DESC
		";
		/* echo $q; */
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$outp = '';
				while($row = mysqli_fetch_array($MyList)){
					if ($outp != "") {$outp .= ",";}
					$outp .= '{"goods_code":"'.rawurlencode($row['goods_code']).'",'; 
					$outp .= '"itemdesc":"'.rawurlencode($row['itemdesc']).'",'; 
					$outp .= '"matclass":"'.rawurlencode($row['matclass']).'",'; 
					$outp .= '"kpno":"'.rawurlencode($row['kpno']).'",'; 
					$outp .= '"Styleno":"'.rawurlencode($row['Styleno']).'",'; 
					$outp .= '"so_no":"'.rawurlencode($row['so_no']).'",'; 
					$outp .= '"bpbno_int":"'.rawurlencode($row['bpbno_int']).'",'; 
					$outp .= '"bpbdate":"'.rawurlencode($row['bpbdate']).'",'; 
					$outp .= '"pono":"'.rawurlencode($row['pono']).'",'; 
					$outp .= '"bcno":"'.rawurlencode($row['bcno']).'",'; 
					$outp .= '"bcdate":"'.rawurlencode($row['bcdate']).'",'; 
					$outp .= '"no_fp":"'.rawurlencode($row['no_fp']).'",'; 
					$outp .= '"tgl_fp":"'.rawurlencode($row['tgl_fp']).'",'; 
					$outp .= '"Supplier":"'.rawurlencode($row['Supplier']).'",'; 
					$outp .= '"supplier_code":"'.($row['supplier_code']).'",'; 
					$outp .= '"short_name":"'.rawurlencode($row['short_name']).'",'; 
					$outp .= '"jo_no":"'.rawurlencode($row['jo_no']).'",'; 
					$outp .= '"jo_date":"'.rawurlencode($row['jo_date']).'",'; 
					$outp .= '"username":"'.rawurlencode($row['username']).'",'; 
					$outp .= '"qty_po_item":"'.rawurlencode($row['qty_po_item']).'",'; 
					$outp .= '"qty_bpb":"'.rawurlencode($row['qty_bpb']).'",'; 
					$outp .= '"qty_outstanding":"'.rawurlencode($row['qty_outstanding']).'",'; 
					$outp .= '"qty":"'.rawurlencode($row['qty']).'",'; 
					$outp .= '"unit":"'.rawurlencode($row['unit']).'",'; 					
					$outp .= '"price":"'.rawurlencode(number_format((float)$row['price'], 2, '.', ',')).'",'; 
					$outp .= '"curr":"'.rawurlencode($row['curr']).'",'; 
					$outp .= '"dpp":"'.rawurlencode(number_format((float)$row['dpp'], 2, '.', ',')).'",'; 
					$outp .= '"ppn":"'.rawurlencode($row['ppn']).'",'; 
					$outp .= '"podate":"'.rawurlencode($row['podate']).'",'; 
					$outp .= '"after_ppn":"'.rawurlencode(number_format((float)$row['after_ppn'], 2, '.', ',')).'",'; 
					$outp .= '"buyer":"'.rawurlencode($row['buyer']).'",'; 
					$outp .= '"byr_code":"'.rawurlencode($row['byr_code']).'"}'; 
					
				} 		
			}		
		}
		$result = '{"data":['.$outp.']}';	
		return $result;
	}
}
?>