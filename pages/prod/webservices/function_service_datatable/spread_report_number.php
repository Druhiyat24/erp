<?php
class parents {
	
	public function main_select_query($_id){
		$_str_arr	= pecah_array($this->gen_kolom());
		
		$sql ="(
			SELECT 	 {$_str_arr}
				FROM prod_spread_report_number A WHERE A.id_mark_entry ='{$id}'
			)X";
		return $sql;
	}	
	
	
	
	public function gen_kolom(){
		return $_kolom = array(
			"0"			=>    "A.id_number",
			"1"       	=>    "A.id_mark_entry",
			"2"        	=>    "A.id_cost",
			"3"        	=>    "A.id_number_group",
			"4"        	=>    "A.username",
			"5"        	=>    "A.d_insert",
			"6"        	=>    "A.is_delete"
		);
		
		
	}
	
	
	public function fetch_data($_table,$_row,$_rowperpage){
		include __DIR__ .'../../../../include/conn.php';
		$_empQuery = "select $colomn  from $_table WHERE 1 ORDER BY X.id_mark_entry desc limit ".$_row.",".			$_rowperpage;
		$_empRecords = mysqli_query($conn_li, $_empQuery);

			while ($_row = mysqli_fetch_assoc($_empRecords)) {
	// echo '123';die();
//echo $row['n_post'];

	$button = '';		
	// $button .="<a href='../prod/?mod=2LA&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='Edit' class='btn btn-sm' onclick='edit(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
	$button .="<a href='../prod/?mod=SpreadingReport_list&id=$row[id_mark_entry]' style='color: #3c8dbc;' title='View' class='btn btn-sm' onclick='preview(".'"'.$row['id_mark_entry'].'"'.")' ><i class='fa fa-eye'> </i></a>";
		$_data[] = $this->generate_json($__row,'YES',$button);
		

		$data[] = array(
			"buyer"=>htmlspecialchars($_row['buyer']),
			"styleno"=>htmlspecialchars($_row['styleno']),
			"ws"=>htmlspecialchars($_row['ws']),
			"created_by"=>htmlspecialchars($_row['created_by']),
			"button"=>rawurlencode($button)
		);
	}


		
		
		
	}
	
	
	public function generate_json($__row,gen_button,$_button){
		$_val 		= $this->gen_kolom();
		$_cnt 		= count($_val);
		$_trigger 	= $_cnt - 1;
			$_x = "";		
		$str = "";
		
			for($_i=0;$_i<$_cnt;$_i++){
				$_pecah_string = explode($__arr);
				if($_i == $_trigger){
					$str .='"'.$_pecah_string[1].'"' => '"'.$__row[$_pecah_string[1]].'"'  ;
				}
				else{
					$str .='"'.$_pecah_string[1].'"' => '"'.$__row[$_pecah_string[1]].'",'  ;
				}
				
			}

		
		$_json = array($str); 
		return $_json;
		
		
	}
	
	public function pecah_array_x($__arr){
		$_cnt = count($__arr);
		$_trigger = $_cnt - 1;
		$_s = "";
		for($_i=0;$_i<$_cnt;$_i++){
			$_pecah_string = explode($__arr);
			if($_i == $_trigger){
				$_s .= "X.".$_pecah_string[1]; 
			}else{
				$_s .= "X.".$_pecah_string[1].","; 
			}
			
		}
		return $_s;
	}
	
	public function pecah_array_s($__arr,$__searchValue){
		$_cnt = count($__arr);
		$_trigger = $_cnt - 1;
		$_s = "";
		for($_i=0;$_i<$_cnt;$_i++){
			$_pecah_string = explode($__arr);
			if($_i == $_trigger){
				$_s .= "X.".$_pecah_string[1]." LIKE'%".$__searchValue."%' "; 
			}else{
				$_s .= "X.".$_pecah_string[1]." LIKE'%".$__searchValue."%'  OR "; 
			}
			
		}
		return $_s;		
		
		
		
	}	
	
	
	public function pecah_array($__arr){
		$_cnt = count($__arr);
		$_trigger = $_cnt - 1;
		$_s = "";
		for($_i=0;$_i<$_cnt;$_i++){
			if($_i == $_trigger){
				$_s .= $__arr;
			}else{
				$_s .= $__arr.",";
			}
			
		}
		return $_s;
	}

	
	
	public function s_query($_searchValue){
		$_searchQuery = "";
			if($_searchValue != ''){
			$_searchQuery = " AND (
						{$this->pecah_array_s($this->gen_kolom(),$__searchValue)}
				)";
			}
			return $_searchQuery;
	}
	
	public function total_number_no_filter($_table){
		include __DIR__ .'../../../../include/conn.php';
		$_sel = mysqli_query($conn_li,"select count(*) allcount from $table");
		$_records = mysqli_fetch_assoc($_sel); 
		$_totalRecords = $_records['allcount'];

		return $_totalRecords;
		
	}
	
	public function total_number_filter($_table){
		include __DIR__ .'../../../../include/conn.php';
	
		$_sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ");
		$_records = mysqli_fetch_assoc($_sel);
		$_totalRecordwithFilter = $_records['allcount'];
		return $_totalRecordwithFilter;
	
	}	
	
	
	
}




?>