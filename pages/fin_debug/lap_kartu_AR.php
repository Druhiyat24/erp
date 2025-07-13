<?php

//include_once "../../../include/conn.php";

if(!isset($_GET['period'])){

    $period = '';

}else{

    $period = $_GET['period'];

}


if(!isset($_GET['period_to'])){

    $periodto = '';

}else{

    $periodto = $_GET['period_to'];

}


if(!isset($_GET['namacoa'])){

    $idcoa = '';

}else{

    $idcoa = $_GET['namacoa'];

}

$idcoa = explode("-",$idcoa);
		
$idcoa1x[0] = $idcoa[0];


//echo 		


?>

<div class="container-fluid">

	<section class="content">

		<div id="formBs" class='box'>

    <div class='box-body'> 

        <form method='get' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>

            <div class="panel panel-default">

                <div class="panel-heading">Periode</div>

                <div class="panel-body row">

                    <div class="col-md-3">

                        <div class='form-group'>

                            <label for="period_from">Tanggal</label>
                            
                            <input type='text'  id="datepicker1" class='form-control monthpicker' name='period'

                              placeholder='MM/YYYY' autocomplete="off" value='<?php echo $period; ?>' required>
                         	 
                        </div>

                    </div>
                    
              

                    <div class="col-md-3">

                        <div class='form-group'>

                            <label>Sampai</label>


                            <input type='text'  id="datepicker2" class='form-control monthpicker' name='period_to'

                                   placeholder='MM/YYYY' autocomplete="off" value='<?php echo $periodto;?>' required>

                        </div>

                    </div>
                    
                 
                    
                    
                    <div class="col-md-3">

                        <div class='form-group'>
                        <label>Nama Coa</label>
                            
                        
                        	<br />
                        

                            
                                <!-- Dropdown --> 
                                <select class="select2" name="namacoa" style='width: 200px;'>
                                
                                
                                <?php 
								$sql = "SELECT id_coa, nm_coa FROM mastercoa WHERE v_normal='D' ORDER BY nm_coa ASC";
								$query = mysql_query($sql);							
								
								while($data = mysql_fetch_array($query))

									{ 
									if ($data['id_coa'] == $idcoa1x[0]){ $selected = 'selected' ;}
									else{ $selected = "";}
									 
									 echo "<option value='".$data['id_coa']."-".$data['nm_coa']."' ".$selected.">".$data['nm_coa']."</option>"; 
								
									
									}
								
								?>
                           
                                  
                                  
                                </select>
                                
                                
                                <br/>

                        </div>

                    </div>                    

                    <div class="clearfix"></div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <input type="hidden" name="mod" value="kartuAR" />

                            <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

	</section>

	<!-- /.content -->

</div>

<div class="container-fluid">
	<section class="content">

		<div class="box">
			<div class="box-body">

		<!-- 		<table class="display responsive" style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;"> -->

			<table style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

					<thead>

						<tr>

		<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>

						</tr>

						<tr>

							<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>KARTU PIUTANG</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>NOMOR COA: <?php echo $idcoa1x[0]; ?></strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>NAMA COA: <?php echo $idcoa[1];; ?></strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14">
                            <strong>PERIODE: <?php echo $period; ?> S/D <?php  echo $periodto; ?></strong></th>

						</tr>
					</thead>
				</table>

				<table id="example1" class="display responsive" style="width:100%;font-size:11px;overflow: scroll;" border="1">
					<thead>

						<tr class="header">

							<th colspan="6" style="background-color: gray;">&nbsp;</th>
