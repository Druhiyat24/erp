<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("konfirmasi_sj", "userpassword", "username='$user'");
if ($akses == "0") {
  echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
}
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "konfirmasi_new") {

?>

  <script type='text/javascript'>
    function gettipe() {
      $("#examplefix1 tr").remove();
      var tipe_konf = document.form.tipe_konf.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_konfirmasi_new.php?modeajax=view_list_tipe',
        data: {
          tipe_konf: tipe_konf,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };



    function getdata() {
      var id_tipe = document.form.cbotipe.value;
      var tipe_konf = document.form.tipe_konf.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_konfirmasi_new.php?modeajax=view_list_data',
        data: {
          id_tipe: id_tipe,
          tipe_konf: tipe_konf
        },
        async: false
      }).responseText;
      if (html) {
        $("#detail_item").html(html);
      }
      $(document).ready(function() {
        var table = $('#examplefix1').DataTable({
          scrollCollapse: true,
          paging: false,
          orderClasses: false
        });
      });
    };
  </script>

  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' action='save_konfirmasi_new.php?mod=simpan'>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Konfirmasi #</label>
              <select class='form-control select2' id='tipe_konf' name='tipe_konf' value='<?php echo $tipe_konf; ?>' onchange='gettipe()'>
                <option>Pilih Data</option>

                <option value="PENERIMAAN" <?php if ($tipe_konf == "PENERIMAAN") {
                                              echo "selected";
                                            } ?>>PENERIMAAN</option>
                <option value="PENGELUARAN" <?php if ($tipe_konf == "PENGELUARAN") {
                                              echo "selected";
                                            } ?>>PENGELUARAN</option>
              </select>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Tipe Item #</label>
              <select class='form-control select2' style='width: 100%;' name='cbotipe' id='cbotipe' onchange='getdata()'>
              </select>
            </div>
          </div>
          <div class='col-md-2'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit' class='btn btn-primary'>Konfirmasi</button>
            </div>
          </div>
          <div class='box-body'>
            <div id='detail_item'></div>
          </div>
      </div>
    </div>
  </div>

<?php }
