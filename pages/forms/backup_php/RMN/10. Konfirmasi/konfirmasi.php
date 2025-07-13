<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];
$logo_company = $data['logo_company'];
if($logo_company=="Z") { $capkonf="Sesuai"; } else { $capkonf="Konfirmasi"; }
if ($cekroll=="Ok") { $status="";} else {$status="disbled"; }
$id_item="";
$mod=$_GET['mod'];

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var bppbno = document.form.cbosj.value;";
    
    echo "if (bppbno == '') { alert('No SJ tidak boleh kosong'); document.form.cbosj.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListKPNo(cri_item)
  {   var modnya = "<?=$mod?>";
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax2_konf.php?modeajax=cari_list_sj',
          data: {cri_item: cri_item, modnya: modnya},
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbosj").html(html);
      }
  }
  function getListData(cri_item2)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2_konf.php?modeajax=view_list_sj',
          data: {cri_item2: cri_item2},
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix2').DataTable
        ({  scrollCollapse: true,
            paging: false,
            fixedColumns:   
            { leftColumns: 1,
              rightColumns: 1
            }
        });
      });
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      // echo "<form method='post' name='form' action='save_data_konf.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
      echo "<form method='post' name='form' action='save_data_konf.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";      
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Tgl Surat Jalan *</label>";
            echo "<input type='text' class='form-control' name='txttglcut' id='datepicker1' autocomplete = 'off'
            placeholder='Masukkan Filter Tgl Surat Jalan' onchange='getListKPNo(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>Nomor Transaksi *</label>";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtsjno' id='cbosj' onchange='getListData(this.value)'>";
          	echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' $status class='btn btn-primary'>$capkonf</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>