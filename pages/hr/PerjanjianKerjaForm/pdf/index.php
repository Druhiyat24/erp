
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
		$q = " SELECT A.n_id
						,A.v_nomorsurat
						,A.v_nik
						,A.v_nik2
						,A.d_create
						,A.v_periodekontrak
						,department
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						,C.namaPT
						,C.jabatanPT
						,C.namaPPT
						FROM hr_perjanjiankerja A
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
		$stmt = mysql_query($q);
		$row=mysql_fetch_array($stmt);					
				//echo "$q";
?>
<html><head><meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style>
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
</style>
<link href="style.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="../../css/style.css"> 



<title>Kontrak Kerja</title></head>
<body>

<div class="calibre" id="calibre_link-0">



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

	<p class="block_">KONTRAK KERJA</p>
	<p class="block_1"><span lang="id">033</span>/<span lang="id">HRD</span>-<span lang="id">NAG</span>/<span lang="id">PKWT</span>/<span lang="id">XII</span>/2017</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_" start="1">
	<li class="block_3"><b class="calibre1"><span class="calibre2">IDENTITAS  KARYAWAN</span></b>
	
	
	</li>


</ol>

	<div class="calibre3">
		<div class="submenu">
			<div class="subsubmenu numbering">
				<b>1.1)</b> Nama
				
			</div>
			<div class="subsubmenu right">
				:
			</div>	
			<div class="extra-right">
			<?php print($row['nama']) ?>	
			</div>	
		</div>
		

		<div class="submenu">
			<div class="subsubmenu numbering">
				<b>1.2)</b> Alamat Karyawan (sesuai Ktp)
				
			</div>
			<div class="subsubmenu right">
				:
			</div>	
			<div class="extra-right">
			<?php print($row['alamat_karyawan']) ?>	
			</div>	
		</div>		
		

</div>

	<ol class="list_" start="2">
	<li value="2" class="block_3"><b class="calibre1">PERIODE MULAI BEKERJA</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
		<div class="submenu">
			<div class="subsubmenu numbering">
				<b>2.1)</b>  Periode kontrak 
				
			</div>
			<div class="subsubmenu right">
				:
			</div>	
			<div class="extra-right">
			<?php print($row['v_periodekontrak'])  ?>
			</div>	
		</div>
		

		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>2.2)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Karyawan tidak memiliki ikatan kerja dengan Perusahaan lain ketika menandatangani
				perjanjian ini.			
			</div>	
		</div>		
			
	</div>
	
	

	


	<ol class="list_" start="3">
<li value="3" class="block_3"><b class="calibre1">POSISI/JABATAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
		<div class="submenu">
			<div class="subsubmenu numbering">
				<b>3.1)</b>  Posisi karyawan 
				
			</div>
			<div class="subsubmenu right">
				:
			</div>	
			<div class="extra-right">
			<?php print($row['jabatan']) ?>
			</div>	
		</div>	
	
			<div class="submenu">
			<div class="subsubmenu numbering">
				<b>3.2)</b>  Melapor kepada 
				
			</div>
			<div class="subsubmenu right">
				:
			</div>	
			<div class="extra-right">
			<?php print($row['namaPPT']) ?>
			</div>	
		</div>	
		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>3.3)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Karyawan dapat dibutuhkan untuk melakukan tugas-tugas lain di luar tanggung jawab
utama yang masih dalam kemampuan Karyawan, baik secara sementara atau
permanen sesuai dengan kebutuhan dari Perusahaan.
		
			</div>	
		</div>				
	

</div>
	<p class="block_6">&nbsp;</p>
	<ol class="list_" start="4">
	<li value="4" class="block_3"><b class="calibre1">JAM KERJA DAN LOKASI KERJA</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">4.1)&nbsp;</span><span class="calibre4">Jam kerja <span lang="id">K</span>aryawan adalah sebagai berikut :</span></div>
