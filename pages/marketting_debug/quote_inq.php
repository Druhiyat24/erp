<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("quote_inq","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_quo = $_GET['id']; } else {$id_quo = "";}
$titlenya="Quotation Inquire";
$mode="";
$mod=$_GET['mod'];
$id_item="";

# COPAS EDIT
if ($id_quo=="")
{ $quote_no = "";
  $quote_date = "";
  $id_buyer = "";
  $id_bisnis = "";
  $id_payment = "";
  $season = "";
  $styleno = "";
  $styledesc = "";
  $qty = "";
  $unit = "";
  $curr = "";
  $price = "";
  $status = "";
  $valid_day = "";
  $attach_file = "";
}
else
{ $query = mysql_query("SELECT * FROM quote_inq where id='$id_quo' ORDER BY id ASC");
  $data = mysql_fetch_array($query);
  $quote_no = $data['quote_no'];
  $quote_date = fd_view($data['quote_date']);
  $id_buyer = $data['id_buyer'];
  $id_bisnis = $data['id_bisnis'];
  $id_payment = $data['id_payment'];
  $season = $data['season'];
  $styleno = $data['styleno'];
  $styledesc = $data['styledesc'];
  $qty = $data['qty'];
  $unit = $data['unit'];
  $curr = $data['curr'];
  $status = $data['status'];
  $price = $data['price'];
  $valid_day = $data['valid_day'];
  $attach_file = $data['attach_file'];
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
<script type='text/javascript'>
  function validasi()
  { var quote_date = document.form.txtquote_date.value;
    var id_buyer = document.form.txtid_buyer.value;
    var id_bisnis = document.form.txtid_bisnis.value;
    var id_payment = document.form.txtid_payment.value;
    var season = document.form.txtseason.value;
    var styleno = document.form.txtstyleno.value;
    var styledesc = document.form.txtstyledesc.value;
    var qty = document.form.txtqty.value;
    var unit = document.form.txtunit.value;
    var curr = document.form.txtcurr.value;
    var price = document.form.txtprice.value;
    var status = document.form.txtstatus.value;
    var valid_day = document.form.txtvalid_day.value;
    var attach_file = document.form.txtattach_file.value;

    if (quote_date == '') { document.form.txtquote_date.focus(); swal({ title: 'Quotation Date Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_buyer == '') { document.form.txtid_buyer.focus(); swal({ title: 'Buyer Name Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_bisnis == '') { document.form.txtid_bisnis.focus(); swal({ title: 'Business Type Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (id_payment == '') { document.form.txtid_payment.focus(); swal({ title: 'Payment Terms Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (season == '') { document.form.txtseason.focus(); swal({ title: 'Season Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (styleno == '') { document.form.txtstyleno.focus(); swal({ title: 'Style # Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (styledesc == '') { document.form.txtstyledesc.focus(); swal({ title: 'Style Description Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (qty == '') { document.form.txtqty.focus(); swal({ title: 'Qty Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (unit == '') { document.form.txtunit.focus(); swal({ title: 'Unit Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (curr == '') { document.form.txtcurr.focus(); swal({ title: 'Currency Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (price == '') { document.form.txtprice.focus(); swal({ title: 'Price Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (status == '') { document.form.txtstatus.focus(); swal({ title: 'Status Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (valid_day == '') { document.form.txtvalid_day.focus(); swal({ title: 'Valid Day Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function Ribuan()
  { var angkanya = document.form.txtqty.value;
  	jQuery.ajax
    ({  
      url: "ajax_quote_inq.php?mdajax=format_ribuan",
      method: 'POST', data: {angkanya: angkanya},
      dataType: 'json', 
      success: function(response)
      {	$('#txtqty').val(response[0]); },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<div class='box'>
 <div class='box-body'>
  <div class='row'>
   <form method='post' name='form' enctype='multipart/form-data' action='s_quote.php?mod=<?php echo $mod; ?>&id=<?php echo $id_quo; ?>' onsubmit='return validasi()'>
    <div class='col-md-3'>       
     <div class='form-group'>
      <label>Quotation #</label>
      <input type='text' class='form-control' name='txtquote_no' readonly placeholder='Masukkan Quotation #' value='<?php echo $quote_no;?>' >
     </div>    
     <div class='form-group'>
      <label>Quotation Date *</label>
      <input type='text' class='form-control' id='datepicker1' name='txtquote_date' placeholder='Masukkan Quotation Date' value='<?php echo $quote_date;?>' >
     </div>     
     <div class='form-group'>
      <label>Buyer Name *</label>
      <select class='form-control select2' style='width: 100%;' name='txtid_buyer'>
       <?php 
        $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
        IsiCombo($sql,$id_buyer,'Pilih Buyer Name');
       ?>
      </select>
     </div>     
     <div class='form-group'>
      <label>Business Type *</label>
      <select class='form-control select2' style='width: 100%;' name='txtid_bisnis'>
       <?php 
        $sql = "select nama_pilihan isi,nama_pilihan tampil from 
          masterpilihan where kode_pilihan='BUS_TYPE'";
        IsiCombo($sql,$id_bisnis,'Pilih Business Type');
       ?>
      </select>
     </div>
    </div>
    <div class='col-md-3'>     
     <div class='form-group'>
      <label>Payment Terms *</label>
      <select class='form-control select2' style='width: 100%;' name='txtid_payment'>
       <?php 
        $sql = "select id isi,nama_pterms tampil from masterpterms";
        IsiCombo($sql,$id_payment,'Pilih Payment Terms');
       ?>
      </select>
     </div>     
     <div class='form-group'>
      <label>Season *</label>
      <select class='form-control select2' style='width: 100%;' name='txtseason'>
       <?php 
        $sql = "select season isi,season tampil from 
          masterseason";
        IsiCombo($sql,$season,'Pilih Season');
       ?>
      </select>
     </div>    
     <div class='form-group'>
      <label>Style # *</label>
      <input type='text' class='form-control' name='txtstyleno' placeholder='Masukkan Style #' value='<?php echo $styleno;?>' >
     </div>    
     <div class='form-group'>
      <label>Style Description *</label>
      <input type='text' class='form-control' name='txtstyledesc' placeholder='Masukkan Style Description' value='<?php echo $styledesc;?>' >
     </div>
    </div>
    <div class='col-md-3'>    
     <div class='form-group'>
      <label>Qty *</label>
      <input type='text' class='form-control' id='txtqty' name='txtqty' onchange='Ribuan()' placeholder='Masukkan Qty' value='<?php echo $qty;?>' >
     </div>     
     <div class='form-group'>
      <label>Unit *</label>
      <select class='form-control select2' style='width: 100%;' name='txtunit'>
       <?php 
        $sql = "select nama_pilihan isi,nama_pilihan tampil from 
          masterpilihan where kode_pilihan='Satuan'";
        IsiCombo($sql,$unit,'Pilih Unit');
       ?>
      </select>
     </div>     
     <div class='form-group'>
      <label>Currency *</label>
      <select class='form-control select2' style='width: 100%;' name='txtcurr'>
       <?php 
        $sql = "select nama_pilihan isi,nama_pilihan tampil from 
          masterpilihan where kode_pilihan='Curr'";
        IsiCombo($sql,$curr,'Pilih Currency');
       ?>
      </select>
     </div>    
     <div class='form-group'>
      <label>Price *</label>
      <input type='text' class='form-control' name='txtprice' placeholder='Masukkan Price' value='<?php echo $price;?>' >
     </div>
    </div>
    <div class='col-md-3'>     
     <div class='form-group'>
      <label>Status *</label>
      <select class='form-control select2' style='width: 100%;' name='txtstatus'>
       <?php 
        $sql = "select nama_pilihan isi,nama_pilihan tampil from 
          masterpilihan where kode_pilihan='ST_INQ'";
        IsiCombo($sql,$status,'Pilih Status');
       ?>
      </select>
     </div>    
     <div class='form-group'>
      <label>Valid Day *</label>
      <input type='text' class='form-control' name='txtvalid_day' placeholder='Masukkan Valid Day' value='<?php echo $valid_day;?>' >
     </div>    
     <div class='form-group'>
      <label>Attach File *</label>
      <input type='file' name='txtattach_file' accept='.jpg'>
     </div>
     <div class='box-footer'>
      <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
     </div>
    </div>
   </form>
  </div>
 </div>
</div><?php 
# END COPAS ADD REMOVE </DIV> TERAKHIR
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" style='font-size:11px;' class="display responsive" style="width:100%">
      <thead>
      <tr>
	      <th>No</th>
        <th>Quotation #</th>
        <th>Quotation Date</th>
        <th>Buyer Name</th>
        <th>Business Type</th>
        <th>Payment Terms</th>
        <th>Season</th>
        <th>Style #</th>
        <th>Style Description</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Curr</th>
        <th>Price</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    if ($id_quo=="")
        { $sql_wh=""; }
        else
        { $sql_wh="where a.id='$id_quo'"; }
        $query = mysql_query("SELECT *,a.id idnya,supplier buyer,nama_pterms pterms 
          FROM quote_inq a inner join mastersupplier s on a.id_buyer=s.id_supplier
          inner join masterpterms d on a.id_payment=d.id 
          $sql_wh ORDER BY a.id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[quote_no]</td>";
            echo "<td>".fd_view($data['quote_date'])."</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[id_bisnis]</td>";
            echo "<td>$data[pterms]</td>";
            echo "<td>$data[season]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[styledesc]</td>";
            echo "<td>$data[qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>$data[price]</td>";
            echo "<td>$data[status]</td>";
            if ($data['status']=="BOOKING")
            { echo "<td><a $cl_ubah href='?mod=3&id=$data[idnya]'
                $tt_ubah</a></td>";
            }
            else
            { echo "<td></td>"; }
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>