<?PHP
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include ("ko.php"); }
else
{ include ("id.php"); }

$title="Kasbon";


// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_L_Neraca","userpassword","username='$user'");


# COPAS EDIT
# END COPAS EDIT

# COPAS VALIDASI
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getBank(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_bank',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cbobank").html(html);
      }
  }
</script>
<div class="box">
  <?PHP
    # COPAS ADD 3 HAPUS /DIV TERAKHIR
    # END COPAS ADD 3 HAPUS /DIV TERAKHIR
  ?>  
</div>
<!-- /.box -->
<!-- Default box -->
<div class="box">
  <div class="box-header with-border">
    <?PHP 
    echo "<h3 class='box-title'>$c_list $title</h3><br>";
    echo "<h3 class='box-title'>.</h3><br>";
    echo "<table id='example1' class='table table-bordered table-striped'>";
      echo "
      <thead>
        <tr>
          <th>$c_ninv</th>
          <th>$c_dinv</th>
        </tr>
      </thead>";

      echo "<tbody>";
        
      echo "</tbody>";
    echo "</table>";
    ?>
  </div>
</div>