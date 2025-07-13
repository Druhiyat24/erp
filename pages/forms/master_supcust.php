<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode = $_GET['mode'];
$mod = $_GET['mod'];

$auto_coa_supp=flookup("auto_coa_supp","mastercompany","company!=''");

# CEK HAK AKSES KEMBALI
if ($mode=="Supplier") 
{ $fld="mnuMasterSupplier"; } 
else if ($mode=="Gudang") 
{ $fld="master_whs"; } 
else 
{ $fld="master_customer"; }
$akses = flookup($fld,"userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

if (isset($_GET['id'])) {$id_supplier = $_GET['id']; } else {$id_supplier = "";}

if ($mode=="Supplier") 
{ $titlenya="Supplier"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='S'";
  $link="href='xlsdatasup.php'";  
}
else if ($mode=="Gudang") 
{ $titlenya="Gudang"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='G'";
}
else if ($mode=="Customer") 
{ $titlenya="Customer"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='C'";
  $link="href='xlsdatacus.php'";
}


# COPAS EDIT
if ($id_supplier=="")
{ $supplier_code = "";
  $terms_of_pay = "";
  $idcoa="";
  $pkp = "";
  $moq_lead_time = "";
  $lead_time = "";
  $moq = "";
  $product_name = "";
  $group_name = "";
  $zip_code = "";
  $short_name = "";
  $Supplier = "";
  $Attn = "";
  $Attn2 = "";
  $Attn3 = "";
  $Attn4 = "";
  $Phone = "";
  $Fax = "";
  $Email = "";
  $area = "";
  $ven_cat = "";
  $alamat = "";
  $alamat2 = "";
  $npwp = "";
  $status_kb = "";
  $country = "";
}
else
{ $query = mysql_query("SELECT * FROM mastersupplier where id_supplier='$id_supplier' ORDER BY id_supplier ASC");
  $data = mysql_fetch_array($query);
  $supplier_code = $data['supplier_code'];
  $terms_of_pay = $data['id_terms'];
  $idcoa=$data['id_coa'];
  $pkp = $data['pkp'];
  $moq_lead_time = $data['moq_lead_time'];
  $lead_time = $data['lead_time'];
  $moq = $data['moq'];
  $product_name = $data['product_name'];
  $group_name = $data['group_name'];
  $zip_code = $data['zip_code'];
  $short_name = $data['short_name'];
  $Supplier = $data['Supplier'];
  $Attn = $data['Attn'];
  $Attn2 = $data['Attn2'];
  $Attn3 = $data['Attn3'];
  $Attn4 = $data['Attn4'];
  $Phone = $data['Phone'];
  $Fax = $data['Fax'];
  $Email = $data['Email'];
  $area_ori = $data['area'];
  $ven_cat = $data['vendor_cat'];
  if ($area_ori=="I") 
  { $area="Import/Export"; }
  else if ($area_ori=="L") 
  { $area="Lokal"; }
  else if ($area_ori=="F") 
  { $area="Factory"; }
  $j_fas = $data['jenis_fasilitas'];
  $alamat = $data['alamat'];
  $alamat2 = $data['alamat2'];
  $npwp = $data['npwp'];
  $status_kb = $data['status_kb'];
  $country = $data['country'];
  $tipe_buyer = $data['tipe_buyer'];
  $tipe_agent = $data['tipe_agent'];
  

  $tipe_buyer = $data['tipe_buyer'];
  if ($tipe_buyer!="")
  {
    $status_tipe_buyer ="checked"; 
  }
  else
  {
    $status_tipe_buyer="";
  }
    $tipe_agent = $data['tipe_agent'];
  if ($tipe_agent!="")
  {
    $status_tipe_agent ="checked"; 
  }
  else
  {
    $status_tipe_agent="";
  }



}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var Supplier = document.form.txtSupplier.value;
      var supcode = document.form.txtsupplier_code.value;
      var supnpwp = document.form.txtnpwp.value;
      var vencat = document.form.txtvencat.value;
      var tipe_sup = document.form.txttipe_sup.value;
      var area = document.form.txtarea.value;
      var jenis_fas = document.form.txtfas.value;

      if (supcode == '') { document.form.txtsupplier_code.focus();swal({ title: 'Kode $mode Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (Supplier == '') { document.form.txtSupplier.focus();swal({ title: '$mode Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (supnpwp == '') { document.form.txtnpwp.focus();swal({ title: 'NPWP Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (vencat == '') { swal({ title: 'Kategori $mode Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (tipe_sup == '') { document.form.txttipe_sup.focus();swal({ title: 'Tipe Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (area == '') { swal({ title: 'Area Tidak Boleh Kosong', $img_alert });valid = false;}
      else if (area == 'Lokal' && jenis_fas == '' ) { swal({ title: 'Jenis Fasilitas Tidak Boleh Kosong', $img_alert });valid = false;}
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
      echo "<form method='post' name='form' action='../forms/save_data.php?mod=$mod&mode=$mode&id=$id_supplier' onsubmit='return validasi()'>";
        ?>
        <div class='col-md-12'>
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Basic</a></li>
              <li><a href="#tab_2" data-toggle="tab">Kontak</a></li>
            </ul>
          </div>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <?php 
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>Kode $titlenya</label>";
                  echo "<input type='text' class='form-control' name='txtsupplier_code' maxlength='10' placeholder='Masukkan Kode $titlenya' value='$supplier_code'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Nama Alias</label>";
                  echo "<input type='text' class='form-control' name='txtshort_name' placeholder='Masukkan Nama Alias' value='$short_name'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$titlenya *</label>";
                  echo "<input type='text' class='form-control' name='txtSupplier' placeholder='$cmas $titlenya' value='$Supplier'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Nama Group</label>";
                  echo "<input type='text' class='form-control' name='txtgroup_name' placeholder='Masukkan Nama Group' value='$group_name'>";
                echo "</div>";

              echo "</div>";
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>Nama Product</label>";
                  echo "<input type='text' class='form-control' name='txtproduct_name' placeholder='Masukkan Nama Product' value='$product_name'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>MOQ</label>";
                  echo "<input type='text' class='form-control' name='txtmoq' placeholder='Masukkan MOQ' value='$moq'>";
                echo "</div>";
                echo "
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Lead Time</label>
                    <input type='text' class='form-control' name='txtlead_time' 
                      placeholder='Masukkan Lead Time' value='$lead_time'>
                  </div>
                </div>";
                echo "
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>MOQ Lead Time</label>
                    <input type='text' class='form-control' name='txtmoq_lead_time' 
                      placeholder='Masukkan MOQ Lead Time' value='$moq_lead_time'>
                  </div>
                </div>";
                echo "
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Area *</label>";
                      $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                        kode_pilihan='Area' order by nama_pilihan";
                    echo "
                    <select class='form-control select2' style='width: 100%;' name='txtarea'>";
                    IsiCombo($sql,$area,$cpil.' Area');
                    echo "
                    </select>
                  </div>
                </div>";
                echo "
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Kategori</label>";
                      $sql = "select left(nama_pilihan,2) isi,nama_pilihan tampil from masterpilihan where 
                        kode_pilihan='Vendor_Cat' order by nama_pilihan";
                    echo "
                    <select class='form-control select2' style='width: 100%;' name='txtvencat'>";
                    IsiCombo($sql,$ven_cat,$cpil.' Kategori');
                    echo "
                    </select>
                  </div>
                </div>";
              echo "</div>";
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>Fasilitas *</label>";
                  $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                        kode_pilihan='J_FAS' order by nama_pilihan";
                  echo "<select class='form-control select2' style='width: 100%;' name='txtfas'>";
                  IsiCombo($sql,$j_fas,$cpil.' Fasilitas');
                  echo "</select>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c28</label>";
                  echo "<input type='text' class='form-control' name='txtalamat' placeholder='$cmas $c28' value='$alamat'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c28 2</label>";
                  echo "<input type='text' class='form-control' name='txtalamat2' placeholder='$cmas $c28 2' value='$alamat2'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Kode Pos</label>";
                  echo "<input type='text' class='form-control' name='txtzip_code' placeholder='Masukkan Kode Pos' value='$zip_code'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c30</label>";
                  echo "<input type='text' class='form-control' name='txtcountry' placeholder='$cmas $c30' value='$country'>";
                echo "</div>";
              echo "</div>";
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>$c29</label>";
                  echo "<input type='text' class='form-control' name='txtnpwp' placeholder='$cmas $c29' value='$npwp'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Status PKP</label>";
                  $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                        kode_pilihan='PKP' order by nama_pilihan";
                  echo "<select class='form-control select2' style='width: 100%;' name='txtpkp'>";
                  IsiCombo($sql,$pkp,' Pilih Status PKP');
                  echo "</select>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Term Of Payment</label>";
                  $sql = "select id isi,concat(kode_pterms,'|',nama_pterms) tampil from masterpterms ";
                  echo "<select class='form-control select2' style='width: 100%;' name='txtterms_of_pay'>";
                  IsiCombo($sql,$terms_of_pay,' Pilih Terms');
                  echo "</select>";
                  #echo "<input type='text' class='form-control' name='txtterms_of_pay' placeholder='Masukkan Term Of Payment' value='$terms_of_pay'>";
                echo "</div>";

                if ($mode=="Customer")
{


                echo "<div class='form-group'>";
                echo "<input type='checkbox' id='tipe_buyer' name='tipe_buyer' value='$tipe_buyer' $status_tipe_buyer></input>";
                echo "<label> Buyer</label>";
                echo "<div></div>";
                echo "<input type='checkbox' id = 'tipe_agent' name='tipe_agent' value='$tipe_agent' $status_tipe_agent ></input>";
                echo "<label> Agent</label>";
                echo "</div>";                
 }               

                if($auto_coa_supp=="N")
                { echo "<div class='form-group'>";
                    echo "<label>ID CoA</label>";
                    $sql = "select id_coa isi,concat(id_coa,'|',nm_coa) tampil from mastercoa ";
                    echo "<select class='form-control select2' style='width: 100%;' name='txtcoa'>";
                    IsiCombo($sql,$idcoa,' Pilih CoA');
                    echo "</select>";
                  echo "</div>";
                }
                echo "<input type='hidden' class='form-control' name='txttipe_sup' placeholder='Masukkan Tipe' value='$tipe_sup' readonly>";
                echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
              echo "</div>";
              ?>
            </div>
            <div class="tab-pane" id="tab_2">
              <?php 
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>$c26</label>";
                  echo "<input type='text' class='form-control' name='txtAttn' placeholder='$cmas $c26' value='$Attn'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c26</label>";
                  echo "<input type='text' class='form-control' name='txtAttn2' placeholder='Masukkan Nama Kontak' value='$Attn2'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c26</label>";
                  echo "<input type='text' class='form-control' name='txtAttn3' placeholder='Masukkan Nama Kontak' value='$Attn3'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>$c26</label>";
                  echo "<input type='text' class='form-control' name='txtAttn4' placeholder='Masukkan Nama Kontak' value='$Attn4'>";
                echo "</div>";
              echo "</div>";
              echo "<div class='col-md-3'>";
                echo "<div class='form-group'>";
                  echo "<label>$c27</label>";
                  echo "<input type='text' class='form-control' name='txtPhone' placeholder='$cmas $c27' value='$Phone'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>Fax.</label>";
                  echo "<input type='text' class='form-control' name='txtFax' placeholder='$cmas Fax.' value='$Fax'>";
                echo "</div>";
                echo "<div class='form-group'>";
                  echo "<label>E-Mail</label>";
                  echo "<input type='text' class='form-control' name='txtEmail' placeholder='$cmas E-Mail' value='$Email'>";
                echo "</div>";
              echo "</div>";
              ?>
            </div>
          </div>
        </div>
      <?php
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
      </a> <a <?PHP echo $link; ?> class='btn btn-success btn-s'>
      <i class='fa fa-print'></i> Save To Excel     
    </a>
  <div class="box-body">
  	<?php 
  	if ($mode=="Supplier")
  	{ include "table_sup.php"; }
  	else
  	{ include "table_cus.php"; }
  	?>  
  </div>
</div>