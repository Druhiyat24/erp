<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rscomp["company"];
  $st_company=$rscomp["status_company"];
  $jenis_company=$rscomp["jenis_company"];
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo_switchout")
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

if ($modenya=="view_list_jo_switchout")
{ $id_cost = json_encode($_REQUEST['id_jo']);
  $id_cost = str_replace("[","",$id_cost);
  $id_cost = str_replace("]","",$id_cost);
  ?>
  <table id="examplefix" class="display responsive" style="width:100%">	
    <thead>
    <tr>
      <th>SO Switch #</th>
      <th>Product</th>
      <th>Product Desc</th>
      <th>Buyer PO</th>
      <th>Dest</th>
      <th>Color</th>
      <th>Size</th>
      <th>Qty SO</th>
      <th>Unit SO</th>
      <th>Saldo Awal</th> 
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
      $tblnya="bppb"; $tblnya2="bpb";
      // $sql="select sod.id,coalesce(sum(msa.qty),0) qtysa,coalesce(sum(mo.qty),0) qtyout,coalesce(sum(co.qty),0) qtyprev,so.so_no,so.buyerno,
      //   sod.dest,sod.color,sod.size,sod.sku,sod.qty,so.unit,so.curr,sod.price,so.fob,
      //   mp.product_group,mp.product_item 
      //   from so inner join so_det sod on so.id=sod.id_so
      //   inner join act_costing ac on so.id_cost=ac.id 
      //   inner join masterproduct mp on ac.id_product=mp.id  
      //   left join 
      //   (select a.id_so_det,sum(a.qty) qty from $tblnya2 a inner join so_det s on a.id_so_det=s.id 
      //     inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bpbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) co on sod.id=co.id_so_det
      //   left join 
      //   (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id 
      //     inner join so d on s.id_so=d.id where d.id_cost in ($id_cost) and bppbdate >= (select tgl1 from tptglperiode where gudang = 'FG')  group by id_so_det) mo on sod.id=mo.id_so_det
      //   left join  
      //   (select so_det.id, sa.qty from saldoawal_gd sa
			// 		inner join act_costing ac on sa.no_ws = ac.kpno
			// 		inner join so on ac.id = so.id_cost
			// 		inner join so_det on so.id = so_det.id_so
			// 		and sa.color = so_det.color
			// 		and sa.size = so_det.size
			// 		where kat = 'FG' and so.id_cost in ($id_cost) and sa.periode = (select tgl1 from tptglperiode where gudang = 'FG')  group by so_det.id ) msa on sod.id=msa.id	  
      //   where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";

      $sql="select mutasi.id_so_det id, f.so_no, f.buyerno,f.dest,f.qty_so,f.curr,f.price,
      ms.goods_code,ms.itemname,ms.styleno,ms.kpno,ms.color,ms.size,ms.country,mutasi.id_item, mutasi.id_so_det, sum(saldo_awal) as qtysa, sum(penerimaan) as qtyprev, sum(pengeluaran) as qtyout, sum(saldo_awal) + sum(penerimaan) - sum(pengeluaran) as saldo_akhir from
(
select id_item , id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran from saldoawal_fg sa 
where sa.periode = (select tgl1 from tptglperiode where gudang = 'FG')
union
select id_item, id_so_det,'0' as saldo_awal, sum(qty) as penerimaan, '0' as pengeluaran from bpb where 
bpbdate >= (select tgl1 from tptglperiode where gudang = 'FG')
and bpbno like 'FG%'
group by id_item, id_so_det
union
select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(qty) as pengeluaran from bppb where 
bppbdate >= (select tgl1 from tptglperiode where gudang = 'FG')
and bppbno like 'SJ-FG%'
group by id_item, id_so_det
)mutasi
inner join masterstyle ms on mutasi.id_item = ms.id_item and mutasi.id_so_det = ms.id_so_det
inner join (select sd.id id_so_det, so.so_no, so.buyerno, sd.qty qty_so,sd.dest, so.curr, sd.price from so_det sd
inner join so on sd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
where id_cost in ($id_cost)) f on mutasi.id_so_det = f.id_so_det
group by mutasi.id_item, mutasi.id_so_det
";

      #echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
	  $total_qty_so = 0;
	  $total_qty_sa = 0;
	  $total_bal = 0;
	  $trigger_sub_total = "0";
	  $trigger_js = 0;
      while($data = mysql_fetch_array($query))
      { 
		$trigger_js++;
		//echo "<script>var jsn ={bkb_qty :0}  <script>";
		
		$trigger_sub_total = "1";
		if($jenis_company=="VENDOR LG") { $defpx=$data['fob']; } else { $defpx=$data['price']; }
        echo "<tr>";
          $id=$data['id'];
          echo "
          <td>$data[so_no]</td>
          <td>$data[goods_code]</td>
          <td>$data[itemname]</td>
          <td>$data[buyerno]</td>";
          if($jenis_company!="VENDOR LG")
          { echo "
            <td>$data[dest]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>"; 
          }
          echo "
          <td>$data[qty_so]</td>
          <td><input type ='text' style='width:70px;' name ='itemunit[$id]' class='unitclass' value='PCS' readonly></td>";
          echo "<td>$data[qtysa]</td>";
          echo "<td>$data[qtyprev]</td>";
          echo "<td>$data[qtyout]</td>";
          $sisa=$data['qtysa']+$data['qtyprev']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='sisaclass'value='$sisa' readonly></td>"; 
          echo "
          <td>
            <input type ='number' style='width:70px;' max = '$sisa' onkeyup='handle_key_up(this)' name ='itemqty[$id]' class='qtyclass qtybkb_$no'>
          </td>
          <td>
            <input type ='text' style='width:70px;' name ='itemcurr[$id]' class='currclass' value='$data[curr]' readonly>
          </td>
          <td>
            <input type ='text' style='width:70px;' name ='itemprice[$id]' class='priceclass' value='$defpx' readonly>
          </td>"; 
        echo "</tr>";
		$total_qty_so = $total_qty_so + $data['qty'];
		$total_sa = $total_qty_sa + $data['qtysa'];
		$total_bal = $total_bal + $sisa;
        $no++; // menambah nilai nomor urut
      }
	  $my_td ="";
	  if( $trigger_sub_total == "1" ){
		 $my_td .="<td colspan=7>";
		 $my_td .="Total";		
		 $my_td .="</td>";
		 
		 $my_td .="<td >";
		 $my_td .=$total_qty_so;		
		 $my_td .="</td>";		 
	
		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";

		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";		 

		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";

		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";		
		 
		 $my_td .="<td >";
		 $my_td .=$total_bal;		
		 $my_td .="</td>";	

		 $my_td .="<td class='total_qty_input'>";
		 $my_td .="0";//total_bkb		
		 $my_td .="</td>";

		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";


		 $my_td .="<td >";
		 $my_td .="-";		
		 $my_td .="</td>";
		 
		   echo "<tr>".$my_td."</tr>"; 
	  }
      ?>
    </tbody>
  </table>
  <?php 
}
?>
<?php 
if ($modenya=="view_list_jo_switchout")
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

<script>	
	
	


  $("#example1").DataTable();
  //Datatable fix header
  $(document).ready(function() {
    var table = $('#examplefix').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
		destroy:true,
        searching: false,
        pageLength: 50,/* 
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        } */
    });
  });
  //Datatable fix header no pagination and searching
  $(document).ready(function() {
    var table = $('#examplefix2').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        searching: false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
</script>
<?php
}
?>