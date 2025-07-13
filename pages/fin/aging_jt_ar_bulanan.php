
<?php 

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }



$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_Umur_AR","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI
$period = $_GET['txtfrom'];

?>

<div class="container-fluid">

  <section class="content">

    <div class="box">

      <div class="box-body"> 

        <div class="row">

          <form method="get" name="form" action=""> 

            <input type="hidden" name="mod" value="agingARjatuhtempo" />

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

    

    <div class="box">

      <div class="box-body">

        <table class="" style="font-size:11px; overflow-x: scroll; border-collapse:  border: 1;">

          <thead>

            <tr>

              <th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT. Nirwana Alabare Garment</strong></th>

            </tr>

            <tr>

              <th style="font-size: 18px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN AGING AR DAN JATUH TEMPO</strong></th>

            </tr>

            <tr>

              <th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PER: <?php echo $period; ?></strong></th>

            </tr>

          </thead>

        </table>

        <br />

        <table id="examplelaporanaging" rules="all" class="" style="font-size:12px;height: 400px;" >

          <thead>

            <tr>

              <th rules="none" colspan="14"></th>

              <!-- <th colspan="9" style="text-align: center;">JATUH TEMPO PIUTANG</th> -->
              <th colspan="4" style="text-align: center;">JATUH TEMPO PIUTANG</th>

            </tr>

            <tr style="text-align: center;">

              <!-- <th style="text-align: center;" rowspan="2"></th> -->

              <th style="text-align: center;" colspan="3">KONSUMEN</th>

              <th rowspan="2"  style="border-right: double; text-align: center;">TOP</th>

              <th rowspan="2"  style="text-align: center;">ID Supplier</th>

              <th style="text-align: center;" rowspan="2"> TOTAL</th>

              <th style="text-align: center;" rowspan="2">M-&ge; 6</th>

              <th style="text-align: center;" rowspan="2">M-5</th>

              <th style="text-align: center;" rowspan="2">M-4</th>

              <th style="text-align: center;" rowspan="2">M-3</th>

              <th style="text-align: center;" rowspan="2">M-2</th>

              <th style="text-align: center;" rowspan="2">M-1</th>

              <th style="text-align: center;" rowspan="2">BULAN BERJALAN</th>

              <th rowspan="2" style="border-right: double; text-align: center;">AR DAYS</th>

              <th style="text-align: center;" rowspan="2">1-30 H</th>

              <th style="text-align: center;" rowspan="2">31-60 H</th>

              <th style="text-align: center;" rowspan="2">61-90 H</th>

              <th style="text-align: center;" rowspan="2"> > 90 H</th>

            </tr>

            <tr>

              <th style="text-align: center; ">COA</th>

              <th style="text-align: center;">ID</th>

              <th style="text-align: center;">NAMA</th>

            </tr>

          </thead>

          <tbody>

            <?php  if(isset($_GET['submit'])){  ?>

              <?php 

              if ($period <> ''){

                $period= str_replace('/', '-', $period);

              }

              $sql = "
              SELECT  
   MASTER.invno
    ,MASTER.invdate
    ,MASTER.days
    ,MASTER.tgl_jatem_invoice
   ,MASTER.umur_piutang   
    ,MASTER.Id_Supplier
    ,MASTER.Supplier
    ,MASTER.post_to
    ,MASTER.supplier_code
    ,MASTER.invoice_amount
   ,MASTER.terbayar
    ,MASTER.curr
    ,SUM(MASTER.nilai_1_30)nilai_1_30
    ,SUM(MASTER.nilai_31_60)nilai_31_60
    ,SUM(MASTER.nilai_61_90)nilai_61_90
    ,SUM(MASTER.nilai_91_120)nilai_91_120
    ,SUM(MASTER.nilai_121_150)nilai_121_150 
    ,SUM(MASTER.nilai_151_180)nilai_151_180
    ,SUM(MASTER.nilai_180)nilai_180
    ,MASTER.tes
    ,SUM(MASTER.jt1_30)jt1_30
    ,SUM(MASTER.jt31_60)jt31_60
    ,SUM(MASTER.jt61_90)jt61_90
    ,SUM(MASTER.jt90)jt90
FROM (SELECT 
Z.invdate
, Z.umur_piutang
, Z.tgl_jatem_invoice
,   Z.terbayar
, Z.curr
, Z.jatuh_tempo_inv  
, Z.id_journal 
, Z.post_to
,  Z.invno
, Z.Id_Supplier
, Z.Supplier
, Z.supplier_code
, Z.days
,  Z.invoice_amount
, Z.nilai_1_30
, Z.nilai_31_60
, Z.nilai_61_90
, Z.nilai_91_120
, Z.nilai_121_150   
, Z.nilai_151_180   
, Z.nilai_180    
, Z.jt1_30
, Z.jt31_60
, Z.jt61_90
, Z.jt90
, Z.tes

FROM (SELECT
Y.invdate
, Y.umur_piutang
, Y.tgl_jatem_invoice
,   Y.terbayar
, Y.curr
, Y.jatuh_tempo_inv  
, Y.id_journal 
, Y.post_to
,  Y.invno
, Y.Id_Supplier
, Y.Supplier
, Y.supplier_code
, Y.days
,  Y.invoice_amount
, IF(Y.umur_piutang >= 1 AND Y.umur_piutang < 30,Y.terbayar,0)nilai_1_30
, IF(Y.umur_piutang >= 31 AND Y.umur_piutang < 60,Y.terbayar,0)nilai_31_60
, IF(Y.umur_piutang >= 61 AND Y.umur_piutang < 90,Y.terbayar,0)nilai_61_90
, IF(Y.umur_piutang >= 91 AND Y.umur_piutang < 120,Y.terbayar,0)nilai_91_120
, IF(Y.umur_piutang >= 121 AND Y.umur_piutang < 150,Y.terbayar,0)nilai_121_150   
, IF(Y.umur_piutang >= 151 AND Y.umur_piutang < 180,Y.terbayar,0)nilai_151_180   
, IF(Y.umur_piutang >= 180,Y.terbayar,0)nilai_180    
, IF(DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) >= 1 
    AND DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) <= 30,Y.terbayar,0)jt1_30
, IF(DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) >= 31 
    AND DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) <= 60,Y.terbayar,0)jt31_60
