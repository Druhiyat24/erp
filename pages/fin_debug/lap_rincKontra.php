<?php
include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_AP_RIN_KON_BON","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

?>

<div class="container-fluid">

  <section class="content">

    <div class="box">

      <div class="box-body">

        <form method='get' name='form' enctype='multipart/form-data' action="">

          <div class="panel panel-default">

            <div class="panel-heading">Periode Journal</div>

            <div class="panel-body row">

              <div class="col-md-3">

                <div class='form-group'>

                  <label for="period_from">Dari</label>



                  <input type='text' id="datepicker1" class='form-control datepicker' name='period_from'

                  placeholder='DD/MM/YYYY' value='<?=isset($period_from)?$period_from:''?>' autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class='form-group'>

                  <label>Sampai</label>

                  <input type='text' id="datepicker2" class='form-control datepicker' name='period_to'

                  placeholder='DD/MM/YYYY' value='<?=isset($period_to)?$period_to:''?>' autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class="form-group"> 

                  <input type="hidden" name="mod" value="rikontrabon" />
                  <label>&nbsp;</label><br>
                  <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>

                </div>

              </div>

            </div>

          </div>

        </form>
<div class="clearfix"></div>
       <table rules="all" id="examplefixrkb" class="" style="width:100%;">
          <thead>
           <tr border=1>
            <!-- <th rowspan="2" style="" >No.</th>
 -->            <th colspan="4" style="text-align: center;">SUPPLIER</th>
            <th rowspan="2">TOP</th>
            <th rowspan="2">PERIODE TAGIHAN</th>
            <th colspan="2" style="text-align: center;">KONTRABON</th>
            <th colspan="2" style="text-align: center;">INVOICE</th>
            <th colspan="2" style="text-align: center;">SURAT JALAN</th>
            <th rowspan="2">TOTAL RUPIAH</th>
            <th rowspan="2">KETERANGAN</th>
          </tr>
          <tr>
            <th>COA</th>
            <th>ID</th>
            <th>NAMA</th>
            <th>NAMA ALIAS</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
          </tr>

        </thead>

        <tbody>

          <?php

          if (isset($_GET['submit'])){

            $from = $_GET['period_from'];

            $from = str_replace('/', '-', $from);

            $from = date("Y-m-d", strtotime($from));
           

            $to = $_GET['period_to'];

            $to = str_replace('/', '-', $to);  

            $to = date("Y-m-d", strtotime($to));   

            $sql = "SELECT  mc.id_coa
            , ms.Id_Supplier
            , ms.Supplier
            , ms.supplier_code
            , mpt.days_pterms  
            , mpt.nama_pterms
            , fjh.period
            , fjh.date_journal
            , fjh.id_journal
            -- , fjh.d_invoice 
            -- , ifnull(fjh.d_invoice,'-')d_invoice
            ,ifnull(if(fjh.d_invoice = '1970-01-01','N/A',fjh.d_invoice),'-')d_invoice
            -- , fjh.inv_supplier
            , fjd.amount_original
            ,IF(fjd.curr='USD',(fjd.amount_original*mr.rate),fjd.amount_original)total_fix
            , b.bpbdate
            , ifnull(b.invno,'-')invno
            -- ,ifnull(fjh.d_invoice,'-')
            ,ifnull(if(fjh.inv_supplier = '','-',fjh.inv_supplier),'-')inv_supplier
            ,fjd.description


            FROM fin_journal_h fjh
            LEFT JOIN fin_journal_d fjd ON fjd.id_journal=fjh.id_journal
            LEFT JOIN mastercoa mc ON mc.id_coa=fjd.id_coa
            left join masterrate mr on mr.rate=mc.id_coa
            LEFT JOIN bpb b ON b.bpbno_int=fjd.reff_doc
            LEFT JOIN jo j ON j.id=b.id_jo
            LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier
            LEFT JOIN po_header poh ON poh.pono=b.pono
            LEFT JOIN masterpterms mpt ON mpt.id=poh.id_terms
            WHERE fjh.type_journal = '14' AND fjh.fg_post = '2' AND ms.tipe_sup='S' AND fjh.date_journal >= '".$from."' and fjh.date_journal <= '".$to."' ORDER BY fjh.date_journal DESC
            limit 5000"; 
        //  echo $sql;  
            $query = mysql_query($sql);   

            $no = 1;

            while($data = mysql_fetch_array($query))
            {
              echo "<tr>";
              // echo "<td>$no</td>";
              echo "<td>$data[id_coa]</td>";
              echo "<td>$data[Id_Supplier]</td>";
              echo "<td>$data[Supplier]</td>";
              echo "<td>$data[supplier_code]</td>";
              //echo "<td>$data[days_pterms] days, $data[nama_pterms]</td>";
              echo "<td>$data[nama_pterms]</td>";
              echo "<td>$data[period]</td>";
              echo "<td>".fd_view($data['date_journal'])."</td>";
              echo "<td>$data[id_journal]</td>";
              echo "<td style='text-align:center;'>$data[d_invoice]</td>";
              // echo "<td>".fd_view($data['d_invoice'])."</td>";
              echo "<td style='text-align:center;'>$data[inv_supplier]</td>";
              echo "<td>".fd_view($data['bpbdate'])."</td>";
              echo "<td>$data[invno]</td>";
              // echo "<td>$data[amount_original]</td>";
              echo "<td style='text-align:right;'>".(number_format("$data[total_fix]",2))."</td>";
              echo "<td>".$data['description']."</td>";
              // echo "<td>-</td>";
              echo "</tr>";

              $no++;
            }
          }

          ?>
        </tbody>

      </table>

    </div>

  </div>

