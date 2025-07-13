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





      <table id="examplefix" class="display responsive" style="width:100%; height: 200px;">   



          <thead>



           <tr class="header">



            <th rowspan="2" style="text-align: center;" >No.</th>



            <th colspan="3" style="text-align: center;">SUPPLIER</th>



            <th rowspan="2" style="text-align: center;">TOP</th>



            <th rowspan="2" style="text-align: center;">PERIODE TAGIHAN</th>



            <th colspan="2" style="text-align: center;">KONTRABON</th>



            <th colspan="2" style="text-align: center;">DOKUMEN</th>



            <th rowspan="2" style="text-align: center;">TOTAL RUPIAH (RP)</th>



            <th rowspan="2" style="text-align: center;">KETERANGAN</th>



          </tr>



          <tr>



            <th style="text-align: center;">KODE/ID</th>



            <th style="text-align: center;">NAMA</th>



            <th style="text-align: center;">NAMA ALIAS</th>



            <th style="text-align: center;">TANGGAL</th>



            <th style="text-align: center;">NOMOR</th>



            <th style="text-align: center;">INVOICE</th>



            <th style="text-align: center;">SURAT JALAN</th>



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

			  

			 

			 

		$sql = "SELECT ms.supplier_code

            , ms.Supplier

            , ms.short_name

            , mp.terms_pterms

            , fjh.period 

            , fjh.date_journal

            , fjh.id_journal

            , fjh.d_invoice

            , BPB.invno

      , fjd.credit

      , fjd.description

      , fjd.reff_doc

      , BPB.id

      , fjd.curr

      , BPB.id_item

      

      FROM bpb BPB LEFT JOIN fin_journal_d fjd ON BPB.bpbno_int = fjd.reff_doc

            

      LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 

      

      LEFT JOIN mastersupplier ms ON BPB.id_supplier = ms.Id_Supplier

      

      LEFT JOIN jo JO on JO.id=BPB.id_jo

      

      LEFT JOIN masteritem mi ON BPB.id_item = mi.id_item 



      LEFT JOIN masterpterms mp ON mp.id = ms.id_terms



            WHERE fjh.type_journal='14' and fjh.fg_post='2' and fjd.credit <> 0 



      AND fjh.date_journal >= '".$from."' and fjh.date_journal <= '".$to."' GROUP BY date_journal,credit,reff_doc,Supplier" ;	
		

		//	echo $sql;  



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



          echo "<td></td>";



          echo "<td>$data[supplier_code]</td>";



          echo "<td>$data[Supplier]</td>";



          echo "<td>$data[short_name]</td>";



          echo "<td>$data[terms_of_pay]</td>";



          echo "<td>$data[period]</td>";



          echo "<td>".fd_view($data[date_journal])."</td>";



          echo "<td>$data[id_journal]</td>";



          echo "<td>$data[d_invoice]</td>";



          echo "<td>$data[invno]</td>";



          echo "<td>".number_format($kredit,2,',','.')."</td>";



          echo "<td>".$data['description']."</td>";







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
    var table = $('#examplelaporan').DataTable({
        "columnDefs": [







