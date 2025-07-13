<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
$mod = $_GET['mod'];
if(!isset($jenis_company)) { $jenis_company=flookup("jenis_company","mastercompany","company!=''"); }
if (isset($_GET['frexc'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }
if ($excel=="Y")
{ $from=date('Y-m-d',strtotime($_GET['frexc']));
  $to=date('Y-m-d',strtotime($_GET['toexc']));
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
}
  
$titlenya="Laporan Purchase Order";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to);
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="border='1'";}
		$sqlcurr="select distinct curr from acc_pettycash order by curr";
		$qcurr = mysql_query($sqlcurr);
    while($dcurr = mysql_fetch_array($qcurr))
    {	echo "
  		<div class='panel panel-default'>
  			<div class='panel-heading'>$dcurr[curr]</div>
      </div>";
   		echo "<table id='customers' $tbl_border style='font-size: 12px; width: 100%;'>";
				$sqlpet="select tanggal_trans,keterangan,curr,
					sum(if(jenis_trans='PENERIMAAN',amount,0)) amt_in,
					sum(if(jenis_trans!='PENERIMAAN',amount,0)) amt_out from
					acc_pettycash where tanggal_trans between '$from' and '$to' 
					and curr='$dcurr[curr]' group by id order by tanggal_trans";
				$qpet = mysql_query($sqlpet);
		    echo "<thead>";
					echo "
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Keterangan</th>
						<th>Curr</th>
						<th style='text-align:right;'>Masuk</th>
						<th style='text-align:right;'>Keluar</th>
						<th style='text-align:right;'>Balance</th>
					</tr>";
				echo "</thead>";
		    echo "<tbody>";
		    $no=1;
		    $sisa_view=0;
		    while($dpet = mysql_fetch_array($qpet))
	    	{	$sisa=$dpet["amt_in"] - $dpet["amt_out"];
	    		$sisa_view = $sisa_view + $sisa;
	    		echo "
	  			<tr>
	  				<td>$no</td>
	  				<td>".fd_view($dpet["tanggal_trans"])."</td>
	  				<td>$dpet[keterangan]</td>
	  				<td>$dpet[curr]</td>
	  				<td align='right'>".fn($dpet["amt_in"],2)."</td>
	  				<td align='right'>".fn($dpet["amt_out"],2)."</td>
	  				<td align='right'>".fn($sisa_view,2)."</td>
	  			</tr>";
	    		$no++;
		    }
		    echo "</tbody>";
			echo "</table>";
    	echo "<br>";
    }
	echo "</div>";
echo "</div>";
?>