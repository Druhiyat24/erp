<?php
$rpt = $_GET['rptid'];
if (isset($_GET['parfromv']))
{	$toexcel = "Y";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
	header("Pragma: no-cache"); 
	header("Expires: 0");
}
else
{ $toexcel = "N"; }

if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (!isset($_SESSION['username'])) { header("location:../../index.php"); }
if (!isset($_SESSION['sesi'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];;
$rpt = $_GET['rptid'];

if ($rpt=='bc23pjt') {$in_out = "In";} elseif ($rpt=='bc23') {$in_out = "In";} elseif ($rpt=='bc262msk') {$in_out = "In";}  
elseif ($rpt=='bc27msk' or $rpt=='bc27msksub') {$in_out = "In";} elseif ($rpt=='bc40lkl') {$in_out = "In";} elseif ($rpt=='bc40sewa') {$in_out = "In";} 
elseif ($rpt=='bc40subkon') {$in_out = "In";} elseif ($rpt=='bc20pibbyr') {$in_out = "In";} elseif ($rpt=='bc21pibk') {$in_out = "In";} 
elseif ($rpt=='bc24kitte') {$in_out = "In";} elseif ($rpt=='gb_perdokumen') {$in_out = "In";} else {$in_out = "Out";}

if (($in_out=='In' AND $rpt!="gb_perdokumen") OR $rpt=='inrekap') 
{	$header_cap="A. LAPORAN PEMASUKAN BARANG PER DOKUMEN PABEAN";
	$fld_cap="BUKTI PENERIMAAN BARANG";
	$fld_capt="PEMASOK / PENGIRIM";
} 
elseif ($rpt=="kite_a") 
{	$header_cap="A. LAPORAN PEMASUKAN BAHAN BAKU"; } 
elseif ($rpt=="kite_al") 
{	$header_cap="LAPORAN PEMASUKAN BAHAN BAKU (LOKAL)"; } 
elseif ($rpt=="kite_b") 
{	$header_cap="B. LAPORAN PEMAKAIAN BAHAN BAKU"; } 
elseif ($rpt=="kite_c") 
{	$header_cap="C. LAPORAN PEMAKAIAN BARANG DALAM PROSES DALAM RANGKA KEGIATAN SUBKONTRAK"; } 
elseif ($rpt=="kite_d") 
{	$header_cap="D. LAPORAN PEMASUKAN HASIL PRODUKSI"; } 
elseif ($rpt=="kite_dl") 
{	$header_cap="LAPORAN PEMASUKAN HASIL PRODUKSI (Lokal)"; } 
elseif ($rpt=="kite_e") 
{	$header_cap="E. LAPORAN PENGELUARAN HASIL PRODUKSI"; } 
elseif ($rpt=="kite_elkl") 
{	$header_cap="LAPORAN PENGELUARAN HASIL PRODUKSI (LOKAL)"; } 
elseif ($rpt=="kite_e24") 
{	$header_cap="LAPORAN PENGELUARAN BC 2.4"; } 
elseif ($rpt=="kite_e25") 
{	$header_cap="LAPORAN PENGELUARAN BC 2.5"; } 
elseif ($rpt=="kite_h") 
{	$header_cap="H. LAPORAN PENYELESAIAN WASTE/SCRAP"; } 
elseif ($in_out=='In' AND $rpt=="gb_perdokumen") 
{	$header_cap="I. LAPORAN POSISI BARANG PER DOKUMEN PABEAN"; } 
else 
{	$header_cap="B. LAPORAN PENGELUARAN BARANG PER DOKUMEN PABEAN";
	$fld_cap="BUKTI PENGELUARAN BARANG";
	$fld_capt="PEMBELI / PENERIMA";
}

if (isset($_GET['parfromv']))
{	$tglf = $_GET['parfrom'];
	$perf = date('d F Y', strtotime($tglf));
	$tglt = $_GET['parto'];
	$pert = date('d F Y', strtotime($tglt));
}
else
{	$tglf = fd($_POST['txtfrom']);
	$perf = date('d F Y', strtotime($tglf));
	$tglt = fd($_POST['txtto']);
	$pert = date('d F Y', strtotime($tglt));
}
$sql="X".$header_cap."-".$rpt." Dari ".$perf." s/d ".$pert;
insert_log($sql,$user);
?>
<!-- Header -->
<div class='box'>
	<div class='box-body'>
	<?php
	if ($rpt=="gb_perdokumen" OR $st_company=="GB")
	{ echo "GUDANG BERIKAT "; 
		echo strtoupper($nm_company);echo "<br>";
		echo $header_cap;echo "<br>";
	}
	elseif ($st_company=="KITE")
	{ echo $header_cap;echo "<br>";
		echo strtoupper($nm_company);echo "<br>";
	}
	else
	{ echo "KAWASAN BERIKAT "; 
		echo strtoupper($nm_company);echo "<br>";
		echo $header_cap;echo "<br>";
	}
	echo "PERIODE "; echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); echo "<br>";

	if ($toexcel!="Y")
	{	
		echo "
		<a class='btn btn-primary btn-s' href='?mod=view_in&uid=$user&sesi=$sesi&parfrom=$tglf&parto=$tglt&parfromv=$perf
			&partov=$pert&rptid=$rpt&dest=xls'><i class='fa fa-file-excel-o'></i> Save Excel
		</a>"; 
	}
	?>
	</div>
</div>
<div class='box'>
	<div class='box-body'>
	<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>
	<?php
		if ($rpt=="gb_perdokumen")
		{	echo "<thead>";
					echo "<tr>";
					echo "<th colspan='11'>D O K U M E N  P E M A S U K A N</th>";
					echo "<th colspan='8'>D O K U M E N  P E N G E L U A R A N</th>";
					echo "<th colspan='3'>S A L D O  B A R A N G</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>NO.</th>";
					echo "<th width='81'>JENIS DOKUMEN</th>";
					echo "<th width='81'>NOMOR</th>";
					echo "<th width='81' >TANGGAL</th>";
					echo "<th width='81' >TGL MASUK</th>";
					echo "<th width='81' >KODE BARANG</th>";
					echo "<th width='81' >SERI BARANG</th>";
					echo "<th width='81' >NAMA BARANG</th>";
					echo "<th width='81' >SATUAN</th>";
					echo "<th width='81' >JUMLAH</th>";
					echo "<th width='81' >NILAI PABEAN</th>";
					echo "<th width='81' >JENIS DOKUMEN</th>";
					echo "<th width='81' >NOMOR</th>";
					echo "<th width='81' >TANGGAL</th>";
					echo "<th width='81' >TGL KELUAR</th>";
					echo "<th width='81' >NAMA BARANG</th>";
					echo "<th width='81' >SATUAN</th>";
					echo "<th width='81' >JUMLAH</th>";
					echo "<th width='81' >NILAI PABEAN</th>";
					echo "<th width='81' >JUMLAH</th>";
					echo "<th width='81' >SATUAN</th>";
					echo "<th width='81' >NILAI PABEAN</th>";
				echo "</tr>";
			echo "</thead>";
		}
		elseif ($rpt=="kite_a" OR $rpt=="kite_al")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='20' rowspan='2'>Jenis Dokumen</th>";
					if($nm_company=="PT. YWH Garment Indonesia" and $rpt=="kite_al")
					{echo "<th colspan='3'>Dokumen Pajak</th>";}
					else
					{echo "<th colspan='3'>Dokumen Pabean</th>";}
					echo "<th colspan='2'>Bukti Penerimaan Barang</th>";
					echo "<th width='90' rowspan='2'>Kode Barang</th>";
					echo "<th width='90' rowspan='2'>Nama Barang</th>";
					echo "<th width='34' rowspan='2'>Satuan</th>";
					echo "<th width='50' rowspan='2'>Jumlah</th>";
					echo "<th width='35' rowspan='2'>Mata Uang</th>";
					echo "<th width='59' rowspan='2'>Nilai Barang</th>";
					echo "<th width='39' rowspan='2'><span class='style1'>Gudang</th>";
					if ($nm_company!="PT. Multi Sarana Plasindo")
					{	echo "<th width='101' rowspan='2'><span class='style1'>Penerima Subkontrak</th>";	}
					echo "<th width='95' rowspan='2'><span class='style1'>Negara Asal Barang</th>";
				echo "</tr>";
				echo "<tr>";
					if($nm_company=="PT. YWH Garment Indonesia" and $rpt=="kite_al")
					{	echo "<th width='50'>No. F.Pajak</th>";
						echo "<th width='50'><span class='style1'>Tgl F.Pajak</th>";
					}
					else
					{	echo "<th width='50'>Nomor PIB</th>";
						echo "<th width='50'><span class='style1'>Tanggal PIB</th>";
					}
					if ($nm_company=="PT. Multi Sarana Plasindo")
					{	echo "<th width='50'>Nomor PO</th>";	}
					else
					{	echo "<th width='50'>No Seri </th>";	}
					echo "<th width='50'>Nomor</th>";
					echo "<th width='50'><div align='left'><span class='style1'>Tanggal</th>";
				echo "</tr>";
			echo "</thead>";	
		}
		elseif ($rpt=="kite_b")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='37' rowspan='2'>No.</th>";
					echo "<th colspan='2'>Bukti Pengeluaran</th>";
					echo "<th width='218' rowspan='2'>Kode Barang</th>";
					echo "<th width='161' rowspan='2'>Nama Barang</th>";
					echo "<th width='63' rowspan='2'>Satuan</th>";
					if ($nm_company!="PT. Multi Sarana Plasindo")
					{	echo "<th colspan='2'>Jumlah</th>";	
						echo "<th width='183' rowspan='2'>Penerima Subkontrak</th>";
					}
					else
					{	echo "<th colspan='1'>Jumlah</th>";	}
				echo "</tr>";
				echo "<tr>";
					echo "<th width='79'><span class='style1'>Nomor</span></th>";
					echo "<th width='100'>Tanggal</th>";
					echo "<th width='91'><span class='style1'>Digunakan</span></th>";
					if ($nm_company!="PT. Multi Sarana Plasindo")
					{	echo "<th width='133'><span class='style1'>Disubkontrakan</span></th>";}
				echo "</tr>";
			echo "</thead>";
		}
		elseif ($rpt=="kite_c")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='38' rowspan='2'>No.</th>";
					echo "<th colspan='2'>Bukti Pengeluaran</th>";
					echo "<th width='239' rowspan='2'>Kode Barang</th>";
					echo "<th width='168' rowspan='2'>Nama Barang</th>";
					echo "<th width='66' rowspan='2'>Satuan</th>";
					echo "<th width='191' rowspan='2'>Disubkontrakan</th>";
					echo "<th width='194' rowspan='2'>Penerima Subkontrak</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th width='60'><span class='style1'>Nomor</span></th>";
					echo "<th width='60'>Tanggal</th>";
				echo "</tr>";
			echo "</thead>";
		}
		elseif ($rpt=="kite_d" or $rpt=="kite_dl")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='34' rowspan='2'>No.</th>";
					echo "<th colspan='2'>Bukti Penerimaan Barang </th>";
					echo "<th width='205' rowspan='2'>Kode Barang</th>";
					echo "<th width='146' rowspan='2'>Nama Barang</th>";
					echo "<th width='60' rowspan='2'>Satuan</th>";
					if ($nm_company=="PT. Multi Sarana Plasindo")
					{	echo "<th colspan='1'>Jumlah</th>";	}
					else
					{	echo "<th colspan='2'>Jumlah</th>";	}
					echo "<th width='299' rowspan='2'>Gudang</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th width='55'><span class='style1'>Nomor</span></th>";
					echo "<th width='56'>Tanggal</th>";
					echo "<th width='94'><span class='style1'>Dari Produksi </span></th>";
					if ($nm_company!="PT. Multi Sarana Plasindo")
					{	echo "<th width='116'><span class='style1'>Dari Subkontrak </span></th>";	}
				echo "</tr>";
			echo "</thead>";
		}
		elseif ($rpt=="kite_e" or $rpt=="kite_e24" or $rpt=="kite_e25" or $rpt=="kite_elkl")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='21' rowspan='2'>No.</th>";
					if ($rpt=="kite_e24")
					{	echo "<th colspan='2'>BC 2.4</th>";	}
					else if ($rpt=="kite_e25")
					{	echo "<th colspan='2'>BC 2.5</th>";	}
					else
					{	echo "<th colspan='2'>PEB</th>";	}
					echo "<th colspan='2'><span class='style1'>Bukti Pengeluaran Barang </span></th>";
					echo "<th width='112' rowspan='2'><span class='style1'>Pembeli / Penerima </span></th>";
					echo "<th width='93' rowspan='2'><span class='style1'>Negara Tujuan </span></th>";
					echo "<th width='70' rowspan='2'>Kode Barang</th>";
					echo "<th width='190' rowspan='2'>Nama Barang</th>";
					echo "<th width='34' rowspan='2'>Satuan</th>";
					echo "<th width='60' rowspan='2'><span class='style1'>Jumlah</span></th>";
					echo "<th width='56' rowspan='2'><span class='style1'>Mata Uang </span></th>";
					echo "<th width='186' rowspan='2'>Nilai Barang </th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th width='37'><span class='style1'>Nomor</span></th>";
					echo "<th width='42'>Tanggal</th>";
					echo "<th width='43'><span class='style1'>Nomor</span></th>";
					echo "<th width='75'><span class='style1'>Tanggal</span></th>";
				echo "</tr>";
			echo "</thead>";
		}
		elseif ($rpt=="kite_h")
		{	echo "<thead>";
				echo "<tr>";
					echo "<th width='38' rowspan='2'>No.</th>";
					echo "<th colspan='2'>BC 2.4</th>";
					echo "<th width='239' rowspan='2'>Kode Barang</th>";
					echo "<th width='168' rowspan='2'>Nama Barang</th>";
					echo "<th width='66' rowspan='2'>Satuan</th>";
					echo "<th width='191' rowspan='2'>Jumlah</th>";
					echo "<th width='194' rowspan='2'>Nilai</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th width='60'><span class='style1'>Nomor</span></th>";
					echo "<th width='60'>Tanggal</th>";
				echo "</tr>";
			echo "</thead>";
		}
		else
		{	echo "<thead>";
				echo "<tr>";
					echo "<th rowspan='2'>NO.</th>
						<th rowspan='2'>JENIS DOKUMEN</th>
						<th rowspan='2'>KATEGORI BARANG</th>";
						if ($rpt=="inrekap" or $rpt=="outrekap")
						{ echo "<th rowspan='2'>NOMOR AJU</th>"; }
						echo "
						<th colspan='2'>DOKUMEN PABEAN</th>
						<th colspan='2'>$fld_cap</th>
						<th rowspan='2'>$fld_capt</th>";
					if ($rpt=="bc25lkl" AND $nm_company=="PT. Bangun Sarana Alloy")
					{	echo "<th rowspan='2'>NO. ORDER</th>"; }
					echo "<th rowspan='2'>KODE BARANG</th>";
					echo "<th rowspan='2'>NAMA BARANG</th>";
					if ($rpt=="bc25lkl" AND $nm_company=="PT. Bangun Sarana Alloy")
					{	echo "<th rowspan='2'>SIZE</th>
							<th rowspan='2'>COLOR</th>";
					}
					echo "<th rowspan='2'>SAT</th>";
					echo "<th rowspan='2'>JUMLAH</th>";
					if ($rpt=="bc23" or $rpt=="bc23pjt")
					{	echo "<th rowspan='2'>SAT CEISA</th>
							<th rowspan='2'>JUMLAH CEISA</th>";
					}
					echo "<th colspan='2'>NILAI BARANG</th>";
					if ($rpt=="inrekap" or $rpt=="outrekap") 
					{	echo "<th rowspan='2'>BERAT BERSIH</th>
									<th rowspan='2'>BERAT KOTOR</th>
									<th rowspan='2'>TUJUAN</th>";
					}
				echo "</tr>";
				echo "<tr>";
					echo "<th>NOMOR</th>";
					echo "<th>TANGGAL</th>";
					echo "<th>NOMOR</th>";
					echo "<th>TANGGAL</th>";
					echo "<th>CURR</th>";
					echo "<th>NILAI</th>";
					if ($rpt=='bc40lkl') {echo "<th>REMARK</th>";}
				echo "</tr>";
			echo "</thead>";
		}
		
		if ($nm_company=="PT. Seyang Indonesia") 
		{$vtrans_no="a.invno";} 
		else 
		{$vtrans_no="if(a.bppbno_int!='',a.bppbno_int,a.bppbno)";}
	  #CETAK KITE
		if ($rpt=='kite_a' OR $rpt=='kite_al')
		{	if ($rpt=='kite_al')
			{	$sql3 = " and (d.area='IB' or a.use_kite='1' and jenis_dok='LOKAL')";	
				$no_seri = "'LOKAL'";
			}
			else
			{	$sql3 = " and (d.area='IB' or a.use_kite='1' and jenis_dok='BC 2.0')";	
				if ($nm_company=="PT. Multi Sarana Plasindo")
				{	$no_seri = "pono";	}
				else
				{	$no_seri = "bcno";	}
			}
			$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			if ($nm_company=="PT. Trevi Fontana")
			{	$lok_val = "'Trevi Fontana'"; }
			else
			{	$lok_val = "'$nm_company'"; }
			if($nm_company=="PT. YWH Garment Indonesia" and $rpt=="kite_al")
			{	$fldno="if(a.jenis_dok='LOKAL',no_fp,a.bcno) bcno,a.tgl_fp bcdate"; }
			else
			{	$fldno="if(a.jenis_dok='LOKAL','LOKAL',lpad(a.bcno,6,'0')) bcno,a.bcdate"; }
			$sqlkite = "select a.jenis_dok,$fldno,$no_seri no_seri,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,(a.qty*a.price) nilai_brg,$lok_val lokasi,'' cmt,
				if(d.country='',a.tujuan,d.country) country,a.nomor_aju,a.tanggal_aju,a.remark
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and s.mattype in ('A','F','BB') and bpbno not like 'FG%' and a.tujuan not like '%SUBKON%' 
				and bpbdate between '$tglf' and '$tglt' $sql3 
				order by a.bcdate,a.bpbno";
			$sql = mysql_query ($sqlkite);
			$no = 1; #nomor awal
			$no_urt_trx=1;
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			$nobc = $data['bcno'];
			$jendok = $data['jenis_dok'];
			$dtbc = $data['bcdate'];
			$notrans= $data['trans_no'];
			if ($no>1)
			{	$cri_next=$data['bcno']; }
			if ($no==1 OR $cri_next!=$cri_prev)
			{ $no_urt_trx=1; }
			$serino = $no_urt_trx; #$data['no_seri']; #dapatkan jurusan mahasiswa dari data array (row) 'jurusan' 
			$dttrans= $data['trans_date'];
			$kdbrg= $data['kode_brg'];
			$descitem= $data['itemdesc'];
			$satuan= $data['unit'];
			$qqty= $data['qty'];
			$matauang= $data['curr'];
			$nilbrg= round($data['nilai_brg'],2);
			if ($nm_company=="PT. Multi Sarana Plasindo")
			{	$lok= $data['remark'];	}
			else
			{	$lok= $data['lokasi'];	}
			$cm= $data['cmt'];
			$negara= $data['country'];
			echo "
			<tr>
				<td align='center'><span class='style1'>$jendok</span></td>
				<td align='center'><span class='style1'>$nobc</span></td>
				<td><span class='style1'>$dtbc</span></td>
				<td><span class='style1'>$serino</span></td>
				<td><span class='style1'>$notrans</span></td>
				<td><span class='style1'>$dttrans</span></td>
				<td><span class='style1'>$kdbrg</span></td>
				<td><span class='style1'>$descitem</span></td>
				<td><span class='style1'>$satuan</span></td>
				<td><span class='style1'>$qqty</span></td>
				<td><span class='style1'>$matauang</span></td>
				<td><span class='style1'>$nilbrg</span></td>
				<td><span class='style1'>$lok</span></td>";
				if ($nm_company!="PT. Multi Sarana Plasindo")
				{ echo "<td><span class='style1'>$cm</span></td>"; }
				echo "<td><span class='style1'>$negara</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			if ($no==1 OR $cri_next!=$cri_prev)
			{	$no_urt_trx++; }
			$no++;
			$cri_prev=$data['trans_no'];
			}	
		}
		if ($rpt=='kite_b')
		{	$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			$sql3 = " and (d.area='IB' or a.use_kite='1')";
			if ($nm_company == 'PT. Bosung Indonesia') {$nm_brg="concat(s.itemdesc,' Order Cut : ',a.kpno)";} else {$nm_brg="s.itemdesc";}
			$sql = mysql_query ("select if(a.jenis_dok='SUBKONTRAK',a.invno,a.bppbno) bppbno
				,a.bppbdate,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) kode_brg
				,$nm_brg itemdesc,a.unit,
				if(d.status_kb like 'SUBKON%' OR a.jenis_dok='SUBKONTRAK',0,a.qty) digunakan,
				if(d.status_kb like 'SUBKON%' OR a.jenis_dok='SUBKONTRAK',a.qty,0) dicmtkan,
				if(a.sent_to='' or isnull(a.sent_to),d.supplier,a.sent_to) sent_to from bppb a inner join masteritem s on a.id_item=s.id_item  
				left join mastersupplier d on a.sent_to=d.supplier or a.id_supplier=d.id_supplier 
				where s.mattype in ('A','F','BB') and bppbno not like 'SJ-FG%' and 
				bppbdate between '$tglf' and '$tglt' $sql3 ");
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>";
				if ($nm_company!="PT. Multi Sarana Plasindo")
				{	echo "<td><span class='style1'>$data[6]</span></td>
						<td><span class='style1'>$data[7]</span></td>";
				}
			echo "</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}	
		}
		if ($rpt=='kite_c')
		{	$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			$sql = mysql_query ("select if(a.jenis_dok like 'SUBKON%',a.invno,a.bppbno) bppbno,a.bppbdate,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) Kode_Brg,s.itemdesc,a.unit,
				if(d.status_kb like 'SUBKON%' OR a.tujuan like 'SUBKON%' OR a.jenis_dok like 'SUBKON%',a.qty,0) DiCMTKan,
				d.supplier from bppb a inner join masteritem s on a.id_item=s.id_item  left join mastersupplier d on a.id_supplier=d.id_supplier where 
				s.mattype in ('A','F','BB','C') and bppbno not like 'SJ-FG%' and 
				(d.status_kb like 'SUBKON%' OR a.tujuan like 'SUBKON%' or a.jenis_dok like 'SUBKON%') 
				and bppbdate between '$tglf' and '$tglt'");
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>
				<td><span class='style1'>$data[6]</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}
		}
		if ($rpt=='kite_d' OR $rpt=='kite_dl')
		{	$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			if ($rpt=="kite_dl") { $cril=" and jenis_dok='LOKAL'"; } else { $cril=" and jenis_dok!='LOKAL'"; }
			$sql = mysql_query ("select if(a.bpbno_int!='',a.bpbno_int,a.bpbno) bpbno,a.bpbdate,if(s.goods_code='',concat('FG ',' ',a.id_item),
				s.goods_code) Kode_Brg,s.itemname,a.unit,if(d.status_kb like 'SUBKON%' OR a.jenis_dok='SUBKONTRAK',0,a.qty) Dari_Inhouse,
				if(d.status_kb like 'SUBKON%' OR a.jenis_dok='SUBKONTRAK',a.qty,0) Dari_CMT,d.supplier 
				from bpb a inner join 
				masterstyle s on a.id_item=s.id_item  left join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and left(a.bpbno,2)='FG' and bpbdate between '$tglf' and '$tglt' $cril ");
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>";
				if ($nm_company!="PT. Multi Sarana Plasindo")
				{	echo "<td><span class='style1'>$data[6]</span></td>"; }
				echo "
				<td><span class='style1'>$data[7]</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}
		}
		if ($rpt=='kite_e' or $rpt=='kite_e24' or $rpt=='kite_e25' or $rpt=='kite_elkl')
		{	$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			if ($rpt=="kite_e24")
			{	$jen_dok=" and jenis_dok='BC 2.4'";	}
			else if ($rpt=="kite_e25")
			{	$jen_dok=" and jenis_dok='BC 2.5'";	}
			else if ($rpt=="kite_elkl")
			{	$jen_dok=" and jenis_dok='JUAL LOKAL'";	}
			else
			{	$jen_dok=" and jenis_dok='BC 3.0'";	}
			# BARANG JADI
			$sql = mysql_query ("select if(a.jenis_dok='JUAL LOKAL',a.jenis_dok,a.bcno) bcno,
				a.bcdate,a.bppbno,a.bppbdate,d.supplier,if(d.country='',a.tujuan,d.country) country,if(s.goods_code='',concat('FG ',' ',a.id_item),s.goods_code) Kode_Brg,s.itemname,a.unit,
				a.qty,a.curr Curr,a.qty*a.price Nilai_Brg from bppb a inner join masterstyle s on a.id_item=s.id_item  left join mastersupplier d on 
				a.id_supplier=d.id_supplier where mid(a.bppbno,4,2)='FG' and bppbdate between '$tglf' 
				and '$tglt' $jen_dok ");
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>
				<td><span class='style1'>$data[6]</span></td>
				<td><span class='style1'>$data[7]</span></td>
				<td><span class='style1'>$data[8]</span></td>
				<td><span class='style1'>$data[9]</span></td>
				<td><span class='style1'>$data[10]</span></td>
				<td><span class='style1'>$data[11]</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}
			# BAHAN BAKU
			$sql = mysql_query ("select a.bcno,a.bcdate,a.bppbno,a.bppbdate,d.supplier,a.tujuan country,if(s.goods_code='',concat(s.mattype,' ',a.id_item),s.goods_code) Kode_Brg,s.itemdesc,a.unit,
				a.qty,a.curr Curr,a.qty*a.price Nilai_Brg from bppb a inner join masteritem s on a.id_item=s.id_item  left join mastersupplier d on 
				a.id_supplier=d.id_supplier where mid(a.bppbno,4,2)!='FG' and bppbdate between '$tglf' 
				and '$tglt' $jen_dok ");
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>
				<td><span class='style1'>$data[6]</span></td>
				<td><span class='style1'>$data[7]</span></td>
				<td><span class='style1'>$data[8]</span></td>
				<td><span class='style1'>$data[9]</span></td>
				<td><span class='style1'>$data[10]</span></td>
				<td><span class='style1'>$data[11]</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}
		}
		if ($rpt=='kite_h')
		{	$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			$sql = mysql_query ("select a.bcno,a.bcdate,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) Kode_Brg,
				s.itemdesc,a.unit,round(a.qty,2) DiCMTKan,round((a.qty*a.price),2) AmtDiCMTKan,a.sent_to from bppb a inner join masteritem s on a.id_item=s.id_item  left join mastersupplier d on a.id_supplier=d.id_supplier where 
				(s.mattype in ('S','L') or jenis_dok='BC 2.4') and bppbno not like 'SJ-FG%' and bppbdate between '$tglf' and '$tglt'");
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
			echo "
			<tr>
				<td align='center'><span class='style1'>$no</span></td>
				<td><span class='style1'>$data[0]</span></td>
				<td><span class='style1'>$data[1]</span></td>
				<td><span class='style1'>$data[2]</span></td>
				<td><span class='style1'>$data[3]</span></td>
				<td><span class='style1'>$data[4]</span></td>
				<td><span class='style1'>$data[5]</span></td>
				<td><span class='style1'>$data[6]</span></td>
			</tr>
			"; #muculkan semua data mahasiswa dalam bentuk tabel
			$no++; #$no bertambah 1
			}
		}
		if ($rpt=='gb_perdokumen')
		{	$SQL="delete from gb_posisiperdok where username='$user' and sesi='$sesi'";
			insert_log($SQL,$user);
			$sql="select distinct tbl_id_item.id_item_nya
				from 
				(select distinct id_item id_item_nya from bpb where
				bpbdate between '$tglf' and '$tglt'
				union all 
				select distinct id_item id_item_nya from bppb where
				bppbdate between '$tglf' and '$tglt')
				as tbl_id_item order by tbl_id_item.id_item_nya";
			$sqlid = mysql_query ($sql);
			$typenya = "Bahan Baku";
			while($rs = mysql_fetch_array($sqlid))
			{	if ($typenya!="FG")
				{ $tblmst="masteritem"; 
				  $cribpb="bpbno not like 'FG%'";
				  $cribppb="bppbno not like 'SJ-FG%'";
				  $fldmst="s.matclass,s.itemdesc";
				}
				else
				{ $tblmst="masterstyle"; 
				  $cribpb="bpbno like 'FG%'";
				  $cribppb="bppbno like 'SJ-FG%'";
				  $fldmst="s.goods_code matclass,s.itemname itemdesc";
				}
				$id_item = "G".$rs['id_item_nya'];
				gen_kartu_stock($user,$sesi,$id_item,$cribpb,$cribppb);
			}
			$sqlgb = "select * from gb_posisiperdok where username='$user' and sesi='$sesi' order by kditem,urut";
			#$sqlgb = "select s.jenis_dok JDokMsk,s.bcno,s.bcdate,s.bpbdate,d.goods_code KdItem,
			#	d.itemdesc,s.unit UnitBTB,s.qty QtyBTB,round(s.qty*s.price,2) AmtBTB,
			#	a.jenis_dok JDokKel,lpad(a.bcno,6,'0') bcno_kel,a.bcdate bcdate_kel,a.bppbdate,
			#	sum(a.qty) QtyBKB,round(sum(a.qty*a.price),2) AmtBKB from  bpb s left join bppb a on a.id_item=s.id_item 
			#	inner join masteritem d on s.id_item=d.id_item where 
			#	bppbdate between '$tglf' and '$tglt'  
			#	and (a.qty>0 or s.qty>0) group by s.bcno,s.id_item order by s.id_item,s.bcdate";
			$sql = mysql_query ($sqlgb);
			$sqlk = ""; $sqlk2 = ""; $sqlk3 = "";
			$no = 1; #nomor awal
			while($data = mysql_fetch_array($sql))
			{ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
				$jenis_dok_msk = $data['JDokMsk'];
				$no_dok = $data['bcno'];
				$tgl_dok = $data['bcdate'];
				$tgl_btb = $data['bpbdate'];
				$kode_brg = $data['KdItem'];
				$no_seri = substr($kode_brg,12,3);
				$nama_barang = $data['itemdesc'];
				$unit_btb = $data['UnitBTB'];
				$qty_btb = $data['QtyBTB'];
				$amt_btb = $data['AmtBTB'];
				
				$no_dok_kel = $data['bcno_kel'];
				$tgl_dok_kel = $data['bcdate_kel'];
				$tgl_bkb = $data['bppbdate'];
				$qty_bkb = $data['QtyBKB'];
				$amt_bkb = $data['AmtBKB'];
				if ($qty_bkb>0)
				{	$nama_barang_out=$nama_barang; 
					$unit_bkb=$unit_btb;
					$jenis_dok_kel = $data['JDokKel'];
				}
				else
				{	$nama_barang_out=""; 
					$unit_bkb="";
					$jenis_dok_kel = "";
				}
				$sal_qty = round($data['sisa'],2);
				$sal_amt = round($data['sisa_amt'],2);
				#$sal_qty = round($qty_btb - $qty_bkb,2);
				#$sal_amt = round($amt_btb - $amt_bkb,2);
				echo "
				<tr>
					<td align='center' rowspana='3'><span class='style1'>$no</span></td>
					<td rowspana='3'><span class='style1'>$jenis_dok_msk</span></td>
					
					<td><span class='style1'>$no_dok</span></td>
					<td><span class='style1'>$tgl_dok</span></td>
					<td><span class='style1'>$tgl_btb</span></td>
					<td><span class='style1'>$kode_brg</span></td>
					<td><span class='style1'>$no_seri</span></td>
					<td><span class='style1'>$nama_barang</span></td>
					<td><span class='style1'>$unit_btb</span></td>
					<td align='right'><span class='style1'>$qty_btb</span></td>
					<td align='right'><span class='style1'>$amt_btb</span></td>
					<td><span class='style1'>$jenis_dok_kel</span></td>
					<td><span class='style1'>$no_dok_kel</span></td>
					<td><span class='style1'>$tgl_dok_kel</span></td>
					<td><span class='style1'>$tgl_bkb</span></td>
					<td><span class='style1'>$nama_barang_out</span></td>
					<td><span class='style1'>$unit_bkb</span></td>
					<td align='right'><span class='style1'>$qty_bkb</span></td>
					<td align='right'><span class='style1'>$amt_bkb</span></td>
					<td><span class='style1'>$sal_qty</span></td>
					<td align='right'><span class='style1'>$unit_btb</span></td>
					<td align='right'><span class='style1'>$sal_amt</span></td>
				</tr>
				";
				$no++; #$no bertambah 1
			}
		}
		if ($rpt=='bc23pjt')
		{	$kode_brg = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,
				concat(s.mattype,' ',s.id_item))";
			$sqlk = "SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,d.supplier,
				$kode_brg kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,a.curr,
				round(sum(a.price*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc
				from bpb a 
				inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and d.area='I' 
				and a.invno like '%PJT%' group by bcno,bpbno,a.id_item,price 
				order by bcdate,bcno,bpbno";
			insert_temp_perdok_bc23($sqlk,$user,$sesi,"Y");
			$kode_brg = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,
				concat('FG ',s.id_item))";
			$sqlk = "SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,d.supplier,
				$kode_brg kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,
				round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc 
				from bpb a 
				inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG'  and d.area='I' and a.invno like '%PJT%' 
				group by bcno,bpbno,a.id_item,price
				order by bcdate,bcno,bpbno";
			insert_temp_perdok_bc23($sqlk,$user,$sesi,"N");
		} elseif ($rpt=='bc23') 
		{	if ($nm_company=="PT. Jinwoo Engineering Indonesia") 
			{	$trans_no="a.invno"; } 
			else 
			{	$trans_no="if(a.bpbno_int!='',a.bpbno_int,a.bpbno)"; }
			$kode_brg = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,
				concat(s.mattype,' ',s.id_item))";
			$sqlk = "SELECT 'BC 2.3 IMPOR' jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				a.bcdate,$trans_no trans_no,a.bpbdate trans_date,d.supplier,$kode_brg kode_brg,s.itemdesc,
				a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and 
				jenis_dok='BC 2.3' and a.invno not like '%PJT%' and a.invno not like '%PIB%' 
				and a.invno not like '%PIBK%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno,bpbno";
			insert_temp_perdok_bc23($sqlk,$user,$sesi,"Y");
			// echo $sqlk;
			$kode_brg = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,
				concat('FG ',s.id_item))";
			$sqlk = "SELECT 'BC 2.3 IMPOR' jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				a.bcdate,$trans_no trans_no,a.bpbdate trans_date,d.supplier,$kode_brg kode_brg,s.itemname itemdesc,
				a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG'  and 
				jenis_dok='BC 2.3' and a.invno not like '%PJT%' and a.invno not like '%PIB%' 
				and a.invno not like '%PIBK%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno,bpbno";
			insert_temp_perdok_bc23($sqlk,$user,$sesi,"N");
		} elseif ($rpt=='bc262msk')
		{	if ($nm_company=='PT. Geum Cheon Indo')
			{ 	$sqlk = "SELECT 'BC 2.6.2 MASUK' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
					if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
					s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
					from bpb a inner join 
					masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
					where a.cancel='N' and bpbdate between '$tglf' and '$tglt'   
					and jenis_dok='BC 2.6.2' and left(a.bpbno,1) in ('C','A') order by bcdate,bcno";
				insert_temp_perdok($sqlk,$user,$sesi,"Y"); 
			} else
			{ 	$kode_brg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) ";
				$sqlk = "SELECT 'BC 2.6.2 MASUK' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
					$kode_brg kode_brg,s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
					from bpb a inner join 
					masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
					where bcno!='-' and  a.cancel='N' and bpbdate between '$tglf' and '$tglt' and bpbno not like 'FG%'  
					and a.jenis_dok='BC 2.6.2' order by bcdate,bcno";
				insert_temp_perdok($sqlk,$user,$sesi,"Y");
			}
			$sqlk2 = "SELECT 'BC 2.6.2 MASUK' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,
				d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) kode_brg,
				'FG' mattype,s.itemname itemdesc,a.unit,a.qty,a.curr,
				round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masterstyle s on a.id_item=s.id_item 
				inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bcno!='-' and a.cancel='N' and bpbdate between '$tglf' and '$tglt' 
				and a.jenis_dok='BC 2.6.2' and left(a.bpbno,2)='FG' order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
		} elseif ($rpt=='bc27msk' or $rpt=="bc27msksub")
		{	if ($nm_company=="PT. Jinwoo Engineering Indonesia") 
			{	$nm_brg="concat(s.matclass,' (',s.itemdesc,')')"; }
			else
			{	$nm_brg="s.itemdesc"; }
			$kode_brg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) ";
			if ($rpt=='bc27msk') 
			{ $sql_tuj=" and tujuan not regexp 'SUBKON' "; } 
			else 
			{ $sql_tuj=" and tujuan regexp 'SUBKON' "; }
			$sqlk = "SELECT 'BC 2.7' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,
				d.supplier,$kode_brg kode_brg,$nm_brg itemdesc,a.unit,sum(a.qty) qty,a.curr,
				round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)!='FG' and
				a.jenis_dok='BC 2.7' $sql_tuj group by bcno,bpbno,a.id_item,price order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$sqlk3 = "SELECT 'BC 2.7' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,
				d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) kode_brg,
				itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item  
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG' 
				and a.jenis_dok='BC 2.7' $sql_tuj group by bcno,bpbno,a.id_item,price order by bcdate,bcno";
			insert_temp_perdok($sqlk3,$user,$sesi,"N");
		} elseif ($rpt=='bc40lkl')
		{	$kode_brg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) "; 
			$kode_brg_fg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) ";
			$sqlk = "(SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				$kode_brg kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item , remark 
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and a.jenis_dok='BC 4.0' 
				and ucase(invno) not like '%SEWA%' 
				and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno)
				UNION
				SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				$kode_brg_fg kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item , remark  
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG' and d.area='L' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' 
				and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			// echo $sqlk;
			// echo $sesi;
			// $sqlk2 = "SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
			// 	$kode_brg_fg kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item  
			// 	from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
			// 	where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG' and d.area='L' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' 
			// 	and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno";
			// insert_temp_perdok($sqlk2,$user,$sesi,"Y");
		} elseif ($rpt=='bc40sewa')
		{	$sqlk = "SELECT 'BC 4.0 MESIN (SEWA)' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,
				a.bpbno trans_no,a.bpbdate trans_date,d.supplier,
				if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and d.status_kb='NON KB' and d.area='L' and ucase(invno) like '%SEWA%' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
		} elseif ($rpt=='bc40subkon')
		{	$kode_brg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) ";
			$sqlk = "SELECT 'BC 4.0 SUBKON' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				$kode_brg kode_brg,s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and a.jenis_dok='BC 4.0' 
				and ucase(invno) not like '%SEWA%' and a.tujuan like '%SUBKON%' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$kode_brg = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) "; 
			$sqlk2 = "SELECT 'BC 4.0 SUBKON' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				$kode_brg kode_brg,s.itemname itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG'  and a.jenis_dok='BC 4.0' 
				and ucase(invno) not like '%SEWA%' and a.tujuan like '%SUBKON%' order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
		} elseif ($rpt=='bc20pibbyr')
		{	$sqlk = "SELECT 'BC 2.0 IMPOR PIB' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and d.area='I' and a.invno like '%PIB%' and a.invno not like '%PIBK%' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
		} elseif ($rpt=='bc21pibk')
		{	$sqlk = "SELECT 'BC 2.1 IMPOR PIBK' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and d.area='I' and a.invno like '%PIBK%' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
		} elseif ($rpt=='bc24kitte')
		{	$sqlk = "SELECT 'BC 2.4 KITTE' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,
				a.bpbdate trans_date,d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,a.id_item  
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG'  and d.status_kb='KITTE' and d.area='L'";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
		} elseif ($rpt=='bc30')
		{	if ($nm_company=="PT. Bangun Sarana Alloy")
			{ $vkode = "s.styleno";} 
			else
			{ $vkode = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item))";} 
			$sqlk = "SELECT 'BC 3.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,
				a.bppbdate trans_date,d.supplier,$vkode kode_brg,s.itemname itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,
				a.price ,'FG' mattype,s.id_so_det id_item 
				from bppb a inner join masterstyle s on a.id_item=s.id_item LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)='FG' and 
				jenis_dok='BC 3.0' order by bcdate,bcno,bppbno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$sqlk2 = "SELECT 'BC 3.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,
				d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,s.itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,
				a.curr,a.price ,s.mattype,s.id_item from bppb a inner join masteritem s on a.id_item=s.id_item 
				LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)<>'FG' and jenis_dok='BC 3.0' order by bcdate,bcno,bppbno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
			// echo $sqlk2;
		} elseif ($rpt=='bc261keluar')
		{	$kodenya = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item))";
			$sqlk = "SELECT 'BC 2.6.1 KELUAR' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kodenya kode_brg,s.itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,
				s.id_item from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bcno!='-' and bppbdate between '$tglf' and '$tglt'   and jenis_dok='BC 2.6.1' and mid(a.bppbno,4,2) not in ('FG') 
				order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$kodenya = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item))";
			$sqlk2 = "SELECT 'BC 2.6.1 KELUAR' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kodenya kode_brg,s.itemname itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,s.id_so_det id_item ,a.curr,a.price ,'FG' mattype,
				s.id_item from bppb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bcno!='-' and bppbdate between '$tglf' and '$tglt'   and jenis_dok='BC 2.6.1' and mid(a.bppbno,4,2) in ('FG') and 
				mid(a.bppbno,4,1) not in ('P') order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
		} elseif ($rpt=='bc27keluar' or $rpt=='bc27subkon')
		{	$kdnya = "if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0'
				,s.goods_code,concat('FG ',s.id_item))";
			if ($nm_company=="PT. Youngil Leather Indonesia")
			{	if ($rpt=='bc27keluar') 
				{ $sql_tuj=" and tujuan not in ('DISUBKONTRAKKAN','LAINNYA') "; } 
				else 
				{ $sql_tuj=" and tujuan in ('DISUBKONTRAKKAN','LAINNYA') "; }
			}
			else
			{	if ($rpt=='bc27keluar') 
				{ $sql_tuj=" and tujuan not in ('DIKEMBALIKAN','DISUBKONTRAKKAN') "; } 
				else 
				{ $sql_tuj=" and tujuan in ('DIKEMBALIKAN','DISUBKONTRAKKAN') "; }
			}
			$sqlk = "SELECT 'BC 2.7' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,
				$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kdnya kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,
				round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_so_det id_item ,a.curr,a.price ,
				'FG' mattype,s.id_item from bppb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on 
				a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' and a.bppbno like 'SJ-FG%' 
				and jenis_dok='BC 2.7' $sql_tuj 
				group by bcno,bppbno,s.goods_code,s.itemname,price order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$sqlk2 = "SELECT 'BC 2.7' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kdnya kode_brg,
				s.itemdesc,a.unit,sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,s.id_item from bppb a inner join masteritem s on a.id_item=s.id_item 
				inner join mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' 
				and a.bppbno not like 'SJ-FG%' and jenis_dok='BC 2.7' $sql_tuj 
				group by bcno,bppbno,a.id_item,price order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
		} elseif ($rpt=='bc41lkl')
		{	$kodenya = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item))";
			$sqlk = "SELECT * from (SELECT 'BC 4.1 LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kodenya kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,
				round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,a.id_item ,a.curr,a.price ,
				s.mattype from bppb a inner join masteritem s on a.id_item=s.id_item inner join 
				mastersupplier d on a.id_supplier=d.id_supplier 
				where 
				bppbdate between '$tglf' and '$tglt' and mid(a.bppbno,4,2)<>'FG'  and jenis_dok='BC 4.1' 
				and ucase(remark) not like '%SEWA%' and a.tujuan not like '%SUBKON%' 
				or bppbdate between '$tglf' and '$tglt' and mid(a.bppbno,4,2)<>'FG' and jenis_dok='BC 4.1' and ucase(remark) is null and a.tujuan is null
				group by a.bcno,a.bppbno,s.goods_code,s.itemdesc,a.price
				order by bcdate,bcno) a
				UNION
				(SELECT 'BC 4.1 LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,a.bppbdate trans_date,d.supplier,
				if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,s.itemdesc itemdesc,a.unit,
				sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_item id_item ,a.curr,a.price ,
				'F' mattype from bppb a inner join masteritem s on a.id_item=s.id_item inner join 
				mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' and left(bppbno_int,2)='GK' and jenis_dok='BC 4.1'
				group by a.bcno,a.bppbno,s.goods_code,s.id_item,a.price
				order by bcdate,bcno)
				UNION
				SELECT 'BC 4.1 LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,a.bppbdate trans_date,d.supplier,
				if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,s.itemdesc itemdesc,a.unit,
				sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_item id_item ,a.curr,a.price ,
				s.mattype from bppb a inner join masteritem s on a.id_item=s.id_item inner join 
				mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' and left(bppbno_int,3)='GEN' and jenis_dok='BC 4.1'
				group by a.bcno,a.bppbno,s.goods_code,s.id_item,a.price
				order by bcdate,bcno";
				// echo $sqlk;
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$sqlk2 = "SELECT 'BC 4.1 LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,s.itemname itemdesc,a.unit,
				sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_so_det id_item ,a.curr,a.price ,
				'FG' mattype,s.id_item from bppb a inner join masterstyle s on a.id_item=s.id_item inner join 
				mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' and 
				mid(a.bppbno,4,2)='FG'  and jenis_dok='BC 4.1' and ucase(remark) not like '%SEWA%'  
				and a.tujuan not like '%SUBKON%' 
				group by a.bcno,a.bppbno,s.goods_code,s.itemname,a.price
				order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
			// $sqlk3 = "SELECT 'BC 4.1 LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,a.bppbdate trans_date,d.supplier,
			// 	if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat('FG ',s.id_item)) kode_brg,s.itemdesc itemdesc,a.unit,
			// 	sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_item id_item ,a.curr,a.price ,
			// 	'F' mattype,s.id_item from bppb a inner join masteritem s on a.id_item=s.id_item inner join 
			// 	mastersupplier d on a.id_supplier=d.id_supplier where bppbdate between '$tglf' and '$tglt' and left(bppbno_int,2)='GK' and jenis_dok='BC 4.1'
			// 	group by a.bcno,a.bppbno,s.goods_code,s.id_item,a.price
			// 	order by bcdate,bcno";
			// insert_temp_perdok($sqlk3,$user,$sesi,"Y");
		} elseif ($rpt=='bc41sewa')
		{	$sqlk = "SELECT 'BC 4.1 LOKAL MESIN (SEWA)' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,
				a.bppbdate trans_date,d.supplier,if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,
				s.itemdesc,a.unit,a.qty,
				round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,s.id_item 
				from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(a.bppbno,4,2)<>'FG'  and jenis_dok='BC 4.1' and ucase(remark) like '%SEWA%' and mid(bppbno,4,1)<>'S' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			
		} elseif ($rpt=='bc41subkon')
		{	$sqlk = "SELECT 'BC 4.1 SUBKON' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,s.itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,
				s.id_item from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(a.bppbno,4,2)<>'FG'  and jenis_dok='BC 4.1'  
				and mid(a.bppbno,4,2)<>'FG'  and 
				ucase(remark) not like '%SEWA%'  and a.tujuan like '%SUBKON%' order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
			$sqlk2 = "SELECT 'BC 4.1 SUBKON' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) kode_brg,itemname itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,s.id_so_det id_item ,a.curr,a.price ,'FG' mattype,
				s.id_item from bppb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and jenis_dok='BC 4.1' and mid(a.bppbno,4,2)='FG'  and  
				ucase(remark) not like '%SEWA%' and a.tujuan like '%SUBKON%' order by bcdate,bcno";
			insert_temp_perdok($sqlk2,$user,$sesi,"N");
		} elseif ($rpt=='bc25scrap')
		{	$kodenya = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item))";
			$sqlk = "SELECT 'BC 2.5 SCRAP' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				$kodenya kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,a.id_item ,a.curr,a.price ,s.mattype,s.id_item 
				from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(a.bppbno,4,2)<>'FG'  
				and jenis_dok='BC 2.5' and s.mattype in ('S') 
				group by bcno,bppbno,s.goods_code,s.itemdesc,price order by bcdate,bcno";
			insert_temp_perdok($sqlk,$user,$sesi,"Y");
		} elseif ($rpt=='bc25lkl')
		{	if ($nm_company=="PT. Bangun Sarana Alloy")
			{ $vkode = "s.styleno";} 
			else 
			{ $vkode = "if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item))";}
			if ($st_company=="GB")
			{	$sqlk = "SELECT 'BC 2.5 KELUAR' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
					$vkode kode_brg,s.itemdesc,a.unit,a.qty,round(a.qty*ifnull(a.price_bc,a.price),2) nilai_barang,a.id_item ,a.curr,a.price,mattype,a.id_item 
					from bppb a inner join masteritem s on a.id_item=s.id_item LEFT join mastersupplier d on a.id_supplier=d.id_supplier where 
					bppbdate between '$tglf' and '$tglt' and bppbno not like 'SJ-FG%' 
					and jenis_dok='BC 2.5' order by bcdate,bcno,bppbno";
				insert_temp_perdok($sqlk,$user,$sesi,"Y");
			}
			else
			{	$sqlk = "SELECT 'BC 2.5 JUAL LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
					$vkode kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,s.id_so_det id_item ,a.curr,a.price,'FG' mattype,a.id_item 
					from bppb a inner join masterstyle s on a.id_item=s.id_item LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
					where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)='FG' and 
					jenis_dok='BC 2.5' 
					group by bcno,bppbno,s.goods_code,s.itemname,price order by bcdate,bcno,bppbno";
				insert_temp_perdok($sqlk,$user,$sesi,"Y");
				$sqlk = "SELECT 'BC 2.5 JUAL LOKAL' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
					if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat(s.mattype,' ',s.id_item)) kode_brg,s.itemdesc,
					a.unit,sum(a.qty) qty,round(sum(a.qty*ifnull(a.price_bc,a.price)),2) nilai_barang,a.id_item ,a.curr,a.price,s.mattype,a.id_item 
					from bppb a inner join masteritem s on a.id_item=s.id_item LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
					where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)<>'FG' and mid(bppbno,4,1)<>'S' and 
					jenis_dok='BC 2.5' 
					group by bcno,bppbno,s.goods_code,s.itemdesc,price order by bcdate,bcno,bppbno";
				insert_temp_perdok($sqlk,$user,$sesi,"N");
			}
		} elseif ($rpt=='inrekap')
		{	$sqlk = "SELECT 
				if(jenis_dok='BC 2.3' and a.invno like '%PJT%','BC 2.3 IMPOR PJT',
				if(jenis_dok='BC 2.3' and a.invno not like '%PJT%' and a.invno not like '%PIB%' and a.invno not like '%PIBK%','BC 2.3 IMPOR',
				if(jenis_dok='BC 2.6.2','BC 2.6.2 MASUK',
				if(jenis_dok='BC 2.7','BC 2.7 MASUK',
				if(jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and ucase(tujuan) not like '%SUBKON%','BC 4.0',
				if(jenis_dok='BC 4.0' and ucase(invno) like '%SEWA%','BC 4.0 (SEWA)',
				if(jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and ucase(tujuan) like '%SUBKON%','BC 4.0 SUBKON',
				if(d.area='I' and a.invno like '%PIB%' and a.invno not like '%PIBK%','BC 2.0 IMPOR PIB',
				if(d.area='I' and a.invno like '%PIBK%','BC 2.1 IMPOR PIBK',
				if(d.status_kb='KITTE' and d.area='L','BC 2.4 KITTE',jenis_dok)))))))))) jenis_dokumen,
				lpad(a.bcno,6,'0') bcno,if(isnull(a.bcdate),a.bpbdate,a.bcdate) bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) kode_brg,
				concat(s.itemdesc,' ',s.color,' ',s.size,' ',s.add_info) itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,
				berat_bersih,berat_kotor,right(nomor_aju,6) nomor_aju,tujuan 
				from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt'  and left(bpbno,2)<>'FG' and jenis_dok!='INHOUSE' order by bcdate,bcno,bpbno ";
			insert_temp_perdok_rekap($sqlk,$user,$sesi,"Y");
			$sqlk = "SELECT 
				if(jenis_dok='BC 2.3' and a.invno like '%PJT%','BC 2.3 IMPOR PJT',
				if(jenis_dok='BC 2.3' and a.invno not like '%PJT%' and a.invno not like '%PIB%' and a.invno not like '%PIBK%','BC 2.3 IMPOR',
				if(jenis_dok='BC 2.6.2','BC 2.6.2 MASUK',
				if(jenis_dok='BC 2.7','BC 2.7 MASUK',
				if(jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and ucase(tujuan) not like '%SUBKON%','BC 4.0',
				if(jenis_dok='BC 4.0' and ucase(invno) like '%SEWA%','BC 4.0 (SEWA)',
				if(jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and ucase(tujuan) like '%SUBKON%','BC 4.0 SUBKON',
				if(d.area='I' and a.invno like '%PIB%' and a.invno not like '%PIBK%','BC 2.0 IMPOR PIB',
				if(d.area='I' and a.invno like '%PIBK%','BC 2.1 IMPOR PIBK',
				if(d.status_kb='KITTE' and d.area='L','BC 2.4 KITTE','N/A')))))))))) jenis_dokumen,
				lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat('FG ',a.id_item),s.goods_code) kode_brg,
				s.itemname itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,
				berat_bersih,berat_kotor,right(nomor_aju,6) nomor_aju 
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt'  and left(bpbno,2)='FG' and jenis_dok!='INHOUSE' order by bcdate,bcno,bpbno ";
			insert_temp_perdok_rekap($sqlk,$user,$sesi,"N");
		} elseif ($rpt=='outrekap')
		{	$sqlk = "SELECT 
				if(jenis_dok='BC 3.0','BC 3.0',
				if(jenis_dok='BC 2.6.1','BC 2.6.1 KELUAR',
				if(jenis_dok='BC 2.7','BC 2.7',
				if(jenis_dok='BC 4.1' and ucase(remark) not like '%SEWA%' and ucase(tujuan) not like '%SUBKON%','BC 4.1',
				if(jenis_dok='BC 4.1' and ucase(remark) like '%SEWA%' and mid(bppbno,4,1)='M','BC 4.1 LOKAL MESIN (SEWA)',
				if(jenis_dok='BC 4.1' and ucase(remark) not like '%SEWA%' and ucase(tujuan) like '%SUBKON%','BC 4.1 SUBKON',
				if(jenis_dok='BC 2.5' and mid(bppbno,4,1) in ('S'),'BC 2.5 SCRAP',
				if(jenis_dok='BC 2.5' and mid(bppbno,4,1) not in ('S'),'BC 2.5',jenis_dok)))))))) jenis_dokumen,
				lpad(a.bcno,6,'0') bcno,a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat(s.mattype,' ',a.id_item),s.goods_code) kode_brg,
				s.itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,ifnull(a.price_bc,a.price),
				berat_bersih,berat_kotor,right(nomor_aju,6) nomor_aju,tujuan
				from bppb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)<>'FG' and jenis_dok!='INHOUSE' order by bcdate,bcno,bppbno";
				// and d.area<>'F'
				// echo $sqlk;
			insert_temp_perdok_rekap($sqlk,$user,$sesi,"Y");
			$sqlk = "SELECT 
				if(jenis_dok='BC 3.0','BC 3.0',
				if(jenis_dok='BC 2.6.1','BC 2.6.1 KELUAR',
				if(jenis_dok='BC 2.7','BC 2.7',
				if(jenis_dok='BC 4.1' and ucase(remark) not like '%SEWA%' and ucase(tujuan) not like '%SUBKON%','BC 4.1',
				if(jenis_dok='BC 4.1' and ucase(remark) like '%SEWA%' and mid(bppbno,4,1)<>'S','BC 4.1 LOKAL MESIN (SEWA)',
				if(jenis_dok='BC 4.1' and ucase(remark) not like '%SEWA%' and ucase(tujuan) like '%SUBKON%','BC 4.1 SUBKON',
				if(jenis_dok='BC 2.5' and mid(bppbno,4,1) in ('S'),'BC 2.5 SCRAP',
				if(jenis_dok='BC 2.5' and mid(bppbno,4,1) not in ('S'),'BC 2.5','N/A')))))))) jenis_dokumen,lpad(a.bcno,6,'0') bcno,
				a.bcdate,$vtrans_no trans_no,a.bppbdate trans_date,d.supplier,
				if(s.goods_code='' OR s.goods_code='-' OR s.goods_code='0',concat('FG ',a.id_item),s.goods_code) kode_brg,
				s.itemname itemdesc,a.unit,a.qty,a.curr,round(ifnull(a.price_bc,a.price)*a.qty,2) nilai_barang,ifnull(a.price_bc,a.price),
				berat_bersih,berat_kotor,right(nomor_aju,6) nomor_aju,tujuan 
				from bppb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where bppbdate between '$tglf' and '$tglt' and mid(bppbno,4,2)='FG' and jenis_dok!='INHOUSE' order by bcdate,bcno,bppbno";
			#and d.area<>'F'
				// echo $sqlk;
			insert_temp_perdok_rekap($sqlk,$user,$sesi,"N");
		}
		#CETAK SQLK / REPORT / PREVIEW
		#echo $sqlk;
	  $no = 1; #nomor awal
	  $no_dok_next=""; $jenis_dok_next="";
	  $no_dok_prev=""; $jenis_dok_prev="";
