<?php 

include '../../include/conn.php';

include 'fungsi.php';

session_start();

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



$user = $_SESSION['username'];
$tgl_app = date("Y-m-d H:i:s");
$sesi=$_SESSION['sesi'];



$mod=$_GET['mod'];

$mode="";

$id=$_GET['id'];

$id_item=$_GET['idd'];

if(isset($_GET['app'])) { $appqc=$_GET['app']; } else { $appqc=""; }

$cbomat=flookup("mattype","masteritem","id_item='$id_item'");

$dateinput=date('Y-m-d');

$txtid_supplier=flookup("id_supplier","mastersupplier","supplier='BOOKING STOCK'");

if($txtid_supplier=="")

{	$sqlinssup="insert into mastersupplier (supplier) values ('BOOKING STOCK')";

	insert_log($sqlinssup,$user);

	$txtid_supplier=flookup("id_supplier","mastersupplier","supplier='BOOKING STOCK'");

}

if($appqc=="QC")

{

	$sql="update transfer_post set status_app_qc='Y',user_app_qc='$user',tgl_app_qc='$tgl_app' where id='$id' and id_item='$id_item'";

	insert_log($sql,$user);

					

}

else

{	

	$sql="select * from transfer_post where id='$id' and id_item='$id_item'";

	$query=mysql_query($sql);

	while($rs = mysql_fetch_array($query))

	{	$tot_kel=flookup("sum(qty)","bppb","id_item='$id_item' and id_jo='$rs[id_jo_from]' group by id_jo");

		if($tot_kel=="") {$tot_kel=0;}

		$tot_book=$rs['qty'];



		$sql="delete from upload_tpb where username='$user' and sesi='$sesi'";

		insert_log($sql,$user);



		$sqlbpb="select bpbno,bpbdate,id_jo,id_item,sum(qty) qty_in,unit from bpb where 

			id_item='$id_item' and id_jo='$rs[id_jo_from]'   

			group by bpbno,id_item,id_jo order by bpbdate";

		$quebpb=mysql_query($sqlbpb);

		while($rsbpb = mysql_fetch_array($quebpb))

		{	if($tot_kel>0 and $rsbpb['qty_in']<=$tot_kel)

			{	$tot_kel_fifo=$rsbpb['qty_in']; 

				$tot_kel = $tot_kel - $tot_kel_fifo;

			}

			else

			{	$tot_kel_fifo=$tot_kel; 

				$tot_kel = $tot_kel - $tot_kel_fifo;

			}

			$sisafifo=$rsbpb['qty_in']-$tot_kel_fifo;

			$bpbdate=fd($rsbpb['bpbdate']);

			$sql="insert into upload_tpb (username,sesi,trans_no,tanggal_daftar,id_item,id_supplier,jumlah_satuan,

				harga_satuan,stock) values ('$user','$sesi','$rsbpb[bpbno]','$bpbdate','$rsbpb[id_item]','$rsbpb[id_jo]',

				'$rsbpb[qty_in]','$tot_kel_fifo','$sisafifo')";

			insert_log($sql,$user);

		}



		#id_supplier=id_jo

		$sqlbpb="select username,sesi,trans_no bpbno,tanggal_daftar bpbdate,id_item,id_supplier id_jo,

			jumlah_satuan tqty_in,harga_satuan tqty_out,stock qty_in from upload_tpb 

			where username='$user' and sesi='$sesi' and stock>0 and  

			id_item='$id_item' and id_supplier='$rs[id_jo_from]'   

			group by trans_no,id_item,id_supplier order by tanggal_daftar";

		$quebpb=mysql_query($sqlbpb);

		while($rsbpb = mysql_fetch_array($quebpb))

		{	

			if($tot_book>0)

			{	

				if($tot_book<=$rsbpb['qty_in'] and $tot_kel<=$rsbpb['qty_in'])

				{	

					$txtbppbno=urutkan("Add_BPPB",$cbomat);

					$txtqtytrf = $tot_book;

			    $txtid_item_fg=0;

			    $txtunit=$rs['unit'];

			    $txtcurr="";

			    $txtprice="0";

			    $txtremark="";

			    $txtberat_bersih=0;

			    $txtberat_kotor=0;

			    $txtnomor_mobil="";

			    $txtinvno="";

			    $txtkpno="";

			    $txtnomor_rak="";

			    $retur="N";

			    $id_jo_to=$rs['id_jo_to'];

			    $sql = "insert into bppb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,

						bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,

						nomor_rak,status_retur,bpbno_ro,id_jo,id_jo_to,id_book) values ('$id_item','$txtid_item_fg',

						'$txtqtytrf','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',

						'$txtnomor_mobil','$txtid_supplier','$txtinvno','','','$txtbppbno',

						'$dateinput','INHOUSE','$user','1','','',

						'$txtkpno','$txtnomor_rak','$retur','$rsbpb[bpbno]','$rsbpb[id_jo]','$id_jo_to','$id')";

					insert_log($sql,$user);

					

					$line_item="";

			  	$id_jo=$rs['id_jo_to'];

			  	$id_jo_from=$rs['id_jo_from'];

			  	$txtbpbno = urutkan("Add_BPB",$cbomat);

			  	$sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,

						id_supplier,invno,bcno,bcdate,bpbno,bpbdate,jenis_dok,tujuan,username,use_kite,nomor_aju,tanggal_aju,

						kpno,id_jo,id_jo_from,id_book,bpbno_from)

						values ('$id_item','$txtid_item_fg','$txtqtytrf','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',

						'$txtberat_kotor','','$txtid_supplier','$txtinvno','','','$txtbpbno',

						'$dateinput','INHOUSE','','$user','1','','','$txtkpno',

						'$id_jo','$id_jo_from','$id','$rsbpb[bpbno]')";

					insert_log($sql,$user);

					calc_stock($cbomat,$id_item);

					$sql="update transfer_post set status_app='Y',user_app='$user',tgl_app='$tgl_app' where id='$id' and id_item='$id_item'";

					insert_log($sql,$user);

					$tot_book = 0;

				}

				else

				{	

					$txtbppbno=urutkan("Add_BPPB",$cbomat);

					$txtqtytrf = $rsbpb['qty_in'];

			    $txtid_item_fg=$rsbpb['id_item_fg'];

			    $txtunit=$rs['unit'];

			    $txtcurr="";

			    $txtprice="0";

			    $txtremark="";

			    $txtberat_bersih=0;

			    $txtberat_kotor=0;

			    $txtnomor_mobil="";

			    $txtinvno="";

			    $txtkpno=$rsbpb['kpno'];

			    $txtnomor_rak="";

			    $retur="N";

			    $id_jo_to=$rs['id_jo_to'];

			    $sql = "insert into bppb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,

						bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,

						nomor_rak,status_retur,bpbno_ro,id_jo,id_jo_to,id_book) values ('$id_item','$txtid_item_fg',

						'$txtqtytrf','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',

						'$txtnomor_mobil','$txtid_supplier','$txtinvno','','','$txtbppbno',

						'$dateinput','INHOUSE','$user','1','','',

						'$txtkpno','$txtnomor_rak','$retur','$rsbpb[bpbno]','$rsbpb[id_jo]','$id_jo_to','$id')";

					insert_log($sql,$user);

					

					$line_item="";

			  	$id_jo=$rs['id_jo_to'];

			  	$id_jo_from=$rs['id_jo_from'];

			  	$txtbpbno = urutkan("Add_BPB",$cbomat);

			  	$sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,

						id_supplier,invno,bcno,bcdate,bpbno,bpbdate,jenis_dok,tujuan,username,use_kite,nomor_aju,tanggal_aju,

						kpno,id_jo,id_jo_from,id_book,bpbno_from)

						values ('$id_item','$txtid_item_fg','$txtqtytrf','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',

						'$txtberat_kotor','','$txtid_supplier','$txtinvno','','','$txtbpbno',

						'$dateinput','INHOUSE','','$user','1','','','$txtkpno',

						'$id_jo','$id_jo_from','$id','$rsbpb[bpbno]')";

					insert_log($sql,$user);

					calc_stock($cbomat,$id_item);

					$sql="update transfer_post set status_app='Y',user_app='$user',tgl_app='$tgl_app' where id='$id' and id_item='$id_item'";

					insert_log($sql,$user);

					$tot_book = $tot_book - $rsbpb['qty_in'];

				}

			}

		}

	} 

}

$_SESSION['msg'] = "Data Berhasil Disimpan";

echo "<script>window.location.href='../forms/?mod=$mod';</script>";

?>