<?php 
include '../../include/conn.php';
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
?>
<div class="box">
  <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
    <?php
    echo "<form method='post' action='rekal_pro.php?mode=$mode'>"; 
    ?>
    <div class="col-md-3">
		  <?php
        if ($mode=="Sal")
        { echo "<div class='form-group'>
                <label>Tanggal Mulai</label>
                <input type='text' class='form-control pull-right' name='txtfrom' id='datepicker1'>
              </div>";
        echo "<div class='form-group'>
                <label>Tanggal Selesai</label>
                <input type='text' class='form-control pull-right' name='txtto' id='datepicker2'>
              </div>";
        }
      ?>
      <div class="form-group">
            <label>Tanggal Bayar</label>
            <input type="text" class="form-control pull-right" name="txtpaydate" id="datepicker3">
      </div>
    </div>
    </div>
    <br>
    <button type="submit" name="submit" class="btn btn-primary">Rekalkulasi</button>
  </div>
	       
		</form>  
		
      
</div>