

<?php
include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_Umur_Utang","userpassword","username='$user'");

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

						<input type="hidden" name="mod" value="utjatem" />

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

							<th style="font-size: 18px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN AGING AP DAN JATUH TEMPO HUTANG</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PER: <?php echo $period; ?></strong></th>

						</tr>

					</thead>

				</table>

				<br />

				<table id="examplelaporan"  class="display responsive" style="width:100%;font-size:12px;" >

					<thead>

						<tr>

							<th colspan="14">&nbsp;</th>

							<th colspan="4" style="text-align: center;">JATUH TEMPO HUTANG</th>

						</tr>

						<tr style="text-align: center;">

							<th style="text-align: center;" rowspan="2">No.</th>

							<th style="text-align: center;" colspan="3">SUPPLIER</th>

							<th rowspan="2"  style="border-right: double; text-align: center;">TOP</th>

							<th rowspan="2"  style="border-right: double; text-align: center;">ID Supplier</th>

							<th style="text-align: center;" rowspan="2"> > 120 H</th>

							<th style="text-align: center;" rowspan="2">91-120 H</th>

							<th style="text-align: center;" rowspan="2">61-90 H</th>

							<th style="text-align: center;" rowspan="2">31-60 H</th>

							<th style="text-align: center;" rowspan="2">1-30 H</th>

							<th style="text-align: center;" rowspan="2">TOTAL</th>

							<th rowspan="2" style="border-right: double; text-align: center;">AP DAYS</th>

							<th style="text-align: center;" rowspan="2">1-30 H</th>

							<th style="text-align: center;" rowspan="2">31-60 H</th>

							<th style="text-align: center;" rowspan="2">61-90 H</th>

							<th style="text-align: center;" rowspan="2"> > 90 H</th>

						</tr>

						<tr>

							<th style="text-align: center; width: 1%;">KODE/ID</th>

							<th style="text-align: center;">KODE COA</th>

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
							SELECT Y.umur_utang_kontrabon
								 ,Y.umur_utang_bpb
								 ,Y.tgl_jatem_kontrabon
								 ,Y.tgl_jatem_bpb
								 ,Y.jatuh_tempo_kontrabon
								 ,Y.jatuh_tempo_bpb
								 ,Y.post_to
								 ,Y.tglbpb
								 ,Y.tglkontrabon	
								 ,Y.id_journal
								 ,Y.currbayar
								 ,Y.terbayar
								 ,Y.n_pph
								 ,Y.Id_Supplier
								 ,Y.Supplier
								 ,Y.supplier_code
								 ,Y.ddayspterms
								 ,Y.dcripterms
								
								,Y.value_pph_d
								,Y.value_ppn
								,Y.amount_original
								,Y.value_pph_header
								,Y.value_pph
								,Y.nilai

								,SUM(Y.nilai_1_31)nilai_1_31
								,SUM(Y.nilai_31_61)nilai_31_61
								,SUM(Y.nilai_61_91)nilai_61_91		
								,SUM(Y.nilai_91_121)nilai_91_121		
								,SUM(Y.nilai_120)nilai_120
								,Y.tes															
								,SUM(Y.jt1_31)jt1_31
								,SUM(Y.jt31_61)jt31_61		
								,SUM(Y.jt61_91)jt61_91		
								,SUM(Y.jt90)jt90
								FROM
								(SELECT X.umur_utang_kontrabon
								 ,X.umur_utang_bpb
								 ,X.tgl_jatem_kontrabon
								 ,X.tgl_jatem_bpb
								 ,X.jatuh_tempo_kontrabon
								 ,X.jatuh_tempo_bpb
								 ,X.post_to
								 ,X.tglbpb
								 ,X.tglkontrabon	
								 ,X.id_journal
								 ,X.currbayar
								 ,X.terbayar
								 ,X.n_pph
								 ,X.Id_Supplier
								 ,X.Supplier
								 ,X.supplier_code
								 ,X.ddayspterms
								 ,X.dcripterms
								
								,X.value_pph_d
								,X.value_ppn
								,X.amount_original
								,X.value_pph_header
								,X.value_pph
								,X.nilai

								,IF(X.umur_utang_kontrabon >= 1 AND X.umur_utang_kontrabon < 31,X.terbayar,0)nilai_1_31
								,IF(X.umur_utang_kontrabon >= 31 AND X.umur_utang_kontrabon < 61,X.terbayar,0)nilai_31_61
								,IF(X.umur_utang_kontrabon >= 61 AND X.umur_utang_kontrabon < 91,X.terbayar,0)nilai_61_91		
								,IF(X.umur_utang_kontrabon >= 91 AND X.umur_utang_kontrabon < 121,X.terbayar,0)nilai_91_121		
								,IF(X.umur_utang_kontrabon >= 120,X.terbayar,0)nilai_120		
								,IF(DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) >= 1 
								AND DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) < 31,X.terbayar,0)jt1_31
								,IF(DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) >= 31 
								AND DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) < 61,X.terbayar,0)jt31_61
								,IF(DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) >= 61 
								AND DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) < 91,X.terbayar,0)jt61_91
								,IF(DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon) > 90,X.terbayar,0 )jt90
								,DATEDIFF(STR_TO_DATE('30 Jan 2020','%d %b %Y'),X.tgl_jatem_kontrabon)tes
		
