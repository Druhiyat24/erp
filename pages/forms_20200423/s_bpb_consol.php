<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$mod=$_GET['mod'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
$txtberat_bersih=0;
$txtberat_kotor=0;
$txtprice=0;
$txtremark="";
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_POST['txttujuan'])) { $txttujuan=nb($_POST['txttujuan']); } else { $txttujuan = ""; }
$txtkkbc = $_POST['txtkkbc'];
if (isset($_POST['txtcurr'])) { $txtcurr=nb($_POST['txtcurr']); } else { $txtcurr = ""; }
if (isset($_POST['txtparitem']) and $mod=="51a") { $txtid_item_fg = $_POST['txtparitem']; } else { $txtid_item_fg = ""; }
if (isset($_POST['txtkpno'])) { $txtkpno=nb($_POST['txtkpno']); } else { $txtkpno=""; }
if ($txtid_item_fg=="") 
{ $txtid_item_fg = $txtkpno; 
	$txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'");
}
else
{ $txtkpno = flookup("kpno","masterstyle","id_item='$txtid_item_fg'"); }
$txtid_supplier=nb($_POST['txtid_supplier']);
$txtinvno=nb($_POST['txtinvno']);
if (isset($_POST['txtid_gudang'])) { $txtid_gudang = $_POST['txtid_gudang']; } else { $txtid_gudang=0; }
if (isset($_POST['txtbcno'])) { $txtbcno=nb($_POST['txtbcno']); } else { $txtbcno=""; }
if (isset($_POST['txtbcdate'])) { $txtbcdate = fd($_POST['txtbcdate']); } else { $txtbcdate=""; } 
if (isset($_POST['txtbcaju'])) { $txtbcaju=nb($_POST['txtbcaju']); } else { $txtbcaju=""; }
if (isset($_POST['txttglaju'])) { $txttglaju = fd($_POST['txttglaju']); } else { $txttglaju = ""; } 
$txtreqno="";
if (isset($_POST['txtnomor_rak'])) {$txtnomor_rak=nb($_POST['txtnomor_rak']);} else {$txtnomor_rak="";}
if (isset($_POST['txtstatus_kb'])) { $status_kb=nb($_POST['txtstatus_kb']); } else { $status_kb = ""; }
$txtreqdate = fd($_POST['txtbpbdate']);
$id_jo="";
if ($bpbno=="")
{	
	$txtqty = $_POST['txtqty'];
  $txtid_item=$_POST['txtitem'];
  $line_item="";
	$id_jo=$_POST['txtreqno'];
	$txtunit=$_POST['txtunit'];
	$txtcurr="";
	$txtprice=0;
	$cbomat = flookup("mattype","masteritem","id_item='$txtid_item'");
		
	$id_cs=flookup("so.id_cost","jo_det jod inner join so on jod.id_so=so.id","jod.id_jo='$id_jo'");
	$id_gen=flookup("id_gen","masteritem","id_item='$txtid_item'");
	$id_so_det=flookup("sod.id","jo_det jod inner join so on jod.id_so=so.id inner join so_det sod on so.id=sod.id_so","jod.id_jo='$id_jo'");
	$id_item_cd=flookup("e.id","mastergroup a inner join mastersubgroup s on a.id=s.id_group
    inner join mastertype2 d on s.id=d.id_sub_group
    inner join mastercontents e on d.id=e.id_type
    inner join masterwidth f on e.id=f.id_contents 
    inner join masterlength g on f.id=g.id_width
    inner join masterweight h on g.id=h.id_length
    inner join mastercolor i on h.id=i.id_weight
    inner join masterdesc j on i.id=j.id_color","j.id='$id_gen'");
	$cek=flookup("count(*)","act_costing_mat","id_act_cost='$id_cs' and id_item='$id_item_cd'");
	if ($cek=="0" or $cek=="")
	{
		if ($txtqty>0)
		{	if ($txtreqno=="") 
			{ $txtreqno = urutkan("Add_BPB",$cbomat); 
				if($gen_nomor_int=="Y")
				{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
					$date=fd($txtreqdate);
					$cri2=$cbomat2."/IN/".date('my',strtotime($date));
					$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
				}
				else
				{ $txtbpbno2=""; }
			}
			$cek = 0;
			if ($cek=="0")
			{	
				$sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,
					id_supplier,invno,bcno,bcdate,bpbno,bpbdate,bpbno_int,jenis_dok,tujuan,nomor_kk_bc,
					username,use_kite,nomor_aju,tanggal_aju,kpno,id_jo)
					values ('$txtid_item','$txtid_item_fg','$txtqty','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih',
					'$txtberat_kotor','','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate','$txtreqno',
					'$txtreqdate','$txtbpbno2','$status_kb','$txttujuan','$txtkkbc','$user','1','$txtbcaju','$txttglaju','$txtkpno',
					'$id_jo')";
				insert_log($sql,$user);
				calc_stock($cbomat,$txtid_item);	
				#echo $sql."<br>";
			}
			$sql = "insert into act_costing_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
				values ('$id_cs','$id_item_cd','0','$txtqty',
				'$txtunit','0','','B')";
			insert_log($sql,$user);
			$sql="insert into bom_jo_item (id_jo,id_so_det,dest,id_item,cons,unit,id_panel,username,dateinput,status,rule_bom,posno)
    		values ('$id_jo','$id_so_det','','$id_gen','$txtqty','$txtunit',
    		'0','$user','$txtreqdate','M','ALL COLOR ALL SIZE','')";
    	insert_log($sql,$user);
		}
		$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtreqno;
	}
	else
	{
		$_SESSION['msg']="XData Sudah Ada";
	}
	# END COPAS SAVE ADD
}
echo "<script>window.location.href='../forms/?mod=40v&mode=Bahan_Baku';</script>";
?>