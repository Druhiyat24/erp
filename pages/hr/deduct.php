<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("hr_deduction","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
$titlenya="Potongan Lain Lain";
$mode="";
$mod=$_GET['mod'];
# COPAS EDIT
if ($id_item=="")
{ $nik = "";
  $paydate = "";
  $amount = "";
  $keterangan = "";
}
else
{ $cri=split(":",$id_item);
  $nik = $cri[0];
  $paydate = $cri[1];
  $query = mysql_query("SELECT * FROM hr_deduction where 
    nik='$nik' and paydate='$paydate'");
  $data = mysql_fetch_array($query);
  $paydate = fd_view($paydate);
  $amount = $data['amount'];
  $keterangan = $data['keterangan'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var nik = document.form.txtnik.value;
    var paydate = document.form.txtpaydate.value;
    var amount = document.form.txtamount.value;
    var keterangan = document.form.txtketerangan.value;
    if (nik == '') { document.form.txtnik.focus(); swal({ title: 'NIK Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (paydate == '') { document.form.txtpaydate.focus(); swal({ title: 'Pay Date Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (amount == '') { document.form.txtamount.focus(); swal({ title: 'Amount Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (keterangan == '') { document.form.txtketerangan.focus(); swal({ title: 'Keterangan Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI

# COPAS ADD
if ($mod=="23") {
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_deduct.php?mod=<?php echo $mod; ?>&id=<?php echo $id_item; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>                
          <div class='form-group'>
            <label>NIK *</label>
            <select class='form-control select2' style='width: 100%;' name='txtnik'>
              <?php 
                if ($id_item=="") { $sql_wh=""; } else { $sql_wh=" and nik='$nik'"; }
                $sql = "select nik isi,concat(nik,':',nama) tampil 
                  from hr_masteremployee where (selesai_kerja='0000-00-00'
                  or selesai_kerja is null) $sql_wh ";
                IsiCombo($sql,$nik,'Pilih NIK');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <?php if ($id_item=="") { $readonly=""; } else { $readonly=" readonly"; } ?>
            <label>Pay Date *</label>
            <input type='text' <?php echo $readonly; ?> id='datepicker1' class='form-control' name='txtpaydate' placeholder='Masukkan Pay Date' value='<?php echo $paydate;?>' >
          </div>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Amount *</label>
            <input type='text' class='form-control' name='txtamount' placeholder='Masukkan Amount' value='<?php echo $amount;?>' >
          </div>        
          <div class='form-group'>
            <label>Keterangan *</label>
            <input type='text' class='form-control' name='txtketerangan' placeholder='Masukkan Keterangan' value='<?php echo $keterangan;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
} else {
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
      <a href='../hr/?mod=23' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>NIK</th>
          <th>Nama</th>
          <th>Pay Date</th>
          <th>Amount</th>
          <th>Keterangan</th>
          <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT a.*,s.nama FROM hr_deduction a inner join hr_masteremployee s 
          on a.nik=s.nik ");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { $cri=$data['nik'].":".$data['paydate'];
          echo "<tr>";
				  echo "
          <td>$no</td>
          <td>$data[nik]</td>
          <td>$data[nama]</td>
          <td>$data[paydate]</td>
          <td>$data[amount]</td>
          <td>$data[keterangan]</td>";
          echo "
          <td>
            <a $cl_ubah href='../hr/?mod=23&id=$cri' 
              class='btn btn-info btn-s' $tt_ubah
            </a>
            <a $cl_hapus href='del_data.php?mod=23&id=$cri&pro=Ded' $tt_hapus";?> 
              onclick="return confirm('Apakah anda yakin akan menghapus ?')">
              <?PHP echo $tt_hapus2."</a>
          </td>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>