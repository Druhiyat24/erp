<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = ""; }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("work_sheet","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

$rpt = $_GET['mod'];
$modv = $rpt."v";
$from = date('d M Y');
$to = date('d M Y');
?>
<script type="text/javascript">
function getStyle(cri_item)
{ var html = $.ajax
  ({  type: "POST",
      url: 'ajax_ws_dev.php?mdajax=cari_style',
      data: "cri_item=" +cri_item,
      async: false
  }).responseText;
  if(html)
  { $("#txtstyle").html(html); }
}
</script>
<?php
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
      echo "var from = document.form.txtfrom.value;";
      echo "var to = document.form.txtto.value;";
      echo "var buyer = document.form.txtid_buyer.value;";
      echo "var stylenya = document.form.txtstyle.value;";
      echo "if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
      echo "else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.focus();valid = false;}";
      if ($mod=="12")
      { echo "else if (buyer == '') { alert('Buyer tidak boleh kosong'); document.form.txtto.focus();valid = false;}";
        echo "else if (stylenya == '') { alert('Style # tidak boleh kosong'); document.form.txtto.focus();valid = false;}";
      }
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI

# COPAS ADD
if ($mod=="23DEV") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>"; 
    	echo "<form method='post' name='form' action='?mod=$modv' onsubmit='return validasi()'>";
      echo "<div class='col-md-3'>";
      	echo "<div class='form-group'>";
      		echo "<label>Dari Delv. Date *</label>";
      		echo "<input type='text' class='form-control' id='datepicker1' name='txtfrom' placeholder='Masukkan Dari Tanggal' value='$from'>";
      	echo "</div>";
      	echo "<div class='form-group'>";
      		echo "<label>Sampai Delv. Date *</label>";
      		echo "<input type='text' class='form-control' id='datepicker2' name='txtto' placeholder='Masukkan Sampai Tanggal' value='$to'>";
      	echo "</div>";
        echo "<button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>";
      echo "</div>";
      if ($mod=="23DEV")
      { echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Buyer *</label>";
            ?>
            <select class='form-control select2' style='width: 100%;' name='txtid_buyer' onchange='getStyle(this.value)'>
            <?php 
              $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";
              IsiCombo($sql,'','Pilih Buyer Name');
            ?>
            </select>
          <?php
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Style # *</label>";
            ?>
            <select class='form-control select2' style='width: 100%;' name='txtstyle' id='txtstyle'>
            </select>
          <?php  
          echo "</div>";
        echo "</div>";
      }
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
} else {
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Worksheet</h3>
    <a href='../marketting/?mod=23DEV' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>Buyer</th>
        <th>Style #</th>
        <th>JO #</th>
        <th>JO Date</th>
        <th>SO #</th>
        <th>WS #</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Curr</th>
        <th>Fob</th>
        <th>Delv.Date</th>
        <th>Created By</th>
        <th>Created Date</th>
        <th>Status</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select ac.styleno,msup.supplier buyer,a.id,jo_no,jo_date,so_no,d.qty,
          d.unit,fob,d.curr,fullname,ac.deldate,ac.kpno,s.cancel from jo_dev a inner join jo_det_dev s on a.id=s.id_jo 
          inner join so_dev d on s.id_so=d.id inner join userpassword up on a.username=up.username
          inner join act_development ac on d.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          group by jo_no order by jo_date desc"); 
        if (!$query) {die(mysql_error());}
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $id=$data['id'];
	
          if($data['cancel']=="Y") { $bgcol=" style='color: red; font-weight:bold;'"; } else { $bgcol=""; }
          echo "<tr $bgcol>";
            echo "
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[jo_no]</td>
            <td>$data[jo_date]</td>
            <td>$data[so_no]</td>
            <td>$data[kpno]</td>
            <td>".fn($data['qty'],0)."</td>
            <td>$data[unit]</td>
            <td>$data[curr]</td>
            <td>".fn($data['fob'],2)."</td>
            <td>".fd_view($data['deldate'])."</td>
            <td>$data[fullname]</td>
            <td>".fd_view($data['jo_date'])."</td>";
            if($data['cancel']=="Y")
            {echo "<td>Cancelled</td>";}
          	else
          	{echo "<td></td>";}
            echo "
            <td>
              <a href='pdfWS_dev.php?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
            </td>";
            echo "
            <td>
              <a href='d_ws_dev.php?mod=$mod&id=$data[id]'
                $tt_hapus";?> 
                onclick="return confirm('Apakah anda yakin akan dicancel ?')">
                <?php echo $tt_hapus2."</a>
            </td>";
            echo "<td></td>"
            /*<td>
              <a href='../marketting/?mod=12a&id=$data[id]'
                data-toggle='tooltip' title='Upload Files'><i class='fa fa-paperclip'></i></a>
            </td>*/;
            echo "<td></td>"
            /*<td>
              <a href='#' class='img-prev-ws' data-id=$data[id]
              	data-toggle='tooltip' title='View'><i class='fa fa-picture-o'></i>
              </a>
            </td>*/;
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>