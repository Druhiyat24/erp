<div class="container-fluid">

	<section class="content-header">

	</section>

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

							<th colspan="13"  style="font-size: 12px; border-collapse: collapse; border: none;"><b>PT. __________________________</b></th>

						</tr>

						<tr>

							<th colspan="13"  style="font-size: 16px; border-collapse: collapse; border: none;"><b>MUTASI PIUTANG DAGANG EKSPOR</b></th>

						</tr>

						<tr>

							<th colspan="13"  style="font-size: 12px; border-collapse: collapse; border: none;"><b>PERIOD: _____________________</b></th>

						</tr>

						<tr>

							<th colspan="13" style="font-size: 12px; border-collapse: collapse; border: none;">&nbsp;</th>

						</tr>
					</thead>
				</table>

				<table id="example1"  class="display responsive" style="width:100%;font-size:11px;" border="1"> 
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

				</table>
			</div>
		</div>
	</section>
</div>