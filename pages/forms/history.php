<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mod = $_GET['mod'];

if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}
if (isset($_GET['jenis_trans'])) {$jenis_trans = $_GET['jenis_trans']; } else {$jenis_trans = "All";}

if(isset($_POST['submit'])) //KLIK SUBMIT
{ $from=date('d M Y',strtotime($_POST['txtfrom']));
  $to=date('d M Y',strtotime($_POST['txtto']));
  $jenis_trans=$_POST['jenis_trans'];
  echo "<script>
    window.location.href='index.php?mod=$mod&from=$from&to=$to&jenis_trans=$jenis_trans';
    </script>";
}

if(isset($_POST['submitexc'])) //KLIK SUBMIT
{ $fromexc=date('d M Y',strtotime($_POST['txtfrom']));
  $toexc=date('d M Y',strtotime($_POST['txtto']));
  $jenis_trans=$_POST['jenis_trans'];
  echo "<script>
  window.open ('index.php?mod=777_exc&from=$fromexc&to=$toexc&jenis_trans=$jenis_trans&mode=exc&dest=xls', '_blank');
    </script>";
     // window.location.href='index.php?mod=777&from=$from&to=$to&jenis_trans=$jenis_trans&mode=exc&dest=xls';   
}


# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
/*
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";

echo "var tipe = document.form.txttipe.value;";
echo "var from = document.form.txtfrom.value;";
echo "var to = document.form.txtto.value;";

echo "if (tipe == '') { alert('Tipe tidak boleh kosong'); document.form.txttipe.focus();valid = false;}";
echo "else if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.style.backgroundColor='yellow'; document.form.txtfrom.focus();valid = false;}";
echo "else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.style.backgroundColor='yellow'; document.form.txtto.focus();valid = false;}";
echo "else valid = true;";
echo "return valid;";
echo "exit;";
  echo "}";
echo "</script>";
*/
# END COPAS VALIDASI
# COPAS ADD
// $from=date('d M Y');
// $to=date('d M Y');
 ?>
  <div class='box'>
    <div class='box-body'>  
      <div class='row'>
        <?php 
         echo "<form method='post' name='form'>";
        ?>
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Awal *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' 
              placeholder='Masukkan Dari Tanggal' value='<?php echo $from;?>'>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Akhir *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' 
              placeholder='Masukkan Sampai Tanggal' value='<?php echo $to; ?>'>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Jenis Transaksi *</label>
<select class='form-control select2' id='jenis_trans'  name='jenis_trans' value='<?php echo $jenis_trans;?>'>
    <option value="All" <?php if($jenis_trans=="All"){echo "selected";} ?>>All</option>
    <option value="Penerimaan" <?php if($jenis_trans=="Penerimaan"){echo "selected";} ?>>Penerimaan</option>
    <option value="Pengeluaran" <?php if($jenis_trans=="Pengeluaran"){echo "selected";} ?>>Pengeluaran</option>
    <option value="Konfirmasi" <?php if($jenis_trans=="Konfirmasi"){echo "selected";} ?>>Konfirmasi</option>
    <option value="Cancel" <?php if($jenis_trans=="Cancel"){echo "selected";} ?>>Cancel</option>
</select> 
        </div>                
        </div>
    </div>
    <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
    <button type='submit' name='submitexc' class='btn btn-success'>Export excel</button>
  </div>
        </form>
