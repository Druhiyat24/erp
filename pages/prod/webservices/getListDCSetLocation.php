<?php

include __DIR__ .'/../../../include/conn.php';

function get_loc($conn_li){ 
	$query = "SELECT 
			location
		FROM prod_dc_set_detail
		WHERE location != ''
		GROUP BY location
	";
	$result = mysqli_query($conn_li, $query); 
	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
	return $data; 
}
 
if (isset($_GET['prod_dc_set_detail'])) {
 	$getLoc = get_loc($conn_li, $_GET['prod_dc_set_detail']);
 	$locList = array();
 	foreach($getLoc as $loc){
 		$locList[] = $loc['location'];
 	}

	echo json_encode($locList);
}
?>