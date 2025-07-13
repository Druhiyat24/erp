<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("generate_kode","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_sub_group = $_GET['id']; } else {$id_sub_group = "";}
$titlenya="Master Sub_group";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_sub_group=="")
{ $sub_group = "";
  $sub_group_desc = "";
  $id_prev = "";
}
else
{ $query = mysql_query("SELECT * FROM mastersubgroup where id='$id_sub_group'");
  $data = mysql_fetch_array($query);
  $sub_group = $data['kode_sub_group'];
  $sub_group_desc = $data['nama_sub_group'];
  $id_prev = $data['id_group'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cboid.value;
      var sub_group = document.form.txtsub_group.value;
      var sub_group_desc = document.form.txtsub_group_desc.value;
      if (id_group == '') { alert('ID Group Tidak Boleh Kosong'); document.form.txtsub_group.focus();valid = false;}
      else if (sub_group == '') { alert('Sub Group Tidak Boleh Kosong'); document.form.txtsub_group.focus();valid = false;}
      else if (sub_group_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtsub_group_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_subgroup.php?mod=$mod&id=$id_sub_group' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Id Group *</label>
            <select class='form-control select2' style='width: 100%;' id='cboid' name='cboid'>";
            IsiCombo('select id isi,nama_group tampil from mastergroup'
              ,$id_prev,'Pilih Id Group');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Kode Sub Group *</label>
            <input type='text' class='form-control' name='txtsub_group' 
            placeholder='Masukkan Kode Sub Group' value='$sub_group'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Sub Group</label>
            <input type='text' class='form-control' name='txtsub_group_desc' 
            placeholder='Masukkan Deskripsi Sub Group' value='$sub_group_desc'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>ID CoA (Debet)</label>";
            $sql = "select id_coa isi,concat(id_coa,'|',nm_coa) tampil from mastercoa ";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcoad'>";
            IsiCombo($sql,$idcoad,' Pilih CoA');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>ID CoA (Credit)</label>";
            $sql = "select id_coa isi,concat(id_coa,'|',nm_coa) tampil from mastercoa ";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcoak'>";
            IsiCombo($sql,$idcoak,' Pilih CoA');
            echo "</select>";
          echo "</div>";
        echo "</div>";
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
          <th>Nama Group</th>
          <th>Kode Sub Group</th>
          <th>Deskripsi Sub Group</th>
          <th>CoA Debet</th>
          <th>CoA Credit</th>
          <th></th>
          <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT s.nama_group,a.* FROM 
          mastersubgroup a inner join mastergroup s on a.id_group=s.id 
          ORDER BY a.id DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[nama_group]</td>";
          echo "<td>$data[kode_sub_group]</td>";
          echo "<td>$data[nama_sub_group]</td>";
          echo "<td>$data[id_coa_d]</td>";
          echo "<td>$data[id_coa_k]</td>";
          echo "<td><a href='../master/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a></td>";
				  echo "<td><a  href='d_subgrp.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>