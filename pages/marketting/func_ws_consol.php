<?php
set_time_limit(0);

function oto_consolidate($WSnya,$user)
{	
	if($WSnya!="")
	{	
		$txtso_date=date('d M Y');
		$date=fd($txtso_date);
		$cri2="SO/".date('my',strtotime($date));
		$txtso_no=urutkan_inq("SO-".date('Y',strtotime($date)),$cri2); 
		$txtid_cost = flookup("id","act_costing","kpno='$WSnya'");
		$sql = "insert into so (id_cost,buyerno,so_no,so_date,qty,unit,curr,fob,nm_file,username,tax,id_season)
			values ('$txtid_cost','Not Available','$txtso_no','$txtso_date','1','PCS','','0',
			'','$user','','SS 19')";
		insert_log($sql,$user);
		$id_so_new=flookup("id","so","so_no='$txtso_no'");
		$sql="insert into so_det (id_so,deldate_det,qty,unit,price) 
			values ('$id_so_new','0000-00-00','1','PCS','0')";
		insert_log($sql,$user);
		$cri2="JO/".date('my',strtotime($txtso_date));
		$jo_no=urutkan_inq("JO-".date('Y',strtotime($txtso_date)),$cri2); 
		$sql = "insert into jo (jo_no,jo_date,username) 
			values ('$jo_no','".fd($txtso_date)."','$user')";
		insert_log($sql,$user);
		$id_jo=flookup("id","jo","jo_no='$jo_no'");
		$sql = "insert into jo_det (id_jo,id_so) 
			values ('$id_jo','$id_so_new')";
		insert_log($sql,$user);
	}
};

?>