, IF(DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) >= 61 
    AND DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) <= 90,Y.terbayar,0)jt61_90
, IF(DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice) >= 91,Y.terbayar,0)jt90
, DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),Y.tgl_jatem_invoice)tes

FROM (SELECT
X.invdate
, X.umur_piutang
, X.tgl_jatem_invoice
,   X.terbayar
, X.curr
, X.jatuh_tempo_inv  
, X.id_journal 
, X.post_to
,  X.invno
, X.Id_Supplier
, X.Supplier
, X.supplier_code
, X.days
,  (SUM(X.invoice_amount))invoice_amount

FROM (SELECT
    a.debit terbayar
    , DATEDIFF(STR_TO_DATE('$period','%d %b %Y'),d.invdate)umur_piutang
    , IFNULL(ADDDATE(d.invdate,IFNULL(e.days_pterms,0)),'-')tgl_jatem_invoice
    , IFNULL(SUBDATE((ADDDATE(d.invdate,e.days_pterms)),STR_TO_DATE('$period','%d %b %Y')),'0000-00-00')jatuh_tempo_inv  
    , a.id_journal
    , a.curr 
    , c.post_to
    ,  d.invno
    ,   d.invdate
    , f.Id_Supplier
    , f.Supplier
    , f.supplier_code
    , e.days_pterms days
    ,  (g.qty * g.price) invoice_amount
    FROM fin_journal_d a
    LEFT JOIN (SELECT id_journal
          ,type_journal
          ,fg_tax
          ,fg_post
          ,n_pph
          ,date_journal
          ,reff_doc FROM fin_journal_h WHERE type_journal='1' AND fg_post='2')b ON a.id_journal=b.id_journal
    LEFT JOIN mastercoa c ON c.id_coa=a.id_coa
    LEFT JOIN invoice_header d ON d.invno=b.reff_doc
    LEFT JOIN invoice_detail g ON g.id_inv=d.id
    LEFT JOIN masterpterms e ON e.id=d.id_pterms
    LEFT JOIN mastersupplier f ON f.Id_Supplier=d.id_buyer
    LEFT JOIN 
    (SELECT id_journal
          ,type_journal
          ,fg_tax
          ,fg_post
          ,n_pph
          ,date_journal
          ,reff_doc FROM fin_journal_h WHERE type_journal='4' AND fg_post='2'
    UNION ALL
    SELECT id_journal
          ,type_journal
          ,fg_tax
          ,fg_post
          ,n_pph
          ,date_journal
          ,reff_doc FROM fin_journal_h WHERE type_journal='13' AND fg_post='2')h ON h.reff_doc=d.invno
    WHERE a.debit > 0 AND d.invno IS NOT NULL AND a.id_coa NOT IN('15204','15207')  GROUP BY d.invno)X 
          GROUP BY X.invno 
          ORDER BY X.Id_Supplier)Y
              GROUP BY Y.invno
              ORDER BY Y.Id_Supplier)Z
                GROUP BY Z.invno
                ORDER BY Z.Id_Supplier)MASTER
                  GROUP BY MASTER.invno
                  ORDER BY MASTER.invno
