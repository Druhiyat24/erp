<?PHP 
$user = $_SESSION["username"];
$st_company = flookup("status_company","mastercompany","company!=''");
$cek_expired = flookup("notif_expired","userpassword","username='$user'");
if ($_SESSION["first"]=="Y" AND $cek_expired=="1")
{ $last_ann_fee = flookup("last_annual_fee","mastercompany","company!=''");
  $tgl_val_ori = date_create($last_ann_fee);
  date_add($tgl_val_ori,date_interval_create_from_date_string("-30 days"));
  $tgl_val = date_format($tgl_val_ori,"Y-m-d");
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
$bln=date('m');
$thn=date('Y');
if ($st_company!="MULTI_WHS")
{ $query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok,count(distinct bpbno) jml_trans from bpb where 
    month(bpbdate)=$bln and year(bpbdate)=$thn
    and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
  $jml_bc20=0; $jml_bclkl=0; $jml_bc24=0; $jml_bcjuallkl=0; 
  $jml_bc23=0; $jml_bc40=0; $jml_bc27=0; $jml_bc262=0; 
  while($data = mysql_fetch_array($query))
  { if ($data['jenis_dok']=="BC 2.3")
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
  { if ($data['jenis_dok']=="BC 3.0")
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
}

$fullname1=flookup("fullname","userpassword","username='$user'");
$fullname=ucwords(strtolower($fullname1));
?>

<div class="box">
<div class="box-header">
  <h3 class="box-title"><?php echo "Hai $fullname,"; ?></h3>  
  <br>
	<h3 class="box-title"><?php echo $c1; ?></h3>
</div>
<div class="box-body">
  <div class="row">
  <?php
  if ($st_company=="KITE")
  { echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc20<p>$c25</p></h3><h4>BC 2.0</h4>
        </div>
      </div>
    </div>";
    echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-green'>
        <div class='inner'>
          <h3>$jml_bclkl<p>Pembelian</p></h3><h4>LOKAL</h4>
        </div>
      </div>
    </div>";
  }
  else if ($st_company=="GB")
  { echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc23<p>$c25</p></h3><h4>BC 2.3</h4>
        </div>
      </div>
    </div>";
    echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-aqua'>
        <div class='inner'>
          <h3>$jml_bc27<p>$c25</p></h3><h4>BC 2.7</h4>
        </div>
      </div>
    </div>";
  }
  else if ($st_company=="MULTI_WHS")
  { $query = mysql_query("select supplier,sum(qty) jml_qty from bpb a inner join mastersupplier s 
      on a.id_gudang=s.id_supplier where 
      month(bpbdate)=$bln and year(bpbdate)=$thn
      and tipe_sup='G' group by  supplier");
    while($data = mysql_fetch_array($query))
    { echo "
        <div class='col-lg-3 col-xs-6'>
          <div class='small-box bg-yellow'>
            <div class='inner'>
              <h3>$data[jml_qty]<p>Qty Terima</p></h3><h4>$data[supplier]</h4>
            </div>
          </div>
        </div>";
    }
  }
  else
  { echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc23<p>$c25</p></h3><h4>BC 2.3</h4>
        </div>
      </div>
    </div>";
    echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-blue'>
        <div class='inner'>
          <h3>$jml_bc40<p>$c25</p></h3><h4>BC 4.0</h4>
        </div>
      </div>
    </div>";
    echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-aqua'>
        <div class='inner'>
          <h3>$jml_bc27<p>$c25</p></h3><h4>BC 2.7</h4>
        </div>
      </div>
    </div>";
    echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-green'>
        <div class='inner'>
          <h3>$jml_bc262<p>$c25</p></h3><h4>BC 2.6.2</h4>
        </div>
      </div>
    </div>";
  }
  ?>
</div>
</div>
</div>
<div class="box">
<div class="box-header">
    <h3 class="box-title"><?php echo $c2; ?></h3>
</div>
<div class="box-body">
  <div class="row">
  <?php
  if ($st_company=="KITE")
  { echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-blue'>
          <div class='inner'>
            <h3>$jml_bc24<p>$c25</p></h3><h4>BC 2.4</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-green'>
          <div class='inner'>
            <h3>$jml_bcjuallkl<p>Penjualan</p></h3><h4>LOKAL</h4>
          </div>
        </div>
      </div>";
  }
  else if ($st_company=="GB")
  { echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-aqua'>
          <div class='inner'>
            <h3>$jml_bc27out<p>$c25</p></h3><h4>BC 2.7</h4>
          </div>
        </div>
      </div>";
  }
  else if ($st_company=="MULTI_WHS")
  {}
  else
  { echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-blue'>
          <div class='inner'>
            <h3>$jml_bc41<p>$c25</p></h3><h4>BC 4.1</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-aqua'>
          <div class='inner'>
            <h3>$jml_bc27out<p>$c25</p></h3><h4>BC 2.7</h4>
          </div>
        </div>
      </div>";
    echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-green'>
          <div class='inner'>
            <h3>$jml_bc261<p>$c25</p></h3><h4>BC 2.6.1</h4>
          </div>
        </div>
      </div>";
  }
  if ($st_company!="MULTI_WHS" AND $st_company!="KITE")
  { echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-purple'>
          <div class='inner'>
            <h3>$jml_bc25<p>$c25</p></h3><h4>BC 2.5</h4>
          </div>
        </div>
      </div>";
  }
  ?>
</div>
</div>
</div>
</div>
?>