</div>
	<p class="block_9">&nbsp;&nbsp;Senin <span lang="id">s/d</span> Jumat <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>0<span lang="id">8.</span>0<span lang="id">0</span> &ndash; <span lang="id">1</span>7<span lang="id">.</span>0<span lang="id">0</span></p>
	<p class="block_9">&nbsp;&nbsp;Sabtu<span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span lang="id">0</span>8<span lang="id">.</span>0<span lang="id">0</span> &ndash; 12.30</p>
	<div class="calibre3">


		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>4.2)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Lokasi kerja Karyawan adalah Majalaya. Perusahaan dapat dan berhak mengubah
				serta memindahkan bagian, tempat dan lokasi kerja dari Karyawan, baik di luar kota maupun
				antar perusahaan di Nirwana Group.<br/><br/>
		
			</div>	
		</div>	


	<ol class="list_" start="5">
	<li value="5" class="block_3"><b class="calibre1">STATUS K</b><b lang="id" class="calibre1">E</b><b class="calibre1">PEGAWAIAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	
	
	
		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>5.1)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Kontrak Kerja Karyawan akan diperpanjang jika kinerja karyawan dinilai Baik.
		
			</div>	
		</div>	
		
		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>5.2)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Setelah Karyawan melewati masa kontrak 3 (tiga) tahun dan menunjukan evaluasi
				kinerja yang Sangat Baik, maka Perusahaan dapat mengangkat Karyawan tersebut
				menjadi Karyawan Tetap. Dan apabila evaluasi kinerja Karyawan dianggap masih
				kurang namun masih dapat dipekerjakan, maka Perusahaan berhak menambah durasi
				kontrak kerja sampai masa yang tidak ditentukan.

			</div>	
		</div>			

	
	<p class="block_11">&nbsp;</p>
	<p class="block_11">&nbsp;</p>
	</div>
	<ol class="list_" start="6">
	<li value="6" class="block_8"><b class="calibre1">CUTI TAHUNAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>6.1) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				 Karyawan berhak mendapatkan cuti tahunan sebanyak 12 (dua belas) hari setelah
				bekerja selama 1 (satu) tahun dan akan digunakan dalam libur Hari Raya Idul Fitri.
				Hal ini akan diatur oleh perusahaan setiap tahunnya.	
			</div>	
		</div>	
		<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>6.2) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Karyawan akan mendapat potongan upah untuk setiap ijin pribadi di luar cuti tahunan
				dan cuti-cuti resmi yang telah diatur dalam Peraturan Perusahaan.
			</div>	
		</div>		
</div>		

	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_" start="7">
	<li value="7" class="block_8"><b lang="id" class="calibre1">IJIN</b><b class="calibre1"> SAKIT</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>7.1)</b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Karyawan dapat mengajukan ijin sakit dengan menunjukan Surat Sakit dari dokter
				dan disetujui oleh Perusahaan.
			</div>	
		</div>	
	
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>7.2) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
Apabila Karyawan tidak dapat menunjukkan surat dokter, maka Karyawan dianggap
absen dan akan
			</div>	
		</div>	
</div>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_" start="8">
	<li value="8" class="block_8"><b class="calibre1">TUNJANGAN KESEHATAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>8.1) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				Karyawan akan mendapatkan Tunjangan Kesehatan sesuai dengan apa yang telah
				diatur oleh Perusahaan setelah Karyawan melewati masa On the job training.
			</div>	
		</div>	 	

</div>

	<p class="block_13">&nbsp;</p>
	<p class="block_13">&nbsp;</p>
	<ol class="list_" start="9">
	<li value="9" class="block_8"><b class="calibre1">PEMUTUSAN KONTRAK</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>9.1) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
				 Apabila Karyawan hendak mengakhiri kontrak sebelum masa kontraknya berakhir,
				maka Karyawan diharuskan mengajukan surat pengunduran diri paling lambat 1 (satu)
				bulan sebelum tanggal pengunduran diri.
			</div>	
		</div>	 

			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				<b>9.2) </b>
			</div>	
			<div class="extra-right extraRightDuaDua">
 Perusahaan dapat memutuskan kontrak ini sesegera mungkin tanpa kompensasi atau
pembayaran sama sekali apabila Karyawan :
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				-
			</div>	
			<div class="extra-right extraRightDuaDua">
				Gagal untuk menunjukkan pengetahuan, ilmu, dan kemampuan atas pekerjaannya
				sesuai dengan yang diharapkan, atau tidak dapat melaksanakan pekerjaannya.
			</div>	
		</div>	 
		

			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				-
			</div>	
			<div class="extra-right extraRightDuaDua">
				Secara sadar dan sengaja tidak mematuhi perintah/petunjuk/arahan yang sah dan
				wajar yang diberikan oleh Perusahaan
			</div>	
		</div>		
	
			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				-
			</div>	
			<div class="extra-right extraRightDuaDua">
				Bersalah atas kelalaian tertentu, melakukan pelanggaran, atau segala tindakan
				tidak jujur dalam melaksanakan tugasnya.
			</div>	
		</div>	
	

			<div class="submenu">
			<div class="subsubmenu numbering duaDua">
				&nbsp;
			</div>
			<div class="subsubmenu right rightDuaDua">
				-
			</div>	
			<div class="extra-right extraRightDuaDua">
				Menjadi terdakwa atau didakwa dengan pidana tertentu atau diragukan
				kemampuan di masa depan untuk melaksanakan tugas-tugasnya.

			</div>	
		</div>	

			</div>	
		</div>			

