<?php 
include '../../include/conn.php';
include 'fungsi.php';
include '../forms/func_gen_kartu_stock.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];
if ($mod == 'simpan')
{
	$txtremark=nb($_POST['txtremark']);
	$txtid_supplier=nb($_POST['txtid_supplier']);
	$txtjns_out=nb($_POST['txtjns_out']);
	if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno = ""; } 
	if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate = ""; } 

	$txtreqno=nb($_POST['txtreqno']);
	$cbomat=substr($txtreqno,3,1);
	$txtbppbno=nb($_POST['txtbppbno']);
	$txtbppbdate = fd($_POST['txtbppbdate']);	

	if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; } 
	if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}	

	$txtbppbno = urutkan('Add_BPPB',$cbomat); 

		$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($txtbppbdate);
		$cri2=$cbomat2."/OUT/".date('my',strtotime($date));
		$txtbppbno2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);


		$QtyoutArray = $_POST['totqtyroll'];
		$id_itemArray = $_POST['id_item'];
		$unit_itemArray = $_POST['unitsisa'];
		$id_suppArray = $_POST['id_supp'];
		$JoArray = $_POST['jono'];
		$RollArr = $_POST['qtyroll'];
	
		foreach ($QtyoutArray as $key_1 => $value_1)
		{
			$qtyout_global=$QtyoutArray[$key_1];
			$id_item=$id_itemArray[$key_1];
			$unit=$unit_itemArray[$key_1];
			$id_supp=$id_suppArray[$key_1];
			$id_jo=$JoArray[$key_1];
			
		if ($qtyout_global != '0' && $qtyout_global != '' )
			{	
		$sql = "insert into bppb (bppbno,bppbno_int,bppbno_req,bppbdate,id_item,qty,price,remark,use_kite,berat_bersih,berat_kotor,username,unit,qty_karton,bcno,bcdate,jenis_dok,id_supplier,id_jo,jenis_trans) 
		values ('$txtbppbno','$txtbppbno2','$txtreqno','$txtbppbdate','$id_item','$qtyout_global','0','$txtremark','1','0','0','$user','$unit','0','$txtbcno','$txtbcdate','$status_kb','$id_supp','$id_jo','$txtjns_out')
		";
								#echo "<br>".$sql;
								insert_log($sql,$user);
		
			if ($qtyout_global_fix == '')
				{
					$qtyout_global_fix = '0';
				}
			else
				{
					$qtyout_global_fix = $qtyout_global;	
				}	
				
		$sql_upd_req = "update bppb_req set qty_out = qty_out + $qtyout_global_fix where bppbno = '$txtreqno' and id_item = '$id_item'";
								insert_log($sql_upd_req,$user);	
										

			}
			if ($cbomat == 'F')
			{
				$criroll = explode("X",$RollArr[$key_1]);
				foreach($criroll as $rollrak)
				{	$criroll2 = explode("|",$rollrak);
					$id_item_barcode = $criroll2[0];
					$id_jo_barcode = $criroll2[1];
					$idroll = $criroll2[2];
					$idroll_h = $criroll2[3];
					$qtyuse = $criroll2[4];
					$sql_roll="update bpb_roll set roll_qty_used=roll_qty_used + $qtyuse where id='$idroll' 
						and id_h='$idroll_h'";		
					insert_log($sql_roll,$user);
					$sql_insert_det = "insert into bppb_barcode_det (id,bppbno,bppbno_int,bppbno_req,id_roll,qty_roll,id_item,id_jo,user) values
					('','$txtbppbno','$txtbppbno2','$txtreqno','$idroll','$qtyuse','$id_item_barcode','$id_jo_barcode','$user')";
					insert_log($sql_insert_det,$user);	
				}				
			}
			else if ($cbomat == 'A')
			{
				$criroll = explode("X",$RollArr[$key_1]);
				foreach($criroll as $rollrak)
				{	$criroll2 = explode("|",$rollrak);
					$id_item_barcode = $criroll2[0];
					$id_jo_barcode = $criroll2[1];
					$id_bpb = $criroll2[2];
					$id_rak = $criroll2[3];
					$qtyuse = $criroll2[4];
					$sql_insert_det = "insert into bppb_det (id,bppbno,bppbno_int,bppbno_req,id_item,id_jo,roll_qty,unit,id_rak_loc,user,bppbdate,id_bpb_det,cancel) values
					('','$txtbppbno','$txtbppbno2','$txtreqno','$id_item_barcode','$id_jo_barcode','$qtyuse','$unit','$id_rak','$user','$date','$id_bpb','N')";
					insert_log($sql_insert_det,$user);
				}
			}		
		}		
		{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPPB : ".$txtbppbno2;}
		echo "<script>window.location.href='../forms/?mod=det_bppb_out&id=$txtbppbno'</script>";
	}







	


// $ItemArray = $_POST['chkid'];
// $QtyArr = $_POST['txtuse'];
// $Id_item_barcode_Arr = $_POST['id_item_barcode'];
// $Id_jo_barcode_Arr = $_POST['id_jo_barcode'];
// foreach ($ItemArray as $key => $value)
// {
// 	$qtyout_roll=$QtyArr[$key];
// 	$id_roll=$ItemArray[$key];
// 	$id_item_barcode=$Id_item_barcode_Arr[$key];
// 	$id_jo_barcode=$Id_jo_barcode_Arr[$key];

// $sql_upd_roll = "update bpb_roll set roll_qty_used = roll_qty_used + $qtyout_roll where id = '$id_roll'";
// 						insert_log($sql_upd_roll,$user);

// $sql_insert_det = "insert into bppb_barcode_det (id,bppbno,bppbno_int,bppbno_req,id_roll,qty_roll,id_item,id_jo,user) values
// 					('','$txtbppbno','$txtbppbno2','$txtreqno','$id_roll','$qtyout_roll','$id_item_barcode','$id_jo_barcode','$user')";
// 					insert_log($sql_insert_det,$user);
					
// }		
				



	# END COPAS SAVE ADD

?>