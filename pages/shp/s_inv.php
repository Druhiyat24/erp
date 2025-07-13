<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
include '../forms/journal_interface.php';
session_start();
//die();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
//$mod2=$_GET['mod']."v";
$mode="";
$txtinvno = nb($_POST['txtinvno']);
$txtinvdate = fd($_POST['txtinvdate']);
$type_invoice = nb($_POST['type_invoice']);
if($txtinvno=="") 
{ //$date=fd($txtinvdate);
$date=date('Y-m-d');
	if($type_invoice == '1'){
		$cri2="LOC/EXIM-NAG/".date('Y',strtotime($date));
		$txtinvno=urutkan_inq_local("LOC-".date('Y',strtotime($date)),$cri2); 
		$mod2="3v_L";
	}else if($type_invoice == '2'){
		$cri2="EXP/EXIM-NAG/".date('Y',strtotime($date));
		$txtinvno=urutkan_inq("EXP-".date('Y',strtotime($date)),$cri2); 	
		
		$mod2="3v";
	}
	
	
	$packing_list = generate_packing_list("PL", $date);
	
}
$txtid_buyer           ='';
$txtconsignee          ='';
$txtshipper            ='';
$txtnotify_party       ='';
$txtwsno               ='';
$txtcountry_of_origin  ='';
$txtmanufacture_address='';
$txtvessel_name        ='';
$txtport_of_loading    ='';
$txtport_of_discharge  ='';
$txtport_of_entrance   ='';
$txtlc_no              ='';
$txtlc_issue_by        ='';
$txths_code            ='';
$txtetd                ='';
$txteta                ='';
$txteta_lax            ='';
$txtid_pterms          ='';
$txtshipped_by         ='';
$txtroute              ='';
$txtship_to            ='';
$txtnw                 ='';
$txtgw                 ='';
$txtmeasurement        ='';
$txtcontainer_no       ='';
$txtseal_no            ='';
$faktur_pajak          ='';
$v_userpost            =$_SESSION['username'];

if(ISSET($_POST['txtid_buyer'])){
	$txtid_buyer=nb($_POST['txtid_buyer']);
	}
if(ISSET($_POST['txtconsignee'])){
	$txtconsignee=nb($_POST['txtconsignee']);
	}
if(ISSET($_POST['txtshipper'])){
	$txtshipper=nb($_POST['txtshipper']);}
if(ISSET($_POST['txtnotify_party'])){
	$txtnotify_party=nb($_POST['txtnotify_party']);}
if(ISSET($_POST['txtwsno'])){
	$txtwsno=nb($_POST['txtwsno']);
	}
if(ISSET($_POST['txtcountry_of_origin'])){
	$txtcountry_of_origin=nb($_POST['txtcountry_of_origin']);
	}
if(ISSET($_POST['txtmanufacture_address'])){
	$txtmanufacture_address=nb($_POST['txtmanufacture_address']);
	}
if(ISSET($_POST['txtvessel_name'])){
	$txtvessel_name=nb($_POST['txtvessel_name']);
	}
if(ISSET($_POST['txtport_of_loading'])){
	$txtport_of_loading=nb($_POST['txtport_of_loading']);
	}
if(ISSET($_POST['txtport_of_discharge'])){
	$txtport_of_discharge=nb($_POST['txtport_of_discharge']);
	}
if(ISSET($_POST['txtport_of_entrance'])){
	$txtport_of_entrance=nb($_POST['txtport_of_entrance']);
	}
if(ISSET($_POST['txtlc_no'])){
	$txtlc_no=nb($_POST['txtlc_no']);
	}
if(ISSET($_POST['txtlc_issue_by'])){
	$txtlc_issue_by=nb($_POST['txtlc_issue_by']);
	}
if(ISSET($_POST['txths_code'])){
	$txths_code=nb($_POST['txths_code']);
	}
if(ISSET($_POST['txtetd'])){
	$txtetd=fd($_POST['txtetd']);
	}
if(ISSET($_POST['txteta'])){
	$txteta=fd($_POST['txteta']);
	}
if(ISSET($_POST['txteta_lax'])){
	$txteta_lax=fd($_POST['txteta_lax']);
	}
if(ISSET($_POST['txtid_pterms'])){
	$txtid_pterms=nb($_POST['txtid_pterms']);
	}
if(ISSET($_POST['txtshipped_by'])){
	$txtshipped_by=nb($_POST['txtshipped_by']);
	}
if(ISSET($_POST['txtroute'])){
	$txtroute=nb($_POST['txtroute']);
	}
if(ISSET($_POST['txtship_to'])){
	$txtship_to=nb($_POST['txtship_to']);
	}
if(ISSET($_POST['txtnw'])){
	$txtnw=nb($_POST['txtnw']);
	}
