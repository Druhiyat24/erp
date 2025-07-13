<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("user_account","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
$username = "";
$FullName = "";
$Password = "";
$nik = "";
$Groupp = "";
$kode_mkt = "";
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var username = document.form.txtusername.value;
    var FullName = document.form.txtFullName.value;
    var Password = document.form.txtPassword.value;
    var nik = document.form.txtnik.value;
    var Groupp = document.form.txtGroupp.value;
    if (username == '') { document.form.txtusername.focus(); swal({ title: 'User Name Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (FullName == '') { document.form.txtFullName.focus(); swal({ title: 'Full Name Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (Password == '') { document.form.txtPassword.focus(); swal({ title: 'Password Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (nik == '') { document.form.txtnik.focus(); swal({ title: 'NIK Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (Groupp == '') { document.form.txtGroupp.focus(); swal({ title: 'Title Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_setting.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>User Name *</label>
            <input type='text' class='form-control' name='txtusername' placeholder='Masukkan User Name' value='<?php echo $username;?>' >
          </div>        
          <div class='form-group'>
            <label>Full Name *</label>
            <input type='text' class='form-control' name='txtFullName' placeholder='Masukkan Full Name' value='<?php echo $FullName;?>' >
          </div>        
          <div class='form-group'>
            <label>Password *</label>
            <input type='password' class='form-control' name='txtPassword' placeholder='Masukkan Password' value='<?php echo $Password;?>' >
          </div>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>NIK *</label>
            <input type='text' class='form-control' name='txtnik' placeholder='Masukkan NIK' value='<?php echo $nik;?>' >
          </div>          
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
          <div class='form-group'>
            <label>Group Marketing / Department</label>
            <input type='text' class='form-control' name='txtkode_mkt' placeholder='Masukkan Group Marketing / Department' value='<?php echo $kode_mkt;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List User</h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>User Name</th>
        <th>Full Name</th>
        <th>NIK</th>
        <th>Title</th>
        <th>Group Marketing / Dept</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select * from userpassword";
        $result=mysql_query($sql);
        while($data = mysql_fetch_array($result))
        { if($data['aktif']=="N") {$bgcol=" style='background-color: red; color:yellow;'";} else {$bgcol="";}
          echo "<tr $bgcol>";
            echo "<td>$data[username]</td>";
            echo "<td>$data[FullName]</td>";
            echo "<td>$data[nik]</td>";
            echo "<td>$data[Groupp]</td>";
            echo "<td>$data[kode_mkt]</td>";
            echo "
            <td><a href='../setting/?mod=2&id=$data[username]'
              data-toggle='tooltip' title='Access'>
              <i class='fa fa-key'></i></a>
            </td>";
            if($data['aktif']=="Y")
            { echo "
              <td>
                <a href='../setting/?mod=inactive&id=$data[username]'
                  data-toggle='tooltip' title='Inactive'>
                  <i class='fa fa-ban'></i>
                </a>
              </td>";
            }
            else
            { echo "
              <td>
                <a href='../setting/?mod=active&id=$data[username]'
                  data-toggle='tooltip' title='Active'>
                  <i class='fa fa-check'></i>
                </a>
              </td>";
            }
            echo "
            <td>
              <a href='../setting/?mod=reset&id=$data[username]'
                data-toggle='tooltip' title='Reset Password'>
                <span class='fa-passwd-reset fa-stack'>
                  <i class='fa fa-undo fa-stack-2x'></i>
                  <i class='fa fa-lock fa-stack-1x'></i>
                </span>
              </a>
            </td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>  
  </div>
</div>