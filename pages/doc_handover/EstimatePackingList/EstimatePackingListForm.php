<?php 
if (empty($_SESSION['username'])) { header("location:../../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("invoice","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI


?>
<form method='post' name='form' action='s_inv.php?mod=<?php echo $mod; ?>' 
  onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>No SO</label>
            <select class='form-control select2' id="so_no" style='width: 100%;'
              onchange="handlekeyup(this)">
            </select>
          </div>      
          <div class='form-group'>
            <label>No.Invoice</label>
            <select class='form-control select2'  id="id_invoiceheader" style='width: 100%;'
              onchange="handlekeyup(this)">
            </select>
          </div> 		  
          <div class='form-group'>
            <label>From</label>
            <input type='text' onkeyup="handlekeyup(this)" class='form-control' id='from'  placeholder='Masukkan Alamat Awal'  value=' ' >
          </div>          
          <div class='form-group'>
            <label>To</label>
            <input type='text' onkeyup="handlekeyup(this)" class='form-control' id='to'  placeholder='Masukkan Alamat Tujuan' value=' ' >
          </div>       
          <div class='form-group'>
            <label>Amount</label>
            <input type='text' class='form-control' onkeyup="handlekeyup(this)" id="amount" placeholder='Masukkan Amount' value='' >
          </div>   
          <div class='form-group'>
            <label>PO</label>
            <input type='text' class='form-control' onkeyup="handlekeyup(this)" id="po" placeholder='Masukkan PO' value='' >
          </div>		  
        <div class='col-md-3'>
          <a href="#" onclick="Save()" class='btn btn-primary'>Simpan</a>
        </div> 
	   </div>
	   

	 </div>
    </div>
  </div>
</form>


