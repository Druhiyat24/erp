<div class="container-fluid">

	<section class="content">

		<script type="text/javascript">function validasi(){var from = document.form.txtfrom.value;var to = document.form.txtto.value;if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.focus();valid = false;}else valid = true;return valid;exit;}</script><div class="box"><div class="box-body"><div class="row"><form method="post" name="form" action="?mod=19&amp;mode=Hist" onsubmit="return validasi()"><div class="col-md-3"><div class="form-group"><label>Dari Tanggal *</label><input type="text" class="form-control" id="datepicker1" name="txtfrom" placeholder="Masukkan Dari Tanggal" value="04 Dec 2019"></div><div class="form-group"><label>Sampai Tanggal *</label><input type="text" class="form-control" id="datepicker2" name="txtto" placeholder="Masukkan Sampai Tanggal" value="04 Dec 2019"></div><button type="submit" name="submit" class="btn btn-primary">Tampilkan</button></div></form></div></div></div>  

	</section>

	<!-- /.content -->

</div>

<div class="container-fluid">
	<section class="content">

		<div class="box" style="overflow: scroll;">
			<div class="box-body">

				<table id="example1"  class="display responsive" style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

					<thead>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="8"><strong>PT. __________________________</strong></th>

						</tr>

						<tr>

							<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="8"><strong>PERINCIAN MUTASI PIUTANG DAGANG</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="8"><strong>PERIOD: ______________________ </strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="8">&nbsp;</th>

						</tr>
					</thead>
				</table>

				<table id="example1"  class="display responsive" style="width:100%;font-size:11px; overflow: scroll;" border="1"> 
					<thead>

						<tr>

							<th style="text-align: center;" rowspan="3">No.</th>

							<th style="text-align: center;" colspan="3">KONSUMEN</th>

							<th style="text-align: center;" colspan="2">SURAT JALAN</th>

							<th style="text-align: center;" colspan="2">INVOICE</th>

							<th style="text-align: center;" colspan="2">KONTRA BON</th>

							<th style="text-align: center;" colspan="2">FAKTUR PAJAK</th>

							<th style="text-align: center;" rowspan="3">NOMOR SALES ORDER</th>

							<th style="text-align: center;" rowspan="3">ID ORDER</th>

							<th style="text-align: center;" rowspan="3">TOP</th>

							<th style="text-align: center;" colspan="3">SALDO AWAL</th>

							<th style="text-align: center;" colspan="6">PENAMBAHAN</th>

							<th style="text-align: center;" colspan="12">PENGURANGAN</th>

							<th style="text-align: center;" colspan="3">SALDO AKHIR</th>

						</tr>

						<tr>

							<th style="text-align: center;" rowspan="2">COA</th>

							<th style="text-align: center;" rowspan="2">ID</th>

							<th style="text-align: center;" rowspan="2">NAMA</th>

							<th style="text-align: center;" rowspan="2">TANGGAL</th>

							<th style="text-align: center;" rowspan="2">NOMOR</th>

							<th style="text-align: center;" rowspan="2">TANGGAL</th>

							<th style="text-align: center;" rowspan="2">NOMOR</th>

							<th style="text-align: center;" rowspan="2">TANGGAL</th>

							<th style="text-align: center;" rowspan="2">NOMOR</th>

							<th style="text-align: center;" rowspan="2">TANGGAL</th>

							<th style="text-align: center;" rowspan="2">NOMOR</th>

							<th style="text-align: center;" rowspan="2">US$</th>

							<th style="text-align: center;" rowspan="2">KURS</th>

							<th style="text-align: center;" rowspan="2">IDR</th>

							<th style="text-align: center;" colspan="3">PENJUALAN</th>

							<th style="text-align: center;" colspan="3">LAIN-LAIN</th>

							<th style="text-align: center;" rowspan="2">REF DOKUMEN</th>

							<th style="text-align: center;" rowspan="2">TANGGAL</th>

							<th style="text-align: center;" rowspan="2">KET</th>

							<th style="text-align: center;" colspan="3">PELUNASAN</th>

							<th style="text-align: center;" colspan="3">RETUR POT</th>

							<th style="text-align: center;" colspan="3">LAIN-LAIN</th>

							<th style="text-align: center;" rowspan="2">$US</th>

							<th style="text-align: center;" rowspan="2">KURS</th>

							<th style="text-align: center;" rowspan="2">IDR</th>

						</tr>

						<tr>

							<th style="text-align: center;">US$</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">IDR</th>

							<th style="text-align: center;">US$</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">IDR</th>

							<th style="text-align: center;">US$</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">IDR</th>

							<th style="text-align: center;">US$</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">IDR</th>

							<th style="text-align: center;">US$</th>

							<th style="text-align: center;">KURS</th>

							<th style="text-align: center;">IDR</th>

						</tr>

					</thead>
					<tbody>
						<td>1.</td>
						<td>10.10.1.001</td>
						<td>145</td>
						<td>KAS BANK</td>
						<td>25 Des 2019</td>
						<td>001</td>
						<td>25 Des 2019</td>
						<td>001</td>
						<td>25 Des 2020</td>
						<td>002</td>
						<td>1 Jan 2020</td>
						<td>003</td>
						<td>SO/001/2019</td>
						<td>1733</td>
						<td>12</td>
						<td>$1</td>
						<td>$1.5</td>
						<td>14444</td>
						<td>$1</td>
						<td>$1.4</td>
						<td>14221</td>
						<td>$2</td>
						<td>$2.4</td>
						<td>15001</td>
						<td>NAG/2019/001</td>
						<td>12 Des 2019</td>
						<td>LUNAs</td>
						<td>$0.1</td>
						<td>$0.22</td>
						<td>1312</td>
						<td>$0.2</td>
						<td>$0.33</td>
						<td>2165</td>
						<td>$0.3</td>
						<td>$0.44</td>
						<td>980</td>
						<td>$222</td>
						<td>$200</td>
						<td>1000000</td>
					</tbody>

				</table>
			</div>
		</div>
	</section>
</div>