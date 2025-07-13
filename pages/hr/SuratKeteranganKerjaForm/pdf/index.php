
	<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$id = $_GET['id'];
//define('_MPDF_PATH','mpdf/'); // Tentukan folder dimana anda menyimpan folder mpdf
include __DIR__ ."/../../mpdf57/mpdf.php"; // Arahkan ke file mpdf.php didalam folder mpdf
$mpdf=new mPDF('utf-8', 'A4', 10.5, 'arial'); // Membuat file mpdf baru
 echo "123";
//Memulai proses untuk menyimpan variabel php dan html

ob_start();
include __DIR__ .'/../../../../include/conn.php';

/*----*/
		$q = "SELECT A.n_id
						,A.v_nik
						,A.v_nik2
						,A.d_insert
						,A.v_lamakerja
						,department
						,DATE(A.d_create) d_create
						,A.v_reason
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						,C.namaPPT
						,C.jabatanPT
						FROM hr_keterangankerja A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,nama,department FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
					LEFT JOIN (SELECT nik
						,'PT. Nirwana Alabare Garment' perusahaan,
							'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamatPT
						,'PT. Nirwana Alabare Garment' namaPT
						,department departmentPT
						,nama namaPPT
						,bagian bagianPT
						,jabatan jabatanPT
							 FROM hr_masteremployee) C
					ON A.v_nik = C.nik				
					WHERE A.n_id = '$id'";
				//echo "$q";
		$stmt = mysql_query($q);
		$row=mysql_fetch_array($stmt);
		$bulan = ['0','Januari','February','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','desember'];
		$createDate = explode('-',$row['d_create']);
		$namaBulan = $bulan[intval($createDate[1])];
		$finalDate = $createDate[2].' '.$namaBulan.' '.$createDate[0];
		


//
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
 	td,th{padding: 5px;text-align: center; width: 150px}
 	h1{text-align: center}
 	th{background-color: #95a5a6; padding: 10px;color: #fff}
	
	
 </style>
 </head>

<body>
<div class="header">
<table width="100%">
	<tr>
	<td >
		<img src="../../css/img/logo.png" width="20%">
	</td>
	<td class="title" >
			PT.NIRWANA ALABARE GARMENT
			<div style="font-size:13px;line-height:9">
Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382 Telp. 022-85962081				
			</div>
	</td>	
	</tr>
</table>

<div class="horizontal">

</div>
</div>

<div class="containerContent" align="justify">
	<p class="contentTitle">SURAT KETERANGAN KERJA   </p>


<p style="text-align:center;font-weight:bold">Yang bertanda tangan di bawah ini menerangkan dengan sebenarnya bahwa :  </p>
 <br/>
 <table width="100%">
 <tr>
<td style="position:absolute;margin-top:300px;width:15px;"><div style="position:absolute;margin-top:30px">&nbsp;</div></td>
<td><div class="pihak12">
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">Nama<br/><p style="text-decoration:none;border-bottom: none;">Name</p> </td> 
			<td align="left">: <?php print_r($row['nama']) ?> </td> 
		</tr>
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">No.Induk<br/><p style="text-decoration:none;border-bottom: none;">Reg. No. </p> </td> 
			<td align="left">: <?php print_r($row['v_nik2']) ?> </td>  
		</tr>
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">Bagian   <br/><p style="text-decoration:none;border-bottom: none;">Departement  </p> </td> 
			<td align="left">: <?php print_r($row['department']) ?> </td>  
		</tr>		
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">Jabatan Terakhir<br/><p style="text-decoration:none;border-bottom: none;">Final Classification</p> </td> 
			<td align="left">: <?php print_r($row['jabatan']) ?> </td>  
		</tr>
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">Lamanya Bekerja<br/><p style="text-decoration:none;border-bottom: none;">Period of Service</p> </td> 
			<td align="left" width="500px">: <?php print_r($row['v_lamakerja']) ?> </td>  
		</tr>	
		<tr>
			<td class="tablecontent" align="left"><p style="line-height:3; padding-bottom: 40px;border-bottom: 1px solid black;">Sebab Berhenti Bekerja<br/><p style="text-decoration:none;border-bottom: none;">Reason for Termination</p> </td> 
			<td align="left" width="500px">: <?php print_r($row['v_reason']) ?> </td>  
		</tr>	
			
		
	</table>
 </div>
 </td>

 </tr>
</table>




 

 Selama bekerja pada perusahaan kami, yang bersangkutan menunjukan prestasi dan disiplin kerja yang baik, oleh karenanya kami mengucapkan terima kasih atas jasa-jasanya yang telah diberikan kepada perusahaan. <br/>
 <br/>


 <br/><br/> 
Demikian surat keterangan ini diberikan untuk dipergunakan sebagaimana mestinya. 
  <br/><br/> 
  
 <table width="100%">
	<tr>
		<td align="left">Majalaya, <?php print_r($finalDate) ?> 
			<br/><br/> 
			PT. Nirwana Alabare Garment 
			<br/><br/> <br/><br/> <br/><br/> <br/><br/> 
 
 
 
			<p style="padding-bottom: 40px;border-bottom: 1px solid black"><?php print_r($row['namaPPT']) ?> </p> 
			
			<?php print_r($row['jabatanPT']) ?> 

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
//$mpdf->WriteHTML(utf8_encode($html));
$mpdf->WriteHTML($html);
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;

	?>
	</body>
	</html>