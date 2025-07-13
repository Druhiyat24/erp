<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("master_route","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_group = $_GET['id']; } else {$id_group = "";}
$titlenya="Master Route";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_group=="")
{ $route = "";
  $route_desc = "";
  $lead_time = 0;
}
else
{ $query = mysql_query("SELECT * FROM masterroute where id='$id_group'");
  $data = mysql_fetch_array($query);
  $route = $data['kode_route'];
  $route_desc = $data['nama_route'];
  $lead_time = $data['lead_time'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var group = document.form.txtgroup.value;
      var group_desc = document.form.txtgroup_desc.value;
      if (group == '') { alert('Group Tidak Boleh Kosong'); document.form.txtgroup.focus();valid = false;}
      else if (group_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtgroup_desc.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_route.php?mod=$mod&id=$id_group' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Kode Route *</label>
            <input type='text' class='form-control' name='txtgroup' 
            placeholder='Masukkan Kode Route' value='$route'>
          </div>
          <div class='form-group'>
            <label>Deskripsi Route</label>
            <input type='text' class='form-control' name='txtgroup_desc' 
            placeholder='Masukkan Deskripsi Route' value='$route_desc'>
          </div>
          <div class='form-group'>
            <label>Lead Time</label>
            <input type='text' class='form-control' name='txtlead_time' 
            placeholder='Masukkan Lead Time' value='$lead_time'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
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
          <th>Kode Route</th>
          <th>Deskripsi Route</th>
          <th>Lead Time</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM masterroute ORDER BY id DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[kode_route]</td>";
          echo "<td>$data[nama_route]</td>";
          echo "<td>$data[lead_time]</td>";
          echo "<td><a $cl_ubah href='../shp/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_rut.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>