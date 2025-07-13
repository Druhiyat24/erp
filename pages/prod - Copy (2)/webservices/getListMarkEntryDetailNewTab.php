<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$id_cost = $_POST['id'];
$color = $_POST['clr'];
$id_url = $_POST['url'];
// print_r($id_url);die();
$List = $getListData->get($id_cost,$color,$id_url);
print_r($List);
//}
//else{
//	exit;
//}

class getListData {
	

public function connect()
		{
			include __DIR__ .'/../../../include/conn.php';
			return $con_new;
		}
		
	public function json_array($res)
    {
		
        $rows = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$rows[] = $row;
			}
		}

        return $rows;
	}		
		


	
	public function result($res)
    {
        $result = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}		
	
	public function check_query($connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			return 1;
		}
	}
	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		return '1';
	}	
	
	public function flookup_new($fld,$tbl,$criteria){
		include __DIR__ .'/../../../include/conn.php';
	
		if ($fld!="" AND $tbl!="" AND $criteria!=""){	
			$quenya = "Select $fld as namafld from $tbl Where $criteria ";
			$strsql = mysql_query($quenya);
			if (!$strsql) { 
				die($quenya. mysql_error()); 
			}
			$rs = mysql_fetch_array($strsql);
			if (mysql_num_rows($strsql)=='0'){
				$hasil="";
			}
			else{
				$hasil=$rs['namafld'];
			}
			return $hasil;
		}
	}
	
	
	public function get_id_group_first($id_cost){
		$sql ="SELECT id_group_det
					,id_mark_detail FROM prod_mark_entry_detail WHERE id_cost = '{$id_cost}' 
			 GROUP BY id_group_det LIMIT 1";
			
			$tmp_array = $this->eksekusi_query($sql,"get_id_group_first");
	
		return $tmp_array[0]['id_group_det'];	
			
	}
	
	public function get($id_cost,$color,$id_url){
		include __DIR__ .'/../../../include/conn.php';


		$sql_group = "SELECT 
						meg.*
					FROM prod_mark_entry_group AS meg
					WHERE meg.id_cost = '{$id_cost}' 
					AND color = '{$color}' AND meg.tab != '1'
					ORDER BY id_group ASC
		";
		// echo $sql_group;
		$stmt_group = mysql_query($sql_group);

		$id_group_det_first = $this->get_id_group_first($id_cost);


		$q = "SELECT 
				med.id_mark_detail,
				med.id_mark,
				med.id_cost,
				med.color,
				med.size,
				med.qty,
				med.ratio,
				med.spread,
				med.kurang,
				meg.id_group,
				meg.unit_yds,
				meg.unit_inch
			FROM prod_mark_entry_detail AS med
			INNER JOIN (
				SELECT 
					meg.id_group,
					meg.id_mark_entry,
					meg.unit_yds,
					meg.unit_inch,
					meg.tab
				FROM prod_mark_entry_group AS meg
			) AS meg ON meg.id_group = med.id_group
			WHERE med.id_cost='{$id_cost}' AND med.color = '{$color}' AND meg.tab != '1'
			AND med.id_group_det = '{$id_group_det_first}'
			ORDER BY med.id_mark_detail ASC
		";
		$stmt = mysql_query($q);


		$sql_end = "SELECT 
						med.id_mark_detail,
						med.id_mark,
						med.id_cost,
						med.color,
						med.size,
						med.qty,
						med.ratio,
						SUM(med.spread) spread,
						med.kurang,
						meg.id_group,
						meg.unit_yds,
						meg.unit_inch
					FROM prod_mark_entry_detail AS med
					INNER JOIN (
						SELECT 
							meg.id_group,
							meg.id_mark_entry,
							meg.unit_yds,
							meg.unit_inch,
							meg.tab
						FROM prod_mark_entry_group AS meg
					) AS meg ON meg.id_group = med.id_group
					WHERE med.id_cost='{$id_cost}' AND med.color = '{$color}' AND meg.tab != '1'
					GROUP BY med.size
					ORDER BY med.id_mark_detail ASC
		";
		$stmt_end = mysql_query($sql_end);


		$id = array();
		$table = "";

		// Table 1
		$table .="<table id='marker2' class='table-bordered table-striped' style='width: 100%'>";
		$table .="<thead>";
		$table .="<tr>";
		$table .="<th colspan='2'>Size</th>";
		while($row = mysql_fetch_array($stmt)){
			$table .="<th>".addslashes($row['size'])."</th>";
		}
		$table .="<th>Total</th>";
		$table .="<th>Action</th>";
		$table .="</tr>";
		$table .="</thead>";
		
		$table .="<tbody>";
		$table .="<tr>";
		$table .="<td style='text-align: center' colspan='2'><label>Qty</label></td>";
		$_sum_qty = 0;
		$stmt_2 = mysql_query($q);
		while($row2 = mysql_fetch_array($stmt_2)){
			$table .="<td  style='text-align: center'>".$row2['qty']."</td>";
			$_sum_qty = $_sum_qty + $row2['qty'];
		}
		$table .="<td style='text-align: center'>{$_sum_qty}</td>";
		$table .="<td></td>";
		$table .="</tr>";
		$stmt_id = mysql_query($q);
		$rowid = mysql_fetch_array($stmt_id);
		$no = 1;
		$array_ratio = array();
		$array_spread = array();
		$_sum_spread_array = array();
		$__ssum_spread = 0;
		while($rowG = mysql_fetch_array($stmt_group)){
			
			
			
				$spread_Index = "spread_".$rowG['id_group'];
				$array_spread[$spread_Index] =  array();
				$array_spread[$spread_Index] = array(
					"id"		=> $rowG['id_group'],
					"spread"	=> $rowG['spread']
				);			
			
			
			
			
			$table .="<tr>";
			$table .="<td style='text-align: center' colspan='2'><label>Ratio ".$no."</label></td>";
			$stmt_3 = mysql_query($q);
			$_sum_ratio = 0;
			while($row3 = mysql_fetch_array($stmt_3)){
				$array_ratio_ = array();
				$row3['size'] = addslashes($row3['size']);
				$_ratio = $this->flookup_new("ratio","prod_mark_entry_detail","color ='{$row3['color']}' 
				AND id_group = '{$rowG['id_group']}' AND size ='{$row3['size']}'");
				$_sum_ratio = $_sum_ratio + $_ratio;
				// $table .="<td  style='text-align: center; background-color: yellow;'>".$_ratio."</td>";
				$_id_md = $this->flookup_new("id_mark_detail","prod_mark_entry_detail","color ='{$row3['color']}' 
				AND id_group = '{$rowG['id_group']}' AND size ='{$row3['size']}'");
				$table .="<td  style='text-align: center;'>
							<input type='text' onkeyup='handleKeyUp(this);' name='ratio' id='ratio_".$_id_md."' class='form-control' value=".$_ratio." style='
							width: 30%;
							text-align: center;
							margin-left: auto;
							margin-right: auto;
							background-color: white;
							color: black;'>
				</td>";
				$ratio_Index = "ratio_".$_id_md;
				$array_ratio[$ratio_Index] =  array();
				$array_ratio[$ratio_Index] = array(
					"id"	=> $_id_md,
					"ratio"	=> $_ratio
				);
			}
			$table .="<td style='text-align: center'>{$_sum_ratio}</td>";
			$rowid['size'] = addslashes($rowid['size']);
			$_group_id = $this->flookup_new("id_group","prod_mark_entry_detail","color ='{$rowid['color']}' 
			AND id_group = '{$rowG['id_group']}' AND size ='{$rowid['size']}'");
			$table .="<td style='text-align: center' rowspan='3'>
					<a style='color: #ff5050;' title='Delete' class='btn btn-sm' data-toggle='modal' data-target='#myModal4'
					onclick='remove(".'"'.$_group_id.'"'.")'>
						<i class='fa fa-trash'></i>
					</a>
					<input type='hidden' name='idgroup' id='idgroup' class='form-control' value=".'"'.$rowG['id_group'].'"'.">
			</td>";
			// $table .="<td style='text-align: center' rowspan='3'>
			// 		<a style='color: #3c8dbc;' title='Edit' class='btn btn-sm' 
			// 		data-toggle='modal' data-target='#myModal2' onclick='getModalSize(".'"'.$rowid['id_cost'].'","'.$rowid['color'].'","'.$_group_id.'"'.")'>
			// 			<i class='fa fa-pencil'></i>
			// 		</a>
			// </td>";
			$table .="</tr>";
			$table .="<tr>";
			$table .="<td style='text-align: center'><label>Spread</label></td>";
			// $table .="<td style='text-align: center; background-color: yellow;'>".$rowG['spread']."</td>";
			$table .="<td  style='text-align: center;'>
					<input type='text' name='spread' id='spread_".$rowG['id_group']."' onkeyup='handleKeyUp(this);' class='form-control' value=".$rowG['spread']." 
					style='
					width: 40%;
					text-align: center;
					float: right;
					background-color: white;
					color: black;'>
			</td>";
			$stmt_4 = mysql_query($q);
			$_sum_spread = 0;
			
			while($row4 = mysql_fetch_array($stmt_4)){
				$row4['size'] = addslashes($row4['size']);
				$_spread = $this->flookup_new("spread","prod_mark_entry_detail","color ='{$row4['color']}' 
				AND id_group = '{$rowG['id_group']}' AND size ='{$row4['size']}'");
				$_sum_spread = $_sum_spread + $_spread;
				$table .="<td  style='text-align: center'>".$_spread."</td>";
			}
			array_push($_sum_spread_array, $_sum_spread);
			$table .="<td style='text-align: center'>{$_sum_spread}</td>";
			$table .="</tr>";
			$table .="<tr>";
			$table .="<td style='text-align: center' colspan='2'><label>Balance</label></td>";
			$stmt_5 = mysql_query($q);
			$_sum_kurang = 0;
			while($row5 = mysql_fetch_array($stmt_5)){
				$row5['size'] = addslashes($row5['size']);
				$_kurang = $this->flookup_new("kurang","prod_mark_entry_detail","color ='{$row5['color']}' 
				AND id_group = '{$rowG['id_group']}' AND size ='{$row5['size']}'");
				$_sum_kurang = $_sum_kurang + $_kurang;
				$table .="<td  style='text-align: center'>".$_kurang."</td>";
			}
			$table .="<td style='text-align: center'>{$_sum_kurang}</td>";
			$table .="</tr>";
		
			$no++;	
		}
		$table .="<tr>";
		$table .="<td style='text-align: center' colspan='2'><label>Total Size</label></td>";
		// $stmt_total = mysql_query($q);
		$ttt = 0;
		while($rowT = mysql_fetch_array($stmt_end)){
			$table .="<td style='text-align: center'>".$rowT['spread']."</td>";
			$ttt = $ttt + $rowT['spread'];
		}
		$table .="<td style='text-align: center'>{$ttt}</td>";
		$table .="<td></td>";
		$table .="</tr>";

		$table .="<tr>";
		$table .="<td style='text-align: center' colspan='2'><label>Balance Size</label></td>";
		$kkk = 0;
		$stmt_kurang = mysql_query($sql_end);
		while($rowK = mysql_fetch_array($stmt_kurang)){
			if($ttt == '0'){
				$kurang_spread = 0;	
			}
			else{
				$kurang_spread = $rowK['spread'] - $rowK['qty'];
			}
			$table .="<td style='text-align: center'>{$kurang_spread}</td>";
			$kkk = $kkk + $kurang_spread;
		}
		$table .="<td style='text-align: center'>{$kkk}</td>";
		$table .="<td></td>";
		$table .="</tr>";

		$table .="</tbody>";
		$table .="</table>";
		// End Table 1
		$table .="<br><br>";


		// Table 2
		$stmt_group2 = mysql_query($sql_group);
		$table .="<table id='length' class='table-bordered table-striped' style='width: 100%'>";
		
		$table .="<thead>";
		$table .="<tr>";
		$table .="<th rowspan='2'>Ratio</th>";
		$table .="<th colspan='2'>Length (Marker)</th>";
		$table .="<th rowspan='2'>Length Yard +2 (YDS)</th>";
		$table .="<th rowspan='2'>YDS</th>";
		$table .="<th rowspan='2'>Con's/YARD</th>";
		$table .="<th rowspan='2'>Con's/Kg</th>";
		$table .="</tr>";
		$table .="<tr>";
		$table .="<th>YDS</th>";
		$table .="<th>Inch</th>";
		$table .="</tr>";
		$table .="</thead>";
		
		$table .="<tbody>";
		$nom = 1;
		$tot_length = 0;
		$tot_yds = 0;
		$tot_yard = 0;
		$tot_kg = 0;
		$array_sum_ratio = array();
		$z = 0;
		while($rowG2 = mysql_fetch_array($stmt_group2)){
			$width__ = $rowG2['width'];
			$gsm__ = $rowG2['gsm'];
				$sum_yds_ratio_Index = "yds_".$rowG2['id_group']; 
				
				$array_sum_ratio[$sum_yds_ratio_Index] =  array();
				
				$array_sum_ratio[$sum_yds_ratio_Index] =  array(
					"id"	=> $rowG2['id_group'],
					"yds"	=> $rowG2['unit_yds'],
					"inch"	=> $rowG2['unit_inch']
				);		
					
			



			if($rowG2['unit_inch'] == '0' && $rowG2['unit_yds'] == '0'){
				$rumus1 = 0;
			}
			else{
				$rumus1 = ($rowG2['unit_inch'] + 2) / 36 + $rowG2['unit_yds'];
			}
			
			//$rumus1=length + 2
			//rumus2 = YDS
			//rumus3= cons/yard
			//rumus4=cons/kg 
			//yds = rumus2
			
			$rumus2 = $rowG2['spread'] * $rumus1;
			$rumus3 = $rumus2 / $_sum_spread_array[$z];
			$__ssum_spread = $__ssum_spread + $_sum_spread_array[$z];
			//echo $_sum_spread;die();
			// print_r($_sum_spread_array);die();
			
			//rumus CONS/kg : (YDS*0.9144) * (width*0.0254) * (gsm/total_spread)
			$rumus4 = ( ($rumus2 * 0.9144) * ($rowG2['width'] * 0.0254) * ($rowG2['gsm'] / $_sum_spread_array[$z]) ) / 1000;
/* 			echo "( (".$rumus2." * 0.9144) * (".$rowG2['width']."  * 0.0254 ) * (".$rowG2['gsm']."/".$_sum_spread_array[$z].") )/1000 \n\n"; */
	

			$table .="<tr>";
			$table .="<td style='text-align: center'><label>Ratio $nom</label></td>";
			// $table .="<td style='text-align: center; background-color: yellow;'>".$rowG2['unit_yds']."</td>";
			// $table .="<td style='text-align: center; background-color: yellow;'>".$rowG2['unit_inch']."</td>";
			$table .="<td style='text-align: center;'>
					<input type='text' name='yds' id='yds_".$rowG2['id_group']."' onkeyup='handleKeyUp(this);' class='form-control' value=".$rowG2['unit_yds']." 
					style='
					width: 30%;
					text-align: center;
					margin-left: auto;
					margin-right: auto;
					background-color: white;
					color: black;'>
			</td>";
			$table .="<td style='text-align: center;'>
					<input type='text' name='inch' id='inch_".$rowG2['id_group']."' onkeyup='handleKeyUp(this);' class='form-control' value='".$rowG2['unit_inch']."' 
					style='
					width: 30%;
					text-align: center;
					margin-left: auto;
					margin-right: auto;
					background-color: white;
					color: black;'>
			</td>";
			$table .="<td style='text-align: center'>".number_format($rumus1, 3, ".", ",")."</td>";
			$table .="<td style='text-align: center'>".number_format($rumus2, 1, ".", ",")."</td>";
			$table .="<td style='text-align: center'>".number_format($rumus3, 3, ".", ",")."</td>";
			$table .="<td style='text-align: center'>".number_format($rumus4, 3, ".", ",")."</td>";
			$table .="</tr>";
			$nom++;
			$z= $z+1;

			$tot_length = $tot_length + $rumus1;
			$tot_yds = $tot_yds + $rumus2;
/* 			$tot_yard = $tot_yard + $rumus3; 
			$tot_kg = $tot_kg + $rumus4; */
		}
		
			$tot_yard = $tot_yds/$__ssum_spread;
			$tot_kg = ( ($tot_yds * 0.9144) * ($width__ * 0.0254) * ($gsm__ / $__ssum_spread) ) / 1000;
		//echo "( (".$tot_yds." * 0.9144) * (".$width__."  * 0.0254 ) * (".$gsm__."/".$__ssum_spread.") )/1000 \n\n";
		//die();
		
		$table .="<tr>";
		$table .="<td style='text-align: center'><label>Total</label></td>";
		$table .="<td style='text-align: center'></td>";
		$table .="<td style='text-align: center'></td>";
		$table .="<td style='text-align: center'>".number_format($tot_length, 3, ".", ",")."</td>";
		$table .="<td style='text-align: center'>".number_format($tot_yds, 1, ".", ",")."</td>";
		$table .="<td style='text-align: center'>".number_format($tot_yard, 3, ".", ",")."</td>";
		$table .="<td style='text-align: center'>".number_format($tot_kg, 3, ".", ",")."</td>";
		$table .="</tr>";

		$table .="</tbody>";
		$table .="</table>";
		// End Table 2

		$table .="<br><br>";

		// Table 3
		$table .="<table id='cons' class='table-bordered table-striped' style='width: 100%'>";
		$table .="<thead>";
		$table .="<tr>";
		$table .="<th>GSM</th>";
		$table .="<th>Width</th>";
		$table .="<th>Allowance</th>";
		$table .="<th>Cad Cons Body/Kg</th>";
		$table .="<th>B-Cons/Kg</th>";
		$table .="<th>Balance</th>";
		$table .="<th>Percentage</th>";
		$table .="<th>Fabric Needs/Kg</th>";
		// $table .="<th>Action</th>";
		$table .="</tr>";
		$table .="</thead>";
		$table .="<tbody>";
		$table .="<tr>";
		$stmt_g2 = mysql_query($sql_group);
		$array_length = array();
		$rowgg = mysql_fetch_array($stmt_g2);


		// Total cons/kg + ( (3/100) * total cons/kg)

		$r_cons = $tot_kg + (($rowgg['allowance'] / 100) * $tot_kg);
		$r_bal =  $rowgg['b_cons_kg'] - $r_cons;
		$r_per = $r_bal / $rowgg['b_cons_kg'] * 100;
		
/* 		echo  $r_per;
		die(); */
		$r_use = ($tot_kg * 1.030) * $_sum_qty;
		
		//BALANCE / BCONS = 





		// print_r($rowgg['gsm']);
		// $table .="<td style='text-align: center; background-color: yellow;'>".$rowgg['gsm']."</td>";
		// $table .="<td style='text-align: center; background-color: yellow;'>".$rowgg['width']."</td>";
		$table .="<td style='text-align: center;'>
				<input type='text' onkeyup='handleKeyUp(this)' name='gsm' id='gsm' class='form-control' value=".$rowgg['gsm']." style='
				width: 30%;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				background-color: white;
				color: black;'>
		</td>";
		$table .="<td style='text-align: center;'>
				<input type='text' onkeyup='handleKeyUp(this)' name='width' id='width' class='form-control' value=".$rowgg['width']." style='
				width: 30%;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				background-color: white;
				color: black;'>
		</td>";
		$table .="<td style='text-align: center;'>
				<input type='text' onkeyup='handleKeyUp(this)' name='allow' id='allow' class='form-control' value=".$rowgg['allowance']." style='
				width: 30%;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				background-color: white;
				color: black;'>
		</td>";
		$table .="<td style='text-align: center'>".number_format($r_cons, 3, ".", ",")."</td>";
		$table .="<td style='text-align: center;'>
				<input type='text' onkeyup='handleKeyUp(this)' name='bcg' id='bcg' class='form-control' value=".$rowgg['b_cons_kg']." style='
				width: 30%;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				background-color: white;
				color: black;'>
		</td>";
		$table .="<td style='text-align: center'>".number_format($r_bal, 4, ".", ",")."</td>";
		$table .="<td style='text-align: center'>".number_format($r_per, 2, ".", ",")." %</td>";
		$table .="<td style='text-align: center'>".number_format($r_use, 2, ".", ",")."</td>";
		// $table .="<td style='text-align: center'>
		// 			<a style='color: #3c8dbc;' title='Edit' class='btn btn-sm' 
		// 			data-toggle='modal' data-target='#myModal3' onclick='getModalCons(".'"'.$rowgg['id_cost'].'","'.$rowgg['color'].'"'.")'>
		// 				<i class='fa fa-pencil'></i>
		// 			</a>
		// </td>";
		// $table .="<td style='text-align: center'>
		// 			<a style='color: #3c8dbc;' title='Save' class='btn btn-sm' 
		// 			onclick='getModalCons(".'"'.$rowgg['id_cost'].'","'.$rowgg['color'].'"'.")'>
		// 				<i class='fa fa-save'></i>
		// 			</a>
		// </td>";
		$table .="</tr>";
		$table .="</tbody>";
		$table .="</table>";
		
		
			
				$length_Index = "lengths";
				$array_length[$length_Index] =  array();
				$array_length[$length_Index] = array(
					"gsm"		=> $rowgg['gsm'],
					"width"		=> $rowgg['width'],
					"allow"		=> $rowgg['allowance'],
					"bcg"		=> $rowgg['b_cons_kg'],
				);			
			
			

		// Table 3
		// $stmt_g = mysql_query($q);
		// $rowg = mysql_fetch_array($stmt_g);
		// $table .="<br><input type='hidden' name='idgroup' id='idgroup' class='form-control' value=".'"'.$rowg['id_group'].'"'.">";
		$table = rawurlencode($table);
		$result = '{ "respon" :"200","status":"ok", "message":"1", "records": "'.$table.'", "array_rasio" : ['.json_encode($array_ratio).'],"array_spread" : ['.json_encode($array_spread).'], "array_sum_ratio" : ['.json_encode($array_sum_ratio).'], "array_length" : ['.json_encode($array_length).']  }';
		return $result;
	}
}
?>