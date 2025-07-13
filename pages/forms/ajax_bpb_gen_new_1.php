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
  $id_item = json_encode($_REQUEST['id_item']);
  $id_item = str_replace("[","",$id_item);
  $id_item = str_replace("]","",$id_item);
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
      <th>ID Item #</th>
      <th>Kode Barang #</th>
      <th>Nama Barang #</th>
      <th>Color</th>
      <th>Size</th>
      <th>Stok</th>
      <th>Qty Out</th>
      <th>Unit</th>
    </tr>
    </thead>
    <tbody>
      <?php
            # QUERY TABLE
       $tgl_skrg=date('Y-m-d');
      // $tblnya="bpb"; $tblnya2="qc_out";
      // if ($gr_fg_link_prod_out=="Y")
      // { $sql="select sod.id,ac.kpno,ac.styleno,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,
      //     so.buyerno,sod.dest,sod.color,sod.size,sod.reff_no,sod.styleno_prod,sod.sku,sod.qty,so.unit,so.curr,sod.price,
      //     mp.product_group,mp.product_item  
      //     from so inner join so_det sod on so.id=sod.id_so
      //     inner join act_costing ac on so.id_cost=ac.id 
      //     inner join masterproduct mp on ac.id_product=mp.id  
      //     inner join 
      //     (select a.id_so_det,sum(a.qty) qty from $tblnya2 a inner join so_det s on a.id_so_det=s.id 
      //       inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) group by id_so_det) co 
      //       on sod.id=co.id_so_det
      //     left join 
      //     (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
      //     where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
      // }
      // else
      // { $sql="select sod.id,ac.kpno,ac.styleno,sum(mo.qty) qtyout,sod.qty qtyprev,so.so_no,
      //     so.buyerno,sod.dest,sod.color,sod.size,sod.reff_no,sod.sku,sod.qty,so.unit,so.curr,sod.price,
      //     mp.product_group,mp.product_item  
      //     from so inner join so_det sod on so.id=sod.id_so
      //     inner join act_costing ac on so.id_cost=ac.id 
      //     inner join masterproduct mp on ac.id_product=mp.id  
      //     left join 
      //     (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
      //     where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
      // }

$sql="
select mi.id_item, concat(mi.id_item,'_',unit) kode ,goods_code, itemdesc, color, size,sum(qty_awal) qty_awal, sum(qty_in) qty_in, sum(qty_out) qty_out,sum(qty_awal) + sum(qty_in) - sum(qty_out) qty_sa,  unit from (
select id_item, sum(qty) qty_awal, '0' qty_in, '0' qty_out, unit  from masteritem mi
inner join mapping_category mc on mi.n_code_category = mc.n_id
left join saldoawal_gd a on mi.goods_code = a.kd_barang
where mc.description in('PERSEDIAAN SPAREPARTS - FACTORY SUPPLIES','PERSEDIAAN MESIN') and mi.mattype = 'N' and periode = '2022-01-01' and non_aktif = 'N'  and id_item in ($id_item)
group by id_item, unit
union
select mi.id_item,'0' qty_awal,sum(qty)qty_in, '0' qty_out, unit from bpb 
inner join masteritem mi on bpb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bpb.bpbdate >= '2022-01-01' and mc.description in ('PERSEDIAAN SPAREPARTS - FACTORY SUPPLIES','PERSEDIAAN MESIN')   and mi.mattype = 'N' and bpb.bpbno like 'N%'  and bpb.id_item in 
($id_item)
group by bpb.id_item, bpb.unit
union
select mi.id_item,'0' qty_awal,'0' qty_in,sum(qty)qty_out, unit from bppb 
inner join masteritem mi on bppb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bppb.bppbdate >= '2022-01-01' and mc.description in ('PERSEDIAAN SPAREPARTS - FACTORY SUPPLIES','PERSEDIAAN MESIN')  and mi.mattype = 'N' and bppb.bppbno like 'SJ-N%' and bppb.id_item in ($id_item)
group by bppb.id_item, bppb.unit
) mutasi_a
inner join masteritem mi on mutasi_a.id_item = mi.id_item
group by id_item, unit

