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
	<button class="btn btn-primary" style="float:right" onclick="save()">Save</button>
  </div>
  <!--<div class="box-body"  style="overflow:scroll;height:500px"> -->
<div class="box-body" >
<div class="calibre" id="calibre_link-0">
				<div>
					<div class="row">
						<div class="col-md-4" style="padding:0">
							<img src="./css/img/logo.png" class="img-responsive center-block" width="40%" style="float:right" >
						</div>
						<div class="col-md-6" style="padding:0;">
							<div style="text-align:center;font-size:4.5vh;font-weight:bold;">PT.NIRWANA ALABARE GARMENT
								<div style="font-size:18px;">
									Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk,
									Kabupaten Bandung 40382 Telp. 022-85962081				
								</div>
							</div>
						</div>
					</div>
					<div class="horizontal">
					</div>
				</div>

	<p class="block_">KONTRAK KERJA</p>
	<p class="block_1"><span lang="id">033</span>/<span lang="id">HRD</span>-<span lang="id">NAG</span>/<span lang="id">PKWT</span>/<span lang="id">XII</span>/2017</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
	<li class="block_3"><b class="calibre1"><span class="calibre2">IDENTITAS  KARYAWAN</span></b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">1.1)&nbsp;</span><span class="calibre4">Nama Karyawan<span lang="id"><span class="tab"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>: 
	<select class='form-control select2' 
					id="content_nama"  onchange="getDetailNama(this,'2')" style="width:172px;position:absolute">
					<option  value='1' >--Pilih Nama--</option>
				 </select>

										 </span></span></span></div>

	<div class="block_4"><span class="bullet_">1.2)&nbsp;</span><span class="calibre4">Alamat Karyawan (<span lang="id">s</span>esuai Ktp<span lang="id">)<span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>:
	<input type="text" id="content_alamatkaryawan">

	<span lang="id"><span class="calibre2">  </span></span></span></div>
</div>
	<p class="block_5"><span lang="id"><span class="calibre2">  </span></span><span lang="id"> </span></p>
	<p class="block_5" lang="id"><span class="calibre2">   </span></p>
	<p class="block_6">&nbsp;</p>
	<ol class="list_">
	<li value="2" class="block_3"><b class="calibre1">PERIODE MULAI BEKERJA</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">2.1)&nbsp;</span><span class="calibre4">Periode kontrak :
	<input type="text" id="content_periodekontrak" onkeyup="handleChange(this)">
	
	<span lang="id"><span class="calibre2">  </span></span></span></div>
	<div class="block_4"><span class="bullet_">2.2)&nbsp;</span><span class="calibre4">Karyawan tidak memiliki ikatan kerja dengan <span lang="id">P</span>erusahaan lain<span lang="id"> </span>ketika <span lang="id"> </span>menandatangani </span></div>
</div>
	<p class="block_7">perjanjian ini<span lang="id">.</span></p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
	<li value="3" class="block_8"><b class="calibre1">JABATAN</b><b lang="id" class="calibre1"> </b><b class="calibre1">/</b><b lang="id" class="calibre1"> </b><b class="calibre1">POSISI</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">3.1)&nbsp;</span><span class="calibre4">Posisi karyawan : <input type="text" id="content_posisikaryawan"><span lang="id"> </span></span></div>
	<div class="block_4"><span class="bullet_">3.2)&nbsp;</span><span class="calibre4">Melapor kepada : 	<select class='form-control select2' 
					id="content_melaporkepada"  onchange="getDetailNama(this,'1')" style="width:172px;position:absolute">
					<option  value='1' >--Pilih Nama--</option>
				 </select><span lang="id"><span class="calibre2">  </span></span></span></div>
	<div class="block_4"><span class="bullet_">3.3)&nbsp;</span><span class="calibre4">Karyawan dapat dibutuhkan untuk melakukan tugas-tugas lain di<span lang="id"> </span>luar tanggung jawab</span></div>
</div>
	<p class="block_9">utama <span lang="id"><span class="calibre2">  </span></span>yang<span lang="id"><span class="calibre2">  </span></span> masih <span lang="id"><span class="calibre2">  </span></span>dalam <span lang="id"><span class="calibre2">  </span></span>kemampuan <span lang="id"><span class="calibre2">  K</span></span>aryawan<span lang="id">,</span> <span lang="id"> </span>baik <span lang="id"><span class="calibre2">  </span></span>secara <span lang="id"> </span>sementara <span lang="id"> </span>atau </p>
	<p class="block_9">permanen sesuai dengan kebutuhan dari <span lang="id">P</span>erusahaan.</p>
	<p class="block_6">&nbsp;</p>
	<ol class="list_">
	<li value="4" class="block_3"><b class="calibre1">JAM KERJA DAN LOKASI KERJA</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">4.1)&nbsp;</span><span class="calibre4">Jam kerja <span lang="id">K</span>aryawan adalah sebagai berikut :</span></div>
