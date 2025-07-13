<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("purch_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$rsc=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rsc["company"];
  $jenis_company = $rsc["jenis_company"];
  $pr_need_app = $rsc["pr_need_app"];
if (isset($_GET['id'])) {$id_jo = $_GET['id']; } else {$id_jo = "";}
if($id_jo!="" and $pr_need_app=="Y")
{ $cek=flookup("jo_no","jo_dev","app='A' and id='$id_jo'");
  if($cek!="")
  { $_SESSION['msg']="XJO Tidak Bisa Dirubah"; 
    echo "<script>window.location.href='../marketting/?mod=15L';</script>";  
  }
}
if (isset($_GET['idd'])) {$id_itjo = $_GET['idd']; } else {$id_itjo = "";}
if (isset($_GET['status'])) {$status = $_GET['status']; } else {$status = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$titlenya="Act-Sample";
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
{	$query = mysql_query("SELECT * FROM bom_dev_jo_item where id_jo='$id_jo' and cancel='N' ");
	$data = mysql_fetch_array($query);
	$id_pre_cost = $data['id_jo'];
	$sql="select a.id,so_no,buyerno,kpno,cost_no,
   	supplier,product_group,product_item,styleno,a.qty,a.unit,
    deldate from so_dev a inner join act_development s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det_dev j on a.id=j.id_so 
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
      from bom_dev_jo_item k inner join so_det_dev l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
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
      url: "ajax_bom_dev.php?mdajax=get_so",
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
<?php if ($mod=="24DEV") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="s_pr_dev.php?mod=<?php echo $mod; ?>&id=<?php echo $id_jo; ?>&idd=<?php echo $id_itjo; ?>&status=<?php echo $status; ?>" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Job Order # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' onchange='getSO()'>
							<?php 
								if ($id_jo!="") 
                { $sql_wh=" where id='$id_jo'";
                  $sql = "select id isi,jo_no tampil from 
                    jo_dev $sql_wh ";
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
if ($mod=="24L") {
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
        <?php if($jenis_company=="VENDOR LG") { ?>
          <th>PO Cust #</th>
          <th>Part #</th>
        <?php } else { ?>
          <th>WS #</th>
        <?php } ?>
        <th>Job Order #</th>
        <th>Job Order Date</th>
        <th>Style #</th>
        <th>Created By</th>
				<th></th>
        <th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select group_concat(distinct concat(' ',ac.kpno)) kpno,
          a.id,a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno,
          count(distinct s.id_item) t_item,a.app,so.buyerno,mp.product_group    
          from jo_dev a inner join bom_dev_jo_item s on a.id=s.id_jo
          inner join jo_det_dev jod on a.id=jod.id_jo
          inner join so_dev so on jod.id_so=so.id inner join act_development ac on so.id_cost=ac.id 
          inner join masterproduct mp on ac.id_product=mp.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username group by a.jo_no order by a.jo_date desc"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
			  { if($jenis_company=="VENDOR LG")
          { $cek=flookup("count(distinct id_gen)","po_item","id_jo='$data[id]' ");
            if ($cek==$data['t_item']) {$bgcol=" style='background-color: red; color:yellow;'";} else {$bgcol="";}
          }
          else
          { $bgcol=""; }
          echo "<tr $bgcol>";
				    echo "
            <td>$no</td>
            <td>$data[supplier]</td>";
            if($jenis_company=="VENDOR LG")
            { echo "
              <td>$data[buyerno]</td>
              <td>$data[product_group]</td>"; }
            else
            { echo "<td>$data[kpno]</td>"; }
            echo "
            <td>$data[jo_no]</td>
            <td>".fd_view($data['jo_date'])."</td>
            <td>$data[styleno]</td>
            <td>$data[fullname]</td>";
            echo "
            <td>
              <a href='pdfPR.php?id=$data[id]'
                data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i>
              </a>
            </td>";
            if(($pr_need_app=="Y" and $data['app']!="A") or $pr_need_app=="N")
            { echo "
              <td>
                <a href='?mod=24DEV&id=$data[id]'
                  data-toggle='tooltip' title='Update'><i class='fa fa-pencil'></i>
                </a>
              </td>";
            }
            else
            { echo "
              <td></td>";
            }
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
    <form name='frmsup' method='post' action='s_upd_supp_pr_dev.php?mod=<?php echo $mod;?>&id=<?php echo $id_jo;?>'>
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
  		  <th>Action</th>
          <th></th>
          <th></th>
  			</tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql = "select k.status,s.id idsubgroup,k.id_item,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
            d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
            g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,m2.supplier supplier2,k.notes,
            k.id_supplier,k.id_supplier2 
            from bom_dev_jo_item k inner join jo_dev jo on k.id_jo=jo.id 
            inner join so_det_dev l on k.id_so_det=l.id inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
            inner join mastertype2 d on s.id=d.id_sub_group
            inner join mastercontents e on d.id=e.id_type
            inner join masterwidth f on e.id=f.id_contents 
            inner join masterlength g on f.id=g.id_width
            inner join masterweight h on g.id=h.id_length
            inner join mastercolor i on h.id=i.id_weight
            inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
            left join mastersupplier m on k.id_supplier=m.id_supplier
            left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
            where k.id_jo='$id_jo' and k.cancel='N' and k.status='M' group by k.id_item
            union all 
            select k.status,0 idsubgroup,k.id_item,l.color,l.size,j.cfdesc item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,m2.supplier supplier2,k.notes,
            k.id_supplier,k.id_supplier2 
            from bom_dev_jo_item k inner join jo_dev jo on k.id_jo=jo.id 
            inner join so_det_dev l on k.id_so_det=l.id inner join 
            masteritem mi on k.id_item=mi.id_item inner join mastercf j on mi.matclass=j.cfdesc 
            left join mastersupplier m on k.id_supplier=m.id_supplier
            left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
            where k.id_jo='$id_jo' and k.cancel='N' group by k.id_item";
        //echo $sql;
          $query = mysql_query($sql); 
          $no = 1; 
		  while($data = mysql_fetch_array($query))
  			  { $cekpo=flookup("group_concat(distinct concat(pono,' ',podate))","po_item a inner join po_header s on a.id_po=s.id","a.id_jo='$id_jo' 
              and a.id_gen='$data[id_item]' and s.jenis!='N' ");
            if ($cekpo>0 or $cekpo!="") {$bgcol=" style='background-color: red; color:yellow;'";} else {$bgcol="";}
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
  						$id_item_bb=flookup("id_item","masteritem","id_gen='$data[id_item]'");
              if ($id_item_bb!="")
              { $sisa_stock=flookup("stock","stock","id_item='$id_item_bb'"); }
              else
              { $sisa_stock=0; }
              echo "<td>$sisa_stock</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplierarr' style='width: 120px;' name='txtsupplierarr[$keycri]' id='txtsupplierarr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where 
                    tipe_sup='S' and non_aktif='0' order by supplier";
                  IsiCombo($sql,$data['id_supplier'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplier2arr' style='width: 120px;' name='txtsupplier2arr[$keycri]' id='txtsupplier2arr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where 
                    tipe_sup='S' and non_aktif='0' order by supplier";
                  IsiCombo($sql,$data['id_supplier2'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "
              <td>$data[notes]</td>
              <td>$cekpo</td>";
              if($cekpo>0)
              { echo "
                <td></td>
                <td></td>";
              }
              else
              { echo "
                <td>
                  <a href='?mod=$mod&idd=$data[id_item]&id=$id_jo&status=$data[status]'
                    data-toggle='tooltip' title='Update'><i class='fa fa-pencil'></i>
                  </a>
                </td>";
                if($sisa_stock>0)
                { echo "
                  <td>
                    <a href='?mod=$mod&idd=$data[id_item]&id=$id_jo&status=$data[status]'
                      data-toggle='tooltip' title='Booking'><i class='fa fa-exchange'></i>
                    </a>
                  </td>";
                }
                else
                { echo "
                  <td></td>";
                }
              }
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