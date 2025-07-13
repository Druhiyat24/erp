<?php
  session_start();
  if (isset($_GET['dest']))
  { $excel = "Y";
    header("Content-type: application/octet-stream"); 
    header("Content-Disposition: attachment; filename=stock_opname.xls");//ganti nama sesuai keperluan 
    header("Pragma: no-cache"); 
    header("Expires: 0");
  }
  else
  { $excel = "N"; }
  include '../../include/conn.php';
  include '../forms/fungsi.php';
  if (empty($_SESSION['username'])) { header("location:../../index.php"); }
  if ($_SESSION['bahasa']=="Indonesia")
  { include '../forms/id.php'; }
  else
  { include '../forms/en.php'; }
  if (isset($_GET['mod'])) { $mod=$_GET['mod']; } else { $mod=""; } 
  if (isset($_SESSION['msg'])) { $msg=$_SESSION['msg']; } else { $msg=""; }
  $user = $_SESSION['username']; #mendapatkan data id  dari method get
  $sesi = $_SESSION['sesi'];;
  $rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $dalam_perbaikan=$rs["dalam_perbaikan"];
  $jenis_company=$rs["jenis_company"];
  $logo_erp=$rs['logo_company'];
  $img_alert = "imageUrl: '../../images/error.jpg'";
  $img_alert_ok = "imageUrl: '../../images/success.jpg'";
  
  if ($dalam_perbaikan=="Y")
  { echo "<script>window.location.href='../../maaf';</script>";
    exit;
  }
  $cl_ubah="class='btn btn-primary btn-s'";
  $tt_ubah="data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>";
  $cl_hapus="class='btn btn-danger btn-s'";
  $tt_hapus="data-toggle='tooltip' title='Cancel'";
  $tt_cancel="data-toggle='tooltip' title='Cancel'";
  $tt_hapus2="<i class='fa fa-times'></i>";
  $cl_hist="class='btn btn-info btn-s'";
  $tt_hist="data-toggle='tooltip' title='$cri'><i class='fa fa-history'></i>";
  $cl_attach="btn btn-warning btn-s";
  $tt_attach="data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i>";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
  	<?php 
    include "content_title.php"; ?> - <?php if ($st_company=="MULTI_WHS") 
          { echo "Inventory Multi Warehouse"; } 
          else if ($logo_erp=="S") 
          { echo "SIGNAL BIT"; } 
          else if ($logo_erp=="Z") 
          { echo "ZAST ERP"; } 
          else 
          { echo "IT Inventory"; } ?></title>
  <link rel="icon" type="image/jpeg" href="../../include/icon2.jpg">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php 
  if ($excel=="N")
  {?>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../../plugins/datatables_responsive/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../../plugins/datatables_responsive/fixedColumns.css">
    <link rel="stylesheet" href="../../plugins/datatables_responsive/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../../plugins/colorpicker/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/font-awesome.css">
    <script src="../../dist/sweetalert.js"></script>
    <link rel="stylesheet" href="../../dist/sweetalert.css">
  <?PHP }
  ?>
