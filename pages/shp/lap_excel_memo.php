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
<?php if ($mod == "lap_memo_list") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_inv = $_POST['tipe_inv'];
    $nama_supp = $_POST['nama_supp'];
    $nama_buyer = $_POST['nama_buyer'];
    echo "<script>
  window.open ('?mod=lap_data_exc_memo&from=$from&to=$to&tipe_inv=$tipe_inv&nama_supp=$nama_supp&nama_buyer=$nama_buyer&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_inv = $_POST['tipe_inv'];
    $nama_supp = $_POST['nama_supp'];
    $nama_buyer = $_POST['nama_buyer'];
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
            <th>Profit Center</th>
            <th>Jenis Invoice</th>
            <th>No Invoice</th>
            <th>No Invoice Vendor</th>
            <th>kepada</th>
            <th>Jenis Transaksi</th>
            <th>Jenis Pengiriman</th>
            <th>Dokumen Pendukung</th>
            <th>Supplier</th>
            <th>Buyer</th>
            <th>Ditagihkan</th>
            <th>jatuh Tempo</th>
            <th>Kategori</th>
            <th>Sub Kategori</th>
            <th>Curr</th>
            <th>Biaya</th>
            <th>No Aju</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>User Input</th>
            <th>Tanggal Input</th>
            <th>User approve</th>
            <th>Tanggal approve</th>
            <th>Nomor PV</th>
            <th>Tanggal PV</th>
            <th>Nomor Bank Out</th>
            <th>Tanggal Bank Out</th>
            <th>No DN</th>
            <th>Tanggal DN</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          if($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv == 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv != 'ALL' and $nama_supp != 'ALL' and $nama_buyer == 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv != 'ALL' and $nama_supp == 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.jns_inv = '$tipe_inv' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }elseif($tipe_inv == 'ALL' and $nama_supp != 'ALL' and $nama_buyer != 'ALL'){
            $where = "where a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";
          }else{
            $where = "where a.jns_inv = '$tipe_inv' and a.id_supplier = '$nama_supp' and a.id_buyer = '$nama_buyer' and a.tgl_memo >= '$from' and a.tgl_memo <= '$to'";  
          }
          
//           $query = mysql_query("select * from (select id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv,no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input,approved_by,approved_date from (select * from (select a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer,a.approved_by,a.approved_date from memo_h a
//           inner join mastersupplier ms on a.id_supplier = ms.id_supplier
//           inner join mastersupplier mb on a.id_buyer = mb.id_supplier
//           inner join memo_det mdet on mdet.id_h = a.id_h
//           where mdet.cancel != 'Y' GROUP BY mdet.id order by mdet.id_h asc) a left join      
// (select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a) a left join 
// (select * from (select nm_memo nomemo from memo_h) nm left join (select nm_memo memo1, no_dn,tgl_dn,no_alk,tgl_alk from (select * from (select b.nm_memo, a.no_dn,a.tgl_dn from tbl_debitnote_h a INNER JOIN tbl_debitnote_det b on b.no_dn = a.no_dn where b.nm_memo like '%MEMO%' and a.status != 'CANCEL' GROUP BY b.nm_memo) a left JOIN
// (select b.no_ref,a.no_alk,a.tgl_alk from tbl_alokasi a INNER JOIN tbl_alokasi_detail b on b.no_alk = a.no_alk where b.no_ref like '%DN%' and a.status != 'CANCEL' GROUP BY b.no_ref) b on b.no_ref = a.no_dn) a) a on a.memo1 = nm.nomemo LEFT JOIN
// (select reff_doc memo2, no_pv,pv_date,no_bankout,bankout_date from (select * from (select b.reff_doc,a.no_pv,a.pv_date from tbl_pv_h a INNER JOIN tbl_pv b on b.no_pv =  a.no_pv where b.reff_doc like '%MEMO/%' and a.status != 'CANCEL' GROUP BY b.reff_doc) a LEFT JOIN
// (select a.no_bankout,a.bankout_date,b.no_reff from b_bankout_h a INNER JOIN b_bankout_det b on b.no_bankout = a.no_bankout where b.no_reff like '%PV/%' and a.status != 'CANCEL') b on b.no_reff = a.no_pv) a) b on b.memo2 = nm.nomemo) b on b.nomemo = a.nm_memo $where");


          $query = mysql_query("select * from ((select * from (select id_det,id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv,no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input,approved_by,approved_date,nama_pc from (select * from (select mdet.id id_det,a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.no_aju is null,'-',a.no_aju) no_aju, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer,a.approved_by,a.approved_date,mp.nama_pc from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          inner join memo_det mdet on mdet.id_h = a.id_h
          left join master_pc mp on mp.kode_pc = a.profit_center
          where mdet.cancel != 'Y' GROUP BY mdet.id order by mdet.id_h asc) a left join      
(select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a) a inner join 
(select nm_memo nomemo, nm_memo memo1, no_dn,tgl_dn,'' no_alk, '' tgl_alk,nm_memo memo2, no_pv,tgl_pv pv_date, no_bankout, tgl_bankout bankout_date from dd_update_memo) b on b.nomemo = a.nm_memo) 
UNION 
(select * from (select id_det,id_h,id_supplier,id_buyer,nm_memo,tgl_memo,jns_inv,no_invoice, inv_buyer, kepada, jns_trans,jns_pengiriman,ditagihkan,curr,jatuh_tempo,dok_pendukung,supplier,buyer,nm_ctg,nm_sub_ctg,biaya, cancel,notes, status,user, date_input,approved_by,approved_date,nama_pc from (select * from (select mdet.id id_det,a.id_h,a.nm_memo,a.tgl_memo,a.jns_inv,IF(mdet.inv_vendor is null,'-',mdet.inv_vendor) inv_buyer,a.kepada,a.jns_trans,a.jns_pengiriman,IF(a.ditagihkan != 'Y','TIDAK','YA') ditagihkan,a.curr,a.jatuh_tempo, a.dok_pendukung, ms.supplier supplier, mb.supplier buyer,mdet.nm_ctg,mdet.nm_sub_ctg,format(round(sum(mdet.biaya),2),2) biaya,mdet.cancel, IF(a.no_aju is null,'-',a.no_aju) no_aju, IF(a.notes is null,'-',a.notes) notes,a.status,a.user,a.date_input,a.id_supplier,a.id_buyer,a.approved_by,a.approved_date,mp.nama_pc from memo_h a
          inner join mastersupplier ms on a.id_supplier = ms.id_supplier
          inner join mastersupplier mb on a.id_buyer = mb.id_supplier
          inner join memo_det mdet on mdet.id_h = a.id_h
          left join master_pc mp on mp.kode_pc = a.profit_center
          where mdet.cancel != 'Y' GROUP BY mdet.id order by mdet.id_h asc) a left join      
(select a.id_h idh, GROUP_CONCAT(b.no_invoice) no_invoice from memo_h a inner join memo_inv b on b.id_h = a.id_h GROUP BY a.id_h) b on b.idh = a.id_h) a) a left join 
(select * from (select nm_memo nomemo from memo_h) nm left join (select nm_memo memo1, no_dn,tgl_dn,no_alk,tgl_alk from (select * from (select b.nm_memo, a.no_dn,a.tgl_dn from tbl_debitnote_h a INNER JOIN tbl_debitnote_det b on b.no_dn = a.no_dn where b.nm_memo like '%MEMO%' and a.status != 'CANCEL' GROUP BY b.nm_memo) a left JOIN
(select b.no_ref,a.no_alk,a.tgl_alk from tbl_alokasi a INNER JOIN tbl_alokasi_detail b on b.no_alk = a.no_alk where b.no_ref like '%DN%' and a.status != 'CANCEL' GROUP BY b.no_ref) b on b.no_ref = a.no_dn) a) a on a.memo1 = nm.nomemo LEFT JOIN
(select reff_doc memo2, no_pv,pv_date,no_bankout,bankout_date from (select * from (select b.reff_doc,a.no_pv,a.pv_date from tbl_pv_h a INNER JOIN tbl_pv b on b.no_pv =  a.no_pv where b.reff_doc like '%MEMO/%' and a.status != 'CANCEL' GROUP BY b.reff_doc) a LEFT JOIN
(select a.no_bankout,a.bankout_date,b.no_reff from b_bankout_h a INNER JOIN b_bankout_det b on b.no_bankout = a.no_bankout where b.no_reff like '%PV/%' and a.status != 'CANCEL') b on b.no_reff = a.no_pv) a) b on b.memo2 = nm.nomemo) b on b.nomemo = a.nm_memo where a.nm_memo >= 'MEMO/NAG/2310/01039')) a $where");
          

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_memo = date('d M Y', strtotime($data[tgl_memo]));
            $date_input = date('d M Y', strtotime($data[date_input]));
            $approveddate = $data[approved_date];
            $pvdate = $data[pv_date];
            $bankoutdate = $data[bankout_date];
            $tgldn = $data[tgl_dn];

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
            echo "<td>$data[nama_pc]</td>";
            echo "<td>$data[jns_inv]</td>";
            echo "<td>$data[no_invoice]</td>";
            echo "<td>$data[inv_buyer]</td>";
            echo "<td>$data[kepada]</td>";
            echo "<td>$data[jns_trans]</td>";
            echo "<td>$data[jns_pengiriman]</td>";
            echo "<td>$data[dok_pendukung]</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[ditagihkan]</td>";
            echo "<td>$data[jatuh_tempo]</td>";
            echo "<td>$data[nm_ctg]</td>";
            echo "<td>$data[nm_sub_ctg]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>$data[biaya]</td>";
            echo "<td>$data[no_aju]</td>";
            echo "<td>$data[notes]</td>";
            echo "<td>$data[status]</td>";
            echo "<td>$data[user]</td>";
            echo "<td>$date_input</td>";
            echo "<td>$data[approved_by]</td>";
            echo "<td>$approved_date</td>";
            echo "<td>$data[no_pv]</td>";
            echo "<td>$pv_date</td>";
            echo "<td>$data[no_bankout]</td>";
            echo "<td>$bankout_date</td>";
            echo "<td>$data[no_dn]</td>";
            echo "<td>$tgl_dn</td>";
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
