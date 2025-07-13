<?php
include "../../include/conn.php";
include "fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];
#echo $modenya;
if ($modenya=="view_list_sc")
{	echo "
	<head>
		<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>
		<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
		if($modenya=="view_list_sc")
		{echo "<link rel='stylesheet' href='../../plugins/select2/select2.min.css'>";}
	echo "</head>";
}

if ($modenya=="view_list_sc")
{	$crinya = $_REQUEST['id_jo'];
	if ($crinya!="")
	{	$ponya=$crinya;
		$id_jo=flookup("id_jo","bppb","bppbno='$crinya'");
		echo "<table id='examplefixsc' style='width: 100%;'>";
			echo "
				<thead>
					<tr>
						<th>Kode Bahan Baku</th>
						<th>Deskripsi</th>
						<th>Qty SJ</th>
						<th>Unit</th>
						<th>Item Scrap</th>
						<th>Qty Scrap</th>
						<th>Unit</th>
					</tr>
				</thead>
				<tbody>";
				$sql="select breq.unit,breq.id_supplier,breq.qty qtysj,mi.goods_code,mi.itemdesc,mi.id_item,breq.id_jo 
					from bppb breq inner join masteritem mi on mi.id_item=breq.id_item where breq.bppbno='$crinya'";
				#echo $sql;
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item'];
					echo "
						<tr>
							<td>$data[goods_code]</td>
							<td>$data[itemdesc]</td>
							<td><input type ='text' style='width:50px;' name ='qtybpb[$id]' value='$data[qtysj]' id='qtybpb$i' class='qtybpbclass' readonly></td>
							<td><input type ='text' style='width:50px;' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly></td>
							<td>
								<select style='width:200px;height:2em;' class='select2 itemsc' name='itemsc[$id]'>";
	              $sql="select id_item isi,concat(goods_code,'|',itemdesc) tampil from masteritem 
	              	where mattype in ('S','L')";
	              IsiCombo($sql,'','Item');
	              echo "</select>
	            </td>
							<td>
								<input type ='text' style='width:50px;' name ='qtysc[$id]' id='qtysc$i' class='qtysc'>
								<input type ='hidden' name ='jono[$id]' id='jono$i' class='jono' value='$id_jo'>
								<input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_supp' value='$data[id_supplier]'>
							</td>
							<td>
								<select class='select2 unitsc' style='width:100px;height:2em;' name='unitsc[$id]'>";
	              $sql="select nama_pilihan isi, nama_pilihan tampil from masterpilihan where kode_pilihan='Satuan'";
	              IsiCombo($sql,'','Unit');
	              echo "</select>
							</td>
						</tr>";
					$i++;
				};
		echo "</tbody></table>";
	}
}

if ($modenya=='view_list_sc')
{?>
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
	<script>
	  //Datatable fix header
	  $(document).ready(function() {
	    function strtrunc(str, max, add){
       add = add || '...';
       return (typeof str === 'string' && str.length > max ? str.substring(0, max) + add : str);
    };
    var table = $('#examplefix').DataTable
	    ({  scrollY: "300px",
	        scrollCollapse: true,
	        paging: false,
	        pageLength: 50,
	        fixedColumns:   
	        { leftColumns: 1,
	          rightColumns: 1
	        }
	    });
	  var table = $('#examplefixsc').DataTable
	    ({  scrollY: "300px",
	        scrollCollapse: true,
	        paging: false,
	        columnDefs: 
	        [ { targets: 0, width:'210px',   
	            render: function(data, type, full, meta)
	            { if(type === 'display')
	              { data = strtrunc(data, 40); }
	              return data;
	            }
	          },
	          { targets: 1, width:'240px',   
	            render: function(data, type, full, meta)
	            { if(type === 'display')
	              { data = strtrunc(data, 40); }
	              return data;
	            }
	          },
	          { targets: 2, width:'10px' },
	          { targets: 3, width:'10px' },
	          { targets: 4, width:'10px' },
	          { targets: 5, width:'10px' },
	          { targets: 6, width:'10px' }
	        ]
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
<?php } ?>
<?php if($modenya=="view_list_sc") { ?>
	<script src="../../plugins/select2/select2.full.min.js"></script>
	<script>
		$(".select2").select2();
	</script>
<?php } ?>