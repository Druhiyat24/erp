<?php 
    if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
?>

<style>
    th{
        text-align: center;
    }
</style>

<div class="box">
    <div class="box-body">
            
        <form action="" name="form" method="post">
                
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Output</label>
                        <input type='text' onchange="handlekeyup(this)" class='form-control' id='tanggal' 
                        name='tanggal' placeholder='Masukkan Tanggal Output' value='<?php echo date('d M Y') ?>' disabled>
                    </div>
                    <div class="form-group">
                        <label>Jam Output</label>
                        <input type='text' onchange="handlekeyup(this)" class='form-control' id='jam' 
                        name='jam' placeholder='Masukkan Jam Output' value='<?php echo date('H : i') ?>' disabled>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Line *</label>
                        <input type="text" onchange="handlekeyup(this)" class="form-control" id="line" 
                        name="line" placeholder="Line" value="" disabled>
                        <input type="hidden" id="id_line" name="" value="">
                    </div>
                    <div class="form-group">
                        <label>WS *</label>
                        <select class='form-control select2' id="ws" style='width: 100%;' name='txtJOItem' onchange="getList(this.value);getDetail(this.value);handlekeyup(this);">
                            <!-- <option selected disabled>--Choose WS--</option> -->
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Notes</label>
                        <input type='text' class='form-control' onkeyup ="handlekeyup(this)" name='txtnotes' id="notes"
                        placeholder='Masukkan Notes' value=''>
                    </div>

                    <!-- <div class="form-group">
                        <br>
                        <input type="button" value="Cari" class="btn btn-primary" name="cari" onclick="getDetail()">
                    </div> -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table id="detail_item" class="table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                	    	    <th>Buyer</th>
                                <th>Dest</th>
                                <th>Color</th>
            			        <th>Size</th>
                                <th>Unit</th>
                                <th>Qty Sewing In</th>
                                <th>Balance</th>
                                <th>Qty Sewing Out</th>
                            </tr>
                        </thead>
                    </table>

                    <!-- <div id="detail_item">
                    </div> -->
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col-md-3">
                    <a type='submit' name='submit' onclick="save()" class='btn btn-primary'>Simpan</a>
                    <!-- <button type="submit" class="btn btn-primary" name='cancel' onclick="Cancel()">Kembali</button> -->
                    <input type="button" value="Kembali" class="btn btn-primary" name="cancel" onclick="Cancel()">
                </div>
            </div>

            
        </form>
        
    </div>
</div>