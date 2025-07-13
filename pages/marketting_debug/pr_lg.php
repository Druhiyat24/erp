<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("purch_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_jo = $_GET['id']; } else {$id_jo = "";}
if (isset($_GET['idd'])) {$id_itjo = $_GET['idd']; } else {$id_itjo = "";}
if (isset($_GET['status'])) {$status = $_GET['status']; } else {$status = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$titlenya="Act-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item=$id_jo;
$st_num="style='text-align: right;'";
# COPAS EDIT
if ($id_item=="")
{	$id_pre_cost = "";
	$txtprodgroup="";
  $txtproditem="";
  $txtbuyer="";
  $txtstyle="";
}
else
{	$query = mysql_query("SELECT * FROM bom_jo_item where id_jo='$id_jo'");
	$data = mysql_fetch_array($query);
	$id_pre_cost = $data['id_jo'];
	$sql="select a.id,so_no,buyerno,kpno,cost_no,
   	supplier,product_group,product_item,styleno,a.qty,a.unit,
    deldate from so a inner join act_costing s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det j on a.id=j.id_so 
    where j.id_jo='$id_jo'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $txtprodgroup=$rs['product_group'];
  $txtproditem=$rs['product_item'];
  $txtbuyer=$rs['supplier'];
  $txtstyle=$rs['styleno']; 
  if ($id_itjo!="")
  { $query = mysql_query("select k.id_item,l.color,l.size,concat(a.goods_code,' ',a.itemdesc,' ',
      ifnull(a.color,''),' ',ifnull(a.size,'')) item,
      l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
      k.unit from bom_jo_item k inner join so_det l on k.id_so_det=l.id inner join 
      masteritem a on k.id_item=a.id_item
      where k.id_jo='$id_jo' and k.status='M' and k.id_item='$id_itjo' group by k.id_item"); 
    $rs=mysql_fetch_array($query);
    $txtitemjo=$rs['item'];
  }
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var supplier = document.form.txtid_supplier.value;
		if (supplier == '') { document.form.txtid_supplier.focus(); swal({ title: 'Supplier Tidak Boleh Kosong', $img_alert }); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function getSO()
  { var joid = document.form.txtid_pre_cost.value;
    jQuery.ajax
    ({  
      url: "ajax_bom.php?mdajax=get_so",
      method: 'POST',
      data: {joid: joid},
      dataType: 'json',
      success: function(response)
      {
        $('#txtprodgroup').val(response[0]);
        $('#txtproditem').val(response[1]);
        $('#txtbuyer').val(response[2]);
        $('#txtstyle').val(response[3]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<?php if ($mod=="15") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="s_pr.php?mod=<?php echo $mod; ?>&id=<?php echo $id_jo; ?>&idd=<?php echo $id_itjo; ?>&status=<?php echo $status; ?>" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Job Order # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' onchange='getSO()'>
							<?php 
								if ($id_jo!="") 
                { $sql_wh=" where jo.id='$id_jo'";
                  $sql = "select jo.id isi,concat(jo_no,' ',so_no) tampil from 
                    jo inner join jo_det jod on jo.id=jod.id_jo 
                    inner join so on jod.id_so=so.id $sql_wh ";
                  IsiCombo($sql,$id_pre_cost,'Pilih Job Order #');
                }
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label>Product Group</label>
						<input type='text' class='form-control' readonly id='txtprodgroup' value='<?php echo $txtprodgroup; ?>' placeholder='Masukkan Product Group'>
					</div>				
					<div class='form-group'>
						<label>Product Item</label>
						<input type='text' class='form-control' readonly id='txtproditem' value='<?php echo $txtproditem; ?>' placeholder='Masukkan Product Item'>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Buyer</label>
						<input type='text' class='form-control' readonly id='txtbuyer' value='<?php echo $txtbuyer; ?>'>
					</div>				
					<div class='form-group'>
						<label>Style #</label>
						<input type='text' class='form-control' readonly id='txtstyle' value='<?php echo $txtstyle; ?>' placeholder='Masukkan Style #' >
					</div>
          <?php if ($id_itjo!="") { ?>
          <div class='form-group'>
            <label>Item</label>
            <input type='text' class='form-control' value='<?php echo $txtitemjo; ?>' readonly>
          </div>
          <?php } ?>
				</div>
        <?php if ($id_itjo!="") { ?>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Supplier</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier'>
              <?php 
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where tipe_sup='S' ";
              IsiCombo($sql,'','Pilih Supplier');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supplier 2</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier2'>
              <?php 
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where tipe_sup='S' ";
              IsiCombo($sql,'','Pilih Supplier');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' name='txtnotes' class='form-control' placeholder='Masukkan Notes' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <?php } ?>
			</form>
		</div>
	</div>
</div><?php } 
# END COPAS ADD REMOVE </DIV> TERAKHIR
if ($mod=="15L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List PR</h3>
    <!--<a href='../marketting/?mod=15' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>-->
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	    	<th>No</th>
        <th>Buyer</th>
        <th>Job Order #</th>
        <th>Job Order Date</th>
        <th>Style #</th>
        <th>Created By</th>
				<th>Action</th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.id,a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno 
          from jo a inner join bom_jo_item s on a.id=s.id_jo
          inner join jo_det jod on a.id=jod.id_jo
          inner join so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username group by a.jo_no"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[jo_no]</td>";
            echo "<td>".fd_view($data['jo_date'])."</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[fullname]</td>";
            echo "
            <td>
              <a class='btn btn-info btn-s' href='pdfPR.php?id=$data[id]'
                data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a $cl_ubah href='?mod=15&id=$data[id]'
              	$tt_ubah</a>
            </td>";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if ($id_jo!="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Purchase Request Detail</h3>
  </div>
  <div class="box-body">
    <form name='frmsup' method='post' action='s_upd_supp_pr.php?mod=<?php echo $mod;?>&id=<?php echo $id_jo;?>'>
      <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
        <tr>
  	    	<th>No</th>
          <th>Item</th>
  				<th>Qty BOM</th>
  				<th>Allowance</th>
          <th>Qty PR</th>
          <th>Unit</th>
          <th>Stock</th>
          <th>Supplier</th>
          <th>Supplier 2</th>
          <th>Notes</th>
          <th>PO #</th>
          <th>Status</th>
  				<th>Action</th>
  			</tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql = "select k.status,0 idsubgroup,k.id_item,l.color,l.size,
            concat(a.goods_code,' ',a.itemdesc,' ',IFNULL(a.color,''),' ',IFNULL(a.size,'')) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,k.notes,if(jo.app='W','Waiting','Approved') status_app,
            k.id_supplier,k.id_supplier2 
            from bom_jo_item k inner join jo on k.id_jo=jo.id 
            inner join so_det l on k.id_so_det=l.id inner join 
            masteritem a on a.id_item=k.id_item 
            left join mastersupplier m on k.id_supplier=m.id_supplier
            where k.id_jo='$id_jo' and k.status in ('M','P') group by k.id_item ";
          #echo $sql;
          $query = mysql_query($sql); 
          $no = 1; 
  				while($data = mysql_fetch_array($query))
  			  { $cekpo=flookup("group_concat(distinct pono)","po_item a inner join po_header s on a.id_po=s.id","a.id_jo='$id_jo' 
                and a.id_gen='$data[id_item]' and s.jenis!='N' group by a.id_gen");
            if ($cekpo!="") {$bgcol=" style='background-color: red; color:yellow;'";} else {$bgcol="";}
            echo "<tr $bgcol>";
  				    echo "<td>$no</td>"; 
              echo "<td>$data[item]</td>";
  						echo "<td>".fn($data['qty_bom'],2)."</td>";
  						$allow=flookup("allowance","masterallow","id_sub_group='$data[idsubgroup]'
                and qty1<=$data[qty_bom] and qty2>=$data[qty_bom]");
              if ($allow==null) { $allow=0; }
              $allowq=$data['qty_bom'] * $allow/100;
              $qtypr=$data['qty_bom'] + $allowq; 
              echo "<td>".fn($allow,2)."</td>";
              echo "<td>".fn($qtypr,2)."</td>";
              echo "<td>$data[unit]</td>";
  						$id_item_bb=$data['id_item'];
              if ($id_item_bb!="")
              { $sisa_stock=flookup("stock","stock","mattype<>'FG' and id_item='$id_item_bb'"); }
              else
              { $sisa_stock=0; }
              echo "<td>$sisa_stock</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplierarr' style='width: 120px;' name='txtsupplierarr[$keycri]' id='txtsupplierarr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='S' order by supplier";
                  IsiCombo($sql,$data['id_supplier'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplier2arr' style='width: 120px;' name='txtsupplier2arr[$keycri]' id='txtsupplier2arr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='S' order by supplier";
                  IsiCombo($sql,$data['id_supplier2'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "<td>$data[notes]</td>";
              echo "<td>$cekpo</td>";
              echo "<td>$data[status_app]</td>";
              echo "
              <td>
              	<a href='?mod=$mod&idd=$data[id_item]&id=$id_jo&status=$data[status]'
                	$tt_ubah</a>
              </td>";
  					echo "</tr>";
  				  $no++; // menambah nilai nomor urut
  				}
  			  ?>
        </tbody>
      </table>
      <button type='submit' class='btn btn-primary'>Simpan</button>
    </form>
  </div>
</div>
<?php } ?>