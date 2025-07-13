<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("update_cons_fabric","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$rsc=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rsc["company"];
  $jenis_company = $rsc["jenis_company"];
  $pr_need_app = $rsc["pr_need_app"];
if (isset($_GET['id'])) {$id_jo = $_GET['id']; } else {$id_jo = "";}
if($id_jo!="" and $pr_need_app=="Y" and $jenis_company=="VENDOR LG")
{ $cek=flookup("jo_no","jo","app='A' and id='$id_jo'");
  if($cek!="")
  { $_SESSION['msg']="XJO Tidak Bisa Dirubah"; 
    echo "<script>window.location.href='../marketting/?mod=15L';</script>";  
  }
}
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
{	$query = mysql_query("SELECT * FROM bom_jo_item where id_jo='$id_jo' and cancel='N' ");
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
  { $query = mysql_query("select k.id_item,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
      d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
      g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,
      l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
      k.unit,k.id_supplier,k.id_supplier2,k.notes  
      from bom_jo_item k inner join so_det l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
      inner join mastertype2 d on s.id=d.id_sub_group
      inner join mastercontents e on d.id=e.id_type
      inner join masterwidth f on e.id=f.id_contents 
      inner join masterlength g on f.id=g.id_width
      inner join masterweight h on g.id=h.id_length
      inner join mastercolor i on h.id=i.id_weight
      inner join masterdesc j on i.id=j.id_color and k.id_item=j.id
      where k.id_jo='$id_jo' and k.cancel='N' and k.status='M' and k.id_item='$id_itjo' group by k.id_item"); 
    $rs=mysql_fetch_array($query);
    $txtitemjo=$rs['item'];
    $txtid_supplier=$rs['id_supplier'];
    $txtid_supplier2=$rs['id_supplier2'];
    $txtnotes=$rs['notes'];
  }
}
# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
// echo "<script type='text/javascript'>
// 	function validasi()
// 	{	var supplier = document.form.txtid_supplier.value;
// 		if (supplier == '') { document.form.txtid_supplier.focus(); swal({ title: 'Supplier Tidak Boleh Kosong', $img_alert }); valid = false;}
// 		else valid = true;
// 		return valid;
// 		exit;
// 	}
// </script>";
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
			<form method='post' name='form' enctype='multipart/form-data' action="s_pr_cons.php?mod=<?php echo $mod; ?>&id=<?php echo $id_jo; ?>&idd=<?php echo $id_itjo; ?>&status=<?php echo $status; ?>" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Job Order # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' onchange='getSO()'>
							<?php 
								if ($id_jo!="") 
                { $sql_wh=" where id='$id_jo'";
                  $sql = "select id isi,jo_no tampil from 
                    jo $sql_wh ";
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
                mastersupplier where tipe_sup='S' and non_aktif='0' ";
              IsiCombo($sql,$txtid_supplier,'Pilih Supplier');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Supplier 2</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier2'>
              <?php 
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where tipe_sup='S' and non_aktif='0'";
              IsiCombo($sql,$txtid_supplier2,'Pilih Supplier');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <textarea name='txtnotes' class='form-control' placeholder='Masukkan Notes'><?php echo $txtnotes;?></textarea>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <?php } ?>
			</form>
		</div>
	</div>
</div><?php } 
# END COPAS ADD REMOVE </DIV> TERAKHIR
if ($mod=="15L" or $mod=="15aL" or $mod=="15bL" or $mod=="15LC") {
?>
<?php } else if ($id_jo!="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Purchase Request Detail</h3>
  </div>
  <div class="box-body">
    <form name='frmsup' method='post' action='s_upd_cons_pr.php?mod=<?php echo $mod;?>&id=<?php echo $id_jo;?>'>
      <table id="examplefixnopage" class="display responsive" style="width:100%">
        <thead>
        <tr>
  	    	<th>No</th>
          <th>Item</th>
  				<th>Consumption</th>
          <th>Qty BOM</th>
  				<th>Allowance</th>
          <th>Qty PR</th>
          <th>Unit</th>
          <th>Stock</th>
          <th>Notes</th>
          <th>Status</th>
        </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql = "select k.status,s.id idsubgroup,k.id_item,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
            d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
            g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app,
            k.id_supplier,k.id_supplier2 
            from bom_jo_item k inner join jo on k.id_jo=jo.id 
            inner join so_det l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
            inner join mastertype2 d on s.id=d.id_sub_group
            inner join mastercontents e on d.id=e.id_type
            inner join masterwidth f on e.id=f.id_contents 
            inner join masterlength g on f.id=g.id_width
            inner join masterweight h on g.id=h.id_length
            inner join mastercolor i on h.id=i.id_weight
            inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
            left join mastersupplier m on k.id_supplier=m.id_supplier
            left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
            where k.id_jo='$id_jo' and k.cancel='N' and k.status='M' and k.cons=-1 group by k.id_item ";
          #echo $sql;
          $query = mysql_query($sql); 
          $no = 1; 
  				while($data = mysql_fetch_array($query))
  			  { $allow=flookup("allowance","masterallow","id_sub_group='$data[idsubgroup]'
              and qty1<=$data[qty_bom] and qty2>=$data[qty_bom]");
            if ($allow==null) { $allow=0; }
            $allowq=$data['qty_bom'] * $allow/100;
            $qtypr=$data['qty_bom'] + $allowq; 
            if($data['status']=="P")
            {
              $filjen=" and s.jenis='P' ";
            }
            else
            {
              $filjen=" and s.jenis='M' ";
            }
            $rspo=mysql_fetch_array(mysql_query("select group_concat(distinct concat(pono,' ',podate)) cekpo,
              sum(a.qty) cekqpo from po_item a inner join po_header s 
              on a.id_po=s.id where a.id_jo='$id_jo' and a.id_gen='$data[id_item]' $filjen and a.cancel='N'"));
            $cekpo=$rspo['cekpo'];
            $cekqpo=$rspo['cekqpo'];
            #$test="PR ".$qtypr." PO ".$cekqpo;
            if($cekqpo>=$qtypr) 
            {$bgcol=" style='background-color: red; color:yellow;'";} 
            else if($cekqpo<$qtypr and $cekqpo>0) 
            {$bgcol=" style='background-color: gray; color:yellow;'";} 
            else 
            {$bgcol="";}
            echo "<tr $bgcol>";
  				    echo "<td>$no</td>"; 
              echo "<td>$data[item]</td>";
              $keycri=$data['id_item'].":".$id_jo;
              echo "<td><input type='text' class='form-control txtconsarr' style='width: 120px;' name='txtconsarr[$keycri]' 
                id='txtconsarr$keycri' value='$data[cons]'></td>";
              echo "<td>".fn($data['qty_bom'],2)."</td>";
  						echo "<td>".fn($allow,2)."</td>";
              echo "<td>".fn($qtypr,2)."</td>";
              echo "<td>$data[unit]</td>";
  						$id_item_bb=flookup("id_item","masteritem","id_gen='$data[id_item]'");
              if ($id_item_bb!="")
              { $sisa_stock=flookup("stock","stock","id_item='$id_item_bb'"); }
              else
              { $sisa_stock=0; }
              echo "<td>$sisa_stock</td>";
              echo "
              <td>$data[notes]</td>
              <td>$data[status_app]</td>";
            echo "</tr>";
  				  $no++; // menambah nilai nomor urut
  				}
  			  ?>
        </tbody>
      </table>
      <i style='background-color:red;color:yellow;'>Full PO Created</i>
      <i style='background-color:gray;color:yellow;'>Not Full PO Created</i>
      <br>
      <button type='submit' class='btn btn-primary'>Simpan</button>
    </form>
  </div>
</div>
<?php } ?>