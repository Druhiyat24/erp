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
	{	var line = document.form.txtline.value;
		var tanggal = document.form.txttanggal.value;
		var keterangan = document.form.txtketerangan.value;
		var jamspl = document.form.txtjamspl.value;
		var mulai = document.form.txtmulai.value;
		var selesai = document.form.txtselesai.value;
		var pilih = 0;
    var chks = document.form.getElementsByClassName('chkclass');
    for (var i = 0; i < chks.length; i++) 
    { if (chks[i].checked) 
      { pilih = pilih + 1; }
    }
		if (tanggal == '') { document.form.txttanggal.focus(); swal({ title: 'Tanggal Tidak Boleh Kosong', $img_alert }); valid = false;}
		else if (pilih == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false;}
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
	function getLine()
  { var dept = document.form.txtdept.value;
  	var cri_item = document.form.txtbagian.value;
  	var html = $.ajax
    ({  type: "POST",
        url: "ajax_spl.php?mdajax=get_list_line",
        data: {cri_item: cri_item, dept: dept},
        async: false
    }).responseText;
    if(html)
    { $("#txtline").html(html); }
  	var dept = document.form.txtdept.value;
  	var bagian = document.form.txtbagian.value;
    var tglnya = document.form.txttanggal.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_spl.php?mdajax=view_list_nik2',
        data: {dept: dept,bagian: bagian,tglnya: tglnya},
        async: false
    }).responseText;
    if(html)
    {	$("#detail_item").html(html); }
  };
  function getBag(cri_item)
  { var html = $.ajax
    ({  type: "POST",
        url: "ajax_spl.php?mdajax=get_list_bagian",
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    { $("#txtbagian").html(html); }
  	var dept = document.form.txtdept.value;
  	var tglnya = document.form.txttanggal.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_spl.php?mdajax=view_list_nik3',
        data: {dept: dept, tglnya: tglnya},
        async: false
    }).responseText;
    if(html)
    {	$("#detail_item").html(html); }
  };
  function getListNIK()
  { var line = document.form.txtline.value;
  	var tglnya = document.form.txttanggal.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_spl.php?mdajax=view_list_nik',
        data: {line: line,tglnya: tglnya},
        async: false
    }).responseText;
    if(html)
    {	$("#detail_item").html(html); }
  };
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
</script>
<?php if ($mod=="18") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' action='s_spl.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
				<div class='col-md-3'>							
					<div class='form-group'>
			      <label>Department *</label>
			      <?php
			      $sql = "select nama_pilihan isi, nama_pilihan tampil from 
			        masterpilihan where kode_pilihan='Dept'";
			      ?>
			      <select class='form-control select2' style='width: 100%;' 
			        name='txtdept' onchange='getBag(this.value)'>
			      <?php
			      IsiCombo($sql,$dept,'Pilih Dept');
			      ?>
			      </select>
			    </div>
			    <div class='form-group'>
			      <label>Bagian *</label>
			      <select class='form-control select2' style='width: 100%;' 
			        name='txtbagian' id='txtbagian' onchange='getLine()'>
			      </select>
			    </div>
			    <div class='form-group'>
			      <label>Line *</label>
			      <select class='form-control select2' style='width: 100%;' 
			        name='txtline' id='txtline' onchange='getListNIK()'>
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
		<table id="example1" class="table table-bordered table-striped">
			<thead>
        <tr>
		      <th>No</th>
          <th>Nik</th>
          <th>Nama</th>
          <th>Tanggal</th>
          <th>Jam Mulai</th>
          <th>Jam Selesai</th>
          <th>Keterangan</th>
          <th>Action</th>
        </tr>
     	</thead>
    	<tbody>
      	<?php
        # QUERY TABLE
        $query = mysql_query("SELECT a.*,s.nama 
        	FROM hr_spl a inner join hr_masteremployee s 
          on a.nik=s.nik order by a.tanggal desc");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
  				  echo "<td>$no</td>"; 
  				  echo "<td>$data[nik]</td>"; 
  				  echo "<td>$data[nama]</td>"; 
  				  echo "<td>$data[tanggal]</td>";
            echo "<td>$data[mulai]</td>"; 
  				  echo "<td>$data[selesai]</td>"; 
            echo "<td>$data[keterangan]</td>";
            $pared=$data['nik'].":".$data['tanggal'];
            echo "<td><a href='../hr/?mod=18e&id=$pared'>Ubah</a> | 
            <a href='del_data.php?id=$pared&pro=SPL'";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
				  echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
        </tbody>
		</table>
	</div>
</div><?php } ?>