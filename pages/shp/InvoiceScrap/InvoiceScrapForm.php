<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
?>

<style>
    .form{
        width: 65% !important;
        margin-bottom: 5px;
        height: 27px;
    }
    .select2{
        /* height: 27px !important; */
        margin-bottom: 5px;
    }
    th{
        text-align: center;
    }
</style>
<div class="box">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="col-md-3">
                    <label>Tanggal Input</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="dtpicker" class="form-control form" value="<?php echo date('j M Y'); ?>" readonly>
                </div>

                <div class="col-md-3">
                    <label>No BKB*</label>
                </div>
                <div class="col-md-9">
                    <select id="bkb" class="form-control select2 form" onchange="getDetail(this.value)" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label>No Invoice#</label>
                </div>
                <div class="col-md-9">
						<input type="text" id="noinvoice" class="form-control form" readonly placeholder="auto">
                </div>		
	                <div class="col-md-3">
                    <label>Bank </label>
                </div>
                <div class="col-md-9">
                    <select id="id_coa" class="form-control select2 form" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>			
            </div>
			
	            <div class="col-md-6">
                <div class="col-md-3">
                    <label>Buyer</label>
                </div>
                <div class="col-md-9">
						<input type="text" id="text_supplier" class="form-control form" readonly placeholder="auto">
                </div>

                <div class="col-md-3">
                    <label>Payment Terms #</label>
                </div>
                <div class="col-md-9">
                    <select id="id_terms" class="form-control select2 form" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">--Choose WS--</option> -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label>PPN</label>
                </div>
                <div class="col-md-9">
						<input type="checkbox" id="fg_ppn" >
                </div>		
				
            </div>		
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table id="inv_scrap_detail" class="table-bordered display responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>BKB#</th>
							<th>Tipe Scrap</th>
                            <th>Stock</th>
                            <th>Unit</th>
							<th>Qty BKB</th>
							<th>Curr</th>
                            <th>Qty</th>
                            <th>Price</th>
							<th>Discount(%)</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-md-3'>
                <a type='submit' name='submit' onclick="Save()" class='btn btn-primary'>Simpan</a>
                <a type='submit' name='cancel' onclick="Cancel()" class='btn btn-primary'>Kembali</a>
            </div>
        </div>
    </div>
</div>