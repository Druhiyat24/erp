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
  $logo_erp=$rs['logo_company'];
  $img_alert = "imageUrl: '../../images/error.jpg'";
  
  if ($dalam_perbaikan=="Y")
    { echo "<script>window.location.href='../../maaf';</script>";
  exit;
}
$cl_ubah="class='btn btn-primary btn-s'";
$tt_ubah="data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>";
$cl_hapus="class='btn btn-danger btn-s'";
$tt_hapus="data-toggle='tooltip' title='$chap'";
$tt_hapus2="<i class='fa fa-trash-o'></i>";
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
    if ($mod=="14") { echo "Kartu Stock"; } 
    else if ($mod=="19") { echo "Laporan Detail Transaksi"; } 
    else { include "content_header.php"; } ?> - <?php if ($st_company=="MULTI_WHS") 
    { echo "Inventory Multi Warehouse"; } 
    else if ($logo_erp=="S") 
      { echo "SIGNAL BIT"; } 
    else 
      { echo "IT Inventory"; } ?></title>
    <link rel="icon" type="image/jpeg" href="../../include/icon2.jpg">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?PHP 
    if ($excel=="N")
      {?>
        <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
        <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../../plugins/datatables_responsive/responsive.dataTables.min.css">
        <link rel="stylesheet" href="../../plugins/datatables_responsive/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="../../fontawesome/css/font-awesome.css"><script src="../../dist/sweetalert.js"></script>
        <script src="../../dist/sweetalert.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="../../dist/sweetalert.css">
        <?PHP }
        ?>
      </head>
      <!-- skin-green atau skin-blue atau skin-yellow atau skin-purple atau skin-red -->
      <body class="hold-transition skin-purple layout-top-nav fixed">
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
  <script src="../marketting/js/SalesOrder.js"></script>
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
    });
    $(function(){
      $(document).on('click','.img-prev',function(e){
        e.preventDefault();
        $("#myModal").modal('show');
        $.post('show_img.php',
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

  <script>
  // Menginisialisasi Select2
  $(document).ready(function() {

    // Cek apakah elemen dengan ID 'jenis_dok' ada
var jenisDokElement = document.getElementById('jenis_dok');
var jenisDok = null;

if (jenisDokElement) {
    // Jika elemen ditemukan, ambil nilainya
    jenisDok = jenisDokElement.value;

    // Proses nilai jenisDok
    jenisDok = jenisDok.substring(3); // Menghilangkan 3 karakter pertama
    jenisDok = jenisDok.replace(/\./g, ''); // Menghapus semua titik (.)
}

// Kirim AJAX hanya jika jenisDok tidak null
if (jenisDok !== null) {
    // $.ajax({
    //     url: 'getNomorPengajuan.php', // Script untuk mengambil nomor pengajuan berdasarkan jenis dokumen
    //     method: 'GET',
    //     data: { jenis_dok: jenisDok },
    //     success: function(response) {
    //         console.log(response);
    //         $('#nomor_daftar').html(response); // Update pilihan nomor pengajuan
    //     }
    // });
} else {
    console.warn("Elemen dengan ID 'jenis_dok' tidak ditemukan.");
}


    // Inisialisasi Select2 untuk jenis dokumen
    // Inisialisasi Select2 untuk nomor pengajuan
    $('#nomor_daftar').select2();

    // Ketika jenis dokumen berubah, update nomor pengajuan
    $('#jenis_dok').change(function() {
      var jenisDok = $(this).val();
      jenisDok = jenisDok.substring(3); // Menghilangkan 3 karakter pertama
    jenisDok = jenisDok.replace(/\./g, ''); // Menghapus semua titik (.)
    // alert(jenisDok)
    $.ajax({
        url: 'getNomorPengajuan.php', // Script untuk mengambil nomor pengajuan berdasarkan jenis dokumen
        method: 'GET',
        data: { jenis_dok: jenisDok },
        success: function(response) {
          console.log(response);
          $('#nomor_daftar').html(response); // Update pilihan nomor pengajuan
        }
      });
  });

    // Ketika nomor pengajuan dipilih, isi tanggal pengajuan, nomor daftar dan tanggal daftar
    $('#nomor_daftar').change(function() {
      var nomorAju = $(this).val();
      var jenisDok = document.getElementById('jenis_dok').value;
      jenisDok = jenisDok.substring(3); // Menghilangkan 3 karakter pertama
    jenisDok = jenisDok.replace(/\./g, '');
      $.ajax({
        url: 'getDetailsPengajuan.php', // Script untuk mengambil data detail berdasarkan nomor pengajuan
        method: 'GET',
        data: { nomor_aju: nomorAju, jenis_dok: jenisDok },
        success: function(response) {
          var data = JSON.parse(response);
          // Isi field dengan data yang diterima
          $('input[name="txttanggal_aju"]').val(data.tgl_aju);
          $('input[name="txtnomor_aju"]').val(data.no_aju);
          $('input[name="txttanggal_daftar"]').val(data.tgl_daftar);
        }
      });
    });
  });
</script>

<script type="text/javascript">
  // Fungsi untuk menampilkan SweetAlert Loading
// Fungsi untuk menampilkan loading SweetAlert
document.getElementById('uploadForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Mencegah form untuk submit secara default
    showLoading(); // Tampilkan loading SweetAlert

    // Membuat objek FormData untuk mengirimkan file ke server
    let formData = new FormData(this);

    try {
        // Mengirim file ke server dengan AJAX
        let response = await fetch('simpan_upload.php', {
          method: 'POST',
          body: formData
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Menunggu respon dari server dan parsing data sebagai JSON
        let data = await response.json();

        // Menutup SweetAlert loading setelah proses selesai
        Swal.close();

        // Menampilkan hasil upload (berhasil/gagal)
        if (data.status === 'success') {
          Swal.fire({
            title: 'Sukses',
            text: data.message,
            icon: 'success',
    showCancelButton: true, // Menampilkan tombol "Kembali"
    confirmButtonText: 'Lanjut Upload',
    cancelButtonText: 'Kembali',
  }).then((result) => {
    if (result.isConfirmed) {
        // Tombol "Lanjut Upload" diklik
        window.location.href = '?mod=proses_upload_exim'; 
      } else if (result.isDismissed) {
        window.location.href = '?mod=upload_exim'; 
      }
    });
} else {
  Swal.fire('Gagal', data.message, 'error');
}
} catch (error) {
        // Menutup SweetAlert loading jika terjadi error
        Swal.close();
        console.error('Error:', error); // Debugging error
        Swal.fire('Gagal', 'Terjadi kesalahan saat memproses file. ' + error.message, 'error');
      }
    });

// Fungsi untuk menampilkan loading
function showLoading() {
  Swal.fire({
    title: 'Memproses...',
    text: 'Mohon tunggu.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
}

</script>

<script type="text/javascript">
  $('table tbody tr').on('click', 'td:eq(1)', function(){                
    $('#modal_memo_app').modal('show');
    var no_memo = $(this).closest('tr').find('td:eq(1)').attr('value');
    var tgl_memo = $(this).closest('tr').find('td:eq(2)').attr('value');
    var jns_inv = $(this).closest('tr').find('td:eq(3)').attr('value');
    var kepada = $(this).closest('tr').find('td:eq(4)').attr('value');
    var supplier = $(this).closest('tr').find('td:eq(5)').attr('value');
    var jns_trans = $(this).closest('tr').find('td:eq(6)').attr('value');
    var buyer = $(this).closest('tr').find('td:eq(8)').attr('value');
    var id_h = $(this).closest('tr').find('td:eq(11)').attr('value');
    $.ajax({
      type: 'post',
      url: 'ajax_modal_memo.php',
      data: {
        no_memo: no_memo,
        tgl_memo: tgl_memo,
        jns_inv: jns_inv,
        kepada: kepada,
        supplier: supplier,
        jns_trans: jns_trans,
        buyer: buyer,
        id_h: id_h
      },
      success: function(data) {
        console.log(data);
        $('#detail_memo').html(data); //menampilkan data ke dalam modal
      }
    });       
        //make your ajax call populate items or what even you need
        $('#txt_title').html(no_memo);
      });
    </script>

    <script type="text/javascript">
      $("#select_all_memo").click(function() {
        var c = this.checked;
        $(':checkbox').prop('checked', c);
      });  
    </script>

    <script type="text/javascript">
      $("#form-simpan").on("click", "#approve_memo", function(){
        $("input[type=checkbox]:checked").each(function () {                
          var no_memo = $(this).closest('tr').find('td:eq(1)').attr('value');

          $.ajax({
            type:'POST',
            url:'approve_memo.php',
            data: {'no_memo':no_memo},
            close: function(e){
              e.preventDefault();
            },
            success: function(response){                
              console.log(response);
              window.location.reload();

            },
            error:  function (xhr, ajaxOptions, thrownError) {
             alert(xhr);
           }
         });
        });
        if(document.querySelectorAll("input[name='select[]']:checked").length >= 1){
          alert("Approved Successfully");
        }else{
          alert("Please check Memo");
        }        
      });
    </script>

    <script type="text/javascript">
      $("#form-simpan").on("click", "#cancel_memo", function(){
        $("input[type=checkbox]:checked").each(function () {                
          var no_memo = $(this).closest('tr').find('td:eq(1)').attr('value');

          $.ajax({
            type:'POST',
            url:'cancel_memo.php',
            data: {'no_memo':no_memo},
            close: function(e){
              e.preventDefault();
            },
            success: function(response){                
              console.log(response);
              window.location.reload();

            },
            error:  function (xhr, ajaxOptions, thrownError) {
             alert(xhr);
           }
         });
        });
        if(document.querySelectorAll("input[name='select[]']:checked").length >= 1){
          alert("Cancelled Successfully");
        }else{
          alert("Please check Memo");
        }        
      });
    </script>

    <script>
      $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Date picker
    $('#datepicker1').datepicker
    ({  format: "dd M yyyy",
      autoclose: true
    });

    $('#datepickerto').datepicker
    ({  format: "dd M yyyy",
      autoclose: true
    });

    $('#datepicker1_memo').datepicker
    ({  format: "dd M yyyy",
      autoclose: true,
      startDate : "01-05-2025",
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
    //Date picker
    $('#datepicker4').datepicker
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
  });
  //Datatable fix header
  $(document).ready(function() {
    var table = $('#examplefix').DataTable
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

  //Datatable fix header no search and pagination
  $(document).ready(function () {
    var table = $('#tbl_dok_pab').DataTable({
        scrollX: true, 
        scrollY: "300px",         // Atur tinggi scrollable
        scrollCollapse: true,     // Scroll collapsible jika data lebih sedikit
        paging: false,            // Nonaktifkan paginasi
        searching: true,          // Aktifkan pencarian
        fixedColumns: {
            leftColumns: 1,       // Kolom kiri tetap
            rightColumns: 1       // Kolom kanan tetap
        },
        // Pastikan tidak ada row grouping atau fitur child rows
        responsive: false,        // Nonaktifkan fitur responsive
        info: false               // Nonaktifkan informasi tambahan di footer
    });
});


  $(document).ready(function() {
    var table = $('#tbl_memo').DataTable
    ({  scrollY: "300px",
      scrollCollapse: true,
      paging: true,
      pageLength: 1000,
      fixedColumns:   
      { leftColumns: 1,
        rightColumns: 1
      }
    });
  });

  $(document).ready(function() {
    var table = $('#examplememo').DataTable
    ({  paging: false,
      ordering: true,
      info: false,
      scrollY:        "300px",
      scrollX:        true,
      scrollCollapse: true,
      paging:         false,
      columnDefs: [
      { width: '20%', targets: 0 }
      ], 
    });
  });


  
  //Datatable fix header no search and pagination
  $(document).ready(function() {
    var table = $('#examplefix2').DataTable
    ({  scrollY: "300px",
      scrollCollapse: true,
      paging: false,
      searching: false,
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
