<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI


?>
<div class="box" >
  <div class="box-header">
	<h4>Kontrak Kerja Form </h4>
	<button class="btn btn-default" style="float:right" onclick="back()" >Back</button>
	<button class="btn btn-primary" style="float:right" onclick="save('P')">Save</button>
  </div>
  <!--<div class="box-body"  style="overflow:scroll;height:500px"> -->
<div class="box-body" >

	<div class="container">
<div>
<div class="row">
<div class="col-md-4" style="padding:0">
	<img src="./css/img/logo.png" class="img-responsive center-block" width="40%" style="float:right" >
</div>
<div class="col-md-6" style="padding:0;">
			<div style="text-align:center;font-size:4.5vh;font-weight:bold;">PT.NIRWANA ALABARE GARMENT
			<div style="font-size:18px;">
			Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382 Telp. 022-85962081				
			</div>
			</div>
</div>
</div>


<div class="horizontal">

</div>
</div>

<div class="containerContent" align="justify">
	<p class="contentTitle">SURAT KONTRAK KERJA  </p>
	<p class="contentTitle" style="font-weight:normal;text-decoration:none;font-size:15px">
	<span id="part_no">
		No. XXX/HRD-NAG/PKWT-0
	</span>
	<input type="text" id="kontrak_ke" style="width:20px">
	<span id="part_no_footer" >
		/I/XXXX
	</span>   </p>
		Yang bertanda tangan di bawah ini,  
 <br/>
 <table width="100%">
 <tr>
<td >1</div></td>
<td><div class="pihak12">
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left" style="width:192px" >Nama </td> 
			<td align="left">:


						<select class='form-control select2' 
							name='txtid_buyer' id="nama_skk"  onchange="getDetailNama(this,'1')" style="width:80%">
							<option  value='1' >--Pilih Nama--</option>

					</select>			</td> 
		</tr>
		<tr>
			<td align="left">Jabatan </td> 
			<td align="left">: <input type="text" name="jabatan_skk" id="jabatan_skk" style="width:80%" placeholder="Masukan Jabatan"> </td> 
		</tr>
		<tr>
			<td align="left">Perusahaan </td> 
			<td align="left">: <input type="text" name="perusahaan_skk" id="perusahaan_skk" placeholder="Masukan Perusahaan" style="width:80%" > </td> 
		</tr>
		<tr>
			<td align="left">Alamat </td> 
			<td align="left">: <input type="text" name="alamat_skk" id="alamat_skk" placeholder="Masukan Alamat" style="width:80%">  </td> 
		</tr>		
	</table>
 </div>
 </td>

 </tr>
</table>
<br/>
Dalam perjanjian ini bertindak 
untuk dan atas nama Perusahaan PT. Nirwana Alabare Garment 
dan selanjutnya dalam perjanjian ini disebut Pihak 
Pengusaha atau PIHAK PERTAMA. 
<br/><br/>

 <table width="100%">
 <tr>
<td >2</div></td>
<td><div class="pihak12">
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left" style="width:190px">Nama </td> 
			<td align="left">: 
						<select class='form-control select2' 
							name='txtid_buyer' id="nama2_skk"  onchange="getDetailNama(this,'2')" style="width:80%">
							<option  value='1' >--Pilih Nama--</option>
					</select>	
			</td> 
		</tr>
		<tr>
			<td align="left">Departemen </td> 
			<td align="left">: <input type="text" name="department_skk" id="department2_skk" placeholder="Masukan Department"  style="width:80%"> </td> 
		</tr>
		<tr>
			<td align="left">Bagian </td> 
			<td align="left">: <input type="text" name="bagian2_skk" id="bagian2_skk" placeholder="Masukan Bagian"  style="width:80%"> </td> 
		</tr>
		<tr>
			<td align="left">Tempat, tgl lahir </td> 
			<td align="left">: <input type="text" id="tempatlahir2_skk" placeholder="Masukan Temp"  style="width:39%"> &nbsp 
			<input type="text" name="tgllahir_skk" id="tgllahir2_skk" placeholder="Masukan Tanggal Lahir"  style="width:40%">
			</td> 
		</tr>	
		<tr>
			<td align="left">Alamat </td> 
			<td align="left" >: <input type="text" name="alamat2_skk" id="alamat2_skk" placeholder="Masukan Alamat"  style="width:80%">  </td> 
		</tr>		
	</table>
 </div>
 </td>

 </tr>
</table>


<br/>
 
Dalam perjanjian ini bertindak untuk dan atas nama diri sendiri, dan selanjutnya dalam perjanjian ini disebut Pihak Pekerja atau PIHAK KEDUA.   <br/><br/>
   Pada hari ini tanggal   
