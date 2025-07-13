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

{ $query = mysql_query("SELECT a.*,s.curr FROM po_header a inner join po_item s on a.id=s.id_po 

    where a.id='$id_po' limit 1");

  $data = mysql_fetch_array($query);

  $pono = $data['pono'];

  $jenis_item = $data['jenis'];

  $podate = fd_view($data['podate']);

  $etddate = fd_view($data['etd']);

  $disc = $data['discount'];

  $etadate = fd_view($data['eta']);

  $expdate = fd_view($data['expected_date']);

  $notes = $data['notes'];

  $n_kurs  = $data['n_kurs'];

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

  

  function select_all()

  { var chks = document.form.getElementsByClassName('chkclass');

    for (var i = 0; i < chks.length; i++) 

    { chks[i].checked = true; }

  };

  function add_item()

  { <?php echo "window.location.href='../pur/?mod=3za&id=$id_po';"; ?>

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

<?php if ($mod=="3" or $mod=="3z") { ?>

<div class='box'>

  <div class='box-body'>

    <div class='row'>

      <form method='post' name='form' action='s_po.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>

        <div class='col-md-3'>              

          <div class='form-group'>

            <label>PO #</label>

            <input type='text' disabled class='form-control' name='txtpono' placeholder='Masukkan PO #' value='<?php echo $pono;?>' >

            <input type='hidden' readonly class='form-control' name='txtconv' id='txtconv'>

          </div>        

          <div class='form-group'>

            <label>PO Date *</label>

            <input type='text' disabled class='form-control' id='datepicker1' name='txtpodate' placeholder='Masukkan PO Date' value='<?php echo $podate;?>' >

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

        </div>

        <div class='col-md-3'>          

          <div class='form-group'>

            <label>Currency *</label>

            <select class='form-control select2' disabled style='width: 100%;' name='txtcurr' onchange='getJOList()'>

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

                <select class='form-control select2' disabled style='width: 100%;' name='txtid_terms'>

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

                <input type='text' disabled class='form-control' name='txtdays' value='<?php echo $jml_pterms; ?>' >

              </div>

            </div>    

	            <div class='col-md-6'>

              <div class='form-group'>

                <label >Day Terms *</label>

                <select class='form-control select2' disabled style='width: 100%;' name='txtid_dayterms'>

                  <?php 

				  if($id_dayterms !="" and $id_dayterms!="0"){

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

                <input type='text' disabled class='form-control' id='datepicker2' 

                  name='txtetddate' placeholder='Masukkan ETD Date' value='<?php echo $etddate;?>' >

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>ETA Date *</label>

                <input type='text' disabled class='form-control' id='datepicker3' 

                  name='txtetadate' placeholder='Masukkan ETA Date' value='<?php echo $etadate;?>' >

              </div>

            </div>

          </div>

        </div>

        <div class='col-md-3'>

          <div class='form-group'>

            <label>Expected Date *</label>

            <input type='text' disabled class='form-control' id='datepicker4' 

              name='txtexpdate' placeholder='Masukkan Expected Date' value='<?php echo $expdate;?>' >

          </div>

          <div class='row'>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>Discount</label>

                <input type='text' disabled class='form-control' name='txtdisc' placeholder='Masukkan Discount' value='<?php echo $disc;?>' >

              </div>

            </div>

            <div class='col-md-6'>

              <div class='form-group'>

                <label>PPN %</label>

                <input type='text' disabled id='ppn_nya' readonly class='form-control' name='txtppn' 

                  placeholder='Masukkan PPN' value='<?php echo $tax;?>' > 

              </div>

            </div>

          </div>

          <div class='form-group'>

            <label>Notes</label>

            <textarea row='5'disabled  class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>

          </div>

          <?php if ($mod=="3") { ?>

          <div class='form-group'>

            <label>Job Order # *</label>

            <select class='form-control select2' disabled multiple='multiple' style='width: 100%;' 

              name='txtJOItem[]' id='cboJO' onchange='getJO()'>

            </select>

          </div>

          <?php } ?>

        </div>

	<div class='col-md-3'> 

		<input type="checkbox"  disabled <?=$checked ?> onclick="checkpkp()" name="pkp" id="pkp" value="<?=$pkp ?>" />PKP

		

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

            <input type='text' disabled class='form-control' id='n_kurs' 

              name='n_kurs' placeholder='Masukkan Kurs' value='<?php echo $n_kurs;?>'  >

          </div>

	</div>		

        <div class='box-body'>

          <div id='detail_item'></div>

        </div>

       
      </form>

    </div>



  </div>

</div><?php } 

# END COPAS ADD

#if ($id_po=="") {

if ($mod=="3L") {

?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">List PO</h3>

    <a href='../pur/?mod=3' class='btn btn-primary btn-s'>

      <i class='fa fa-plus'></i> New

    </a>

  </div>

  <div class="box-body">

    <table id="examplefix" class="display responsive" style="width:100%">

      <thead>
      <tr>
        <th>No</th>

        <th>PO #</th>

        <th>Rev</th>

        <th>PO Date</th>

        <th>Supplier</th>

        <th>P.Terms</th>

        <th>Buyer</th>

        <?php if($jenis_company=="VENDOR LG") { ?>

          <th>JO #</th>

        <?php } else { ?>

          <th>WS #</th>

        <?php } ?>

        <th>Style #</th>

        <th>Notes</th>

        <th>Kurs</th>

        <th>Status</th>
        <th></th>

        <th></th>

        <th></th>

      </tr>

      </thead>

      <tbody>

        <?php
  
        # QUERY TABLE

        $sql="select t_it_po_cx,t_it_po, if(t_it_po_cx=t_it_po,'Cancelled','') status_po,a.app,a.id,pono,podate,supplier,

          nama_pterms,tmppoit.buyer,a.notes,a.n_kurs,a.revise,tmppoit.kpno,tmppoit.jo_no,tmppoit.styleno,

          tmppoit.tqtypo,tmppoit.tqtybpb  

          from po_header a inner join 

          mastersupplier s on a.id_supplier=s.id_supplier inner join 

          masterpterms d on a.id_terms=d.id

          inner join 

          (select group_concat(distinct jo_no) jo_no,ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer,count(*) t_it_po,

            sum(if(poit.cancel='Y',1,0)) t_it_po_cx,sum(poit.qty) tqtypo,

            tmpbpb.tqtybpb from po_item poit 

            inner join jo_det jod on jod.id_jo=poit.id_jo 

            inner join jo on jo.id=jod.id_jo  

            inner join so on jod.id_so=so.id 

            inner join act_costing ac on so.id_cost=ac.id 

            inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 

            left join 

            (select id_po_item,sum(qty) tqtybpb from bpb group by id_po_item)

            tmpbpb on poit.id=tmpbpb.id_po_item 

            -- where poit.cancel='N'   

            group by poit.id_po

          ) tmppoit on tmppoit.id_po=a.id where a.jenis!='N' order by podate desc";

        $query = mysql_query($sql); 

      //  echo "<pre>".$sql."</pre>";

        $no = 1; 

        while($data = mysql_fetch_array($query))
        {

         if ($data['tqtybpb']>=$data['tqtypo']) 

          {$bgcol=" style='background-color: green; color:yellow;'";} 

          else if ($data['tqtybpb']<$data['tqtypo'] and $data['tqtybpb']>0) 

          {$bgcol=" style='background-color: yellow; font-color:green;'";} 

          else 

          {$bgcol="";}
//t_it_po_cx=t_it_po
        	if ($data['t_it_po_cx']== $data['t_it_po']){

        		$bgcol=" style='color: red !important;'";
        	}
          echo "

            <tr $bgcol>

              <td>$no</td>

              <td>$data[pono]</td>

              <td>$data[revise]</td>

              <td>".fd_view($data['podate'])."</td>

              <td>$data[supplier]</td>

              <td>$data[nama_pterms]</td>

              <td>$data[buyer]</td>";

              if($jenis_company=="VENDOR LG") 

              { echo "<td>$data[jo_no]</td>"; }

              else

              { echo "<td>$data[kpno]</td>"; }

              echo "

              <td>$data[styleno]</td>

              <td>$data[notes]</td>

              <td>$data[n_kurs]</td>

              <td>$data[status_po]</td>
             <td>";

              if($data['app']=="A") 

              { if($nm_file_pdf_po=="")

                { $nm_file_pdf_po = "pdfPO.php"; }

                echo "<a href='?mod=3z&id=$data[id]'

                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>";  

              }

              else

              {   ; 

              }

            echo "</td>

              <td>";

              if($data['app']=="A") 

              { if($nm_file_pdf_po=="")

                { $nm_file_pdf_po = "pdfPO.php"; }

                echo "<a href='$nm_file_pdf_po?id=$data[id]'

                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";

                

              }

              else

              { echo "<a href='?mod=3z&id=$data[id]'

                data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>"; 

              }

            echo "</td>";

            $cek=nb(flookup("username","userpassword","username='$user' and update_notes_po='1'"));

            if($cek=="")

            { echo "<td></td>"; }

            else

            { echo "<td><a href='?mod=3zn&id=$data[id]'

              data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i></a>

              </td>"; 

            }

          echo "

          </tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

    <i style='background-color:green;color:yellow;'>Qty BPB >= Qty PO</i>

    <i style='background-color:yellow;color:green;'>Qty BPB < Qty PO</i>

  </div>

</div>

<?php } else if (($mod=="3" or $mod=="3z") and $id_po!="") { ?>

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

        <th>Status</th>

        <th></th>

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

        { if($jenis_company=="VENDOR LG")

          { $query = mysql_query("select l.id,jo_no,concat(a.goods_code,' ',a.itemdesc,' ',

              a.color,' ',a.size) item, (l.qty*l.price)as totalin,

              l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,l.id_gen id_mat_cs  

              from po_item l inner join jo m on l.id_jo=m.id 

              inner join masteritem a on a.id_item=l.id_gen where l.id_po='$id_po'");

          }

          else

          { $query = mysql_query("select l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',

              d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',

              g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',ifnull(j.add_info,'')) item,

              l.qty,l.unit,l.curr,l.price,l.cancel,l.id_jo,e.id id_mat_cs, (l.qty*l.price)as totalin

              from po_item l inner join jo m on l.id_jo=m.id 

              inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group

              inner join mastertype2 d on s.id=d.id_sub_group

              inner join mastercontents e on d.id=e.id_type

              inner join masterwidth f on e.id=f.id_contents 

              inner join masterlength g on f.id=g.id_width

              inner join masterweight h on g.id=h.id_length

              inner join mastercolor i on h.id=i.id_weight

              inner join masterdesc j on i.id=j.id_color 

              and l.id_gen=j.id where l.id_po='$id_po'");

          }

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

          if ($data['price']>$cek) {$bgcol=" style='background-color: blue;color:yellow;'";} else {$bgcol="";}
          if ($data['cancel']=="Y"){$bgcol=" style='color: red;'";} else {$bgcol="";}

          echo "

          <tr $bgcol>

            <td>$no</td>

            <td>$data[jo_no]</td>

            <td>$data[item]</td>

            <td>".fn($data['qty'],2)."</td>

            <td>$data[unit]</td>

            <td>$data[curr]</td>

            <td>".fn($data['price'],2)."</td>";

            echo "<td>".fn($data['totalin'],2)."</td>";

            if($data['cancel']=="Y")

            {

             echo "<td>Canceled</td>"; }

            else

            { echo "<td></td>"; }

            if($data['cancel']=="N")

            { echo "

              <td>  </td>

              <td> </td>";

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

    <i style='background-color:blue;color:yellow;'>Price PO > Price Costing</i>

  </div>

</div>

<?php } ?>