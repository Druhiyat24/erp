<?php
$thn=date('Y');
$jenis_company=flookup("jenis_company","mastercompany","company!=''");
if ($jenis_company=="VENDOR LG")
{	$fld_date="so.so_date"; }
else
{	$fld_date="ac.deldate"; }
$sql="select month($fld_date) Bln,sum(ac.qty) QtyCost,sum(so.qty) QtySO 
	from act_costing ac left join so on ac.id=so.id_cost
	where year($fld_date)='$thn' group by month(ac.deldate)";
#echo $sql;
$query=mysql_query($sql);
$jan_cs=0; $feb_cs=0; $mar_cs=0; $apr_cs=0; $may_cs=0; $jun_cs=0;
$jul_cs=0; $aug_cs=0; $sep_cs=0; $okt_cs=0; $nov_cs=0; $des_cs=0;
$jan_so=0; $feb_so=0; $mar_so=0; $apr_so=0; $may_so=0; $jun_so=0;
$jul_so=0; $aug_so=0; $sep_so=0; $okt_so=0; $nov_so=0; $des_so=0;
while($data = mysql_fetch_array($query))
{	if ($data['Bln']==1)
	{	$jan_so=$data['QtySO']; }
	else if ($data['Bln']==2)
	{	$feb_so=$data['QtySO']; }
	else if ($data['Bln']==3)
	{	$mar_so=$data['QtySO']; }
	else if ($data['Bln']==4)
	{	$apr_so=$data['QtySO']; }
	else if ($data['Bln']==5)
	{	$may_so=$data['QtySO']; }
	else if ($data['Bln']==6)
	{	$jun_so=$data['QtySO']; }
	else if ($data['Bln']==7)
	{	$jul_so=$data['QtySO']; }
	else if ($data['Bln']==8)
	{	$aug_so=$data['QtySO']; }
	else if ($data['Bln']==9)
	{	$sep_so=$data['QtySO']; }
	else if ($data['Bln']==10)
	{	$okt_so=$data['QtySO']; }
	else if ($data['Bln']==11)
	{	$nov_so=$data['QtySO']; }
	else if ($data['Bln']==12)
	{	$des_so=$data['QtySO']; }
	
	if ($data['Bln']==1)
	{	$jan_cs=$data['QtyCost']; }
	else if ($data['Bln']==2)
	{	$feb_cs=$data['QtyCost']; }
	else if ($data['Bln']==3)
	{	$mar_cs=$data['QtyCost']; }
	else if ($data['Bln']==4)
	{	$apr_cs=$data['QtyCost']; }
	else if ($data['Bln']==5)
	{	$may_cs=$data['QtyCost']; }
	else if ($data['Bln']==6)
	{	$jun_cs=$data['QtyCost']; }
	else if ($data['Bln']==7)
	{	$jul_cs=$data['QtyCost']; }
	else if ($data['Bln']==8)
	{	$aug_cs=$data['QtyCost']; }
	else if ($data['Bln']==9)
	{	$sep_cs=$data['QtyCost']; }
	else if ($data['Bln']==10)
	{	$okt_cs=$data['QtyCost']; }
	else if ($data['Bln']==11)
	{	$nov_cs=$data['QtyCost']; }
	else if ($data['Bln']==12)
	{	$des_cs=$data['QtyCost']; }
}
$dataPoints1 = 
array
(	array('label'=> "Jan", "y"=> $jan_cs),
	array('label'=> "Feb", "y"=> $feb_cs),
	array('label'=> "Mar", "y"=> $mar_cs),
	array('label'=> "Apr", "y"=> $apr_cs),
	array('label'=> "May", "y"=> $may_cs),
	array('label'=> "Jun", "y"=> $jun_cs),
	array('label'=> "Jul", "y"=> $jul_cs),
	array('label'=> "Aug", "y"=> $aug_cs),
	array('label'=> "Sep", "y"=> $sep_cs),
	array('label'=> "Okt", "y"=> $okt_cs),
	array('label'=> "Nov", "y"=> $nov_cs),
	array('label'=> "Des", "y"=> $des_cs),
);
$dataPoints2 = 
array
(	array('label'=> "Jan", "y"=> $jan_so),
	array('label'=> "Feb", "y"=> $feb_so),
	array('label'=> "Mar", "y"=> $mar_so),
	array('label'=> "Apr", "y"=> $apr_so),
	array('label'=> "May", "y"=> $may_so),
	array('label'=> "Jun", "y"=> $jun_so),
	array('label'=> "Jul", "y"=> $jul_so),
	array('label'=> "Aug", "y"=> $aug_so),
	array('label'=> "Sep", "y"=> $sep_so),
	array('label'=> "Okt", "y"=> $okt_so),
	array('label'=> "Nov", "y"=> $nov_so),
	array('label'=> "Des", "y"=> $des_so),
);
?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
	window.onload = function () {
 	var chart = new CanvasJS.Chart("chartContainer", 
 	{	animationEnabled: true,
		exportEnabled: true,
		theme: "light2",
		title:{	text: "Costing vs Sales Order" },
		axisY:{ title: "Quantity" },
        legend:
		{	cursor: "pointer",
			verticalAlign: "center",
			horizontalAlign: "right",
			itemclick: toggleDataSeries
		},
		data: [{
			type: "column",
			name: "Costing",
			indexLabel: "{y}",
			yValueFormatString: "#,##0",
			showInLegend: true,
			dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		},
		{
		type: "column",
		name: "Sales Order",
		indexLabel: "{y}",
		yValueFormatString: "#,##0",
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