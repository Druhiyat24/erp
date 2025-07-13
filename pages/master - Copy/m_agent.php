<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup($fld,"userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI


$nm_company = flookup("company","mastercompany","company!=''");
if (isset($_GET['id'])) {$id = $_GET['id']; } else {$id = "";}
$titlenya="Master Agent";
$mode="";
$mod=$_GET['mod'];
$status_aktif = "checked";


# COPAS EDIT
if ($id=="")
{ 
  $id_buyer = "";
  $id_agent = "";
  $aktif    = "";
  $status_aktif = 'checked';
}
else
{ $query = mysql_query("SELECT * FROM master_agent where id = '$id'");
  $data = mysql_fetch_array($query);
  $id_buyer = $data['id_buyer'];
  $id_agent = $data['id_agent'];
  $aktif    = $data['aktif'];  
}

if ($aktif !='')
{
  $status_aktif = 'checked';
}  
else
{  
  $status_aktif = '';
}


# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='simpan_agent.php?mod=$mod&id=$id' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Nama Buyer *</label>
            <select class='form-control select2' style='color: 100%;' id='cbobuyer' name='cbobuyer' required>";
            IsiCombo("select id_supplier isi, supplier tampil from mastersupplier where tipe_buyer = 'Y' order by supplier asc"
              ,$id_buyer,'Pilih Buyer');
            echo "
            </select>
          </div>
          <div class='form-group'>
            <label>Nama Agent *</label>
            <select class='form-control select2' style='color: 100%;' id='cboagent' name='cboagent' required>";
            IsiCombo("select id_supplier isi, supplier tampil from mastersupplier where tipe_agent = 'Y' order by supplier asc"
              ,$id_agent,'Pilih Agent');
            echo "
            </select>
          </div>
          <div class='form-group'>";
          if ($id =="")
          {
           $status_aktif = 'checked'; 
          }
echo "<input type='checkbox' style='width:20px;height:20px;' name='aktif' value='$aktif' $status_aktif> AKTIF</input>";
            echo "
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
    <table id="magent" class="display responsive" style="width:100%">
      <thead>
      <tr>
		      <!-- <th>No</th> -->
          <th>ID</th>
          <th>Buyer</th>
          <th>Agent</th>
          <th>Aktif</th>
          <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/Masteragent.js"></script>