
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

echo "123";
/*----*/
		$q = "SELECT 
						 A.id
						,A.n_id
						,A.v_nik1
						,A.v_nik2
						,date(A.d_insert) d_insert
						,A.n_kontrak
						,A.v_gaji
						,DATE(A.d_create) d_create
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.selesai_kerja
						,B.nama
						,B.department
						,B.bagian
						,B.tempat_lahir
						,B.tgl_lahir
						,C.namaPPT
						,C.alamatPT
						,C.departmentPT
						,C.bagianPT
						,C.perusahaan
						,C.jabatanPT
						FROM hr_kontrakkerja A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,selesai_kerja,nama,department,bagian,tempat_lahir,tgl_lahir FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
					LEFT JOIN (SELECT nik,nama namaPPT
						,'PT. Nirwana Alabare Garment' perusahaan,
							'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamatPT
						,'PT. Nirwana Alabare Garment' namaPT
						,department departmentPT
						,bagian bagianPT
						,jabatan jabatanPT
							 FROM hr_masteremployee) C
					ON A.v_nik1 = C.nik					
					WHERE A.id = $id";
				//echo "$q";
		$stmt = mysql_query($q);
		$row=mysql_fetch_array($stmt);
		$createDate = explode('-', $row['d_create']);
		$mulaiDate = explode('-', $row['mulai_kerja']);
		$selesaiDate = explode('-', $row['selesai_kerja']);
		$datenow = explode('-', $row['d_insert']);
		
		
	
		$bulan = ['0',
					'Januari',
					'February',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember',
					
					];
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
 	td,th{padding: 5px;text-align: center; width: auto}
 	h1{text-align: center}
 	th{background-color: #95a5a6; padding: 10px;color: #fff}
	
	
	
.submenu{
	width:100%;
	height:auto;
	/*background-color:#0000ff; */
background-color:transparent;
	
	
}
.subsubmenu{
	width:40%;
	height:2px;
	diplay:inline-block;
	background-color:#ff0000;
	background-color:transparent;
	
}

.right{
	width:5%;
	height:2px;
	float:right;
	margin-top:-19px;
	margin-right:55%;
	diplay:inline-block;
	background-color:#ab0000;
	background-color:transparent;
}
.extra-right{
	
	width:auto;
	position:abolute;
	height:2px;
	background-color:#00f0f;
	margin-top:-18px;
	margin-left:42%;
	background-color:transparent;
	text-align:justify;
	
}
.duaDua{
	width:10%;;
		
	
}
.rightDuaDua{
	margin-right:95%;
	
	
}

.extraRightDuaDua{
	
	margin-left:12%;
	margin-left:5%;
	width:100%;
	
}	
	ul {
list-style: none;
	}

table > tr > td > ul{
	list-style: none;
	
}	

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
	<p class="contentTitle">SURAT KONTRAK KERJA  </p>
	<p class="contentTitle2"><?php print_r($row['n_id'])  ?> </p>


Yang bertanda tangan di bawah ini,  
 <br/>
 
 

 
 
 </div>

 <table width="100%">
 <tr>
<td style="position:absolute;margin-top:300px;width:10px;"><div style="position:absolute;margin-top:-30px">&nbsp;</div></td>
<td>
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left" width="1.4in"><ol start="0">
				<li>Nama </li>
			
			</ol>  </td> 
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['namaPPT']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Jabatan </td> 
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['jabatanPT']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Perusahaan </td> 
			<td align="left">:</td>
			<td align="left">  <?php print_r($row['perusahaan']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Alamat </td> 
			<td align="left">:</td>
			<td align="left">  <?php print_r($row['alamatPT']) ?>   </td> 
		</tr>		
	</table>
 </td>

 </tr>
</table>



 <table width="100%">
 <tr>
<td style="position:absolute;margin-top:300px;width:10px;"><div style="position:absolute;margin-top:30px">&nbsp;</div></td>
<td>
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left" width="1.4in"><ol start="1">
				<li>Nama </li>
			
			</ol> </td> 
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['nama']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Departemen </td>
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['department']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Bagian </td> 
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['bagian']) ?> </td> 
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Tempat, tgl lahir </td>
			<td align="left">:</td>
			<td align="left"> <?php print_r($row['tempat_lahir']) ?>,  <?php print_r($row['tgl_lahir']) ?>  </td> 
		</tr>	
		<tr>
			<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Alamat </td> 
			<td align="left">:</td>
			<td align="left" style="width:500px"> <?php print_r($row['alamat_karyawan']) ?>    </td> 
		</tr>		
	</table>

 </td>
 </td>

 </tr>
</table>



 
Dalam perjanjian ini bertindak untuk dan atas nama diri sendiri, dan selanjutnya dalam perjanjian ini disebut Pihak Pekerja atau PIHAK KEDUA.   <br/><br/>
   Pada hari ini tanggal  
   <u> <?php print_r($createDate[2])  ?></u>  
   <u> <?php print_r($bulan[intval($createDate[1])]);  ?></u> tahun 
   <u> <?php print_r($createDate[0])  ?></u> bertempat di PT. Nirwana Alabare Garment Kedua belah pihak sepakat untuk mengadakan Perjanjian Kerja Waktu Tertentu sebagaimana diatur dalam UU No.13 Tahun 2003 dengan ketentuan-ketentuan sebagai berikut: <br/>
 <br/>

 
<ul >  <li type="decimal"> Pihak  Pertama   menerima   Pihak  Kedua   sebagai  Karyawan  dengan   masa   kontrak  dimulai  sejak   
tanggal 
	<u> <?php print_r($mulaiDate[2])  ?></u> 
	<u> <?php print_r($bulan[intval($mulaiDate[1])]);  ?></u> tahun 
	<u> <?php print_r($mulaiDate[0])  ?></u>  sampai dengan tanggal 
	<u> <?php print_r($selesaiDate[2])  ?></u> 
	<u> <?php print_r($bulan[intval($selesaiDate[1])]); ?></u>  tahun 
	<u> <?php print_r($selesaiDate[0])  ?></u> . 
 <br/> <br/>
	</li>
	
 <li type="decimal" > Perjanjian Kerja untuk waktu/pekerjaan tertentu ini diadakan hanya karena tersedianya pekerjaan yang menurut sifat atau jenis, atau yang kegiatannya akan selesai dalam waktu tertentu, antara lain yang berhubungan dengan produk/kegiatan produktivitas Perusahaan dalam waktu tertentu.  
</li>
 <br/> <br/>
<li type="decimal" > Pihak Pertama mempekerjakan Pihak Kedua sebagai 
<u> <?php print_r($row['jabatan'])  ?></u> di bagian  <u><?php print_r($row['bagian'])  ?></u>                                           . Dan Pihak Kedua besedia melaksanakan tugas dan tanggung jawabnya yang diberikan Pimpinan/atasannya dengan sebaikbaiknya. 
</li>
<br/> <br/>
<li type="decimal" > Perusahaan dapat dan berhak mengubah serta memindahkan bagian, 
tempat, dan lokasi kerja karyawan baik diluar kota maupun antar perusahaan di Nirwana Group. 
</li>
 <br/> <br/>
 <li type="decimal" >
Pihak Kedua wajib hadir setiap hari sesuai dengan jadwal kerja yang telah ditentukan dimana Pihak Kedua ditempatkan, serta menyatakan bersedia bekerja dengan jadwal kerja yang diatur sebagai berikut: <br/>
	&nbsp;&nbsp;&nbsp;a. Senin s/d Kamis :   07.30 s/d 15.30 WIB Istirahat  :   12.00 s/d 13.00 WIB <br/>
	&nbsp;&nbsp;&nbsp;b. Jumat   :   07.30 s/d 15.30 WIB Istirahat  :   11.30 s/d 12.30 WIB<br/>
	&nbsp;&nbsp;&nbsp;c. Sabtu  :   07.30 s/d 12.30 WIB <br/>
Jadwal kerja tersebut di atas sewaktu-waktu dapat berubah sesuai dengan kebutuhan. Pihak Kedua wajib melakukan absensi saat masuk dan pulang kerja. 
</li>
<li type="decimal" >
<br/><br/>
  </li><li type="decimal" >
Pihak Pertama memberikan Upah/Gaji pokok kepada Pihak Kedua sebesar Rp.<?php print_r(number_format($row['v_gaji'],0,",",".")) ?>,-  (dua juta enam ratus tujuh puluh delapan ribu dua puluh sembilan rupiah)/bulan, dan pembayaran Gaji/Upah diberikan berdasarkan absensi kehadiran Karyawan dan dibayarkan setiap tanggal 1 (satu) setiap bulannya. 
 <br/><br/> 
  </li> <li type="decimal" >
Waktu dan Jam kerja di Perusahaan 6 (enam) hari kerja dalam seminggu, dengan ketentuan 7 (tujuh) jam sehari dan/atau 40 (empat puluh) jam seminggu serta hari Minggu merupakan hari libur/istirahat mingguan. 
 <br/><br/> 
   </li><li type="decimal" >
Pihak Kedua wajib mematuhi Peraturan Perusahaan yang berlaku dan menjaga ketertiban, kedisiplinan, produktivitas kerja, kerjasama dan kebersihan di lingkungan kerjanya serta merawat peralatan kerja, mesin produksi, dan barang inventaris yang menjadi tanggung jawabnya. 
 <br/><br/> 
 
  </li><li type="decimal" >Pihak Kedua wajib memberitahukan melalui telepon, lisan atau tertulis jika berhalangan hadir dan memberikan alasan dengan jelas kepada pimpinan/atasannya, dan bilamana 5 (lima) hari tidak masuk kerja tanpa ada alasan yang sah dan tidak dapat dipertanggungjawabkan, serta sudah mendapatkan surat panggilan dari HRD maka dianggap mengundurkan diri dari Perusahaan atas permintaan sendiri. 
 <br/><br/> 
  </li> <li type="decimal" >
 Pihak Pertama berhak untuk mengakhiri hubungan kerja sebelum berakhirnya tanggal perjanjian, dan prosesnya mengikuti peraturan perundang-undangan ketenagakerjaan yang berlaku.  
 <br/><br/> 
   </li><li type="decimal" >
 Apabila Pihak Kedua akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan Pihak Pertama, maka Pihak Kedua wajib memberitahukan kepada Pihak Pertama selambat-lambatnya 2 (dua) minggu sebelumnya. 
 <br/><br/> 
  </li> <li type="decimal" >
Perjanjian kerja ini dapat diperpanjang apabila Pihak Pertama memerlukan Pihak Kedua, dan Pihak Pertama akan memberitahukan kapada Pihak Kedua selambat-lambatnya 7 (tujuh) hari sebelum berakhirnya masa berlakunya Perjanjian Kerja ini untuk diperpanjang, dan perpanjangan tersebut atas kepentingan kedua belah pihak. 
 <br/><br/>  </li> <li type="decimal" >
Perjanjian Kerja ini dapat berubah sesuai dengan situasi dan kondisi Perusahaan, atau persetujuan kedua belah pihak dan wakil-wakil yang sah. 
 <br/><br/>  </li> <li type="decimal" >
Hal-hal yang belum tercantum dalam perjanjian ini, mengenai syarat-syarat kerja, hak dan kewajiban dalam hubungan kerja diatur dalam Peraturan Perusahaan. 
Perjanjian Kerja ini mulai berlaku sejak ditandatangani oleh kedua belah pihak sampai dengan berakhirnya masa Perjanjian Kerja, atau karena meninggalnya Pihak Kedua. 
 <br/><br/> 
  </li>
 <li type="decimal" >Perjanjian Kerja ini dibuat dilembar kop surat perusahaan yang mempunyai kekuatan hukum sesuai dengan ketentuan perundang-undangan yang berlaku.
 <br/><br/> 
Demikian Perjanjian Kerja ini dibuat oleh kedua belah pihak dalam keadaan sehat jasmani dan rohani tanpa ada tekanan atau paksaan dari manapun. 
  <br/><br/> 
  </li>
  </ul>
 <table width="100%">
	<tr>
		<td align="left">Majalaya, 
			<br/><br/> 
			PIHAK KEDUA, Karyawan 
			<br/><br/> 	<br/><br/> <br/><br/> 	<br/><br/> <br/>
 
 
 
			__________________  
			<br/><br/> 
			Copy Perjanjian (PKWT) ini<br/>  
			Sudah diterima oleh karyawan.<br/>
			Pada tanggal : <?php echo $datenow[2] ?>/<?php echo $datenow[1] ?>/<?php echo $datenow[0] ?> 
		</td>
		<td  align="left">&nbsp;<br/><br/><br/><br/>PIHAK PERTAMA, PT. Nirwana Alabare Garment 
			<br/><br/> 	<br/><br/> <br/><br/> 	<br/><br/> <br/>
 
 
			________________________   
			<br/><br/> 
	Terdaftar di<br/>
	Suku Dinas Tenaga Kerja Dan Transmigrasi<br/>
	Administrasi Kabupaten Bandung.<br/>
	Tanggal :  <?php echo $datenow[2] ?>/<?php echo $datenow[1] ?>/<?php echo $datenow[0]?><br/>
	Nomor   :<?php echo $row['n_id']; 	 ?>

	
	
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