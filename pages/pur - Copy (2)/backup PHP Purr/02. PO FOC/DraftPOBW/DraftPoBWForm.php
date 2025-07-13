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

<form method='post' name='form' > 

<div class='col-md-3'> 



<div class='form-group'> 



<label>DRAFT PO #</label> 



	<input type='text' readonly class='form-control' id="txtpono" name='txtpono' placeholder='Masukkan Draft PO #' > 



</div> 



<div class='form-group'> 



<label>Draft PO Date *</label> 



<input type='text' autocomplete="off" class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan Draft PO Date' value='' > 



</div> 



<div class='form-group'> 



<label>Jenis Item *</label> 



<select id="jenis_item" class='form-control select2' style='width: 100%;' 



name='txtJItem' onchange='getListSupp(this.value)'> 





</select> 



</div> 



<div class='form-group'> 



<label>Supplier *</label> 



<select class='form-control select2' style='width: 100%;' 



name='txtid_supplier' id='cbosupp'> 



<?php 



/* if($id_supplier!="") 



{ $sql="select id_supplier isi,supplier tampil from mastersupplier where id_supplier='$id_supplier'"; 



IsiCombo($sql,$id_supplier,'Pilih Supplier'); 



}  */



?> 



</select> 



</div> 



</div> 



<div class='col-md-3'> 



<div class='form-group'> 



<label>Currency *</label> 



<select class='form-control ' id="curr" style='width: 100%;' name='txtcurr' onchange='getJOList()'> 







</select> 



</div> 



<div class='row'> 



<div class='col-md-12'> 



<div class='form-group'> 



<label>Payment Terms *</label> 



<select class='form-control select2' id="txtid_terms" style='width: 100%;' name='txtid_terms'> 



</select> 



</div> 



</div> 



<div class='col-md-6'> 



<div class='form-group'> 



<label>Days *</label> 



<input type='text' id='txtdays' class='form-control' name='txtdays' value='' > 



</div> 



</div> 



<div class='col-md-6'> 



<div class='form-group'> 



<label >Day Terms *</label> 



<select class='form-control select2' style='width: 100%;' id="txtid_dayterms" name='txtid_dayterms'> 







</select> 



</div> 



</div> 



</div> 



<div class='row'> 



<div class='col-md-6'> 



<div class='form-group'> 



<label>ETD Date *</label> 



<input type='text' autocomplete="off" class='form-control' id='datepicker2' 



name='txtetddate' placeholder='Masukkan ETD Date' value='' > 



</div> 



</div> 



<div class='col-md-6'> 



<div class='form-group'> 



<label>ETA Date *</label> 



<input type='text' autocomplete="off" class='form-control' id='datepicker3' 



name='txtetadate' placeholder='Masukkan ETA Date' value='' > 



</div> 



</div> 



</div> 



</div> 



<div class='col-md-3'> 



<div class='form-group'> 



<label>Expected Date *</label> 



<input type='text' class='form-control' id='datepicker4' 



name='txtexpdate' autocomplete="off" placeholder='Masukkan Expected Date' value='' > 



</div> 



<div class='row'> 



<div class='col-md-6'> 



<div class='form-group'> 



<label>Discount</label> 



<input type='text' class='form-control' id="txtdisc" name='txtdisc' placeholder='Masukkan Discount' value='' > 



</div> 



</div> 



<div class='col-md-6'> 



<div class='form-group'> 



<label>PPN %</label> 



<input type='text' id='ppn_nya' readonly class='form-control' name='txtppn' 



placeholder='Masukkan PPN' value='' > 



</div> 



</div> 



</div> 



<div class='form-group'> 



<label>Notes</label> 



<textarea row='5' class='form-control' id="txtnotes" name='txtnotes' placeholder='Masukkan Notes'></textarea> 



</div> 







<div class='form-group'> 



<label>Job Order # *</label> 



<select class='form-control select2' multiple='multiple' style='width: 100%;' 



name='txtJOItem[]' id='cboJO' onchange='getJO()'> 



</select> 



</div> 



</div> 



<div class='col-md-3'> 



<input type="checkbox"  onclick="checkpkp()" name="pkp" id="pkp" value="" />PKP 



<div class='form-group'> 



<label >Tax</label> 



<select class='form-control select2' id="triger_ppn" style='width: 100%;' onchange="" name='tax_nya'> 





</select> 



</div> 



<div class='form-group'> 



<label>Kurs *</label> 



<input type='text' class='form-control' id='n_kurs' 



name='n_kurs' placeholder='Masukkan Kurs' value='' > 



</div> 



</div> 



<div class='box-body'> 



<div id='detail_item'></div> 



</div> 



<div class='col-md-3'> 





<a href="#"  class='btn btn-primary' onclick="Save()">Simpan</a>

<button type='button' class='btn btn-primary' onclick='select_all()'>Select All</button> 







</div> 



</form> 

    </div>

</div>