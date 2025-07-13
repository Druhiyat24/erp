<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_color")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>";
		echo "<link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
	echo "</head>";
}

if ($modenya=="view_list_color")
{	$crinya = $_REQUEST['cri_size'];
	$cricol = $_REQUEST['cri_col'];
	if ($crinya!="" AND $cricol!="")
	{	$capt="col";
		echo "
			<table style='width: 100%;'>
				<thead>
					<tr>
						<th width='8%'>Color</th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
					</tr>
				</thead>";
			echo "<tr>"; show_color(1,10,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(11,20,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(21,30,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(31,40,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(41,50,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(51,60,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(61,70,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(71,80,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(81,90,$cricol,$capt); echo "</tr>";
			echo "<tr>"; show_color(91,100,$cricol,$capt); echo "</tr>";
		echo "</table>";
		$capt="sz";
		echo "
			<table style='width: 100%;'>
				<thead>
					<tr>
						<th width='8%'>Size</th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
						<th width='8%'></th>
					</tr>
				</thead>";
			echo "<tr>"; show_color(1,10,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(11,20,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(21,30,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(31,40,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(41,50,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(51,60,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(61,70,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(71,80,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(81,90,$crinya,$capt); echo "</tr>";
			echo "<tr>"; show_color(91,100,$crinya,$capt); echo "</tr>";
		echo "</table>";
	}
}
?>
<?php 
if ($modenya=="view_list_color")
{?>
	<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
<script>
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