</div>
<?php
  # END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Log</h3>
  </div>
  <div class="box-body"> 
    <table id="tbl_list_in_out" border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>No</th>
            <th>Nomor Trans</th>
            <th>Tgl Input</th>
            <th>Username</th>
            <th>Ket</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d 23:59:59',strtotime($to));
            
        if ($from == '')
        {  
        $from_fix=date('Y-m-d');
        }
        else
        {
        $from_fix=$fromcri; 
        } 

        if ($to == '')
        {  
        $to_fix=date('Y-m-d 23:59:59');
        }
        else
        {
        $to_fix=$tocri; 
        }
        
        if ($jenis_trans == 'All')
        {
         $sql="select * from
(select bpbno_int no_trans,dateinput tgl_input ,username, 'Input Penerimaan' ket from bpb where dateinput >= '$from_fix' and dateinput <= '$to_fix' and bpbno_int != ''
union all
select bppbno_int no_trans,dateinput tgl_input,username, 'Input Pengeluaran' ket from bppb where dateinput >= '$from_fix' and dateinput <= '$to_fix' and bppbno_int != ''
union all
select bpbno_int no_trans,confirm_date tgl_input ,confirm_by username, 'Konfirmasi Penerimaan' ket  from bpb where confirm_date >= '$from_fix' and confirm_date <= '$to_fix' and bpbno_int != ''
union all
select bppbno_int no_trans,confirm_date tgl_input ,confirm_by username, 'Konfirmasi Pengeluaran' ket from bppb where confirm_date >= '$from_fix' and confirm_date <= '$to_fix' and bppbno_int != ''
union all
select bpbno_int no_trans,cancel_date tgl_input ,cancel_by, 'Cancel Penerimaan' ket from bpb where cancel_date >= '$from_fix' and cancel_date <= '$to_fix' and bpbno_int != ''
union all
select bppbno_int no_trans,cancel_date tgl_input ,cancel_by, 'Cancel Pengeluaran' ket from bppb where cancel_date >= '$from_fix' and cancel_date <= '$to_fix' and bppbno_int != ''
) a
group by a.no_trans
order by a.tgl_input desc
";
        }
        else 
        if ($jenis_trans == 'Penerimaan')
        {
         $sql="
select bpbno_int no_trans,dateinput tgl_input ,username, 'Input Penerimaan' ket from bpb where dateinput >= '$from_fix' and dateinput <= '$to_fix' and bpbno_int != ''
group by no_trans
order by tgl_input desc
";
        }
        else 
        if ($jenis_trans == 'Pengeluaran')
        {
         $sql="
select bppbno_int no_trans,dateinput tgl_input,username, 'Input Pengeluaran' ket from bppb where dateinput >= '$from_fix' and dateinput <= '$to_fix' and bppbno_int != ''
group by no_trans
order by tgl_input desc
";
        }
        else 
        if ($jenis_trans == 'Konfirmasi')
        {
         $sql="
select * from         
(select bpbno_int no_trans,confirm_date tgl_input ,confirm_by username, 'Konfirmasi Penerimaan' ket  from bpb where confirm_date >= '$from_fix' and confirm_date <= '$to_fix' and bpbno_int != ''
union all
select bpbno_int no_trans,cancel_date tgl_input ,cancel_by, 'Cancel Penerimaan' ket from bpb where cancel_date >= '$from_fix' and cancel_date <= '$to_fix' and bppbno_int != '') a
group by a.no_trans
order by a.tgl_input desc
";
        }
        else 
        if ($jenis_trans == 'Cancel')
        {
         $sql="
select * from         
(select bpbno_int no_trans,cancel_date tgl_input ,cancel_by, 'Cancel Penerimaan' ket from bpb where cancel_date >= '$from_fix' and cancel_date <= '$to_fix' and bpbno_int != ''
union all
select bppbno_int no_trans,cancel_date tgl_input ,cancel_by, 'Cancel Pengeluaran' ket from bppb where cancel_date >= '$from_fix' and cancel_date <= '$to_fix' and bppbno_int != '') a
group by a.no_trans
order by a.tgl_input desc
";
        }                          
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        echo "<td>$no</td>"; 
        echo "<td>$data[no_trans]</td>";
        $trans_date=date('d M Y h:i:s',strtotime($data['tgl_input']));
        echo "<td>$trans_date</td>";
        echo "<td>$data[username]</td>";
        echo "<td>$data[ket]</td>";
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>