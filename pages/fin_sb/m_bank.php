<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Bank","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_bank = $_GET['id']; } else {$id_bank = "";}
$titlenya="Master Bank";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_bank=="")
{ 
 $id_coa            = $data['id_coa'];
 $nm_coa            = $data['nm_coa'];
$bank            = "";
$bank_desc       = "";
$curr            = "";
$norek           = "";
$namarek         = "";
$v_company       ="";    
$v_companyaddress="";
$v_branch        ="";
$v_bankaddress   ="";
$v_swiftcode     ="";
}
else
{ $query = mysql_query("SELECT BANK.*,COA.nm_coa coa_name FROM masterbank BANK INNER JOIN (SELECT id_coa,nm_coa FROM mastercoa) COA
									ON BANK.id_coa = COA.id_coa
									WHERE BANK.id = '$id_bank'
									");
									
  $data = mysql_fetch_array($query);
 $id_coa            = $data['id_coa'];
 $coa_name            = $data['coa_name'];
$bank            = $data['kode_bank'];
$bank_desc       = $data['nama_bank'];
$curr            = $data['curr'];
$norek           = $data['no_rek'];
$namarek         = $data['nama_rek'];
$v_company       =$data['v_company'];    
$v_companyaddress=$data['v_companyaddress'];
$v_branch        =$data['v_branch'];
$v_bankaddress   =$data['v_bankaddress'];
$v_swiftcode     =$data['v_swiftcode'];
echo "<script>


	$( document ).ready(function() {
		getdefaulid();
		
	});
async function getdefaulid(){
	await $('#id_coa').val('".$id_coa."').trigger('change');
}
	
</script>
";
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { var bank = document.form.txtbank.value;
      var bank_desc = document.form.txtbank_desc.value;
      var curr = document.form.txtcurr.value;
      var norek = document.form.txtnorek.value;
      var namarek = document.form.txtnamarek.value;
      if (curr == '') { alert('Currency Tidak Boleh Kosong'); valid = false;}
      else if (bank == '') { alert('Bank Tidak Boleh Kosong'); document.form.txtbank.focus();valid = false;}
      //else if (bank_desc == '') { alert('Deskripsi Tidak Boleh Kosong'); document.form.txtbank_desc.focus();valid = false;}
       else if (norek == '') { alert('No Rekening Tidak Boleh Kosong'); document.form.txtnorek.focus();valid = false;}
      //else if (namarek == '') { alert('Nama Rekening Tidak Boleh Kosong'); document.form.txtnamarek.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bank.php?mod=$mod&id=$id_bank' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Bank Name*</label>
            <input type='text' class='form-control' name='txtbank' 
            placeholder='Masukkan Kode Bank' value='$bank'>
          </div>		


        	<div class='form-group'>
            <label>Bank Branch</label>
            <input type='text' class='form-control' name='v_branch' 
            placeholder='Masukkan Bank Branc' value='$v_branch'>			
		  </div>
		
		
        	<div class='form-group'>
            <label>Bank Address</label>
            <input type='text' class='form-control' name='v_bankaddress' 
            placeholder='Masukkan Bank Address' value='$v_bankaddress'>			
		  </div>		

          <div class='form-group' style='display:none'>
            <label>Deskripsi Bank</label>
            <input type='text' class='form-control' name='txtbank_desc' 
            placeholder='Masukkan Deskripsi Bank' value='$bank_desc'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>
          Simpan</button>
        </div>
        <div class='col-md-3'>
		
          <div class='form-group'>
            <label>Currency *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
            	where kode_pilihan='Curr'";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcurr'>";
            IsiCombo($sql,$curr,$cpil.' '.$c33);
            echo "</select>            
          </div>		
		
        	<div class='form-group'>
            <label>Bank Account No.</label>
            <input type='text' class='form-control' name='txtnorek' 
            placeholder='Masukkan No Rekening' value='$norek'>
          </div>


        	<div class='form-group'>
            <label>Swift Code</label>
            <input type='text' class='form-control' name='v_swiftcode' 
            placeholder='Masukkan Swift Code' value='$v_swiftcode'>			
		  </div>


          <div class='form-group' style='display:none'>
            <label>Nama Rekening</label>
            <input type='text' class='form-control' name='txtnamarek' 
            placeholder='Masukkan Nama Rekening' value='$namarek'>
          </div>  
		  ";
		  

        echo "
        </div>
		
		  
		  <div class='col-md-3'>
	

        	<div class='form-group'>
            <label>Beneficiary Name</label>
            <input type='text' class='form-control' name='v_company' 
            placeholder='Masukkan Beneficiary Name' value='$v_company'>			
		  </div>
	
	        	<div class='form-group'>
            <label>Beneficiary Address</label>
            <input type='text' class='form-control' name='v_companyaddress' 
            placeholder='Masukkan Beneficiary Address' value='$v_companyaddress'>			
		  </div>
	
	
	  
		  





		  
		  </div>		



		  <div class='col-md-3'>";
		  
			          echo "<div class='form-group'>";
            echo "<label>ID CoA</label>";
            $sql = "select id_coa isi,concat(id_coa,'|',nm_coa) tampil from mastercoa where id_coa > 11000 AND id_coa <= 11012";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcoa' id='id_coa' onchange='getNamaCoa(this)'>";
            IsiCombo($sql,$idcoa,' Pilih CoA');
            echo "</select>";
          echo "</div>";	  
		  
		  echo "
		  
        	<div class='form-group'>
            <label>COA Name</label>
            <input type='text' readonly onclick='getNamaCOA()' class='form-control' name='coa_name' 
            placeholder='Masukkan COA Name' id='coa_name' value='$coa_name'>			
		  </div>			  

		  


	

		  
		  </div>

		
		";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>

		<th>Bank Name </th>
		<th>Bank Branch </th>
		<th >Bank Account </th>
		<th>Currency </th>
		<th>Swift Code </th>
		<th >Beneficiary Name </th>

 </th>
		<th> </th>
		  <!--<th>No</th>
          <th>Curr</th>
          <th>Kode Bank</th>
          <th>Deskripsi Bank</th>
          <th>No. Rekening</th>
          <th>Nama Rekening</th>
          <th>ID CoA</th>
          <th></th>
          <th></th>
		  -->
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("SELECT BANK.*,COA.nm_coa coa_name FROM masterbank BANK INNER JOIN (SELECT id_coa,nm_coa FROM mastercoa) COA
									ON BANK.id_coa = COA.id_coa
			ORDER BY BANK.id DESC limit 500");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "
					<tr>
					
						<td>$data[nama_bank]</td>
						<td>$data[v_branch]</td>
						<td>$data[no_rek]</td>
						<td>$data[curr]</td>
						<td>$data[v_swiftcode]</td>
						<td>$data[v_company]</td>
			
			
			";
          echo "<td><a href='index.php?mod=$mod&id=$data[id]' $tt_ubah </a></td>";
				  echo "<td><a href='d_bank.php?mod=$mod&id=$data[id]' $tt_hapus";?> 
            onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $tt_hapus2."</a>";
          echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function getNamaCoa(Item){
	console.log(Item);
	x=$( "#id_coa option:selected" ).text();
	split = x.split("|");
	console.log(split);
	$("#coa_name").val(split[1]);
}
</script>