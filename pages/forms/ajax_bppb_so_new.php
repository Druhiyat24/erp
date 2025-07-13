<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rscomp["company"];
  $st_company=$rscomp["status_company"];
  $jenis_company=$rscomp["jenis_company"];
$modenya = $_GET['modeajax'];


if ($modenya=="cari_list_sent")
{	$nama_buyer = $_REQUEST['nama_buyer'];
	{	
		$sql="
		select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' and id_supplier = '$nama_buyer'
		union
		select id_agent isi, agent from master_agent where id_buyer = '$nama_buyer'
		order by tampil asc";
		#var_dump($sql);
		IsiCombo($sql,'','Pilih Sent To');
	}
//and ifnull(t_bpb.qtybpb,0)<d.qty di line 157
}

if ($modenya=="view_data_fg")
{	
	$id_bppb = $_REQUEST['id_bppb'];
	echo $id_bppb;
	echo "<br>
 		<div style='font-size:18px;'>Total : <p id='total_qty_chk_rak' style='display:none' value=''></p> </div>
    <table id='examplefix' width='100%' style='font-size:13px;'>";
		echo "
			<thead>
				<tr>
					<th>..</th>
					<th>ID Rak</th>
					<th>#</th>
					<th>Lot #</th>
					<th>Qty Roll</th>
					<th>Qty Sudah Digunakan</th>
					<th>Qty sisa</th>
					<th>Unit</th>
					<th>Rak #</th>
				</tr>
			</thead>
			<tbody>";
			$sql="select * from bppb where bppbno = '$id_bppb'";
			echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$matnya = "";
			while($data=mysql_fetch_array($query))
			{	echo "
					<tr>
            <td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
						<td>$data[id]</td>
					</tr>";
				$i++;
			};
	echo "</tbody>
<tfoot>
            <tr>
                <th colspan='6' style='text-align:right'>Total:</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>

	</table>";
}


















if ($modenya=="view_list_jo_new")
{ 

?>

<?php

	echo "<head>";
    echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
    echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	
?>


<?php 
	
	
  echo "</head>";
}

