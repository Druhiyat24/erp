<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }


# START CEK HAK AKSES KEMBALI

$akses = flookup("purch_ord","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='../pur/?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI

if (isset($_GET['id'])) {$id_po=$_GET['id'];} else {$id_po="";}

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

  $st_company = $rscomp["status_company"];

  $nm_company = $rscomp["company"];

  $nm_file_pdf_po = $rscomp["file_po"];

  $jenis_company = $rscomp["jenis_company"];

$id_item="";

# COPAS EDIT

if ($id_po=="") 

{ $pono = "";

  $podate = date('d M Y');

  $etddate = date('d M Y');

  $disc=0;

  $etadate = date('d M Y');

  $expdate = date('d M Y');

  $notes = "";

  $n_kurs = "";

  $tax="0"; 

  $id_supplier = "";

  $id_terms = "";

  $jml_pterms = "0";

  $curr = "";

  $id_dayterms  = "";

  $pkp  = "0";

  $checked="";

  $disabled = 'disabled';

}

else

{ 

  $query = mysql_query("SELECT a.*,s.curr FROM po_header_draft a inner join po_item_draft s on a.id=s.id_po_draft 

    where a.id='$id_po' limit 1");


  $data = mysql_fetch_array($query);

  $pono = $data['draftno'];

  $jenis_item = $data['jenis'];

  $podate = fd_view($data['draftdate']);

  $etddate = fd_view($data['etd']);

  $disc = $data['discount'];

  $etadate = fd_view($data['eta']);

  $expdate = fd_view($data['expected_date']);

  $notes = $data['notes'];

  $n_kurs  = $data['n_kurs'];

  $tipe_com  = $data['tipe_com'];

  $tax = $data['tax'];

  $curr = $data['curr'];

  $id_supplier = $data['id_supplier'];

  $id_terms = $data['id_terms'];

  $jml_pterms = $data['jml_pterms'];

  $id_dayterms = $data['id_dayterms'];

  $pkp  = $data['fg_pkp'];


  if($pkp == '1'){

	  $checked="checked";

	  $disabled = '';

  }

}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

?>

<script type='text/javascript'>

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

  
  function add_item()

  { <?php echo "window.location.href='../pur/?mod=33za&id=$id_po';"; ?>

  };

  function validasi() 

  { 

    var podate = document.form.txtpodate.value;

    var id_supplier = document.form.txtid_supplier.value;

	var day_terms = document.form.txtid_dayterms.value;

	var day = document.form.txtdays.value;

	var n_kurs = document.form.n_kurs.value;

    var curr = document.form.txtcurr.value;

    var id_terms = document.form.txtid_terms.value;

    var id_jo = $('#cboJO').val();

    var jenis_item = document.form.txtJItem.value;

    var pilih = 0;

    var unitkos = 0;

    var qtykos = 0;

    var qtyover = 0;

    var qtymin = 0;

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

        if (qtys[i].value > qtybtss[i].value + 1)

        { qtyover = qtyover + 1; }

        if (qtys[i].value < 0)

        { qtymin = qtymin + 1; }

      }

    }

    if (podate == '') { document.form.txtpodate.focus(); swal({ title: 'PO Date Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (jenis_item == '') { swal({ title: 'Jenis Item Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (id_supplier == '') { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (curr == '') { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (id_terms == '') { swal({ title: 'Payment Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

	else if (day == '') { swal({ title: 'Day Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false;}

	else if (n_kurs == '') { swal({ title: 'Kurs Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false;}

	else if (day_terms == '') { swal({ title: 'Day Terms Terms Tidak Boleh Kosong', <?php echo $img_alert; ?> }); return false;}

    <?php if ($mod=="3") { ?>

    else if (id_jo == null) { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (pilih == 0) { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>}); valid = false; }

    else if (unitkos > 0) { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }

    else if (qtykos > 0) { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>}); valid = false; }

    else if (qtymin > 0) { swal({ title: 'Qty Tidak Boleh Lebih Kecil Nol', <?php echo $img_alert; ?>}); valid = false; }

    else if (qtyover > 0) { alert('Qty Melebihi Kebutuhan'); valid = true; }

    <?php } ?>

    else valid = true;

    return valid;

    exit;

  };

  function getListSupp()

  { var jenis_item = document.form.txtJItem.value;

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_po.php?modeajax=get_list_supp',

        data: "jenis_item=" +jenis_item,

        async: false

    }).responseText;

    if(html)

    { $("#cbosupp").html(html); }

  };

  function getJOList()

  { var id_supp = document.form.txtid_supplier.value;

    var jenis_item = document.form.txtJItem.value;

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_po.php?modeajax=get_list_jo',

        data: {id_supp: id_supp,jenis_item: jenis_item},

        async: false

    }).responseText;

    if(html)

    { $("#cboJO").html(html); }

  };


$(document).ready( function () {
    $('#myTable').DataTable();
} );


  function getJO()

  { var id_jo = $('#cboJO').val();

    var id_supp = document.form.txtid_supplier.value;

    var jenis_item = document.form.txtJItem.value;

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_po.php?modeajax=view_list_jo',

        data: {id_jo: id_jo,id_supp: id_supp,jenis_item: jenis_item},

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

    });

    $(".select2").select2();

  };

</script>

<!-- # END COPAS VALIDASI

# COPAS ADD -->

<?php if ($mod=="3" or $mod=="33z") { ?>

<div class='box'>

  <div class='box-body'>

    <div class='row'>

<!--       <form method='post' name='form' action='s_po.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'> -->
        <form method='post' name='form' action='s_po_draft_edit_new.php?id_po=<?php echo $id_po; ?>' >

        <div class='col-md-3'>              

          <div class='form-group'>

            <label>DRAFT PO #</label>

            <input type='text' disabled class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >

            <input type='hidden' readonly class='form-control' name='txtconv' id='txtconv'>

          </div>        

          <div class='form-group'>

            <label>DRAFT PO Date *</label>

            <input type='text' class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan PO Date' value='<?php echo $podate;?>' >

          </div>

          <div class='form-group'>

            <label>Jenis Item *</label>

            <select class='form-control select2' style='width: 100%;' 

              name='txtJItem' disabled onchange='getListSupp()'>

              <?php

              if($jenis_item!="") 

              { if($jenis_item=="M")

                { $jenis_item2="Material"; }

                else

                { $jenis_item2="Others"; }

                $sql_fil=" and nama_pilihan='$jenis_item2'"; 

              } 

              else 

              { $sql_fil=""; }

              $sql = "SELECT if(nama_pilihan='Material','M','P') isi,if(nama_pilihan='Others','Manufacturing',nama_pilihan) tampil

                from masterpilihan where kode_pilihan='J_BOM_Det' $sql_fil ";

              IsiCombo($sql,$jenis_item,'Pilih Jenis Item');

              ?>

            </select>

          </div>

          <div class='form-group'>

            <label>Supplier *</label>

            <select class='form-control select2' style='width: 100%;' 

              name='txtid_supplier' disabled id='cbosupp'>

            <?php 

            if($id_supplier!="")

            { $sql="select id_supplier isi,supplier tampil from mastersupplier where id_supplier='$id_supplier'";

              IsiCombo($sql,$id_supplier,'Pilih Supplier');

            }

            ?>

            </select>

          </div>

          <div style='width: 400%;' class='form-group'>

            <label>List JO</label>
<?php
$sql1=mysql_query("select DISTINCT(jo_no) from po_item_draft a 
    inner join jo on a.id_jo = jo.id
    where id_po_draft = '$id_po'
    group by jo_no");
$jumlahdata = mysql_num_rows($sql1);
$jumlahdatafix = $jumlahdata + 2;   
?>

          <select class='form-control select'  multiple='multiple'  size='<?php echo $jumlahdatafix ?>'; name='listjo' style ='overflow-x: auto;'>
  <?php 
  $sql=mysql_query("select DISTINCT(jo_no),group_concat(jo_no , ' - ', ms.supplier, ' - ', ac.styleno) nm_jo from po_item_draft a 
    inner join jo on a.id_jo = jo.id
    inner join jo_det jd on jo.id = jd.id_jo
    inner join so on jd.id_so = so.id
    inner join act_costing ac on so.id_cost = ac.id
    inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
    where id_po_draft = '$id_po'
    group by jo_no");
  while ($datadet=mysql_fetch_array($sql)) {
 ?>
   <option value="<?=$datadet['nm_jo']?>"><?=$datadet['nm_jo']?></option> 
 <?php
  }
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

                <select class='form-control select2' style='width: 100%;' name='txtid_terms'>

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

                    $sql = "select id isi,concat(kode_pterms) tampil

                      from masterdayterms where aktif='Y' and is_deleted = 'N'";

                    IsiCombo($sql,$id_dayterms,'Pilih Day Terms');


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

        </div>

        <div class='col-md-3'>

          <div class='form-group'>

            <label>Expected Date *</label>

            <input type='text' class='form-control' id='datepicker4' 

              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >

          </div>

          <div class='row'>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>Discount</label>

                <input type='text' class='form-control' name='txtdisc' placeholder='Masukkan Discount' value='<?php echo $disc;?>' >

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>PPN %</label>

                <input type='text' id='ppn_nya' readonly class='form-control' name='txtppn' 

                  placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 

              </div>

            </div>

          </div>

          <div class='form-group'>

            <label>Notes</label>

            <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>

          </div>
        
        </div>

	<div class='col-md-3'> 

		<input type="checkbox"  <?=$checked ?> onclick="checkpkp()" name="pkp" id="pkp" value="<?=$pkp ?>" />PKP

		

              <div class='form-group'>

                <label >Tax</label>

                <select class='form-control select2' <?=disabled ?> id="triger_ppn" style='width: 100%;' onchange="$('#ppn_nya').val(this.value)" name='tax_nya'>

                  <?php 

                    $sql = "select percentage isi,concat(kriteria,' ',percentage) tampil

                      from mtax where category_tax = 'PPN'";

                    IsiCombo($sql,'','Pilih Tax');			

					  ?>

				  

                </select>

              </div>		

               <div class='form-group'>

            <label>Kurs *</label>

            <input type='text' class='form-control' id='n_kurs' 

              name='n_kurs' placeholder='Masukkan Kurs' value='<?php echo $n_kurs;?>'  >

          </div>

               <div class='form-group'>

            <label>Tipe Commercial *</label>

<!--             <input type='text' class='form-control' id='tipe_com' 

              name='tipe_com' placeholder='Masukkan Tipe' value='<?php echo $tipe_com;?>'  > -->

<select class='form-control select2' id='tipe_com'  name='tipe_com' value='<?php echo $tipe_com;?>'>

    <option value="REGULAR" <?php if($tipe_com=="REGULAR"){echo "selected";} ?>>REGULAR</option>
    <option value="FOC" <?php if($tipe_com=="FOC"){echo "selected";} ?>>FOC</option>
    <option value="BUYER" <?php if($tipe_com=="BUYER"){echo "selected";} ?>>BUYER</option>
  </select>              

          </div>          

	</div>		
       

    </div>



  </div>

</div><?php } 

# END COPAS ADD

#if ($id_po=="") {

if (($mod=="3" or $mod=="33z") and $id_po!="") { ?>

 <script type="text/javascript">
  function checkAll(ele) {
       var checkboxes = document.getElementsByTagName('input');
       if (ele.checked) {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].id == 'id_cancel' ) {
                   checkboxes[i].checked = true;
               }
           }
       } else {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].id == 'id_cancel') {
                   checkboxes[i].checked = false;
               }
           }
       }
   }
 </script>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">PO Detail</h3>

  </div>

  <div class="box-body">

    <table id="examplefix" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th hidden='hidden'> </th>

        <th>No</th>

        <th>WS #</th>

        <th>JO #</th>

        <th>Item</th>

        <th>Qty BOM</th>

        <th>Unit BOM</th>

        <th>Qty PO</th>

        <th>Unit</th>

        <th>Price</th>

        <th>Total</th>

        <th>Cancel<input type="checkbox" onchange="checkAll(this)" name="chk[]" ></th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        if($jenis_item=="P")

        { $query = mysql_query("select l.id,jo_no,concat(a.matclass,' ',a.goods_code,' ',a.itemdesc,' ',

            a.color,' ',a.size) item, (l.qty*l.price)as totalin,

            l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,l.id_gen id_mat_cs  

            from po_item l inner join jo m on l.id_jo=m.id 

            inner join masteritem a on a.id_item=l.id_gen 

            where l.id_po='$id_po'");

        }

          else

          { $query = mysql_query("select pid.id,ac.kpno,jo_no, mi.itemdesc, round(sum(sd.qty)*k.cons,2) qty_bom,k.unit unit_bom, pid.unit, pid.qty,pid.price, pid.qty * pid.price total , pid.cancel from po_header_draft phd 
inner join po_item_draft pid on phd.id = pid.id_po_draft
inner join jo_det jd on pid.id_jo = jd.id_jo
inner join jo on jo.id = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join masteritem mi on pid.id_gen = mi.id_gen
inner join so_det sd on sd.id_so = so.id
inner join bom_jo_item k on k.id_jo = pid.id_jo and k.id_item = pid.id_gen and k.id_so_det = sd.id
where id_po_draft = '$id_po'
group by pid.id_jo, pid.id_gen
");
          }


        $no = 1; 

        while($data = mysql_fetch_array($query))

        { if($jenis_item=="P")

          { $cek=flookup("acm.price","jo_det jod inner join so on jod.id_so=so.id 

              inner join act_costing_oth acm on so.id_cost=acm.id_act_cost ",

              "jod.id_jo='$data[id_jo]' and acm.id_item='$data[id_mat_cs]'");
          }

          else

          { $sql="select if(jenis_rate='B',price/rate_beli,price) cst_usd, 

      				if(jenis_rate='J',price*rate_jual,price) cst_idr 

      				from jo_det jod inner join so on jod.id_so=so.id 

              inner join act_costing act on act.id=so.id_cost inner join act_costing_mat acm on so.id_cost=acm.id_act_cost 

              inner join masterrate d on 'USD'=d.curr and act.cost_date=d.tanggal 

              where jod.id_jo='$data[id_jo]' and acm.id_item='$data[id_mat_cs]'";

          	$rscst=mysql_fetch_array(mysql_query($sql));

          	if ($data['curr']=="IDR")

          	{	$cek = round($rscst['cst_idr'],2);	}

          	else

          	{	$cek = round($rscst['cst_usd'],2);	}

          }

          $id = $data['id'];

          if ($data['price']>$cek) {$bgcol=" style='background-color: blue;color:yellow;'";} else {$bgcol="";}
          if ($data['cancel']=="Y"){$bgcol=" style='color: red;'"; $statuscheck="";} else {$bgcol="";$statuscheck="checked";}
          


          echo "

          <tr $bgcol>

            <td hidden='hidden'><input type='checkbox' id='id_cek' $statuscheck  name='id_cek[$id]' value='$data[id]'></td>

            <td>$no</td>



            <td>$data[kpno]</td>

            <td>$data[jo_no]</td>

            <td>$data[itemdesc]</td>

            <td>$data[qty_bom]</td>

            <td>$data[unit_bom]</td>

            <td><input style='width:65px;' name ='qty_po[$id]' value = '$data[qty]'></td>

            <td>$data[unit]</td>

            <td><input style='width:65px;' name ='price_po[$id]' value = '$data[price]'></td>

            <td>$data[total]</td>";

            if($data['cancel']=="Y")

            {

             echo "<td>Canceled</td>"; }

            else

            if($data['cancel']=="N")

            { 
              $statuscancel = "";
              echo "

              <td> <input type='checkbox' id='id_cancel' name='id_cancel[$id]' value='$data[cancel]'> </td>";

            }

          echo "</tr>";
          $no++; // menambah nilai nomor urut

        }
        $sum=0;
        $sum_qty=0;
        $sum_qty_bom=0;
        $result_total_sum = mysql_query("SELECT SUM(qty*price) AS value_sum FROM po_item_draft where id_po_draft='$id_po' and cancel='N' group by id_po_draft"); 
        $result_total_qty = mysql_query("SELECT SUM(qty) AS value_sum_qty FROM po_item_draft where id_po_draft='$id_po' and cancel='N' group by id_po_draft"); 
        $result_total_price = mysql_query("SELECT SUM(price) AS value_sum_price FROM po_item_draft where id_po_draft='$id_po' and cancel='N' group by id_po_draft");         
        $row1 = mysql_fetch_assoc($result_total_sum); 
        $row2 = mysql_fetch_assoc($result_total_qty);
        $row3 = mysql_fetch_assoc($result_total_price); 
        $sum = $row1['value_sum'];
        $sum_qty = $row2['value_sum_qty'];
        $sum_price = $row3['value_sum_price'];
        echo "<tr>";
        echo "<td colspan='6' style='text-align: right;'><b>SUB TOTAL Qty Non Cancel</td><td><b>".fn($sum_qty,2)."<b></td>";
        echo "<td></td><td><b>".fn($sum_price,2)."<b></td>";
        echo "<td><b>".fn($sum,2)."<b></td>";
        echo "</tr>";

        ?>
      </tbody>
    </table>
    <button type="submit" class='btn btn-primary' name='simpan_detail' > Update</button>
<!--     <a button type="submit" name="simpan_detail" class='btn btn-primary' href='s_po_draft_edit_new.php?id_po=<?php echo $id_po; ?>'>Update</a> -->
    <i style='background-color:blue;color:yellow;'>Price PO > Price Costing</i>
</form>
  </div>
</div>
<?php } ?>