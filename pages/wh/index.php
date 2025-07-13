<?php
session_start();
if (isset($_GET['dest'])) {
  $excel = "Y";
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=stock_opname.xls"); //ganti nama sesuai keperluan 
  header("Pragma: no-cache");
  header("Expires: 0");
} else {
  $excel = "N";
}
include '../../include/conn.php';
include 'fungsi.php';
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if ($_SESSION['bahasa'] == "Indonesia") {
  include 'id.php';
} else if ($_SESSION['bahasa'] == "Korean") {
  include 'kr.php';
} else {
  include 'id.php';
}
if (isset($_GET['mod'])) {
  $mod = $_GET['mod'];
} else {
  $mod = "";
}
if (isset($_SESSION['msg'])) {
  $msg = $_SESSION['msg'];
} else {
  $msg = "";
}
$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
$rs = mysql_fetch_array(mysql_query("select * from mastercompany"));
$dalam_perbaikan = $rs["dalam_perbaikan"];
$print_sj = $rs['print_sj'];
$logo_erp = $rs['logo_company'];
$st_company = $rs['status_company'];
$img_alert = "imageUrl: '../../images/error.jpg'";

if ($dalam_perbaikan == "Y") {
  echo "<script>window.location.href='../../maaf';</script>";
  exit;
}
$cl_ubah = "";
$tt_ubah = "data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>";
$cl_hapus = "";
$tt_hapus = "data-toggle='tooltip' title='$chap'";
$tt_hapus2 = "<i class='fa fa-trash-o'></i>";
$cl_hist = "";
$tt_hist = "data-toggle='tooltip' title='$cri'><i class='fa fa-history'></i>";
$cl_attach = "";
$tt_attach = "data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i>";
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    <?php
    if ($mod == "14") {
      echo "Kartu Stock";
    } else if ($mod == "19") {
      echo "Laporan Detail Transaksi";
    } else if ($mod == "25") {
      echo "Monitoring";
    } else {
      include "content_header.php";
    } ?> - Nirwana Digital Solution</title>
  <link rel="icon" type="image/jpeg" href="../../include/icon2.jpg">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?PHP
  if ($excel == "N") { ?>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../bootstrap_new/bootstrap-select.min.css">
    <link rel="stylesheet" href="../../bootstrap_new/apexchart/apexcharts.css">
    <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../../plugins/datatables_responsive/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../../plugins/datatables_responsive/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/font-awesome.css">
    <script src="../../dist/sweetalert.js"></script>
    <link rel="stylesheet" href="../../dist/sweetalert.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="../../plugins/datatables_responsive/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="../../dist/sweetalert.css">
  <?PHP }
  ?>
</head>
<!-- skin-green atau skin-blue atau skin-yellow atau skin-purple -->

