<?php
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$tgl_down=date('Y-m-d H:i:s');

include "excel_reader2.php";

$sql_all=mysql_query("select *,s.id_supplier id_buyer,d.id id_product,d.product_item,f.id_item  
	from act_costing_temp a inner join mastersupplier s on a.buyer_code=s.supplier_code 
	inner join masterproduct d on a.product=d.product_group 
	inner join masteritem f on a.item_code=f.goods_code where username='$user' and sesi='$sesi'");
$rs=mysql_fetch_array($sql_all);
#Costing Header
$id_buyer=$rs['id_buyer'];
$date=fd($rs['cost_date']);
$cri2="CST/".date('my',strtotime($date));
$txtcost_no=urutkan_inq("CST-".date('Y',strtotime($date)),$cri2); 
$sql="select supplier_code from mastersupplier 
	where id_supplier='$id_buyer'"; 
$rs2=mysql_fetch_array(mysql_query($sql));
$kd_buyer=$rs2['supplier_code'];
$cri2=$kd_buyer."/".date('my',strtotime($date));
$txtkpno=urutkan_inq($kd_buyer."-".date('Y',strtotime($date)),$cri2);
$txtcost_date=$rs['cost_date'];
$txtid_smode=0;
$txtsmv_min=0;
$txtsmv_sec=0;
$txtbook_min=0;
$txtbook_sec=0;
$txtnotes=$rs['notes'];
$txtdeldate=$rs['cost_date'];
$txtattach_file="";
$id_product=$rs['id_product'];
$styleno=$rs['product_item'];
$qty=0;
$status=$rs['status'];
$status_order=$rs['status_order'];
$txtcurr=$rs['curr'];
$txtvat=$rs['vat'];
$txtdeal=0;
$txtga=0;
$txtunit=$rs['unit'];
$txtcfm=$rs['cfm_price'];
$txtcomm=0;
$dateinput=date("Y-m-d H:i:s");

$sql = "insert into act_costing (id_pre_cost,cost_no,cost_date,
	kpno,id_smode,smv_min,smv_sec,book_min,book_sec,notes,deldate,
	attach_file,id_buyer,id_product,styleno,qty,status,status_order,
	username,curr,vat,deal_allow,ga_cost,unit,cfm_price,comm_cost,dateinput)
	values ('','$txtcost_no','$txtcost_date',
	'$txtkpno','$txtid_smode','$txtsmv_min','$txtsmv_sec',
	'$txtbook_min','$txtbook_sec','$txtnotes','$txtdeldate',
	'$txtattach_file','$id_buyer','$id_product','$styleno',
	'$qty','$status','$status_order','$user','$txtcurr','$txtvat','$txtdeal','$txtga',
	'$txtunit','$txtcfm','$txtcomm','$dateinput')";
insert_log($sql,$user);

$sql_all2=mysql_query("select *,s.id_supplier id_buyer,d.id id_product,d.product_item,f.id_item  
	from act_costing_temp a inner join mastersupplier s on a.buyer_code=s.supplier_code 
	inner join masterproduct d on a.product=d.product_group 
	inner join masteritem f on a.item_code=f.goods_code where username='$user' and sesi='$sesi'");
$id_cs = flookup("id","act_costing","cost_no='$txtcost_no'");
$id_cd = "";
while($data = mysql_fetch_array($sql_all2))
{	$id_item_cd = $data['id_item'];
	$curr_cd = $data['curr_bb'];
	
	$px=$data['price'];
	$tgl=$data['cost_date'];
	if ($curr_cd=="USD")
	{	#$rate=flookup("rate_jual","masterrate","curr='USD' 
		#	and tanggal<='".fd($tgl)."' order by tanggal desc limit 1");
		#$hsl=$px * $rate;
		$hsl=$px;
		$j_rate_cd="J";
	}
	else if ($curr_cd=="IDR")
	{	#$rate=flookup("rate_beli","masterrate","curr='USD' 
		#	and tanggal<='".fd($tgl)."' order by tanggal desc limit 1");
		#$hsl=round($px/$rate,6);
		$hsl=$px;
		$j_rate_cd="B";
	}
	$price_cd = $hsl;
	$cons_cd = $data['cons'];
	$unit_cd = nb($data['unit']);
	$allow_cd = $data['allow'];
	$mat_sour_cd = $data['material_source'];
	if ($id_cd=="")
	{	$sql = "insert into act_costing_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate)
			values ('$id_cs','$id_item_cd','$price_cd','$cons_cd',
			'$unit_cd','$allow_cd','$mat_sour_cd','$j_rate_cd')";
		insert_log($sql,$user);
	}
}
$_SESSION['msg']="Upload Berhasil. Costing # ".$txtcost_no;
echo "<script>window.location.href='../marketting/?mod=5L';</script>";
?>