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
  <table id="examplefix2" class="display responsive" style="width:100%">
    <thead>
    <tr>
      <th>SO #</th>
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
      <th>Bal</th>
      <th>Qty BPB</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $tblnya="bpb"; $tblnya2="qc_out";
      if ($gr_fg_link_prod_out=="Y")
      { $sql="select sod.id,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,
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
      { $sql="select sod.id,sum(mo.qty) qtyout,sod.qty qtyprev,so.so_no,
          so.buyerno,sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,
          mp.product_group,mp.product_item  
          from so inner join so_det sod on so.id=sod.id_so
          inner join act_costing ac on so.id_cost=ac.id 
          inner join masterproduct mp on ac.id_product=mp.id  
          left join 
          (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
          where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
      }
      #echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "<tr>";
          $id=$data['id'];
          echo "
          <td>$data[so_no]</td>
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
          <td>$data[qty]</td>
          <td><input type ='text' style='width:70px;' name ='itemunit[$id]' class='form-control  unitclass' value='$data[unit]' readonly></td>";
          $sisa=$data['qtyprev']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='form-control  sisaclass'value='$sisa' readonly></td>"; 
          echo "
          <td>
            <input type ='text' style='width:70px;' name ='itemqty[$id]' class='form-control qtyclass'>
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