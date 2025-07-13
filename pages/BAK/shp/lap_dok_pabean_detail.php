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
<?php if ($mod == "lap_dok_pabean_det") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_pilihan = $_POST['nama_pilihan'];
    // $status = $_POST['status'];

    echo "<script>
    window.open ('?mod=lap_dok_pabean_exc_det&from=$from&to=$to&nama_pilihan=$nama_pilihan&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_pilihan = $_POST['nama_pilihan'];
    // $status = $_POST['status'];
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


          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Dokumen</label>
              <select class="form-control select2" name="nama_pilihan" id="nama_pilihan" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($nama_pilihan == "ALL") { echo "selected"; } ?>>ALL</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $data2 = isset($_POST['nama_pilihan']) ? $_POST['nama_pilihan']: null;
                }                 
                $sql = mysql_query("select DISTINCT nama_pilihan from masterpilihan where kode_pilihan in ('JENIS_DOK_IN','JENIS_DOK_OUT') and nama_pilihan not in ('IN','INHOUSE','SPPBE','OUT')");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['nama_pilihan'];
                  $data2 = $row['nama_pilihan'];
                  if($row['nama_pilihan'] == $_POST['nama_pilihan']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>
          </div>
<!-- 
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Status</label>
              <select class='form-control select2' id='status' name='status' >
                <option value="ALL" <?php if ($status == "ALL") {
                  echo "selected";
                } ?>>ALL</option>
                <option value="Updated" <?php if ($status == "Updated") {
                  echo "selected";
                } ?>>UPDATED</option>
                <option value="Not Updated" <?php if ($status == "Not Updated") {
                  echo "selected";
                } ?>>NOT UPDATED</option>
              </select>
            </div>
          </div> -->


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

          <div class='col-md-3'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
              <button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
            </div>
          </div>
          <div class='col-md-3'>
            <small style="color: red"><em> * filter tanggal daftar</em></small>
          </div>
        </div>
      </div>
      <table id="tbl_dok_pab" class="table table-striped table-bordered display responsive text-nowrap" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>No Upload</th>
            <th>Jenis Dokumen</th>
            <th>Supplier</th>
            <th>No Daftar</th>
            <th>Tgl Daftar</th>
            <th>No Aju</th>
            <th>Tgl Aju</th> 
            <th>Kode Barang</th> 
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Curr</th>
            <th>Price</th>
            <th>Total</th>
            <th>Rate</th>
            <th>Total IDR</th>
            <th>Uploaded By</th>
            <th>Uploaded Date</th>
            <th hidden>-</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          // if($nama_pilihan == 'ALL' and $status == 'ALL'){
          //   $where = "where a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
          //   $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          // }elseif($nama_pilihan != 'ALL' and $status == 'ALL'){
          //   $where = "where kode_dokumen = '$nama_pilihan' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
          //   $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          // }elseif($nama_pilihan == 'ALL' and $status != 'ALL'){
          //   $where = "where status = '$status' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
          //   $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          // }else{
          //   $where = "where kode_dokumen = '$nama_pilihan' and status = '$status' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";  
          //   $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          // }


          if($nama_pilihan == 'ALL'){
            $where = "where tgl_daftar >= '$from' and tgl_daftar <= '$to'";
          }else{
            $where = "where kode_dokumen_format = '$nama_pilihan' tgl_daftar >= '$from' and tgl_daftar <= '$to'";  
          }
          
          $sql = "select * from (SELECT *, CASE 
          WHEN LENGTH(kode_dokumen) = 3 THEN 
          CONCAT('BC ', 
           SUBSTRING(kode_dokumen, 1, 1), '.', 
           SUBSTRING(kode_dokumen, 2, 1), '.', 
           SUBSTRING(kode_dokumen, 3, 1))
          WHEN LENGTH(kode_dokumen) = 2 THEN 
          CONCAT('BC ', 
           SUBSTRING(kode_dokumen, 1, 1), '.', 
           SUBSTRING(kode_dokumen, 2, 1))
          ELSE kode_dokumen 
          END AS kode_dokumen_format FROM ( SELECT a.*,c.nama_entitas,kode_barang, uraian, qty, unit, curr, price, rates, cif, cif_rupiah FROM (SELECT no_dokumen, kode_dokumen ,nomor_aju,SUBSTRING(nomor_aju,-6) no_aju,DATE_FORMAT(STR_TO_DATE(SUBSTRING(nomor_aju,13,8),'%Y%m%d'),'%Y-%m-%d') tgl_aju,LPAD(nomor_daftar,6,0) no_daftar,tanggal_daftar tgl_daftar, created_by, created_date FROM exim_header) a LEFT JOIN ( SELECT nomor_aju,kode_barang,uraian,jumlah_satuan qty,kode_satuan unit, IF(ndpbm = 1,'IDR','USD') curr,(cif/jumlah_satuan) price, ndpbm rates, cif,cif_rupiah FROM exim_barang) b ON b.nomor_aju=a.nomor_aju left join (select nomor_aju, nama_entitas from exim_entitas where kode_entitas = '5' GROUP BY nomor_aju) c on c.nomor_aju=a.nomor_aju) a) a $where";

        // echo $sql;
          $query = mysql_query($sql);

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_aju = date('d M Y', strtotime($data[tgl_aju]));
            $tgl_daftar = date('d M Y', strtotime($data[tgl_daftar]));

            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[no_dokumen]</td>";
            echo "<td>$data[kode_dokumen_format]</td>";
            echo "<td>$data[nama_entitas]</td>";
            echo "<td>$data[no_daftar]</td>";
            echo "<td>$tgl_daftar</td>";
            echo "<td>$data[no_aju]</td>";
            echo "<td>$tgl_aju</td>";
            echo "<td>$data[kode_barang]</td>";
            echo "<td>$data[uraian]</td>";
            echo "<td style='text-align: right;'>".number_format($data['qty'],2)."</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td style='text-align: right;'>".number_format($data['price'],2)."</td>";
            echo "<td style='text-align: right;'>".number_format($data['cif'],2)."</td>";
            echo "<td style='text-align: right;'>".number_format($data['rates'],2)."</td>";
            echo "<td style='text-align: right;'>".number_format($data['cif_rupiah'],2)."</td>";
            echo "<td>$data[created_by]</td>";
            echo "<td>$data[created_date]</td>";
            echo "<td hidden>$data[nomor_aju]</td>";
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
