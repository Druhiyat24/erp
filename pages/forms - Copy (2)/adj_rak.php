<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("mut_rak","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
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
  function getDetail(raknya)
  { 
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_adj_rak.php?modeajax=view_list_rak',
        data: "raknya=" +raknya,
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
    $(".select2").select2();
    $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollY: "300px",
          scrollX: true,
          paging: false,
          scrollCollapse: true,
          sorting: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
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

<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="mrL")
{
?>
  <!-- Jika nantinya ada list -->
<?php } else { ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='s_adj_rak.php?mod=<?=$mod?>' onsubmit='return validasi()'>
    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Rak #</label>
              <?php
              $sql="select a.id_rak_loc isi,concat(a.id_rak_loc,' ',s.kode_rak,' ',s.nama_rak) tampil 
                from bpb_roll a inner join master_rak s 
                on a.id_rak_loc=s.id where a.roll_qty!=ifnull(a.roll_qty_used,0) group by a.id_rak_loc";
              echo "<select class='form-control select2' style='width: 100%;' name='cborak' 
                onchange='getDetail(this.value)'>";
              IsiCombo($sql,'',$cpil.' Rak #');
              echo "</select>";
              ?>
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