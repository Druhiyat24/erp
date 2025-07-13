<?php



// Lap kontra bon last edited by Haris jumail

include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

 $akses = flookup("F_L_AP_REK_KON_BON","userpassword","username='$user'");

 if ($akses=="0") 

 { echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

?>
<style type="text/css">
  th.{
    line-height: 5px;
    height: 5px;
    max-height: 5px;
    margin-top: 5px;
  }
  tr.{
    line-height: 5px;
    height: 5px;
    max-height: 5px;
    margin-top: 5px;
  }

</style>

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

                    <!-- <div class="clearfix"></div> -->

                    <div class="col-md-3">

                        <div class="form-group"> 

                            <input type="hidden" name="mod" value="lapKontraBon" />
                            <label>&nbsp;</label><br>
                            <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>

                        </div>

                    </div>

                </div>

            </div>

        </form>





      <table rules="all" id="examplefixkb" class="" style="width:100%;">   



          <thead>



           <tr style="height:0%" class="header">



          <!--   <th height="5" rowspan="2" style="text-align: center;"  >No.</th> -->



            <th colspan="3" style="text-align: center;">SUPPLIER</th>



            <th height="5" rowspan="2" style="text-align: center;">TOP</th>



            <th height="5" rowspan="2" style="text-align: center;">PERIODE TAGIHAN</th>



            <th height="5" colspan="2" style="text-align: center;">KONTRABON</th>



            <th height="5" colspan="2" style="text-align: center;">DOKUMEN</th>



            <th height="5" rowspan="2" style="text-align: center;">TOTAL RUPIAH (RP)</th>



            <th height="5" rowspan="2" style="text-align: center;">KETERANGAN</th>



          </tr>



          <tr>



            <th height="5" style="text-align: center;">KODE/ID</th>



            <th height="5" style="text-align: center;">NAMA</th>



            <th height="5" style="text-align: center;">NAMA ALIAS</th>



            <th height="5" style="text-align: center;">TANGGAL</th>



            <th height="5" style="text-align: center;">NOMOR</th>



            <th height="5" style="text-align: center;">INVOICE</th>



            <th height="5" style="text-align: center;">SURAT JALAN</th>



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

        

       

       

    $sql = "SELECT 


              -- ms.supplier_code
              ifnull(if(ms.supplier_code='','-',ms.supplier_code),'-')supplier_code

            , ms.Supplier
            , ms.Id_Supplier

            , ms.short_name
            

            , mp.terms_pterms

            , ifnull(if(mp.nama_pterms='','-',mp.nama_pterms),'-')nama_pterms
            
            -- , IFNULL (mp.nama_pterms,'-')

            , fjh.period 

            , fjh.date_journal

            , fjh.id_journal
            , ifnull(if(fjh.inv_supplier='','-',fjh.inv_supplier),'-')inv_supplier


            -- ,IF(fjd.curr='USD',(fjd.amount_original*mr.rate),
            -- fjd.amount_original)total_fix

            ,IF(fjd.curr='USD',(fjd.credit*mr.rate),fjd.credit)total_fix

            ,BPB.invno

      , fjd.credit

      , fjd.description

      , fjd.reff_doc

      , BPB.id

      , fjd.curr

      , BPB.id_item

      

      FROM bpb BPB LEFT JOIN fin_journal_d fjd ON BPB.bpbno_int = fjd.reff_doc

            

      LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 

      LEFT JOIN masterrate mr on mr.rate=fjd.id_journal

      

      LEFT JOIN mastersupplier ms ON BPB.id_supplier = ms.Id_Supplier

      

      LEFT JOIN jo JO on JO.id=BPB.id_jo

      

      LEFT JOIN masteritem mi ON BPB.id_item = mi.id_item 



      LEFT JOIN masterpterms mp ON mp.id = ms.id_terms



            WHERE fjh.type_journal='14' and fjh.fg_post='2' and fjd.credit <> 0 



      AND fjh.date_journal >= '".$from."' and fjh.date_journal <= '".$to."' GROUP BY date_journal,credit,reff_doc,Supplier" ; 
    

    //  echo $sql;  



          $query = mysql_query($sql);

      

          $no = 1;

      

      //cek usd

      

      $sqlusd = "SELECT curr,rate FROM masterrate WHERE curr = 'USD' ORDER BY id DESC LIMIT 0,1";

      

      $queryusd = mysql_query($sqlusd);

      

      $row = mysql_fetch_assoc($queryusd);

      

      $usd = $row['rate'];



          while($data = mysql_fetch_array($query))



            {

        

      if($data['curr']== "IDR"){

        

        $kredit = $data['credit'];

        

        }

          

      else{

       

       $kredit = $data['credit']* $usd; 

        

      }

        

         echo "<tr>";



          // echo "<td>".$no."</td>";
         // echo "<td></td>";
         // echo "<td>".$no['']."</td>";
          echo "<td style='text-align:center;'>$data[supplier_code]</td>";
          echo "<td>$data[Supplier]</td>";
          echo "<td>$data[short_name]</td>";
          echo "<td style='text-align:center;'>$data[nama_pterms]</td>";
          echo "<td>$data[period]</td>";
          echo "<td>".fd_view($data[date_journal])."</td>";
          echo "<td>$data[id_journal]</td>";
          echo "<td style='text-align:center;'>$data[inv_supplier]</td>";
          echo "<td>$data[invno]</td>";
          // echo "<td>".number_format($kredit,2,',','.')."</td>";
          echo "<td style='text-align:right;'>".(number_format("$data[total_fix]",2))."</td>";
          echo "<td>".$data[description]."</td>";

          echo "</tr>";



          //$no++;

    



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
    var table = $('#examplefixkb').DataTable({
'serverside' : true,
'processing' : true,
'serverMethod': 'post',
// 'pageLength' : 5,   
'lengthMenu': [5, 10, 25,50],

dom: 'Bfrtip',
        buttons: [  
            { 
              extend: 'excel', 
              text: 'Export to Excel',
              // message: "Periode "" Sampai "" \n",
              message: "Periode <?php echo $from; ?> Sampai <?php echo $to; ?>",
            },
        ], 
        dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",


//  dom: 'Bfrtip',
//        buttons: [
// {

//               extend: 'excel', 
//               text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
//               className: 'btn-primary',
//         //title: 'Any title for file',
//                  message: "Periode "+from_+" Sampai "+to_+" \n",
//             exportOptions:{
//               search :'applied',
//               order:'applied'
//             },

//              "action": newexportaction,
             
             
             

//                       }       
    
      
//         ],  

// dom:
//       "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
//       "<'row'<'col-sm-12'tr>>"+
//     "<'row'<'col-sm-5'i><'col-sm-7'p>>",  
//     } );
// }



// function fnExcelReport()
// {
//   //table.destroy().draw();
// /*  $('#laporan_jurnal').DataTable( {
      
//      "bPaginate": false,
//   }); */
//   table.destroy().draw();
//   overlayon();
//   setTimeout(function(){    
//     var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
//     var textRange; var j=0;
//     tab = document.getElementById('examplefixkb'); // id of table

//     for(j = 0 ; j < tab.rows.length ; j++) 
//     {     
//         tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
//         //tab_text=tab_text+"</tr>";
//     }

//     tab_text=tab_text+"</table>";
//     tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
//     tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
//     tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

//     var ua = window.navigator.userAgent;
//     var msie = ua.indexOf("MSIE "); 
//     if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
//     {
//         txtArea1.document.open("txt/html","replace");
//         txtArea1.document.write(tab_text);
//         txtArea1.document.close();
//         txtArea1.focus(); 
//         sa=txtArea1.document.execCommand("SaveAs",true,"Lap_Journal.xls");
//     }  
//     else  {               //other browser not tested on IE 11
//   var uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
//     var downloadLink = document.createElement("a");
//     downloadLink.href = uri;
//     downloadLink.download = "Lap_Journal.xls";
    
//     document.body.appendChild(downloadLink);
//     downloadLink.click();
//     document.body.removeChild(downloadLink);

  
  
//   overlayoff();
//   GenerateTable(from,to);
//   overlayoff();
//         //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
//   }
//     return (sa);
//   }, 3000); 
  

// }


});
})    
</script>


