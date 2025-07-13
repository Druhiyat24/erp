<?php 
if (empty($_SESSION['username'])) { header("location:../"); }
$user = $_SESSION['username'];

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
if(isset($_GET['dest'])) { $dest=$_GET['dest']; } else { $dest=""; }
$akses_read = flookup("username","userpassword","username='$user' and security='1'");
$akses_hide = flookup("username","userpassword","username='$user' and hide_sec='1'");
if($akses_read=="")
{ $akses = flookup("groupp","userpassword","username='$user'");
  if ($akses!="SECURITY") 
  { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
}
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
if(isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
if ($id_item=="")
{ $jenis_kendaraan = "";
  $nama_supir = "";
  $nomor_polisi = "";
  $nomor_sj = "";
  $id_supplier = "";
  $jenis_barang = "";
  $pono = "";
  $jumlah = "";
  $satuan = "";
}
else
{ $query = mysql_query("SELECT * FROM list_in_out where id='$id_item'");
  $data = mysql_fetch_array($query);
  $jenis_kendaraan = $data['jenis_kendaraan'];
  $nama_supir = $data['nama_supir'];
  $nomor_polisi = $data['nomor_polisi'];
  $nomor_sj = $data['nomor_sj'];
  $id_supplier = $data['id_supplier'];
  $jenis_barang = $data['jenis_barang'];
  $pono = $data['pono'];
  $jumlah = $data['qty'];
  $satuan = $data['unit'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var jenis_kendaraan = document.form.txtjenis_kendaraan.value;
    var nama_supir = document.form.txtnama_supir.value;
    var nomor_polisi = document.form.txtnomor_polisi.value;
    var nomor_sj = document.form.txtnomor_sj.value;
    var id_supplier = document.form.txtid_supplier.value;
    var jenis_barang = document.form.txtjenis_barang.value;
    
    if (jenis_kendaraan == '') { document.form.txtjenis_kendaraan.focus(); swal({ title: 'Jenis Kendaraan Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (nama_supir == '') { document.form.txtnama_supir.focus(); swal({ title: 'Nama Supir Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (nomor_polisi == '') { document.form.txtnomor_polisi.focus(); swal({ title: 'Nomor Polisi Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (nomor_sj == '') { document.form.txtnomor_sj.focus(); swal({ title: 'Nomor SJ Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_supplier == '') { document.form.txtid_supplier.focus(); swal({ title: 'Nama Supplier Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (jenis_barang == '') { document.form.txtjenis_barang.focus(); swal({ title: 'Nama Barang Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
?>
<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="1") {
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_list_in_out.php?mod=<?php echo $mod;?>&id=<?php echo $id_item;?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Jenis Kendaraan *</label>
            <input type='text' class='form-control' name='txtjenis_kendaraan' placeholder='Masukkan Jenis Kendaraan' value='<?php echo $jenis_kendaraan;?>' >
          </div>        
          <div class='form-group'>
            <label>Nama Supir *</label>
            <input type='text' class='form-control' name='txtnama_supir' placeholder='Masukkan Nama Supir' value='<?php echo $nama_supir;?>' >
          </div>        
          <div class='form-group'>
            <label>Nomor Polisi *</label>
            <input type='text' class='form-control' name='txtnomor_polisi' placeholder='Masukkan Nomor Polisi' value='<?php echo $nomor_polisi;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Nomor SJ *</label>
            <input type='text' class='form-control' name='txtnomor_sj' placeholder='Masukkan Nomor SJ' value='<?php echo $nomor_sj;?>' >
          </div>
          <div class='form-group'>
            <label>Nama Supplier *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_supplier'>
              <?php 
                $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup in ('S','C') order by supplier";
                IsiCombo($sql,$id_supplier,'Pilih Nama Supplier');
              ?>
            </select>
          </div>        
          <div class='form-group'>
            <label>PO #</label>
            <input type='text' class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >
          </div>
        </div>
        <div class='col-md-3'>          
          <div class='form-group'>
            <label>Nama Barang *</label>
            <input type='text' class='form-control' name='txtjenis_barang' placeholder='Masukkan Nama Barang' value='<?php echo $jenis_barang;?>' >
          </div>
          <div class='form-group'>
            <label>Jumlah</label>
            <input type='number' class='form-control' step="any" name='txtjumlah' placeholder='Masukkan Jumlah' value='<?php echo $jumlah;?>' >
          </div>
          <div class='form-group'>
            <label>Satuan</label>
            <select class='form-control select2' style='width: 100%;' name='txtsatuan'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Satuan'";
                IsiCombo($sql,$satuan,'Pilih Satuan');
              ?>
            </select>
          </div>        
        </div>
      </form>
    </div>
  </div>
</div> 
<?php } else { ?>
<div class="box">
  <div class="box-header">
  	<?php if($akses_read=="") { ?>
      <a href='../sec/?mod=1' class='btn btn-primary btn-s'>
    		<i class='fa fa-plus'></i> New
    	</a>
    <?php } elseif ($dest!="xls") { ?>
      <a href='../sec/?mod=1v&dest=xls'>
        Save Excel
      </a>
    <?php } ?>
	</div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	    	<th>No</th>
	    	<th>Tanggal</th>
        <th>Nomor SJ</th>
        <th>Supplier</th>
				<th>Nama Barang</th>
				<th>Nama Supir</th>
				<th>Kendaraan</th>
				<th>Nomor Polisi</th>
        <th>PO #</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th></th>
        <th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("select d.bpbno,a.*,s.supplier from list_in_out a 
		    	inner join mastersupplier s on a.id_supplier=s.id_supplier 
          left join bpb d on a.id=d.id_sec 
          where dihide='N' order by dateinput desc "); 
        $no = 1;
        while($data = mysql_fetch_array($query))
			  { echo "
					<tr>
						<td>$no</td>
						<td>".fd_view_dt($data['dateinput'])."</td>
            <td>$data[nomor_sj]</td>
						<td>$data[supplier]</td>
						<td>$data[jenis_barang]</td>
						<td>$data[nama_supir]</td>
						<td>$data[jenis_kendaraan]</td>
						<td>$data[nomor_polisi]</td>
            <td>$data[pono]</td>
            <td>$data[qty]</td>
            <td>$data[unit]</td>";
            if($data['bpbno']=="" and $akses_read=="")
            { echo "<td><a href='../sec/?mod=1&id=$data[id]' $tt_ubah </a></td>"; }
            else
            { echo "<td></td>"; }
            if($akses_hide!="")
            { if($data['dihide']=="N")
              { echo "<td><a href='../sec/?mod=1vh&id=$data[id]' data-toggle='tooltip' title='Hide'><i class='fa fa-eye-slash'></i> </a></td>"; }
              else
              { echo "<td>Hide</td>"; }  
            }
            else
            { echo "<td></td>"; }
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php }
# END COPAS ADD
?>