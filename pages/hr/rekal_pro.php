<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
$user=$_SESSION['username'];
if ($mode=="Sal")
{	$txtfrom = fd(strtoupper($_POST['txtfrom']));
	$txtto = fd(strtoupper($_POST['txtto']));
	$txtpaydate = fd(strtoupper($_POST['txtpaydate']));

	#VALIDASI PTKP TIDAK ADA DIMASTER PTKP
	#VALIDASI UANG MAKAN TIDAK ADA DIMASTER UANG MAKAN
	#VALIDASI GAJI BERSIH TIDAK ADA DIMASTER SALARY

	#HANYA TEST
	#$sql="delete from hr_salaryrecord";
	#insert_log($sql,$user);
	#HANYA TEST

	$query = mysql_query("select a.nik,f.gaji_pokok,0 uang_makan,d.ptkp,
		a.set_bpjstk,a.set_bpjskes 
		from hr_masteremployee a inner join hr_masterptkp d on a.ptkp=d.ptkpcode
		inner join hr_mastersalary f on a.nik=f.nik
		where selesai_kerja='0000-00-00' or selesai_kerja is null");
	while($data = mysql_fetch_array($query))
	{	$nik=$data['nik'];
		$gaji_bersih=$data['gaji_pokok'];
		$ptkp=$data['ptkp'];
		$uang_makan=$data['uang_makan'];
		$hari_kerja=25; #AMBIL DARI TIMECARD
		$t_uang_makan=$uang_makan * $hari_kerja;
		$persen_bpjs_tk=flookup("persen_bpjs_tk","hr_parameter_payroll","persen_bpjs_tk<>0");
		# BPJS TENAGA KERJA
		if ($data['set_bpjstk']=="1")
		{	$bpjs_tk=$persen_bpjs_tk/100 * $gaji_bersih;
			$bpjs_tk=round($bpjs_tk,2);	
		}
		else
		{	$bpjs_tk=0;	}
		# BPJS PENSIUN
		$max_bpjs_pen=flookup("max_bpjs_pen","hr_parameter_payroll","max_bpjs_pen<>0");
		$persen_bpjs_pen=flookup("persen_bpjs_pen","hr_parameter_payroll","persen_bpjs_pen<>0");
		if ($gaji_bersih>$max_bpjs_pen)
		{	$bpjs_pen=$persen_bpjs_pen/100 * $max_bpjs_pen; }
		else
		{	$bpjs_pen=$persen_bpjs_pen/100 * $gaji_bersih; }
		$bpjs_pen=round($bpjs_pen,2);

		# BPJS KESEHATAN
		if ($data['set_bpjskes']=="1")
		{	$max_bpjs_kes=flookup("max_bpjs_kes","hr_parameter_payroll","max_bpjs_kes<>0");
			$persen_bpjs_kes=flookup("persen_bpjs_kes","hr_parameter_payroll","persen_bpjs_kes<>0");
			if ($gaji_bersih>$max_bpjs_kes)
			{	$bpjs_kes=$persen_bpjs_kes/100 * $max_bpjs_kes; }
			else
			{	$bpjs_kes=$persen_bpjs_kes/100 * $gaji_bersih; }
			$bpjs_kes=round($bpjs_kes);
		}
		else
		{	$bpjs_kes=0; }
		$deduction=flookup("amount","hr_deduction","nik='$nik' and paydate='$txtpaydate'");
		#$t_onsite=flookup("amount","hr_onsite","nik='$nik' and paydate='$txtpaydate'");
		$t_onsite=0;
		
		$t_ot = flookup("sum(pot)","hr_spl","nik='$nik' and tanggal between '$txtfrom' and '$txtto' ");
		$tarif_ot = round($gaji_bersih / 173,2);
		$ot = $t_ot * $tarif_ot;

		$t_pendapatan=$gaji_bersih+$t_uang_makan+$t_onsite+$ot;
		$t_pengurang=$bpjs_tk+$bpjs_pen+$bpjs_kes+$deduction;
		$t_gaji=$t_pendapatan-$t_pengurang;
		$cek=flookup("nik","hr_salaryrecord","nik='$nik' and paydate='$txtpaydate'");
		if ($cek!="")
		{	$sql="delete from hr_salaryrecord where nik='$nik' and paydate='$txtpaydate' ";
			insert_log($sql,$user);
		}
		$sql="insert into hr_salaryrecord (nik,mulai,selesai,paydate,gaji_pokok,hari_kerja,
			t_makan,t_onsite,bpjs_tk,bpjs_pen,bpjs_kes,deduction,total_gaji,overtime) 
			values ('$nik','$txtfrom','$txtto','$txtpaydate','$gaji_bersih','$hari_kerja',
			'$t_uang_makan','$t_onsite','$bpjs_tk','$bpjs_pen','$bpjs_kes','$deduction','$t_gaji','$ot')";
		insert_log($sql,$user);	
	}
	$_SESSION['msg'] = "Rekalkulasi Selesai";
	echo "<script>
		window.location.href='../hr/?mod=21&pay=$txtpaydate';
		</script>";
}
else
{	#$txtpaydate = fd(strtoupper($_POST['txtpaydate']));
	$txtpaydate="2017-01-31";
	$query = mysql_query("select * from hr_salaryrecord where paydate='$txtpaydate'");
	while($data = mysql_fetch_array($query))
	{	$persen_bpjs_tk=flookup("persen_bpjs_tk_company","hr_parameter_payroll","persen_bpjs_tk_company<>0");
		$q=32666.6666666667;
		$i=32666.6666666667;
		
		$g=$data['gaji_pokok'];
		$h=$persen_bpjs_tk/100 * $g;
		$j=$data['t_makan'];
		$k=0;
		$l=0;
		$m=0;
		$n=$g+$h+$i+$j+$k+$l+$m;
		$o=2/100 * $g;
		$p=($n-$k-$i)+$q;
		if ($p*0.05<=500000)
		{	$r=$p*0.05; }
		else
		{	$r=500000; }
		$rbulat=floor($r * 100) / 100;
		$s=0;
		$t=$p-$o-$rbulat;
		$u=$t*12;
		$v=67500000; #PTKP	
		if ($u>$v) { $w=$u-$v; } else { $w=0; }
		$wbulat=fnribuan(round($w));
		if ($wbulat<=50000000 AND $wbulat>0)
		{	$x=$wbulat * 5/100; } # WITH NPWP 5% NON NPWP 6%
		else if ($wbulat>50000000 AND $wbulat<=250000000)
		{	$x=(($wbulat-50000000) * 15/100)+2500000; } # WITH NPWP 15%+2.500.000 NON NPWP 18%+3.000.000
		else if ($wbulat>250000000 AND $wbulat<=500000000)
		{	$x=((($wbulat-250000000) * 25/100)+32500000); } # WITH NPWP 25%+32.500.000 NON NPWP 30%+39.000.000
		else if ($wbulat>500000000)
		{	$x=((($wbulat-500000000)*30/100)+95000000); } # WITH NPWP 30%+95.000.000 NON NPWP 36%+114.000.000
		else
		{	$x=0; }
		$z=$x;
		$aa=$z/12;
		if ($k>0)
		{	$ab=$at; }
		else
		{	$ab=$aa; }
		$ac=0;
		$ad=0;
		$ae=$ab;
		$af=0; # PPH BLN SBLMNYA
		$ag=$ae-$af;

		$ar=32950;
		
		$ai=$ar;
		$ah=($n-$i)+$ai;
		$al=$ah-$k-$o;
		$am=$al*12+$k;
		if ($am*0.05<=6000000) { $aj=$am*0.05; } else { $aj=6000000; }
		$ak=$am-$aj;
		if ($ak>$v) { $an=$ak-$v; } else { $an=0; }
		$anbulat=fnribuan(round($an));
		if ($anbulat<=50000000 AND $anbulat>0)
		{	$ao=$anbulat * 5/100; } # WITH NPWP 5% NON NPWP 6%
		else if ($anbulat>50000000 AND $anbulat<=250000000)
		{	$ao=(($anbulat-50000000) * 15/100)+2500000; } # WITH NPWP 15%+2.500.000 NON NPWP 18%+3.000.000
		else if ($anbulat>250000000 AND $anbulat<=500000000)
		{	$ao=((($anbulat-250000000) * 25/100)+32500000); } # WITH NPWP 25%+32.500.000 NON NPWP 30%+39.000.000
		else if ($anbulat>500000000)
		{	$ao=((($anbulat-500000000)*30/100)+95000000); } # WITH NPWP 30%+95.000.000 NON NPWP 36%+114.000.000
		else
		{	$ao=0; }
		$ap=$ao*120/100;
		$aq=$ao; #KLO GA ADA NPWP $ap
		$as=$aq-$z; #KLO GA ADA NPWP $ap
		$at=round($as+$aa);
		#echo $at;
	}
	echo "<script>
					alert('Selesai')
					window.location.href='rekalkulasi.php?mode=PPh';
				</script>";
}
?>