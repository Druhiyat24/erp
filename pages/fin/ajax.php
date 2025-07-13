<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];
if ($modenya=="cari_bank")
{	$crinya = $_REQUEST['cri_item'];
	$sql = "select id isi,concat(nama_bank,' ',no_rek) tampil from masterbank 
         where curr='$crinya' order by nama_bank";
    IsiCombo($sql,"",'Pilih Nama Bank');
}
if ($modenya=="cari_list_trx")
{	$from = $_REQUEST['tglfr'];
	$to = $_REQUEST['tglto'];
	$trx = $_REQUEST['trx'];
	if($from!="" and $to!="")
	{	$from=fd($from);
		$to=fd($to);
		if ($trx=="AP")
    { $sql = "select concat('AP|',invno,'|',bcno) isi,concat(group_concat(distinct bpbno),'|',pono,'|',supplier,'|',invno,'|',jenis_dok,'(',bcno,')') tampil from 
        bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier
        where area in ('L','I') and bpbdate between '$from' and '$to' 
        and jenis_dok!='INHOUSE'  
        group by concat('AP|',invno,'|',bcno) order by invno";
    }
    else
    { $sql = "select concat('AR|',invno,'|',bcno) isi,concat(group_concat(distinct bppbno),'|',invno,'|',supplier,'|',jenis_dok,'(',bcno,')') tampil from 
        bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier
        where area in ('L','I') and bppbdate between '$from' and '$to' 
        and jenis_dok!='INHOUSE' 
        group by concat('AR|',invno,'|',bcno) order by invno";
    }
		IsiCombo($sql,"",'Pilih No. Invoice');
	}
}
if ($modenya=="get_data_trans")
{	$transno_ori = $_REQUEST['transno'];
	$transno=explode("|",$transno_ori);
	$invno=$transno[1];
	$bcno=$transno[2];
	if (substr($transno_ori,0,2)=="AR")
	{	$sql="select supplier,no_fp,bppbdate trx_date,curr,sum(qty*price) amount,sum(qty) tqty 
			from bppb a inner join mastersupplier s 
			on a.id_supplier=s.id_supplier 
			where invno='$invno' and bcno='$bcno' ";
	}
	else
	{	$sql="select supplier,no_fp,bpbdate trx_date,curr,sum(qty*price) amount,sum(qty) tqty 
			from bpb a inner join mastersupplier s 
			on a.id_supplier=s.id_supplier 
			where invno='$invno' and bcno='$bcno' ";
	}
	$rs=mysql_fetch_array(mysql_query($sql));
	echo 
	json_encode
	(
		array
		(	$rs['supplier'],
			$rs['no_fp'],
			$rs['amount'],
			fd_view($rs['trx_date']),
			$rs['curr'],
			$rs['tqty']
		)
	);
}
if ($modenya=="get_sisa_cash")
{	$tgl_trans = fd($_REQUEST['tgl_trans']);
	$curr = $_REQUEST['curr'];
	if($curr!="" and $tgl_trans!="")
	{	$sql="select curr,sum(if(jenis_trans='PENERIMAAN',amount,0)) amt_in,
			sum(if(jenis_trans!='PENERIMAAN',amount,0)) amt_out from acc_pettycash 
			where tanggal_trans<='$tgl_trans' and curr='$curr' group by curr";
		$rs=mysql_fetch_array(mysql_query($sql));
		$sisa=$rs["amt_in"] - $rs["amt_out"];
		echo 
		json_encode
		(
			array
			(	$sisa,
				'xxx',
				'yyy' 
			)
		);
	}
	else
	{	echo 
		json_encode
		(
			array
			(	'0',
				'xxx',
				'yyy' 
			)
		);
	}
}
?>
