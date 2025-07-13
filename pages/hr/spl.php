<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("hr_spl","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
$id_item="";

# COPAS EDIT
if ($id_item=="")
{	$tanggal = date('d M Y');
	$keterangan = "";
	$jamspl = "";
	$mulai = "";
	$selesai = "";
}
else
{	$query = mysql_query("SELECT * FROM hr_spl where id_item='$id_item' ORDER BY id_item ASC");
	$data = mysql_fetch_array($query);
	$tanggal = $data['tanggal'];
	$keterangan = $data['keterangan'];
	$jamspl = $data['jamspl'];
	$mulai = $data['mulai'];
	$selesai = $data['selesai'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var options = document.getElementById('txtnik').options, count = 0;
		for (var i=0; i < options.length; i++) {
		  if (options[i].selected) count++;
		}
		var tanggal = document.form.txttanggal.value;
		var keterangan = document.form.txtketerangan.value;
		var jamspl = document.form.txtjamspl.value;
		var mulai = document.form.txtmulai.value;
		var selesai = document.form.txtselesai.value;
		if (count == 0) { swal({ title: 'NIK Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (tanggal == '') { document.form.txttanggal.focus(); swal({ title: 'Tanggal Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (keterangan == '') { document.form.txtketerangan.focus(); swal({ title: 'Keterangan Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (jamspl == '') { document.form.txtjamspl.focus(); swal({ title: 'Jam SPL Tidak Boleh Kosong', $img_alert }); valid = false;}
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
	function CalcMulaiSelesai()
  { var jamnya = document.form.txtjamspl.value;
  	var tglnya = document.form.txttanggal.value;
  	jQuery.ajax
    ({  
      url: "ajax_spl.php?mdajax=calc_jam_mulai_selesai",
      method: 'POST',
      data: {jamnya: jamnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#jam_mulai').val(response[0]); 
      	$('#jam_selesai').val(response[1]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function getListData()
  { var tglnya = document.formfilter.txttglfilter.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_spl.php?mdajax=view_list_nik2spl',
        data: {tglnya: tglnya},
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
  };
</script>
<?php if ($mod=="18") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' action='s_spl.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>							
					<div class='form-group'>
			      <label>NIK *</label>
			      <?php
			      $sql = "select nik isi, concat(nik,' ',ucase(nama)) tampil 
			      	from hr_masteremployee where selesai_kerja='0000-00-00' or selesai_kerja is null";
			      ?>
			      <select class='form-control select2' multiple='multiple' style='width: 100%;' 
			        name='txtnik[]' id='txtnik'>
			      <?php
			      IsiCombo($sql,'-','');
			      ?>
			      </select>
			    </div>
			    </div>
			 	<div class='col-md-3'>
    			<div class='form-group'>
						<label>Tanggal *</label>
						<input type='text' class='form-control' id='datepicker1' name='txttanggal' placeholder='Masukkan Tanggal' value='<?php echo $tanggal;?>' >
					</div>				
					<div class='form-group'>
						<label>Keterangan *</label>
						<input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='<?php echo $keterangan;?>' >
					</div>
					<div class='form-group'>
						<label>Jam SPL *</label>
						<input type='text' class='form-control' name='txtjamspl' 
						placeholder='Masukkan Jam SPL' value='<?php echo $jamspl;?>' onchange="CalcMulaiSelesai()">
					</div>
				</div>
				<div class='col-md-3'>				
					<div class="bootstrap-timepicker">
						<div class='form-group'>
							<label>Jam Mulai *</label>
							<input type='text' class='form-control timepicker1' name='txtmulai' id='jam_mulai' placeholder='Masukkan Jam Mulai' value='<?php echo $mulai;?>' >
						</div>
					</div>
					<div class="bootstrap-timepicker">
						<div class='form-group'>
							<label>Jam Selesai *</label>
							<input type='text' class='form-control timepicker' name='txtselesai' id='jam_selesai' placeholder='Masukkan Jam Selesai' value='<?php echo $selesai;?>' >
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
</div><?php } else {  
# END COPAS ADD
?>
<div class="box">
	<div class="box-header">
	  <h3 class="box-title">List SPL</h3>
	  <a href='../hr/?mod=18' class='btn btn-primary btn-s'>
	    <i class='fa fa-plus'></i> New
	  </a>
	</div>
	<div class="box-body">
    <form name='formfilter'>
      <div class='col-md-3'>
        <div class='form-group'>
          <label>Tanggal *</label>
          <input type='text' $read class='form-control' id='datepicker2' 
            name='txttglfilter' onchange='getListData()' placeholder='Masukkan Tanggal'>
        </div>
      </div>
      <div id='detail_item'></div>
    </form>
	</div>
</div><?php } ?>