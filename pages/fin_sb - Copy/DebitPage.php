<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_debit = $_GET['id']; } else {$id_debit = "";}
if (isset($_GET['pro'])) { $pro=$_GET['pro']; } else { $pro=""; }

// $titlenya="Master Tunjangan Masa Kerja";
$mode="";
$mod=$_GET['mod'];
// $tgl_skrg=date('Y-m-d');


	$carikode = mysql_query("SELECT kd_debit from tbl_debit");
    $datakode = mysql_fetch_array($carikode);
    $jumlah_data = mysql_num_rows($carikode);

    $nilaikode = substr($jumlah_data[0], 4);
    $kode = (int) $nilaikode;
    $kode = $jumlah_data + 1;
    $kode_otomatis = "D-".str_pad($kode, 3, "0", STR_PAD_LEFT);


	
# COPAS EDIT
if ($id_debit=="")
{ 
  $kd_debit ="";
  $no_invoice ="";
  $attn ="";
  $tanggal ="";
  $SKU ="";
  $price ="";
  $qty ="";
  $total_amount ="";
  $nopo ="";
  $notes ="";
  $nm_tempat ="";
  $nm_kantor ="";
  $nm_bank ="";
  $bank_alamat ="";
  $country ="";
  $city ="";
  $act_usd ="";
  $swift_code ="";
  $nm_buyer ="";

  
}
else
{ $query = mysql_query("SELECT * FROM tbl_debit where kd_debit='$id_debit'");
  $data = mysql_fetch_array($query);
  $kd_debit = $_GET['id'];
  $no_invoice = $data['no_invoice'];
  $attn = $data['attn'];
  $tanggal = date('d M Y');
  $price = $data['price'];
  $qty = $data['qty'];
  $total_amount = $data['total_amount'];
  $nopo = $data['nopo'];
  $notes = $data['notes'];
  $nm_tempat = $data['nm_tempat'];
  $nm_kantor = $data['nm_kantor'];
  $nm_bank = $data['nm_bank'];
  $bank_alamat = $data['bank_alamat'];
  $country = $data['country'];
  $city = $data['city'];
  $act_usd = $data['act_usd'];
  $swift_code = $data['swift_code'];
  $nm_buyer = $data['nm_buyer'];
  $SKU = $data['SKU'];
 
  
 if ($pro=="Copy") { $id_debit=""; }
}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { 
		var kd_debit  = document.form.txtkddebit.value;
		var no_invoice = document.form.txtno_invoice.value;
		var attn = document.form.txtattn.value;
		var tanggal = document.form.txttanggal.value;
		var price = document.form.txtprice.value;
		var qty = document.form.txtqty.value;
		var total_amount  = document.form.txttotal_amount.value;
		var nopo  = document.form.txtnopo.value;
		var notes  = document.form.txtnotes.value;
		var nm_tempat  = document.form.txtnm_tempat.value;
		var nm_kantor  = document.form.txtnm_kantor.value;
		var nm_bank  = document.form.txtnm_bank.value;
		var bank_alamat  = document.form.txtbank_alamat.value;
		var country = document.form.txtcountry.value;
		var city  = document.form.txtcity.value;
		var act_usd  = document.form.txtact_usd.value;
		var swift_code  = document.form.txtswift_code.value;
		var nm_buyer  = document.form.txtnm_buyer.value;
		var SKU  = document.form.txtSKU.value;
	
      if (kd_debit == '') {
         alert('Kode Tunjangan Tidak Boleh Kosong'); document.form.txtkd_debit.focus();valid = false;
       }
       else if (price == '') {
         alert('price Tidak Boleh Kosong'); document.form.txtprice.focus();valid = false;
       }
      else if (notes == '') {
        alert('notes Tidak Boleh Kosong'); document.form.txtnotes.focus();valid = false;
      }
      else if (nopo == '') {
        alert('nopo Tidak Boleh Kosong'); document.form.txtnopo.focus();valid = false;
      }

      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI
echo "<script type='text/javascript'>
		function sum() {
			var price = document.getElementById('price').value;
			var qty = document.getElementById('qty').value;
			var result = parseFloat(price) * parseFloat(qty);
      if (!isNaN(result)) {
         document.getElementById('txt4').value = result;
      }
}
	</script>";

# COPAS ADD
if ($mod=="debit2") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_debit.php?id=$id_debit' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
              <input type='hidden' class='form-control' name='txtkddebit' value='$kode_otomatis' readonly>";
        echo "
              <div class='form-group'>
                    <label>Nama Buyer</label>
                    <input type='text' class='form-control' id='nm_buyer' name='txtnm_buyer' value='$nm_buyer'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>Nama Tempat</label>
                    <input type='text' class='form-control' style='height:70px;' id='nm_tempat' name='txtnm_tempat' value='$nm_tempat'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>ATTN</label>
                    <input type='text' class='form-control' id='attn' name='txtattn' value='$attn'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>NO Invoice</label>
                    <input type='text' class='form-control' id='no_invoice' name='txtno_invoice' value='$no_invoice'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>Tanggal</label>
					<input type='text' class='form-control' id='datepicker1' name='txttanggal' placeholder='Masukkan tanggal' value='$tanggal'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>Description</label>
                    <input type='text' class='form-control' id='notes' name='txtnotes' value='$notes'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>NO. PO</label>
                    <input type='text' class='form-control' id='nopo' name='txtnopo' value='$nopo'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>SKU</label>
                    <input type='text' class='form-control' id='SKU' name='txtSKU' value='$SKU'>
              </div>";
		echo "
              <div class='form-group'>
                    <label>QTY IN PCS</label>
                    <input type='text' class='form-control' id='qty' name='txtqty' value='$qty' onkeyup='sum();'/>
              </div>";
        echo "
              <div class='form-group'>
                    <label>Price</label>
                    <input type='text' class='form-control' id='price' name='txtprice' value='$price' onkeyup='sum();'/>
              </div>";
		echo "
              <div class='form-group'>
                    <label>Total Amount</label>
                    <input type='text' class='form-control' id='txt4' name='txttotal_amount' readonly>
              </div></div>";
			  
			  
		echo "
         <div class='col-md-6'>
            <div class='form-group'>
                    <label>Nama Kantor</label>
                    <input type='text' class='form-control' id='price' name='txtnm_kantor' value='$nm_kantor'>
            </div>";
		echo"
         <div class='form-group'>
                    <label>Nama Bank</label>
                    <input type='text' class='form-control' id='nm_bank' name='txtnm_bank' value='$nm_bank'>
            </div>";
		echo"
         <div class='form-group'>
                    <label>Alamat Bank</label>
                    <input type='text' class='form-control' id='bank_alamat' name='txtbank_alamat' value='$bank_alamat'>
            </div>";
		echo"
         <div class='form-group'>
                    <label>Bank City</label>
                    <input type='text' class='form-control' id='city' name='txtcity' value='$city'>
            </div>";   
		echo"
         <div class='form-group'>
                    <label>Country Bank</label>
                    <input type='text' class='form-control' id='country' name='txtcountry' value='$country'>
            </div>";
		echo"
         <div class='form-group'>
                    <label>Account USD</label>
                    <input type='text' class='form-control' id='act_usd' name='txtact_usd' value='$act_usd'>
            </div>";
		echo"
         <div class='form-group'>
                    <label>SWIFT Code</label>
                    <input type='text' class='form-control' id='swift_code' name='txtswift_code' value='$swift_code'>
            </div>
    <br>&nbsp;&nbsp;
       <button type='submit' name='submit' class='btn btn-primary'>
       Simpan</button>
    </div>";


      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
} else {
?>
<div class="box">
  <div class="box-header">
      <!--<h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>-->
      <a href='../fin/?mod=debit2' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
		  <th>NO</th>
          <th>NO. INVOICE</th>
		  <th>DESCRIPTION</th>
          <th>Tanggal</th>
          <th>QTY IN PCS</th>
          <th>PRICE</th>
          <th>NO. PO</th>
          <th>NAMA BUYER</th>
          <th>TOTAL AMOOUNT</th>
          <th>ACTION</th>
      </tr>
      </thead>
      <tbody>

        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT * FROM tbl_debit");
			  $no = 1;
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>";
          echo "<td>$data[no_invoice]</td>";
		  echo "<td>$data[notes]</td>";
          echo "<td>$data[tanggal]</td>";
          echo "<td>$data[qty]</td>";
          echo "<td>$data[price]</td>";
          echo "<td>$data[nopo]</td>";
          echo "<td>$data[nm_buyer]</td>";
          echo "<td>$data[total_amount]</td>";
          echo "
          <td>
            <a class='btn btn-primary btn-s' href='?mod=debit2&id=$data[kd_debit]' $tt_ubah</a>
			<a class='btn btn-warning btn-s' href='pdfdebit.php?id=$data[kd_debit]' 
            data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
            </a>
          </td>";
          echo "</tr>";
				  $no++;
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