</head>
<!-- skin-green atau skin-blue atau skin-yellow atau skin-purple atau skin-red -->
<body class="hold-transition skin-yellow-light layout-top-nav fixed">
<div class="wrapper">
  <?PHP 
  if ($excel=="N")
  {?>
    <!-- Modal-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Detail <?PHP include "content_header.php"; ?></h4>
          </div>
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="myModalListCosting" tabindex="-1" 
      role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">List Costing</h4>
          </div>
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>
  <?PHP }
  ?>
  <header class="main-header">
    <?php if ($excel=="N") { include 'header.php'; } ?>
  </header>
  <?php 
    if ($msg!="")
    { $msgtext=flookup("nama_pilihan","masterpilihan","kode_pilihan='Msg$msg'");
      if ($msgtext=="") { $msgtext=$msg; }
      if ($msg=="1" OR $msg=="2" OR $msg=="6")
      { if ($mod=="66")
        { if (isset($_GET['noid'])) { $noid=$_GET['noid']; } else { $noid=""; }
          $msgtext=$msgtext.' BKB # '.$noid;
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>"; 
        }
        else
        { echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>"; }
      }
      else if ($msg=="4")
      { $MinTr = flookup("min(trans_no)","upload_tpb","username='$user' and sesi='$sesi' and trans_no<>''");
        $MaxTr = flookup("max(trans_no)","upload_tpb","username='$user' and sesi='$sesi' and trans_no<>''"); 
        $sql = "insert into upload_tpb_hist select * from upload_tpb where USERNAME='$user'
          and SESI='$sesi'";
        insert_log($sql,$user);
        $sql = "delete from upload_tpb where USERNAME='$user'
          and SESI='$sesi'";
        insert_log($sql,$user);
        echo "<script>swal({ title: 'Data berhasil diproses. Nomor transaksi dari $MinTr s/d $MaxTr', imageUrl: '../../images/success.jpg' });</script>"; 
      }
      else
      { if (substr($msgtext,0,1)=="X")
        { $msgtext=substr($msgtext,1,111);
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/error.jpg' });</script>"; 
        }
        else
        { echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>"; }
      }
      $_SESSION['msg'] = "";
    }
  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <section class="content-header">
        <h1>
          <?PHP include "content_header.php"; ?>  
        </h1>
      </section>
      <section class="content">
        <?PHP include "content.php"; ?>  
      </section>
      <!-- /.content -->
    </div>
  </div>
</div>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
  $(function(){
    $(document).on('click','.edit-record',function(e){
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('detail_in.php',
            {id:$(this).attr('data-id')},
            function(html){
                $(".modal-body").html(html);
            }   
        );
    });
    $(document).on('click','.list-cost',function(e){
        e.preventDefault();
        $("#myModalListCosting").modal('show');
        $.post('list_cs.php',
            {id:$(this).attr('data-id')},
            function(html){
                $(".modal-body").html(html);
            }   
        );
    });
    $(document).on('click','.add-oth',function(e){
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('add_oth_cs.php',
            {id:$(this).attr('data-id')},
            function(html){
                $(".modal-body").html(html);
            }   
        );
    });
  });
  $(function(){
    $(document).on('click','.img-prev-ws',function(e){
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('show_img_ws.php',
            {id:$(this).attr('data-id')},
            function(html){
                $(".modal-body").html(html);
            }   
        );
    });
  });
  $(function(){
    $(document).on('click','.add-bom',function(e){
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('add_bom.php',
            {id:$(this).attr('data-id')},
            function(html){
                $(".modal-body").html(html);
            }   
        );
    });
  });
</script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../../plugins/select2/select2.full.min.js"></script>
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="../../dist/js/app.min.js"></script>
<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.fixedColumns.min.js"></script>
<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    $(".my-colorpicker1").colorpicker();      
    //Date picker
    $('#datepicker1').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });
    //Date picker
    $('#datepicker2').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });
    //Date picker
    $('#datepicker3').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });
  });
  // tabel
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
    $('#examplefix').DataTable
    ({scrollY: "300px",
      scrollCollapse: true,
      paging: true,
      pageLength: 20,
      fixedColumns:   
      { leftColumns: 1,
        rightColumns: 1
      },
      <?php if ($mod=="18v") { ?>
        columnDefs: 
        [ { "type": "date", "targets": 2,
            "type": "date", "targets": 5 
          } 
        ]
      <?php } ?>
    });
  });
  $(document).ready(function() {
    var table = $('#examplefix2nopage').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
  $(document).ready(function() {
    var table = $('#examplefix3nopage').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
  
  $(document).ready(function() {
    var table = $('#examplefixnopage').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });

  $(document).ready(function () {
    $(document).ready(function() {
    var table = $('#example_bom_global_sum').DataTable 
    ({ 
      paging: false, 
footerCallback: function (row, data, start, end, display) {
  
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
 
            // Total over all pages
            total = api
                .column(6)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer
            $(api.column(6).footer()).html(pageTotal);
        },
    });
});
});
  
  //Datatable fix header no search and pagination
  $(document).ready(function() {
    var table = $('#examplefix2').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
  $(document).ready(function() {
    var table = $('#examplemon').DataTable( {
    scrollY:        "300px",
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    fixedColumns:   
    {
        leftColumns: 5
    }
    });
  });
  $(document).ready(function() {
    var table = $('#examplefix4').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        sorting: false,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
  $(document).ready(function() {
    var table = $('#examplefix3').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        sorting: false,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
  // tabel dengan grand total
  $(document).ready(function() {
    $('#example').DataTable(
    { "footerCallback": function ( row, data, start, end, display ) {
      var api = this.api(), data;
      // Remove the formatting to get integer data for summation
      var intVal = function ( i ) 
      { return typeof i === 'string' ?
        i.replace(/[\$,]/g, '')*1 :
        typeof i === 'number' ?
        i : 0;
      };
      // Total over all pages
      total = api
      <?PHP 
      if ($mode=="FG")
      { echo ".column(7)"; }
      else 
      { echo ".column(5)"; }
      ?>
      .data()
      .reduce( function (a, b) 
      { 
        return intVal(a) + intVal(b);
      }, 0 );
      // Total over this page
      pageTotal = api
      <?PHP 
      if ($mode=="FG")
      { echo ".column(7,{page: 'current'})"; }
      else
      { echo ".column(5,{page: 'current'})"; }
      ?>
      .data()
      .reduce( function (a, b) 
      {
      return intVal(a) + intVal(b);
      },0);
      // Update footer
      <?PHP
      if ($mode=="FG")
      { echo "$(api.column(7).footer()).html("; }
      else
      { echo "$(api.column(5).footer()).html("; }
      ?>
      pageTotal +' ( '+ total +' total)'
      );
      }
    });
  });
</script>
</body>
</html>