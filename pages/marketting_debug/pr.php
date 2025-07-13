<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
if($mod=="15LC")
{
  $akses = flookup("update_cons_fabric","userpassword","username='$user'");
}
else
{
  $akses = flookup("purch_req","userpassword","username='$user'");
}
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
			<form method='post' name='form' enctype='multipart/form-data' action="s_pr.php?mod=<?php echo $mod; ?>&id=<?php echo $id_jo; ?>&idd=<?php echo $id_itjo; ?>&status=<?php echo $status; ?>" onsubmit='return validasi()'>
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
<div class="box">
  <div class="box-header">
    <?php if($jenis_company=="VENDOR LG") { ?>
      <form method="post" action="../marketting/?mod=15det">
        <h3 class="box-title">List PR</h3>
        <input type='text' class='form-group form-control' style='width:200px;' name='txtitemdet' placeholder='Item #'>
        <select class='form-control select2' style='width: 200px;' name='txtsupplierdet'>
          <?php 
          $sql="select id_supplier isi,concat(supplier,' ',supplier_code) tampil from mastersupplier where 
            tipe_sup='S' and non_aktif='0' order by supplier";
          IsiCombo($sql,"",'Supplier');
          ?>
        </select>
        <button type="submit" class='btn btn-primary'>Search</button>
      </form>
    <?php } else { ?>
      <h3 class="box-title">List PR</h3>
    <?php } ?>
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
		<th>Status</th>
		<th></th>
        <th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if($mod=="15bL") 
        { $sql_cri=" where ac.status='CONSOLIDATE'"; 
          $sql_join=" left ";
        } 
        else if($mod=="15LC")
        { $sql_cri=" where s.cons=-1 "; 
          $sql_join=" inner ";
        }
        else 
        { $sql_cri=""; 
          $sql_join=" inner ";
        }
		$sql = "select group_concat(distinct concat(' ',ac.kpno)) kpno,
          a.id,a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno,
          count(distinct s.id_item) t_item,a.app,so.buyerno,mp.product_group,max(s.dateinput) last_update,
		  if(a.app='W','Waiting','Approved') status_app,a.app_by,a.app_date		  
          from jo a $sql_join join bom_jo_item s on a.id=s.id_jo
          inner join jo_det jod on a.id=jod.id_jo
          inner join so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
          inner join masterproduct mp on ac.id_product=mp.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username $sql_cri group by a.jo_no order by a.jo_date desc";
		#echo $sql;
        $query = mysql_query($sql); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
			  { 
          $createby=$data['fullname']." ".fd_view_dt($data['last_update']);
          $appnya = $data['status_app'].' '.$data['app_by']." ".fd_view_dt($data['app_date']);
		  if($jenis_company=="VENDOR LG")
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
            <td>$createby</td>
            <td>$appnya</td>";
            echo "
            <td>
              <a href='pdfPR.php?id=$data[id]'
                data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i>
              </a>
            </td>";
            if(($pr_need_app=="Y" and $data['app']!="A") or $pr_need_app=="N" or $jenis_company!="VENDOR LG")
            { if($mod=="15aL") { $modnew="15a"; } 
              else if($mod=="15bL") { $modnew="15b"; } 
              else if($mod=="15LC") { $modnew="15C"; } else { $modnew="15"; }
              echo "
              <td>
                <a href='?mod=$modnew&id=$data[id]'
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
    <form name='frmsup' method='post' action='s_upd_supp_pr.php?mod=<?php echo $mod;?>&id=<?php echo $id_jo;?>'>
      <table id="examplefixnopage" class="display responsive" style="width:100%">
        <thead>
        <tr>
  	    	<th>No</th>
          <th>Item</th>
  				<th>Qty BOM</th>
  				<th>Allowance</th>
          <th>Qty PR</th>
          <th>Unit</th>
          <th>Stock</th>
          <th>Booking</th>
          <th>Supplier</th>
          <th>Supplier 2</th>
          <th>Notes</th>
          <th>Status</th>
          <th>PO #</th>
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
            k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app,
            k.id_supplier,k.id_supplier2,a.id nama_group  
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
            where k.id_jo='$id_jo' and k.cancel='N' and k.status='M' group by k.id_item
            union all 
            select k.status,0 idsubgroup,k.id_item,l.color,l.size,concat(mi.matclass,' ',mi.goods_code,' ',mi.itemdesc) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,m2.supplier supplier2,k.notes,if(jo.app='W','Waiting','Approved') status_app,
            k.id_supplier,k.id_supplier2,'999' nama_group  
            from bom_jo_item k inner join jo on k.id_jo=jo.id 
            inner join so_det l on k.id_so_det=l.id inner join 
            masteritem mi on k.id_item=mi.id_item inner join mastercf j on mi.matclass=j.cfdesc 
            left join mastersupplier m on k.id_supplier=m.id_supplier
            left join mastersupplier m2 on k.id_supplier2=m2.id_supplier
            where k.id_jo='$id_jo' and k.cancel='N' and k.status='P' group by k.id_item 
            order by nama_group asc";
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
            $bookqty=flookup("sum(qty)","transfer_post","status_app='Y' and status_app_qc='Y' 
              and id_jo_to='$id_jo' and id_item='$data[id_item]'");
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
  						echo "<td>".fn($data['qty_bom'],2)."</td>";
  						echo "<td>".fn($allow,2)."</td>";
              echo "<td>".fn($qtypr,2)."</td>";
              echo "<td>$data[unit]</td>";
  						$id_item_bb=flookup("id_item","masteritem","id_gen='$data[id_item]'");
              if ($id_item_bb!="")
              { $sisa_stock=flookup("stock","stock","id_item='$id_item_bb'"); }
              else
              { $sisa_stock=0; }
              if($bookqty>=$qtypr)
              {
                $wheresup=" and supplier='XXX'";
              }
              else
              {
                $wheresup=" ";
              }
              echo "<td>$sisa_stock</td>";
              echo "<td>$bookqty</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplierarr' style='width: 120px;' name='txtsupplierarr[$keycri]' id='txtsupplierarr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where 
                    tipe_sup='S' and non_aktif='0' $wheresup order by supplier";
                  IsiCombo($sql,$data['id_supplier'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "<td>";
                $keycri=$data['id_item'].":".$id_jo;
                echo "<select class='form-control select2 txtsupplier2arr' style='width: 120px;' name='txtsupplier2arr[$keycri]' id='txtsupplier2arr$keycri'>";
                  $sql="select id_supplier isi,supplier tampil from mastersupplier where 
                    tipe_sup='S' and non_aktif='0' $wheresup order by supplier";
                  IsiCombo($sql,$data['id_supplier2'],'Pilih Supplier');
                echo "</select>";
              echo "</td>";
              echo "
              <td>$data[notes]</td>
              <td>$data[status_app]</td>
              <td>$cekpo</td>";
              if($cekqpo>0)
              { echo "
                <td></td>
                <td></td>";
              }
              else
              { 
                echo "
                <td>
                  <a href='?mod=$mod&idd=$data[id_item]&id=$id_jo&status=$data[status]'
                    data-toggle='tooltip' title='Update'><i class='fa fa-pencil'></i>
                  </a>
                </td>";
                if($sisa_stock>0)
                { echo "
                  <td>
                    <a href='../pur/?mod=5&id=$id_jo'
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
      <i style='background-color:red;color:yellow;'>Full PO Created</i>
      <i style='background-color:gray;color:yellow;'>Not Full PO Created</i>
      <br>
      <button type='submit' class='btn btn-primary'>Simpan</button>
    </form>
  </div>
</div>
<?php } ?>