<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$tglinputawal = date('Y-m-d');
$tglinputakhir = date('Y-m-d');

if (isset($_POST['submitfilter']))
{
  $tglinputawal = $_POST['tglinputawal'];
  $tglinputakhir = $_POST['tglinputakhir'];
}
# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("mut_rak","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="mrL")
{
?>
  <!-- Jika nantinya ada list -->
<?php } else { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List History Adjustment Qty Rak</h3>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>Tgl Input (Awal) : </label>
        <input type='date' class='form-control' id='tglinputawal' name='tglinputawal' placeholder='Masukan Tgl Input' 
        value='<?php echo $tglinputawal;?>'>    
      </div>
      <div class='col-md-2'>                            
        <label>Tgl Input (Akhir) : </label>
        <input type='date' class='form-control' id='tglinputakhir' name='tglinputakhir' placeholder='Masukkan Tgl Akhir' 
        value='<?php echo $tglinputakhir;?>'>    
      </div>      
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submitfilter' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>




  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
        <tr>
          <th>Tgl Input</th>
          <th>WS #</th>
          <th>Style #</th>
          <th>Supplier</th>
          <th>Kode Item</th>
          <th>Nama Item</th>
          <th>Color Item</th>
          <th>Roll #</th>
          <th>Lot #</th>
          <th>Qty Lama</th>
          <th>Qty Baru</th>
          <th>Unit</th>
          <th>Rak</th>
          <th>User</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql="select brd.id,ac.kpno,ac.styleno,brh.bpbno,brh.id_jo,brh.id_item,mi.goods_code,mi.color,concat(replace(mi.itemdesc,mi.color,''),' ',mi.add_info) itemdesc,roll_no,lot_no, sisa_old, sisa_new,brd.unit,concat(mr.kode_rak,' ',mr.nama_rak) rak_loc, user,tgl_input
from bpb_roll_h brh
inner join adj_rak_log brd on brh.id=brd.id_h
inner join master_rak mr on brd.id_rak_loc=mr.id 
inner join jo_det jod on brh.id_jo=jod.id_jo 
inner join so on jod.id_so=so.id 
inner join act_costing ac on so.id_cost=ac.id   
inner join masteritem mi on brh.id_item=mi.id_item
where tgl_input >= '$tglinputawal' and tgl_input <= '$tglinputakhir'
order by tgl_input desc,  brd.id desc";
        echo $sql;
        $i=1;
        $query=mysqli_query($con_new,$sql);
        while($data=mysqli_fetch_array($query))
        { 
          $id=$data['id'];
          $sqlbpb="select supplier,unit from bpb a inner join mastersupplier s 
            on a.id_supplier=s.id_supplier where bpbno='$data[bpbno]' 
            and a.id_item='$data[id_item]' and a.id_jo='$data[id_jo]'";
          $rsbpb=mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));          
          echo "
          <tr>";
          $tgl_input=date('d M Y',strtotime($data['tgl_input']));
          echo"
            <td>$tgl_input</td>
            <td>$data[kpno]</td>
            <td>$data[styleno]</td>
            <td>$rsbpb[supplier]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[color]</td>
            <td>$data[roll_no]</td>
            <td>$data[lot_no]</td>
            <td>$data[sisa_old]</td>
            <td>$data[sisa_new]</td>
            <td>$rsbpb[unit]</td>
            <td>$data[rak_loc]</td>
            <td>$data[user]</td>
          </tr>";
          $i++;
        };
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php
}
# END COPAS ADD
?>