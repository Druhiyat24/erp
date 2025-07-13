<?php
ini_set('memory_limit', '128M');

// echo "Limit: " . ini_get('memory_limit') . "<br>";


// phpinfo();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("general_req","userpassword","username='$user'");
$purch = flookup("gen_req_purch","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");
if (isset($_GET['id'])) {$id_item=$_GET['id'];} else {$id_item="";}
# COPAS EDIT
if ($id_item=="")
{ $reqno = "";
  $reqdate = date('d M Y');
  $notes = "";
  $app_by="";
  $app_by2="";
  $jenis="";
}
else
{ $query = mysql_query("SELECT * FROM reqnon_header where id='$id_item'");
  $data = mysql_fetch_array($query);
  $reqno = $data['reqno'];
  $reqdate = fd_view($data['reqdate']);
  $notes = $data['notes'];
  $app_by = $data['app_by'];
  $app_by2 = $data['app_by2'];
  $jenis = $data['jenis_po'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var reqdate = document.form.txtreqdate.value;
    var jenis = document.form.txtjenis.value;
    var app_by = document.form.txtapp_by.value;
    var app_by2 = document.form.txtapp_by2.value;
    if (reqdate == '') { document.form.txtreqdate.focus(); swal({ title: 'Request Date Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (jenis == '') { swal({ title: 'Jenis Request Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (app_by == '') { swal({ title: 'Approve By Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (app_by == app_by2) { swal({ title: 'Approve By 2 Tidak Boleh Sama', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getStatusApp(cri_item)
  {   
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_status_app.php',
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php 
# COPAS ADD
if ($mod=="1") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_reqno_h.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Request # *</label>
            <input type='text' class='form-control' name='txtreqno' readonly value='<?php echo $reqno;?>' >
          </div>        
          <div class='form-group'>
            <label>Request Date *</label>
            <input type='text' id='datepicker1' class='form-control' name='txtreqdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate;?>' >
          </div>
          <div class='form-group'>
            <label>Jenis Request *</label>
            <select class='form-control select2' style='width: 100%;' name='txtjenis'>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='J_PO'";
                IsiCombo($sql,$jenis,'Pilih Jenis Req');
              ?>
            </select>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' name='txtnotes' 
              placeholder='Masukkan Notes' value='<?php echo $notes;?>' >
          </div>
          <div class='form-group'>
            <label>Approve By *</label>
            <select class='form-control select2' style='width: 100%;' name='txtapp_by'>
              <?php 
                $sql = "select username isi,fullname tampil 
                  from userpassword where approval_gen_req='1'";
                IsiCombo($sql,$app_by,'Pilih Approve By');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Approve By 2 *</label>
            <select class='form-control select2' style='width: 100%;' name='txtapp_by2'>
              <?php 
                $sql = "select username isi,fullname tampil 
                  from userpassword where approval_gen_req='1'";
                IsiCombo($sql,$app_by2,'Pilih Approve By 2');
              ?>
            </select>
          </div>
        </div>
      </form>
    </div>
  </div>
</div><?php 
# END COPAS ADD
} else if ($id_item=="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List General Request</h3>
    <a href='../others/?mod=1' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>&nbsp
<select id="cri_item" class='form-control select2' style='width: 10%;' name='txtstatusapp' onchange='getStatusApp(this.value)'>
  <?php 
    $sql = "select nama_pilihan isi,nama_pilihan tampil 
            from masterpilihan 
            where kode_pilihan='Jen_App' or kode_pilihan='Jen_App2'
            order by nama_pilihan";
    IsiCombo($sql,'','Pilih Status');
  ?>
</select>
  </div>
  <div class="box-body">
    <div id='detail_item'>
<table id="gen_req_data" class="display responsive" style="width:100%">
  <thead>
    <tr>
      <th>Req #</th>
      <th>Req Date</th>
      <th>Supplier</th>
      <th>Description</th>
      <th>Notes</th>
      <th>Status</th>
      <th>Created By</th>
      <th>Created Date</th>
      <th>App By</th>
      <th>App By 2</th>
      <th>Rcvd By</th>
      <th>PO #</th>
      <th></th><th></th><th></th>
    </tr>
  </thead>
</table>

<script>
  function initDataTableFix() {
    if ( $.fn.DataTable.isDataTable('#gen_req_data') ) {
      $('#gen_req_data').DataTable().clear().destroy();
    }

    $('#gen_req_data').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": "ajax_gen_req.php",
      "responsive": true,
      "pageLength": 20,
      "scrollCollapse": true,
      "paging": true,
      "scrollY": "300px",
      "ordering": false,
      "fixedColumns": {
        "leftColumns": 1,
        "rightColumns": 1
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initDataTableFix();
  });
</script>
    </div>  
  </div>
</div>
<?php } ?>
<?php if ($mod=="1" and $id_item!="") { ?>
  <div class="box">
  <div class="box-header">
    <h3 class="box-title">List Item</h3>
    <a href='../others/?mod=1a&id=<?php echo $id_item;?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> Add Item
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>Item Code</th>
        <th>Description</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Unit</th>
        <?php if($purch=="1") { ?>
          <th>Currency</th>
          <th>Price</th>
          <th>Supplier</th>
        <?php } ?>
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select ms.supplier,a.*,s.goods_code,s.itemdesc,s.size 
          from reqnon_item a inner join masteritem s on a.id_item=s.id_item 
          left join mastersupplier ms on a.id_supplier=ms.id_supplier 
          where id_reqno='$id_item' ";
        #echo $sql;
        $result=mysql_query($sql);
        while($data = mysql_fetch_array($result))
        { if($data['cancel']=="Y") {$bgcol=" style='color: red; font-weight:bold;'";} else {$bgcol="";}
          echo "<tr $bgcol>";
            echo "
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[size]</td>";
            echo "<td>".fn($data['qty'],2)."</td>";
            echo "<td>$data[unit]</td>";
            if($purch=="1")
            { echo "<td>$data[curr]</td>";
              echo "<td>".fn($data['price'],2)."</td>";
              echo "<td>$data[supplier]</td>";
            }
            if($data['cancel']=="Y")
            { echo "<td>Cancelled</td>
              <td></td>
              <td></td>";
            }
            else
            { echo "
              <td></td>
              <td>
                <a class='btn btn-primary btn-s' 
                  href='../others/?mod=1a&id=$id_item&idd=$data[id]'
                  data-toggle='tooltip' title='Edit'>
                  <i class='fa fa-pencil'></i></a>
              </td>
              <td>
                <a $cl_hapus href='d_genr.php?mod=$mod&id=$id_item&idd=$data[id]'
                  $tt_hapus";?> 
                  onclick="return confirm('Are You Sure Want To Cancel ?')">
                  <?php echo $tt_hapus2."</a>
              </td>"; 
            }
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>  
  </div>
</div>
<?php } ?>