<?PHP
include ("../../include/conn.php");
include ("../forms/fungsi.php");

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include "../forms/ko.php"; }
else
{ include "../forms/id.php"; }

if (isset($_GET['frexc'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }

$user=$_SESSION['username'];
$mode = $_GET['mode'];

if ($excel=="Y")
{ $from=date('Y-m-d',strtotime($_GET['frexc']));
  $to=date('Y-m-d',strtotime($_GET['toexc']));
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
}

if ($mode=="AR")
{ $tbl="acc_rec"; $title="AR Akan Jatuh Tempo"; $cap_supcus="Customer"; }
else if ($mode=="AR2")
{ $tbl="acc_rec"; $title="AR Telah Lewat Jatuh Tempo"; $cap_supcus="Customer"; }
else if ($mode=="AR3")
{ $tbl="acc_rec"; $title="Laporan Account Receivable"; $cap_supcus="Customer"; }
else if ($mode=="AP")
{ $tbl="acc_pay"; $title="AP Akan Jatuh Tempo"; $cap_supcus="Supplier"; }
else if ($mode=="AP2")
{ $tbl="acc_pay"; $title="AP Telah Lewat Jatuh Tempo"; $cap_supcus="Supplier"; }
else if ($mode=="AP3")
{ $tbl="acc_pay"; $title="Laporan Account Payable"; $cap_supcus="Supplier"; }

$nm_company=flookup("company","mastercompany","company!=''");

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?PHP echo "<title>$title</title>"; ?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?PHP
  if ($excel=="N")
  { echo "
        <link rel='stylesheet' href='../../bootstrap/css/bootstrap.min.css'>
        <link rel='stylesheet' href='../../plugins/select2/select2.min.css'>
        <link rel='stylesheet' href='../../plugins/datepicker/datepicker3.css'>
        <link rel='stylesheet' href='../../plugins/datatables/dataTables.bootstrap.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'>
        <link rel='stylesheet' href='../../dist/css/AdminLTE.min.css'>
        <link rel='stylesheet' href='../../dist/css/skins/_all-skins.min.css'>
    ";
  }
  ?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS sidedar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-purple layout-top-nav fixed">
<!-- Site wrapper -->
<div class="wrapper">
<?php
if ($excel=="N") 
  { echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
  { $nm_company=flookup("company","mastercompany","company!=''");
  }
?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?php
      if ($excel=="N")
      { echo "<h1>$nm_company</h1>"; }
      else
      { echo "$nm_company<br>"; }
      echo "Laporan ".$title;
      if ($from!="") { echo "<br>Periode Dari ".fd_view($from)." s/d ".fd_view($to); }
      if ($excel=="N") 
      { echo "<br><a href='detail_trans.php?mode=$mode&frexc=$from&toexc=$to'>Save To Excel</a></br>"; }
      ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <?PHP
        echo "<table class='table table-bordered table-striped'>";
        echo "
        <thead>
          <tr>
            <th>No. Inv</th>
            <th>Tgl. Inv</th>
            <th>No. Faktur</th>
            <th>$cap_supcus</th>
            <th>Mata Uang</th>
            <th>Jumlah</th>
            <th>Tgl. Tanda Terima</th>
            <th>Tgl. Jatuh Tempo</th>
            <th>Jml Hari</th>
            <th>Tgl. Bayar</th>
            <th>Nama Bank</th>
          </tr>
        </thead>";

        echo "<tbody>";
          $today=date('Y-m-d');
          if ($mode=="AR" OR $mode=="AP")
          { $crinya="DATEDIFF(due_date,'$today') between 1 and 10 and pay_date='0000-00-00'"; }
          else if ($mode=="AR2" OR $mode=="AP2")
          { $crinya="DATEDIFF(due_date,'$today') < 0 and pay_date='0000-00-00'"; }
          else 
          { $crinya="inv_date between '$from' and '$to'"; }
          $sql="SELECT a.*,s.supplier,datediff(due_date,'$today') jml_hari
              ,datediff(pay_date,due_date) jml_hari_pay,concat(d.nama_bank,' ',no_rek) rek_bank 
              FROM $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier
              left join masterbank d on a.pay_bank=d.id
              where $crinya";
          #echo $sql;
          $query = mysql_query($sql);
          while($data = mysql_fetch_array($query))
          {   $due_date=fd_view($data['due_date']);
              $inv_date=fd_view($data['inv_date']);
              $tt_date=fd_view($data['tt_date']);
              if ($data['pay_date']!="0000-00-00") { $pay_date=fd_view($data['pay_date']); } else { $pay_date=""; }
              $amt=fn($data['amount'],2);
              if ($pay_date!="")
              { $jml_hari=$data['jml_hari_pay']; }
              else
              { $jml_hari=$data['jml_hari']; }
              echo "<tr>";
                $modenya=substr($mode,0,2);
                if ($pay_date=="")
                { echo "<td><a href='../fin/?mod=2&mode=$modenya&noid=$data[inv_no]'>$data[inv_no]</a></td>"; }
                else
                { echo "<td>$data[inv_no]</td>"; }
              echo "
                <td>$inv_date</td>
                <td>$data[no_faktur]</td>
                <td>$data[supplier]</td>
                <td>$data[curr]</td>
                <td>$amt</td>
                <td>$tt_date</td>
                <td>$due_date</td>";
                if ($pay_date=="")
                { if ($jml_hari<0)
                  { echo "<td style='background-color: red;'>$jml_hari</td>"; }
                  else
                  { echo "<td>$jml_hari</td>"; }
                }
                if ($pay_date!="")
                { if ($jml_hari>0)
                  { echo "<td style='background-color: pink;'>$jml_hari</td>"; }
                  else
                  { echo "<td>$jml_hari</td>"; }
                }
              echo "
                <td>$pay_date</td>
                <td>$data[rek_bank]</td>
              </tr>";
          }
        echo "</tbody>";
      echo "</table>";      
      ?>        
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
