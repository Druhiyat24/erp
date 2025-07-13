<?php 

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



<!-- Dashboard -->

<div class='box'>

  <div class='box-body' style='display:none;'>

    <div class='row'>

      <div class="col-lg-2 col-xs-4">

        <div class="small-box bg-orange">

          <div class="inner">

            <!-- WAITING PO -->

            <?PHP

            $jml_w_app=flookup("count(id)","po_header","app='W'");

            echo "<h3>$jml_w_app</h3><h6><p>PO Waiting Approval</p></h6>";

            ?>

            <!-- END WAITING PO -->

          </div>

          <div class="icon"><i class="ion ion-bag"></i></div>

          <a href='?mod=det_dash&pow=pow' class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

        </div>

      </div>

      <div class="col-lg-2 col-xs-4">

        <div class="small-box bg-red">

          <div class="inner">

            <!-- LATE ETA -->

            <?PHP

            $today=date('Y-m-d');

            $jml_late_eta=flookup("count(id)","po_header","DATEDIFF(eta,'$today') < 1");

            echo "<h3>$jml_late_eta</h3><h6><p>Late PO From ETA Date</p></h6>";  

            ?>

            <!-- END LATE ETA -->

          </div>

          <div class="icon">

            <i class="ion ion-alert"></i>

          </div>

          <a href="?mod=det_dash&leta=leta" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

        </div>

      </div>

      <div class="col-lg-2 col-xs-4">

        <div class="small-box bg-purple">

          <div class="inner">

            <!-- PR APPROVED - PO BELUM APPROVE -->

            <?PHP

            $jml_pr_po=flookup("count(b.id)","po_header b INNER JOIN po_item a  INNER JOIN jo c","a.id_po=b.id AND a.id_jo=c.id AND b.app='W' AND c.app='A'");

            echo "<h3>$jml_pr_po</h3><h6><p>PR Approved, PO Waiting</p></h6>";

            ?>

            <!-- END PR APPROVED - PO BELUM APPROVE -->

          </div>

          <div class="icon"><i class="ion ion-stats-bars"></i></div>

          <a href="?mod=det_dash&app_pr_po" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

        </div>

      </div>

      <div class="col-lg-2 col-xs-4">

        <div class="small-box bg-aqua">

          <div class="inner">

            <!-- ALL PO -->

            <?PHP

            $jml_po=flookup("count(id)","po_header","pono IS NOT NULL");

            echo "<h3>$jml_po</h3><h6><p>All List PO</p></h6>";

            ?>

            <!-- END ALL PO -->

          </div>

          <div class="icon">

            <i class="ion ion-alert"></i>

          </div>

          <a href="?mod=det_dash&allpo" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

        </div>

      </div>

      <div class="col-lg-2 col-xs-4">

        <div class="small-box bg-olive">

          <div class="inner">

            <!-- NOTIF PO TELAT H+3-->

            <?PHP

            #$datefil=date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));

            #echo $datefil;

            $jml_late_po="SELECT COUNT(*) as jo_total FROM (

            select COUNT(a.id) AS jo_tot

            from jo a 

            inner join po_header b 

            inner join po_item c 

            inner join mastersupplier d 

            where b.id_supplier=d.Id_Supplier 

            AND b.id=c.id_po 

            AND a.id=c.id_jo 

            AND a.app_date IS NOT NULL 

            AND a.jo_date=a.jo_date 

            AND a.app='A' 

            GROUP BY d.Id_Supplier 

            ORDER BY a.jo_date ASC

          ) x";

            $query = mysql_query($jml_late_po);

            $jo_total=0;

            while($data = mysql_fetch_array($query)){

              $jo_total = $data['jo_total'];

            }

echo "<h3>$jo_total</h3><h6><p>Late PO Open by PR Date</p></h6>";

?>

<!-- END NOTIF PO TELAT H+3 -->

</div>

<div class="icon">

  <i class="ion ion-alert"></i>

</div>

<a href="?mod=det_dash&latepo" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

</div>

</div>

<div class="col-lg-2 col-xs-4" >

  <div class="small-box bg-blue">

    <div class="inner">

      <!-- FULL QTY PO (Qty PO = Qty BPB) -->

      <?PHP

      $jml_po=flookup("count(a.id)","jo a inner join po_header b inner join po_item c inner join mastersupplier d ","b.id_supplier=d.Id_Supplier AND b.id=c.id_po AND a.id=c.id_jo AND a.app_date IS NOT NULL AND a.jo_date=a.jo_date AND a.app='A' GROUP BY d.Supplier ORDER BY a.jo_date asc");

      echo "<h3>$jml_po</h3><h6><p>Full Qty PO</p></h6>";

      ?>

      <!-- END FULL QTY PO (Qty PO = Qty BPB) -->

    </div>

    <div class="icon">

      <i class="ion ion-alert"></i>

    </div>

    <a href="?mod=det_dash&fullpo" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

  </div>

</div>

<div class="col-lg-2 col-xs-4" >

  <div class="small-box bg-teal">

    <div class="inner">

      <!-- Belum Terima Qty PO -->

      <?PHP

      $jml_po=flookup("count(a.id)"," bpb a

      INNER JOIN jo b

      INNER JOIN po_header c

      INNER JOIN po_item d

      INNER JOIN mastersupplier e"," a.pono=c.pono

      AND c.pono=d.id_po

      AND a.id_jo=b.id

      AND b.id=d.id_jo

      AND a.id_supplier=e.Id_Supplier

      AND c.pono IS NOT NULL

      AND a.qty=0

      ORDER BY c.podate DESC");

      echo "<h3>$jml_po</h3><h6><p>Qty PO Not Received</p></h6>";

      ?>

      <!-- END Belum Terima Qty PO -->

    </div>

    <div class="icon">

      <i class="ion ion-alert"></i>

    </div>

    <a href="?mod=det_dash&ponotr" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

  </div>

</div>

<div class="col-lg-2 col-xs-4" >

  <div class="small-box bg-maroon">

    <div class="inner">

      <!-- PO PARSIAL -->

      <?PHP

      $jml_po=flookup("count(a.id)","bpb a INNER JOIN jo b on a.id_jo=b.id INNER JOIN po_item d 
        on a.id_po_item=d.id INNER JOIN po_header c on d.id_po=c.id INNER JOIN mastersupplier e 
        on c.id_supplier=e.id_supplier","c.pono IS NOT NULL");

      echo "<h3>$jml_po</h3><h6><p>PO Partial</p></h6>";

      ?>

      <!-- END PO PARSIAL -->

    </div>

    <div class="icon">

      <i class="ion ion-alert"></i>

    </div>

    <a href="?mod=det_dash&popart" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>

  </div>

</div>

</div>

</div>

<!-- End Dashboard -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="js/dashboard.js"></script>

<table>
  <div class="box-body">

    <table id="new_list_dashboard"  style="width:100%">

      <thead>

      <tr>

        <th>No WS</th>

        <th>Style No</th>

        <th>Item</th>

        <th>Qty PR</th>

        <th>1st Garment Delivery</th>
        <th>Target (H-50 Garment Delivery)</th>

      </tr>

      </thead>

      <tbody>

</table>





