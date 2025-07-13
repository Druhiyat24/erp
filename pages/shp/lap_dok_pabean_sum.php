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
<?php if ($mod == "lap_dok_pabean") {

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_pilihan = $_POST['nama_pilihan'];
    $status = $_POST['status'];

    echo "<script>
    window.open ('?mod=lap_dok_pabean_exc&from=$from&to=$to&nama_pilihan=$nama_pilihan&status=$status&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_pilihan = $_POST['nama_pilihan'];
    $status = $_POST['status'];
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
          </div>


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
            <th>Jenis Dok</th>
            <th>No Daftar</th>
            <th>Tgl Daftar</th>
            <th>No Aju</th>
            <th>Tgl Aju</th>
            <th>Satuan Ceisa</th>
            <th>Satuan SB</th> 
            <th>Qty Ceisa</th>
            <th>Qty SB</th> 
            <th>Diff Qty</th>
            <th>Total Ceisa</th>
            <th>Total IDR</th>
            <th>Total SB</th>
            <th>Total SB IDR</th>
            <th>Diff Total</th>
            <th>Status</th>
            <th>Issue List</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          if($nama_pilihan == 'ALL' and $status == 'ALL'){
            $where = "where a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
            $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          }elseif($nama_pilihan != 'ALL' and $status == 'ALL'){
            $where = "where kode_dokumen = '$nama_pilihan' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
            $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          }elseif($nama_pilihan == 'ALL' and $status != 'ALL'){
            $where = "where status = '$status' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";
            $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          }else{
            $where = "where kode_dokumen = '$nama_pilihan' and status = '$status' and a.tgl_daftar >= '$from' and a.tgl_daftar <= '$to'";  
            $where2 = "bcdate >= '$from' and bcdate <= '$to'";
          }
          
          $sql = " select *, CASE
    -- 1. Semua sesuai
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ROUND(total, 2) = ROUND(COALESCE(total_sb, 0), 2)
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SESUAI'

    -- 2. TOTAL CEISA kosong tapi data lain sesuai
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ROUND(total, 2) = 0 AND ROUND(COALESCE(total_sb, 0), 2) > 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'TOTAL CEISA KOSONG'

    -- 3. Total tidak sesuai (lebih dari ±1000)
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND (diff_total > 1000 OR diff_total < -1000)
         AND diff_total != 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'TOTAL TIDAK SESUAI'

    -- 4. Total selisih pembulatan (kurang dari ±1000)
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ABS(diff_total) < 1000
         AND diff_total != 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SELISIH PEMBULATAN'

    -- 5. Satuan tidak sesuai tapi total & qty sama
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ROUND(total, 2) = ROUND(COALESCE(total_sb, 0), 2)
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SATUAN TIDAK SESUAI'

    -- 6. Satuan tidak sesuai + TOTAL CEISA kosong
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ROUND(total, 2) = 0 AND ROUND(COALESCE(total_sb, 0), 2) > 0
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SATUAN TIDAK SESUAI, TOTAL CEISA KOSONG'

    -- 7. Satuan tidak sesuai + total selisih pembulatan
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ABS(diff_total) < 1000 AND diff_total != 0
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SATUAN TIDAK SESUAI, SELISIH PEMBULATAN'

    -- 8. Satuan dan total tidak sesuai (selisih besar)
    WHEN ROUND(qty, 2) = ROUND(COALESCE(qty_sb, 0), 2)
         AND ABS(diff_total) > 1000 AND diff_total != 0
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SATUAN DAN TOTAL TIDAK SESUAI'

    -- 9. QTY selisih kecil + total sama + satuan sama
    WHEN ABS(diff_qty) < 1 AND diff_qty != 0
         AND ROUND(total, 2) = ROUND(COALESCE(total_sb, 0), 2)
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SELISIH PEMBULATAN'
     
     -- 10. QTY selisih kecil + total sama + satuan sama
    WHEN ABS(diff_qty) < 1 AND diff_qty != 0
         AND ABS(diff_total) < 1000
         AND diff_total != 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'SELISIH PEMBULATAN' 

    -- 11. QTY tidak sesuai + total sama
    WHEN ABS(diff_qty) >= 1
         AND ROUND(total, 2) = ROUND(COALESCE(total_sb, 0), 2)
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'QTY TIDAK SESUAI'

    -- 11. QTY tidak sesuai + total sama
    WHEN ABS(diff_qty) >= 1
         AND ABS(diff_total) < 1000
         AND diff_total != 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'QTY TIDAK SESUAI'

    -- 12. QTY tidak sesuai + TOTAL CEISA kosong
    WHEN ABS(diff_qty) >= 1
         AND ROUND(total, 2) = 0 AND ROUND(COALESCE(total_sb, 0), 2) > 0
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'QTY TIDAK SESUAI, TOTAL CEISA KOSONG'

    -- 13. QTY tidak sesuai + total tidak sesuai
    WHEN ABS(diff_qty) >= 1
         AND ROUND(total, 2) != ROUND(COALESCE(total_sb, 0), 2)
         AND (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'QTY DAN TOTAL TIDAK SESUAI'

    -- 14. QTY dan satuan tidak sesuai, total sama
    WHEN ABS(diff_qty) >= 1
         AND ROUND(total, 2) = ROUND(COALESCE(total_sb, 0), 2)
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
    THEN 'QTY DAN SATUAN TIDAK SESUAI'

    -- 15. QTY, satuan, dan total tidak sesuai
    WHEN ROUND(qty, 2) != ROUND(COALESCE(qty_sb, 0), 2)
         AND NOT (satuan_sb = satuan_ciesa OR satuan_ciesa REGEXP REPLACE(satuan_sb, ',', '|'))
         AND ROUND(total, 2) != ROUND(COALESCE(total_sb, 0), 2)
    THEN 'QTY, SATUAN DAN TOTAL TIDAK SESUAI'
END AS status_kesesuaian from (
          select kode_dokumen, no_aju, tgl_aju, a.no_daftar, a.tgl_daftar, ROUND(qty,2) qty, ROUND(total,2) total, ROUND(total_idr,2) total_idr, ROUND(COALESCE(qty_sb,0),2) qty_sb, ROUND(COALESCE(total_sb,0),2) total_sb, ROUND(COALESCE(total_sb_idr,0),2) total_sb_idr, ROUND(ROUND(qty,2) - ROUND(COALESCE(qty_sb,0),2),2) diff_qty, ROUND(ROUND(total,2) - ROUND(COALESCE(total_sb,0),2),2) diff_total, if(no_bpb is null,'-',no_bpb) no_bpb, IF(jenis_dok is null,'Not Updated','Updated') status, satuan_sb, satuan_sb_total, satuan_ciesa, satuan_ciesa_tampil, satuan_ciesa_total from (SELECT CONCAT('BC ',GROUP_CONCAT(SUBSTRING(kode_dokumen,n,1) ORDER BY n SEPARATOR '.')) AS kode_dokumen,nomor_aju,no_aju,tgl_aju,no_daftar,tgl_daftar,qty,satuan_ciesa, satuan_ciesa_tampil, satuan_ciesa_total,total,total_idr FROM (
          select kode_dokumen,nomor_aju,no_aju,tgl_aju,no_daftar,tgl_daftar,SUM(qty) AS qty,satuan_sb kode_satuan,SUM(total) AS total,SUM(total_idr) AS total_idr, GROUP_CONCAT(DISTINCT satuan_sb SEPARATOR ', ') AS satuan_ciesa, GROUP_CONCAT(DISTINCT kode_satuan SEPARATOR ', ') AS satuan_ciesa_tampil, GROUP_CONCAT(CONCAT(kode_satuan, ' (', round(qty,2), ')') SEPARATOR ', ') satuan_ciesa_total from (select a.*, b.satuan_sb from (SELECT kode_dokumen,nomor_aju,no_aju,tgl_aju,no_daftar,tgl_daftar,SUM(jumlah_satuan) AS qty,kode_satuan,SUM(cif) AS total,SUM(cif_rupiah) AS total_idr FROM ( SELECT a.*,kode_barang,uraian,jumlah_satuan,kode_satuan,cif,cif_rupiah,harga_satuan,price,price2 FROM ( SELECT kode_dokumen,nomor_aju,SUBSTRING(nomor_aju,-6) AS no_aju,DATE_FORMAT(STR_TO_DATE(SUBSTRING(nomor_aju,13,8),'%Y%m%d'),'%Y-%m-%d') AS tgl_aju,LPAD(nomor_daftar,6,0) AS no_daftar,tanggal_daftar AS tgl_daftar FROM exim_header) a LEFT JOIN ( SELECT nomor_aju,kode_barang,uraian,jumlah_satuan,kode_satuan,IF(LEFT(nomor_aju, 6) LIKE '%25%', harga_penyerahan, cif) cif,IF(LEFT(nomor_aju, 6) LIKE '%25%', harga_penyerahan, cif_rupiah) cif_rupiah,harga_satuan,ndpbm,(IF(LEFT(nomor_aju, 6) LIKE '%25%', harga_penyerahan, cif)/jumlah_satuan) AS price,((IF(LEFT(nomor_aju, 6) LIKE '%25%', harga_penyerahan, cif_rupiah)/ndpbm)/jumlah_satuan) AS price2 FROM exim_barang) b ON b.nomor_aju=a.nomor_aju) a GROUP BY a.no_daftar, a.nomor_aju, kode_satuan) a LEFT JOIN (select satuan_ceisa, GROUP_CONCAT(satuan_sb) satuan_sb from mapping_satuan_ceisa GROUP BY satuan_ceisa) b on b.satuan_ceisa = a.kode_satuan) a GROUP BY a.no_daftar, a.nomor_aju) a JOIN ( SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) numbers ON n<=LENGTH(a.kode_dokumen) GROUP BY no_daftar, nomor_aju ORDER BY nomor_aju asc) a 
          left join (
          select * from (select jenis_dok, nomor_aju, tanggal_aju, bcno, bcdate, GROUP_CONCAT(DISTINCT bpbno_int SEPARATOR ', ') no_bpb, GROUP_CONCAT(DISTINCT unit SEPARATOR ', ') AS satuan_sb, GROUP_CONCAT(CONCAT(unit, ' (', round(qty,2), ')') SEPARATOR ', ') satuan_sb_total, SUM(qty) qty_sb, sum(total) total_sb, sum(total * rate) total_sb_idr from (select jenis_dok, nomor_aju, tanggal_aju, bcno, bcdate, bpbno_int, unit, SUM(qty) qty, sum(qty * coalesce(ifnull(price_bc,price),0)) total, IF(rate is null,'1',rate) rate from bpb a left join (select tanggal, curr, rate from masterrate where v_codecurr = 'PAJAK' GROUP BY tanggal, curr ) cr on cr.tanggal = a.bpbdate and cr.curr = a.curr where $where2 and (bcno is not null and bcno not in ('','-')) GROUP BY bcno, jenis_dok, nomor_aju, unit) a GROUP BY bcno, jenis_dok, nomor_aju
          UNION
          select jenis_dok, nomor_aju, tanggal_aju, bcno, bcdate, GROUP_CONCAT(DISTINCT bppbno_int SEPARATOR ', ') no_bpb, GROUP_CONCAT(DISTINCT unit SEPARATOR ', ') AS satuan_sb, GROUP_CONCAT(CONCAT(unit, ' (', round(qty,2), ')') SEPARATOR ', ') satuan_sb_total, SUM(qty) qty_sb, sum(total) total_sb, sum(total * rate) total_sb_idr from (select jenis_dok, nomor_aju, tanggal_aju, bcno, bcdate, bppbno_int, unit, SUM(qty) qty, sum(qty * coalesce(ifnull(price_bc,price),0)) total, IF(rate is null,'1',rate) rate from bppb a left join (select tanggal, curr, rate from masterrate where v_codecurr = 'PAJAK' GROUP BY tanggal, curr ) cr on cr.tanggal = a.bppbdate and cr.curr = a.curr where $where2 and (bcno is not null and bcno not in ('','-')) GROUP BY bcno, jenis_dok, nomor_aju, unit) a GROUP BY bcno, jenis_dok, nomor_aju
        ) a GROUP BY bcno, jenis_dok) b on b.nomor_aju = a.no_aju and b.bcno = a.no_daftar and b.jenis_dok = a.kode_dokumen) a $where";



        // echo $sql;
        $query = mysql_query($sql);



        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $tgl_aju = date('d M Y', strtotime($data[tgl_aju]));
          $tgl_daftar = date('d M Y', strtotime($data[tgl_daftar]));
          $status = $data[status];

          $satuan_sb = str_replace(" ", "<br>", $data[satuan_sb]);
          $satuan_ciesa = str_replace(" ", "<br>", $data[satuan_ciesa_tampil]);
          $ciesa_total = str_replace(",", "<br>", $data[satuan_ciesa_total]);
          $sb_total = str_replace(",", "<br>", $data[satuan_sb_total]);

          if ($status == 'Updated' ) {
            $tr_style = "style='color: green;'";
          }else{
            $tr_style = "style='color: red;'";
          }

          echo "<tr $tr_style>";
          echo "<td>$no</td>";
          echo "<td>$data[kode_dokumen]</td>";
          echo "<td>$data[no_daftar]</td>";
          echo "<td>$tgl_daftar</td>";
          echo "<td>$data[no_aju]</td>";
          echo "<td>$tgl_aju</td>";
          echo "<td>$satuan_ciesa</td>";
          echo "<td>$satuan_sb</td>";
          echo "<td style='text-align: right;'>".number_format($data['qty'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['qty_sb'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['diff_qty'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['total'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['total_idr'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['total_sb'],2)."</td>";
           echo "<td style='text-align: right;'>".number_format($data['total_sb_idr'],2)."</td>";
          echo "<td style='text-align: right;'>".number_format($data['diff_total'],2)."</td>";
          echo "<td>$data[status]</td>";
          echo "<td>$data[status_kesesuaian]</td>";
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
