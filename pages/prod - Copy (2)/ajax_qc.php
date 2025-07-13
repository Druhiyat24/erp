<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$jenis_company=flookup("jenis_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}

if ($modenya=="view_list_jo")
{	$id_cost = json_encode($_REQUEST['id_jo']);
	$id_cost = str_replace("[","",$id_cost);
  $id_cost = str_replace("]","",$id_cost);
  ?>
	<table id="examplefix" class="display responsive" style="width:100%">
    <thead>
    <tr>
    	<th>SO #</th>
			<th>Buyer PO</th>
			<?php if($jenis_company=="VENDOR LG") { ?>
        <th>Part #</th>
        <th>Part Name</th>
        <th>Model</th>
      <?php } else { ?>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
      <?php } ?>
      <th>Qty SO</th>
      <th>Unit SO</th>
      <th>Bal</th>
      <?php if($jenis_company=="VENDOR LG") { ?>
        <th>Qty OK</th>
        <th>Qty NG</th>
      <?php } else { ?>
        <th>Qty RFT</th>
        <th>Qty RPR</th>
      <?php } ?>
      <th>Defect</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      if ($jenis_company=="VENDOR LG")
      { $sql="select sod.id,sum(mo.qty) qtyout,0 qtyprev,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
        ,product_group,product_item,model
        from so inner join so_det sod on so.id=sod.id_so 
        inner join act_costing ac on so.id_cost=ac.id 
        inner join masterproduct mp on ac.id_product=mp.id
        left join 
        (select a.id_so_det,sum(a.qty) qty from qc_out a inner join so_det s on a.id_so_det=s.id inner join 
          so d on s.id_so=d.id where d.id_cost in ($id_cost) group by id_so_det) mo 
        on sod.id=mo.id_so_det
        where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id";
        $query = mysql_query($sql); 
      }
      else
      { $query = mysql_query("select sod.id,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
        from so inner join so_det sod on so.id=sod.id_so
        inner join 
        (select a.id_so_det,sum(a.qty) qty from sew_out a inner join so_det s on a.id_so_det=s.id inner join 
          so d on s.id_so=d.id where d.id_cost in ($id_cost) group by id_so_det) co 
        on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,sum(a.qty) qty from qc_out a inner join so_det s on a.id_so_det=s.id inner join 
          so d on s.id_so=d.id where d.id_cost in ($id_cost) group by id_so_det) mo 
        on sod.id=mo.id_so_det
        where so.id_cost in ($id_cost) and sod.cancel='N' group by sod.id"); 
      }
      #echo $sql;
      $no = 1; 
			while($data = mysql_fetch_array($query))
		  { echo "<tr>";
			    $id=$data['id'];
			    echo "<td>$data[so_no]</td>";
					echo "<td>$data[buyerno]</td>";
					if($jenis_company=="VENDOR LG")
          { echo "<td>$data[product_group]</td>";
            echo "<td>$data[product_item]</td>";
            echo "<td>$data[model]</td>";
          }
          else
          { echo "<td>$data[dest]</td>";
            echo "<td>$data[color]</td>";
            echo "<td>$data[size]</td>";
          }
          echo "<td>$data[qty]</td>";
          echo "<td>$data[unit]</td>";
					$sisa=$data['qty']-$data['qtyout'];
          echo "<td><input type ='text' style='width:50px;' name ='itemsisa[$id]' class='sisaclass' value='$sisa' readonly></td>"; 
          echo "<td><input type ='text' style='width:50px;' name ='itemqty[$id]' class='qtyclass'></td>";
          echo "<td><input type ='text' style='width:50px;' name ='itemrpr[$id]' class='rprclass'></td>"; 
          echo "<td><select style='height:30px;' name ='defect[$id]' id='defectajax' class='defectclass'>";
            IsiCombo("select id_defect isi,if(product_group is null,nama_defect,concat(product_group,'|',nama_defect)) tampil from master_defect ",'','Pilih Defect');
          echo "</select></td>";
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
if ($modenya=="view_list_jo")
{?>
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
	<script>
  $("#example1").DataTable();
  //Datatable fix header
  $(document).ready(function() {
    var table = $('#examplefix').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 50,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
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