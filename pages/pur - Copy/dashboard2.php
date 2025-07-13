<?PHP 
$user = $_SESSION["username"];
$st_company = flookup("status_company","mastercompany","company!=''");
$cek_expired = flookup("notif_expired","userpassword","username='$user'");
if ($_SESSION["first"]=="Y" AND $cek_expired=="1")
{	$last_ann_fee = flookup("last_annual_fee","mastercompany","company!=''");
	$tgl_val = date_create($last_ann_fee);
	date_add($tgl_val,date_interval_create_from_date_string("-30 days"));
	$tgl_val = date_format($tgl_val,"Y-m-d");
	$tgl_skrg = date("Y-m-d");
	if ($tgl_skrg>=$tgl_val)
  { if ($tgl_skrg>=$last_ann_fee)
  	{	$_SESSION["expired"]="Y";
  		$msgtext = "Hosting Sudah Expired Sejak ".date_format(date_create($last_ann_fee),"d-M-Y");
    	echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/error.jpg' });</script>";
  	}
  	else
  	{	$_SESSION["expired"]="N";
  		$msgtext = "Hosting Akan Expired Pada ".date_format(date_create($last_ann_fee),"d-M-Y");
    	echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/warning.jpg' });</script>";
  	}
  }
	$_SESSION["first"]="N";
}
if ($st_company=="") 
{	echo "<script>
		alert('Status Company Tidak Ditemukan');
		window.location.href='../../index.php';
	</script>";
}
$user=$_SESSION['username'];
$fullname1=flookup("fullname","userpassword","username='$user'");
$fullname=ucwords(strtolower($fullname1));
?>

<div class="box">
<div class="box-header">
  <h3 class="box-title"><?php echo "Hai $fullname,"; ?></h3>  
</div>
<div class="box-body">
  <?php include "chart2.php"; ?>
</div>
</div>