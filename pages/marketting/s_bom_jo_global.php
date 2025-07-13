<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mod=$_GET['mod'];
if ($mod == 'simpan')
{
	$id_jo 		= nb($_GET['id']);
	$id_contents=nb($_POST['txtItemCS']);
	$user		=$_SESSION['username'];
	$id_supp	= nb($_POST['txtsupp']);
	$txtnotes 	= nb($_POST['txtnotes']);
	$dateinput	=date('Y-m-d H:i:s');

	// if (isset($_POST['txtdest'])) {$dest=$_POST['txtdest'];} else {$dest="";}
	// if ($dest!="")
	// {
	// 	$dest="";
	// 	foreach ($_POST['txtdest'] as $names)
	// 	{
	// 		if($dest=="")
	// 		{
	// 			$dest = "'".$names."'";
	// 		}
	// 		else
	// 		{
	// 			$dest = $dest.",'".$names."'";
	// 		}
	// 	};
	// }

		$JmlArray = $_POST['id_item'];
		$NoArray = $_POST['no_roll'];
		$QtyArray = $_POST['qty_input'];
		$UnitArray = $_POST['unit'];
		$rulebom_global = $_POST['txtroll'];

		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{	$txtcri = $NoArray[$key];
		    $txtitem = $JmlArray[$key];
			$qty	 = $QtyArray[$key];
			$unit	 = $UnitArray[$key];

		    	$sql="insert into bom_jo_global_item (id_jo,id_contents,id_item,qty,unit,rule_bom,id_supplier,notes,username,dateinput,cancel)
		    		values ('$id_jo','$id_contents','$txtitem','$qty','$unit',
		    		'$rulebom_global','$id_supp','$txtnotes','$user','$dateinput','N')";
		    	insert_log($sql,$user);
				$_SESSION['msg'] = "Data Berhasil Disimpan";
				echo "<script>window.location.href='../marketting/?mod=144d&id_item=$id_jo';</script>";	
		  	
		  }
		}	


		// foreach ($JmlArray as $key => $value) 
		// {	if ($value!="")
		// 	{	$txtcri = $NoArray[$key];
		//     $txtitem = $JmlArray[$key];
		// 	$qty	 = $QtyArray[$key];
		// 	$unit	 = $UnitArray[$key];
		//     if ($dest!="") {$sql_dest=" and s.dest in ($dest) ";} else {$sql_dest="";}		    
		//     if (nb($rulebom_global)==nb("Per Color All Size"))
		//     {	$arrrule=explode(" | ",$txtcri);
		//   		$sql_wh=" and s.color='$arrrule[0]' $sql_dest ";
		// 		}
		// 		else if (nb($rulebom_global)==nb("All Color Range Size"))
		//     {	$arrrule=explode(" | ",$txtcri);
		//   		$sql_wh=" and s.size='$arrrule[1]' $sql_dest ";
		// 		}
		// 		else if (nb($rulebom_global)==nb("Per Color Range Size"))
		//     {	$arrrule=explode(" | ",$txtcri);
		//   		$sql_wh=" and s.color='$arrrule[0]' and s.size='$arrrule[1]' $sql_dest ";
		// 		}
		// 		else
		// 		{	$sql_wh=" $sql_dest "; }
		//     $sql="select s.* 
		// 			from jo_det a inner join so_det s 
		// 			on a.id_so=s.id_so where 
		// 			id_jo='$id_jo' and s.cancel='N' $sql_wh ";
		//     $rs=mysql_query($sql);
		//     while($data=mysql_fetch_array($rs))
		//     {	$sql="insert into bom_jo_global_item (id_jo,id_so_det,id_contents,id_item,qty,unit,rule_bom,id_supplier,notes,username,dateinput,cancel)
		//     		values ('$id_jo','$data[id]','$id_contents','$txtitem','$qty','$unit',
		//     		'$rulebom_global','$id_supp','$txtnotes','$user','$dateinput','N')";
		//     	insert_log($sql,$user);
		// 		$_SESSION['msg'] = "Data Berhasil Disimpan";
		// 		echo "<script>window.location.href='../marketting/?mod=144d&id_item=$id_jo';</script>";	
		//   	}
		//   }
		// }
}


if ($mod == 'simpan_ws_child')
{
	$id_jo 		= nb($_GET['id']);
	$cbo_jo_child=nb($_POST['cbo_jo_child']);
	$user		=$_SESSION['username'];
	$dateinput	=date('Y-m-d H:i:s');

	if ($cbo_jo_child!="")
		{	

				$cekjo=flookup("id_jo_child","bom_jo_global_child","id_jo_global='$id_jo' and id_jo_child = '$cbo_jo_child' and cancel = 'N'");
				if ($cekjo=="")
				{	

		    	$sql="insert into bom_jo_global_child (id_jo_global,id_jo_child,tgl_input,user,cancel)
		    		values ('$id_jo','$cbo_jo_child','$dateinput','$user','N')";
		    	insert_log($sql,$user);
				$_SESSION['msg'] = "Data Berhasil Disimpan";
				echo "<script>window.location.href='../marketting/?mod=144add_c&id=$id_jo';</script>";	
		  		}
		  		else
		  		{
		  		$_SESSION['msg'] = "Data Sudah Ada";
		  		echo "<script>window.location.href='../marketting/?mod=144add_c&id=$id_jo';</script>";		
		  		}	
		  }
			

}


if ($mod == 'cancel_item')
{
	$id_jo 		= nb($_GET['id_jo']);
	$id	  = $_GET['id'];


		    $sql = "update bom_jo_global_child set cancel = case when cancel = 'Y' then'N' else 'Y' end where id = '$id'";
			insert_log($sql,$user);			

			{$_SESSION['msg']="Data Berhasil Dicancel";}
			echo "<script>window.location.href='../marketting/?mod=144add_c&id=$id_jo';</script>";	
		
}


if ($mod == 'delete')
{
	$id_jo 		=$_GET['id'];
	$idd		=$_GET['idd'];	

	$sql="update bom_jo_global_item set cancel = case when cancel = 'Y' then'N' else 'Y' end
			where id = '$idd' and id_jo = '$id_jo'";
					insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Di Rubah";
		// echo "<script>window.location.reload();</script>";
		  echo "<script>window.location.href='../marketting/?mod=144d&id_item=$id_jo';</script>";
}

if ($mod == 'edit')
{
	$id_jo 		=$_GET['id'];
	$ItemArray 	= $_POST['id_cek'];
	$QtyArr		= $_POST['qty'];
	$unitArr	= $_POST['unit'];
	$SuppArr	= $_POST['supplier'];
	foreach ($ItemArray as $key => $value) 
	{
		$id_row=$ItemArray[$key];
		$qty=$QtyArr[$key];
		$unit=$unitArr[$key];
		$supp=$SuppArr[$key];
	
	$sql="update bom_jo_global_item set qty = '$qty', unit = '$unit', id_supplier = '$supp' 
			where id = '$id_row' and id_jo = '$id_jo'";
					insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Di Rubah";
		// echo "<script>window.location.reload();</script>";
		  echo "<script>window.location.href='../marketting/?mod=144d&id_item=$id_jo';</script>";
	}
}


?>