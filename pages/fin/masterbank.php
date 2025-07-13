<?PHP
include ("conn.php");
include ("fungsi.php");

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include ("ko.php"); }
else
{ include ("id.php"); }

$title=$c_mbank;

if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

# COPAS EDIT
if ($id_item=="")
{ $curr = "";
  $nama_bank = "";
  $no_rek = "";
  $nama_rek = "";
}
else
{ $query = mysql_query("SELECT * FROM acc_masterbank where id_bank='$id_item' ORDER BY id_bank ASC");
  $data = mysql_fetch_array($query);
  $curr = $data['curr'];
  $nama_bank = $data['nama_bank'];
  $no_rek = $data['no_rek'];
  $nama_rek = $data['nama_rek'];
}
# END COPAS EDIT
# COPAS VALIDASI
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";

echo "var curr = document.form.txtcurr.value;";
echo "var nama_bank = document.form.txtnama_bank.value;";
echo "var no_rek = document.form.txtno_rek.value;";
echo "var nama_rek = document.form.txtnama_rek.value;";

echo "if (curr == '') { alert('$c_curr $c_tbk'); document.form.txtcurr.focus();valid = false;}";
echo "else if (nama_bank == '') { alert('Nama Bank tidak boleh kosong'); document.form.txtnama_bank.focus();valid = false;}";
echo "else if (no_rek == '') { alert('No. Rekening tidak boleh kosong'); document.form.txtno_rek.focus();valid = false;}";
echo "else if (nama_rek == '') { alert('Nama Beneficiary tidak boleh kosong'); document.form.txtnama_rek.focus();valid = false;}";
echo "else valid = true;";
echo "return valid;";
echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?PHP echo "<title>$title</title>"; ?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS sidedar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-purple sidebar-collapse sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <header class="main-header">
    <?PHP include ("header.php"); ?>  
  </header>
  <?PHP include ("sidebar.php"); ?>

  <!-- =============================================== -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?PHP echo "<h1>$title</h1>"; ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <?PHP
          # COPAS ADD 3 HAPUS /DIV TERAKHIR
          echo "<div class='box-body'>";
          echo "<div class='row'>";
          echo "<form method='post' name='form' action='save_bank.php?id=$id_item' onsubmit='return validasi()'>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c_curr *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
              where kode_pilihan='CURR' order by nama_pilihan";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcurr'>";
            IsiCombo($sql,$curr,$c_pil.' '.$c_curr);
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c_nmbank *</label>";
            echo "<input type='text' class='form-control' name='txtnama_bank' placeholder='$c_mskan $c_nmbank' value='$nama_bank'>";
          echo "</div>";
          echo "</div>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c_norek *</label>";
            echo "<input type='text' class='form-control' name='txtno_rek' placeholder='$c_mskan $c_norek' value='$no_rek'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c_nmacc *</label>";
            echo "<input type='text' class='form-control' name='txtnama_rek' placeholder='$c_mskan $c_nmacc' value='$nama_rek'>";
          echo "</div>";
          echo "<div class='box-footer'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>$c_save</button>";
          echo "</div>";
          echo "</form>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          # END COPAS ADD 3 HAPUS /DIV TERAKHIR
        ?>  
      </div>
      <!-- /.box -->
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <?PHP 
          echo "<h3 class='box-title'>List $title</h3><br>";
          echo "<h3 class='box-title'>.</h3><br>";
          echo "<table id='example1' class='table table-bordered table-striped'>";
            echo "
            <thead>
              <tr>
                <th>$c_curr</th>
                <th>$c_nmbank</th>
                <th>$c_norek</th>
                <th>$c_nmacc</th>
                <th>Ubah</th>
                <th>Hapus</th>
              </tr>
            </thead>";

            echo "<tbody>";
              $query = mysql_query("SELECT * from acc_masterbank");
              while($data = mysql_fetch_array($query))
              {   echo "
                  <tr>
                    <td>$data[curr]</td>
                    <td>$data[nama_bank]</td>
                    <td>$data[no_rek]</td>
                    <td>$data[nama_rek]</td>
                    <td><a href='masterbank.php?id=$data[id_bank]'>Ubah</a></td>
                    <td><a href='del_data.php?mode=mbank&id=$data[id_bank]'";?> 
                      onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo "Hapus</a></td>";
                  echo "</tr>";
              }
            echo "</tbody>";
          echo "</table>";
          ?>
        </div>
      </div>
      <!-- /.box -->    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script>
  //Date picker
  $('#dtp1').datepicker
  ({  format: "dd M yyyy",
      autoclose: true
  });

  $('#dtp2').datepicker
  ({  format: "dd M yyyy",
      autoclose: true
  });

  $('#dtp3').datepicker
  ({  format: "dd M yyyy",
      autoclose: true
  });

  $('#dtp4').datepicker
  ({  format: "dd M yyyy",
      autoclose: true
  });

  $(function () 
  {
    //Initialize Select2 Elements
    $(".select2").select2();

  });

  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });

</script>
</body>
</html>