<body class="hold-transition skin-green layout-top-nav fixed">
  <div class="wrapper">
    <?PHP
    if ($excel == "N") { ?>
      <!-- Modal-->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
    <?PHP }
    ?>
    <header class="main-header">
      <?php if ($excel == "N") {
        include 'header.php';
      } ?>
    </header>
    <?php
    if ($msg != "") {
      $msgtext = flookup("nama_pilihan", "masterpilihan", "kode_pilihan='Msg$msg'");
      if ($msgtext == "") {
        $msgtext = $msg;
      }
      if ($msg == "1" or $msg == "2" or $msg == "6") {
        if ($mod == "66") {
          if (isset($_GET['noid'])) {
            $noid = $_GET['noid'];
          } else {
            $noid = "";
          }
          $msgtext = $msgtext . ' BKB # ' . $noid;
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>";
        } else {
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>";
        }
      } else if ($msg == "4") {
        $MinTr = flookup("min(trans_no)", "upload_tpb", "username='$user' and sesi='$sesi' and trans_no<>''");
        $MaxTr = flookup("max(trans_no)", "upload_tpb", "username='$user' and sesi='$sesi' and trans_no<>''");
        $sql = "insert into upload_tpb_hist select * from upload_tpb where USERNAME='$user'
          and SESI='$sesi'";
        insert_log($sql, $user);
        $sql = "delete from upload_tpb where USERNAME='$user'
          and SESI='$sesi'";
        insert_log($sql, $user);
        echo "<script>swal({ title: 'Data berhasil diproses. Nomor transaksi dari $MinTr s/d $MaxTr', imageUrl: '../../images/success.jpg' });</script>";
      } else {
        if (substr($msgtext, 0, 1) == "X") {
          $msgtext = substr($msgtext, 1, 111);
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/error.jpg' });</script>";
        } else {
          echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/success.jpg' });</script>";
        }
      }
      $_SESSION['msg'] = "";
    }
    ?>
    <div class="content-wrapper">
      <div class="container-fluid">
        <section class="content-header">
          <h1>
            <?php
            if ($mod != "view_in") {
              include "content_header.php";
            }
            ?>
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
  <script src="../marketting/js/SalesOrder.js"></script>
  <script>
    $(function() {
      $(document).on('click', '.edit-record', function(e) {
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('detail_in.php', {
            id: $(this).attr('data-id')
          },
          function(html) {
            $(".modal-body").html(html);
          }
        );
      });
    });
    $(function() {
      $(document).on('click', '.img-prev', function(e) {
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('show_img.php', {
            id: $(this).attr('data-id')
          },
          function(html) {
            $(".modal-body").html(html);
          }
        );
      });
    });
    $(function() {
      $(document).on('click', '.add-bom', function(e) {
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('add_bom.php', {
            id: $(this).attr('data-id')
          },
          function(html) {
            $(".modal-body").html(html);
          }
        );
      });
    });
  </script>
  <script src="../../bootstrap/js/bootstrap.min.js"></script>
  <script src="../../bootstrap_new/bootstrap-select.min.js"></script>
  <script src="../../bootstrap_new/apexchart/apexcharts.js"></script>
  <script src="../../bootstrap_new/apexchart/apexcharts.min.js"></script>

  <script src="../../plugins/select2/select2.full.min.js"></script>

  <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>

  <script src="../../dist/js/app.min.js"></script>

  <script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>

  <script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>


  <script type="text/javascript" src="../../plugins/datatables_responsive/dataTables.fixedColumns.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../plugins/datatables_responsive/jquery.dataTables.1.10.20.min.css" />
  <link rel="stylesheet" type="text/css" href="../../plugins/datatables_responsive/buttons.dataTables.1.6.1.css" />


  <script type="text/javascript" src="../../plugins/js/jszip.js"></script>
  <script src="js/CustomDatatable.js"> </script>
  <script type="text/javascript" src="../../plugins/js/vfs_fonts.js"></script>
  <script type="text/javascript" src="js/dataTables.buttons.min.js"></script>


  <script>
    $('#master_name').change(function() {
      var master_name = $(this).val();
      // $('#divtabel_master').empty();
      $.ajax({
        type: 'POST',
        url: 'get_master.php',
        data: {
          'master_name': master_name
        },
        success: function(response) {
          $('#tabel_datamaster').html(response);
        }
      });
    });
  </script>

  <script type="text/javascript">
    $('#kd').change(function() {
      var kd = $(this).val();
      $.ajax({
        type: 'POST',
        url: 'ubah_selectrak.php',
        data: {
          'kd': kd
        },
        success: function(response) {
          $('#kode_rak').html(response);
        }
      });
    });
  </script>

  <script type="text/javascript">
    $('#kd_rak').change(function() {
      var kd_rak = $(this).val();
      $.ajax({
        type: 'POST',
        url: 'ubah_selectunit.php',
        data: {
          'kd_rak': kd_rak
        },
        success: function(response) {
          $('#unit_rak').html(response);
        }
      });
    });
  </script>

  <script type="text/javascript">
    $("#form-simpan_newmaterial").on("click", "#simpan", function() {
      $("table tbody tr").each(function() {
        var no_dok = document.getElementById('txtno_dok').value;
        var tgl_dok = document.getElementById('tanggal').value;
        var supplier = document.getElementById('txtsupp').value;
        var no_po = document.getElementById('txtpo').value;
        var tipe_pembelian = $('select[name=txtjns_in] option').filter(':selected').val();
        var no_sj = document.getElementById('txtinvno').value;
        var keterangan = document.getElementById('txtremark').value;
        var kode_barang = $(this).closest('tr').find('td:eq(0) input').val();
        var nama_barang = $(this).closest('tr').find('td:eq(1) input').val();
        var job_order = $(this).closest('tr').find('td:eq(2) input').val();
        var qty = $(this).closest('tr').find('td:eq(3) input').val();
        var unit = $(this).closest('tr').find('td:eq(4)').find('select[name=pil_unit] option').filter(':selected').val()


        $.ajax({
          type: 'POST',
          url: 'insert_inmaterial.php',
          data: {
            'no_dok': no_dok,
            'tgl_dok': tgl_dok,
            'supplier': supplier,
            'no_po': no_po,
            'tipe_pembelian': tipe_pembelian,
            'no_sj': no_sj,
            'keterangan': keterangan,
            'kode_barang': kode_barang,
            'nama_barang': nama_barang,
            'job_order': job_order,
            'qty': qty,
            'unit': unit
          },
          cache: 'false',
          close: function(e) {
            e.preventDefault();
          },
          success: function(response) {
            // alert(response);
            window.location.href = '../wh/?mod=in_material';

          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr);
            alert(xhr);
          }
        });

      });
    });
  </script>
  <script>
    $(function() {
      $('.selectpicker').selectpicker();
    });
  </script>
  <script type="text/javascript">
    $('#tanggal').change(function() {
      var tgl_dok = $(this).val();
      $.ajax({
        type: 'POST',
        url: 'get_nomordok.php',
        data: {
          'tgl_dok': tgl_dok
        },
        success: function(response) {
          $('#txtno_dok').val(response);
        }
      });
    });
  </script>

  <?PHP include "content_script.php"; ?>

  <!-- script apex -->

  <script>
    $(function() {
      //Initialize Select2 Elements
      $(".select2").select2();

      //Date picker
      $('#datepickerbrgjadi_awal').datepicker({
        format: "dd M yyyy",
        startDate: "01-10-2022",
        todayHighlight: true,
        autoclose: true
      });

      //Date picker
      $('#datepickerbrgjadi_akhir').datepicker({
        format: "dd M yyyy",
        startDate: "01-10-2022",
        todayHighlight: true,
        autoclose: true
      });

      //Date picker
      $('#tanggal').datepicker({
        format: "dd M yyyy",
        autoclose: true
      });

      $('#datepicker1').datepicker({
        format: "dd M yyyy",
        autoclose: true
      });
      //Date picker
      $('#datepicker2').datepicker({
        format: "dd M yyyy",
        autoclose: true
      });
      //Date picker
      $('#datepicker3').datepicker({
        format: "dd M yyyy",
        autoclose: true
      });
      //Date picker
      $('#datepicker4').datepicker({
        format: "dd M yyyy",
        autoclose: true
      });
      $('#monthpicker').datepicker({
        format: "M yyyy",
        autoclose: true
      });
      $(".monthpick").datepicker({
        format: "M yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
      });
    });
    // tabel
    $(function() {
      $("#example1").DataTable();
      $("#tabel_datamaster").DataTable();
      $("#dashboard1").DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false
      });
      $("#dashboard2").DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false
      });
      $("#dashboard3").DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false
      });
    });
    //Datatable fix header
    $(document).ready(function() {
      function strtrunc(str, max, add) {
        add = add || '...';
        return (typeof str === 'string' && str.length > max ? str.substring(0, max) + add : str);
      };

      var table = $('#tbl_master_item').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        fixedColumns: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [{
            targets: 0,
            width: "1px"
          },
          {
            targets: 1,
            width: "1px"
          },
          {
            targets: 2,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          }
        ]
      });
      // $('#tbl_list_in_out thead tr').clone(true).appendTo( '#tbl_list_in_out thead' );
      // $('#tbl_list_in_out thead tr:eq(1) th').each( function (i) 
      // {   $(this).html( '<input type="text" size="2"/>' );
      //     $( 'input', this ).on( 'keyup change', function () {
      //       if (table.column(i).search() !== this.value ) 
      //       { table
      //           .column(i)
      //           .search( this.value )
      //           .draw();
      //         }
      //     });
      // });
      var table = $('#tbl_list_in_out').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        fixedColumns: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [{
            targets: 0,
            width: "10px"
          },
          {
            targets: 4,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          }
        ]
      });

      var table = $('#tbl_mut_brgjadi').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        fixedColumns: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [{
            targets: 0,
            width: "10px"
          },
          {
            targets: 4,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          }
        ]
      });

      var table = $('#tbl_sup').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        fixedColumns: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [{
            targets: 2,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          },
          {
            targets: 3,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          }
        ]
      });
      var table = $('#examplefix').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        fixedColumns: true,
        sorting: false,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [{
            targets: 0,
            width: "10px"
          },
          {
            targets: 10,
            width: "70px"
          },
          {
            targets: 2,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          },
          {
            targets: 3,
            width: "150px",
            render: function(data, type, full, meta) {
              if (type === 'display') {
                data = strtrunc(data, 40);
              }
              return data;
            }
          }
        ]
      });
    });
    //Datatable fix header no search and pagination
    $(document).ready(function() {
      var table = $('#examplefix2').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        searching: false,
        fixedColumns: {
          leftColumns: 1,
          rightColumns: 1
        }
      });
    });
    //Datatable fix header no search and pagination
    $(document).ready(function() {
      var table = $('#examplefix3').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        sorting: false,
        fixedColumns: {
          leftColumns: 1,
          rightColumns: 1
        }
      });
    });

    $(document).ready(function() {
      $(document).ready(function() {
        var table = $('#example_bpb_global').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {

            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(10)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(6, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);
            pageTotal_a = api
              .column(8, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);
            pageTotal_b = api
              .column(9, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);
            // Update footer
            $(api.column(6).footer()).html(pageTotal);
            $(api.column(8).footer()).html(pageTotal_a);
            $(api.column(9).footer()).html(pageTotal_b);
          },
        });
      });
    });

    $(document).ready(function() {
      $(document).ready(function() {
        var table = $('#example_bppb_global').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {

            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(5)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(5, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Update footer
            $(api.column(5).footer()).html(pageTotal);
          },
        });
      });
    });

    $(document).ready(function() {
      $(document).ready(function() {
        var table = $('#example_bppb_global_lokasi').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {

            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(7)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(6, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);
              }, 0);

            // Update footer
            $(api.column(6).footer()).html(pageTotal);
          },
        });
      });
    });



    $(document).ready(function() {
      $(document).ready(function() {
        var table = $('#example_bpb_global_lokasi').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {

            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(7)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(6, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);

              }, 0);
            // Update footer
            $(api.column(6).footer()).html(pageTotal);
          },
        });
      });
    });


    $(document).ready(function() {
      $(document).ready(function() {
        var table = $('#example_in_material_lokasi').DataTable({
          info: false,
          paging: false,
          footerCallback: function(row, data, start, end, display) {

            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(7)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(4, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                let result = intVal(a) + intVal(b);
                return result.toFixed(2);

              }, 0);
            // Update footer
            $(api.column(4).footer()).html(pageTotal);
          },
        });
      });
    });
  </script>
</body>

</html>