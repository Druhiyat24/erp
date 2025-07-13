<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("m_desc_add_info","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_type = $_GET['id']; } else {$id_type = "";}
$titlenya="Master Desc";
$mode="";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_type=="")
{ $type = "";
  $type_desc = "";
  $id_prev = "";
}
else
{ $query = mysql_query("SELECT * FROM masterdesc where id='$id_type'");
  $data = mysql_fetch_array($query);
  $type = $data['add_info'];
  $type_desc = $data['nama_desc'];
  $id_prev = $data['id_color'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var id_group = document.form.cboid.value;
      var type = document.form.txttype.value;
      if (id_group == '') { alert('ID Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
      else if (type == '') { alert('Additional Info Tidak Boleh Kosong'); document.form.txttype.focus();valid = false;}
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
      echo "<form method='post' name='form' action='s_desc_add.php?mod=$mod&id=$id_type' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Id *</label>
            <select class='form-control select2' style='desc: 100%;' id='cboid' name='cboid'>";
            IsiCombo("select i.id isi,concat(nama_group,' ',
              nama_sub_group,' ',nama_type,' ',
              nama_contents,' ',nama_width,' ',
              nama_length,' ',nama_weight,' ',nama_color) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              inner join masterwidth f on e.id=f.id_contents
              inner join masterlength g on f.id=g.id_width
              inner join masterweight h on g.id=h.id_length
              inner join mastercolor i on h.id=i.id_weight"
              ,$id_prev,'Pilih Id');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Additional Info *</label>
            <input type='text' class='form-control' name='txttype' 
              placeholder='Masukkan Additional Info' value='$type'>
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
      <h3 class="box-title">Data Additional Info</h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="desc:100%">
      <thead>
      <tr>
		      <th>No</th>
          <th>Nama Sub Group</th>
          <th>Kode Desc</th>
          <th>Deskripsi</th>
          <th>Additional Info</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT concat(a.nama_group,' ',
          s.nama_sub_group,' ',d.nama_type,' ',
          e.nama_contents,' ',f.nama_width,' ',g.nama_length,
          ' ',h.nama_weight,' ',i.nama_color) tampil
          ,j.* FROM 
          mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type
          inner join masterwidth f on e.id=f.id_contents 
          inner join masterlength g on f.id=g.id_width
          inner join masterweight h on g.id=h.id_length
          inner join mastercolor i on h.id=i.id_weight
          inner join masterdesc j on i.id=j.id_color 
          where j.add_info!=''
          ORDER BY j.id DESC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
          echo "<td>$data[tampil]</td>";
          echo "<td>$data[kode_desc]</td>";
          echo "<td>$data[nama_desc]</td>";
          echo "<td>$data[add_info]</td>";
          echo "<td><a $cl_ubah href='../marketting/?mod=$mod&mode=$mode&id=$data[id]' $tt_ubah </a>";
				  echo " <a $cl_hapus href='d_desc_add.php?mod=$mod&mode=$mode&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>