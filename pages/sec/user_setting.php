<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("user_account","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$usernya=$_GET['id'];} else {$usernya="";}
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
# END COPAS VALIDASI
# COPAS ADD
$mod=1;
$id=$_GET['id'];
$rsUser=mysql_fetch_array(mysql_query("select * from userpassword where username='$id'"));
$kode_mkt=$rsUser["kode_mkt"];
$Groupp=$rsUser["Groupp"];
$nik=$rsUser["nik"];
?>
<?php 
# END COPAS ADD
?>
<form method='post' name='form' action='s_access.php?mod=<?php echo $mod; ?>&id=<?php echo $id; ?>' onsubmit='return validasi()'>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List Menu</h3>
    </div>
    <div class='col-md-3'>
      <div class='form-group'>
        <label>Title *</label>
        <select class='form-control select2' style='width: 100%;' name='txtGroupp'>
          <?php 
            $sql = "select nama_pilihan isi,nama_pilihan tampil from 
              masterpilihan where kode_pilihan='Jabatan_UA'";
            IsiCombo($sql,$Groupp,'Pilih Title');
          ?>
        </select>
      </div>
    </div>
    <div class='col-md-3'>
      <div class='form-group'>
        <label>NIK *</label>
        <select class='form-control select2' style='width: 100%;' name='txtnik'>
          <?php 
            $sql = "select nik isi,nama tampil from 
              hr_masteremployee where selesai_kerja='0000-00-00' or selesai_kerja is null";
            IsiCombo($sql,$nik,'Pilih NIK');
          ?>
        </select>
      </div>
    </div>
    <div class='col-md-3'>
      <div class='form-group'>
        <label>Group Marketing / Department</label>
        <input type='text' class='form-control' name='txtkode_mkt' placeholder='Masukkan Group Marketing / Department' value='<?php echo $kode_mkt;?>' >
      </div>
    </div>
    <div class="box-body">
      <table id="example1" class="display responsive" style="width:100%">
        <thead>
        <tr>
          <th width="2%">Access</th>
          <th>Nama Menu</th>
        </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql="desc userpassword";
          $result=mysql_query($sql);
          $sql="select * from userpassword where username='$usernya'";
          $rs=mysql_fetch_array(mysql_query($sql));
          while($data = mysql_fetch_array($result))
          { if ($data[0]!="username" AND $data[0]!="FullName"
              AND $data[0]!="Password" AND $data[0]!="Locked"
              AND $data[0]!="Groupp" AND $data[0]!="nik"
              AND $data[0]!="kode_mkt" AND $data[0]!="user_account")
            { echo "<tr>";
                if ($rs[$data[0]]=="1")  
                {$status_checked="checked";}
                else
                {$status_checked="";}
                echo "
                <td>
                  <input type ='hidden' name='chkhide[$data[0]]' value='$data[0]'>
                  <input type ='checkbox' $status_checked name='itemchk[$data[0]]' class='chkclass'>
                </td>";
                $cap_ori=menu_ua($data[0]);
                echo "<td>$cap_ori</td>";
              echo "</tr>";
            }
          }
          ?>
        </tbody>
      </table>
      <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>  
    </div>
  </div>
</form>