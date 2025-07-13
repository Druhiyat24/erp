<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
// $akses = flookup("mnuBPB", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$harus_bpb = $rscomp["req_harus_bpb"];
$logo_company = $rscomp["logo_company"];
$user = $_SESSION['username'];

$cekuser = mysql_fetch_array(mysql_query("select * from userpassword where username = '$user'"));
$fullname = $cekuser["FullName"];

?>
<script type="text/javascript">


</script>
<?php if ($mod == "scan_numbering_input") {
  $tgl_input = date("d M Y");
  $tgl_cari_awal = date('Y-m-d 00:00:00');
  $tgl_cari_akhir = date('Y-m-d 23:59:59');
?>
  <script type='text/javascript'>
    function submitdata() {
      var txtqr = document.form.txtqr.value;
      console.log(txtqr);
      var html = $.ajax({
        type: "POST",
        url: 'ajax_scan_numbering.php?modeajax=submit_qr',
        data: {
          txtqr: txtqr
        },
        async: false
      }).responseText;
      // if (html) {
      //   $("#detail_item").html(html);
      // }
    };
  </script>
  <script type='text/javascript'>
    document.addEventListener('keydown', function(event) {
      if (event.keyCode == 17 || event.keyCode == 74)
        event.preventDefault();
    });
  </script>

  <style>
    #preview {
      width: 300px;
      height: 300px;
      margin: 0px auto;
    }
  </style>

  <!-- 
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> -->
  <!-- <script type="text/javascript" src="./instascan-master/assets/instascan_new.min.js"></script> -->

  <!-- <script type="text/javascript">
    var scanner = new Instascan.Scanner({
      video: document.getElementById('preview'),
      scanPeriod: 5,
      mirror: false
    });
    scanner.addListener('scan', function(content) {
      alert(content);
      //window.location.href=content;
    });
    Instascan.Camera.getCameras().then(function(cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
        $('[name="options"]').on('change', function() {
          if ($(this).val() == 1) {
            if (cameras[0] != "") {
              scanner.start(cameras[0]);
            } else {
              alert('No Front camera found!');
            }
          } else if ($(this).val() == 2) {
            if (cameras[1] != "") {
              scanner.start(cameras[1]);
            } else {
              alert('No Back camera found!');
            }
          }
        });
      } else {
        console.error('No cameras found.');
        alert('No cameras found.');
      }
    }).catch(function(e) {
      console.error(e);
      alert(e);
    });
  </script>
  <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
    <label class="btn btn-primary active">
      <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
    </label>
    <label class="btn btn-secondary">
      <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
    </label>
  </div> -->




  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>
          <div class='col-md-5'>
            <div class='form-group'>
              <label>Halo, <?php echo $fullname; ?></label>
            </div>
            <div class='form-group'>
              <label>Tgl Input #</label>
              <input type='text' class='form-control' id='datepicker1' name='tgl_input' disabled value='<?php echo $tgl_input; ?>'>
            </div>
            <!-- <div class='form-group'>
              <video id="preview"></video>
              <script type="text/javascript">
                let scanner = new Instascan.Scanner({
                  video: document.getElementById('preview'),
                  mirror: false
                });
                scanner.addListener('scan', function(content) {
                  console.log(content);
                  $("#txtqr").val(content);
                  submitdata();
                  $("#txtqr").val("");
                  window.location.reload();
                  $("#txtqr").focus();
                });
                Instascan.Camera.getCameras().then(function(cameras) {
                  if (cameras.length > 0) {
                    scanner.start(cameras[1]);
                  } else {
                    console.error('No cameras found.');

                  }
                }).catch(function(e) {
                  console.error(e);

                });
              </script>
            </div> -->
            <div class='form-group'>
              <label>Scan #</label>
              <input type='text' class='form-control' id='txtqr' autocomplete="off" name='txtqr' required autofocus>
              <!-- <input type='text' class='form-control' id='txtqr' autocomplete="off" name='txtqr' onkeypress='return NoSpaces()' autofocus> -->

              <!-- <textarea id="txtqr" name="txtqr" rows="4" cols="50"></textarea> -->
            </div>
            <div class='form-group'>
              <input type='submit' name='simpan' id='simpan' class='btn btn-primary' value='simpan' onclick='submitdata()'>
            </div>
          </div>
          <div class='box-body'>
            <!-- <div id='detail_item'></div> -->
            <!-- <table id='examplefix' class='display responsive ' align="left" style='width: 40%;font-size:12px;'> -->
            <table id='example_numbering' class='display responsive' style='width: 100%;font-size:12px;'>
              <thead>
                <tr>
                  <th>No #</th>
                  <th>WS</th>
                  <th>Brand</th>
                  <th>Color</th>
                  <th>Size</th>
                  <th>Total</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "select a.id, id_qr, ac.kpno, ac.brand, sd.color, sd.size, count(a.id) tot_input from numbering_input a
                inner join so_det sd on a.id_qr = sd.id
								inner join so on sd.id_so = so.id
								inner join act_costing ac on so.id_cost = ac.id
                where user = '$user' and tgl_input >= '$tgl_cari_awal' and tgl_input <= '$tgl_cari_akhir'
                group by id_qr";

                $i = 1;
                $query = mysql_query($sql);
                while ($data = mysql_fetch_array($query)) {
                  echo "
                <tr>
                  <td>$i</td>
                  <td>$data[kpno]</td>
                  <td>$data[brand]</td>
                  <td>$data[color]</td>
                  <td>$data[size]</td>
                  <td>$data[tot_input]</td>
                  <td>PCS</td>
                </tr>";
                  $i++;
                };
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>PCS</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div><?php } ?>