<?PHP
include ("conn.php");
include ("fungsi.php");

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['id'])) {$id_supplier = $_GET['id']; } else {$id_supplier = "";}
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include ("ko.php"); }
else
{ include ("id.php"); }

if ($mode=="Supplier") 
{ $titlenya="Supplier"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='S'";
}
else if ($mode=="Customer") 
{ $titlenya="Customer"; 
  $tipe_sup=$titlenya;
  $filternya="tipe_sup='C'";
}
$title="Master ".$titlenya;
# COPAS EDIT
if ($id_supplier=="")
{ $Supplier = "";
  $Attn = "";
  $Phone = "";
  $Fax = "";
  $Email = "";
  $area = "";
  $alamat = "";
  $alamat2 = "";
  $npwp = "";
  $status_kb = "";
  $country = "";
}
else
{ $query = mysql_query("SELECT * FROM mastersupplier where id_supplier='$id_supplier' ORDER BY id_supplier ASC");
  $data = mysql_fetch_array($query);
  $Supplier = $data['Supplier'];
  $Attn = $data['Attn'];
  $Phone = $data['Phone'];
  $Fax = $data['Fax'];
  $Email = $data['Email'];
  $area_ori = $data['area'];
  if ($area_ori=="I") 
    { $area="Import/Export"; }
  else if ($area_ori=="L") 
    { $area="Lokal"; }
  else if ($area_ori=="F") 
    { $area="Factory"; }
  $alamat = $data['alamat'];
  $alamat2 = $data['alamat2'];
  $npwp = $data['npwp'];
  $status_kb = $data['status_kb'];
  $country = $data['country'];
}
# END COPAS EDIT
# COPAS VALIDASI
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
      echo "var Supplier = document.form.txtSupplier.value;";
      echo "var tipe_sup = document.form.txttipe_sup.value;";
      echo "var area = document.form.txtarea.value;";

      echo "if (Supplier == '') { alert('Supplier tidak boleh kosong'); document.form.txtSupplier.focus();valid = false;}";
      echo "else if (tipe_sup == '') { alert('Tipe tidak boleh kosong'); document.form.txttipe_sup.focus();valid = false;}";
      echo "else if (area == '') { alert('Area tidak boleh kosong'); document.form.txtarea.focus();valid = false;}";
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
          echo "<form method='post' name='form' action='save_data.php?mode=$mode&id=$id_supplier' onsubmit='return validasi()'>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$titlenya *</label>";
            echo "<input type='text' class='form-control' name='txtSupplier' placeholder='Masukkan Supplier' value='$Supplier'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Nama Kontak</label>";
            echo "<input type='text' class='form-control' name='txtAttn' placeholder='Masukkan Nama Kontak' value='$Attn'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Telp.</label>";
            echo "<input type='text' class='form-control' name='txtPhone' placeholder='Masukkan Telp.' value='$Phone'>";
          echo "</div>";
          echo "</div>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Fax.</label>";
            echo "<input type='text' class='form-control' name='txtFax' placeholder='Masukkan Fax.' value='$Fax'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>E-Mail</label>";
            echo "<input type='text' class='form-control' name='txtEmail' placeholder='Masukkan E-Mail' value='$Email'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Area *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                  kode_pilihan='Area' order by nama_pilihan";
            echo "<select class='form-control select2' style='width: 100%;' name='txtarea'>";
            IsiCombo($sql,$area,'Pilih Area');
            echo "</select>";
          echo "</div>";
          echo "</div>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Alamat</label>";
            echo "<input type='text' class='form-control' name='txtalamat' placeholder='Masukkan Alamat' value='$alamat'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Alamat 2</label>";
            echo "<input type='text' class='form-control' name='txtalamat2' placeholder='Masukkan Alamat 2' value='$alamat2'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>NPWP</label>";
            echo "<input type='text' class='form-control' name='txtnpwp' placeholder='Masukkan NPWP' value='$npwp'>";
          echo "</div>";
          echo "</div>";
          echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Negara</label>";
            echo "<input type='text' class='form-control' name='txtcountry' placeholder='Masukkan Negara' value='$country'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tipe *</label>";
            echo "<input type='text' class='form-control' name='txttipe_sup' placeholder='Masukkan Tipe' value='$tipe_sup' readonly>";
          echo "</div>";
          echo "<div class='box-footer'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
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
                <th>No</th>
                <th>$titlenya</th>
                <th>Alamat</th>
                <th>Area</th>
                <th>NPWP</th>
                <th>Ubah</th>
              </tr>
            </thead>";

            echo "<tbody>";
              if ($mode=="Supplier") { $cri="tipe_sup='S'"; }
              else if ($mode=="Customer") { $cri="tipe_sup='C'"; }

              $query = mysql_query("SELECT * FROM mastersupplier where $cri order by id_supplier desc limit 500");
              $no = 1; 
              while($data = mysql_fetch_array($query))
              { echo "<tr>";
                echo "<td>$no</td>"; 
                echo "<td>$data[Supplier]</td>"; 
                echo "<td>$data[alamat]</td>"; 
                echo "<td>$data[area]</td>";
                echo "<td>$data[npwp]</td>"; 
                echo "<td><a href='master_supcust.php?mode=$mode&id=$data[Id_Supplier]'>Ubah</a></td>";
                echo "</tr>";
                $no++; // menambah nilai nomor urut
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
