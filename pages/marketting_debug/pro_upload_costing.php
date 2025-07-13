<?php
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$tgl_down=date('Y-m-d H:i:s');

include "excel_reader2.php";

$data = new Spreadsheet_Excel_Reader($_FILES['txtfile']['tmp_name']);
$jmlbaris = $data->rowcount($sheet_index=0);

$sql="delete from act_costing_temp where username='$user' and sesi='$sesi'";
insert_log($sql,$user);

for ($i=2; $i<=$jmlbaris; $i++)
{	$cost_date=$data->val($i, 1, 0);
	if($cost_date=="")
	{ $_SESSION['msg']="XCosting Date Tidak Boleh Kosong. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$cost_date=fd($data->val($i, 1, 0));
	$notes=$data->val($i, 2, 0);
	$status=$data->val($i, 3, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='ST_CST' and nama_pilihan='$status'");
	if($cek=="")
	{ $_SESSION['msg']="XStatus Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$status_order=$data->val($i, 4, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='BUS_TYPE' and nama_pilihan='$status_order'");
	if($cek=="")
	{ $_SESSION['msg']="XStatus Order Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$buyer_code=$data->val($i, 5, 0);
	$cek=flookup("supplier","mastersupplier","supplier_code='$buyer_code' and tipe_sup='C'");
	if($cek=="")
	{ $_SESSION['msg']="XKode Customer Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$product=$data->val($i, 6, 0);
	$cek=flookup("product_group","masterproduct","product_group='$product' and product_group!=''");
	if($cek=="")
	{ $_SESSION['msg']="XProduct Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$curr=$data->val($i, 7, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Curr' and nama_pilihan='$curr'");
	if($cek=="")
	{ $_SESSION['msg']="XCurr Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$vat=$data->val($i, 8, 0);
	$cfm_price=$data->val($i, 9, 0);
	$item_code=$data->val($i, 10, 0);
	$cek=flookup("goods_code","masteritem","goods_code='$item_code' and goods_code!=''");
	if($cek=="")
	{ $_SESSION['msg']="XBahan Baku Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$curr_bb=$data->val($i, 11, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Curr' and nama_pilihan='$curr_bb'");
	if($cek=="")
	{ $_SESSION['msg']="XCurr Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$cek=flookup("curr","masterrate","tanggal='$cost_date' and curr='USD'");
	if($cek=="")
	{ $_SESSION['msg']="XRate Tidak Ditemukan"; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$price=$data->val($i, 12, 0);
	if(!is_numeric($price))
	{ $_SESSION['msg']="XPrice Tidak Valid. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$cons=$data->val($i, 13, 0);
	if(!is_numeric($cons))
	{ $_SESSION['msg']="XCons Tidak Valid. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$unit=$data->val($i, 14, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Satuan' and nama_pilihan='$unit'");
	if($cek=="")
	{ $_SESSION['msg']="XUnit Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$allow=$data->val($i, 15, 0);
	$material_source=$data->val($i, 16, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Mat_Sour' and nama_pilihan='$material_source'");
	if($cek=="")
	{ $_SESSION['msg']="XMaterial Source Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../marketting/?mod=23';</script>";
	}
	$sql="insert into act_costing_temp (cost_date,notes,status,status_order,buyer_code,product,curr,vat,cfm_price,
		item_code,curr_bb,price,cons,unit,allow,material_source,tgl_down,username,sesi) 
		values ('$cost_date','$notes','$status','$status_order','$buyer_code','$product','$curr','$vat','$cfm_price',
		'$item_code','$curr_bb','$price','$cons','$unit','$allow','$material_source',
		'$tgl_down','$user','$sesi')";
	#echo "<br>".$sql; # FOR TEST
	insert_log($sql,$user);
}
echo "<script>window.location.href='../marketting/?mod=23R';</script>";
?>