<select id="content_tglday1" style="width:auto">
				<option value ="-1" disabled>--Pilih Tanggal--</option>
		</select>
   bulan 

<select id="content_tglmonth1"  style="width:auto">
				<option value ="-1" disabled>--Pilih Month--</option>
		</select>
   tahun 
   
   <select id="content_tglyears1"  style="width:auto">
				<option value ="-1" disabled>--Pilih Tahun--</option>
		</select
   
   bertempat di PT. Nirwana Alabare Garment Kedua belah pihak sepakat untuk mengadakan Perjanjian Kerja Waktu Tertentu sebagaimana diatur dalam UU No.13 Tahun 2003 dengan ketentuan-ketentuan sebagai berikut: <br/>
<br/><br/>
 <ul>
 
1. Pihak  Pertama   menerima   Pihak  Kedua   sebagai  Karyawan  dengan   masa   kontrak  dimulai  sejak  
 tanggal <select id="content_tglday2" style="width:auto">
				<option value ="-1" disabled>--Pilih Tanggal--</option>
		</select> -
	bulan 

<select id="content_tglmonth2" style="width:auto">
				<option value ="-1" disabled>--Pilih Month--</option>
		</select>	
	tahun<select id="content_years2" style="width:auto">
				<option value ="-1" disabled>--Pilih Tahun--</option>
		</select>sampai dengan
	tanggal 
<select id="content_tglday3" style="width:auto">
				<option value ="-1" disabled>--Pilih Tanggal--</option>
		Bulan </select> -
<select id="content_tglmonth3" style="width:auto">
				<option value ="-1" disabled>--Pilih Month--</option>
		</select>
	tahun <select id="content_years3" style="width:auto">
				<option value ="-1" disabled>--Pilih Tahun--</option>
		</select>	. 
 <br/> <br/>
2. Perjanjian Kerja untuk waktu/pekerjaan tertentu ini diadakan hanya karena tersedianya pekerjaan yang menurut sifat atau jenis, atau yang kegiatannya akan selesai dalam waktu tertentu, antara lain yang berhubungan dengan produk/kegiatan produktivitas Perusahaan dalam waktu tertentu.  
 <br/> <br/>
3. Pihak Pertama mempekerjakan Pihak Kedua sebagai 
	<input type="text" id="content_jabatan" style="width:auto" >
	di bagian <input type="text" id="content_posisi" style="width:auto" >                                           . Dan Pihak Kedua besedia melaksanakan tugas dan tanggung jawabnya yang diberikan Pimpinan/atasannya dengan sebaikbaiknya. 
 <br/> <br/>
4. Perusahaan dapat dan berhak mengubah serta memindahkan bagian, tempat, dan lokasi kerja karyawan baik diluar kota maupun antar perusahaan di Nirwana Group. 
 <br/> <br/>
5. Pihak Kedua wajib hadir setiap hari sesuai dengan jadwal kerja yang telah ditentukan dimana Pihak Kedua ditempatkan, serta menyatakan bersedia bekerja dengan jadwal kerja yang diatur sebagai berikut: 
	a. Senin s/d Kamis :   07.30 s/d 15.30 WIB Istirahat  :   12.00 s/d 13.00 WIB <br/><br/>
	b. Jumat   :   07.30 s/d 15.30 WIB Istirahat  :   11.30 s/d 12.30 WIB<br/><br/> 
	c. Sabtu  :   07.30 s/d 12.30 WIB <br/><br/> 
Jadwal kerja tersebut di atas sewaktu-waktu dapat berubah sesuai dengan kebutuhan. Pihak Kedua wajib melakukan absensi saat masuk dan pulang kerja. 

<br/><br/>
6. Pihak Pertama memberikan Upah/Gaji pokok kepada Pihak Kedua sebesar Rp. <input type="text" id="gaji">,- (dua juta enam ratus tujuh puluh delapan ribu dua puluh sembilan rupiah)/bulan, dan pembayaran Gaji/Upah diberikan berdasarkan absensi kehadiran Karyawan dan dibayarkan setiap tanggal 1 (satu) setiap bulannya. 
 <br/><br/> 
7. Waktu dan Jam kerja di Perusahaan 6 (enam) hari kerja dalam seminggu, dengan ketentuan 7 (tujuh) jam sehari dan/atau 40 (empat puluh) jam seminggu serta hari Minggu merupakan hari libur/istirahat mingguan. 
 <br/><br/> 
8. Pihak Kedua wajib mematuhi Peraturan Perusahaan yang berlaku dan menjaga ketertiban, kedisiplinan, produktivitas kerja, kerjasama dan kebersihan di lingkungan kerjanya serta merawat peralatan kerja, mesin produksi, dan barang inventaris yang menjadi tanggung jawabnya. 
 <br/><br/> 