if(ISSET($_POST['txtgw'])){
	$txtgw=nb($_POST['txtgw']);
	}
if(ISSET($_POST['txtmeasurement'])){
	$txtmeasurement=nb($_POST['txtmeasurement']);
	}
if(ISSET($_POST['txtcontainer_no'])){
	$txtcontainer_no=nb($_POST['txtcontainer_no']);
	}
if(ISSET($_POST['txtseal_no'])){
	$txtseal_no=nb($_POST['txtseal_no']);
	}
if(ISSET($_POST['faktur_pajak'])){
	$faktur_pajak=nb($_POST['faktur_pajak']);
	}

$cek = flookup("count(*)","invoice_header","invno='$txtinvno'");
if ($cek=="0")
{	$sql = "insert into invoice_header (invno,date_paclist,id_buyer,consignee,shipper,notify_party,country_of_origin,manufacture_address,vessel_name,port_of_loading,port_of_discharge,port_of_entrance,lc_no,lc_issue_by,hs_code,etd,eta,eta_lax,id_pterms,shipped_by,route,ship_to,nw,gw,measurement,container_no,seal_no,n_typeinvoice,v_fakturpajak,v_userpost,v_codepaclist)
		values ('$txtinvno','$txtinvdate','$txtid_buyer','$txtconsignee','$txtshipper','$txtnotify_party','$txtcountry_of_origin','$txtmanufacture_address','$txtvessel_name','$txtport_of_loading','$txtport_of_discharge','$txtport_of_entrance','$txtlc_no','$txtlc_issue_by','$txths_code','$txtetd','$txteta','$txteta_lax','$txtid_pterms','$txtshipped_by','$txtroute','$txtship_to','$txtnw','$txtgw','$txtmeasurement','$txtcontainer_no','$txtseal_no','$type_invoice','$faktur_pajak','$v_userpost','$packing_list')";
	insert_log($sql,$user); 
	$id_inv=flookup("id","invoice_header","invno='$txtinvno'");
	$QtyArr = $_POST['itemqty'];
	$UnitArr = $_POST['itemunit'];
	$PxArr = $_POST['itempx'];
	foreach ($QtyArr as $key => $value) 
	{	if ($value>0)
		{	$id_so_det=$key;
			$qty=$QtyArr[$key];
			$unit=$UnitArr[$key];
			$price=$PxArr[$key];
			$sql = "insert into invoice_detail (id_inv,id_so_det,qty,unit,price)
				values ('$id_inv','$id_so_det','$qty','$unit','$price')";
			insert_log($sql,$user);
		}
	}
	
	//get tanggal bppb
$sql="SELECT 
                ih.invno
                ,ih.invdate
                ,(id.qty * id.price) invoice_amount
                ,so.curr
                ,ms.supplier
                ,ms.vendor_cat
                ,ms.area
				,sd.id idso
				,ifnull(bppb.bppbno,'')bppbno
				,bppb.bppbdate
				,ifnull(bpb.pono,'')pono
                ,CONCAT(mp.product_item,' - ', sd.color, ' - ',sd.size) description
                ,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order
                ,'A' grade
            FROM 
            invoice_header ih
            LEFT JOIN invoice_commercial ic ON ic.v_noinvoicecommercial = ih.id
            LEFT JOIN invoice_detail id ON ih.id = id.id_inv
            LEFT JOIN so_det sd ON sd.id = id.id_so_det
			LEFT JOIN bpb  ON bpb.id_so_det = sd.id
            LEFT JOIN so ON so.id = sd.id_so
			LEFT JOIN bppb ON sd.id = bppb.id_so_det
            LEFT JOIN act_costing ac ON so.id_cost = ac.id
            LEFT JOIN masterproduct mp ON ac.id_product = mp.id
            LEFT JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
            WHERE 
                ih.invno = '$txtinvno' GROUP BY ih.invno";
	
$stmt = mysql_query($sql);
while($row = mysql_fetch_array($stmt)){
	$bppbno = $row['bppbno'];
	$bppbdate = $row['bppbdate'];

}	
	//get tanggal bppb
	
	
$id_header = flookup("id","invoice_header","1 ORDER BY id DESC LIMIT 1");
$sql = "INSERT INTO invoice_commercial (v_noinvoicecommercial,n_idinvoiceheader,bpbno) VALUES('$txtinvno','$id_header','$bppbno')";
	insert_log($sql,$user);
$sql = "UPDATE invoice_header SET invdate='$bppbdate',date_paclist='$bppbdate' WHERE id = '$id_header'";
	insert_log($sql,$user);	
	//insert_inv_sales($txtinvno,$faktur_pajak);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../shp/?mod=$mod2&mode=$mode';
	</script>";
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>
		 window.location.href='../shp/?mod=$mod2&mode=$mode';
	</script>";
}
?>