<th style="text-align: center;" colspan="4">ORIGINAL CURRENCY</th>
                            
 <th style="text-align: center;" colspan="3">IDR</th>

						</tr>

						<tr style="text-align: center;" class="header">

							<th style="text-align: center;">No.</th>

							<th style="text-align: center;">TANGGAL</th>

							<th style="text-align: center;">NOMOR JURNAL</th>

							<th style="text-align: center;">NOMOR REFERENSI DOKUMEN</th>

							<th style="text-align: center;">NOMOR VOUCHER</th>

							<th style="text-align: center;">DESCRIPTIONS</th>

							<th style="text-align: center;">DEBET</th>

							<th style="text-align: center;">KREDIT</th>

							<th style="text-align: center;">SALDO</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">DEBET</th>

							<th style="text-align: center;">KREDIT</th>

							<th style="text-align: center;">SALDO</th>

						</tr>

					</thead>

					<tbody>

						<?php



			if(isset($_GET['submit'])) {
						
						$period = date("Y-m-d", strtotime($period));  						
						$periodto = date("Y-m-d", strtotime($periodto));
						
						
						
						
					$sql3 = "AND fjd.dateadd  >= '".$period."' AND fjd.dateadd <= '".$periodto."'";
					$sql2 = "AND fjd.dateadd BETWEEN '".$period."' AND ".$periodto."";	
					$sql = "SELECT 
							fjd.id_journal,
							fjd.dateadd,
							bpb.bpbno,
							fjh.reff_doc,
							fjhd.v_novoucher,
							fjd.debit, 
							fjd.credit,
							fjh.type_journal,
							fjd.id_coa,
							fjd.curr							
							 FROM fin_journal_d fjd LEFT JOIN fin_journal_h fjh ON fjd.id_journal = fjh.id_journal 
							LEFT JOIN bpb ON fjh.reff_doc = bpb.bpbno_int LEFT JOIN fin_journalheaderdetail fjhd
							ON fjh.id_journal = fjhd.v_idjournal							
							WHERE fjd.id_coa= '".$idcoa1x[0]."' ".$sql3."";						
							
								
										
							
			//query to mysql
			
		//	echo $sql;
			
			$query = mysql_query($sql);
			
			$count = 0;
			
		//convert kurs berdasarkan nilai rate terkahir
	
			
		$sqlusd = "SELECT curr,rate FROM masterrate WHERE curr = 'USD' AND tanggal BETWEEN ".$period." AND ".$periodto."
		
		ORDER BY id DESC LIMIT 0,1";			
			
			$queryusd = mysql_query($sqlusd);
			
			$row = mysql_fetch_assoc($queryusd);
			
			$usd = $row['rate'];
			
			// jika null maka ambil nilai terkahir aja
			
			if($usd == null ){
				
			$sqlusd = "SELECT curr,rate FROM masterrate WHERE curr = 'USD' ORDER BY id DESC LIMIT 0,1";			
			
			$queryusd = mysql_query($sqlusd);
			
			$row = mysql_fetch_assoc($queryusd);
			
			$usd = $row['rate'];	
				
				}


		// perhitungan saldo akhir berdasarkan id COA ,
		
		
		$sqlsaldo = "SELECT n_saldo,v_curr FROM fin_history_saldo WHERE n_idcoa = '".$idcoa1x[0]."' ORDER BY d_dateupdate DESC LIMIT 0,1";	
		
		//echo $sqlsaldo;	
		
				
			
			$qsaldo = mysql_query($sqlsaldo);
			
			$row = mysql_fetch_assoc($qsaldo);
			
			$saldo = $row['n_saldo'];
			
			
			
		//jik di bawah1 november maka saldo awal adalah: 
		
		if($row['v_curr'] == "IDR"){			
		
		if($period < "2019-11-1"){
			
				
			$saldoawal = 0;
			
		}
		else{
			
			$saldoawal = $saldo;
			
		}
		
		}
		else{
			
			
		if($period < "2019-11-1"){
			
				
			$saldoawal = 0;
			
		}
		else{
			
			$saldoawal = $saldo * $usd;
			
		}
					
			
			
		}
		
			
		 while($data = mysql_fetch_array($query))
		
		//for($i=1;$i<10;$i++)

            { 
			
			$count++;
			
			if($data['curr'] == "IDR"){
			
			$debitori = "";
			$kreditori = "";
			$saldoori = "";
			$debitidr = $data['debit'];
			$kreditidr = $data['credit'];
			$saldoidr = "";
			
			}
			
			elseif($data['curr'] == "IDR"){
				
			$debitori = $data['debit'];
			$kreditori = $data['credit'];
			$debitidr = $data['debit'] * $usd;
			$kreditidr = $data['cebit'] * $usd;			
			$saldoori = "";
				
			}
			
			else{
				
			$debitori = $data['debit'];
			$kreditori = $data['credit'];
			$debitidr = $data['debit'] * $usd;
			$kreditidr = $data['cebit'] * $usd;			
			$saldoori = "";
				
			}
		
			
			
			//echo "test";
							
						
				?>
					
					
					<tr>

						<td><?php echo $count; ?></td>

						<td><?php echo $data['dateadd']?></td>

						<td><?php echo $data['id_journal'] ?></td>

						<td><?php echo $data['reff_doc'] ?></td>

						<td><?php echo $data['v_novoucher']?></td>

						<td>-</td>						

						<td><?php echo $debitori ?></td>

						<td><?php echo $kreditori ?></td>						

						<td><?php echo number_format($saldoawal ,0,',','.') ;?></td>

						<td><?php echo $data['curr']?></td>

						<td style='text-align: right'><?php echo number_format($debitidr ,0,',','.') ; ?></td>

						<td style='text-align: right'><?php echo number_format($kreditidr,0,',','.') ; ?></td>

						<td style='text-align: right'><?php echo number_format($saldoawal ,0,',','.') ;?></td>

						</tr>
                        
                        <?php } ?>


				</tbody>
				<tr>

					<td style="text-align: right; background-color: gray;" colspan="6">TOTAL MUTASI</th>

						<th>&nbsp;</th>

						<th>&nbsp;</th>

						<th colspan="5" style="background-color: gray;">&nbsp;</th>

					</tr>

				</table>
                
                <?php } ?>
			</div>
		</div>
	</section>
</div>

