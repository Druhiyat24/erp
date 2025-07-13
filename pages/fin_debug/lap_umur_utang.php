<?php /* lap_umur_utang.php, last dev by Haris J*/

 ?>
<?php
include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_Umur_Utang","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

?>


<div class="container-fluid">



	<section class="content">

    



		<script type="text/javascript">function validasi(){var from = document.form.txtfrom.value;var to = document.form.txtto.value;if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.focus();valid = false;}else valid = true;return valid;exit;}</script>

        

        <div class="box"><div class="box-body"><div class="row">

        

        <form method="post" name="form" action="?mod=umurutang" onsubmit="return validasi()">

        <div class="col-md-3"><div class="form-group"><label>PER *</label>

        <input type="text" class="form-control" id="datepicker1" name="txtfrom" placeholder="Masukkan Periode" autocomplete="off"></div>

        

        <!-- 

        

        <div class="form-group"><label>Sampai Tanggal *</label>

        <input type="text" class="form-control" id="datepicker2" name="txtto" placeholder="Masukkan Sampai Tanggal" value="04 Dec 2019"></div>

        

        -->

         

        <button type="submit" name="submit" class="btn btn-primary">Tampilkan</button></div></form></div></div></div>  







		<div class="box">

			<div class="box-body">





<?php 



if(isset($_POST['submit'])){



			$from = $_POST['txtfrom'];

			

			$to = $_POST['txtto'];

			

			if ($from <> '' and $to <> ''){

				

			$from = str_replace('/', '-', $from);

			

			$from = date("Y-m-d", strtotime($from));

			

			$to = str_replace('/', '-', $to);  

			  

			$to = date("Y-m-d", strtotime($to)); 

			

			$query1 = "AND fjh.date_journal between '".$from."' AND '".$to."'"; 

				

				}

				

			$sql = "SELECT * FROM (SELECT DISTINCT fjd.id_journal, 

					fjh.reff_doc ab,fjh.n_pph,

					fjh.date_journal tglkontrabon, 

					fjd.credit,fjd.id_coa, 

					fjd.nm_coa, 

					fjd.curr,

					sub.* 

					FROM fin_journal_h fjh LEFT JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal 

					LEFT JOIN (SELECT bpb.bpbno_int,mastersupplier.Supplier,bpb.id_supplier,bpb.id_po_item,bpb.bpbdate 			                    tglbpb,po_item.id_po,t_terms.* FROM bpb LEFT JOIN po_item ON bpb.id_po_item = po_item.id LEFT JOIN  mastersupplier ON bpb.id_supplier = mastersupplier.id_supplier LEFT JOIN 

                    (SELECT masterpterms.id dmastertermsid,masterpterms.days_pterms ddayspterms,

                    masterpterms.cri_pterms dcripterms,po_header.id_terms,po_header.id poheaderid,po_header.id_terms                    idtermsdipo,po_header.jml_pterms,po_header.id_dayterms 

                    FROM po_header LEFT JOIN masterpterms ON masterpterms.id = po_header.id_terms 

                    WHERE masterpterms.cri_pterms <> 'NULL') t_terms on t_terms.poheaderid = po_item.id_po) sub

                    ON fjh.reff_doc = sub.bpbno_int WHERE fjh.type_journal = '2' AND fjd.credit <> 0 

					AND dmastertermsid <> '' AND  fjh.fg_post='2' ".$query1.") A LEFT JOIN (SELECT DISTINCT fjd.id_journal, 

					fjh.reff_doc reff_docbayar, 

					fjd.credit terbayar,					 

					fjd.curr currbayar

					FROM fin_journal_h fjh LEFT JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal 

					WHERE fjh.type_journal = '3' AND  fjh.fg_post='2' AND fjd.credit <> 0 ".$query1.")B 

					ON A.ab = B.reff_docbayar

					UNION ALL

					SELECT * FROM (SELECT DISTINCT fjd.id_journal, 

					fjh.reff_doc ab, fjh.n_pph,

					fjh.date_journal tglkontrabon, 

					fjd.credit,fjd.id_coa, 

					fjd.nm_coa, 

					fjd.curr,

					sub.* 

					FROM fin_journal_h fjh LEFT JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal 

					LEFT JOIN (SELECT bpb.bpbno_int,mastersupplier.Supplier,bpb.id_supplier,bpb.id_po_item,bpb.bpbdate 			                    tglbpb,po_item.id_po,t_terms.* FROM bpb LEFT JOIN po_item ON bpb.id_po_item = po_item.id LEFT JOIN                    mastersupplier ON bpb.id_supplier = mastersupplier.id_supplier LEFT JOIN 

                    (SELECT masterpterms.id dmastertermsid,masterpterms.days_pterms ddayspterms,

                    masterpterms.cri_pterms dcripterms,po_header.id_terms,po_header.id poheaderid,po_header.id_terms                    idtermsdipo,po_header.jml_pterms,po_header.id_dayterms 

                    FROM po_header LEFT JOIN masterpterms ON masterpterms.id = po_header.id_terms 

                    WHERE masterpterms.cri_pterms <> 'NULL') t_terms on t_terms.poheaderid = po_item.id_po) sub

                    ON fjd.reff_doc = sub.bpbno_int WHERE fjh.type_journal = '14' AND fjd.credit <> 0 

					AND dmastertermsid <> '' AND  fjh.fg_post='2' ".$query1.") A LEFT JOIN (SELECT DISTINCT fjd.id_journal, 

					fjh.reff_doc reff_docbayar, 

					fjd.credit terbayar,					 

					fjd.curr currbayar

					FROM fin_journal_h fjh LEFT JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal 

					WHERE fjh.type_journal = '3' AND  fjh.fg_post='2' AND fjd.credit <> 0 ".$query1.")B 

					ON A.ab = B.reff_docbayar";



		

		$sqlsupplier = "SELECT Supplier,id_Supplier FROM mastersupplier";

				

		//echo $sql;



