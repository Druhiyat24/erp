<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_bank","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_bank = $_GET['id']; } else {$id_bank = "";}
$titlenya="Master Bank";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_bank=="")
{ $bank = "";
  $bank_desc = "";
  $curr = "";
  $norek = "";
  $namarek = "";
}
else
{ $query = mysql_query("SELECT * FROM masterbank where id='$id_bank'");
  $data = mysql_fetch_array($query);
  $bank = $data['kode_bank'];
  $bank_desc = $data['nama_bank'];
  $curr = $data['curr'];
  $norek = $data['no_rek'];
  $namarek = $data['nama_rek'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var bank = document.form.txtbank.value;
      var bank_desc = document.form.txtbank_desc.value;
      var curr = document.form.txtcurr.value;
      var norek = document.form.txtnorek.value;
      var namarek = document.form.txtnamarek.value;
      if (curr == '') { alert('Currency Tidak Boleh Kosong'); valid = false;}
      else if (bank == '') { alert('Bank Tidak Boleh Kosong'); document.form.txtbank.focus();valid = false;}
      else if (bank_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtbank_desc.focus();valid = false;}
      else if (norek == '') { alert('No Rekening Tidak Boleh Kosong'); document.form.txtnorek.focus();valid = false;}
      else if (namarek == '') { alert('Nama Rekening Tidak Boleh Kosong'); document.form.txtnamarek.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bank.php?mod=$mod&id=$id_bank' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Currency *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
            	where kode_pilihan='Curr'";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcurr'>";
            IsiCombo($sql,$curr,$cpil.' '.$c33);
            echo "</select>            
          </div>
          <div class='form-group'>
            <label>Kode Bank *</label>
            <input type='text' class='form-control' name='txtbank' 
            placeholder='Masukkan Kode Bank' value='$bank'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Bank</label>
            <input type='text' class='form-control' name='txtbank_desc' 
            placeholder='Masukkan Deskripsi Bank' value='$bank_desc'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>
        <div class='col-md-3'>
        	<div class='form-group'>
            <label>No Rekening</label>
            <input type='text' class='form-control' name='txtnorek' 
            placeholder='Masukkan No Rekening' value='$norek'>
          </div>
          <div class='form-group'>
            <label>Nama Rekening</label>
            <input type='text' class='form-control' name='txtnamarek' 
            placeholder='Masukkan Nama Rekening' value='$namarek'>
          </div>";
          echo "<div class='form-group'>";
            echo "<label>ID CoA</label>";
            $sql = "select id_coa isi,concat(id_coa,'|',nm_coa) tampil from mastercoa ";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcoa'>";
            IsiCombo($sql,$idcoa,' Pilih CoA');
            echo "</select>";
          echo "</div>";
        echo "
        </div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Curr</th>
          <th>Kode Bank</th>
          <th>Deskripsi Bank</th>
          <th>No. Rekening</th>
          <th>Nama Rekening</th>
          <th>ID CoA</th>
          <th></th>
          <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterbank ORDER BY id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "
					<tr>
						<td>$no</td>
						<td>$data[curr]</td>
						<td>$data[kode_bank]</td>
						<td>$data[nama_bank]</td>
						<td>$data[no_rek]</td>
						<td>$data[nama_rek]</td>
            <td>$data[id_coa]</td>";
          echo "<td><a href='index.php?mod=$mod&id=$data[id]' $tt_ubah </a></td>";
				  echo "<td><a href='d_bank.php?mod=$mod&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>