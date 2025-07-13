<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("update_smv","userpassword","username='$user'");
if ($akses=="0") 
  { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
$cprodgr="Product Group"; 
$cprodit="Product Item";
$cstyle="Style #";
?>
<script type='text/javascript'>
  function CalcSMVSec()
  { 
    var smvnya = document.form.txtsmv_sec.value;
    var qtynya = document.form.txtqty.value;
    jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_smv_sec",
      method: 'POST',
      data: {smvnya: smvnya,qtynya: qtynya},
      dataType: 'json',
      success: function(response)
      { $('#txtsmv_min').val(response[0]); 
      $('#txtbook_min').val(response[1]);
      $('#txtbook_sec').val(response[2]);
      $('#txtsmv_sec').val(response[3]);
    },
    error: function (request, status, error) 
    { alert(request.responseText); },
  });
  };
  function CalcSMVMin()
  { 
    var smvnya = document.form.txtsmv_min.value;
    var qtynya = document.form.txtqty.value;
    jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_smv_min",
      method: 'POST',
      data: {smvnya: smvnya,qtynya: qtynya},
      dataType: 'json',
      success: function(response)
      { $('#txtsmv_sec').val(response[0]); 
      $('#txtbook_sec').val(response[1]);
      $('#txtbook_min').val(response[2]);
      $('#txtsmv_min').val(response[3]);
    },
    error: function (request, status, error) 
    { alert(request.responseText); },
  });
  };
  function validasi()
  { 
    var costno = document.form.txtid_cost.value;
    if (costno == '') { swal({ title: 'Costing Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  };
  function getActCost()
  { 
    var actcostid = document.form.txtid_cost.value;
    jQuery.ajax
    ({  
      url: "ajax_sales_ord.php?mdajax=get_act_cost",
      method: 'POST',
      data: {actcostid: actcostid},
      dataType: 'json',
      success: function(response)
      {
        $('#txtprodgroup').val(response[0]);
        $('#txtproditem').val(response[1]);
        $('#txtstyleno').val(response[2]);
        $('#txtwsno').val(response[3]);
        $('#txtqty').val(response[8]);
        $('#txtunit').val(response[9]);
        document.getElementById('attach_file').src = 'upload_files/costing/'+response[10];
        $('#txtsmv_min').val(response[11]);
        $('#txtsmv_sec').val(response[12]);
        $('#txtbook_min').val(response[13]);
        $('#txtbook_sec').val(response[14]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="updsmvl") {
?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List Data</h3>
    </div>
    <div class="box-body">
      <table id="examplefix4New" class="display responsive" style="width:100%;font-size:13px;">
        <thead>
          <tr>
            <th>Costing #</th>
            <th>Costing Date</th>
            <th>Buyer</th>
            <th>Style #</th>
            <th>WS #</th>
            <th>Product Group</th>
            <th>Product Item</th>
            <th>Qty</th>
            <th>Delv. Date</th>
            <th>Status</th>
            <th>SMV (Min)</th>
            <th>SMV (Sec)</th>
            <th>Created By</th>
            <th></th>
          </tr>
        </thead>
        <?php 
        $sql="select a.id,cost_no,cost_date,supplier,styleno,
          qty,deldate,fullname,status,mp.product_item,mp.product_group,
          a.username,kpno,a.smv_min,a.smv_sec from act_costing a inner join mastersupplier s 
          on a.id_buyer=s.id_supplier inner join userpassword d 
          on a.username=d.username inner join masterproduct mp 
          on a.id_product=mp.id
          ORDER BY id DESC";        
        //where ifnull(smv_min,0)=0 and ifnull(smv_sec,0)=0
		$result=mysql_query($sql);
        while($rs = mysql_fetch_array($result))
        { echo "
          <tr>
            <td>$rs[cost_no]</td>
            <td>".fd_view($rs['cost_date'])."</td>
            <td>$rs[supplier]</td>
            <td>$rs[styleno]</td>
            <td>$rs[kpno]</td>
            <td>$rs[product_group]</td>
            <td>$rs[product_item]</td>
            <td>".fn($rs['qty'],0)."</td>
            <td>".fd_view($rs['deldate'])."</td>
            <td>$rs[status]</td>
            <td>$rs[smv_min]</td>
            <td>$rs[smv_sec]</td>
            <td>$rs[fullname]</td>
            <td>
              <a class='btn-s' href='?mod=updsmv&id=$rs[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
              </a>
            </td>
          </tr>";
        }
        ?>  
      </table>
    </div>
  </div>
<?php } else { 
  if(isset($_GET['id'])) {$sql_whe=" where a.id='$_GET[id]'";} else {$sql_whe="";}
  $id_cost=$_GET['id'];
  $sql="select d.supplier,f.product_group,f.product_item,styleno
    ,g.kpno,g.deldate,g.cfm_price,g.curr,g.qty,g.unit,g.attach_file,smv_min,smv_sec,book_min,book_sec   
    from act_costing g inner join mastersupplier d on g.id_buyer=d.id_supplier 
    inner join masterproduct f on g.id_product=f.id 
    where g.id='$id_cost'";
  $rscst=mysql_fetch_array(mysql_query($sql));
  // echo json_encode(array(,1
  //   ,2,3,$rscst['supplier']
  //   ,fd_view($rs['deldate'])
  //   ,$rs['cfm_price']
  //   ,$rs['curr'],8,9
  //   ,$rs['attach_file']
  //   ,11
  //   ,12
  //   ,$rs['book_min']
  //   ,$rs['book_sec']));
  ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='s_upd_smv.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Costing # *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_cost' onchange='getActCost()'>
              <?php
              $sql = "select a.id isi,a.attach_file,concat(a.cost_no,'|',a.kpno,'|',ms.supplier,'|',a.styleno) tampil from 
                act_costing a inner join mastersupplier ms on a.id_buyer=ms.id_supplier 
                $sql_whe ";
              IsiCombo($sql,$id_cost,'Pilih Costing #');
              //getActCost();
			        ?>
            </select>
		  </div>
          <div class='form-group'>
            <label><?php echo $cprodgr; ?></label>
            <input type='text' class='form-control' name='txtprodgroup' id='txtprodgroup' value='<?=$rscst['product_group']?>' readonly >
          </div>        
          <div class='form-group'>
            <label><?php echo $cprodit; ?></label>
            <input type='text' class='form-control' name='txtproditem' id='txtproditem' value='<?=$rscst['product_item']?>' readonly >
          </div>        
          <div class='form-group'>
            <label><?php echo $cstyle; ?></label>
            <input type='text' class='form-control' name='txtstyleno' id='txtstyleno' value='<?=$rscst['styleno']?>' readonly >
          </div>        
          <div class='form-group'>
            <label>WS Number</label>
            <input type='text' class='form-control' id='txtwsno' value='<?=$rscst['kpno']?>' readonly >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <div class='col-md-3'>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Qty</label>
              <input type='text' class='form-control' name='txtqty' id='txtqty' value='<?=$rscst['qty']?>' readonly >
            </div>
          </div>
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Unit</label>
              <label>Qty</label>
              <input type='text' class='form-control' name='txtunit' id='txtunit' value='<?=$rscst['unit']?>' readonly>
            </div>
          </div>
          <div class='col-md-6'>        
            <div class='form-group'>
              <label>SMV (Min) *</label>
              <input type='text' class='form-control' name='txtsmv_min' id='txtsmv_min' 
              value='<?=$rscst['smv_min']?>' onchange='CalcSMVMin()'>
            </div>
          </div>
          <div class='col-md-6'>        
            <div class='form-group'>
              <label>SMV (Sec) *</label>
              <input type='text' class='form-control' name='txtsmv_sec' id='txtsmv_sec' 
              value='<?=$rscst['smv_sec']?>' onchange='CalcSMVSec()'>
            </div>
          </div>
          <div class='col-md-6'>        
            <div class='form-group'>
              <label>Book (Min) *</label>
              <input type='text' class='form-control' readonly name='txtbook_min' id='txtbook_min' value='<?=$rscst['smv_min']*$rscst['qty']?>'>
            </div>
          </div>
          <div class='col-md-6'>        
            <div class='form-group'>
              <label>Book (Sec) *</label>
              <input type='text' class='form-control' readonly name='txtbook_sec' id='txtbook_sec' value='<?=$rscst['smv_sec']*$rscst['qty']?>'>
            </div>
          </div>
          <div class='col-md-6'>
            <div class='col-md-12'>
             
              <div class='form-group'>
                <label>Image</label>
                <div>&nbsp&nbsp&nbsp<img src='upload_files/costing/' id='attach_file' name='attach_file' width='300px' height='300px'></div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form>
<?php } 
# END COPAS ADD
?>
<script>
   $(document).ready(function() {
    var table = $('#examplefix4New').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
		//order: [1, "desc" ],
		ordering: false,
		//columnDefs : [{"targets":1, "type":"date","targets":8, "type":"date"}],
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  }); 

</script>