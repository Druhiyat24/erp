<?php 
include '../../include/conn.php';
include 'fungsi.php';

function update_pono($bpb){ //A.bpbno LIKE '%R%' AND (A.id_supplier >= 432 AND A.id_supplier <= 458
		$sql ="UPDATE bpb A INNER JOIN(
SELECT A.bpbno
		,A.bpbno_int
		,A.id_supplier
		,A.id_item
		,A.id_jo
		,B.id_jo jo_po
		,X.id_item id_item_master
		,X.id_gen id_gen_master
		,B.id_gen id_gen_po
		,B.cancel
		,C.id_supplier supp_po
		,A.qty
		,A.price
		,X.itemdesc
		
		,C.pono
	FROM bpb A
INNER JOIN masteritem X ON X.id_item = A.id_item	
INNER JOIN (SELECT * FROM po_item WHERE cancel ='N')B ON B.id_gen = X.id_gen AND A.id_jo =B.id_jo
INNER JOIN 	(SELECT * FROM po_header WHERE app='A')C	ON B.id_po = C.id AND A.id_supplier = C.id_supplier

WHERE 1=1 AND
A.bpbno_int  = '{$bpb}' AND A.id_supplier NOT IN(
'432',
'433',
'434',
'435',
'436',
'437',
'438',
'439',
'440',
'441',
'442',
'443',
'444',
'445',
'446',
'447',
'448',
'449',
'450',
'451',
'452',
'453',
'454',
'455',
'456',
'457',
'458')
)B

ON A.bpbno = B.bpbno
SET A.pono = B.pono
WHERE 1=1 AND
A.bpbno_int ='{$bpb}'
AND A.id_supplier NOT IN(
'432',
'433',
'434',
'435',
'436',
'437',
'438',
'439',
'440',
'441',
'442',
'443',
'444',
'445',
'446',
'447',
'448',
'449',
'450',
'451',
'452',
'453',
'454',
'455',
'456',
'457',
'458')
";
insert_log($sql,$user);
}

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
$txtbppbno_ri = nb($_POST['txtsjno']);
$tgl_ri = fd($_POST['txtbpbdate']);
$jenis_dok=$_POST['txtstatus_kb'];
if (isset($_POST['txttujuan'])) {$txttujuan=$_POST['txttujuan'];} else {$txttujuan="";}
$txtremark=$_POST['txtnotes'];
$txtbcno=$_POST['txtbcno'];
$txtbcdate=fd($_POST['txtbcdate']);
$txtbcaju="";
$txttglaju="";
if($mode=="FG")
{	$cbomat=substr($txtbppbno_ri,3,2); }
else
{	$cbomat=substr($txtbppbno_ri,3,1); }
if (!isset($_POST['item']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Disimpan";
	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode';</script>"; 
}
else
{	$QtySJ = $_POST['itemstock'];
	$QtyRI = $_POST['item'];
	$JOArr = $_POST['jo'];
	
	$txtrino=urutkan("Add_RI",$cbomat);
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($tgl_ri);
		$cri2=$cbomat2."/RI/".date('my',strtotime($date));
		$txtrino2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtrino2=""; }
	foreach ($QtyRI as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqtyri = $QtyRI[$key];
	    $keysplit=explode("|",$key);
	    $id_item = $keysplit[0];
	    $id_jo = $JOArr[$key];
			if($mode=="FG") { $sqlcri=""; } else { $sqlcri="and id_jo='$id_jo'"; }
			$sql="select * from bppb where bppbno='$txtbppbno_ri' 
	    	and id_item='$id_item' $sqlcri ";
	    $databppb=mysql_fetch_array(mysql_query($sql));
	    $txtid_item_fg=$databppb['id_item_fg'];
	    $txtunit=$databppb['unit'];
	    $txtcurr=$databppb['curr'];
	    $txtprice=$databppb['price'];
	    $txtjam_masuk="";
	    $txtberat_bersih=0;
	    $txtberat_kotor=0;
	    $txtnomor_mobil="";
	    $txtpono="";
	    $txtid_supplier=$databppb['id_supplier'];
	    $txtinvno=$_POST['txtsjno2'];
	    $txtkpno=$databppb['kpno'];
	    $txtid_gudang=$databppb['id_gudang'];
	    $txtid_so_det=$databppb['id_so_det'];
	    $txtnomor_rak="";
	    $retur="Y";
	    $sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,jam_masuk,berat_bersih,berat_kotor,nomor_mobil,pono,id_supplier,
				invno,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,tujuan,username,use_kite,nomor_aju,tanggal_aju,
				kpno,id_gudang,nomor_rak,status_retur,bppbno_ri,bppbno,id_jo,id_so_det)
				values ('$id_item','$txtid_item_fg','$txtqtyri','$txtunit','$txtcurr','$txtprice','$txtremark','$txtjam_masuk','$txtberat_bersih',
				'$txtberat_kotor','$txtnomor_mobil','$txtpono','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate',
				'$txtrino','$txtrino2','$tgl_ri','$jenis_dok','$txttujuan','$user','1','$txtbcaju','$txttglaju','$txtkpno',
				'$txtid_gudang','$txtnomor_rak','$retur','$txtbppbno_ri','$txtbppbno_ri','$id_jo','$txtid_so_det')";
			insert_log($sql,$user);
			calc_stock($cbomat,$id_item);
	  }
	}	
	//UPDATE PONO
	update_pono($txtrino2);	
	$_SESSION['msg'] = "Data Berhasil Disimpan Dengan Nomor RI : ".$txtrino." (".$txtrino2.")";
	echo "<script>window.location.href='../forms/?mod=20v&mode=$mode';</script>";
}
?>