</div>

	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_" start="10">
	<li value="10" class="block_8"><b class="calibre1">KERAHASIAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<p class="block_10">Karyawan mengetahui dan menyetujui bahwa seluruh materi informasi yang bersifat<b class="calibre1"> </b>non-publik, termasuk namun tidak terbatas pada informasi yang berhubungan dengan pendapat<span lang="id">an</span>, volume bisnis, metode bisnis, s<span lang="id">i</span>stem, rencana-rencana, akun-akun, ketentuan dalam Perjanjian ini, dan hal lain yang bersifat rahasia atau informasi hak milik yang bernilai komersil yang dimiliki oleh Perusahaan <b class="calibre1">(“Informasi Rahasia”)</b> akan tetap dirahasiakan dan tidak akan diungkapkan atau diberikan kepada pihak ketiga manapun tanpa persetujuan tertulis dari Perusahaan.</p>
	<p class="block_14">&nbsp;</p>
	<p class="block_10">Ketentuan dalam pasal ini akan terus berlaku meskipun setelah putus dan/atau berakhirnya Perjanjian ini, tanpa batasan waktu.</p>
	<p class="block_14">&nbsp;</p>
	<p class="block_14">&nbsp;</p>
	<ol class="list_" start="11">
	<li value="11" class="block_8"><b class="calibre1">HAK KEKAYAAN INTELEKTUAL</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<p class="block_10">Karyawan dengan ini mengakui dan menyetujui bahwa seluruh merk dagang, nama dagang<span lang="id">,</span> logo, hak cipta dan hak milik lainnya, termasuk namun tidak terbatas pada penciptaan, paten, rahasia dagang, penemuan, teknik, proses, alat, penyempurnaan,<b class="calibre1"> </b><i class="calibre6">know-how,</i> perbaikan, s<span lang="id">i</span>stem, kurikulum, perubahan yang terkandung, gambar, tulisan, susunan desain, model, hasil karya seni, hasil pekerjaan pengarang dan benda berwuju<span lang="id">d </span>dan benda tidak berwujud lainnya <b class="calibre1">(“Hak Kekayaan Intelektual”) </b>yang dibuat dalam hubungannya dalam Perjanjian ini baik terdaftar maupun tidak, kan tetap dan merupakan hak milik eksklusif dari Perusahaan (atau pemilik yang sesuai) adalah pemilik dari seluruh hak, title, dan kepentingan atas Hak Kekayaan Intelektual baik yang berada dalam wilayahnya atau di tempat lain di seluruh dunia. Karyawan menjamin dan setuju untuk tidak mengambil tindakan apapun yang mungkin merugikan atau mempengaruhi validas dari Hak Kekayaan Intelektual atau kepemilikan Perusahaan (atau pemilik yang sesuai) atau lisensi daripadanya dan akan berhenti menggunakan Hak Kekayaan Intelektual setelah putusnya Perjanjian ini. </p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_" start="12">
	<li value="12" class="block_8"><b class="calibre1">HUKUM YANG MENGUAT</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<p class="block_15">Perjanjian ini diatur dan ditafsirkan berdasarkan hukum Republik Indonesia. Masing-masing Pihak setuju bahwa segala sengketa yang muncul sehubungan dengan Perjanjian ini akan diselesaikan secara musyawarah. Jika penyelesaian secara <span lang="id">m</span>usyawarah tidak dapat dicapai oleh Para Pihak, maka Para Pihak setuju bahwa segala tindakan atau proses yang muncul atau yang berhubungan dengan Perjanjian ini akan diserahkan dan menjadi kewenangan yurisdiksi Pengadilan Indonesia.</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_16">Ditandatangani oleh HRD</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_17">_________________________</p>
	<p class="block_17">Nama <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>:
	
	<?php print($row['namaPPT']) ?>
	
	<span lang="id"> </span> </p>
	<p class="block_17">Posisi <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>: 
	
		<?php print($row['jabatanPT']) ?>
	
	<span lang="id"> </span></p>
	<p class="block_17">Tanggal <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>: 
	
	
	<?php print($row['d_create']) ?>
	
	<span lang="id"> </span></p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_16">Ditandatangani oleh Karyawan </p>
	<p class="block_18">Saya dengan ini menyatakan menerima ketentuan yang dinyatakan dalam perjanjian kerja ini dengan sebenar-benarnya dan tanpa paksaan dari pihak manapun. </p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_6">&nbsp;</p>
	<p class="block_17">_________________________</p>
	<p class="block_17">Nama<span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>:
	
	<?php print($row['nama']) ?>
	
	<span lang="id">  </span></p>
	<p class="block_19">Tanggal<span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span lang="id"><span class="calibre2">:  
	
	<?php print($row['d_create']) ?>
	
	</span></span></p>



</body></html>


 
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