";        
      //echo "<pre>$sql</pre>";
        $stmt = mysql_query($sql); 
        
        ?>

        <?php
        $total_180 = 0;
        $total_151_180 = 0;
        $total_121_150 = 0;
        $total_91_120 = 0;
        $total_61_90 = 0;
        $total_31_60 = 0;
        $total_1_30 = 0;


        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
      

        while($data = mysql_fetch_array($stmt))
        // rumus perhitungan ap = 365/(total utang)/ (rata rata utang)
                { 
                  $total_piutang = 0; 

                  $total_180 = $total_180 +$data['nilai_180'];
                  $total_151_180 = $total_151_180 +$data['nilai_151_180'];
                  $total_121_150 = $total_121_150 +$data['nilai_121_150'];
                  $total_91_120 = $total_91_120 +$data['nilai_91_120'];
                  $total_61_90 = $total_61_90 +$data['nilai_61_90'];
                  $total_31_60 = $total_31_60 +$data['nilai_31_60'];
                  $total_1_30 = $total_1_30 +$data['nilai_1_30'];

                  $total1 = $total1 +$data['invoice_amount'];
                  
                  $total3 = $total3 +$data['jt1_30'];
                  $total4 = $total4 +$data['jt31_60'];
                  $total5 = $total5 +$data['jt61_90'];
                  $total6 = $total6 +$data['jt90'];
                  
                  $total_piutang = $data['nilai_180']+$data['nilai_151_180']+$data['nilai_121_150']+$data['nilai_91_120']+$data['nilai_61_90']+$data['nilai_31_60']+$data['nilai_1_30'];
                  $ar_days = 0;
                  if($data['invoice_amount'] != '0'){
                  $ar_days = ((365/$total_piutang)/$data['invoice_amount']);
                  }else{
                    $ar_days = 0;
                  }
                  
                  echo "<tr>";

              // echo "<td></td>";
              echo "<td style='text-align: right;'>$data[post_to]</td>"; // COA post to
              echo "<td style='text-align: right;'>$data[supplier_code]</td>"; // kode supplier
              echo "<td style='text-align: right;'>$data[Supplier]</td>"; // Nama Supplier
              echo "<td style='text-align: right;'>$data[days] days</td>"; // days payment terms
              echo "<td style='text-align: right;'>$data[Id_Supplier]</td>"; // days payment terms
              echo "<td style='text-align: right;'>".(number_format("$data[invoice_amount]",2))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_180]",2))."</td>"; //120
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_151_180]",2))."</td>"; //91-121
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_121_150]",2))."</td>"; //61-91
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_91_120]",2))."</td>"; //31-61
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_61_90]",2))."</td>"; //1-31
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_31_60]",2))."</td>"; //1-31
        echo "<td style='text-align: right;'>".(number_format("$data[nilai_1_30]",2))."</td>"; //1-31        
        // echo "<td style='text-align: right;'>".(number_format("$data[ar_days]",2))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format($ar_days  ,4,'.','.'))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format("$data[jt1_30]",2))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format("$data[jt31_60]",2))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format("$data[jt61_90]",2))."</td>"; //TOTAL
        echo "<td style='text-align: right;'>".(number_format("$data[jt61_90]",2))."</td>"; //TOTAL

              echo "</tr>";
      $total2 = $total2 +$ar_days;
      ?>
        <?php } //end loop table?>


    <!-- <?php } //end if submit ?> -->

  </tbody> 



