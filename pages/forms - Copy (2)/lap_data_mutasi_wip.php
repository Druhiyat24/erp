<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
// $akses = flookup("lap_inventory", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "lap_mutasi_wip") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    echo "<script>
  window.open ('?mod=lap_data_exc_memo&from=$from&to=$to&tipe_inv=$tipe_inv&nama_supp=$nama_supp&nama_buyer=$nama_buyer&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    }

?>
<!--   <script type='text/javascript'>
    function getdetail() {
      var tipe_inv = document.form.tipe_inv.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_lap_data.php?modeajax=view_list_tipe',
        data: {
          tipe_data: tipe_data,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };
  </script> -->


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

       <!--  <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Invoice #</label>
              <select class='form-control select2' id='tipe_inv' name='tipe_inv' >
                <option value="ALL" <?php if ($tipe_inv == "ALL") {
                                              echo "selected";
                                            } ?>>ALL</option>
                <option value="INVOICE" <?php if ($tipe_inv == "INVOICE") {
                                              echo "selected";
                                            } ?>>INVOICE</option>
                <option value="NON INVOICE" <?php if ($tipe_inv == "NON INVOICE") {
                                              echo "selected";
                                            } ?>>NON INVOICE</option>
              </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Supplier #</label>
              <select class="form-control select2" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($nama_supp == "ALL") { echo "selected"; } ?>>ALL</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data2 = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                $sql = mysql_query("select distinct(Supplier),id_supplier from mastersupplier where tipe_sup = 'S' order by Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                    $data = $row['Supplier'];
                    $data2 = $row['id_supplier'];
                    if($row['id_supplier'] == $_POST['nama_supp']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
            </div>
          </div>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Buyer #</label>
              <select class="form-control select2" name="nama_buyer" id="nama_buyer" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($nama_buyer == "ALL") { echo "selected"; } ?>>ALL</option>                                            
                <?php
                $nama_buyer ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_buyer = isset($_POST['nama_buyer']) ? $_POST['nama_buyer']: null;
                }                 
                $sql = mysql_query("select distinct(Supplier),id_supplier from mastersupplier where tipe_sup = 'C' order by Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                    $data = $row['Supplier'];
                     $data2 = $row['id_supplier'];
                    if($row['id_supplier'] == $_POST['nama_buyer']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';

                    }
                    echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
                </select>
            </div>
          </div>-->

      </div>
      <div class='row'>
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Dari *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal' value="<?php 
            $txtfrom = isset($_POST['txtfrom']) ? $_POST['txtfrom']: null;            
            if(!empty($_POST['txtfrom'])) {
                echo $_POST['txtfrom'];
            }
            else{
                echo date("d M Y");
            } ?>">
          </div>
        </div>

        <div class='col-md-2'>
          <div class='form-group'>
            <label>Sampai *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal' value="<?php 
            $txtto = isset($_POST['txtto']) ? $_POST['txtto']: null;            
            if(!empty($_POST['txtto'])) {
                echo $_POST['txtto'];
            }
            else{
                echo date("d M Y");
            } ?>">
          </div>
        </div>

        <div class='col-md-6'>
          <div class='form-group' style='padding-top:25px'>
            <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
            <button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
          </div>
        </div>
      </div>
    </div>
    <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Saldo Buku</th>
            <th>Hasil Pencacahan</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          // if($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
          //   $where = "where a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
          //   $where = "where a.jns_inv = '$tipe_inv' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
          //   $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
          //   $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
          //   $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
          //   $where = "where a.jns_inv = '$tipe_inv' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
          //   $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          // }else{
          //   $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";  
          // }
          
          $query = mysql_query("select kode_barang,nama_barang,satuan,format(saldo_buku,2) saldo_buku,format(hasil_pencacahan,2) hasil_pencacahan,keterangan from tbl_mutasi_wip");
          

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            // $tgl_memo = date('d M Y', strtotime($data[tgl_memo]));
            // $date_input = date('d M Y', strtotime($data[date_input]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[kode_barang]</td>";
            echo "<td>$data[nama_barang]</td>";
            echo "<td>$data[satuan]</td>";
            echo "<td>$data[saldo_buku]</td>";
            echo "<td>$data[hasil_pencacahan]</td>";
            echo "<td>$data[keterangan]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
          </tbody>
      </table>
  </div>
  </div>
  </form>
  </div>
  </div><?php }
