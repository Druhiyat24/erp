<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_jo")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}

if ($modenya=="get_list_jo")
{ $id_supp = $_REQUEST['id_supp'];
  $sql = "select a.id isi,jo_no tampil from 
    jo a inner join bom_jo_item s on a.id=s.id_jo 
    where id_supplier='$id_supp' group by jo_no";
  IsiCombo($sql,'','Pilih Job Order #');
}
if ($modenya=="view_list_jo")
{	$id_jo = $_REQUEST['id_jo'];
	?>
	<table id="examplefix" class="display responsive" style="width:100%">
    <thead>
    <tr>
    	<th>..</th>
      <th width='45%'>Item</th>
			<th width='15%'>Qty BOM</th>
			<th width='15%'>Unit</th>
      <th width='5%'>Stock</th>
      <th width='5%'>Qty PO</th>
      <th width='5%'>Unit</th>
      <th width='10%'>Price</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $query = mysql_query("select k.id_item,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
        d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
        g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,
        l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
        k.unit,m.supplier from bom_jo_item k inner join so_det l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
        inner join mastertype2 d on s.id=d.id_sub_group
        inner join mastercontents e on d.id=e.id_type
        inner join masterwidth f on e.id=f.id_contents 
        inner join masterlength g on f.id=g.id_width
        inner join masterweight h on g.id=h.id_length
        inner join mastercolor i on h.id=i.id_weight
        inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
        left join mastersupplier m on k.id_supplier=m.id_supplier
				where k.id_jo='$id_jo' group by k.id_item"); 
      $no = 1; 
			while($data = mysql_fetch_array($query))
		  { echo "<tr>";
			    $id=$data['id_item'];
			    echo "<td><input type ='checkbox' name ='itemchk[$id]' 
			    	class='chkclass'></td>"; 
          echo "<td>$data[item]</td>";
					echo "<td>$data[qty_bom]</td>";
					echo "<td>$data[unit]</td>";
					$id_item_bb=flookup("id_item","masteritem","id_gen='$data[id_item]'");
          if ($id_item_bb!="")
          { $sisa_stock=flookup("stock","stock","id_item='$id_item_bb'"); }
          else
          { $sisa_stock=0; }
          echo "<td>$sisa_stock</td>";
          $qtyPO=$data['qty_bom'] - $sisa_stock;
          echo "<td><input type ='text' style='width:70px;' name ='itemqty[$id]' class='qtyclass'value='$qtyPO'></td>"; 
          echo "<td><select style='width:70px; height: 26px;' name ='itemunit[$id]' class='unitclass'>";
					 $sql="select nama_pilihan isi,nama_pilihan tampil
              from masterpilihan where kode_pilihan='Satuan'";
					 IsiCombo($sql,$data['unit'],'Pilih Unit');
					echo "</select></td>";
          echo "
          <td>
            <input type ='text' style='width:70px;' name ='itemprice[$id]' class='priceclass'>
            <input type ='hidden' name ='itembb[$id]' value='$data[id_item]'>
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