</div>
	<p class="block_9">Senin <span lang="id">s/d</span> Jumat <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>0<span lang="id">8.</span>0<span lang="id">0</span> &ndash; <span lang="id">1</span>7<span lang="id">.</span>0<span lang="id">0</span></p>
	<p class="block_9">Sabtu<span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span lang="id">0</span>8<span lang="id">.</span>0<span lang="id">0</span> &ndash; 12.30</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">4.2)&nbsp;</span><span class="calibre4">Lokasi <span lang="id"> </span>kerja<span lang="id">  K</span>aryawan <span lang="id"> </span>adalah<span lang="id">  M</span>ajalaya.<span lang="id"> P</span>erusahaan<span lang="id">  </span>dapat<span lang="id">  </span>dan<span lang="id">  </span>berhak<span lang="id"> </span>mengubah</span></div>
</div>
	<p class="block_10"><span lang="id">serta memindahkan bagian, tempat dan </span>lokasi<span lang="id"> </span> kerja<span lang="id"> </span> dari<span lang="id"> </span> <span lang="id">K</span>aryawan<span lang="id">, baik di luar kota maupun antar perusahaan di Nirwana Group.</span></p>
	<p class="block_6">&nbsp;</p>
	<ol class="list_">
	<li value="5" class="block_3"><b class="calibre1">STATUS K</b><b lang="id" class="calibre1">E</b><b class="calibre1">PEGAWAIAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">5.1)&nbsp;</span><span class="calibre4"><span lang="id">Kontrak Kerja Karyawan akan diperpanjang jika kinerja karyawan dinilai Baik. </span></span></div>
	<div class="block_4"><span class="bullet_">5.2)&nbsp;</span><span class="calibre4">Setelah<span lang="id"> </span> Karyawan<span lang="id"> </span> melewati<span lang="id"> </span> masa <span lang="id"> </span>kontrak<span lang="id"> 3 (tiga)  tahun d</span>an<span lang="id">  </span>menunjukan<span lang="id"> </span>evaluasi<span lang="id"> </span></span></div>
</div>
	<p class="block_9">kinerja<span lang="id">  </span>yang<span lang="id"> Sangat B</span>aik,<span lang="id">  </span>maka<span lang="id"> </span> Perusahaan dapat<span lang="id">  </span>mengangkat<span lang="id">  </span>Karyawan<span lang="id">  </span>tersebut<span lang="id"> </span></p>
	<p class="block_9">menjadi<span lang="id">  Karyawan  Tetap</span>.<span lang="id"> Dan  a</span>pabila<span lang="id">  </span>evaluasi<span lang="id">  </span>kinerja<span lang="id">  </span>Karyawan<span lang="id"> dianggap masih</span></p>
	<p class="block_9"><span lang="id">kurang  namun masih dapat dipekerjakan</span>, maka <span lang="id"> </span>Perusahaan<span lang="id"> </span>berhak menambah<span lang="id"> </span>durasi </p>
	<p class="block_9">kontrak kerja samp<span lang="id">a</span>i masa yang tidak ditentukan<span lang="id">.</span></p>
	<p class="block_11">&nbsp;</p>
	<p class="block_11">&nbsp;</p>
	<ol class="list_">
	<li value="6" class="block_8"><b class="calibre1">CUTI TAHUNAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre5">
	<div class="block_4"><span class="bullet_">6.1)&nbsp;</span><span class="calibre4">Karyawan <span lang="id"> </span>berhak<span lang="id">  </span>mendapatkan <span lang="id"> </span>cuti<span lang="id">  </span>tahunan <span lang="id"> </span>sebanyak 12 (<span lang="id">d</span>ua <span lang="id">b</span>elas<span lang="id">) </span>hari <span lang="id"> setelah bekerja  selama 1 (satu)  tahun </span>dan akan <span lang="id"> </span>digunakan<span lang="id"> </span>dalam <span lang="id"> </span>libur Hari <span lang="id"> </span>Raya Idul Fitri.<span lang="id"> </span></span></div>
