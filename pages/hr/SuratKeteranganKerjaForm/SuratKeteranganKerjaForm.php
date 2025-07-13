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
		<h4>Surat Keterangan Form </h4>
	<button class="btn btn-default" style="float:right" onclick="back()" >Back</button>
	<button class="btn btn-primary" style="float:right" onclick="save()">Save</button>		
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
									Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk,
									Kabupaten Bandung 40382 Telp. 022-85962081				
								</div>
							</div>
						</div>
					</div>
					<div class="horizontal">
					</div>
				</div>
				<div class="containerContent" align="justify">
					<p class="contentTitle">SURAT KETERANGAN KERJA  </p>
					<center>Yang bertanda tangan di bawah ini menerangkan dengan sebenarnya bahwa :  </center>
					<br/>
					<br/>
					<table width="100%">
					<tr>
						<td style="position:absolute;margin-top:300px;width:15px;"><div style="position:absolute;margin-top:30px">&nbsp;</div></td>
						<td><div class="pihak12">
							<table width="100%">
								<tr>
									<td class="tablecontent" align="left" style="width:3px;">
										<p><u>Nama</u>
										<p style="text-decoration:none;border-bottom: none;margin-top:-16px">Name</p> 
									</td> 
									<td align="left" >
										<div style="text-decoration:none;border-bottom: none;margin-top:-24px">
											: <select class='form-control select2' 
												name='txtid_buyer' id="content_nama"  onchange="getDetailNama(this,'1')" style="width:80%">
												<option  value='1' >--Pilih Nama--</option>
											</select>
										</div> 
									</td> 
								</tr>
								<tr>
									<td class="tablecontent" align="left"><p><u>No.Induk</u><br/><p style="text-decoration:none;border-bottom: none;margin-top:-16px">Reg. No. </p> </td> 
									<td align="left">: <input type="text" id="content_noinduk" readonly style="width:80%" > </td>  
								</tr>
								<tr>
									<td class="tablecontent" align="left"><u><p >Bagian</u>   <br/><p style="text-decoration:none;border-bottom: none;">Departement  </p> </td> 
									<td align="left">: <input type="text" id="content_department" readonly style="width:80%"> </td>  
								</tr>		
								<tr>
									<td class="tablecontent" align="left"><p ><u>Jabatan Terakhir</u><br/><p style="text-decoration:none;border-bottom: none;margin-top:-16px">>Final Classification</p> </td> 
									<td align="left">: <input type="text" id="content_jabatan" readonly style="width:80%"></td>  
								</tr>
								<tr>
									<td class="tablecontent" align="left"><p ><u>Lamanya Bekerja</u><br/><p style="text-decoration:none;border-bottom: none;margin-top:-16px">Period of Service</p> </td> 
									<td align="left" width="500px">: <input type="text" id="content_lamabekerja" style="width:80%" onkeyup="handleKeyUp(this)" > </td>  
								</tr>	
								<tr>
									<td class="tablecontent" align="left"><p ><u>Sebab Berhenti Bekerja</u><br/><p style="text-decoration:none;border-bottom: none;margin-top:-16px">Reason for Termination</p> </td> 
									<td align="left" width="500px">: <input type="text" id="content_alasan" style="width:80%" onkeyup="handleKeyUp(this)" > </td>  
								</tr>	
									
								
							</table>
						</div>
						</td>
					
					</tr>
					</table>
					
					
					<br/>
					Selama bekerja pada perusahaan kami, yang bersangkutan menunjukan prestasi dan disiplin kerja yang
					baik, oleh karenanya kami mengucapkan terima kasih atas jasa-jasanya yang telah diberikan kepada
					perusahaan.
					<br/><br/>
					
					
					Demikian surat keterangan ini diberikan untuk dipergunakan sebagaimana mestinya
					<br/><br/> 
					
					<table width="100%">
						<tr>
							<td align="left">Majalaya, 
							
							<select id="footer_tglday" style="width:auto"> &nbsp;&nbsp;
								<option value ="-1" disabled>--Pilih Day--</option>
							</select>
							<select id="footer_tglmonth" style="width:auto">
								<option value ="-1" disabled>--Pilih Month--</option>&nbsp;&nbsp;
							</select>
							<select id="footer_tglyears" style="width:auto">
								<option value ="-1" disabled>--Pilih Years--</option>&nbsp;&nbsp;
							</select>
								<br/><br/> 
								PT. Nirwana Alabare Garment 
								<br/><br/> 
					
					
					
								_________________________________________
								<p style="padding-bottom: 40px;border-bottom: 1px solid black">
									<select class='form-control select2' 
										name='txtid_buyer' id="footer_personpt"  onchange="getDetailNama(this,'2')" style="width:25%">
										<option  value='1' >--Pilih Nama--</option>
									 </select>
								</p> 
								Mgr. HRGA 
							</td>
					</tr>
					</table> 
				</div>
			</div>
	</div>
</div>
