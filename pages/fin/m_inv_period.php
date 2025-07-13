<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Acc_Period","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_periode = $_GET['id']; } else {$id_periode = "";}
$titlenya="Master Inventory Periode";
$mod=$_GET['mod'];

# COPAS EDIT
if ($id_periode=="")
{ 
$id_periode     = "";
$gudang         = "";
$tglawal        = "";
$tglakhir       = "";
$status         = "disabled";
}
else
{ $query = mysql_query("SELECT * from tptglperiode
									WHERE idx = '$id_periode'
									");
									
  $data = mysql_fetch_array($query);
$id_periode     = $data['idx'];
$gudang         = $data['gudang'];
$tglawal        = $data['tgl1'];
$tglakhir       = $data['tgl2'];
$status         = "";
echo "<script>
</script>
";
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
    function validasi()
    { 
      var id_periode = document.form.txtid_periode.value;
      var gudang = document.form.txtgudang.value;
      var tglawal = document.form.txttglawal.value;
      var tglakhir = document.form.txttglakhir.value;
      else if (tglawal == '') { alert('Tgl Awal Tidak Boleh Kosong'); document.form.txttglawal.focus();valid = false;}
      else if (tglakhir == '') { alert('Tgl Akhir Tidak Boleh Kosong'); document.form.txttglakhir.focus();valid = false;}
      else valid = true;
      return valid;
      exit;
    }
  </script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-header'>";
  echo  "<h3 class='box-title'>Data Master Inventory Period</h3>";
      echo "</div>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_inv_period.php?mod=$mod&id=$id_periode' onsubmit='return validasi()'>";
        echo "
        <div class='col-md-3'>
          <div class='form-group'>
            <label>ID*</label>
            <input type='text' class='form-control' name='txtid_periode' readonly
           value='$id_periode'>
          </div>		


        	<div class='form-group'>
            <label>Gudang</label>
            <input type='text' class='form-control' name='txtgudang' readonly 
            placeholder='Masukan Gudang' value='$gudang'>			
		  </div>
		
		
        	<div class='form-group'>
            <label>Tgl Awal</label>
            <input type='date' class='form-control' name='txttglawal' 
            placeholder='Masukkan Tgl Awal' value='$tglawal'>			
		  </div>		

          <div class='form-group'>
            <label>Tgl Akhir</label>
            <input type='date' class='form-control' name='txttglakhir' 
            placeholder='Masukkan Tgl Akhir' value='$tglakhir'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary' $status>
          Simpan</button>
        </div>
        <div class='col-md-3'>
		  ";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
      <h3 class="box-title">List <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>

		<th>ID </th>
		<th>Jenis Gudang</th>
		<th >Tgl. Awal</th>
		<th>Tgl. Akhir</th>
		<th>Aksi </th>

 </th>
		<th> </th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
		    $query = mysql_query("select * from tptglperiode");
				while($data = mysql_fetch_array($query))
			  {
        $tglawal = date('d-m-Y',strtotime($data['tgl1']));
        $tglakhir = date('d-m-Y',strtotime($data['tgl2']));
         echo"
					<tr>
					
						<td>$data[idx]</td>
						<td>$data[gudang]</td>
						<td>$tglawal</td>
						<td>$tglakhir</td>		
			";
          echo "<td><a href='index.php?mod=$mod&id=$data[idx]' $tt_ubah </a></td>";
          echo "</tr>";
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>