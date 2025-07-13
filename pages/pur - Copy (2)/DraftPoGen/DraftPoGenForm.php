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



<label>kode_dept *</label> 



<select id="cboDept" multiple class='form-control select2' onchange='getReqList()' style='width: 100%;' 



 onchange='getListSupp(this.value)'> 





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



<select class='form-control ' id="curr" style='width: 100%;' name='txtcurr' > 







</select> 



</div> 



<div class='row'> 



<div class='col-md-12'> 



<div class='form-group'> 



<label>Payment Terms *</label> 



<select class='form-control select2' id="txtid_terms" style='width: 100%;' name='txtid_terms' onchange="check_payment_terms(this)"> 



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



<label>Pph</label> 



<input type='text' class='form-control' id="txtdisc" name='txtdisc' placeholder='Masukkan PPH' value='' > 



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



<label>Request # *</label> 



<select class='form-control select2' multiple='multiple' style='width: 100%;' 



name='txtJOItem[]' id='cboJO' onchange='getReq()'> 



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





<div class='form-group'>

		<input type="checkbox" onclick="return false;"  id="fg_installment" name="fg_installment" id="fg_installment" />Installment	

</div>

              <div class='form-group'>



                <label >Jumlah Cicilan</label>



				<input autocomplete="off" disabled class="form-control" type="text" id="n_installment" name="n_installment">



              </div>		



              <div class='form-group'>



                <label >Start Cicilan</label>



				<input autocomplete="off" disabled class="form-control" type="text" id="datepicker_installment" name="d_installment" >



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