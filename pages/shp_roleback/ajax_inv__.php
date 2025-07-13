<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo")
{ echo "<head>";
    echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
    echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
  echo "</head>";
}

if ($modenya=="get_list_kp")
{ $id_buyer = $_REQUEST['cri_item'];
  $sql = "select ACT.id isi,concat(ACT.kpno,'|',ACT.styleno,'|',IFNULL(SOD.dest,'-')) tampil from 
    act_costing ACT 
	LEFT JOIN so SO ON. ACT.id = SO.id_cost
	LEFT JOIN so_det SOD ON SO.id = SOD.id_so
	where ACT.id_buyer='$id_buyer' GROUP BY ACT.kpno order by ACT.kpno";
  IsiCombo($sql,'','Pilih WS #');
}

if ($modenya=="view_list_jo")
{ //$id_cost = $_REQUEST['id_jo'];
$id_cost = '';
$array_jo = $_REQUEST['id_jo'];
$key = $_REQUEST['jumlah'] - 1;
$id_cost .="(";
for($w=0;$w<$_REQUEST['jumlah'];$w++){

	if($w==$key){
		$id_cost .= "'".$array_jo[$w]['id']."'";
	}else{
		$id_cost .= "'".$array_jo[$w]['id']."',";
	}

	
}
$id_cost .=")";
	
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
      <th>Qty Inv</th>
      <th>Price</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $tblnya="invoice_detail"; $tblnya2="bpb";
      $sql="select sod.id,sod.price,sum(mo.qty) qtyout,sum(co.qty) qtyprev,so.so_no,so.buyerno,sod.dest,sod.color,sod.size,sod.qty,so.unit
        from so inner join so_det sod on so.id=sod.id_so
        inner join 
        (select a.id_so_det,sum(a.qty) qty from $tblnya2 a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost IN$id_cost group by id_so_det) co on sod.id=co.id_so_det
        left join 
        (select a.id_so_det,sum(a.qty) qty from $tblnya a inner join so_det s on a.id_so_det=s.id inner join so d on s.id_so=d.id where d.id_cost IN$id_cost group by id_so_det) mo on sod.id=mo.id_so_det
        where so.id_cost IN $id_cost group by sod.id";
echo $sql;    
	$query = mysql_query($sql); 
      #echo $sql;
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
          echo "<td><input type ='text' style='width:70px;' name ='itemunit[$id]' class='unitclass' value='$data[unit]' readonly></td>";
          $sisa=$data['qtyprev']-$data['qtyout'];
          echo "<td><input type ='text' style='width:70px;' name ='itemsisa[$id]' class='sisaclass' value='$sisa' readonly></td>"; 
          echo "<td><input type ='text' style='width:70px;' name ='itemqty[$id]' class='qtyclass'></td>";
          $pxdef=$data['price'];
          echo "<td><input type ='text' style='width:70px;' name ='itempx[$id]' class='pxclass' value='$pxdef'></td>"; 
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