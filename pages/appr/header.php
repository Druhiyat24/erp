<?php
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
    $nm_company=$rscomp["company"];
    $jenis_company=$rscomp["jenis_company"];
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  $rs=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
  $app_costing=$rs['approval_costing'];
  $app_pr=$rs['approval_pr'];
  $app_po=$rs['approval_po'];
  $app_ptk=$rs['approval_ptk'];
    $bpb_cancel=$rs['Bpb_Cancel'];
	 $app_draft_po=$rs['APP_DRA_PO'];
  $app_gen_req=$rs['approval_gen_req'];
  $kode_mkt=$rs['kode_mkt'];
   $app_listpayment=$rs['app_listpayment'];
  include "services_app.php";
  $services = new services(); 
?>
<nav class="navbar navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company;?></b></a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <?php if ($app_costing=="1") 
      { $cek=flookup("count(distinct ac.cost_no)","act_costing ac inner join act_costing_mat acm on ac.id=acm.id_act_cost 
          inner join userpassword up on ac.username=up.username","
          app1='W' and status='CONFIRM' and cost_date >= '2024-01-01'");
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=2">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek; ?>
          </span>
            Costing
          </a>
        </li>
      </ul>
     <!-- <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=sampledev1">
          <span class="label label-danger" style="font-size:10px;">
          <?php // echo $cek; ?>
          </span>
            Sample Development
          </a>
        </li>
      </ul>
	  -->
      <?php } ?>
      <?php if ($app_pr=="1") 
      { $cek=flookup("count(distinct a.jo_no)","jo a inner join bom_jo_item s on a.id=s.id_jo
          inner join jo_det jod on a.id=jod.id_jo
          inner join so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username","app='W' and so.so_date >= '2024-01-01'"); 
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=3">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Purchase Request
          </a>
        </li>
      </ul>
      <?php } ?>
<!--     <?php if ($app_listpayment=="1") 
      { 
    $cek = $services->getApproval();
      ?>  
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=ApprovalListPaymant">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Finance
          </a>
        </li>
      </ul>   
    
      <?php } ?>     -->
          <?php if ($app_draft_po=="1") 
      { 
        // $cek=flookup("count(distinct draftno)","po_header_draft a inner join po_item_draft s on a.id=s.id_po_draft","s.cancel='N' and app='W'"); 
        $cek=flookup("count(a.id)","po_header_draft a
inner join (select id_po_draft,count(id),count(case cancel when 'Y' then 1 else null end) from po_item_draft 
where id_po_draft is not null
group by id_po_draft
having count(id) > count(case cancel when 'Y' then 1 else null end)
) b on a.id = b.id_po_draft
inner join mastersupplier ms on a.id_supplier = ms.id_Supplier
inner join masterpterms mp on a.id_terms = mp.id",
"a.app = 'W' and draftdate >= '2024-01-01'");         
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=4_draft">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Draft PO
          </a>
        </li>
      </ul>
      <?php } ?>      
    
      <?php if ($app_po=="1") 
      { $cek=flookup("count(distinct pono)","po_header a inner join po_item s on a.id=s.id_po","s.cancel='N' and app='W' and a.podate >= '2024-01-01'"); 
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=4">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Purchase Order
          </a>
        </li>
      </ul>
      <?php } ?>
            <?php if ($app_costing=="1" and $jenis_company!="VENDOR LG") 
      { $cek=flookup("count(distinct ac.cost_no)","act_development ac inner join act_development_mat acm on ac.id=acm.id_act_cost 
          inner join userpassword up on ac.username=up.username","
          app1='W' ");
      ?>
   <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=sampledev1">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek; ?>
          </span>
            Sample Dev
          </a>
        </li>
      </ul>
	  
         <?php } ?>
      <?php if ($app_pr=="1" and $jenis_company!="VENDOR LG") 
      { $cek=flookup("count(distinct a.jo_no)","jo_dev a inner join bom_dev_jo_item s on a.id=s.id_jo
          inner join jo_det_dev jod on a.id=jod.id_jo
          inner join so_dev so on jod.id_so=so.id inner join act_development ac on so.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username","app='W'"); 
      ?>

    <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=sampledev2">  
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Request Pr Dev
          </a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($app_po=="1" and $jenis_company!="VENDOR LG") 
      { $cek=flookup("count(distinct pono)","po_header_dev a inner join po_item_dev s on a.id=s.id_po","s.cancel='N' and app='W'"); 
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=sampledev3">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            Purchase Dev
          </a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($app_gen_req=="1") 
      { $cek=flookup("count(distinct reqno)","reqnon_header a inner join reqnon_item s on a.id=s.id_reqno",
          "s.cancel='N' and (((a.app='W' or a.app='R') and a.app_by='$user') or ((a.app2='W' or a.app2='R') and a.app_by2='$user')) "); 
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=5">
          <span class="label label-danger" style="font-size:10px;">
          <?php echo $cek;?>
          </span>
            General Request
          </a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($app_ptk=="1") 
      { $cricek="(setuju1='$user' and setuju1_app='W')";
        $cricek=$cricek." or (setuju2='$user' and setuju2_app='W')";
        $cricek=$cricek." or (setuju3='$user' and setuju3_app='W')";
        $cricek=$cricek." or (ketahui='$user' and ketahui_app='W')";
        // $cek=flookup("count(*)","form_tenaga_kerja",$cricek); 
      ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../appr/?mod=6">
            <span class="label label-danger" style="font-size:10px;">
            <?php echo $cek; ?>
            </span>
            Permintaan TK
          </a>
        </li>
      </ul>
      <?php } ?>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Tools<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php 
            if ($app_po=="1") 
            { echo "<li><a href='?mod=7'>Unapprove PO</a></li>"; }
            if ($bpb_cancel=="1") 
            { echo "<li><a href='?mod=bpb_cancel'>Cancel Bpb</a></li>"; }		
            ?>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="../">Main Menu</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    