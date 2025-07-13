<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo" or $modenya=="view_list_jo_dc")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}

if ($modenya=="view_list_jo" or $modenya=="view_list_jo_dc")
{	$id_cost = $_REQUEST['id_jo'];
	?>
	<table id="examplefix" class="display responsive" style="width:100%">
    <thead>
    <tr>
    	<th>SO #</th>
			<th>Buyer PO</th>
			<th>Dest</th>
      <th>Color</th>
      <th>Size</th>
      <th>Qty SO</th>
      <th>Unit SO</th>
      <th>Bal</th>
      <?php 
      if ($modenya=="view_list_jo_dc") 
      { echo "<th>Qty Output</th>"; }
      else
      { echo "<th>Qty Output</th>"; }
      ?>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      if ($modenya=="view_list_jo") 
      { $tblnya="sew_out"; $tblnya2="sew_in"; } 
      else 
      { $tblnya="dc_out"; $tblnya2="cut_out"; }
      $query = mysql_query("select sod.id,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
        from so inner join so_det sod on so.id=sod.id_so
        inner join 
        (select a.id_so_det,sum(a.qty) qty from $tblnya2 a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) co on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost='$id_cost' group by id_so_det) mo on sod.id=mo.id_so_det
        where so.id_cost='$id_cost' and sod.cancel='N' group by sod.id"); 
      $no = 1; 
			while($data = mysql_fetch_array($query))
		  { echo "<tr>";
			    $id=$data['id'];
			    echo "<td>$data[so_no]</td>";
					echo "<td>$data[buyerno]</td>";
					echo "<td>$data[dest]</td>";
          echo "<td>$data[color]</td>";
          echo "<td>$data[size]</td>";
          echo "<td>$data[qty]</td>";
          echo "<td>$data[unit]</td>";
					$sisa=$data['qtyprev']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='sisaclass'value='$sisa' readonly></td>"; 
          echo "<td><input type ='text' style='width:70px;' name ='itemqty[$id]' class='qtyclass'></td>"; 
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
if ($modenya=="view_list_jo" or $modenya=="view_list_jo_dc")
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