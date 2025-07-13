<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



# START CEK HAK AKSES KEMBALI

$akses = flookup("purch_ord_gen","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI

if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}

$st_company = flookup("status_company","mastercompany","company!=''");

$id_item="";

# COPAS EDIT

if ($id_po=="")

{ $pono = "";

  $podate = date('d M Y');

  $etddate = date('d M Y');

  $etadate = date('d M Y');

  $expdate = date('d M Y');

  $pph = "0";

  $notes = "";

  $n_kurs = "";

  $tax="0";

  $id_supplier = "";

  $kode_dept = "";

  $id_terms = "";

  $jml_pterms = "0";

  $curr = "";

  $id_dayterms  = "";

  $pkp  = "0";

  $fg_installment  = "0";  

  $checked=""; 

  $d_installment =date('d M Y');

  $n_installment ="";

  $checked_installment="";

  $disabled = 'disabled';

  $disabled_installment = 'disabled';
}
else

{ $query = mysql_query("SELECT a.*,s.curr,f.kode_mkt FROM po_header a inner join po_item s 

    on a.id=s.id_po inner join reqnon_header d on s.id_jo=d.id 

    inner join userpassword f on d.username=f.username 

    where a.id='$id_po' limit 1");

  $data = mysql_fetch_array($query);

  $pono = $data['pono'];

  $podate = fd_view($data['podate']);

  $etddate = fd_view($data['etd']);

  $etadate = fd_view($data['eta']);

  $expdate = fd_view($data['expected_date']);

  $pph = $data['pph'];

  $notes = $data['notes'];

  $n_kurs = $data['n_kurs'];

  $tax = $data['tax'];

  $curr = $data['curr'];

  $id_supplier = $data['id_supplier'];

  $kode_dept = $data['kode_mkt'];

  $id_terms = $data['id_terms'];

  $jml_pterms = $data['jml_pterms'];

  $id_dayterms = $data['id_dayterms']; 

  $d_installment =fd_view($data['d_installment']);

  $n_installment =$data['n_installment']; 

  $fg_installment = $data['fg_installment'];

  $pkp  = $data['fg_pkp'];	

  if($pkp == '1'){	

	  $checked="checked";	

	  $disabled = '';

  }  

   if($fg_installment == '1'){	

	  $checked_installment="checked";	

	  $disabled_installment = '';

  }  

  

  

}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

?>

<script type='text/javascript'>





<?php 

if ($id_po!=""){ ?>

	$("#datepicker_installment").val("<?php $d_installment ?>");

	$("#datepicker_installment").datepicker().datepicker("setDate", "<?php $d_installment ?>");

	

	

<?php	

}





?>



	function checkpkp(){	

if ($('#pkp').is(':checked')) {	

	$("#pkp").val('1');	

	$("#triger_ppn").prop('disabled',false);

	$("#triger_ppn").val('10').trigger('change');

}	

else{	

	$("#pkp").val('0');	

	$("#triger_ppn").prop('disabled',true);

	$("#triger_ppn").val('').trigger('change');

	$("#ppn_nya").val('0');

}	

console.log($("#pkp").val());	

}	

  function calc_amt_po(that)	

  {	

  };	

  function calc_amt_po_err(that)	

	{ 	

    // INI ID JO DAN ID GEN JADI SAMA KLO ADA BEBERAPA JO	

		var qtyo = document.form.getElementsByClassName('qtyclass'); 	

	 // var pxo = document.form.getElementsByClassName('priceclass'); 	

	  var totamto = document.form.getElementsByClassName('totamtclass'); 	

	  var curr = document.form.txtcurr.value;	

	  var tanggalnya = $("#datepicker1").val();	

	  classprice = that.className;	

	  console.log(that.name);	

	  if(curr == "IDR"){	

		 $("input[name='"+that.name+"']").siblings().val(that.value);	

		 // $("."+classprice).parent('<td>').child('.totamtclass').val(tanggalnya);	

		  return false;	

		  	

	  } 	

	 	

	  var totqty = 0;	

//	  for (var i = 0; i < qtyo.length; i++) 	

//	  {	

		//..  if (Number(pxo[i].value) > 0)	

	   // { 	

	    	var pxnya = that.value;	

	      var totamtnya;	

	      jQuery.ajax	

		    ({  	

		      url: "../forms/ajax3.php?modeajax=conv_amt_to_usds",	

		      method: 'POST',	

		      data: {pricenya: pxnya,currnya: curr,tglnya :tanggalnya},	

		      dataType: 'json',	

		      success: function(response)	

		      { 	

		      	totamtnya = response;	

				$("input[name='"+that.name+"']").siblings().val(totamtnya[0]);	

		      	//console.log(totamtnya);	

		     	},	

		      error: function (request, status, error) 	

		      { alert(request.responseText); },	

		    });	

		//    totamto[i].value = totamtnya[0];	

	   // }	

	  	

	};	

  

  function select_all()

  { var chks = document.form.getElementsByClassName('chkclass');

    for (var i = 0; i < chks.length; i++) 

    { chks[i].checked = true; }

  };

  function validasi()

  { var podate = document.form.txtpodate.value;

    var id_supplier = document.form.txtid_supplier.value;

    var curr = document.form.txtcurr.value;

    var id_terms = document.form.txtid_terms.value;

		var day_terms = document.form.txtid_dayterms.value;

	var day = document.form.txtdays.value;	

    var id_jo = document.form.txtJOItem.value;

    var pilih = 0;

    var unitkos = 0;

    var qtykos = 0;

    var qtyover = 0;

    var chks = document.form.getElementsByClassName('chkclass');

    var units = document.form.getElementsByClassName('unitclass');

    var qtys = document.form.getElementsByClassName('qtyclass');

    var qtybtss = document.form.getElementsByClassName('qtybtsclass');

    for (var i = 0; i < chks.length; i++) 

    { if (chks[i].checked) 

      { pilih = pilih + 1;

        if (units[i].value == '')

        { unitkos = unitkos + 1; }

        if (qtys[i].value == '' || qtys[i].value <= '0')

        { qtykos = qtykos + 1; }

        if (qtys[i].value > qtybtss[i].value)

        { qtyover = qtyover + 1; }

      }

    }

    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

		else if (day == '') { swal({ title: 'Day Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false;}

	else if (day_terms == '') { swal({ title: 'Day Terms Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false;}	

    <?php if ($mod=="9") { ?>

    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (pilih == 0) { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>}); valid = false; }

    else if (unitkos > 0) { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }

    else if (qtykos > 0) { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }

    else if (qtyover > 0) { alert('Qty Melebihi Kebutuhan'); valid = true; }

    <?php } ?>

    else valid = true;

    return valid;

    exit;

  };



</script>

<?php

# END COPAS VALIDASI



# COPAS ADD

?>

<script type="text/javascript">

	

 function check_payment_terms(that){

	 console.log(that.value);

	 if(that.value == "1011" || that.value == "1012" ){

		 $( "#fg_installment" ).prop( "checked", true );

		 $( "#fg_installment" ).val("1");

		 $("#datepicker_installment").prop('disabled',false);

		  $("#n_installment").val(0);

		 $("#n_installment").prop('disabled',false); //date('d M Y')

	$("#datepicker_installment").val("<?php echo $d_installment ?>");

	$("#datepicker_installment").datepicker().datepicker("setDate", "<?php echo $d_installment ?>");		 

		 //document.getElementById("fg_installment").checked = true;

		 

	 }else{

		 $( "#fg_installment" ).prop( "checked", false );

		 $( "#fg_installment" ).val("0");

		 $("#datepicker_installment").prop('disabled',true);

		 $("#n_installment").val(''); 

		 $("#n_installment").prop('disabled',true); //date('d M Y')		

	$("#datepicker_installment").val("");			 

		 // document.getElementById("fg_installment").checked = false;

	 }

	 

 }



  function getReqList()

  { var kode_dept = $('#cboDept').val();

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_po.php?modeajax=get_list_req',

        data: {kode_dept: kode_dept},

        async: false

    }).responseText;

    if(html)

    { $("#cboJO").html(html); }

  };

  function getReq()

  { var id_jo = $('#cboJO').val();

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_po.php?modeajax=view_list_req',

        data: {id_jo: id_jo},

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    $(document).ready(function() {

      var table = $('#examplefix2').DataTable

      ({  scrollCollapse: true,

          paging: false,

          fixedColumns:   

          { leftColumns: 1,

            rightColumns: 1

          }

      });

	  check_payment_terms();

    });

    $(".select2").select2();

  };

</script>

<?php if ($mod=="9" or $mod=="9e") { ?>

<div class='box'>

  <div class='box-body'>

    <div class='row'>

      <form method='post' name='form' action='s_po.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>

        <div class='col-md-3'>              

          <div class='form-group'>

            <label>PO #</label>

            <input type='text' readonly class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >

          </div>        

          <div class='form-group'>

            <label>PO Date *</label>

            <input type='text' class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan PO Date' value='<?php echo $podate;?>' >

          </div>

          <div class='form-group'>

            <label>Kode Dept *</label>

            <select class='form-control select2' multiple='multiple' style='width: 100%;' 

              name='txtkode_dept' id='cboDept' onchange='getReqList()'>

              <?php

              $sql="select kode_mkt isi,kode_mkt tampil from userpassword

                where kode_mkt!='' group by kode_mkt order by kode_mkt";

              IsiCombo($sql,'','')

              ?>

            </select>

            <input type="hidden" name='txtJItem' value='N'>

          </div>

          <div class='form-group'>

            <label>Supplier *</label>

            <select class='form-control select2' style='width: 100%;' 

              name='txtid_supplier'>

              <?php

              $sql="select id_supplier isi,supplier tampil from mastersupplier

                where tipe_sup='S' and supplier!='' order by supplier";

              IsiCombo($sql,$id_supplier,'Pilih Supplier')

              ?>

            </select>

          </div>

        </div>

        <div class='col-md-3'>          

          <div class='form-group'>

            <label>Currency *</label>

            <select class='form-control select2' style='width: 100%;' name='txtcurr'>

              <?php 

                $sql = "select nama_pilihan isi,nama_pilihan tampil

                  from masterpilihan where kode_pilihan='Curr' order by nama_pilihan";

                IsiCombo($sql,$curr,'Pilih Currency');

              ?>

            </select>

          </div>

 <div class='row'>

            <div class='col-md-12'>

              <div class='form-group'>

                <label>Payment Terms *</label>

                <select onchange="check_payment_terms(this)" class='form-control select2' style='width: 100%;' name='txtid_terms'>

                  <?php 

                    $sql = "select id isi,concat(kode_pterms,'-',nama_pterms) tampil

                      from masterpterms where aktif='Y' ";

                    IsiCombo($sql,$id_terms,'Pilih Payment Terms');

                  ?>

                </select>

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>Days *</label>

                <input type='text' class='form-control' name='txtdays' value='<?php echo $jml_pterms; ?>' >

              </div>

            </div>    

	            <div class='col-md-6'>

              <div class='form-group'>

                <label >Day Terms *</label>

                <select class='form-control select2' style='width: 100%;' name='txtid_dayterms'>

                  <?php 

				  if($id_dayterms !=""){

                    $sql = "select id isi,concat(kode_pterms) tampil

                      from masterdayterms where id = '$id_dayterms'";

                    IsiCombo($sql,$id_dayterms,'Pilih Day Terms');					  

					  

					  

				  }

				  else{

                    $sql = "select id isi,concat(kode_pterms) tampil

                      from masterdayterms where aktif='Y' and is_deleted = 'N'";

                    IsiCombo($sql,$id_dayterms,'Pilih Day Terms');

                  }

				  ?>

                </select>

              </div>

            </div>			

          </div>

          <div class='row'>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>ETD Date *</label>

                <input type='text' class='form-control' id='datepicker2' 

                  name='txtetddate' placeholder='Masukkan ETD Date' value='<?php echo $etddate;?>' >

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>ETA Date *</label>

                <input type='text' class='form-control' id='datepicker3' 

                  name='txtetadate' placeholder='Masukkan ETA Date' value='<?php echo $etadate;?>' >

              </div>

            </div>

          </div>

          <div class='form-group'>

            <label>Expected Date *</label>

            <input type='text' class='form-control' id='datepicker4' 

              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >

          </div>

        </div>

        <div class='col-md-3'>

          <div class='row'>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>PPh</label>

                <input type='number' class='form-control' name='txtpph' placeholder='Masukkan PPh' value='<?php echo $pph;?>' >

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>PPN *</label>

                <input type='number' readonly id='ppn_nya' class='form-control' name='txtppn' 

                  placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 

              </div>

            </div>

          </div>

          <div class='form-group'>

                <label >Kurs </label>

        <input class="form-control" type="text" id="n_kurs" name="n_kurs" placeholder="Masukkan Nilai Kurs" value="<?php echo $n_kurs;?> ">

              </div>

          <div class='form-group'>

            <label>Notes</label>

            <textarea row='5' class='form-control' name='txtnotes' id='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>

          </div>

          <?php if ($mod=="9") { ?>

          <div class='form-group'>

            <label>Request # *</label>

            <select class='form-control select2' multiple='multiple' style='width: 100%;' 

              name='txtJOItem' id='cboJO' onchange='getReq()'>

            </select>

          </div>

              

		  

          <?php } ?>

        </div>

		<div class='col-md-3'> 	

		<input type="checkbox"  <?=$checked ?>  onclick="checkpkp()" name="pkp" id="pkp" value="<?=$pkp ?>" />PKP	

              <div class='form-group'>

                <label >Tax</label>

                <select class='form-control select2' <?=$disabled ?> id='triger_ppn' style='width: 100%;' onchange="$('#ppn_nya').val(this.value)" name='tax_nya'>

                  <?php 

                    $sql = "select percentage isi,concat(kriteria,' ',percentage) tampil

                      from mtax where category_tax = 'PPN'";

                    IsiCombo($sql,'','Pilih Tax');			

					  ?>

				  

                </select>

              </div>			

			  

			  

		<input type="checkbox"  <?=$checked_installment ?>  onclick="return false;" value="<?=$fg_installment ?>" id="fg_installment" name="fg_installment" id="fg_installment" value="<?=$fg_installment ?>" />Installment	

              <div class='form-group'>

                <label >Jumlah Cicilan</label>

				<input class="form-control" type="text" <?=$disabled_installment ?> id="n_installment" name="n_installment" value="<?=$n_installment?> ">

              </div>		

              <div class='form-group'>

                <label >Start Cicilan</label>

				<input class="form-control" type="text" <?=$disabled_installment ?> id="datepicker_installment" name="d_installment" value="<?=$d_installment?> ">

              </div>				  

	</div>			

        <div class='box-body'>

          <div id='detail_item'></div>

        </div>

        <div class='col-md-3'>

          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button> 

          <button type='button' class='btn btn-primary' onclick='select_all()'>Select All</button>

        </div>

      </form>

    </div>

  </div>

</div><?php } 

# END COPAS ADD

#if ($id_po=="") {

if ($mod=="9L") {
include "po_gen_page/HeaderPage.php";
?>


<?php } else if (($mod=="9" or $mod=="9e") and $id_po!="") { ?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">PO Detail</h3>

  </div>

  <div class="box-body">

    <table id="examplefix" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>No</th>

        <th>JO #</th>

        <th>Item</th>

        <th>Qty</th>

        <th>Unit</th>

        <th>Curr</th>

        <th>Price</th>

        <th>Total</th>

        <th></th>


      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $query = mysql_query("select l.cancel,l.id,reqno,itemdesc item,l.qty,l.unit,l.curr,l.price, (l.qty*l.price)as totalin 

        from po_item l inner join reqnon_header m on l.id_jo=m.id 

        inner join masteritem j on l.id_gen=j.id_item 

        where l.id_po='$id_po' "); 

        $no = 1; 

        while($data = mysql_fetch_array($query))

        { echo "<tr>";

            echo "

            <td>$no</td>

            <td>$data[reqno]</td>

            <td>$data[item]</td>

            <td>".fn($data['qty'],2)."</td>

            <td>$data[unit]</td>

            <td>$data[curr]</td>

            <td>".fn($data['price'],2)."</td>";

            echo "<td>".fn($data['totalin'],2)."</td>";

            if($data['cancel']=='Y')

            { echo "

              <td>Canceled</td>

              <td></td>";

            }

            else

            { echo "

              <td>

                <a href='?mod=9ei&id=$data[id]'

                  $tt_ubah</a>

              </td>

              <td>

                <a href='d_po.php?mod=$mod&id=$data[id]&idd=$id_po'

                  $tt_hapus";?> 

                  onclick="return confirm('Are You Sure Want To Cancel ?')">

                  <?php echo $tt_hapus2."</a>

              </td>";

            }

          echo "</tr>";

          $no++; // menambah nilai nomor urut

        }
        $sum=0;
        $sum_qty=0;
        $result_total_sum = mysql_query("SELECT SUM(qty*price) AS value_sum FROM po_item where id_po='$id_po' and cancel='N' group by id_po"); 
        $result_total_qty = mysql_query("SELECT SUM(qty) AS value_sum_qty FROM po_item where id_po='$id_po' and cancel='N' group by id_po"); 
        $row1 = mysql_fetch_assoc($result_total_sum); 
        $row2 = mysql_fetch_assoc($result_total_qty); 
        $sum = $row1['value_sum'];
        $sum_qty = $row2['value_sum_qty'];
        echo "<tr>";
        echo "<td colspan='3' style='text-align: right;'><b>SUB TOTAL</td><td><b>".fn($sum_qty,2)."<b></td>";
        echo "<td colspan='3'></td><td><b>".fn($sum,2)."<b></td>";
        echo "</tr>";

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } ?>