if ($rpt=='bc40lkl')
{
		$sql = mysql_query("(SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier, if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, b.matclass, remark from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier LEFT JOIN (select id_item iditem, if(matclass = '-' OR matclass is null, b.description, matclass) matclass from masteritem a left join mapping_category b on b.n_id = a.n_code_category GROUP BY id_item) b on b.iditem = a.id_item where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno)
			UNION
			SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
				if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item ,'BARANG JADI' matclass , remark  
				from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
				where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG' and d.area='L' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' 
				and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno");

		// $sql = mysql_query("SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier, if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, b.matclass, remark from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier LEFT JOIN (select id_item iditem, if(matclass = '-' OR matclass is null, b.description, matclass) matclass from masteritem a left join mapping_category b on b.n_id = a.n_code_category GROUP BY id_item) b on b.iditem = a.id_item where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno");

		// echo "(SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier, if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat(s.mattype,s.id_item)) kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item, b.matclass, remark from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier LEFT JOIN (select id_item iditem, if(matclass = '-' OR matclass is null, b.description, matclass) matclass from masteritem a left join mapping_category b on b.n_id = a.n_code_category GROUP BY id_item) b on b.iditem = a.id_item where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)<>'FG' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno)
		// 	UNION
		// 	SELECT 'BC 4.0' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no,a.bpbdate trans_date,d.supplier,
		// 		if(goods_code<>'' AND goods_code<>'-' AND goods_code<>'0',goods_code,concat('FG ',s.id_item)) kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,a.curr,round(sum(ifnull(a.price_bc,a.price)*a.qty),2) nilai_barang,a.id_item , 'BARANG JADI' matclass, remark  
		// 		from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier 
		// 		where a.cancel='N' and bpbdate between '$tglf' and '$tglt' and left(bpbno,2)='FG' and d.area='L' and a.jenis_dok='BC 4.0' and ucase(invno) not like '%SEWA%' 
		// 		and a.tujuan not like '%SUBKON%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno";


}
else
{  
		$sql = mysql_query("select a.*, if(JENIS_BARANG like '%FG/%','BARANG JADI',matclass) matclass from upload_standard a LEFT JOIN (select id_item iditem, if(matclass = '-' OR matclass is null, b.description, matclass) matclass from masteritem a left join mapping_category b on b.n_id = a.n_code_category GROUP BY id_item) b on b.iditem = a.id_item where username='$user' and sesi='$sesi' order by jenis_dokumen,bcdate,bcno");
		// echo "select a.*, if(JENIS_BARANG like '%FG/%','BARANG JADI',matclass) matclass from upload_standard a LEFT JOIN (select id_item iditem, if(matclass = '-' OR matclass is null, b.description, matclass) matclass from masteritem a left join mapping_category b on b.n_id = a.n_code_category GROUP BY id_item) b on b.iditem = a.id_item where username='$user' and sesi='$sesi'  and bcno = '729273' order by jenis_dokumen,bcdate,bcno";
}	
$no = 1;
					$no_urt = 1;
					$no_urt_fix = 1;
					$cri_prev = '';
		while($data = mysql_fetch_array($sql))		
		{	

if ($rpt=='bc40lkl')
{

$jenis_dok = $data['jenis_dokumen'];
			$no_dok = $data['bcno'];
			if ($no>1)
			{	$cri_next=$data['bcno']; }

			$tgl_dok = fd_view($data['bcdate']);
			$no_trans = $data['trans_no'];
			$matclass = isset($data['matclass']) ? $data['matclass'] : '-';
			$tgl_trans = MiddleDate($data['trans_date']);
			$nm_sup = $data['supplier'];
			$kode_barang = $data['id_item']; #dapatkan id mahasiswa dari data array (row) 'id'
			$nm_barang = $data['itemdesc']; #dapatkan nama mahasiswa dari data array (row) 'nama'
			$satuan = $data['unit']; #dapatkan jurusan mahasiswa dari data array (row) 'jurusan' 
			$jml = number_format($data['qty'],2);
			$curr = $data['curr'];
			$remark = $data['remark'];
			$nilai = number_format($data['nilai_barang'],2);	
}
else
{	
			$jenis_dok = $data['JENIS_DOKUMEN'];
			$no_dok = $data['BCNO'];
			if ($no>1)
			{	$cri_next=$data['JENIS_BARANG']; }

			$tgl_dok = fd_view($data['BCDATE']);
			$matclass = isset($data['matclass']) ? $data['matclass'] : '-';
			$no_trans = $data['JENIS_BARANG'];
			$tgl_trans = MiddleDate($data['PODATE']);
			$nm_sup = $data['SUPPLIER'];
			$kode_barang = $data['ID_ITEM']; #dapatkan id mahasiswa dari data array (row) 'id'
			$nm_barang = $data['ITEMDESC']; #dapatkan nama mahasiswa dari data array (row) 'nama'
			$satuan = $data['UNIT']; #dapatkan jurusan mahasiswa dari data array (row) 'jurusan' 
			$jml = number_format($data['QTY'],2);
			$curr = $data['CURR'];
			$remark = $data['remark'];
			$nilai = number_format($data['PRICE'],2);
			$satuan_bc = $data['satuan_bc']; 
			$qty_bc = number_format($data['qty_bc'],2);
}			
			if ($rpt=="inrekap" or $rpt=="outrekap") 
			{ $berat_bersih=$data['BERAT_BERSIH']; 
				if ($nm_company=="PT. Global Hanstama Jaya")
				{$berat_kotor=$data['BERAT_BERSIH'];}
				else
				{$berat_kotor=$data['BERAT_KOTOR'];}
				$nomor_aju=$data['nomor_aju'];
				$tujuan=$data['tujuan'];
			}

			// if ($rpt=="bc23" or $rpt=="bc23pjt")
			// 		{	
			// 			$satuan_bc = $data['satuan_bc']; 
			// 			$qty_bc = number_format($data['qty_bc'],2)
			// 		}


			// $cri_next = $data['trans_no'];

			echo "
			<tr>";
				if ($cri_next != $cri_prev) {
							$no_urt++;
							$no_urt_fix++;
						}
						
						if ($no_urt != '' && $no_urt_fix != '') {
							echo "<td> $no_urt</td>";
						} else {
							echo "<td></td>";
						}

				echo "
					<td>$jenis_dok</td>
					<td>$matclass</td>";
					if ($rpt=="inrekap" or $rpt=="outrekap")
					{ echo "<td align='left'>$nomor_aju</td>"; }
					echo "
					<td>$no_dok</td>
					<td>$tgl_dok</td>
					";
				
				echo "
				<td>$no_trans</td>
				<td>$tgl_trans</td>
				<td>$nm_sup</td>
				<td>$kode_barang</td>
				<td>$nm_barang</td>
				<td>$satuan</td>
				<td align='right'>$jml</td>";
				if ($rpt=="bc23" or $rpt=="bc23pjt")
					{	echo "<td>$satuan_bc</td>
							<td align='right'>$qty_bc</td>";
					}
				echo "<td align='left'>$curr</td>
				<td align='right'>$nilai</td>
				";
				if ($rpt=='bc40lkl') {echo "<td align='right'>$remark</td>";}
				//<td align='right'>$remark</td>
				if ($rpt=="inrekap" or $rpt=="outrekap") 
				{ echo "<td align='right'>$berat_bersih</td>
								<td align='right'>$berat_kotor</td>
								<td align='left'>$tujuan</td>";
				}
			echo "</tr>";
			$no++;
						$cri_prev = $cri_next;
						if ($cri_prev == $cri_next) {
							$no_urt_fix = '';
						}
		}
		?>
	</table>
	</div>
</div>