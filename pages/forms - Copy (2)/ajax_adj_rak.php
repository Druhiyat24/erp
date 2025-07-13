<?php
include "../../include/conn.php";
include "fungsi.php";
// include "../forms/func_cek_po_over_allow.php";

$mode="";
$mod="";
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$jenis_company=$rscomp["jenis_company"];
	$allow_bpb=$rscomp["allowance_bpb"];
	$whs_input_bc_dok = $rscomp['whs_input_bc_dok'];
  $whs_see_price = $rscomp['whs_see_price'];
  if($whs_see_price=="N")
  { $hidepx = "hidden"; }
  else
  { $hidepx = "text"; }
$modenya = $_GET['modeajax'];

if($modenya=="view_list_rak")
{
	$crinya = $_REQUEST['raknya'];
	if($crinya!="")
	{	
		?>
		<table id="examplefix2" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
        <tr>
          <th><input type="checkbox" onchange="checkAll(this)" name="chk[]" ></th>
          <th>ID</th>
          <th>WS #</th>
          <th>Style #</th>
          <th>Supplier</th>
          <th>No BPB</th>
          <th>ID Item</th>
          <th>Kode Item</th>
          <th>Nama Item</th>
          <th>Color Item</th>
          <th>Roll #</th>
          <th>Lot #</th>
          <th>Qty Rak</th>
          <th>Qty Used</th>
          <th>Qty Adjust</th>
          <th>Unit</th>
          <th>Rak Location</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql="select ac.kpno,ac.styleno,brh.bpbno,brh.id_jo,brh.id_item,
          mi.goods_code,mi.color,concat(replace(mi.itemdesc,mi.color,''),' ',mi.add_info) itemdesc,
          brd.roll_no,brd.lot_no,brd.roll_qty,brd.roll_qty_used,brd.roll_qty - brd.roll_qty_used sisa,concat(mrt.kode_rak,' ',mrt.nama_rak) rak_temp,
          concat(mr.kode_rak,' ',mr.nama_rak) rak_loc,brd.id,mr.id id_rak_old         
          from bpb_roll_h brh inner join bpb_roll brd on brh.id=brd.id_h 
          inner join master_rak mr on brd.id_rak_loc=mr.id  
          inner join master_rak mrt on brd.id_rak=mrt.id  
          inner join jo_det jod on brh.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id   
          inner join masteritem mi on brh.id_item=mi.id_item
          where brd.roll_qty - brd.roll_qty_used >'0' 
          and brd.id_rak_loc='$crinya'";
        echo $sql;
        $i=1;
        $query=mysqli_query($con_new,$sql);
        while($data=mysqli_fetch_array($query))
        { 
          $id=$data['id'];
          $sqlbpb="select bpbno_int,supplier,unit from bpb a inner join mastersupplier s 
            on a.id_supplier=s.id_supplier where bpbno='$data[bpbno]' 
            and a.id_item='$data[id_item]' and a.id_jo='$data[id_jo]'";
          $rsbpb=mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));
          echo "
          <tr>
            <td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass'></td>
            <td>$data[id]</td>
            <td>$data[kpno]</td>
            <td>$data[styleno]</td>
            <td>$rsbpb[supplier]</td>
            <td>$rsbpb[bpbno_int]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[color]</td>
            <td>$data[roll_no]</td>
            <td>$data[lot_no]</td>
            <td>$data[roll_qty]</td>
            <td>$data[roll_qty_used]</td>
            <td><input type = 'text' style='width:50px;' name ='itemqty[$id]' ></td>
            <td>$rsbpb[unit]</td>
            <td>$data[rak_loc]</td>
          </tr>";
          $i++;
        };
        ?>
      </tbody>
    </table>
	<?php 
	}
}
?>
