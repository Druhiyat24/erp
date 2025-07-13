
<?php 
include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_AR_UAN_MUK_PEL","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
$period = $_GET['txtfrom'];

?>

<div class="container-fluid">

  <section class="content">

    <div class="box">

      <div class="box-body"> 

        <div class="row">

          <form method="get" name="form" action=""> 

            <input type="hidden" name="mod" value="UMPelanggan" />

            <div class="col-md-3">

              <div class="form-group">

                <label>PER *</label>

                <input type="text" class="form-control" id="datepicker1" name="txtfrom" placeholder="Masukkan Dari Tanggal" autocomplete="off">

              </div>

              <button type="submit" name="submit" class="btn btn-primary">Tampilkan</button>

            </div>

          </form>

        </div>

      </div>

    </div>  

    <!-- /.content -->

    <div class="box">

      <div class="box-body">

        <table class="display responsive" style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

          <thead>

            <tr>

              <th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT. Nirwana Alabare Garment</strong></th>

            </tr>

            <tr>

              <th style="font-size: 18px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN UANG MUKA PELANGGAN</strong></th>

            </tr>

            <tr>

              <th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PER: <?php echo $period; ?></strong></th>

            </tr>

          </thead>

        </table>

        <br />

        <table id="examplefix"  class="display responsive" style="width:auto;font-size:12px;" width="100%" >

          <thead>

           <tr>
            <th rowspan="2">NO.</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">NOMOR BUKTI</th>
            <th colspan="3">CHART OF ACCOUNT KONSUMEN</th>
            <th rowspan="2">DESCRIPTIONS</th>
            <th colspan="2">SO</th>
            <th colspan="2">SURAT JALAN</th>
            <th rowspan="2">&nbsp;</th>
            <th>PENGURANGAN</th>
            <th>PENAMBAHAN</th>
            <th>SALDO</th>
          </tr>
          <tr>
            <th>NOMOR</th>
            <th>ID KONSUMEN</th>
            <th>NAMA</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>RUPIAH</th>
            <th>RUPIAH</th>
            <th>RUPIAH</th>
          </tr>

        </thead>

        <tbody>

          <?php  if(isset($_GET['submit'])){  ?>

            <?php 

            if ($period <> ''){

              $period= str_replace('/', '-', $period);

            }

            $sql = "
            
            SELECT a.invdate
            ,   a.invno
            ,   d.so_no  
            ,   d.so_date
            ,   e.post_to
            ,   f.supplier_code
            ,   f.Id_Supplier
            ,   f.Supplier
            ,   c.notes
            ,   i.n_saldo
            ,   j.bpbdate
            ,   j.invno invno_bpb
            ,   h.debit
            ,   h.credit
            ,   SUM(h.debit)suum

            FROM invoice_header a
            LEFT JOIN invoice_detail b ON b.id_inv=a.id
            INNER JOIN (SELECT id_journal, reff_doc, fg_post, type_journal from fin_journal_h WHERE fg_post='2' AND type_journal='1' )g ON g.reff_doc=a.invno
            LEFT JOIN fin_journal_d h ON h.id_journal=g.id_journal
            LEFT JOIN so_det c ON c.id=b.id_so_det
            LEFT JOIN so d ON d.id=c.id_so
            LEFT JOIN mastercoa e ON e.id_coa=h.id_coa
            LEFT JOIN mastersupplier f ON f.Id_Supplier=a.id_buyer
            LEFT JOIN fin_history_saldo i ON i.n_idcoa=e.id_coa
            LEFT JOIN bpb j ON j.bpbno_int=g.reff_doc
            LEFT JOIN
                (SELECT id_journal
                      ,type_journal
                      ,fg_tax
                      ,fg_post
                      ,n_pph
                      ,date_journal
                      ,reff_doc FROM fin_journal_h WHERE type_journal='13' AND fg_post='2'
                UNION ALL
                SELECT id_journal
                      ,type_journal
                      ,fg_tax
                      ,fg_post
                      ,n_pph
                      ,date_journal
                      ,reff_doc FROM fin_journal_h WHERE type_journal='4' AND fg_post='2')k ON k.reff_doc=a.invno
                WHERE h.debit > 0 AND a.invno IS NOT NULL AND e.id_coa NOT IN('15204','15207') GROUP BY a.invno ORDER BY f.Id_Supplier, a.invdate, a.invno ASC;

            ";        
      // echo "<pre>$sql</pre>";
            $stmt = mysql_query($sql);  

            ?>

            <?php

            while($data = mysql_fetch_array($stmt))
   
            { 

              echo "<tr>";

              echo "<td></td>";
              echo "<td>$data[invdate]</td>"; // COA post to
              echo "<td>$data[invno]</td>"; // kode supplier
              echo "<td>$data[post_to]</td>"; // Nama Supplier
              echo "<td>$data[supplier_code]</td>"; // days payment terms
              echo "<td>$data[Supplier]</td>"; // days payment terms
              echo "<td>$data[notes]</td>"; // days payment terms
              echo "<td>$data[so_date]</td>"; // days payment terms
              echo "<td>$data[so_no]</td>"; // days payment terms
              echo "<td>$data[bpbdate]</td>"; // days payment terms
              echo "<td>$data[invno_bpb]</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[n_saldo]"))."</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[n_saldo]-$data[debit]"))."</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[n_saldo]-$data[debit]+$data[credit]"))."</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[suum]"))."</td>"; // days payment terms
              
      
        echo "</tr>"
        ?>
      <?php } //end loop table?>


      <!-- <?php } //end if submit ?> -->

    </tbody> 

    <tfoot>
      <?php echo "
      <tr>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      
      </tr> ";
      ?>

    </tfoot>

  </table>

</div>

</div>

</section>

</div>