if ($modenya=="view_list_jo_new")
{ $id_cost = json_encode($_REQUEST['id_jo']);
  $id_cost = str_replace("[","",$id_cost);
  $id_cost = str_replace("]","",$id_cost);

  // $sqlsaldo = mysql_query("
  // select sum(qtysa + qtyprev - qtyout) saldo from
  // (select sod.id,coalesce(sum(msa.qty),0) qtysa,coalesce(sum(mo.qty),0) qtyout,coalesce(sum(co.qty),0) qtyprev,so.so_no,so.buyerno,
  //       sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,so.fob,
  //       mp.product_group,mp.product_item, ac.kpno, sod.reff_no, sod.styleno_prod 
  //       from so inner join so_det sod on so.id=sod.id_so
  //       inner join act_costing ac on so.id_cost=ac.id 
  //       inner join masterproduct mp on ac.id_product=mp.id  
  //       left join 
  //       (select a.id_so_det,sum(a.qty) qty from bpb a inner join so_det s on a.id_so_det=s.id 
  //         inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bpbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) co on sod.id=co.id_so_det
  //       left join 
  //       (select a.id_so_det,sum(a.qty) qty from bppb a inner join so_det s on a.id_so_det=s.id 
  //         inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bppbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) mo on sod.id=mo.id_so_det
  //       left join  
  //       (select so_det.id, sa.qty from saldoawal_gd sa
	// 				inner join act_costing ac on sa.no_ws = ac.kpno
	// 				inner join so on ac.id = so.id_cost
	// 				inner join so_det on so.id = so_det.id_so
	// 				and sa.color = so_det.color
	// 				and sa.size = so_det.size
	// 				where kat = 'FG' and so.id_cost in ($id_cost) and sa.periode = (select tgl1 from tptglperiode where gudang = 'FG')  group by so_det.id ) msa on sod.id=msa.id	  
  //       where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id) a
  // ");


  $sqlsaldo = mysql_query("
select
coalesce(sum(mutasi.saldo_awal),0) + 
coalesce(sum(mutasi.penerimaan),0) -
coalesce(sum(mutasi.pengeluaran),0)
saldo
from 
(
select id_item,id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran 
from saldoawal_fg 
inner join so_det sd on saldoawal_fg.id_so_det = sd.id
inner join so on sd.id_so = so.id
where periode = '2022-10-01' and so.id_cost in ($id_cost)
union
select id_item, id_so_det, '0' as saldo_awal, sum(bpb.qty) as penerimaan, '0' as pengeluaran from bpb 
inner join so_det sd on bpb.id_so_det = sd.id
inner join so on sd.id_so = so.id
where 
bpbdate >= '2022-10-01' and bpbdate <= '2024-01-24'
and bpbno like 'FG%' and so.id_cost in ($id_cost)
group by id_item, id_so_det
union
select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(bppb.qty) as pengeluaran from bppb
inner join so_det sd on bppb.id_so_det = sd.id
inner join so on sd.id_so = so.id 
where 
bppbdate >= '2022-10-01' and bppbdate <= '2024-01-24'
and bppbno like 'SJ-FG%' and so.id_cost in ($id_cost)
group by id_item, id_so_det
)mutasi
  ");

  $datasaldo = mysql_fetch_array($sqlsaldo);
  $saldo_sisa = $datasaldo['saldo'];


  ?>



  <table id="examplefix1" class="table table-responsive" style="width:100%">
    <thead>
    <tr>
            <th colspan="12" style="text-align:right">Total Saldo Sisa:</th>
               <td> <input type = 'text' size = '2' value = '<?php echo $saldo_sisa; ?>'></td>
                <th colspan="3" style="text-align:right">Total Input:</th>
                <td> <input type = 'text' size='2' id = 'total_qty_chk' disabled readonly> </td>
                <td></td>
                <td></td>
                <td></td>
  </tr> 
    <tr>
      <th>SO New #</th>
      <th>WS #</th>
      <th>Product</th>
      <th>Product Desc</th>
      <th>Buyer PO</th>
      <?php if($jenis_company!="VENDOR LG") { ?>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
        <th>Reff No</th>
        <th>Style No Prod</th>
        <th>SKU</th>
      <?php } ?>
      <th>Qty SO</th>
      <th>Unit SO</th>
      <th style='width:5px;'>S.Awal</th> 
      <th>Qty In</th> 
      <th>Qty Out</th>      
      <th>Bal</th>
      <th>Qty BKB</th>
      <th>Curr</th>
      <th>Price</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $tblnya="bppb"; $tblnya2="bpb"; $tgl_skrg=date('Y-m-d');
      // $sql="select sod.id,coalesce(sum(msa.qty),0) qtysa,coalesce(sum(mo.qty),0) qtyout,coalesce(sum(co.qty),0) qtyprev,so.so_no,so.buyerno,
      //   sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,so.fob,
      //   mp.product_group,mp.product_item, ac.kpno, sod.reff_no 
      //   from so inner join so_det sod on so.id=sod.id_so
      //   inner join act_costing ac on so.id_cost=ac.id 
      //   inner join masterproduct mp on ac.id_product=mp.id  
      //   left join 
      //   (select a.id_so_det,sum(a.qty) qty from bpb a inner join so_det s on a.id_so_det=s.id 
      //     inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bpbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) co on sod.id=co.id_so_det
      //   left join 
      //   (select a.id_so_det,sum(a.qty) qty from bppb a inner join so_det s on a.id_so_det=s.id 
      //     inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bppbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) mo on sod.id=mo.id_so_det
      //   left join  
      //   (select id_so_det,saldo qty
      //   from saldoawal_fg sa
      //   inner join so_det sd on sa.id_so_det = sd.id
      //   inner join so on sd.id_so = so.id
      //   inner join act_costing ac on so.id_cost = ac.id
      //   where so.id_cost in ($id_cost) and sa.periode = (select tgl1 from tptglperiode where gudang = 'FG') group by id_so_det ) msa on sod.id=msa.id_so_det	  
      //   where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";


//       $sql="select sd.id,
// coalesce(sum(mutasi.saldo_awal),0) qtysa,
// coalesce(sum(mutasi.penerimaan),0) qtyprev,
// coalesce(sum(mutasi.pengeluaran),0) qtyout,
// so.so_no,
// so.buyerno,
// sd.dest,
// sd.color,
// sd.size,
// sd.sku,
// sd.qty,
// so.unit,
// so.curr,
// sd.price,
// so.fob,
// mp.product_group,
// mp.product_item,
// ac.kpno,
// sd.reff_no 
// from 
// (
// select id_item,id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran 
// from saldoawal_fg 
// inner join so_det sd on saldoawal_fg.id_so_det = sd.id
// inner join so on sd.id_so = so.id
// where periode = '2022-10-01' and so.id_cost in ($id_cost)
// union
// select id_item, id_so_det, '0' as saldo_awal, sum(bpb.qty) as penerimaan, '0' as pengeluaran from bpb 
// inner join so_det sd on bpb.id_so_det = sd.id
// inner join so on sd.id_so = so.id
// where 
// bpbdate >= '2022-10-01' and bpbdate <= '$tgl_skrg'
// and bpbno like 'FG%' and so.id_cost in ($id_cost)
// group by id_item, id_so_det
// union
// select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(bppb.qty) as pengeluaran from bppb
// inner join so_det sd on bppb.id_so_det = sd.id
// inner join so on sd.id_so = so.id 
// where 
// bppbdate >= '2022-10-01' and bppbdate <= '$tgl_skrg'
// and bppbno like 'SJ-FG%' and so.id_cost in ($id_cost)
// group by id_item, id_so_det
// )mutasi
// inner join so_det sd on mutasi.id_so_det = sd.id
// inner join so on sd.id_so = so.id
// inner join act_costing ac on so.id_cost = ac.id
// inner join masterproduct mp on ac.id_product = mp.id
// group by id_so_det";


      $sql="select master_so.*,
coalesce(sum(mutasi.saldo_awal),0) qtysa,
coalesce(sum(mutasi.penerimaan),0) qtyprev,
coalesce(sum(mutasi.pengeluaran),0) qtyout
from 
(
select sd.id, 
so.so_no,
so.buyerno,
sd.dest,
sd.color,
sd.size,
sd.sku,
sd.qty,
so.unit,
so.curr,
sd.price,
so.fob,
mp.product_group,
mp.product_item,
ac.kpno,
sd.reff_no,
sd.styleno_prod  
from so_det sd
inner join so on sd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join masterproduct mp on ac.id_product = mp.id
where so.id_cost in ($id_cost)
group by sd.id
) master_so
left join 
(
select id_item,id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran 
from saldoawal_fg 
inner join so_det sd on saldoawal_fg.id_so_det = sd.id
inner join so on sd.id_so = so.id
where periode = '2022-10-01' and so.id_cost in ($id_cost)
union
select id_item, id_so_det, '0' as saldo_awal, sum(bpb.qty) as penerimaan, '0' as pengeluaran from bpb 
inner join so_det sd on bpb.id_so_det = sd.id
inner join so on sd.id_so = so.id
where 
bpbdate >= '2022-10-01' and bpbdate <= '$tgl_skrg'
and bpbno like 'FG%' and so.id_cost in ($id_cost)
group by id_item, id_so_det
union
select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(bppb.qty) as pengeluaran from bppb
inner join so_det sd on bppb.id_so_det = sd.id
inner join so on sd.id_so = so.id 
where 
bppbdate >= '2022-10-01' and bppbdate <= '$tgl_skrg'
and bppbno like 'SJ-FG%' and so.id_cost in ($id_cost)
group by id_item, id_so_det
)mutasi on master_so.id = mutasi.id_so_det
group by id";


      #echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { 
		//echo "<script>var jsn ={bkb_qty :0}  <script>";
		
		if($jenis_company=="VENDOR LG") { $defpx=$data['fob']; } else { $defpx=$data['price']; }
        echo "<tr>";
          $id=$data['id'];
          echo "
          <td>$data[so_no]</td>
          <td>$data[kpno]</td>
          <td>$data[product_group]</td>
          <td>$data[product_item]</td>
          <td>$data[buyerno]</td>";
          if($jenis_company!="VENDOR LG")
          { echo "
            <td>$data[dest]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[reff_no]</td>
            <td>$data[styleno_prod]</td>
            <td>$data[sku]</td>"; 
          }
          echo "
          <td>$data[qty]</td>
          <td>
          $data[unit]
          <input type ='hidden' style='width:70px;' name ='itemunit[$id]' class='unitclass' value='$data[unit]' readonly></td>";
          echo "<td>$data[qtysa]</td>";
          echo "<td>$data[qtyprev]</td>";
          echo "<td>$data[qtyout]</td>";
          $sisa=$data['qtysa']+$data['qtyprev']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='sisaclass'value='$sisa' readonly></td>"; 
          echo "
          <td>
            <input type ='number' style='width:70px;' max = '$sisa' min = '0' name ='itemqty[$id]' class='form-control sisaclass' onFocus='startCalcBpb()' onBlur='stopCalcBpb()'>
          </td>
          <td>
            $data[curr]
            <input type ='hidden' style='width:50px;' name ='itemcurr[$id]' class='currclass' value='$data[curr]' readonly>
          </td>
          <td>
            <input type ='text' style='width:70px;' name ='itemprice[$id]' class='priceclass' value='$defpx' readonly>
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
<?php 
if ($modenya=="view_list_jo_new")
{?>
  <script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
  <script>
	array_json =[];
	function handle_key_up(that){
		var tot_qty_bkb = 0;
		var last_qtybkb = $(".total_qty_input").text();
		var populasi_classNames = that.className.split(" ");
		var classNames = populasi_classNames[1];
		var spl_classNames = classNames.split("_");
		var idx = parseFloat(spl_classNames[1]) - 1;
		if(isNaN(that.value)){
			$(".qtybkb_"+spl_classNames[1]).val("0");
			return false;
		}
		array_json[parseFloat(idx)].qtybpb = that.value;
			for(var i =0; i<array_json.length; i++){
				tot_qty_bkb = parseFloat(tot_qty_bkb) + parseFloat(array_json[i].qtybpb);
			}
			setTimeout(function(){
				if(isNaN(tot_qty_bkb)){
					$(".total_qty_input").text(last_qtybkb);
					array_json[parseFloat(idx)].qtybpb = 0;
					return false;
				}else{
					$(".total_qty_input").text(tot_qty_bkb);
				}	
				
				
				
				
			},400)
		console.log(array_json);
	}
	
</script>

<?php 
	if($trigger_js > 0){
		echo "<script>";
			echo "var_js = '$trigger_js';";
			echo " for(var i=0;i < var_js;i++ ) { ";
			echo " jsn = {qtybpb : 0 }; ";
			echo " array_json.push(jsn);";
			echo " }";
		echo "</script>";	
		
	
	}

?>
<?php
}
?>