FROM
(SELECT DATEDIFF(STR_TO_DATE('01 Jan 2020','%d %b %Y'),IFNULL(MASTER.date_journal,0))umur_utang_kontrabon
		 ,DATEDIFF(STR_TO_DATE('01 Jan 2020','%d %b %Y'),MASTER.bpbdate)umur_utang_bpb
		 ,IFNULL(ADDDATE(MASTER.date_journal,IFNULL(MASTER.ddayspterms,0)),'-')tgl_jatem_kontrabon
		 ,IFNULL(ADDDATE(MASTER.bpbdate,IFNULL(MASTER.ddayspterms,0)),'-')tgl_jatem_bpb
		 ,IFNULL(SUBDATE((ADDDATE(MASTER.date_journal,MASTER.ddayspterms)),STR_TO_DATE('01 Jan 2020','%d %b %Y')),'-')jatuh_tempo_kontrabon
		 ,IFNULL(SUBDATE((ADDDATE(MASTER.bpbdate,MASTER.ddayspterms)),STR_TO_DATE('01 Jan 2020','%d %b %Y')),'-')jatuh_tempo_bpb
		 ,MASTER.post_to
		 ,MASTER.bpbdate tglbpb
		 ,MASTER.date_journal tglkontrabon	
		 ,MASTER.id_journal
		 ,MASTER.currbayar
		 ,MASTER.terbayar
		 ,MASTER.n_pph
		 ,MASTER.Id_Supplier
		 ,MASTER.Supplier
		 ,MASTER.supplier_code
		 ,ifnull(MASTER.ddayspterms,0) ddayspterms
		 ,ifnull(MASTER.dcripterms,'KONTRABON') dcripterms
		
		,SUM(ifnull(MASTER.value_pph,0))value_pph_d
		,SUM(ifnull(MASTER.value_ppn,0))value_ppn
		,SUM(ifnull(MASTER.amount_original,0))amount_original
		,( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0)))value_pph_header
		,( ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) 
		
			-  SUM(ifnull(MASTER.value_pph,0))) value_pph
		,(SUM(ifnull(MASTER.nilai,0)  ) - ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) ) nilai