?>





				<table id="examplefix"  class="display responsive" style="width:100%;font-size:12px;">

					<thead>



						<tr>



							<th rowspan="2" style="text-align: center;">No.</th>



							<th colspan="2" style="text-align: center;">SUPPLIER</th>



							<th rowspan="2" style="text-align: center;">TOP</th>



							<th rowspan="2" style="text-align: center;">BASED ON</th>



							<th rowspan="2" style="text-align: center;">TOTAL</th>



							<th rowspan="2" style="text-align: center;">M->6</th>



							<th rowspan="2" style="text-align: center;">M-5</th>



							<th rowspan="2" style="text-align: center;">M-4</th>



							<th rowspan="2" style="text-align: center;">M-3</th>



							<th rowspan="2" style="text-align: center;">M-2</th>



							<th rowspan="2" style="text-align: center;">M-1</th>



							<th rowspan="2" style="text-align: center;">BULAN BERJALAN</th>



							<th rowspan="2" style="text-align: center;">AP DAYS</th>



						</tr>



						<tr>



							<th style="text-align: center;">KODE/ID</th>



							<th style="text-align: center;">NAMA</th>







						</tr>







					</tbody>

                    

<?php 



			

			//convert kurs berdasarkan nilai rate terkahir

			

			$sqlusd = "SELECT curr,rate FROM masterrate WHERE curr = 'USD' ORDER BY id DESC LIMIT 0,1";

			

			$queryusd = mysql_query($sqlusd);

			

			$row = mysql_fetch_assoc($queryusd);

			

			$usd = $row['rate'];

						

			

			//$sqlhkg = "SELECT curr,rate FROM masterrate WHERE curr = 'USD' ORDER BY id DESC LIMIT 0,1"

			

			//today saya ganti menjadi tanggal periode karena ada kesalahan perhitungan umur utang , bukan perhitungan per hari ini 

			//tetapi perhitungan berdasarkan tanggal input periode.

			  

			//$today =  date("Y-m-d");

			

			$today = $from ;

			

			//query to mysql

			$query = mysql_query($sql);

			

			$count = 0;	

		

	//	for($i = 0; $i < mysql_num_rows($query); $i++) 

	

		while($data = mysql_fetch_array($query))



            { 

			

			  //echo $data['id_supplier'];

			

			//$data = mysql_fetch_array($query);

			

			$count++;

			//perhitungan jatuh tempo

			

			 if($data['dcripterms'] == 'KONTRABON'){

				  

				 $date1 =  $data['tglkontrabon'];

		

			  }

			  

			  elseif($data['dcripterms'] == 'BPB'){

				  

				 $date1 =  $data['tglbpb'];

					

			  }

			   

			  $jatuhtempo = date('Y-m-d', strtotime($date1. ' + '.$data['ddayspterms'].' days'));

				 

			//  $secs = strtotime($today) - strtotime($jatuhtempo);

				 

			  //$daysjatuhtempo = $secs / 86400;

			  

			  //echo $jatuhtempo; echo "<br>";

			  

			  

			  $ts1 = strtotime($today);

			  

			  $ts2 = strtotime($jatuhtempo);

				

			  $year1 = date('Y', $ts1);

			

			  $year2 = date('Y', $ts2);

				

			  $month1 = date('m', $ts1);

			

			  $month2 = date('m', $ts2);

				

			 $monthjatuhtempo = (($year2 - $year1) * 12) + ($month2 - $month1);

			 

			 

		//	 echo "Tanggal sekarang : ". $today. "Tanggal jatuh tempo : ". $jatuhtempo."|" ;echo $monthjatuhtempo; echo "<br>";

			 

		

			  if ($data['curr'] == 'USD') {				  

			  

			  	$credita = $data['credit'] * $usd;

			

			  }

			  

			  else{

				  

				  $credit = $data['credit'];

				  

			  }

			  

			  

		     if ($data['currbayar'] == 'USD') {

				  

			  

			  	$creditb = $data['terbayar'] * $usd;

				

				$pph = ($data['n_pph']/100)*$creditb; 

				

				$creditb = $creditb+$pph;

			

			  }

			  

			  else{

				  

				  $creditb = $data['terbayar'];

				  

			  }

			  

			  $credit = $credita - $creditb;

			  

			//  $b =$credit;

			  

			

			  

			  $id[$data['id_supplier']] = $credit;

			  
			for ($h=0 ; $h<=1000;$h++){

				

				if ($h==$data['id_supplier']){

				

				$temp[$h] = $temp[$h]+ $id[$data['id_supplier']];

				

				if( $monthjatuhtempo == 6 ){

					

					$temp6[$h] = $temp6[$h]+ $id[$data['id_supplier']];

					

					$ecount6[$h] = 1;

					

				}

				

				elseif( $monthjatuhtempo == 5 ){

					

					$temp5[$h] = $temp5[$h]+ $id[$data['id_supplier']];

					

					$ecount5[$h] = 1;

					

				}

				

				elseif( $monthjatuhtempo == 4 ){

					

					$temp4[$h] = $temp4[$h]+ $id[$data['id_supplier']];

					

					$ecount4[$h] = 1;

					

				}		

				

				elseif( $monthjatuhtempo == 3 ){

					

					$temp3[$h] = $temp3[$h]+ $id[$data['id_supplier']];

					

					$ecount3[$h] = 1;

					

				}	

				

				elseif( $monthjatuhtempo == 2 ){

					

					$temp2[$h] = $temp2[$h]+ $id[$data['id_supplier']];

					

					$ecount2[$h] = 1;

					

				}

				

				elseif( $monthjatuhtempo == 1 ){

					

					$temp1[$h] = $temp1[$h]+ $id[$data['id_supplier']];

					

					$ecount1[$h] = 1;

					

				}	

				

				elseif( $monthjatuhtempo == 0 ){

					

					$temp0[$h] = $temp1[$h]+ $id[$data['id_supplier']];

					

					$ecount0[$h] = 1;

					

				}	

				

				elseif($monthjatuhtempo < 0 ){

					

					

					$temp10[$h] = $temp10[$h]+ $id[$data['id_supplier']];

					

					$ecount10[$h] = 1;

					

					

				}

																							

				$ecounttotal[$h] = $ecount0[$h] + $ecount1[$h] + $ecount2[$h] + $ecount3[$h] + $ecount4[$h] + $ecount5[$h] + $ecount6[$h];

				

				goto end;

					

				}

				

				

			}

			

			end:

			  

  



			}



	

		$querytemp = mysql_query($sqlsupplier);

		

		$xe = 0;

			

		while($data = mysql_fetch_array($querytemp))



            { 	

			

			$xe = $xe + 1;

			

			if( $temp[$data['id_Supplier']] <> null ){

					



                    

			?>

					<tr>

					  <td><?php echo $xe;?></td>

					  <td><?php 

					 

						echo $data['id_Supplier'];

					 

					 ?></td>

					  <td><?php 

					

						echo $data['Supplier'];

					

					?></td>

					  <td style="text-align: center;"><?php echo  number_format($data['ddayspterms']);?></td>

					  <td>Aktual</td>

					  <td style="text-align: center;">

					  <?php 

					  

					  echo number_format($temp[$data['id_Supplier']] ,0,',','.') ;

					

				//	echo ;

					

					?></td>

					  <td style="text-align: center;"><?php echo number_format($temp6[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp5[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp4[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp3[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp2[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp1[$data['id_Supplier']]); ?></td>

					  <td style="text-align: center;"><?php echo number_format($temp0[$data['id_Supplier']]); ?></td>

					  <td>

                      

<?php 



	  

			  $tempap1 = ($temp[$data['id_Supplier']] /  $ecounttotal[$data['id_Supplier']]);

			  

			  $tempap2 = (365 / $tempap1);

			  

			  

			  echo number_format($tempap2  ,10,',','.');



?>                      

                      

                      

                      </td>

					  </tr>

					<tr>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>Jt Tempo</td>

					  <td style="text-align: center;">

					  <?php 

					  

					  echo number_format( $temp10[$data['id_Supplier']] ,0,',','.') ;

					  

					  ?></td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  </tr>

					<tr>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>Selisih</td>

					  <td style="text-align: center;">

                      

                      <?php 

					  

					  $selisih = $temp[$data['id_Supplier']] - $temp10[$data['id_Supplier']];

					  

					  echo number_format($selisih,0,',','.') ;

					  

					  ?>

                      </td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  <td>&nbsp;</td>

					  </tr>

                      

				  <?php 

				  

					}

				}		  

				  

				   ?>                     

                                                                



				</table>

                

              <?php } ?>  

			</div>

		</div>

	</section>

</div>