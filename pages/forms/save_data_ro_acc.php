<?php 
include '../../include/conn.php';
include 'fungsi.php';
include 'journal_interface.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
// if($auto_ap_ar=="Y") { include 'insert_ap_ar.php'; }

$txtbpbno_ro = nb($_POST['txtsjno']);
$tgl_ro = fd($_POST['txtbpbdate']);
$jenis_dok=$_POST['txtstatus_kb'];
$txtbcno=$_POST['txtbcno'];
$txtbcdate=$_POST['txtbcdate'];
$txtbcaju="";
$txttglaju="";
$cbomat='A';
// if (!isset($_POST['item']))
// {	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Disimpan";
// 	echo "<script>window.location.href='../forms/?mod=21v';</script>"; 
// }
	$QtySJ = $_POST['itemstock'];
	$QtyRI = $_POST['item'];
	$ItemID = $_POST['iditem'];
	$JoID = $_POST['idjo'];
	$RakID = $_POST['idrak'];
	$BpbdetID = $_POST['idbpbdet'];
	if(isset($_POST['qtyroll'])) {$RollArr = $_POST['qtyroll'];} else {$RollArr = "";}
	#VALIDASI QTY RI > QTY SJ
	// foreach ($QtyRI as $key => $value) 
	// {	if (is_numeric($value))
	// 	{	$txtqtysj = $QtySJ[$key];
	//     $txtqtyro = $QtyRI[$key];
	// 		if ((int)$txtqtyro>(int)$txtqtysj)
	// 		{	$_SESSION['msg'] = "XQty RI Tidak Boleh Melebihi Qty SJ";
	// 			echo "<script>window.location.href='../forms/?mod=$mod';</script>";
	// 			exit;
	// 		}    
	//   }
	// }
	$txtrono=urutkan("Add_RO",$cbomat);
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_A'");
		$date=fd($tgl_ro);
		$cri2=$cbomat2."/RO/".date('my',strtotime($date));
		$txtrono2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtrono2=""; }
	foreach ($QtyRI as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqtyro = $QtyRI[$key];
			$txtiditem = $ItemID[$key];
			$txtidjo = $JoID[$key];
			$txtidrak = $RakID[$key];
			$txtidbpbdet = $BpbdetID[$key];
	    $cri=explode(":",$key);
	    // echo "<script>console.log($key)</script>";
	    $id_item = $cri[0];
	    $id_jo = $cri[1];
	    $sql="select * from bpb where bpbno_int='$txtbpbno_ro' and id_item = '$txtiditem' and id_jo = '$txtidjo'";
	    $databpb=mysql_fetch_array(mysql_query($sql));
	    $txtid_item_fg=$databpb['id_item_fg'];
	    $txtbpbno=$databpb['bpbno'];
	    $txtunit=$databpb['unit'];
	    $txtcurr=$databpb['curr'];
	    $txtprice=$databpb['price'];
	    $txtremark=$_POST['txtdefect'];
	    $txtberat_bersih=0;
	    $txtberat_kotor=0;
	    $txtnomor_mobil="";
	    $txtid_supplier=$databpb['id_supplier'];
	    $txtinvno="";
	    $txtkpno=$databpb['kpno'];
	    $txtnomor_rak="";
	    $retur="Y";
	    $sql = "insert into bppb_temp_ro (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,
				bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
				nomor_rak,status_retur,bpbno_ro,id_jo) values ('$txtiditem','$txtid_item_fg',
				'$txtqtyro','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',
				'$txtnomor_mobil','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate','$txtrono','$txtrono2',
				'$tgl_ro','$jenis_dok','$user','1','$txtbcaju','$txttglaju',
				'$txtkpno','$txtnomor_rak','$retur','$txtbpbno','$txtidjo')";
			insert_log($sql,$user);

		 $sql_det = "insert into bppb_det (bppbno,bppbno_int,bppbno_req,id_item,id_jo,roll_qty,roll_qty_old,unit,id_rak_loc,user,bppbdate,id_bpb_det,cancel) values ('$txtrono','$txtrono2','','$txtiditem','$txtidjo','$txtqtyro','','$txtunit','$txtidrak','$user','$tgl_ro','$txtidbpbdet','N')";
			insert_log($sql_det,$user);

			calc_stock($cbomat,$txtiditem);
	  	if($RollArr!="")
	  	{
	  		$criroll = explode("X",$RollArr[$key]);
				foreach($criroll as $rollrak)
				{	
					$qtyuse = 0; 
					$criroll2 = explode("|",$rollrak);
					$idroll = $criroll2[2];
					$idroll_h = $criroll2[3];
					$qtyuse = $criroll2[4];
					if(!ISSET($qtyuse)){
						$qtyuse = 0;
					}
					if($qtyuse == ''){
						$qtyuse = 0;
					}
					// $sql="update bpb_roll set roll_qty_used=(roll_qty_used + $qtyuse) where id='$idroll' 
					// 	and id_h='$idroll_h'";
					// insert_log($sql,$user);
				}
	  	}
	  }
	}

	$sql_bppb = "insert into bppb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,
				bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
				nomor_rak,status_retur,bpbno_ro,id_jo) (select id_item,id_item_fg,sum(qty),unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,nomor_rak,status_retur,bpbno_ro,id_jo from bppb_temp_ro GROUP BY id_item,id_jo)";
	insert_log($sql_bppb,$user);
	if ($sql_bppb) {
		$sql_del = "delete from bppb_temp_ro";
		insert_log($sql_del,$user);
	}
	insert_bpb_gr_ret($txtrono);
	if($auto_ap_ar=="Y") { insert_jurnal_retur("AR",$txtrono,$user); }	
	$_SESSION['msg'] = "Data Berhasil Disimpan Dengan Nomor RO : ".$txtrono;
	echo "<script>window.location.href='../forms/?mod=21v&mode=Bahan_Baku';</script>";

?>