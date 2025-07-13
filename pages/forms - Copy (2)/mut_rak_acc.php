<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
// $mod=$_GET['mod'];
// $akses = flookup("mut_rak","userpassword","username='$user'");
// if ($akses=="0") 
// { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { 
    var raknya = document.form.cbomutrak.value;
    var rakold = document.form.cborak.value;
    var rak_kos = 0;
    var chkclass = document.getElementsByClassName('chkclass');
    var dipilih = 0;
    for (var i = 0; i < chkclass.length; i++) 
    { if (chkclass[i].checked)
      { 
        dipilih = dipilih + 1;
        break;
      }
    }
    if(rakold == '') { swal({ title: 'Rak # Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if(raknya == '') { swal({ title: 'Mutasi Rak # Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if(rakold == raknya) { swal({ title: 'Rak # Tidak Boleh Sama', <?php echo $img_alert; ?> });valid = false;}
    else if(Number(dipilih) == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  };


  function getDetail()
  { 
    var id_item = $('#id_item').val();
    var id_rak_loc = $('#nama_rak').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_mut_acc.php?modeajax=viewdetail',
        data: {
          id_item: id_item,
          id_rak_loc: id_rak_loc
        },
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
    $(".select2").select2();
    $('#data_mut').dataTable();
  };
</script>
 <script type="text/javascript">
  function checkAll(ele) {
       var checkboxes = document.getElementsByTagName('input');
       if (ele.checked) {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox' ) {
                   checkboxes[i].checked = true;
               }
           }
       } else {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox') {
                   checkboxes[i].checked = false;
               }
           }
       }
   }
 </script>
<script type='text/javascript'>
    function getitem() {
      var id_rak_loc = $('#nama_rak').val();
      var html = $.ajax({
        type: "POST",
        url: 'ajax_mut_acc.php?modeajax=view_item',
        data: {
          id_rak_loc: id_rak_loc
        },
        async: false
      }).responseText;
      if (html) {
        $("#id_item").html(html);
      }
    };
  </script>

<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="mrL")
{
?>
  <!-- Jika nantinya ada list -->
<?php } else { ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='save_rak_acc.php?mod=<?=$mod?>' >
    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          
           <div class='col-md-3'>
            <div class='form-group'>
              <label>Rak #</label>
              <select class="form-control select2" name="nama_rak" id="nama_rak" data-dropup-auto="false" data-live-search="true" onchange="getitem();getDetail()">
                <option value="" disabled selected="true">Select Rak</option>                                            
                <?php
                $nama_buyer ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_buyer = isset($_POST['nama_buyer']) ? $_POST['nama_buyer']: null;
                }                 
                $sql = mysql_query("select id, CONCAT(kode_rak,' ',nama_rak) nama_rak from master_rak where nama_rak like '%ACCESSORIES%' and aktif = 'Y'");
                while ($row = mysql_fetch_array($sql)) {
                    $data = $row['nama_rak'];
                     $data2 = $row['id'];
                    if($row['nama_rak'] == $_POST['nama_rak']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Item #</label>
              <select class="form-control select2" name="id_item" id="id_item" data-dropup-auto="false" data-live-search="true" onchange="getDetail()">
                <option value="" disabled selected="true">Select Item</option>         
                </select>
            </div>
          </div>
          <div class='col-md-12'>
            <div id='detail_item'>
            </div>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php
}
# END COPAS ADD
?>