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
<?php if ($mod == "stat_memo") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $status = $_POST['status'];
    $nama_supp = $_POST['nama_supp'];
    $nama_buyer = $_POST['nama_buyer'];
    echo "<script>
  window.open ('?mod=exc_stat_memo&from=$from&to=$to&status=$status&nama_supp=$nama_supp&nama_buyer=$nama_buyer&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $status = $_POST['status'];
    $nama_supp = $_POST['nama_supp'];
    $nama_buyer = $_POST['nama_buyer'];
    }

?>
<!--   <script type='text/javascript'>
    function getdetail() {
      var status = document.form.status.value;
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
              <label>Status #</label>
              <select class='form-control select2' id='status' name='status' >
                <option value="ALL" <?php if ($status == "ALL") {
                                              echo "selected";
                                            } ?>>ALL</option>
                <option value="DRAFT" <?php if ($status == "DRAFT") {
                                              echo "selected";
                                            } ?>>DRAFT</option>
                <option value="PAYMENT DRAFT" <?php if ($status == "PAYMENT DRAFT") {
                                              echo "selected";
                                            } ?>>PAYMENT DRAFT</option>
                <option value="PAID" <?php if ($status == "PAID") {
                                              echo "selected";
                                            } ?>>PAID</option>
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
          </div>

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
            <th>No Memo</th>
            <th>Tanggal Memo</th>
            <th>Supplier</th>
            <th>Buyer</th>
            <th>Nomor PV</th>
            <th>Tanggal PV</th>
            <th>Nomor Bank Out</th>
            <th>Tanggal Bank Out</th>
            <th>No DN</th>
            <th>Tanggal DN</th>
            <th>Status</th>
            <th>User Input</th>
            <th>Tanggal Input</th>
            <th>User approve</th>
            <th>Tanggal approve</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          if($status == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.status = '$status' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.status = '$status' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.status = '$status' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($status == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }else{
            $where = "where a.status = '$status' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";  
          }
          
          $query = mysql_query("select a.nm_memo,tgl_memo,supplier,buyer,no_dn,tgl_dn,no_alk,tgl_alk,no_pv,pv_date,no_bankout,bankout_date,status ,id_supplier,id_buyer,date_input,user,approved_date,approved_by from (select a.nm_memo,a.tgl_memo,a.status, ms.supplier supplier, mb.supplier buyer, a.id_supplier,a.id_buyer,a.date_input,a.user,a.approved_date,a.approved_by from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier order by id_h desc) a LEFT JOIN
(select nm_memo, no_dn,tgl_dn,no_alk,tgl_alk from (select * from (select b.nm_memo, a.no_dn,a.tgl_dn from tbl_debitnote_h a INNER JOIN tbl_debitnote_det b on b.no_dn = a.no_dn where b.nm_memo like '%MEMO%' and a.status != 'CANCEL' GROUP BY b.nm_memo) a left JOIN
(select b.no_ref,a.no_alk,a.tgl_alk from tbl_alokasi a INNER JOIN tbl_alokasi_detail b on b.no_alk = a.no_alk where b.no_ref like '%DN%' and a.status != 'CANCEL' GROUP BY b.no_ref) b on b.no_ref = a.no_dn) a) b on b.nm_memo = a.nm_memo LEFT JOIN
(select reff_doc nm_memo, no_pv,pv_date,no_bankout,bankout_date from (select * from (select b.reff_doc,a.no_pv,a.pv_date from tbl_pv_h a INNER JOIN tbl_pv b on b.no_pv =  a.no_pv where b.reff_doc like '%MEMO/%' and a.status != 'CANCEL') a LEFT JOIN
(select a.no_bankout,a.bankout_date,b.no_reff from b_bankout_h a INNER JOIN b_bankout_det b on b.no_bankout = a.no_bankout where b.no_reff like '%PV/%' and a.status != 'CANCEL') b on b.no_reff = a.no_pv) a) c on c.nm_memo = a.nm_memo $where");
          

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_memo = date('d M Y', strtotime($data[tgl_memo]));
            $approveddate = $data[approved_date];
            $pvdate = $data[pv_date];
            $bankoutdate = $data[bankout_date];
            $tgldn = $data[tgl_dn];
            $date_input = date('d M Y', strtotime($data[date_input]));

            if ($approveddate == null || $approveddate == '' ) {
              $approved_date = '-';
            }else{
              $approved_date = date('d M Y', strtotime($data[approved_date]));
            }
            if ($pvdate == null || $pvdate == '' ) {
              $pv_date = '-';
            }else{
              $pv_date = date('d M Y', strtotime($data[pv_date]));
            }
            if ($bankoutdate == null || $bankoutdate == '' ) {
              $bankout_date = '-';
            }else{
              $bankout_date = date('d M Y', strtotime($data[bankout_date]));
            }
            if ($tgldn == null || $tgldn == '' ) {
              $tgl_dn = '-';
            }else{
              $tgl_dn = date('d M Y', strtotime($data[tgl_dn]));
            }
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[nm_memo]</td>";
            echo "<td>$tgl_memo</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[no_pv]</td>";
            echo "<td>$pv_date</td>";
            echo "<td>$data[no_bankout]</td>";
            echo "<td>$bankout_date</td>";
            echo "<td>$data[no_dn]</td>";
            echo "<td>$tgl_dn</td>";
            echo "<td>$data[status]</td>";
            echo "<td>$data[user]</td>";
            echo "<td>$date_input</td>";
            echo "<td>$data[approved_by]</td>";
            echo "<td>$approved_date</td>";
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
