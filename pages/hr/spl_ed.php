<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("hr_spl","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

# COPAS EDIT
$nik=split(":",$_GET['id'])[0];
$tgl=split(":",$_GET['id'])[1];
$query = mysql_query("SELECT a.*,s.nama FROM hr_spl a 
	inner join hr_masteremployee s on a.nik=s.nik where a.nik='$nik' 
	and tanggal='$tgl'");
$data = mysql_fetch_array($query);
$nama = $data['nama'];
$tanggal = fd_view($data['tanggal']);
$keterangan = $data['keterangan'];
$mulai = $data['mulai'];
$selesai = $data['selesai'];
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var line = document.form.txtline.value;
		var tanggal = document.form.txttanggal.value;
		var keterangan = document.form.txtketerangan.value;
		var mulai = document.form.txtmulai.value;
		var selesai = document.form.txtselesai.value;
		if (line == '') { swal({ title: 'Line Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (tanggal == '') { document.form.txttanggal.focus(); swal({ title: 'Tanggal Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (keterangan == '') { document.form.txtketerangan.focus(); swal({ title: 'Keterangan Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (mulai == '') { document.form.txtmulai.focus(); swal({ title: 'Jam Mulai Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (selesai == '') { document.form.txtselesai.focus(); swal({ title: 'Jam Selesai Tidak Boleh Kosong', $img_alert }); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
	function getLine(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: "ajax_spl.php?mdajax=get_list_line",
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#txtline").html(html); }
  };
  function getListNIK()
  { var line = document.form.txtline.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_spl.php?mdajax=view_list_nik',
        data: {line: line},
        async: false
    }).responseText;
    if(html)
    {	$("#detail_item").html(html); }
  };
</script>
<?php if ($mod=="18e") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' action='s_spl_ed.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>							
					<div class='form-group'>
			      <label>NIK *</label>
			      <input type='text' readonly class='form-control' name='txtnik' value='<?php echo $nik;?>' >
			    </div>
			    <div class='form-group'>
			      <label>Nama</label>
			      <input type='text' readonly class='form-control' name='txtnama' value='<?php echo $nama;?>' >
			    </div>
			 	</div>
			 	<div class='col-md-3'>
    			<div class='form-group'>
						<label>Tanggal *</label>
						<input type='text' readonly class='form-control' name='txttanggal' placeholder='Masukkan Tanggal' value='<?php echo $tanggal;?>' >
					</div>				
					<div class='form-group'>
						<label>Keterangan *</label>
						<input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='<?php echo $keterangan;?>' >
					</div>
				</div>
				<div class='col-md-3'>				
					<div class="bootstrap-timepicker">
						<div class='form-group'>
							<label>Jam Mulai *</label>
							<input type='text' class='form-control timepicker1' name='txtmulai' placeholder='Masukkan Jam Mulai' value='<?php echo $mulai;?>' >
						</div>
					</div>
					<div class="bootstrap-timepicker">
						<div class='form-group'>
							<label>Jam Selesai *</label>
							<input type='text' class='form-control timepicker' name='txtselesai' placeholder='Masukkan Jam Selesai' value='<?php echo $selesai;?>' >
						</div>
					</div>
				</div>
				<div class='box-body'>
        	<div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
        	<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
			</form>
		</div>
	</div>
</div><?php }  
# END COPAS ADD
?>