FROM(

		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,d.bpbdate
					,b.date_journal
					,a.n_pph
					,e.Id_Supplier
					,e.supplier_code
					,e.Supplier
					,g.days_pterms ddayspterms
					,g.cri_pterms dcripterms
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.amount_original
					,a.n_ppn
					, (ifnull(a.amount_original,0) - ( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) ) + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr currbayar
					,a.debit
					,a.credit
					,a.credit terbayar
					,h.post_to
					
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph,date_journal FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					LEFT JOIN bpb d ON d.id=a.id_bpb
					LEFT JOIN mastersupplier e ON e.Id_Supplier=d.id_supplier
					LEFT JOIN po_header f ON f.id=a.id_po
					LEFT JOIN masterpterms g ON g.id=f.id_terms
					LEFT JOIN mastercoa h ON h.id_coa=a.id_coa
					WHERE 1=1 AND 
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NOT NULL GROUP BY a.id_journal,a.row_id
UNION ALL
			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,d.bpbdate
					,b.date_journal
					,a.n_pph
					,e.Id_Supplier
					,e.supplier_code
					,e.Supplier
					,g.days_pterms ddayspterms
					,g.cri_pterms dcripterms
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,a.amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr currbayar
					,a.debit
					,a.credit
					,a.credit terbayar
					,h.post_to
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph, date_journal FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN bpb d ON d.id=a.id_bpb
					LEFT JOIN mastersupplier e ON e.Id_Supplier=d.id_supplier
					LEFT JOIN po_header f ON f.id=a.id_po
					LEFT JOIN masterpterms g ON g.id=f.id_terms
					LEFT JOIN mastercoa h ON h.id_coa=a.id_coa
					WHERE 1=1 AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
					)MASTER GROUP BY MASTER.id_journal ORDER BY MASTER.id_journal, MASTER.Id_Supplier ASC)X ORDER BY X.Id_Supplier)Y GROUP BY Y.Id_Supplier, Y.ddayspterms ORDER BY Y.Id_Supplier DESC
";				
		 // echo "<pre>$sql</pre>";
				$stmt = mysql_query($sql); 
				
				?>

				<?php
				$total_120 = 0;
				$total_91_120 = 0;
				$total_61_91 = 0;
				$total_31_61 = 0;
				$total_1_30 = 0;

				$total_120 = 0;
				$total_91_120 = 0;
				$total_61_91 = 0;
				$total_31_61 = 0;
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
                	$total_utang = 0;

                	$total_120 = $total_120 +$data['nilai_120'];
                	$total_91_120 = $total_91_120 +$data['nilai_91_121'];
                	$total_61_91 = $total_61_91 +$data['nilai_61_91'];
                	$total_31_61 = $total_31_61 +$data['nilai_31_61'];
                	$total_1_30 = $total_1_30 +$data['nilai_1_31'];

                	$total1 = $total1 +$data['amount_original'];
                	
                	$total3 = $total3 +$data['jt1_31'];
                	$total4 = $total4 +$data['jt31_61'];
                	$total5 = $total5 +$data['jt61_91'];
                	$total6 = $total6 +$data['jt90'];
                	
                	$total_utang = $data['nilai_120']+$data['nilai_91_121']+$data['nilai_61_91']+$data['nilai_31_61']+$data['nilai_1_31'];
                	$ap_days = 0;
                	if($data['amount_original'] != '0'){
                	$ap_days = ((365/$total_utang)/$data['amount_original']);
                	}else{
                		$ap_days = 0;
                	}
                	
                	echo "<tr>";

              echo "<td></td>";
              echo "<td>$data[supplier_code]</td>"; // kode supplier
              echo "<td>$data[post_to]</td>"; // COA post to
              echo "<td>$data[Supplier]</td>"; // Nama Supplier
              echo "<td>$data[ddayspterms] days $data[dcripterms] </td>"; // days payment terms
              echo "<td>$data[Id_Supplier]</td>"; // days payment terms
			  echo "<td style='text-align: right;'>".(number_format("$data[nilai_120]"))."</td>"; //120
			  echo "<td style='text-align: right;'>".(number_format("$data[nilai_91_121]"))."</td>"; //91-121
			  echo "<td style='text-align: right;'>".(number_format("$data[nilai_61_91]"))."</td>"; //61-91
			  echo "<td style='text-align: right;'>".(number_format("$data[nilai_31_61]"))."</td>"; //31-61
			  echo "<td style='text-align: right;'>".(number_format("$data[nilai_1_31]"))."</td>"; //1-31
			  echo "<td style='text-align: right;'>".(number_format("$data[amount_original]"))."</td>"; //TOTAL
			  echo "<td style='text-align: right;'>".(number_format($ap_days  ,5,',','.'))."</td>"; //TOTAL
			  echo "<td style='text-align: right;'>".(number_format("$data[jt1_31]"))."</td>"; //TOTAL
			  echo "<td style='text-align: right;'>".(number_format("$data[jt31_61]"))."</td>"; //TOTAL
			  echo "<td style='text-align: right;'>".(number_format("$data[jt61_91]"))."</td>"; //TOTAL
			  echo "<td style='text-align: right;'>".(number_format("$data[jt90]"))."</td>"; //TOTAL




              echo "</tr>";
			$total2 = $total2 +$ap_days;
			?>
				<?php } //end loop table?>


		<?php } //end if submit ?>

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
		<th style='text-align: right;'>".number_format($total_120)."</th>
		<th style='text-align: right;'>".number_format($total_91_120)."</th>
		<th style='text-align: right;'>".number_format($total_61_91)."</th>
		<th style='text-align: right;'>".number_format($total_31_61)."</th>
		<th style='text-align: right;'>".number_format($total_1_30)."</th>
		<th style='text-align: right;'>".number_format($total1)."</th>
		<th style='text-align: right;'>".number_format($total2)."</th>
		<th style='text-align: right;'>".number_format($total3)."</th>
		<th style='text-align: right;'>".number_format($total4)."</th>
		<th style='text-align: right;'>".number_format($total5)."</th>
		<th style='text-align: right;'>".number_format($total6)."</th>


	</tr> ";
