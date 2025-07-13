<?php 

?>

<?php 

$period = $_GET['txtfrom'];

?>

<div class="container-fluid">

	<section class="content">

		<div class="box">

			<div class="box-body"> 

				<div class="row">

					<form method="get" name="form" action=""> 

						<input type="hidden" name="mod" value="mutpilok" />

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

							<th style="font-size: 18px; border-collapse: collapse; border: none;" colspan="14"><strong>LAPORAN MUTASI PIUTANG LOKAL</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PER: <?php echo $period; ?></strong></th>

						</tr>

					</thead>

				</table>

				<br />

				<table id="examplefix"  class="display responsive" style="font-size:12px;" width="100%" >
					<thead>
						<tr>
							<th rowspan="2">NO.</th>
							<th colspan ="3" style="text-align: center;" >KONSUMEN</th>
							<th rowspan="2" style="text-align: center;">TOP</th>
							<th rowspan="2" style="text-align: center;">SALDO AWAL</th>
							<th colspan="2" style="text-align: center;">PENAMBAHAN</th>
							<th colspan="3" style="text-align: center;">PENGURANGAN</th>
							<th rowspan="2" style="text-align: center;">SALDO AKHIR</th>
							<th rowspan="2" style="text-align: center;">AR Days</th>
						</tr>
						<tr>
							<th style="text-align: center;">COA</th>
							<th style="text-align: center;">ID</th>
							<th style="text-align: center;">NAMA</th>
							<th style="text-align: center;">PENJUALAN</th>
							<th style="text-align: center;">LAIN-LAIN</th>
							<th style="text-align: center;">PELUNASAN</th>
							<th style="text-align: center;">RETUR/POT</th>
							<th style="text-align: center;">LAIN-LAIN</th>
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
							,		a.invno
							,		d.so_no  
							,		d.so_date
							,		e.post_to
							,		f.supplier_code
							,		f.Id_Supplier
							,		f.Supplier
							,		o.days_pterms
							,		c.notes
							,		i.n_saldo
							,		j.bppbdate
							,		j.invno
							,		h.debit d_penjualan
							,		h.credit c_penjualan
,		m.debit d_penerimaan #masih kosong
,		m.credit c_penerimaan #masih kosong
,		k.debit d_alokasi_ar #masih kosong
,		k.credit c_alokasi_ar #masih kosong

FROM invoice_header a
LEFT JOIN invoice_detail b ON b.id_inv=a.id
INNER JOIN (SELECT id_journal, reff_doc, fg_post, type_journal from fin_journal_h WHERE fg_post='2' AND type_journal='1' )g ON g.reff_doc=a.invno #PENJUALAN
LEFT JOIN fin_journal_d h ON h.id_journal=g.id_journal
LEFT JOIN so_det c ON c.id=b.id_so_det
LEFT JOIN so d ON d.id=c.id_so
LEFT JOIN mastercoa e ON e.id_coa=h.id_coa
LEFT JOIN mastersupplier f ON f.Id_Supplier=a.id_buyer
LEFT JOIN fin_history_saldo i ON i.n_idcoa=e.id_coa
LEFT JOIN bppb j ON j.bppbno_int=g.reff_doc
LEFT JOIN masterpterms o ON o.id=a.id_pterms
LEFT JOIN
(SELECT fin_journal_h.id_journal
	,fin_journal_h.type_journal
	,fin_journal_h.fg_tax
	,fin_journal_h.fg_post
	,fin_journal_h.n_pph
	,fin_journal_h.date_journal
	,fin_journal_h.reff_doc 
	,fin_journal_d.debit
	,fin_journal_d.credit
	,fin_journal_d.id_coa
	FROM fin_journal_h 
	LEFT JOIN fin_journal_d ON fin_journal_d.id_journal=fin_journal_h.id_journal
	LEFT JOIN mastercoa ON mastercoa.id_coa=fin_journal_d.id_coa
	WHERE fin_journal_h.type_journal='13' 
	AND fin_journal_h.fg_post='2' 
			 AND mastercoa.v_normal='D')m ON m.reff_doc=a.invno #PENERIMAAN
			 LEFT JOIN			 
			 (SELECT fin_journal_h.id_journal
			 ,fin_journal_h.type_journal
			 ,fin_journal_h.fg_tax
			 ,fin_journal_h.fg_post
			 ,fin_journal_h.n_pph
			 ,fin_journal_h.date_journal
			 ,fin_journal_h.reff_doc 
			 ,fin_journal_d.debit
			 ,fin_journal_d.credit
			 ,fin_journal_d.id_coa 
			 FROM fin_journal_h 
			 LEFT JOIN fin_journal_d ON fin_journal_d.id_journal=fin_journal_h.id_journal
			 LEFT JOIN mastercoa ON mastercoa.id_coa=fin_journal_d.id_coa		 
			 WHERE fin_journal_h.type_journal='4' 
			 AND fin_journal_h.fg_post='2' 
			 AND mastercoa.v_normal='D')k ON k.reff_doc=a.invno #ALOKASI AR
			 WHERE a.invno IS NOT NULL AND e.id_coa NOT IN('15204','15207') GROUP BY a.invno ORDER BY f.Id_Supplier, a.invdate ASC 


			 ";        
			 // echo "<pre>$sql</pre>";
			 $stmt = mysql_query($sql); 

			 ?>

			 <?php

			 while($data = mysql_fetch_array($stmt))

			 { 

			 	 // echo "<td>$data[Id_Supplier]</td>"; // days payment terms
			  // echo "<td style='text-align: right;'>".(number_format("$data[nilai_120]"))."</td>"; //120

			 	echo "<tr>";

			 	echo "<td>&nbsp;</td>";
			 	echo "<td>$data[post_to]</td>";
			 	echo "<td>$data[supplier_code]</td>";
			 	echo "<td>$data[Supplier]</td>";
			 	echo "<td>$data[days_pterms]</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[d_penjualan]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";
			 	echo "<td style='text-align: right;'>".(number_format("$data[n_saldo]"))."</td>";

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

				</tr> ";
				?>

			</tfoot>

		</table>

	</div>

</div>

</section>

</div>
