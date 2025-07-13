<?PHP
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include "../forms/ko.php"; }
else
{ include "../forms/id.php"; }
?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
		  <div class="col-lg-3 col-xs-6">
		    <div class="small-box bg-aqua">
		      <div class="inner">
		        <?PHP
		          $today=date('Y-m-d');
		          $jml_ar=flookup("count(*)","acc_rec","DATEDIFF(due_date,'$today') between 1 and 10 and pay_date='0000-00-00'");
		          echo "<h3>$jml_ar<p>$c_inv</p></h3><h4>AR $c_ajt</h4>";
		        ?>
		      </div>
		      <div class="icon"><i class="ion ion-bag"></i></div>
		      <a href="detail_trans.php?mode=AR" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>
		    </div>
		  </div>
		  <div class="col-lg-3 col-xs-6">
		    <div class="small-box bg-red">
		      <div class="inner">
		        <?PHP
		          $today=date('Y-m-d');
		          $jml_ar=flookup("count(*)","acc_rec","DATEDIFF(due_date,'$today') < 0 and pay_date='0000-00-00'");
		          echo "
		              <h3>$jml_ar<p>$c_inv</p></h3>
		              <h4>AR $c_Tljt</h4>
		              ";
		        ?>
		      </div>
		      <div class="icon">
		        <i class="ion ion-alert"></i>
		      </div>
		      <a href="detail_trans.php?mode=AR2" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>
		    </div>
		  </div>
		  <div class="col-lg-3 col-xs-6">
		    <div class="small-box bg-green">
		      <div class="inner">
		        <?PHP
		          $today=date('Y-m-d');
		          $jml_ap=flookup("count(*)","acc_pay","DATEDIFF(due_date,'$today') between 1 and 10 and pay_date='0000-00-00'");
		          echo "<h3>$jml_ap<p>$c_inv</p></h3><h4>AP $c_ajt</h4>";
		        ?>
		      </div>
		      <div class="icon"><i class="ion ion-stats-bars"></i></div>
		      <a href="detail_trans.php?mode=AP" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>
		    </div>
		  </div>
		  <div class="col-lg-3 col-xs-6">
		    <div class="small-box bg-red">
		      <div class="inner">
		        <?PHP
		          $today=date('Y-m-d');
		          $jml_ap=flookup("count(*)","acc_pay","DATEDIFF(due_date,'$today') < 0 and pay_date='0000-00-00'");
		          echo "
		              <h3>$jml_ap<p>$c_inv</p></h3>
		              <h4>AP $c_Tljt</h4>
		              ";
		        ?>
		      </div>
		      <div class="icon">
		        <i class="ion ion-alert"></i>
		      </div>
		      <a href="detail_trans.php?mode=AP2" class="small-box-footer"><?php echo $c_detail; ?> <i class="fa fa-arrow-circle-right"></i></a>
		    </div>
		  </div>
		</div>
	</div>
</div>