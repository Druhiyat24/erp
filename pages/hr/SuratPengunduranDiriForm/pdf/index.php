
	<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$id = $_GET['id'];
//define('_MPDF_PATH','mpdf/'); // Tentukan folder dimana anda menyimpan folder mpdf
include __DIR__ ."/../../mpdf57/mpdf.php"; // Arahkan ke file mpdf.php didalam folder mpdf
$mpdf=new mPDF('utf-8', 'A4', 10.5, 'arial'); // Membuat file mpdf baru
//Memulai proses untuk menyimpan variabel php dan html
ob_start();
include __DIR__ .'/../../../../include/conn.php';
/*-----*/
		$q = " SELECT A.n_id
						,A.v_nik
						,A.d_insert
						,DATE(A.d_create) d_create
						,DATE(A.d_header) d_header
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						FROM hr_pengundurandiri A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,nama FROM hr_masteremployee) B
					ON A.v_nik = B.nik
					WHERE A.n_id = $id";
				//echo "$q";
		$stmt = mysql_query($q);
		$row=mysql_fetch_array($stmt);
		$bulan = ['0','Januari','February','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','desember'];
		$createDate = explode('-', $row['d_create']);
		$headerDate = explode('-', $row['d_header']);
		$mulaiKerja = explode('-', $row['mulai_kerja']);

/////////
?>
<body>
<head>
    <link rel="stylesheet" href="../../../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="../../../../plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="../../../../plugins/select2/select2.min.css">
    <link rel="stylesheet" href="../../../../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../../../../plugins/datatables_responsive/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../../../../plugins/datatables_responsive/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../../../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../../../../fontawesome/css/font-awesome.css">
<link rel="stylesheet" href="../../css/style.css">
 <style>
 	table{margin: auto;}
 	td,th{padding: 5px;text-align: center; width: auto}
 	h1{text-align: center}
 	th{background-color: #95a5a6; padding: 10px;color: #fff}
	
	
 </style>
 </head>

<body>
<div class="header">

</div>

<div class="containerContent" align="justify">
	<p align="right">Solokan,Jeruk <u><?php print_r($headerDate[2].'-'.$bulan[intval($headerDate[1])].'-'.$headerDate[0]  ) ?> </u>  </p>


Kepada Yth, 
 <br/><br/>
Bapak/Ibu Personalia 
 <br/><br/>
Di 
 <br/><br/>
Tempat,  
 <br/><br/>
 Dengan Hormat,
 <br/>
 <br/>
 Yang bertanda tangan di bawah ini,  
 
<br/><br/>
 <table width="100%">
 <tr>
<td style="position:absolute;margin-top:300px;width:15px;"><div style="position:absolute;margin-top:30px">&nbsp;</div></td>
<td><div class="pihak12">
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left">Nama </td> 
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['nama']) ?> </td> 
		</tr>
		<tr>
			<td align="left">Jabatan </td> 
			<td align="left">:</td>
			<td align="left"> 
			<?php print_r($row['jabatan']) ?> </td> 
		</tr>
		<tr>
			<td align="left">Tanggal Masuk </td> 
			<td align="left">: 
			</td><td align="left"><?php print_r($mulaiKerja[2].' '.$bulan[intval($mulaiKerja[1])].$mulaiKerja[0])  ?> </td> 
		</tr>
		<tr>
			<td align="left">Alamat </td> 
			<td align="left">: </td>
			<td align="left" ><?php print_r($row['alamat_karyawan']) ?>  </td> 
		</tr>		
	</table>
 </div>
 

 </tr>
</table>

<br/>
<br/>
Dengan ini saya mengundurkan diri dari PT. Nirwana Alabare Garment sejak tanggal
<u> <?php print_r($createDate[2].' '.$bulan[intval($createDate[1])].' '.$createDate[0]) ?> </u>  atas keinginan sendiri tanpa ada paksaan dari pihak manapun. 
<br/><br/>
Dengan dibuat surat pengunduran diri ini maka terhadap pihak perusahaan telah selesai dan tidak akan ada tuntutan apapun di kemudian hari. 
<br/><br/>
Demikian surat pengunduran diri saya buat. 
 <br/><br/>
Atas perhatian Bapak/Ibu pimpinan PT. Nirwana Alabare Garment saya ucapkan terimakasih. 

 <br/><br/> <br/><br/> <br/><br/>
  
 <table width="100%">
	<tr>
		<td align="Center">  
			Hormat Saya 
			<br/><br/> <br/><br/> <br/><br/> <br/><br/>
 
 
 
			(__________________)<br/>  
				Karyawan
		</td>
		<td align="Center">  
			Hormat Saya 
			<br/><br/> <br/><br/> <br/><br/> <br/><br/>
 
 
 
			(__________________)<br/>  
				Atasan  

		</td>
				<td align="Center">  
			Hormat Saya 
			<br/><br/> <br/><br/> <br/><br/> <br/><br/>
 
 
 
			(__________________)<br/>  
				HRGA Manager   

		</td>

	
	
</tr>
</table> 


</div>


 
<?php
 
$mpdf->setFooter('{PAGENO}');
//penulisan output selesai, sekarang menutup mpdf dan generate kedalam format pdf
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Disini dimulai proses convert UTF-8, kalau ingin ISO-8859-1 cukup dengan mengganti $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;

	?>
	</body>
	</html>