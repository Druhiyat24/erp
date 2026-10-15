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

	// Pengaman stok aksesoris: cek ulang sisa tiap baris rak di server sebelum apa pun disimpan
	// (termasuk sebelum nomor BPPB dibuat). Sisa = roll_qty - qty_mutasi - yang sudah keluar.
	if ($cbomat == 'A')
	{
		$pakai = array();
		foreach ($_POST['totqtyroll'] as $k => $v)
		{
			foreach (explode("X", $_POST['qtyroll'][$k]) as $rollrak)
			{
				$c = explode("|", $rollrak);
				if (count($c) < 5 || (int) $c[2] <= 0 || (float) $c[4] <= 0) { continue; }
				$pakai[(int) $c[2]] = (isset($pakai[(int) $c[2]]) ? $pakai[(int) $c[2]] : 0) + (float) $c[4];
			}
		}
		$gagal = array();
		foreach ($pakai as $idb => $qty)
		{
			$r = mysql_fetch_array(mysql_query("select bd.id_item, concat(mr.kode_rak,' ',mr.nama_rak) rak,
				bd.roll_qty - coalesce(bd.qty_mutasi,0)
				- coalesce((select sum(o.roll_qty) from bppb_det o where o.id_bpb_det = '$idb' and o.cancel = 'N'),0) sisa
				from bpb_det bd left join master_rak mr on mr.id = bd.id_rak_loc where bd.id = '$idb'"));
			if (!$r) { $gagal[] = "Baris rak ID $idb tidak ditemukan"; continue; }
			if ($qty > round($r['sisa'], 4)) { $gagal[] = "Item $r[id_item] di $r[rak]: diambil $qty, sisa ".number_format($r['sisa'],2); }
		}
		if (count($gagal) > 0)
		{
			$_SESSION['msg'] = "XData tidak disimpan:\\n".implode("\\n", $gagal);
			echo "<script>window.location.href='../wh/?mod=new_req_material';</script>";
			exit;
		}
	}

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
					$id_bpb = (int) $criroll2[2];
					$id_rak = $criroll2[3];
					$qtyuse = (float) $criroll2[4];
					if ($id_bpb <= 0 || $qtyuse <= 0) { continue; }
					// guard atomik: hanya tercatat keluar kalau sisa baris masih cukup saat disimpan
					$sql_insert_det = "insert into bppb_det (bppbno,bppbno_int,bppbno_req,id_item,id_jo,roll_qty,unit,id_rak_loc,user,bppbdate,id_bpb_det,cancel)
					select '$txtbppbno','$txtbppbno2','$txtreqno','$id_item_barcode','$id_jo_barcode','$qtyuse','$unit','$id_rak','$user','$date','$id_bpb','N'
					from bpb_det bd where bd.id = '$id_bpb'
					and (bd.roll_qty - coalesce(bd.qty_mutasi,0) - coalesce((select sum(x.roll_qty) from bppb_det x where x.id_bpb_det = '$id_bpb' and x.cancel = 'N'),0)) >= $qtyuse";
					mysql_query($sql_insert_det);
					$affected = mysql_affected_rows();
					insert_log("X".$sql_insert_det,$user);
					if ($affected < 1) { $gagal_simpan[] = "Item $id_item_barcode (baris rak $id_bpb): stok berubah saat disimpan"; }
				}
			}
		}
		if (!empty($gagal_simpan))
		{ $_SESSION['msg']="XNomor BPPB ".$txtbppbno2." tersimpan, tetapi baris berikut tidak tercatat:\\n".implode("\\n", $gagal_simpan); }
		else
		{ $_SESSION['msg']="Data Berhasil Disimpan, Nomor BPPB : ".$txtbppbno2; }
		echo "<script>window.location.href='../wh/?mod=retur_material&id=$txtbppbno'</script>";
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