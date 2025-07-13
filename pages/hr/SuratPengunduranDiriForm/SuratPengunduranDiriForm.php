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
	<h4>Surat Pengunduran Diri Form </h4>
	<button class="btn btn-default" style="float:right" onclick="back()" >Back</button>
	<button class="btn btn-primary" style="float:right" onclick="save()">Save</button>		
  </div>
  <!--<div class="box-body"  style="overflow:scroll;height:500px"> -->
<div class="box-body" >

	<div class="container">
<div>




</div>

<div class="containerContent" align="justify">
<p align="right">Solokan,Jeruk,

				<select id="header_tglday"  width="30%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Tanggal--</option>
				</select> &nbsp
						<select id="header_tglmonth"  width="20%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Bulan--</option>
				</select>	&nbsp;
							<select id="header_tglyears" width="30%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Tahun--</option>
				</select> 

</p>
 <br/>

<br/>

Kepada Yth, 
 <br/>
Bapak/Ibu Personalia 
 <br/>
Di 
 <br/>
Tempat,  
 <br/> 
 Dengan Hormat,
 <br/>
 <br/>
 Yang bertanda tangan di bawah ini,  
 

 <br/>
 <table width="100%">
 <tr>
<div class="pihak12">
	<table width="100%">
		<tr>
			<td class="tablecontent" align="left">Nama </td> 
			<td align="left">: <select class='form-control select2' 
											name='txtid_buyer' id="content_nama"  onchange="getDetailNama(this,'1')" style="width:80%">
											<option  value='1' >--Pilih Nama--</option>
										 </select>
			</td> 
		</tr>
		<tr>
			<td align="left">Jabatan </td> 
			<td align="left">: <input type="text" style="width:80%;" readonly id="content_jabatan" width="80%"> &nbsp; </td> 
		</tr>
		<tr>
			<td align="left">Tanggal Masuk </td> 
			<td align="left">: 
				<select id="content_tglday" disabled width="300px">
					<option value="-1">--Pilih Tanggal--</option>
				</select> &nbsp
						<select id="content_tglmonth" disabled width="20%">
					<option value="-1">--Pilih Bulan--</option>
				</select>	&nbsp;
							<select id="content_tglyears" disabled width="30%">
					<option value="-1">--Pilih Tahun--</option>
				</select> </td> 
		</tr>
		<tr>
			<td align="left">Alamat </td> 
			<td align="left">: <input type="text" style="width:80%;" id="content_alamat" readonly>    </td> 
		</tr>		
	</table>
 </div>
 

 </tr>
</table>




<br/>
<br/>
Dengan ini saya mengundurkan diri dari PT. 
Nirwana Alabare Garment sejak 
tanggal 
				<select id="footer_tglday" width="30%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Tanggal--</option>
				</select> &nbsp
						<select id="footer_tglmonth"  width="20%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Bulan--</option>
				</select>	&nbsp;
							<select id="footer_tglyears" width="30%" onchange="handleKeyUp(this)">
					<option value="-1">--Pilih Tahun--</option>
				</select> 
atas keinginan sendiri tanpa ada paksaan dari pihak manapun. 
<br/><br/>
Dengan dibuat surat pengunduran diri ini maka terhadap 
pihak perusahaan telah selesai dan 
tidak akan ada tuntutan apapun di kemudian hari. 
<br/><br/>
Demikian surat pengunduran diri saya buat. 
 <br/><br/>
Atas perhatian Bapak/Ibu pimpinan 
PT. Nirwana Alabare Garment saya ucapkan terimakasih. 
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


	</div>




</div>
</div>