</div>
	<p class="block_9">Hal ini akan diatur oleh<span lang="id"> </span>perusahaan setiap tahunnya.</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">6.2)&nbsp;&nbsp;</span><span class="calibre4">Karyawan<span lang="id"> </span>akan mendapat <span lang="id"> </span>potongan upah untuk<span lang="id"> </span>setiap <span lang="id">ijin </span>pribadi di<span lang="id"> </span>luar cuti <span lang="id">tahunan </span></span></div>
</div>
	<p class="block_12"><span lang="id">dan cuti-cuti resmi yang</span> telah diatur <span lang="id">dalam Peraturan </span>Perusahaan.</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
	<li value="7" class="block_8"><b lang="id" class="calibre1">IJIN</b><b class="calibre1"> SAKIT</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">7.1)&nbsp;</span><span class="calibre4">Karyawan <span lang="id"> </span>dapat<span lang="id"> </span> mengajukan<span lang="id"> ijin</span> <span lang="id"> </span>sakit <span lang="id"> </span>dengan<span lang="id"> </span> menunjukan Surat <span lang="id"> </span>S<span lang="id">a</span>kit<span lang="id"> </span>dari dokter </span></div>
</div>
	<p class="block_9">dan disetujui oleh Perusahaan.</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">7.2)&nbsp;</span><span class="calibre4">Apabila <span lang="id"> </span>Karyawan tidak dapat <span lang="id"> </span>menunjukkan surat dokter, maka Karyawan <span lang="id"> </span>dianggap </span></div>
</div>
	<p class="block_9">absen <span lang="id"> </span>dan akan <span lang="id"> </span>mendapat potongan <span lang="id"> </span>gaji sesuai <span lang="id"> </span>dengan <span lang="id">a</span>pa <span lang="id"> </span>yang<span lang="id"> </span> telah<span lang="id"> </span> diatur <span lang="id"> </span>oleh </p>
	<p class="block_9">Perusahaan.</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
	<li value="8" class="block_8"><b class="calibre1">TUNJANGAN KESEHATAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">8.1)&nbsp;</span><span class="calibre4">Karyawan <span lang="id"> </span>akan <span lang="id"> </span>mendapatkan <span lang="id"> </span>Tunjangan<span lang="id"> </span> Kesehatan <span lang="id"> </span>sesuai <span lang="id"> </span>dengan <span lang="id"> </span>apa yang telah </span></div>
</div>
	<p class="block_12"><span lang="id"></span>diatur oleh Perusahaan setelah Karyawan melewati masa <i class="calibre6">On the job training.</i></p>
	<p class="block_13">&nbsp;</p>
	<p class="block_13">&nbsp;</p>
	<ol class="list_">
	<li value="9" class="block_8"><b class="calibre1">PEMUTUSAN KONTRAK</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">9.1)&nbsp;</span><span class="calibre4">Apabila <span lang="id"> </span>Karyawan<span lang="id"> </span> hendak<span lang="id"> </span> mengakhiri<span lang="id"> </span> kontrak <span lang="id"> </span>sebelum masa kontraknya berakhir,</span></div>
</div>
	<p class="block_9">maka Karyawan diharuskan mengajukan surat pengunduran diri paling lambat 1 (satu) </p>
	<p class="block_9">bulan sebelum tanggal pengunduran diri.</p>
	<div class="calibre3">
	<div class="block_4"><span class="bullet_">9.2)&nbsp;</span><span class="calibre4">Perusahaan dapat <span lang="id"> </span>memutuskan kontrak ini <span lang="id"> </span>sesegera mungkin<span lang="id"> </span> tanpa kompensasi atau </span></div>
</div>
	<p class="block_7">pembayaran sama sekali apabila Karyawan :</p>
	<div class="calibre7">
	<div class="block_4"><span class="bullet_">-&nbsp;</span><span class="calibre4">Gagal untuk menunjukkan pengetahuan<span lang="id">, ilmu, dan kemampuan</span> atas pekerjaannya<span lang="id"> sesuai dengan yang diharapkan,</span> atau tidak dapat melaksanakan pekerjaannya.</span></div>
	<div class="block_4"><span class="bullet_">-&nbsp;</span><span class="calibre4">Secara sadar dan sengaja tidak mematuhi perintah<span lang="id">/petunjuk/arahan </span>yang sah dan wajar yang diberikan oleh Perusahaan.</span></div>
	<div class="block_4"><span class="bullet_">-&nbsp;</span><span class="calibre4">Bersalah atas kelalaian tertentu<span lang="id">, </span>melakukan pelangg<span lang="id">a</span>ran<span lang="id">, </span>atau segala tindakan tidak jujur dalam melaksanakan tugasnya.</span></div>
	<div class="block_4"><span class="bullet_">-&nbsp;</span><span class="calibre4">Menjadi terdakwa atau didakwa dengan pidana tertentu atau diragukan kemampuan di masa depan untuk melaksanakan tugas-tugasnya.</span></div>
