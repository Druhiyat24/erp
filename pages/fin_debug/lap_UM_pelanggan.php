
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

        <table id="examplefix_um"  rules="all" class="" style="width:auto;font-size:12px;" width="100%" >

          <thead>

           <tr>
          <!--   <th rowspan="2">NO.</th> -->
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">NOMOR BUKTI</th>
            <th style="text-align: center;" colspan="3">CHART OF ACCOUNT KONSUMEN</th>
            <th rowspan="2">DESCRIPTIONS</th>
            <th style="text-align: center;" colspan="2">SO</th>
            <th style="text-align: center;" colspan="2">SURAT JALAN</th>
            <!-- <th rowspan="2">&nbsp; SALDO</th> -->
            <th>PENGURANGAN</th>
            <th>PENAMBAHAN</th>
            <!-- <th>SALDO</th> -->
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
          <!--   <th>RUPIAH</th> -->
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
            ,ifnull(if(e.id_coa='13001','25002','25001'),'N/A')id_coa_convert
                        ,g.id_journal
            ,   h.debit
            ,   h.credit
            ,ifnull(if(h.debit>0,(i.n_saldo+h.debit),(i.n_saldo-h.credit)),'-')jumlah_saldo
            ,ifnull(if(h.curr='USD',(h.debit*mr.rate),h.debit),h.debit)jumlah_debit
            ,ifnull(if(h.curr='USD',(h.credit*mr.rate),h.credit),h.credit)jumlah_credit
            ,   e.id_coa
            ,mr.rate
        ,   mr.v_codecurr
            ,  mr.curr
            ,  h.curr
            -- ,   e.post_to
            ,ifnull(if(f.supplier_code='','N/A',f.supplier_code),'N/A')supplier_code
            -- ,   f.supplier_code
            ,   f.Id_Supplier
            ,   f.Supplier
            ,ifnull(if(c.notes='','N/A',c.notes),'N/A')notes
            -- ,   c.notes

            
            ,   i.n_saldo
            -- ,   j.bpbdate
            ,ifnull(if(j.bpbdate='','N/A',j.bpbdate),'N/A')bpbdate
            ,ifnull(if(j.invno='','N/A',j.invno),'N/A')invno_bpb
            -- ,   j.invno invno_bpb

        ,   SUM(h.debit)suum
        

            FROM invoice_header a
            LEFT JOIN invoice_detail b ON b.id_inv=a.id
            INNER JOIN (SELECT id_journal, reff_doc,date_journal, fg_post, type_journal from fin_journal_h WHERE fg_post='2' AND type_journal='1' )g ON g.reff_doc=a.invno
            LEFT JOIN (SELECT id,v_codecurr,curr,rate,tanggal from masterrate WHERE v_codecurr='PAJAK' ) mr ON mr.tanggal = g.date_journal
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
                      ,reff_doc FROM fin_journal_h k WHERE type_journal='13' AND fg_post='2'
                UNION ALL
                SELECT id_journal
                      ,type_journal
                      ,fg_tax
                      ,fg_post
                      ,n_pph
                      ,date_journal
                      ,reff_doc FROM fin_journal_h
               WHERE type_journal='4' AND fg_post='2')k ON k.reff_doc=a.invno
        
            WHERE h.debit > 0 AND a.invno IS NOT NULL AND d.so_no NOT IN ('SO/0120/00077','SO/0220/00195','SO/1119/00845')
           AND e.id_coa NOT IN('15204','15207') 
          
           GROUP BY a.invno 
           ORDER BY  a.invdate,f.Id_Supplier, a.invno ASC;
           

            ";        
      // echo "<pre>$sql</pre>";
            $stmt = mysql_query($sql);  

            ?>

            <?php

            while($data = mysql_fetch_array($stmt))
   
            { 

              echo "<tr>";

              // echo "<td></td>";
              echo "<td >$data[invdate]</td>"; // COA post to
              echo "<td>$data[id_journal]</td>"; // kode supplier
              echo "<td style='text-align:center;'>$data[id_coa_convert]</td>"; // Nama Supplier
              echo "<td style='text-align:center;'>$data[supplier_code]</td>"; // days payment terms
              echo "<td>$data[Supplier]</td>"; // days payment terms
              echo "<td style='text-align:center;'>$data[notes]</td>"; // days payment terms
              echo "<td>$data[so_date]</td>"; // days payment terms
              echo "<td>$data[so_no]</td>"; // days payment terms
              echo "<td style='text-align:center;'>$data[bpbdate]</td>"; // days payment terms
              echo "<td style='text-align:center;'>$data[invno_bpb]</td>"; // days payment terms
              //echo "<td style='text-align:right;'>".(number_format("$data[n_saldo]"))."</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[jumlah_credit]"))."</td>"; // days payment terms
              echo "<td style='text-align:right;'>".(number_format("$data[jumlah_debit]"))."</td>"; // days payment terms
              //echo "<td style='text-align:right;'>".(number_format("$data[jumlah_saldo]"))."</td>"; // days payment terms
              
      
        echo "</tr>"
        ?>
      <?php } //end loop table?>


      <!-- <?php } //end if submit ?> -->

    </tbody> 

    

  </table>

</div>

</div>

</section>

</div>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#examplefix_um').DataTable({
'serverside' : true,
'processing' : true,
'scrollX'    : true,
'serverMethod': 'post',
// 'pageLength' : 10,   

dom: 'Bfrtip',
        buttons: [  
            { 
              extend: 'excel', 
              text: 'Export to Excel',
              // message: "Periode "" Sampai "" \n",
              message: "Periode <?php echo $period; ?> ",
            // exportOptions:{
            //   search :'applied',
            //   order:'applied'
            },
        ], 
        dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
});
})    
</script>



