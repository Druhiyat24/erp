<?php 
include ("../../include/conn.php");
include ("../forms/fungsi.php");

$kpno = $_POST['id'];
$sono = flookup("so_no","so a inner join act_costing s on a.id_cost=s.id","kpno='$kpno'");
echo "WS # ".$kpno." SO # ".$sono;
echo "<table class='table table-bordered' style='font-size:12px;'>";
	echo "<thead>";
	echo "
		<tr>
			<th>Dest</th>
			<th>Color</th>
			<th>Size</th>
			<th>Qty Order</th>
			<th>Cutting</th>
			<th>Secondary</th>
			<th>Input Sew</th>
			<th>Sewing</th>
			<th>QC Sewing</th>
			<th>Packing</th>
		</tr>";
	echo "</thead>";
	# QUERY TABLE
	$query = mysql_query("select ac.kpno,so.so_no,sum(co.qty) qtycut,so.buyerno,sod.dest,sod.color,sod.size,
    sum(sod.qty) qtyorder,so.unit,sum(tmp_sec.qtysec) qtysec,sum(tmp_sew_in.qtysewin) qtysewin,
    sum(tmp_sew.qtysew) qtysew,sum(tmp_qcsew.qtyqcsew) qtyqcsew,
    sum(tmp_pack.qtypack) qtypack
    from so inner join so_det sod on so.id=sod.id_so
    inner join cut_out co on sod.id=co.id_so_det
    inner join act_costing ac on so.id_cost=ac.id
    left join 
    (select co.id_so_det,sum(co.qty) qtysec 
    from so inner join so_det sod on so.id=sod.id_so inner join mfg_out co 
    on sod.id=co.id_so_det where so_no='$sono' group by co.id_so_det) tmp_sec
    on co.id_so_det=tmp_sec.id_so_det
    left join 
    (select co.id_so_det,sum(co.qty) qtysewin 
    from so inner join so_det sod on so.id=sod.id_so inner join sew_in co 
    on sod.id=co.id_so_det where so_no='$sono' group by co.id_so_det) tmp_sew_in
    on co.id_so_det=tmp_sew_in.id_so_det
    left join 
    (select co.id_so_det,sum(co.qty) qtysew 
    from so inner join so_det sod on so.id=sod.id_so inner join sew_out co 
    on sod.id=co.id_so_det where so_no='$sono' group by co.id_so_det) tmp_sew
    on co.id_so_det=tmp_sew.id_so_det 
    left join 
    (select co.id_so_det,sum(co.qty) qtyqcsew 
    from so inner join so_det sod on so.id=sod.id_so inner join qc_out co 
    on sod.id=co.id_so_det where so_no='$sono' group by co.id_so_det) tmp_qcsew
    on co.id_so_det=tmp_qcsew.id_so_det
    left join 
    (select co.id_so_det,sum(co.qty) qtypack 
    from so inner join so_det sod on so.id=sod.id_so inner join pack_out co 
    on sod.id=co.id_so_det where so_no='$sono' group by co.id_so_det) tmp_pack
    on co.id_so_det=tmp_pack.id_so_det 
    where kpno='$kpno' group by co.id_so_det");
	while($data = mysql_fetch_array($query))
	{ echo "
		<tr>
			<td>$data[dest]</td>
			<td>$data[color]</td>
			<td>$data[size]</td>
			<td>$data[qtyorder]</td>
			<td>$data[qtycut]</td>
			<td>$data[qtysec]</td>
			<td>$data[qtysewin]</td>
			<td>$data[qtysew]</td>
			<td>$data[qtyqcsew]</td>
			<td>$data[qtypack]</td>
		</tr>";
	}
echo "</table>";

?>