</section>

</div>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#examplefixrkb').DataTable({
'serverside' : true,
'processing' : true,
'serverMethod': 'post',
// 'pageLength' : 10,   
'lengthMenu': [5, 10, 25,50],
// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]

// "ajax":  "webservices/getDataRincianKontrabon.php?from="+from_+"&to="+to_,
//         "columns": [
//     //period
//         {
//       "data":           "id_coa",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.period);
//                             } */
//          } ,
//     //status
//         {
//       "data":           "Id_Supplier",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.status);
//                             } */
//          } ,     
//     //id journal
//         {
//       "data":           "Supplier",
//          } ,       

//     //date journal
//         {
//       "data":           "supplier_code",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.date_journal);
//                             } */
//          } ,    


//     //id coa
//         {
//       "data":           "nama_pterms",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.id_coa);
//                             } */
//          } ,


//     //nama coa
//         {
//       "data":           "period",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.nm_coa);
//                             } */
//          } ,
     
//     //curr
//         {
//       "data":           "date_journal",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.curr);
//                             } */
//          } ,
 
//     //debit
//         {
//       "data":           "id_journal", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.debit);
//              //                } 
//          } ,

//     //curr
//          {
//       "data":           "d_invoice", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.curr);
//              //                } 
//          } ,   

//     //credit
//         {
//       "data":           "inv_supplier", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.credit);
//              //                } 
//          } ,     

//     //debit_eqv
//         {
//       "data":           "bpbdate", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.debit_eqv);
//              //                } 
//          } ,
     
//     //credit_eqv
//         {
//       "data":           "invno", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.credit_eqv);
//              //                } 
//          } ,     
     
//     //{ "data": "credit_eqv",className:"right" },
  
//     //description
//         {
//       "data":           "amount_original", 
//              // "render":        function (data) {
//              //  return decodeURIComponent(data.description);
//              //                } 
//          } ,
      
//     //id_costcenter
//         {
//       "data":           "description",/* 
//              "render":        function (data) {
//               return decodeURIComponent(data.id_costcenter);
//                             } 
//          } ,  


// //     //nm_costcenter
// //         {
// //       "data":           "nm_costcenter",/* 
// //              "render":        function (data) {
// //               return decodeURIComponent(data.nm_costcenter);
// //                             } */
// //          } ,  
// //         ],

dom: 'Bfrtip',
        buttons: [  
            { 
              extend: 'excel', 
              text: 'Export to Excel',
              // message: "Periode "" Sampai "" \n",
              message: "Periode <?php echo $from; ?> Sampai <?php echo $to; ?>",
            // exportOptions:{
            //   search :'applied',
            //   order:'applied'
            },
        ], 
        dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",

// function GenerateTable(from_,to_){
// //overlayon();

//   GenerateTable(from,to);

//   $("#label_from").text(from);
//   $("#label_to").text(to);
//   $("#label_type_journal").text(type__);
});
})    
</script>