</div>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
	<li value="10" class="block_8"><b class="calibre1">KERAHASIAN</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<p class="block_10">Karyawan mengetahui dan menyetujui bahwa seluruh materi informasi yang bersifat<b class="calibre1"> </b>non-publik, termasuk namun tidak terbatas pada informasi yang berhubungan dengan pendapat<span lang="id">an</span>, volume bisnis, metode bisnis, s<span lang="id">i</span>stem, rencana-rencana, akun-akun, ketentuan dalam Perjanjian ini, dan hal lain yang bersifat rahasia atau informasi hak milik yang bernilai komersil yang dimiliki oleh Perusahaan <b class="calibre1">(“Informasi Rahasia”)</b> akan tetap dirahasiakan dan tidak akan diungkapkan atau diberikan kepada pihak ketiga manapun tanpa persetujuan tertulis dari Perusahaan.</p>
	<p class="block_14">&nbsp;</p>
	<p class="block_10">Ketentuan dalam pasal ini akan terus berlaku meskipun setelah putus dan/atau berakhirnya Perjanjian ini, tanpa batasan waktu.</p>
	<p class="block_14">&nbsp;</p>
	<p class="block_14">&nbsp;</p>
	<ol class="list_">
	<li value="11" class="block_8"><b class="calibre1">HAK KEKAYAAN INTELEKTUAL</b></li>
</ol>
	<p class="block_2">&nbsp;</p>
	<p class="block_10">Karyawan dengan ini mengakui dan menyetujui bahwa seluruh merk dagang, nama dagang<span lang="id">,</span> logo, hak cipta dan hak milik lainnya, termasuk namun tidak terbatas pada penciptaan, paten, rahasia dagang, penemuan, teknik, proses, alat, penyempurnaan,<b class="calibre1"> </b><i class="calibre6">know-how,</i> perbaikan, s<span lang="id">i</span>stem, kurikulum, perubahan yang terkandung, gambar, tulisan, susunan desain, model, hasil karya seni, hasil pekerjaan pengarang dan benda berwuju<span lang="id">d </span>dan benda tidak berwujud lainnya <b class="calibre1">(“Hak Kekayaan Intelektual”) </b>yang dibuat dalam hubungannya dalam Perjanjian ini baik terdaftar maupun tidak, kan tetap dan merupakan hak milik eksklusif dari Perusahaan (atau pemilik yang sesuai) adalah pemilik dari seluruh hak, title, dan kepentingan atas Hak Kekayaan Intelektual baik yang berada dalam wilayahnya atau di tempat lain di seluruh dunia. Karyawan menjamin dan setuju untuk tidak mengambil tindakan apapun yang mungkin merugikan atau mempengaruhi validas dari Hak Kekayaan Intelektual atau kepemilikan Perusahaan (atau pemilik yang sesuai) atau lisensi daripadanya dan akan berhenti menggunakan Hak Kekayaan Intelektual setelah putusnya Perjanjian ini. </p>
	<p class="block_2">&nbsp;</p>
	<p class="block_2">&nbsp;</p>
	<ol class="list_">
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
	
	<input type="text" id="footer_namahrd" readonly>
	<span lang="id"> </span> </p>
	<p class="block_17">Posisi <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	: <input type="text" id="footer_posisihrd" readonly>
	
	<span lang="id"> </span></p>
	<p class="block_17">Tanggal <span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>: 
							<input type='text' class='form-control' id='footer_tanggalhrd' style="width:175px;position:absolute;margin-left:85px;margin-top:-19px" onchange="handleChange(this)" >
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
	<p class="block_17">Nama<span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>:<input type="text" id="footer_namapekerja" readonly><span lang="id">  </span></p>
	<p class="block_19">Tanggal<span lang="id"><span class="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span><span lang="id"><span class="calibre2">: 
	<input type='text' class='form-control' id='footer_tanggalpekerja' style="width:175px;position:absolute;margin-left:85px;margin-top:-19px" onchange="handleChange(this)"  >
	</span></span></p>

</div>

</body></html>


 

</div>


	</div>