?>

	</tfoot>

</table>

</div>

</div>

</section>

</div>
<link rel="stylesheet" href="./css/tab.css"> 
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    var table = $('#examplelaporan').DataTable({
        "columnDefs": [
            { "visible": false, "targets": 5 }
        ],
        "order": [[ 2, 'asc' ]],
        "displayLength": 25,
        dom: 'Bfrtip',
        buttons: [
            { 
              extend: 'excel', 
              text: 'Export to Excel',
            },
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            // var subTotal_satutigasatu = new Array();
            var groupID = -1;
            var aData = new Array();
            var index = 0;
            
            api.column(5, {page:'current'} ).data().each( function ( group, i ) {
            	
              // console.log(group+">>>"+i);
            
              var vals = api.row(api.row($(rows).eq(i)).index()).data();
              	vals_10 = 		vals[10].replace(/,/g, '').toString();
              	vals_11 = 		vals[11].replace(/,/g, '').toString();
              	vals_9 = 		vals[9].replace(/,/g, '').toString();
              	vals_8 = 		vals[8].replace(/,/g, '').toString();
              	vals_7 = 		vals[7].replace(/,/g, '').toString();
              	vals_6 = 		vals[6].replace(/,/g, '').toString();
              	vals_13 = 		vals[13].replace(/,/g, '').toString();
              	vals_14 = 		vals[14].replace(/,/g, '').toString();
              	vals_15 = 		vals[15].replace(/,/g, '').toString();
              	vals_16 = 		vals[16].replace(/,/g, '').toString();
              	//console.log(vals_10);  
              var nilai_1_31 = vals[10] ? parseFloat(vals_10) : 0;
              var amount_original = vals[11] ? parseFloat(vals_11) : 0;
              var nilai_31_61 = vals[9] ? parseFloat(vals_9) : 0;
              var nilai_61_91 = vals[8] ? parseFloat(vals_8) : 0;
              var nilai_91_121 = vals[7] ? parseFloat(vals_7) : 0;
              var nilai_120 = vals[6] ? parseFloat(vals_6) : 0;
              var jt1_31 = vals[13] ? parseFloat(vals_13) : 0;
              var jt31_61 = vals[14] ? parseFloat(vals_14) : 0;
              var jt61_91 = vals[15] ? parseFloat(vals_15) : 0;
              var jt90 = vals[16] ? parseFloat(vals_16) : 0;

               
              if (typeof aData[group] == 'undefined') {
                 aData[group] = new Array();
                 aData[group].rows = [];
                 aData[group].nilai_1_31 = [];
                 aData[group].amount_original = []; 
                 aData[group].nilai_31_61 = []; 
                 aData[group].nilai_61_91 = []; 
                 aData[group].nilai_91_121 = []; 
                 aData[group].nilai_120 = []; 
                 aData[group].jt1_31 = []; 
                 aData[group].jt31_61 = []; 
                 aData[group].jt61_91 = []; 
                 aData[group].jt90 = []; 
                 
              }
            
           		aData[group].rows.push(i); 
        			aData[group].nilai_1_31.push(nilai_1_31); 
        			aData[group].amount_original.push(amount_original); 
        			aData[group].nilai_31_61.push(nilai_31_61); 
        			aData[group].nilai_61_91.push(nilai_61_91); 
        			aData[group].nilai_91_121.push(nilai_91_121); 
        			aData[group].nilai_120.push(nilai_120); 
        			aData[group].jt1_31.push(jt1_31); 
        			aData[group].jt31_61.push(jt31_61); 
        			aData[group].jt61_91.push(jt61_91); 
        			aData[group].jt90.push(jt90); 
                
            } );
    

            var idx= 0;

      
          	for(var supplier in aData){
       
									 idx =  Math.max.apply(Math,aData[supplier].rows);
      
                   var sum = 0; 
                   $.each(aData[supplier].nilai_1_31,function(k,v){
                        sum = sum + v;
                   });      

                   var sum_total = 0; 
                   $.each(aData[supplier].amount_original,function(k,v){
                        sum_total = sum_total + v;
                   });
  					
                   var sum_dua = 0; 
                   $.each(aData[supplier].nilai_31_61,function(k,v){
                        sum_dua = sum_dua + v;
                   });
  					  					
                   var sum_tiga = 0; 
                   $.each(aData[supplier].nilai_61_91,function(k,v){
                        sum_tiga = sum_tiga + v;
                   });
  					  					  					
                   var sum_empat = 0; 
                   $.each(aData[supplier].nilai_91_121,function(k,v){
                        sum_empat = sum_empat + v;
                   });
  					  					  					  					
                   var sum_lima = 0; 
                   $.each(aData[supplier].nilai_120,function(k,v){
                        sum_lima = sum_lima + v;
                   });
  					  					  					  					
                   var sum_enam = 0; 
                   $.each(aData[supplier].jt1_31,function(k,v){
                        sum_enam = sum_enam + v;
                   });
  												  					
                   var sum_tujuh = 0; 
                   $.each(aData[supplier].jt31_61,function(k,v){
                        sum_tujuh = sum_tujuh + v;
                   });			
  												  					
                   var sum_lapan = 0; 
                   $.each(aData[supplier].jt61_91,function(k,v){
                        sum_lapan = sum_lapan + v;
                   });
  												  					
                   var sum_mbilan = 0; 
                   $.each(aData[supplier].jt90,function(k,v){
                        sum_mbilan = sum_mbilan + v;
                   });
  									
                   $(rows).eq( idx ).after(
                        '<tr class="group"><td ><b>SUBTOTAL<b></td>'+
                        '<td colspan="5" style="text-align:right;">'+number_format(sum_lima)  +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_empat) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_tiga) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_dua) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_total) +'</td>'+
                        '<td style="text-align:right;">-</td>'+
                        '<td style="text-align:right;">'+number_format(sum_enam) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_tujuh) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_lapan) +'</td>'+
                        '<td style="text-align:right;">'+number_format(sum_mbilan) +'</td></tr>'
                    );
                    
            };

        }
    } );

} );


</script>