UNION

select mi.id_item, concat(mi.id_item,'_',unit) kode ,goods_code, itemdesc, color, size,sum(qty_awal) qty_awal, sum(qty_in) qty_in, sum(qty_out) qty_out,sum(qty_awal) + sum(qty_in) - sum(qty_out) qty_sa,  unit
from
(
select mi.id_item,'0' qty_awal,sum(qty)qty_in, '0' qty_out, unit from bpb 
inner join masteritem mi on bpb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where  mi.mattype = 'N' and tipe_item = 'ASSET' and non_aktif = 'N' and bpb.bpbno like 'N%' and bpb.id_item in ($id_item)
group by bpb.id_item
union
select mi.id_item,'0' qty_awal,'0' qty_in,sum(qty)qty_out, unit from bppb 
inner join masteritem mi on bppb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where mi.mattype = 'N' and tipe_item = 'ASSET' and non_aktif = 'N' and bppb.bppbno like 'SJ-N%' and bppb.bppbno like 'SJ-N%' and bppb.id_item in ($id_item)
group by bppb.id_item
) mutasi_aset
inner join masteritem mi on mutasi_aset.id_item = mi.id_item
group by id_item

UNION

select mi.id_item, concat(mi.id_item,'_',unit) kode ,goods_code, itemdesc, color, size,sum(qty_awal) qty_awal, sum(qty_in) qty_in, sum(qty_out) qty_out,sum(qty_awal) + sum(qty_in) - sum(qty_out) qty_sa,  unit
from
(
select mi.id_item,'0' qty_awal,sum(qty)qty_in, '0' qty_out, unit from bpb 
inner join masteritem mi on bpb.id_item = mi.id_item
where  mi.mattype = 'M' and non_aktif = 'N' and bpb.bpbno like 'M%' and bpb.id_item in ($id_item)
or 
mi.mattype = 'M' and non_aktif = 'N' and bpb.bpbno like 'N%' and bpb.id_item in ($id_item)
group by bpb.id_item, bpb.unit
union
select mi.id_item,'0' qty_awal,'0' qty_in,sum(qty)qty_out, unit from bppb 
inner join masteritem mi on bppb.id_item = mi.id_item
where  mi.mattype = 'M' and non_aktif = 'N' and bppb.bppbno like 'SJ-M%' and bppb.id_item in ($id_item)
or 
mi.mattype = 'M' and non_aktif = 'N' and bppb.bppbno like 'SJ-N%' and bppb.id_item in ($id_item)
group by bppb.id_item, bppb.unit
) mutasi_aset
inner join masteritem mi on mutasi_aset.id_item = mi.id_item
group by id_item, unit



";

      #echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "<tr>";
          $id=$data['kode'];
          echo "
          <td>$data[id_item]</td>
          <td>$data[goods_code]</td>
          <td>$data[itemdesc]</td>
          <td>$data[color]</td>
          <td>$data[size]</td>
          <td>$data[qty_sa]</td>"; 
          echo "<td><input type ='text' style='width:70px;' name ='qty_out[$id]' id ='qty_out[$id]' class='form-control qtyclass' ></td>"; 

          echo "
          <td><input type ='text' style='width:70px;' name ='itemunit[$id]' id ='itemunit[$id]' class='form-control  unitclass' value='$data[unit]' readonly></td>
<input type ='hidden' style='width:70px;' name ='id_item[$id]' id ='id_item[$id]' class='id_itemclass' value='$data[id_item]'>
          ";
          // echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='form-control qtyclass'value='$data[qty_sa]' ></td>"; 
          // echo "
          // <td>
          //   <input type ='text' style='width:70px;' name ='itemqty[$id]' class='form-control qtyclass' onchange='calcJUM()'>
          //   <input type ='hidden' style='width:70px;' name ='itemcurr[$id]' class='currclass' value='$data[curr]'>
          //   <input type ='hidden' style='width:70px;' name ='itemprice[$id]' class='priceclass' value='$data[price]'>
          // </td>"; 
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