<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rscomp["company"];
  $st_company=$rscomp["status_company"];
  $jenis_company=$rscomp["jenis_company"];
  $gr_fg_link_prod_out=$rscomp["gr_fg_link_prod_out"];
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo")
{ 
  $id_cost = json_encode($_REQUEST['id_jo']);
  $id_cost = str_replace("[","",$id_cost);
  $id_cost = str_replace("]","",$id_cost);
  ?>

  <div class='col-md-6'></div>
  <div class='col-md-6'></div>
  <div class='col-md-2'></div>
  <div class='col-md-1'>   
      <label font-weight:bold>TOTAL : </label> 
      <input type='text' style="text-align: right" class='form-control' id='txtqtybpb' readonly>
  </div>
  <div class='col-md-10'></div>

  <table id="examplefix2" class="display responsive" style="width:100%">
    <thead>
    <tr>
      <th>SO #</th>
      <th>WS Tujuan</th>
      <th>Style #</th>
      <th>Product</th>
      <th>Product Desc</th>
      <th>Buyer PO</th>
      <?php if($jenis_company!="VENDOR LG") { ?>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
        <th>SKU</th>
      <?php } ?>
      <th>Qty SO</th>
      <th>Unit SO</th>
      <th>Saldo Awal</th>
      <th>Qty In</th>
      <th>Qty Out</th>
      <th>Bal</th>
      <th>Qty BPB</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $tblnya="bpb"; $tblnya2="qc_out";
      if ($gr_fg_link_prod_out=="Y")
      { $sql="select sod.id,ac.kpno,ac.styleno,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,
          so.buyerno,sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,
          mp.product_group,mp.product_item  
          from so inner join so_det sod on so.id=sod.id_so
          inner join act_costing ac on so.id_cost=ac.id 
          inner join masterproduct mp on ac.id_product=mp.id  
          inner join 
          (select a.id_so_det,sum(a.qty) qty from $tblnya2 a inner join so_det s on a.id_so_det=s.id 
            inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) group by id_so_det) co 
            on sod.id=co.id_so_det
          left join 
          (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
          where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
      }
      else
      { $sql="select sod.id,ac.kpno,ac.styleno,sod.qty qtyprev,coalesce(sum(msa.qty),0) qtysa,coalesce(sum(mo.qty),0) qtyin,coalesce(sum(mi.qty),0) qtyout,so.so_no,
          so.buyerno,sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,
          mp.product_group,mp.product_item  
          from so inner join so_det sod on so.id=sod.id_so
          inner join act_costing ac on so.id_cost=ac.id 
          inner join masterproduct mp on ac.id_product=mp.id
          left join
          (select so_det.id, sa.qty from saldoawal_gd sa
          inner join act_costing ac on sa.no_ws = ac.kpno
          inner join so on ac.id = so.id_cost
          inner join so_det on so.id = so_det.id_so
          and sa.color = so_det.color
          and sa.size = so_det.size
          where kat = 'FG' and so.id_cost in ($id_cost) and sa.periode = (select tgl1 from tptglperiode where gudang = 'FG')  group by so_det.id) msa on sod.id=msa.id          
          left join 
          (select a.id_so_det,sum(a.qty) qty from bpb a left join so_det s on a.id_so_det=s.id left join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bpbdate >= (select tgl1 from tptglperiode where gudang = 'FG') group by id_so_det) mo on sod.id=mo.id_so_det
          left join
          (select a.id_so_det,sum(a.qty) qty from bppb a left join so_det s on a.id_so_det=s.id left join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bppbdate >= (select tgl1 from tptglperiode where gudang = 'FG') group by id_so_det) mi on sod.id=mi.id_so_det         
          where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
      }
      echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "<tr>";
          $id=$data['id'];
          echo "
          <td>$data[so_no]</td>
          <td>$data[kpno]</td>
          <td>$data[styleno]</td>
          <td>$data[product_group]</td>
          <td>$data[product_item]</td>
          <td>$data[buyerno]</td>";
          if($jenis_company!="VENDOR LG")
          { echo "
            <td>$data[dest]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[sku]</td>"; 
          }
          echo "
          <td>$data[qtyprev]</td>
          <td><input type ='text' style='width:70px;' name ='itemunit[$id]' class='form-control  unitclass' value='$data[unit]' readonly></td>
          <td>$data[qtysa]</td>
          <td>$data[qtyin]</td>
          <td>$data[qtyout]</td>";
          $sisa= $data['qtysa'] + $data['qtyin']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='form-control  sisaclass'value='$sisa' readonly></td>"; 
          echo "
          <td>
            <input type ='text' style='width:70px;' name ='itemqty[$id]' class='form-control qtyclass' onchange='calcJUM()'>
            <input type ='hidden' style='width:70px;' name ='itemcurr[$id]' class='currclass' value='$data[curr]'>
            <input type ='hidden' style='width:70px;' name ='itemprice[$id]' class='priceclass' value='$data[price]'>
          </td>"; 
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
  </table>
  <?php 
}
?>
<script type='text/javascript'>
function calcJUM()
  {
    var qtybpb = document.form.getElementsByClassName('qtyclass');
    var totdet = 0;
    for (var i = 0; i < qtybpb.length; i++) 
    { 
      totdet += Number(qtybpb[i].value);
    }
    $('#txtqtybpb').val(totdet);
  };
</script>