<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod==24)
{	echo "Laporan Monitoring"; }
else if ($mod==25)
{	if ($excel=="Y")
  { #$id_item_fg=$_GET['frexc'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  }
  else if (isset($_GET['noid'])) 
  { #$id_item_fg=$_GET['noid'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  }
  else
  { #$id_item_fg=$_POST['txtkpno'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  } 
}
?>