9. Pihak Kedua wajib memberitahukan melalui telepon, lisan atau tertulis jika berhalangan hadir dan memberikan alasan dengan jelas kepada pimpinan/atasannya, dan bilamana 5 (lima) hari tidak masuk kerja tanpa ada alasan yang sah dan tidak dapat dipertanggungjawabkan, serta sudah mendapatkan surat panggilan dari HRD maka dianggap mengundurkan diri dari Perusahaan atas permintaan sendiri. 
 <br/><br/> 
10. Pihak Pertama berhak untuk mengakhiri hubungan kerja sebelum berakhirnya tanggal perjanjian, dan prosesnya mengikuti peraturan perundang-undangan ketenagakerjaan yang berlaku.  
 <br/><br/> 
11. Apabila Pihak Kedua akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan Pihak Pertama, maka Pihak Kedua wajib memberitahukan kepada Pihak Pertama selambat-lambatnya 2 (dua) minggu sebelumnya. 
 <br/><br/> 
12. Perjanjian kerja ini dapat diperpanjang apabila Pihak Pertama memerlukan Pihak Kedua, dan Pihak Pertama akan memberitahukan kapada Pihak Kedua selambat-lambatnya 7 (tujuh) hari sebelum berakhirnya masa berlakunya Perjanjian Kerja ini untuk diperpanjang, dan perpanjangan tersebut atas kepentingan kedua belah pihak. 
 <br/><br/> 
13. Perjanjian Kerja ini dapat berubah sesuai dengan situasi dan kondisi Perusahaan, atau persetujuan kedua belah pihak dan wakil-wakil yang sah. 
 <br/><br/> 
14. Hal-hal yang belum tercantum dalam perjanjian ini, mengenai syarat-syarat kerja, hak dan kewajiban dalam hubungan kerja diatur dalam Peraturan Perusahaan. 
 <br/><br/> 
15. Perjanjian Kerja ini mulai berlaku sejak ditandatangani oleh kedua belah pihak sampai dengan berakhirnya masa Perjanjian Kerja, atau karena meninggalnya Pihak Kedua. 
 <br/><br/> 
16. Perjanjian Kerja ini dibuat dilembar kop surat perusahaan yang mempunyai kekuatan hukum sesuai dengan ketentuan perundang-undangan yang berlaku.
 <br/><br/> 
Demikian Perjanjian Kerja ini dibuat oleh kedua belah pihak dalam keadaan sehat jasmani dan rohani tanpa ada tekanan atau paksaan dari manapun. 
  <br/><br/> 
  
 <table width="100%">
	<tr>
		<td align="left">Majalaya, 
			<br/><br/> 
			PIHAK KEDUA, Karyawan 
			<br/><br/> 
 
 
 
			__________________  
			<br/><br/> 
			Copy Perjanjian (PKWT) ini<br/>  
			Sudah diterima oleh karyawan.<br/>
			Pada tanggal : 
			<select id="footer_tglday" style="width:auto" disabled > &nbsp;&nbsp;
				<option value ="-1" disabled>--Pilih Day--</option>
			</select>
			<select id="footer_tglmonth" style="width:auto" disabled>
				<option value ="-1" disabled>--Pilih Month--</option>&nbsp;&nbsp;
			</select>
			<select id="footer_tglyears" style="width:auto" disabled>
				<option value ="-1" disabled>--Pilih Years--</option>&nbsp;&nbsp;
			</select>			
		</td>
		<td  align="left">&nbsp;<br/><br/><br/><br/>PIHAK PERTAMA, PT. Nirwana Alabare Garment 
			<br/><br/> 
 
 
 
			________________________   
			<br/><br/> 
	Terdaftar di<br/>
	Suku Dinas Tenaga Kerja Dan Transmigrasi<br/>
	Administrasi Kabupaten Bandung.<br/>
	Tanggal : <br/>
		<select id="footer_tglday2" style="width:8%" disabled > &nbsp;&nbsp;
			<option value ="-1" disabled>--Pilih Day--</option>
		</select>
		<select id="footer_tglmonth2" style="width:18%" disabled >
			<option value ="-1" disabled>--Pilih Month--</option>&nbsp;&nbsp;
		</select>
		<select id="footer_tglyears2" style="width:15%" disabled >
			<option value ="-1" disabled>--Pilih Years--</option>&nbsp;&nbsp;
		</select>		
	</br>
	
	Nomor   :  </td>

	
	
</tr>
</table> 


</div>


	</div>




</div>
</div>