</table>

</div>

</div>

</section>

</div>
<link rel="stylesheet" href="./css/tab.css"> 
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#examplelaporanaging').DataTable({
        
        // 'processing': true,
        'serverside' : true,
        // 'responsive': true,
        'scrollX' : true,
        "lengthMenu": [10, 25, 50, "All"],
        // "columnDefs": [
        //     { "visible": false, "targets": 5 }
        // ],
        // "order": [[ 2, 'asc' ]],
        // "displayLength": 10,
        dom: 'Bfrtip',
        buttons: [
            { 
              extend: 'excel', 
              text: 'Export to Excel',
              message: "Periode <?php echo $period; ?>",
            },
        ], 
        dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",


        // "drawCallback": function ( settings ) {
        //     var api = this.api();
        //     var rows = api.rows( {page:'current'} ).nodes();
        //     var last=null;
        //     // var subTotal_satutigasatu = new Array();
        //     var groupID = -1;
        //     var aData = new Array();
        //     var index = 0;
            
        //     api.column(5, {page:'current'} ).data().each( function ( group, i ) {
              
        //       // console.log(group+">>>"+i);
            
        //       var vals = api.row(api.row($(rows).eq(i)).index()).data();
        //         vals_6 =    vals[6].replace(/,/g, '').toString();
        //         vals_7 =    vals[7].replace(/,/g, '').toString(); 
        //         vals_8 =    vals[8].replace(/,/g, '').toString();
        //         vals_9 =    vals[9].replace(/,/g, '').toString();
        //         vals_10 =     vals[10].replace(/,/g, '').toString(); 
        //         vals_11 =     vals[11].replace(/,/g, '').toString();               
        //         vals_12 =     vals[12].replace(/,/g, '').toString();               
        //         vals_13 =     vals[13].replace(/,/g, '').toString();

        //         vals_15 =     vals[15].replace(/,/g, '').toString();
        //         vals_16 =     vals[16].replace(/,/g, '').toString();
        //         vals_17 =     vals[17].replace(/,/g, '').toString();
        //         vals_18 =     vals[18].replace(/,/g, '').toString();

        //       var invoice_amount = vals[6] ? parseFloat(vals_6) : 0;
        //       var nilai_180 = vals[7] ? parseFloat(vals_7) : 0;
        //       var nilai_151_180 = vals[8] ? parseFloat(vals_8) : 0;
        //       var nilai_121_150 = vals[9] ? parseFloat(vals_9) : 0; 
        //       var nilai_91_120 = vals[10] ? parseFloat(vals_10) : 0;
        //       var nilai_61_90 = vals[11] ? parseFloat(vals_11) : 0;
        //       var nilai_31_60 = vals[12] ? parseFloat(vals_12) : 0;
        //       var nilai_1_30 = vals[13] ? parseFloat(vals_13) : 0;

        //       var jt1_31 = vals[15] ? parseFloat(vals_15) : 0;
        //       var jt31_61 = vals[16] ? parseFloat(vals_16) : 0;
        //       var jt61_91 = vals[17] ? parseFloat(vals_17) : 0;
        //       var jt90 = vals[18] ? parseFloat(vals_18) : 0;

               
        //       if (typeof aData[group] == 'undefined') {
        //          aData[group] = new Array();
        //          aData[group].rows = [];
        //          aData[group].nilai_1_30 = [];
        //          aData[group].invoice_amount = []; 
        //          aData[group].nilai_31_60 = []; 
        //          aData[group].nilai_61_90 = []; 
        //          aData[group].nilai_91_120 = []; 
        //          aData[group].nilai_121_150 = []; 
        //          aData[group].nilai_151_180 = []; 
        //          aData[group].nilai_180 = []; 
        //          aData[group].jt1_31 = []; 
        //          aData[group].jt31_61 = []; 
        //          aData[group].jt61_91 = []; 
        //          aData[group].jt90 = []; 
                 
        //       }
            
        //       aData[group].rows.push(i); 
        //       aData[group].nilai_1_30.push(nilai_1_30); 
        //       aData[group].invoice_amount.push(invoice_amount); 
        //       aData[group].nilai_31_60.push(nilai_31_60); 
        //       aData[group].nilai_61_90.push(nilai_61_90); 
        //       aData[group].nilai_91_120.push(nilai_91_120); 
        //       aData[group].nilai_121_150.push(nilai_121_150); 
        //       aData[group].nilai_151_180.push(nilai_151_180); 
        //       aData[group].nilai_180.push(nilai_180); 

        //       aData[group].jt1_31.push(jt1_31); 
        //       aData[group].jt31_61.push(jt31_61); 
        //       aData[group].jt61_91.push(jt61_91); 
        //       aData[group].jt90.push(jt90); 
                
        //     } );
    

        //     var idx= 0;

      
        //     for(var supplier in aData){
       
        //            idx =  Math.max.apply(Math,aData[supplier].rows);
      
        //            var sum = 0; 
        //            $.each(aData[supplier].nilai_1_30,function(k,v){
        //                 sum = sum + v;
        //            });      

        //            var sum_total = 0; 
        //            $.each(aData[supplier].invoice_amount,function(k,v){
        //                 sum_total = sum_total + v;
        //            });
            
        //            var sum_dua = 0; 
        //            $.each(aData[supplier].nilai_31_60,function(k,v){
        //                 sum_dua = sum_dua + v;
        //            });
                        
        //            var sum_tiga = 0; 
        //            $.each(aData[supplier].nilai_61_90,function(k,v){
        //                 sum_tiga = sum_tiga + v;
        //            });
                                    
        //            var sum_empat = 0; 
        //            $.each(aData[supplier].nilai_91_120,function(k,v){
        //                 sum_empat = sum_empat + v;
        //            });
                                                
        //            var sum_lima = 0; 
        //            $.each(aData[supplier].nilai_121_150,function(k,v){
        //                 sum_lima = sum_lima + v;
        //            });

        //            var sum_limas = 0; 
        //            $.each(aData[supplier].nilai_151_180,function(k,v){
        //                 sum_limas = sum_limas + v;
        //            });
                                                
        //            var sum_senam = 0; 
        //            $.each(aData[supplier].nilai_180,function(k,v){
        //                 sum_senam = sum_senam + v;
        //            });
 
                                                
        //            var sum_enam = 0; 
        //            $.each(aData[supplier].jt1_31,function(k,v){
        //                 sum_enam = sum_enam + v;
        //            });
                                      
        //            var sum_tujuh = 0; 
        //            $.each(aData[supplier].jt31_61,function(k,v){
        //                 sum_tujuh = sum_tujuh + v;
        //            });      
                                      
        //            var sum_lapan = 0; 
        //            $.each(aData[supplier].jt61_91,function(k,v){
        //                 sum_lapan = sum_lapan + v;
        //            });
                                      
        //            var sum_mbilan = 0; 
        //            $.each(aData[supplier].jt90,function(k,v){
        //                 sum_mbilan = sum_mbilan + v;
        //            });
                    
        //            $(rows).eq( idx ).after(
        //                 '<tr class="group" style="background-color: #efefef" colspan="19"><td >&nbsp;</td>'+'</tr>'
        //             );
                    
        //     };

        // }
    } );

} );


</script>