<div class="container-fluid">

	<section class="content">

		<script type="text/javascript">function validasi(){var from = document.form.txtfrom.value;var to = document.form.txtto.value;if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.focus();valid = false;}else valid = true;return valid;exit;}</script><div class="box"><div class="box-body"><div class="row"><form method="post" name="form" action="?mod=19&amp;mode=Hist" onsubmit="return validasi()"><div class="col-md-3"><div class="form-group"><label>Dari Tanggal *</label><input type="text" class="form-control" id="datepicker1" name="txtfrom" placeholder="Masukkan Dari Tanggal" value="04 Dec 2019"></div><div class="form-group"><label>Sampai Tanggal *</label><input type="text" class="form-control" id="datepicker2" name="txtto" placeholder="Masukkan Sampai Tanggal" value="04 Dec 2019"></div><button type="submit" name="submit" class="btn btn-primary">Tampilkan</button></div></form></div></div></div>  

	</section>

	<!-- /.content -->

</div>

<div class="container-fluid">
	<section class="content">

		<div class="box">
			<div class="box-body">

				<table id="example1"  class="display responsive" style="width:100%;font-size:11px; border-collapse: collapse; outline: none; border: none;">

					<thead>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PT. ______________________________</strong></th>

						</tr>

						<tr>

							<th style="font-size: 16px; border-collapse: collapse; border: none;" colspan="14"><strong>KARTU HUTANG</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>NOMOR COA: _____________________</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>NAMA COA: _______________________</strong></th>

						</tr>

						<tr>

							<th style="font-size: 12px; border-collapse: collapse; border: none;" colspan="14"><strong>PERIODE: ________________________</strong></th>

						</tr>
					</thead>
				</table>

				<table id="example1"  class="display responsive" style="width:100%;font-size:11px;" border="1">
					<thead>

						<tr>

							<th colspan="6">&nbsp;</th>

							<th style="text-align: center;" colspan="3">ORIGINAL CURRENCY</th>

							<th style="text-align: center;" colspan="4">IDR</th>

						</tr>

						<tr style="text-align: center;">

							<th style="text-align: center;">No.</th>

							<th style="text-align: center;">TANGGAL</th>

							<th style="text-align: center;">NOMOR JURNAL</th>

							<th style="text-align: center;">NOMOR BPB</th>

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

						<tr>

							<th style="text-align: right; background-color: gray;" colspan="6">TOTAL MUTASI</th>

							<th>&nbsp;</th>

							<th>&nbsp;</th>

							<th colspan="5" style="background-color: gray;">&nbsp;</th>

						</tr>

					</thead>

				</table>
			</div>
		</div>
	</section>
</div>