<?php
$bln=date('m');
$thn=date('Y');
$query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok,count(distinct bpbno) jml_trans from bpb where 
   month(bpbdate)=$bln and year(bpbdate)=$thn
and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
$jml_bc20=0; $jml_bclkl=0; $jml_bc24=0; $jml_bcjuallkl=0; 
$jml_bc23=0; $jml_bc40=0; $jml_bc27=0; $jml_bc262=0; 
while($data = mysql_fetch_array($query))
{	if ($data['jenis_dok']=="BC 2.3")
	{ $jml_bc23=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.0")
	{ $jml_bc20=$data['jml_dok']; }
	else if ($data['jenis_dok']=="LOKAL")
	{ $jml_bclkl=$data['jml_trans']; }
	else if ($data['jenis_dok']=="BC 4.0")
	{ $jml_bc40=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.7")
	{ $jml_bc27=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.6.2")
	{ $jml_bc262=$data['jml_dok']; }
}
$query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok from bppb where 
   month(bppbdate)=$bln and year(bppbdate)=$thn
and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
$jml_bc30=0; $jml_bc41=0; $jml_bc27out=0; $jml_bc261=0; $jml_bc25=0; 
while($data = mysql_fetch_array($query))
{	if ($data['jenis_dok']=="BC 3.0")
	{ $jml_bc30=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.4")
	{ $jml_bc24=$data['jml_dok']; }
	else if ($data['jenis_dok']=="JUAL LOKAL")
	{ $jml_bcjuallkl=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 4.1")
	{ $jml_bc41=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.7")
	{ $jml_bc27out=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.6.1")
	{ $jml_bc261=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.5")
	{ $jml_bc25=$data['jml_dok']; }
}
# PREVIOS MONTH
if (date('m')==1)
{	$bln=12;
	$thn=date('Y')-1;
}
else
{	$bln=date('m')-1;
	$thn=date('Y');
}
$query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok,count(distinct bpbno) jml_trans from bpb where 
   month(bpbdate)=$bln and year(bpbdate)=$thn
and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
$jml_bc20_prev=0; $jml_bclkl_prev=0; $jml_bc24_prev=0; $jml_bcjuallkl_prev=0; 
$jml_bc23_prev=0; $jml_bc40_prev=0; $jml_bc27_prev=0; $jml_bc262_prev=0; 
while($data = mysql_fetch_array($query))
{	if ($data['jenis_dok']=="BC 2.3")
	{ $jml_bc23_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.0")
	{ $jml_bc20_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="LOKAL")
	{ $jml_bclkl_prev=$data['jml_trans']; }
	else if ($data['jenis_dok']=="BC 4.0")
	{ $jml_bc40_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.7")
	{ $jml_bc27_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.6.2")
	{ $jml_bc262_prev=$data['jml_dok']; }
}
$query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok from bppb where 
   month(bppbdate)=$bln and year(bppbdate)=$thn
and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
$jml_bc30_prev=0; $jml_bc41_prev=0; $jml_bc27out_prev=0; $jml_bc261_prev=0; $jml_bc25_prev=0; 
while($data = mysql_fetch_array($query))
{	if ($data['jenis_dok']=="BC 3.0")
	{ $jml_bc30_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.4")
	{ $jml_bc24_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="JUAL LOKAL")
	{ $jml_bcjuallkl_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 4.1")
	{ $jml_bc41_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.7")
	{ $jml_bc27out_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.6.1")
	{ $jml_bc261_prev=$data['jml_dok']; }
	else if ($data['jenis_dok']=="BC 2.5")
	{ $jml_bc25_prev=$data['jml_dok']; }
}
$dataPoints1 = array(
	array("label"=> "BC 2.3", "y"=> $jml_bc23),
	array("label"=> "BC 4.0", "y"=> $jml_bc40),
	array("label"=> "BC 2.7", "y"=> $jml_bc27),
	array("label"=> "BC 2.6.2", "y"=> $jml_bc262),
	array("label"=> "BC 3.0", "y"=> $jml_bc30),
	array("label"=> "BC 4.1", "y"=> $jml_bc41),
	array("label"=> "BC 2.7 Out", "y"=> $jml_bc27out),
	array("label"=> "BC 2.6.1", "y"=> $jml_bc261),
	array("label"=> "BC 2.5", "y"=> $jml_bc25),
);
$dataPoints2 = array(
	array("label"=> "BC 2.3", "y"=> $jml_bc23_prev),
	array("label"=> "BC 4.0", "y"=> $jml_bc40_prev),
	array("label"=> "BC 2.7", "y"=> $jml_bc27_prev),
	array("label"=> "BC 2.6.2", "y"=> $jml_bc262_prev),
	array("label"=> "BC 3.0", "y"=> $jml_bc30_prev),
	array("label"=> "BC 4.1", "y"=> $jml_bc41_prev),
	array("label"=> "BC 2.7 Out", "y"=> $jml_bc27out_prev),
	array("label"=> "BC 2.6.1", "y"=> $jml_bc261_prev),
	array("label"=> "BC 2.5", "y"=> $jml_bc25_prev),
);
	
?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light2",
	title:{	text: "Grafik Perdokumen Bulan Ini dan Bulan Lalu" },
	axisY:{ title: "Jumlah Dokumen" },
	axisX:{ title: "Jenis Dokumen" },
	legend:
	{	cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "Bulan Ini",
		indexLabel: "{y}",
		yValueFormatString: "#0",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
	},{
		type: "column",
		name: "Bulan Lalu",
		indexLabel: "{y}",
		yValueFormatString: "#0",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="../../bootstrap/js/canvasjs.min.js"></script>
</body>
</html>